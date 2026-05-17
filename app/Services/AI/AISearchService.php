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
        $systemPrompt = "You are an intelligent entity extraction AI for a vehicle dealership website.
Your task is to understand the user's natural language search query and map it to our strict database schema.
Extract these 4 entities:

1. 'category': We only have two main vehicle classes: 'cars' (covers any 4+ wheeler like SUVs, Jeeps, Vans, Sedans) and 'bikes' (covers any 2-wheeler like Scooters, Scooties, Motorcycles, Heavy Bikes). 
   - Analyze whatever vehicle the user mentions using your own knowledge, and map it strictly to one of these exact strings: 'new-cars', 'used-cars', 'new-bikes', 'used-bikes', 'cars', or 'bikes'. 
   - If no vehicle type is implied, return null.
   
2. 'keyword': The specific brand, model, or descriptive term (e.g., 'Honda Civic', 'Alto', 'Vespa'). 
   - CRITICAL: Since you already mapped the vehicle type to 'category', do NOT include generic vehicle words (like 'car', 'scooty', 'jeep', 'suv', 'bike', 'new', 'used') in the keyword. The keyword must only contain specific identifiable terms that would match a listing's title. If the user only typed a generic vehicle name, return null for keyword.
3. 'city': The name of the city if mentioned (e.g. 'Lahore').
4. 'price_range': A price range in Pakistani Rupees (PKR), formatted as one of the following exact string values based on the user's intent. (Remember: 1 Lac = 100,000):
   - '0-500000' (Under 5 Lac)
   - '500000-1500000' (5 to 15 Lac)
   - '1500000-3000000' (15 to 30 Lac)
   - '3000000-6000000' (30 to 60 Lac)
   - '6000000-999999999' (Above 60 Lac)

If an entity is not mentioned, return null for that entity.
Return ONLY a valid JSON object with the keys: 'category', 'keyword', 'city', 'price_range'. No markdown formatting, no backticks, no explanations. Just raw JSON.";

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
