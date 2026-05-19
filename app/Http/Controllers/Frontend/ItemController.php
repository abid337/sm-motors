<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\City;
use App\Models\Category;
use App\Models\Inquiry;
use App\DTOs\AISearchCriteria;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function search(Request $request)
    {
        $criteria = AISearchCriteria::fromRequest($request);

        $items = Item::with('category', 'city')
            ->where('status', 'published')
            ->applyAISearch($criteria)
            ->latest()
            ->paginate(12);
        $cities     = City::all();
        $categories = Category::all();

        return view('frontend.search', compact('items', 'cities', 'categories'));
    }

    public function show($slug)
    {
        $item = Item::with('category', 'city', 'properties', 'media')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Session check 
        $sessionKey = 'viewed_item_' . $item->id;
        if (!session()->has($sessionKey)) {
            $item->incrementViews();
            session()->put($sessionKey, true);
        }

        $related = Item::with('city')
            ->where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->where('status', 'published')
            ->take(4)
            ->get();

        return view('frontend.detail', compact('item', 'related'));
    }

    public function inquiry(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:255',
            'message' => 'nullable|string|max:1000',
            'item_id' => 'required|exists:items,id',
        ]);

        Inquiry::create([
            'item_id' => $validated['item_id'],
            'name'    => $validated['name'],
            'phone'   => $validated['phone'],
            'email'   => $validated['email'] ?? null,
            'message' => $validated['message'] ?? null,
        ]);

        return back()->with('success', 'Inquiry sent successfully!');
    }
}
