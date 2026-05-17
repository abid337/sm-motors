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
            'query' => 'required|string|max:500'
        ]);

        $filters = $this->searchService->extractQuery($request->input('query'));

        // Filter out nulls and empty strings
        $queryParameters = array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        });

        return response()->json([
            'redirect_url' => route('items.search', $queryParameters)
        ]);
    }
}
