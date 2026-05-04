<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyTemplate;
use App\Models\Category;
use Illuminate\Http\Request;

class PropertyTemplateController extends Controller
{
    public function index()
    {
        $categories = Category::with('propertyTemplates')->get();
        return view('admin.property-templates.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'label'       => 'required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'required'    => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ]);

        PropertyTemplate::create([
            'category_id' => $request->category_id,
            'label'       => $request->label,
            'placeholder' => $request->placeholder,
            'required'    => $request->has('required'),
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Property added!');
    }

    public function update(Request $request, PropertyTemplate $propertyTemplate)
    {
        $request->validate([
            'label'       => 'required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'sort_order'  => 'nullable|integer',
        ]);

        $propertyTemplate->update([
            'label'       => $request->label,
            'placeholder' => $request->placeholder,
            'required'    => $request->has('required'),
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Property updated!');
    }

    public function destroy(PropertyTemplate $propertyTemplate)
    {
        $propertyTemplate->delete();
        return back()->with('success', 'Property deleted!');
    }
}
