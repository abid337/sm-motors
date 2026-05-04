<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\City;
use App\Models\Category;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function search(Request $request)
    {
        $query = Item::with('category', 'city')
            ->where('status', 'published');

        // Keyword Search
        if ($request->keyword) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        // City Filter
        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        // Price Filter
        if ($request->price_range) {
            [$min, $max] = explode('-', $request->price_range);
            $query->whereBetween('price', [(int)$min, (int)$max]);
        }

        // Category Filter
        if ($request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $items      = $query->latest()->paginate(12);
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

        // Increment Views
        $item->incrementViews();

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