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
    public function buildInventoryContext(?string $searchTerm = null): string
    {
        $query = Item::with(['category', 'properties'])
            ->where('status', 'published');

        if ($searchTerm) {
            $keywords = explode(' ', $searchTerm);
            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $word) {
                    if (strlen($word) > 1) {
                        $q->where(function($sq) use ($word) {
                            $sq->where('title', 'LIKE', "%{$word}%")
                               ->orWhere('description', 'LIKE', "%{$word}%");
                        });
                    }
                }
            });
        }

        $vehicles = $query->limit(20)->get();

        $activeCount = Item::where('status', 'published')->count();
        $categoriesCount = Item::where('status', 'published')->with('category')->get()->groupBy('category.name')->map->count();

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
