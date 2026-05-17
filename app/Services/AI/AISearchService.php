<?php

namespace App\Services\AI;

use App\Models\City;
use App\Services\AI\Contracts\AIProviderInterface;

class AISearchService
{
    public function __construct(
        protected AIProviderInterface $provider
    ) {}

    public function extractQuery(string $query): array
    {
        $systemPrompt = "You are 'SM-Autos AI', a highly sophisticated entity extraction model for a premium Pakistani vehicle portal.
Your task is to analyze the user's natural language search query and accurately map it to our structured criteria.

Extract these 4 entities:
1. 'category': Map strictly to one of these exact strings or null: 'new-cars', 'used-cars', 'new-bikes', 'used-bikes', 'cars', or 'bikes'.
   - Smart Mapping: If user mentions 'scooty', 'motorcycle', '70cc', map to 'bikes'. If they say 'suv', 'prado', 'vitz', map to 'cars'.

2. 'keyword': The specific brand, model, or conceptual feature (e.g., 'Honda Civic', 'Mehran', 'Vespa').
   - CRITICAL (Stop Word Stripping): Do NOT include generic words like 'car', 'bike', 'gari', 'motorcycle', 'new', 'used' in the keyword.
   - PAKISTANI SLANGS & MISSPELLINGS: Correct common typing mistakes or local names on the fly (e.g., 'civic under 50 lacs' -> keyword is 'Civic'. 'sasti gari' -> keyword is null. 'fuel efficient' -> keyword is 'fuel efficient' or 'hybrid').
   - CONTEXT CONVERSION: If user mentions 'family car' or '7 seater', convert keyword to '7 seater' or 'SUV' to match listing tags.

3. 'city': Extract the standard city name (e.g., 'Lahore', 'Karachi', 'Islamabad'). Correct Roman Urdu names (e.g., 'lhr' -> 'Lahore', 'isb' -> 'Islamabad', 'khi' -> 'Karachi').

4. 'price_range': Calculate values based on Pakistani terminology (1 Lac = 100,000 | 1 Crore = 10,000,000).
   - Interpret local contexts like '30lacs', '50 lac', 'under 15 lakh', 'range 20 se 25 lac'.
   Map strictly to ONE of these exact string ranges:
   - '0-500000' (If query implies under 5 Lac, e.g., 'sasti bike' or 'under 5 lac')
   - '500000-1500000' (5 to 15 Lac, e.g., 'mehran in 10 lac')
   - '1500000-3000000' (15 to 30 Lac, e.g., 'alto automatic under 25 lac')
   - '3000000-6000000' (30 to 60 Lac, e.g., 'civic around 45 lac')
   - '6000000-999999999' (Above 60 Lac, e.g., 'fortuner' or 'above 70 lacs')

If an entity is not mentioned or cannot be confidently extracted, return null for that key.
Return ONLY a raw, valid JSON object with the keys: 'category', 'keyword', 'city', 'price_range'. Do NOT include any markdown blocks (like ```json), no backticks, no conversational fillers, and no explanations.";

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $query]
        ];

        try {
            $response = $this->provider->generateResponse($messages);
            $response = trim($response);
            
            // Clean markdown if AI still includes it
            if (str_starts_with($response, '```json')) {
                $response = substr($response, 7);
                $response = substr($response, 0, -3);
            } elseif (str_starts_with($response, '```')) {
                $response = substr($response, 3);
                $response = substr($response, 0, -3);
            }
            
            $data = json_decode(trim($response), true);
            
            if (!$data) {
                return ['keyword' => $query, 'city_id' => null, 'price_range' => null, 'category' => null];
            }

            $cityId = null;
            if (!empty($data['city'])) {
                $city = City::where('name', 'like', '%' . $data['city'] . '%')->first();
                if ($city) {
                    $cityId = $city->id;
                }
            }

            return [
                'category' => $data['category'] ?? null,
                'keyword' => $data['keyword'] ?? null,
                'city_id' => $cityId,
                'price_range' => $data['price_range'] ?? null
            ];

        } catch (\Exception $e) {
            // Fallback to simple keyword search
            return ['keyword' => $query, 'city_id' => null, 'price_range' => null, 'category' => null];
        }
    }
}
