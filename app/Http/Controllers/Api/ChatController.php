<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatRequest;
use App\Services\KickFlowAiService;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __construct(private readonly KickFlowAiService $aiService) {}

    /**
     * Handle an incoming chat message and return the AI assistant's reply.
     *
     * POST /api/chat
     * Rate limited to 30 requests per minute per session.
     */
    public function chat(StoreChatRequest $request): JsonResponse
    {
        $result = $this->aiService->chat(
            message: $request->validated('message'),
            sessionId: $request->validated('session_id'),
            userId: auth()->id(),
        );

        return response()->json([
            'reply' => $result['reply'],
            'function_called' => $result['function_called'],
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
