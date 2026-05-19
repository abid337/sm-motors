<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AI\AISearchService;
use App\DTOs\AISearchCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AISearchController extends Controller
{
    public function __construct(
        protected AISearchService $searchService
    ) {}

    /**
     * Extract query parameters from natural language text and return a redirect URL.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function extractQuery(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'nullable|string|max:500',
            'city_id' => 'nullable',
            'price_range' => 'nullable'
        ]);

        $queryText = $request->input('query', '');
        
        if (!empty($queryText)) {
            $criteria = $this->searchService->extractQuery($queryText);
            
            // Merge manual form filters if AI extraction missed them
            if (!$criteria->cityId && $request->input('city_id')) {
                $criteria->cityId = (int)$request->input('city_id');
            }
            if (!$criteria->minPrice && !$criteria->maxPrice && $request->input('price_range')) {
                // We fallback to request price range
                if (str_contains($request->input('price_range'), '-')) {
                    [$min, $max] = explode('-', $request->input('price_range'));
                    $criteria->minPrice = (int)$min;
                    $criteria->maxPrice = (int)$max;
                }
            }
        } else {
            // If no natural language query, just use manual filters
            $criteria = AISearchCriteria::fromRequest($request);
        }

        return response()->json([
            'redirect_url' => route('items.search', $criteria->toQueryParams())
        ]);
    }
}
