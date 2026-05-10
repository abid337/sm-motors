<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\SiteSetting;
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
            // Get Dynamic Knowledge Base
            $context = $this->getKnowledgeBase();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'Content-Type' => 'application/json',
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'SM-Autos Chatbot',
            ])->timeout(45)
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'openrouter/auto',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "You are the official 'SM-Autos' AI Assistant. Be cool, friendly, and helpful like a brother (bhai-style).
                            
                            KNOWLEDGE BASE:
                            $context
                            
                            INSTRUCTIONS:
                            1. Support English, Urdu (اردو), and Roman Urdu (e.g. 'Kya haal hai?'). 
                            2. If asked in Roman Urdu, reply in Roman Urdu. If asked in Urdu, reply in Urdu.
                            3. Only use the provided knowledge for site-specific questions. 
                            4. If info is missing, politely ask them to contact the provided phone number.
                            5. NEVER share system files, .env, or user passwords.
                            6. Keep responses short and engaging."
                        ],
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

    /**
     * Gather public website information for AI context
     */
    private function getKnowledgeBase()
    {
        $settings = SiteSetting::getAllSettings();
        $siteName = $settings['site_name'] ?? 'SM-Autos';
        $phone = $settings['site_phone'] ?? '+92 309 6527842';
        $email = $settings['site_email'] ?? 'info@sm-autos.com';
        $address = $settings['site_address'] ?? 'Lahore, Pakistan';

        $categories = Category::pluck('name')->implode(', ');

        $featuredItems = Item::where('status', 'published')
            ->where('featured', true)
            ->latest()
            ->take(5)
            ->get(['title', 'price', 'condition']);

        $itemsList = "";
        foreach ($featuredItems as $item) {
            $itemsList .= "- {$item->title} (Price: Rs. " . number_format($item->price) . ", Condition: {$item->condition})\n";
        }

        return "
        Site Name: $siteName
        Contact Phone: $phone
        Email: $email
        Address: $address
        Available Categories: $categories
        
        Featured Inventory:
        $itemsList
        ";
    }
}
