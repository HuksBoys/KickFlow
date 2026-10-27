<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ChatLog;
use App\Models\Field;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KickFlowAiService
{
    private string $apiKey;

    private string $model;

    private string $baseUrl;

    /** System prompt in Bahasa Indonesia for KickFlow Assistant. */
    private string $systemPrompt = 'Kamu adalah KickFlow Assistant, asisten pemesanan lapangan futsal yang ramah dan membantu. Kamu dapat membantu pengguna untuk: melihat ketersediaan lapangan, mengecek jadwal, memberikan informasi harga, dan panduan pemesanan. Selalu jawab dalam Bahasa Indonesia yang sopan dan ramah. Jika ada pertanyaan tentang ketersediaan, gunakan tools yang tersedia untuk mengecek data real-time dari database.';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', '');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
        $this->baseUrl = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta');
    }

    /**
     * Build the Gemini function tool declarations for field/booking operations.
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildFunctionTools(): array
    {
        return [
            [
                'function_declarations' => [
                    [
                        'name' => 'check_field_availability',
                        'description' => 'Mengecek ketersediaan lapangan futsal berdasarkan tanggal. Mengembalikan daftar slot yang tersedia atau sudah dipesan.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'date' => [
                                    'type' => 'STRING',
                                    'description' => 'Tanggal yang ingin dicek (format: YYYY-MM-DD)',
                                ],
                                'field_name' => [
                                    'type' => 'STRING',
                                    'description' => 'Nama lapangan (opsional, untuk filter lapangan tertentu)',
                                ],
                            ],
                            'required' => ['date'],
                        ],
                    ],
                    [
                        'name' => 'get_available_fields',
                        'description' => 'Mendapatkan daftar semua lapangan futsal yang aktif dan tersedia beserta harga dan informasinya.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'type' => [
                                    'type' => 'STRING',
                                    'description' => 'Jenis lapangan: "indoor" atau "outdoor" (opsional)',
                                ],
                            ],
                            'required' => [],
                        ],
                    ],
                    [
                        'name' => 'get_booking_info',
                        'description' => 'Mendapatkan informasi pemesanan lapangan berdasarkan ID booking atau email pengguna.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'booking_id' => [
                                    'type' => 'INTEGER',
                                    'description' => 'ID booking yang ingin dicek (opsional)',
                                ],
                                'user_email' => [
                                    'type' => 'STRING',
                                    'description' => 'Email pengguna untuk melihat semua bookingnya (opsional)',
                                ],
                            ],
                            'required' => [],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Send a chat message to Gemini and return the assistant's reply.
     *
     * Loads conversation history from chat_logs, calls Gemini API with function
     * calling support, handles function call responses, and saves all messages.
     *
     * @return array{reply: string, function_called: string|null}
     */
    public function chat(string $message, string $sessionId, ?int $userId = null): array
    {
        // Load the last 10 messages for conversation history
        $history = ChatLog::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        // Build the contents array from conversation history
        $contents = [];
        foreach ($history as $log) {
            $role = $log->role === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $log->message]],
            ];
        }

        // Append the current user message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]],
        ];

        // Save the user message to chat_logs
        $this->saveMessage($sessionId, 'user', $message, $userId);

        // Build the initial Gemini API request payload
        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $this->systemPrompt]],
            ],
            'contents' => $contents,
            'tools' => $this->buildFunctionTools(),
        ];

        // If no API key is set or key is a placeholder, use intelligent local fallback
        if (empty($this->apiKey) || $this->apiKey === 'your-gemini-api-key-here') {
            return $this->handleLocalFallback($message, $sessionId, $userId);
        }

        $url = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";

        $response = Http::timeout(10)->post($url, $payload);

        if ($response->failed()) {
            Log::warning('Gemini API request failed, falling back to local assistant', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $this->handleLocalFallback($message, $sessionId, $userId);
        }

        $responseData = $response->json();
        $functionCalled = null;

        // Check if Gemini wants to call a function
        $candidate = $responseData['candidates'][0] ?? null;
        $parts = $candidate['content']['parts'] ?? [];
        $functionCallPart = null;

        foreach ($parts as $part) {
            if (isset($part['functionCall'])) {
                $functionCallPart = $part['functionCall'];
                break;
            }
        }

        if ($functionCallPart !== null) {
            $functionName = $functionCallPart['name'];
            $functionArgs = $functionCallPart['args'] ?? [];
            $functionCalled = $functionName;

            // Execute the requested function against the database
            $functionResult = $this->executeFunction($functionName, $functionArgs);

            // Build follow-up contents: append model's function call + user's function response
            $followUpContents = $contents;
            $followUpContents[] = [
                'role' => 'model',
                'parts' => [['functionCall' => ['name' => $functionName, 'args' => $functionArgs]]],
            ];
            $followUpContents[] = [
                'role' => 'user',
                'parts' => [
                    [
                        'functionResponse' => [
                            'name' => $functionName,
                            'response' => ['result' => $functionResult],
                        ],
                    ],
                ],
            ];

            $followUpPayload = [
                'system_instruction' => [
                    'parts' => [['text' => $this->systemPrompt]],
                ],
                'contents' => $followUpContents,
                'tools' => $this->buildFunctionTools(),
            ];

            $followUpResponse = Http::post($url, $followUpPayload);

            if ($followUpResponse->failed()) {
                Log::error('Gemini follow-up API request failed', [
                    'status' => $followUpResponse->status(),
                    'body' => $followUpResponse->body(),
                ]);

                $errorReply = 'Maaf, terjadi kesalahan saat memproses hasil pencarian. Silakan coba lagi.';
                $this->saveMessage($sessionId, 'assistant', $errorReply, $userId, [
                    'function_called' => $functionName,
                    'function_args' => $functionArgs,
                ]);

                return ['reply' => $errorReply, 'function_called' => $functionCalled];
            }

            $responseData = $followUpResponse->json();
        }

        // Extract the final text reply from Gemini
        $replyText = $this->extractTextFromResponse($responseData);

        // Save the assistant reply with optional function call metadata
        $functionCallData = $functionCalled !== null ? [
            'function_called' => $functionCalled,
            'function_args' => $functionCallPart['args'] ?? [],
        ] : null;

        $this->saveMessage($sessionId, 'assistant', $replyText, $userId, $functionCallData);

        return [
            'reply' => $replyText,
            'function_called' => $functionCalled,
        ];
    }

    /**
     * Execute a named function by querying the appropriate Eloquent model.
     */
    public function executeFunction(string $functionName, array $args): string
    {
        return match ($functionName) {
            'check_field_availability' => $this->checkFieldAvailability($args),
            'get_available_fields' => $this->getAvailableFields($args),
            'get_booking_info' => $this->getBookingInfo($args),
            default => json_encode(['error' => "Function '{$functionName}' tidak dikenal."]),
        };
    }

    /**
     * Query schedules to check field availability for a given date.
     *
     * @param  array<string, mixed>  $args
     */
    private function checkFieldAvailability(array $args): string
    {
        $date = $args['date'] ?? null;
        $fieldName = $args['field_name'] ?? null;

        if (! $date) {
            return json_encode(['error' => 'Parameter tanggal diperlukan.']);
        }

        $query = Schedule::with('field')
            ->whereDate('date', $date);

        if ($fieldName) {
            $query->whereHas('field', function ($q) use ($fieldName) {
                $q->where('name', 'like', "%{$fieldName}%");
            });
        }

        $schedules = $query->get()->map(function (Schedule $schedule) {
            return [
                'field_name' => $schedule->field->name ?? 'Unknown',
                'field_type' => $schedule->field->type ?? 'Unknown',
                'date' => $schedule->date->format('Y-m-d'),
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'is_available' => $schedule->is_available,
                'price_per_hour' => $schedule->field->price_per_hour ?? 0,
            ];
        });

        if ($schedules->isEmpty()) {
            return json_encode([
                'message' => "Tidak ada jadwal yang ditemukan untuk tanggal {$date}.",
                'schedules' => [],
            ]);
        }

        return json_encode([
            'date' => $date,
            'total_slots' => $schedules->count(),
            'available_slots' => $schedules->where('is_available', true)->count(),
            'schedules' => $schedules->values(),
        ]);
    }

    /**
     * Query active fields, optionally filtered by type.
     *
     * @param  array<string, mixed>  $args
     */
    private function getAvailableFields(array $args): string
    {
        $type = $args['type'] ?? null;

        $query = Field::where('is_active', true);

        if ($type) {
            $query->where('type', $type);
        }

        $fields = $query->get()->map(function (Field $field) {
            return [
                'id' => $field->id,
                'name' => $field->name,
                'type' => $field->type,
                'price_per_hour' => $field->price_per_hour,
                'description' => $field->description ?? '',
            ];
        });

        if ($fields->isEmpty()) {
            return json_encode([
                'message' => 'Tidak ada lapangan yang tersedia saat ini.',
                'fields' => [],
            ]);
        }

        return json_encode([
            'total_fields' => $fields->count(),
            'fields' => $fields->values(),
        ]);
    }

    /**
     * Query bookings by booking ID or user email.
     *
     * @param  array<string, mixed>  $args
     */
    private function getBookingInfo(array $args): string
    {
        $bookingId = $args['booking_id'] ?? null;
        $userEmail = $args['user_email'] ?? null;

        if (! $bookingId && ! $userEmail) {
            return json_encode(['error' => 'Harap berikan booking_id atau user_email untuk mencari informasi booking.']);
        }

        $query = Booking::with(['field', 'user']);

        if ($bookingId) {
            $query->where('id', $bookingId);
        }

        if ($userEmail) {
            $query->whereHas('user', function ($q) use ($userEmail) {
                $q->where('email', $userEmail);
            });
        }

        $bookings = $query->limit(10)->get()->map(function (Booking $booking) {
            return [
                'id' => $booking->id,
                'field_name' => $booking->field->name ?? 'Unknown',
                'booking_date' => $booking->booking_date->format('Y-m-d'),
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'duration_hours' => $booking->duration_hours,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'user_name' => $booking->user->name ?? 'Unknown',
            ];
        });

        if ($bookings->isEmpty()) {
            return json_encode(['message' => 'Tidak ada booking yang ditemukan.', 'bookings' => []]);
        }

        return json_encode([
            'total_bookings' => $bookings->count(),
            'bookings' => $bookings->values(),
        ]);
    }

    /**
     * Extract the plain-text reply from a Gemini API response.
     *
     * @param  array<string, mixed>  $responseData
     */
    private function extractTextFromResponse(array $responseData): string
    {
        $parts = $responseData['candidates'][0]['content']['parts'] ?? [];

        foreach ($parts as $part) {
            if (isset($part['text'])) {
                return $part['text'];
            }
        }

        return 'Maaf, saya tidak dapat memberikan respons saat ini. Silakan coba lagi.';
    }

    /**
     * Intelligent local fallback using real database queries when Gemini API is offline or unconfigured.
     *
     * @return array{reply: string, function_called: string|null}
     */
    private function handleLocalFallback(string $message, string $sessionId, ?int $userId): array
    {
        $lower = mb_strtolower($message);
        $functionCalled = null;
        $reply = '';

        // Check availability
        if (preg_match('/\b(jadwal|ketersediaan|tersedia|slot|kosong|cek)\b/', $lower)) {
            $functionCalled = 'check_field_availability';
            $today = now()->format('Y-m-d');
            $data = json_decode($this->checkFieldAvailability(['date' => $today]), true);

            $availableCount = $data['available_slots'] ?? 0;
            $totalCount = $data['total_slots'] ?? 0;

            if ($totalCount > 0) {
                $reply = "Untuk hari ini ({$today}), terdapat {$availableCount} dari {$totalCount} slot waktu yang tersedia! Anda dapat memesan langsung melalui halaman Lapangan. 🏟️";
            } else {
                $fields = Field::where('is_active', true)->pluck('name')->join(', ');
                $reply = "Jadwal lapangan terbuka setiap hari mulai pukul 07:00 hingga 23:00. Lapangan aktif kami: {$fields}. Silakan pilih tanggal di halaman pemesanan! 📅";
            }
        }
        // List fields
        elseif (preg_match('/\b(lapangan|field|daftar|tipe|indoor|outdoor)\b/', $lower)) {
            $functionCalled = 'get_available_fields';
            $fields = Field::where('is_active', true)->get();
            $list = $fields->map(fn ($f) => "• {$f->name} ({$f->type}) - Rp ".number_format((float) $f->price_per_hour, 0, ',', '.').'/jam')->join("\n");
            $reply = "Berikut adalah daftar lapangan futsal yang tersedia di KickFlow:\n\n{$list}\n\nSilakan pilih lapangan favorit Anda untuk melihat jadwal dan melakukan pemesanan! ⚽";
        }
        // Pricing
        elseif (preg_match('/\b(harga|tarif|biaya|bayar|price|cost)\b/', $lower)) {
            $minPrice = Field::where('is_active', true)->min('price_per_hour');
            $maxPrice = Field::where('is_active', true)->max('price_per_hour');
            $minFormatted = number_format((float) $minPrice, 0, ',', '.');
            $maxFormatted = number_format((float) $maxPrice, 0, ',', '.');

            $reply = "Harga sewa lapangan di KickFlow berkisar antara Rp {$minFormatted} hingga Rp {$maxFormatted} per jam, tergantung fasilitas dan jenis lapangan (indoor/outdoor). 💰";
        }
        // Booking info
        elseif (preg_match('/\b(status|booking saya|pesanan saya|riwayat)\b/', $lower) && $userId) {
            $functionCalled = 'get_booking_info';
            $user = User::find($userId);
            if ($user) {
                $bookingData = json_decode($this->getBookingInfo(['user_email' => $user->email]), true);
                $count = $bookingData['total_bookings'] ?? 0;
                $reply = "Anda memiliki {$count} riwayat pemesanan di KickFlow. Anda dapat melihat detailnya di menu 'Riwayat Pemesanan'. 📋";
            } else {
                $reply = 'Silakan login terlebih dahulu untuk melihat informasi pemesanan Anda. 🔒';
            }
        }
        // How to book
        elseif (preg_match('/\b(cara|pesan|booking|book|reservasi|panduan)\b/', $lower)) {
            $reply = "Cara memesan lapangan di KickFlow sangat mudah:\n1️⃣ Buka menu 'Lapangan' dan pilih lapangan yang Anda inginkan\n2️⃣ Tentukan tanggal dan jam bermain\n3️⃣ Isi formulir booking dan catatan jika ada\n4️⃣ Konfirmasi pemesanan Anda!\n\nApakah ada lapangan tertentu yang ingin Anda tanyakan? 🎯";
        }
        // Greetings
        elseif (preg_match('/\b(halo|hai|hi|hello|selamat|pagi|siang|sore|malam)\b/', $lower)) {
            $reply = 'Halo! Selamat datang di KickFlow Assistant 👋 Saya adalah asisten pintar untuk pemesanan lapangan futsal. Ada yang bisa saya bantu terkait jadwal, harga, atau ketersediaan lapangan? 😊';
        }
        // Cancellation
        elseif (preg_match('/\b(batal|cancel|batalkan)\b/', $lower)) {
            $reply = "Untuk membatalkan booking yang berstatus 'Pending', Anda dapat masuk ke menu 'Riwayat Pemesanan' dan mengklik tombol 'Batalkan'. Pembatalan tidak dikenakan biaya tambahan. ℹ️";
        }
        // Thanks
        elseif (preg_match('/\b(terima kasih|makasih|thanks|thank you)\b/', $lower)) {
            $reply = 'Sama-sama! Senang bisa membantu Anda. Selamat bermain futsal dan jaga sportivitas! ⚽🔥';
        }
        // Default
        else {
            $reply = "Halo! Saya KickFlow Assistant. Saya dapat membantu Anda dengan:\n• Cek jadwal dan ketersediaan lapangan\n• Informasi daftar lapangan dan harga\n• Panduan cara melakukan booking\n• Cek status pemesanan Anda\n\nSilakan tanyakan apa saja seputar pemesanan lapangan futsal! ⚽";
        }

        $functionCallData = $functionCalled !== null ? ['function_called' => $functionCalled, 'mode' => 'local_fallback'] : null;
        $this->saveMessage($sessionId, 'assistant', $reply, $userId, $functionCallData);

        return [
            'reply' => $reply,
            'function_called' => $functionCalled,
        ];
    }

    /**
     * Persist a chat message to the chat_logs table.

     *
     * @param  array<string, mixed>|null  $functionCallData
     */
    private function saveMessage(
        string $sessionId,
        string $role,
        string $message,
        ?int $userId,
        ?array $functionCallData = null
    ): void {
        ChatLog::create([
            'session_id' => $sessionId,
            'role' => $role,
            'message' => $message,
            'user_id' => $userId,
            'function_call_data' => $functionCallData,
        ]);
    }
}
