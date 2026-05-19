<?php

namespace App\Services\AI;

use App\Models\City;
use App\DTOs\AISearchCriteria;
use App\Services\AI\Contracts\AIProviderInterface;

class AISearchService
{
    public function __construct(
        protected AIProviderInterface $provider
    ) {}

    public function extractQuery(string $query): AISearchCriteria
    {
        $priceRangesJson = json_encode(config('search.price_ranges', []), JSON_PRETTY_PRINT);
        $categoriesJson = json_encode(config('search.categories', []), JSON_PRETTY_PRINT);

        $systemPrompt = "You are 'SM-Autos AI', a highly sophisticated entity extraction model for a premium Pakistani vehicle portal.
Your task is to analyze the user's natural language search query and accurately map it to our structured criteria.

Extract these 4 entities:
1. 'category': Map strictly to one of these exact strings or null: {$categoriesJson}.
   - Smart Mapping: If user mentions 'scooty', 'motorcycle', '70cc', map to 'bikes'. If they say 'suv', 'prado', 'vitz', map to 'cars'.

2. 'keyword': The specific brand, model, or conceptual feature (e.g., 'Honda Civic', 'Mehran', 'Vespa').
   - CRITICAL (Stop Word Stripping): Do NOT include generic words like 'car', 'bike', 'gari', 'motorcycle', 'new', 'used' in the keyword.
   - PAKISTANI SLANGS & MISSPELLINGS: Correct common typing mistakes or local names on the fly (e.g., 'civic under 50 lacs' -> keyword is 'Civic'. 'sasti gari' -> keyword is null. 'fuel efficient' -> keyword is 'fuel efficient' or 'hybrid').
   - CONTEXT CONVERSION: If user mentions 'family car' or '7 seater', convert keyword to '7 seater' or 'SUV' to match listing tags.

3. 'city': Extract the standard city name (e.g., 'Lahore', 'Karachi', 'Islamabad'). Correct Roman Urdu names (e.g., 'lhr' -> 'Lahore', 'isb' -> 'Islamabad', 'khi' -> 'Karachi'). Never use short forms.

4. 'price_range': Calculate values based on Pakistani terminology (1 Lac = 100,000 | 1 Crore = 10,000,000).
   Map strictly to ONE of these exact JSON keys:
{$priceRangesJson}

If an entity is not mentioned or cannot be confidently extracted, return null for that key.
Return ONLY a raw, valid JSON object with the keys: 'category', 'keyword', 'city', 'price_range'. Do NOT include any markdown blocks (like ```json), no backticks, no conversational fillers, and no explanations.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $query]
        ];

        try {
            $response = $this->provider->generateResponse($messages);
            
            // Clean markdown wrapper bulletproof
            $response = preg_replace('/^```json\s*|\s*```$/i', '', trim($response));
            $response = preg_replace('/^```\s*|\s*```$/i', '', trim($response));
            
            $data = json_decode(trim($response), true);
            
            if (!$data) {
                return new AISearchCriteria(keyword: $query);
            }

            $cityId = null;
            $rawCityName = null;
            
            if (!empty($data['city'])) {
                $rawCityName = $data['city'];
                // 1. Try exact match using LOWER
                $cityId = City::whereRaw('LOWER(name) = ?', [strtolower($data['city'])])->value('id');
                
                // 2. Fallback to LIKE query if not found
                if (!$cityId) {
                    $cityId = City::where('name', 'like', '%' . $data['city'] . '%')->value('id');
                }
            }

            return AISearchCriteria::fromAIArray($data, $cityId, $rawCityName);

        } catch (\Exception $e) {
            // Fallback to simple keyword search
            return new AISearchCriteria(keyword: $query);
        }
    }
}
