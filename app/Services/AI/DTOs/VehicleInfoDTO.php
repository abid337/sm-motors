<?php

namespace App\Services\AI\DTOs;

class VehicleInfoDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $condition,
        public readonly float $price,
        public readonly string $category,
        public readonly string $city,
        public readonly array $properties,
        public readonly string $url
    ) {}

    public function toContextString(): string
    {
        $props = collect($this->properties)
            ->map(fn($v, $k) => "{$k}: {$v}")
            ->implode(', ');

        return "Vehicle: {$this->title} | City: {$this->city} | Condition: {$this->condition} | Price: {$this->price} | Category: {$this->category} | Specs: [{$props}] | Details: {$this->url}";
    }
}
