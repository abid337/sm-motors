<?php

namespace App\Services\AI\Agents;

use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Prompts\SalesPromptManager;
use App\Services\AI\Context\VehicleContextBuilder;
use App\Services\AI\DTOs\ChatResponseDTO;

class SalesmanAgent
{
    public function __construct(
        protected AIProviderInterface $provider,
        protected SalesPromptManager $promptManager,
        protected VehicleContextBuilder $contextBuilder
    ) {}

    /**
     * Handle the chat interaction.
     *
     * @param string $userInput
     * @param array $history
     * @return ChatResponseDTO
     */
    public function chat(string $userInput, array $history = []): ChatResponseDTO
    {
        $inventoryContext = $this->contextBuilder->buildInventoryContext();
        $systemPrompt = $this->promptManager->getSystemPrompt($inventoryContext);

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $history,
            [['role' => 'user', 'content' => $userInput]]
        );

        $reply = $this->provider->generateResponse($messages);

        return new ChatResponseDTO(
            success: !empty($reply),
            reply: $reply,
            isLeadGenerated: $this->detectLeadIntent($reply, $userInput)
        );
    }

    /**
     * Simple logic to detect if a lead was likely generated in the conversation.
     */
    protected function detectLeadIntent(string $reply, string $userInput): bool
    {
        // Check if user provided a phone number or email in their input
        // Or if the AI confirmed a lead was booked
        $hasContact = preg_match('/[0-9]{7,15}/', $userInput) || str_contains($userInput, '@');
        $isConfirming = str_contains(strtolower($reply), 'booked') || str_contains(strtolower($reply), 'consultation');

        return $hasContact && $isConfirming;
    }
}
