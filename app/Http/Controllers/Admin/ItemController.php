<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\City;
use App\Models\Media;
use App\Models\ItemProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class ItemController extends Controller
{
    private function getCloudinary()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true,
            ]
        ]);
        return new Cloudinary();
    }

    public function index()
    {
        $items = Item::with('category', 'city')
            ->latest()
            ->paginate(10);
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $cities     = City::all();
        return view('admin.items.create', compact('categories', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
        ]);

        // Thumbnail Upload to Cloudinary
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $cloudinary = $this->getCloudinary();
            $result = $cloudinary->uploadApi()->upload(
                $request->file('thumbnail')->getRealPath(),
                ['folder' => 'thumbnails']
            );
            $thumbnailPath = $result['secure_url'];
        }

        $item = Item::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'price'       => $request->price,
            'thumbnail'   => $thumbnailPath,
            'status'      => $request->status ?? 'published',
            'condition'   => $request->condition ?? 'used',
            'featured'    => $request->featured ? true : false,
            'category_id' => $request->category_id,
            'city_id'     => $request->city_id,
            'user_id'     => auth()->id(),
        ]);

        // Properties Save (mileage, engine etc)
        if ($request->has('prop_keys')) {
            foreach ($request->prop_keys as $i => $key) {
                if (!empty($key)) {
                    ItemProperty::create([
                        'item_id' => $item->id,
                        'key'     => $key,
                        'value'   => $request->prop_values[$i] ?? '',
                    ]);
                }
            }
        }

        // Extra Images Upload to Cloudinary
        if ($request->hasFile('images')) {
            $cloudinary = $this->getCloudinary();
            foreach ($request->file('images') as $image) {
                $result = $cloudinary->uploadApi()->upload(
                    $image->getRealPath(),
                    ['folder' => 'items']
                );
                Media::create([
                    'item_id'   => $item->id,
                    'file_path' => $result['secure_url'],
                    'type'      => 'image',
                ]);
            }
        }

        return redirect()->route('admin.items.index')
            ->with('success', 'Item created successfully!');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $cities     = City::all();
        $item->load('properties', 'media');
        return view('admin.items.edit', compact('item', 'categories', 'cities'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Thumbnail Update on Cloudinary
        $thumbnailPath = $item->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $cloudinary = $this->getCloudinary();
            $result = $cloudinary->uploadApi()->upload(
                $request->file('thumbnail')->getRealPath(),
                ['folder' => 'thumbnails']
            );
            $thumbnailPath = $result['secure_url'];
        }

        $item->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . $item->id,
            'description' => $request->description,
            'price'       => $request->price,
            'thumbnail'   => $thumbnailPath,
            'status'      => $request->status,
            'condition'   => $request->condition,
            'featured'    => $request->featured ? true : false,
            'category_id' => $request->category_id,
            'city_id'     => $request->city_id,
        ]);

        // Properties Update
        $item->properties()->delete();
        if ($request->has('prop_keys')) {
            foreach ($request->prop_keys as $i => $key) {
                if (!empty($key)) {
                    ItemProperty::create([
                        'item_id' => $item->id,
                        'key'     => $key,
                        'value'   => $request->prop_values[$i] ?? '',
                    ]);
                }
            }
        }

        return redirect()->route('admin.items.index')
            ->with('success', 'Item updated successfully!');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index')
            ->with('success', 'Item deleted!');
    }
}