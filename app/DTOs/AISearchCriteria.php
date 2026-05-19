<?php

namespace App\DTOs;

class AISearchCriteria
{
    public function __construct(
        public ?string $category = null,
        public ?string $keyword = null,
        public ?int $cityId = null,
        public ?int $minPrice = null,
        public ?int $maxPrice = null,
        public ?string $rawCityName = null // Fallback if exact match fails
    ) {}

    /**
     * Create from AI extracted JSON array
     */
    public static function fromAIArray(array $data, ?int $cityId = null, ?string $rawCityName = null): self
    {
        $minPrice = null;
        $maxPrice = null;

        // Parse price range dynamically (e.g., "500001-1500000")
        if (!empty($data['price_range']) && str_contains($data['price_range'], '-')) {
            [$minPrice, $maxPrice] = explode('-', $data['price_range']);
        }

        return new self(
            category: $data['category'] ?? null,
            keyword: $data['keyword'] ?? null,
            cityId: $cityId,
            minPrice: $minPrice ? (int)$minPrice : null,
            maxPrice: $maxPrice ? (int)$maxPrice : null,
            rawCityName: $rawCityName
        );
    }

    /**
     * Create from standard Request inputs (for manual form submission fallback/merging)
     */
    public static function fromRequest(\Illuminate\Http\Request $request): self
    {
        $minPrice = null;
        $maxPrice = null;

        if ($request->filled('price_range') && str_contains($request->price_range, '-')) {
            [$minPrice, $maxPrice] = explode('-', $request->price_range);
        }

        return new self(
            category: $request->input('category'),
            keyword: $request->input('keyword'),
            cityId: $request->input('city_id') ? (int)$request->input('city_id') : null,
            minPrice: $minPrice ? (int)$minPrice : null,
            maxPrice: $maxPrice ? (int)$maxPrice : null
        );
    }
    
    /**
     * Convert back to array for query parameters (e.g., when redirecting)
     */
    public function toQueryParams(): array
    {
        $params = [
            'category' => $this->category,
            'keyword' => $this->keyword ?? $this->rawCityName, // Fallback city to keyword if not matched
            'city_id' => $this->cityId,
        ];
        
        if ($this->minPrice !== null && $this->maxPrice !== null) {
            $params['price_range'] = "{$this->minPrice}-{$this->maxPrice}";
        }

        // Filter out nulls and empty strings
        return array_filter($params, function ($value) {
            return !is_null($value) && $value !== '';
        });
    }
}
