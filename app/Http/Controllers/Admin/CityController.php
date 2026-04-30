<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::withCount('items')->latest()->get();
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        City::create(['name' => $request->name]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City added successfully!');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $city->update(['name' => $request->name]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully!');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted!');
    }
}