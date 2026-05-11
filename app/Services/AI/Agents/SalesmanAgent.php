<?php

namespace App\Services\AI\Agents;

use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Prompts\SalesPromptManager;
use App\Services\AI\Context\VehicleContextBuilder;
use App\Services\AI\DTOs\ChatResponseDTO;

use App\Services\AI\Knowledge\DealershipKnowledgeLoader;

class SalesmanAgent
{
    public function __construct(
        protected AIProviderInterface $provider,
        protected SalesPromptManager $promptManager,
        protected VehicleContextBuilder $contextBuilder,
        protected DealershipKnowledgeLoader $knowledgeLoader
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
        // Extract potential search term
        $searchTerm = $this->extractSearchTerm($userInput);
        
        // Extract city if mentioned
        $cityName = $this->extractCity($userInput);
        
        $inventoryContext = $this->contextBuilder->buildInventoryContext($searchTerm, $cityName);
        $generalKnowledge = $this->knowledgeLoader->getGeneralKnowledge();
        
        $systemPrompt = $this->promptManager->getSystemPrompt($inventoryContext, $generalKnowledge);

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
     * Extract a potential vehicle name or keyword from user input.
     */
    protected function extractSearchTerm(string $input): ?string
    {
        // Remove common filler words and return the core keywords
        $input = strtolower($input);
        $ignore = ['do', 'you', 'have', 'the', 'in', 'is', 'best', 'cheapest', 'for', 'me', 'which', 'model', 'any', 'tell', 'about', 'show', 'name', 'price', 'all', 'list', 'and', 'with', 'details'];
        $words = explode(' ', $input);
        $keywords = array_filter($words, fn($w) => !in_array($w, $ignore) && strlen($w) > 1);
        
        return !empty($keywords) ? implode(' ', array_slice($keywords, 0, 4)) : null;
    }

    /**
     * Extract city name from user input by matching against database cities.
     */
    protected function extractCity(string $input): ?string
    {
        $input = strtolower($input);
        $cities = \App\Models\City::pluck('name')->toArray();

        foreach ($cities as $city) {
            if (str_contains($input, strtolower($city))) {
                return $city;
            }
        }

        return null;
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
