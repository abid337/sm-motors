<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\City;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('items')->get();

        $featured_items = Item::with('category', 'city')
            ->where('status', 'published')
            ->where('featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latest_items = Item::with('category', 'city')
            ->where('status', 'published')
            ->latest()
            ->take(8)
            ->get();

        $cities = City::all();

        return view('frontend.home', compact(
            'categories',
            'featured_items',
            'latest_items',
            'cities'
        ));
    }
}