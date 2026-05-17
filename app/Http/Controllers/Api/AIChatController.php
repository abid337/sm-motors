<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AI\AIChatService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIChatController extends Controller
{
    public function __construct(
        protected AIChatService $chatService
    ) {}

    /**
     * Handle the chat request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function message(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        

        $result = $this->chatService->getReply($request->message);

        return response()->json($result);
    }

    /**
     * Clear the conversation history.
     *
     * @return JsonResponse
     */
    public function reset(): JsonResponse
    {
        $this->chatService->reset();

        return response()->json([
            'success' => true,
            'message' => 'Conversation history cleared.'
        ]);
    }
}
