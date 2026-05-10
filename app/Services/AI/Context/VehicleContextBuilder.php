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

        $activeCount = Item::where('status', 'active')->count();
        $categoriesCount = Item::where('status', 'active')->with('category')->get()->groupBy('category.name')->map->count();

        $context = "CURRENT LIVE INVENTORY STATUS:\n";
        $context .= "- Total active items across all categories: {$activeCount}\n";
        
        if ($categoriesCount->isEmpty()) {
            $context .= "- No items are currently active in any category.\n";
        } else {
            foreach ($categoriesCount as $catName => $count) {
                $context .= "- {$catName}: {$count} active units\n";
            }
        }
        
        $context .= "\nDETAILED LIST OF TOP ITEMS:\n";
        
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
