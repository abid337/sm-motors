<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AI\AISearchService;
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
            $filters = $this->searchService->extractQuery($queryText);
        } else {
            $filters = [];
        }

        // Merge AI extracted filters with manual form filters
        // AI extraction takes precedence if found
        $finalFilters = [
            'keyword' => !empty($filters['keyword']) ? $filters['keyword'] : $request->input('query'),
            'city_id' => !empty($filters['city_id']) ? $filters['city_id'] : $request->input('city_id'),
            'price_range' => !empty($filters['price_range']) ? $filters['price_range'] : $request->input('price_range'),
        ];

        // Filter out nulls and empty strings
        $queryParameters = array_filter($finalFilters, function ($value) {
            return !is_null($value) && $value !== '';
        });

        return response()->json([
            'redirect_url' => route('items.search', $queryParameters)
        ]);
    }
}
