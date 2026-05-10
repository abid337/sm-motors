<?php

namespace App\Services\AI\Context;

use App\Models\Item;
use App\Services\AI\DTOs\VehicleInfoDTO;
use Illuminate\Support\Collection;

class VehicleContextBuilder
{
    /**
     * Build the inventory context for the AI.
     *
     * @return string
     */
    public function buildInventoryContext(): string
    {
        $vehicles = Item::with(['category', 'properties'])
            ->where('status', 'active')
            ->limit(20) // Limit to top 20 to keep prompt window manageable
            ->get();

        if ($vehicles->isEmpty()) {
            return "Currently, there are no vehicles available in our inventory.";
        }

        $context = "AVAILABLE INVENTORY AT SM AUTOS:\n";
        
        $vehicles->each(function (Item $item) use (&$context) {
            $dto = new VehicleInfoDTO(
                id: $item->id,
                title: $item->title,
                condition: $item->condition,
                price: (float) $item->price,
                category: $item->category->name ?? 'Vehicle',
                properties: $item->properties->pluck('value', 'key')->toArray(),
                url: route('items.show', $item->slug)
            );
            
            $context .= "- " . $dto->toContextString() . "\n";
        });

        return $context;
    }
}
