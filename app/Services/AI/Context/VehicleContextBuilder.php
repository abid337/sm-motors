<?php

namespace App\Services\AI\Context;

use App\Models\Item;
use App\Services\AI\DTOs\VehicleInfoDTO;
use Illuminate\Support\Collection;

class VehicleContextBuilder
{
    /**
     * Build the inventory context for the AI.
     */
    public function buildInventoryContext(?string $searchTerm = null, ?string $cityName = null): string
    {
        $query = Item::with(['category', 'properties', 'city'])
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

        if ($cityName) {
            $query->whereHas('city', function($q) use ($cityName) {
                $q->where('name', 'LIKE', "%{$cityName}%");
            });
        }

        $vehicles = $query->limit(20)->get();

        $activeCount = Item::where('status', 'published')->count();
        $categoriesCount = Item::leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->where('items.status', 'published')
            ->select('categories.name', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->pluck('total', 'name');

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
                city: $item->city->name ?? 'Unknown',
                properties: $item->properties->pluck('value', 'key')->toArray(),
                url: $item->slug ? route('items.show', $item->slug) : '#'
            );
            
            $context .= "- " . $dto->toContextString() . "\n";
        });

        return $context;
    }
}
