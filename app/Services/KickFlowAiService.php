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
                    [
                        'name' => 'create_booking',
                        'description' => 'Membuat pesanan / booking lapangan futsal baru langsung dari percakapan. Mengecek bentrok jadwal dan menyimpan booking.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'field_name' => [
                                    'type' => 'STRING',
                                    'description' => 'Nama lapangan futsal yang ingin dipesan (contoh: "Garuda Sport" atau "Arena Futsal Merdeka")',
                                ],
                                'booking_date' => [
                                    'type' => 'STRING',
                                    'description' => 'Tanggal bermain dalam format YYYY-MM-DD',
                                ],
                                'start_time' => [
                                    'type' => 'STRING',
                                    'description' => 'Jam mulai bermain dalam format HH:MM (contoh: "19:00" atau "20:00")',
                                ],
                                'duration_hours' => [
                                    'type' => 'INTEGER',
                                    'description' => 'Durasi bermain dalam jam (1 sampai 8 jam, default: 1)',
                                ],
                                'notes' => [
                                    'type' => 'STRING',
                                    'description' => 'Catatan tambahan dari pemesan (opsional)',
                                ],
                            ],
                            'required' => ['field_name', 'booking_date', 'start_time'],
                        ],
                    ],
                    [
                        'name' => 'cancel_booking',
                        'description' => 'Membatalkan pemesanan lapangan futsal berdasarkan ID booking.',
                        'parameters' => [
                            'type' => 'OBJECT',
                            'properties' => [
                                'booking_id' => [
                                    'type' => 'INTEGER',
                                    'description' => 'ID atau nomor tiket pemesanan yang ingin dibatalkan',
                                ],
                            ],
                            'required' => ['booking_id'],
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
        $user = $userId ? User::find($userId) : null;

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

        $roleAwareReply = $this->handleRoleAwareConversation($message, $sessionId, $user);

        if ($roleAwareReply !== null) {
            return $roleAwareReply;
        }

        $systemPrompt = $this->buildSystemPrompt($user);

        // Build the initial Gemini API request payload
        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]],
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
            $functionResult = $this->executeFunction($functionName, $functionArgs, $userId);

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
                    'parts' => [['text' => $systemPrompt]],
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

    private function buildSystemPrompt(?User $user): string
    {
        $basePrompt = 'Kamu adalah KickFlow Assistant, asisten futsal berbahasa Indonesia. Bantu pengguna dengan jadwal, lapangan, tarif, booking, dan pembatalan. Gunakan tool untuk data atau aksi. Jangan mengarang data. Jawab ringkas, jelas, dan ramah.';

        if ($user?->role === 'admin') {
            return $basePrompt.' Pengguna adalah admin. Kamu boleh merangkum operasional, tetapi aksi konfirmasi atau pembatalan booking wajib meminta persetujuan eksplisit terlebih dahulu.';
        }

        return $basePrompt.' Pengguna adalah customer atau tamu. Jangan tampilkan data booking milik pengguna lain atau ringkasan operasional admin. Bantu rekomendasi lapangan dan booking miliknya.';
    }

    /**
     * Handle deterministic role-specific flows before using Gemini.
     *
     * @return array{reply: string, function_called: string|null}|null
     */
    private function handleRoleAwareConversation(string $message, string $sessionId, ?User $user): ?array
    {
        $lowerMessage = mb_strtolower(trim($message));

        if (preg_match('/\b(ringkasan|operasional|briefing)\b/', $lowerMessage)) {
            if ($user?->role !== 'admin') {
                return $this->replyForRoleFlow(
                    $sessionId,
                    'Ringkasan operasional khusus admin. Saya bisa bantu cek jadwal, lapangan, atau booking Anda sendiri.',
                    $user?->id,
                    null,
                );
            }

            return $this->replyForRoleFlow(
                $sessionId,
                $this->adminOperationalSummary(),
                $user->id,
                'get_admin_operational_summary',
            );
        }

        if ($user?->role === 'admin') {
            $adminActionReply = $this->handleAdminBookingAction($message, $sessionId, $user);

            if ($adminActionReply !== null) {
                return $adminActionReply;
            }
        }

        if ($user?->role !== 'admin') {
            return $this->handleBookingRecommendation($message, $sessionId, $user?->id);
        }

        return null;
    }

    /**
     * @return array{reply: string, function_called: string|null}|null
     */
    private function handleAdminBookingAction(string $message, string $sessionId, User $admin): ?array
    {
        $lowerMessage = mb_strtolower(trim($message));
        $pendingAction = $this->latestAssistantFlow($sessionId, 'admin_booking_action');

        if ($pendingAction !== null && preg_match('/^(setujui|ya|iya|konfirmasi|lanjutkan)$/', $lowerMessage)) {
            $booking = Booking::find($pendingAction['booking_id'] ?? null);
            $action = $pendingAction['action'] ?? null;

            if (! $booking || ! in_array($action, ['confirm', 'cancel'], true)) {
                return $this->replyForRoleFlow($sessionId, 'Permintaan aksi sudah tidak tersedia. Silakan ulangi perintahnya.', $admin->id, null);
            }

            if ($action === 'confirm') {
                if ($booking->status !== 'pending') {
                    return $this->replyForRoleFlow($sessionId, 'Booking tidak lagi berstatus pending, jadi tidak dapat dikonfirmasi.', $admin->id, null);
                }

                $booking->update(['status' => 'confirmed']);

                return $this->replyForRoleFlow(
                    $sessionId,
                    'Booking #KF-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT).' berhasil dikonfirmasi.',
                    $admin->id,
                    'confirm_booking',
                );
            }

            if ($booking->status === 'completed' || $booking->status === 'cancelled') {
                return $this->replyForRoleFlow($sessionId, 'Booking ini tidak dapat dibatalkan pada status saat ini.', $admin->id, null);
            }

            $booking->update([
                'status' => 'cancelled',
                'payment_status' => $booking->payment_status === 'paid' ? 'refunded' : $booking->payment_status,
            ]);

            return $this->replyForRoleFlow(
                $sessionId,
                'Booking #KF-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT).' berhasil dibatalkan.',
                $admin->id,
                'cancel_booking',
            );
        }

        if (preg_match('/\b(konfirmasi|batalkan|cancel)\b/i', $message) && preg_match('/#?(?:kf-)?(\d+)/i', $message, $matches)) {
            $action = str_contains($lowerMessage, 'konfirmasi') ? 'confirm' : 'cancel';
            $booking = Booking::find((int) $matches[1]);

            if (! $booking) {
                return $this->replyForRoleFlow($sessionId, 'Booking yang diminta tidak ditemukan.', $admin->id, null);
            }

            $label = $action === 'confirm' ? 'mengonfirmasi' : 'membatalkan';

            return $this->replyForRoleFlow(
                $sessionId,
                'Saya akan '.$label.' booking #KF-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT).'. Ketik **Setujui** untuk melanjutkan.',
                $admin->id,
                'request_admin_booking_confirmation',
                ['flow' => 'admin_booking_action', 'action' => $action, 'booking_id' => $booking->id],
            );
        }

        return null;
    }

    /**
     * @return array{reply: string, function_called: string|null}|null
     */
    private function handleBookingRecommendation(string $message, string $sessionId, ?int $userId): ?array
    {
        $lowerMessage = mb_strtolower(trim($message));
        $flow = $this->latestAssistantFlow($sessionId, 'booking_recommendation');

        if ($flow === null && (str_contains($lowerMessage, 'bantu pilihkan') || str_contains($lowerMessage, 'rekomendasi lapangan'))) {
            return $this->replyForRoleFlow(
                $sessionId,
                'Siap. Tanggal bermain kapan? Contoh: hari ini, besok, atau 2026-11-20.',
                $userId,
                'start_booking_recommendation',
                ['flow' => 'booking_recommendation', 'step' => 'date'],
            );
        }

        if ($flow === null) {
            return null;
        }

        $step = $flow['step'] ?? null;
        $data = $flow['data'] ?? [];

        if ($step === 'date') {
            $date = $this->normalizeBookingDate($lowerMessage);

            if ($date === null) {
                return $this->replyForRoleFlow($sessionId, 'Tanggal belum terbaca. Kirim `hari ini`, `besok`, atau format `YYYY-MM-DD`.', $userId, null, $flow);
            }

            return $this->replyForRoleFlow(
                $sessionId,
                'Jam mulai bermain pukul berapa? Contoh: 19:00.',
                $userId,
                'collect_booking_preference',
                ['flow' => 'booking_recommendation', 'step' => 'time', 'data' => ['date' => $date]],
            );
        }

        if ($step === 'time') {
            if (! preg_match('/^(?:[01]?\d|2[0-3])(?::[0-5]\d)?$/', $lowerMessage)) {
                return $this->replyForRoleFlow($sessionId, 'Jam belum valid. Kirim format seperti `19:00`.', $userId, null, $flow);
            }

            $time = strlen($lowerMessage) <= 2 ? sprintf('%02d:00', (int) $lowerMessage) : $lowerMessage;

            return $this->replyForRoleFlow(
                $sessionId,
                'Berapa lama bermain? Contoh: 2 jam.',
                $userId,
                'collect_booking_preference',
                ['flow' => 'booking_recommendation', 'step' => 'duration', 'data' => [...$data, 'time' => $time]],
            );
        }

        if ($step === 'duration') {
            if (! preg_match('/\b([1-8])\s*jam\b/', $lowerMessage, $matches)) {
                return $this->replyForRoleFlow($sessionId, 'Durasi harus 1 sampai 8 jam. Contoh: `2 jam`.', $userId, null, $flow);
            }

            return $this->replyForRoleFlow(
                $sessionId,
                'Budget maksimal per jam berapa? Kirim angka saja, contoh: 200000. Ketik `bebas` bila tidak ada batas.',
                $userId,
                'collect_booking_preference',
                ['flow' => 'booking_recommendation', 'step' => 'budget', 'data' => [...$data, 'duration' => (int) $matches[1]]],
            );
        }

        if ($step === 'budget') {
            $budget = $lowerMessage === 'bebas' ? null : (int) preg_replace('/\D/', '', $lowerMessage);

            if ($budget !== null && $budget <= 0) {
                return $this->replyForRoleFlow($sessionId, 'Budget belum valid. Kirim angka, misalnya `200000`, atau ketik `bebas`.', $userId, null, $flow);
            }

            $recommendations = $this->bookingRecommendations($data, $budget);

            return $this->replyForRoleFlow(
                $sessionId,
                $recommendations,
                $userId,
                'get_booking_recommendations',
            );
        }

        return null;
    }

    /** @return array<string, mixed>|null */
    private function latestAssistantFlow(string $sessionId, string $flow): ?array
    {
        $functionCallData = ChatLog::where('session_id', $sessionId)
            ->where('role', 'assistant')
            ->latest('id')
            ->value('function_call_data');

        if (! is_array($functionCallData) || ($functionCallData['flow'] ?? null) !== $flow) {
            return null;
        }

        return $functionCallData;
    }

    private function normalizeBookingDate(string $message): ?string
    {
        if ($message === 'hari ini') {
            return today()->toDateString();
        }

        if ($message === 'besok') {
            return today()->addDay()->toDateString();
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $message)) {
            return $message;
        }

        return null;
    }

    private function bookingRecommendations(array $preferences, ?int $budget): string
    {
        $query = Field::query()->where('is_active', true);

        if ($budget !== null) {
            $query->where('price_per_hour', '<=', $budget);
        }

        $fields = $query->orderBy('price_per_hour')->get();
        $date = $preferences['date'] ?? today()->toDateString();
        $time = $preferences['time'] ?? '19:00';
        $duration = (int) ($preferences['duration'] ?? 1);
        $endTime = date('H:i', strtotime($time) + ($duration * 3600));

        $availableFields = $fields->filter(function (Field $field) use ($date, $time, $endTime): bool {
            return ! Booking::query()
                ->where('field_id', $field->id)
                ->whereDate('booking_date', $date)
                ->where('status', '!=', 'cancelled')
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $time)
                ->exists();
        })->take(3);

        if ($availableFields->isEmpty()) {
            return 'Belum ada rekomendasi yang cocok untuk '.$date.' pukul '.$time.'. Coba naikkan budget atau ubah jam bermain.';
        }

        $list = $availableFields->map(fn (Field $field): string => '• **'.$field->name.'** ('.$field->type.') — Rp '.number_format((float) $field->price_per_hour, 0, ',', '.').'/jam')->join("\n");

        return "Rekomendasi untuk {$date}, {$time}-{$endTime}, {$duration} jam:\n\n{$list}\n\nMau booking? Kirim: `Pesan lapangan [nama] {$date} jam {$time} selama {$duration} jam`.";
    }

    private function adminOperationalSummary(): string
    {
        $todayBookings = Booking::whereDate('booking_date', today())->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $unpaidBookings = Booking::where('payment_status', 'unpaid')->where('status', '!=', 'cancelled')->count();
        $activeFields = Field::where('is_active', true)->count();

        return "**Ringkasan operasional hari ini**\n• Booking hari ini: {$todayBookings}\n• Menunggu konfirmasi: {$pendingBookings}\n• Belum lunas: {$unpaidBookings}\n• Lapangan aktif: {$activeFields}\n\nKetik `Konfirmasi booking #KF-123` atau `Batalkan booking #KF-123`; saya akan minta persetujuan sebelum mengubah data.";
    }

    /**
     * @param  array<string, mixed>|null  $functionCallData
     * @return array{reply: string, function_called: string|null}
     */
    private function replyForRoleFlow(string $sessionId, string $reply, ?int $userId, ?string $functionCalled, ?array $functionCallData = null): array
    {
        $metadata = $functionCallData ?? ($functionCalled ? ['function_called' => $functionCalled] : null);
        $this->saveMessage($sessionId, 'assistant', $reply, $userId, $metadata);

        return ['reply' => $reply, 'function_called' => $functionCalled];
    }

    /**
     * Execute a named function by querying the appropriate Eloquent model or performing mutations.
     */
    public function executeFunction(string $functionName, array $args, ?int $userId = null): string
    {
        return match ($functionName) {
            'check_field_availability' => $this->checkFieldAvailability($args),
            'get_available_fields' => $this->getAvailableFields($args),
            'get_booking_info' => $this->getBookingInfo($args, $userId),
            'create_booking' => $this->createBooking($args, $userId),
            'cancel_booking' => $this->cancelBooking($args, $userId),
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
    private function getBookingInfo(array $args, ?int $userId): string
    {
        $bookingId = $args['booking_id'] ?? null;
        $userEmail = $args['user_email'] ?? null;

        if (! $bookingId && ! $userEmail) {
            return json_encode(['error' => 'Harap berikan booking_id atau user_email untuk mencari informasi booking.']);
        }

        $user = $userId ? User::find($userId) : null;

        if (! $user) {
            return json_encode(['error' => 'Silakan login untuk melihat informasi booking.']);
        }

        $query = Booking::with(['field', 'user']);

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

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
     * Create a new booking directly via function calling from the AI chatbot.
     *
     * @param  array<string, mixed>  $args
     */
    private function createBooking(array $args, ?int $userId): string
    {
        if (! $userId) {
            return json_encode([
                'status' => 'unauthenticated',
                'error' => 'Pengguna belum login. Untuk memesan lapangan langsung lewat percakapan AI, Anda harus masuk / login ke akun KickFlow terlebih dahulu.',
            ]);
        }

        $fieldName = $args['field_name'] ?? null;
        $bookingDate = $args['booking_date'] ?? null;
        $startTime = $args['start_time'] ?? null;
        $durationHours = max(1, min(8, (int) ($args['duration_hours'] ?? 1)));
        $notes = $args['notes'] ?? 'Dipesan via KickFlow AI Assistant';

        if (! $fieldName || ! $bookingDate || ! $startTime) {
            return json_encode([
                'status' => 'error',
                'error' => 'Data tidak lengkap. Harap sebutkan nama lapangan, tanggal bermain (YYYY-MM-DD), dan jam mulai (HH:MM).',
            ]);
        }

        // Format start time to HH:MM
        $startTime = date('H:i', strtotime($startTime));
        $endTime = date('H:i', strtotime($startTime) + ($durationHours * 3600));

        // Find field
        $field = Field::where('is_active', true)
            ->where(function ($q) use ($fieldName) {
                $q->where('name', 'like', "%{$fieldName}%")
                    ->orWhere('id', $fieldName);
            })
            ->first();

        if (! $field) {
            $availableList = Field::where('is_active', true)->pluck('name')->join(', ');

            return json_encode([
                'status' => 'error',
                'error' => "Lapangan '{$fieldName}' tidak ditemukan. Lapangan aktif yang tersedia: {$availableList}.",
            ]);
        }

        // Prevent booking in the past
        if (strtotime($bookingDate) < strtotime(date('Y-m-d'))) {
            return json_encode([
                'status' => 'error',
                'error' => "Tanggal booking ({$bookingDate}) tidak boleh di masa lalu.",
            ]);
        }

        // Check for double booking conflicts
        $conflict = Booking::where('field_id', $field->id)
            ->whereDate('booking_date', $bookingDate)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($conflict) {
            return json_encode([
                'status' => 'conflict',
                'error' => "Maaf, lapangan {$field->name} pada tanggal {$bookingDate} pukul {$startTime} - {$endTime} WIB sudah terisi. Silakan pilih jam atau tanggal lain.",
            ]);
        }

        $totalPrice = $field->price_per_hour * $durationHours;

        $booking = Booking::create([
            'user_id' => $userId,
            'field_id' => $field->id,
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $durationHours,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $notes,
        ]);

        return json_encode([
            'status' => 'success',
            'message' => 'Pemesanan lapangan futsal berhasil dibuat!',
            'booking_id' => $booking->id,
            'booking_code' => '#KF-'.str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT),
            'field_name' => $field->name,
            'field_type' => $field->type,
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $durationHours,
            'total_price' => $totalPrice,
            'formatted_price' => 'Rp '.number_format($totalPrice, 0, ',', '.'),
            'booking_status' => 'pending',
        ]);
    }

    /**
     * Cancel a booking directly via function calling from the AI chatbot.
     *
     * @param  array<string, mixed>  $args
     */
    private function cancelBooking(array $args, ?int $userId): string
    {
        if (! $userId) {
            return json_encode([
                'status' => 'unauthenticated',
                'error' => 'Pengguna belum login. Untuk membatalkan pemesanan via percakapan AI, Anda harus masuk / login terlebih dahulu.',
            ]);
        }

        $bookingId = (int) ($args['booking_id'] ?? 0);

        if ($bookingId <= 0) {
            return json_encode([
                'status' => 'error',
                'error' => 'Harap berikan nomor / ID booking yang valid.',
            ]);
        }

        $user = User::find($userId);
        $booking = Booking::with('field')->find($bookingId);

        if (! $booking) {
            return json_encode([
                'status' => 'error',
                'error' => 'Pemesanan dengan nomor #KF-'.str_pad((string) $bookingId, 5, '0', STR_PAD_LEFT).' tidak ditemukan.',
            ]);
        }

        // Ownership / permission check
        if ($booking->user_id !== $userId && ($user?->role !== 'admin')) {
            return json_encode([
                'status' => 'forbidden',
                'error' => 'Anda tidak memiliki akses untuk membatalkan pemesanan milik orang lain.',
            ]);
        }

        // Status check
        if ($booking->status === 'cancelled') {
            return json_encode([
                'status' => 'already_cancelled',
                'message' => 'Pemesanan #KF-'.str_pad((string) $bookingId, 5, '0', STR_PAD_LEFT).' sudah dibatalkan sebelumnya.',
            ]);
        }

        if ($booking->status === 'completed') {
            return json_encode([
                'status' => 'error',
                'error' => 'Pemesanan yang sudah selesai dimainkan tidak dapat dibatalkan.',
            ]);
        }

        $booking->update(['status' => 'cancelled']);

        return json_encode([
            'status' => 'success',
            'message' => 'Pemesanan berhasil dibatalkan.',
            'booking_code' => '#KF-'.str_pad((string) $bookingId, 5, '0', STR_PAD_LEFT),
            'field_name' => $booking->field->name ?? 'Lapangan',
            'booking_date' => $booking->booking_date->format('Y-m-d'),
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

        // Direct Cancel Booking Request (e.g. "batalkan booking #KF-00001" or "cancel booking 2")
        if (preg_match('/(?:batalkan|cancel)\s+(?:booking|pesanan)?\s*#?(?:kf-)?(\d+)/i', $lower, $m)) {
            $functionCalled = 'cancel_booking';
            $cancelResult = json_decode($this->cancelBooking(['booking_id' => (int) $m[1]], $userId), true);

            if (($cancelResult['status'] ?? '') === 'success') {
                $reply = "✅ Pemesanan {$cancelResult['booking_code']} ({$cancelResult['field_name']}) berhasil dibatalkan sesuai instruksi Anda. Status pemesanan kini telah diperbarui. ℹ️";
            } elseif (($cancelResult['status'] ?? '') === 'unauthenticated') {
                $reply = '🔒 Anda harus masuk / login terlebih dahulu ke akun KickFlow untuk membatalkan pemesanan via asisten AI.';
            } else {
                $reply = '⚠️ '.($cancelResult['error'] ?? $cancelResult['message'] ?? 'Gagal membatalkan pemesanan.');
            }
        }
        // Direct Create Booking Request via Chat (e.g. "pesan lapangan garuda besok jam 19:00" or "booking lapangan merdeka jam 20:00")
        elseif (preg_match('/(?:booking|pesan|sewa)\s+(?:lapangan\s+)?([a-z0-9\s]+?)\s+(?:(hari ini|besok|\d{4}-\d{2}-\d{2})\s+)?(?:jam|pukul)\s*(\d{1,2}(?:[:.]\d{2})?)/i', $lower, $matches)) {
            $functionCalled = 'create_booking';

            if (! $userId) {
                $reply = '🔒 Anda harus login terlebih dahulu untuk melakukan pemesanan lapangan langsung via chat. Silakan klik menu "Masuk" di kanan atas!';
            } else {
                $targetField = trim($matches[1]);
                $dateWord = $matches[2] ?? 'besok';
                $timeInput = str_replace('.', ':', $matches[3]);
                if (strlen($timeInput) <= 2) {
                    $timeInput = sprintf('%02d:00', (int) $timeInput);
                }

                $bookingDate = match ($dateWord) {
                    'hari ini' => now()->format('Y-m-d'),
                    'besok' => now()->addDay()->format('Y-m-d'),
                    default => preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateWord) ? $dateWord : now()->addDay()->format('Y-m-d'),
                };

                // Check duration if mentioned (e.g. "selama 2 jam")
                $duration = 1;
                if (preg_match('/(?:selama|durasi)?\s*(\d+)\s*jam/i', $lower, $durMatch)) {
                    $duration = max(1, min(8, (int) $durMatch[1]));
                }

                $bookResult = json_decode($this->createBooking([
                    'field_name' => $targetField,
                    'booking_date' => $bookingDate,
                    'start_time' => $timeInput,
                    'duration_hours' => $duration,
                    'notes' => 'Pemesanan langsung via KickFlow AI Assistant',
                ], $userId), true);

                if (($bookResult['status'] ?? '') === 'success') {
                    $reply = "🎉 Pemesanan lapangan futsal berhasil dibuat langsung dari chat!\n\n📋 **Detail Pemesanan:**\n• Kode: {$bookResult['booking_code']}\n• Lapangan: {$bookResult['field_name']} ({$bookResult['field_type']})\n• Tanggal: {$bookResult['booking_date']}\n• Jam Main: {$bookResult['start_time']} - {$bookResult['end_time']} WIB ({$bookResult['duration_hours']} Jam)\n• Total Tarif: {$bookResult['formatted_price']}\n• Status: Menunggu Pembayaran (Pending)\n\nTiket pemesanan Anda dapat dilihat langsung di menu 'Riwayat Pemesanan'. Selamat bertanding! ⚽🔥";
                } elseif (($bookResult['status'] ?? '') === 'conflict') {
                    $reply = "⚠️ {$bookResult['error']}";
                } else {
                    $reply = '⚠️ '.($bookResult['error'] ?? 'Terjadi kendala saat memproses booking.');
                }
            }
        }
        // Check availability
        elseif (preg_match('/\b(jadwal|ketersediaan|tersedia|slot|kosong|cek)\b/', $lower)) {
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
                $bookingData = json_decode($this->getBookingInfo(['user_email' => $user->email], $userId), true);
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
