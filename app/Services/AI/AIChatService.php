<?php

namespace App\Services\AI;

use App\Services\AI\Agents\SalesmanAgent;
use App\Services\AI\Actions\GenerateLeadAction;
use App\Services\AI\DTOs\ChatResponseDTO;
use Illuminate\Support\Facades\Session;

class AIChatService
{
    public function __construct(
        protected SalesmanAgent $agent,
        protected GenerateLeadAction $leadAction
    ) {}

    /**
     * Process a user message and return the AI response.
     *
     * @param string $message
     * @return array
     */
    public function getReply(string $message): array
    {
        // Retrieve conversation history from session
        $history = Session::get('ai_chat_history', []);

        // Get AI response
        $response = $this->agent->chat($message, $history);

        if ($response->success) {
            // Update history
            $history[] = ['role' => 'user', 'content' => $message];
            $history[] = ['role' => 'assistant', 'content' => $response->reply];
            
            // Keep history manageable (last 10 messages)
            if (count($history) > 10) {
                array_shift($history);
                array_shift($history);
            }
            
            Session::put('ai_chat_history', $history);

            // Handle Lead Generation
            if ($response->isLeadGenerated) {
                $this->handleLead($message);
            }
        }

        return $response->toArray();
    }

    /**
     * Extract contact info and save lead.
     */
    protected function handleLead(string $message): void
    {
        // Basic extraction (can be improved with another AI call if needed)
        preg_match('/[0-9]{7,15}/', $message, $phoneMatches);
        preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}/i', $message, $emailMatches);

        $this->leadAction->execute([
            'phone' => $phoneMatches[0] ?? null,
            'email' => $emailMatches[0] ?? null,
            'last_input' => $message
        ]);
    }

    /**
     * Reset the conversation history.
     */
    public function reset(): void
    {
        Session::forget('ai_chat_history');
    }
}
