<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'SM-Autos Chatbot',
            ])->timeout(45)
                ->post('https://openrouter.ai/api/v1/chat/completions', [

                    'model' => 'google/gemma-7b-it:free',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are SM-Autos vehicle assistant. Be polite and helpful.'],
                        ['role' => 'user', 'content' => $request->message]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();


                $botReply = $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.';

                return response()->json([
                    'success' => true,
                    'reply' => $botReply
                ]);
            }


            $errorData = $response->json();
            return response()->json([
                'success' => false,
                'reply' => 'Chat Error: ' . ($errorData['error']['message'] ?? 'Provider issue')
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'reply' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
