<?php

namespace App\Services\AI\Actions;

use App\Models\Inquiry;
use Illuminate\Support\Facades\Log;

class GenerateLeadAction
{
    /**
     * Create a lead from AI conversation.
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data): void
    {
        try {
            Inquiry::create([
                'name' => $data['name'] ?? 'AI Chat Lead',
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'message' => "Lead generated from AI Chatbot. User Input: {$data['last_input']}",
                'item_id' => $data['item_id'] ?? null,
            ]);

            Log::info('AI Lead Generated', ['data' => $data]);
            
            // Here you could also trigger a notification email to the sales team
        } catch (\Exception $e) {
            Log::error('Lead Generation Failed', ['error' => $e->getMessage()]);
        }
    }
}
