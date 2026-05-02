<?php

namespace App\Http\Controllers\User;

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
            'url' => ['secure' => true]
        ]);
        return new Cloudinary();
    }

    // User Dashboard - apne items
    public function dashboard()
    {
        $items = Item::where('user_id', auth()->id())
            ->with('category', 'city')
            ->latest()
            ->paginate(10);
        return view('user.dashboard', compact('items'));
    }

    // Create Form
    public function create()
    {
        $categories = Category::all();
        $cities     = City::all();
        return view('user.items.create', compact('categories', 'cities'));
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
        ]);

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
            'status'      => 'published',
            'condition'   => $request->condition ?? 'used',
            'featured'    => false,
            'category_id' => $request->category_id,
            'city_id'     => $request->city_id,
            'user_id'     => auth()->id(),
        ]);

        // Properties
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

        // Extra Images
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

        return redirect()->route('user.dashboard')
            ->with('success', 'Item added successfully!');
    }

    // Edit Form
    public function edit(Item $item)
    {

        if ($item->user_id !== auth()->id()) {
            abort(403);
        }
        $categories = Category::all();
        $cities     = City::all();
        $item->load('properties', 'media');
        return view('user.items.edit', compact('item', 'categories', 'cities'));
    }

    // Update
    public function update(Request $request, Item $item)
    {
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

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
            'condition'   => $request->condition,
            'category_id' => $request->category_id,
            'city_id'     => $request->city_id,
        ]);

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

        // Extra Images
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

        return redirect()->route('user.dashboard')
            ->with('success', 'Item updated successfully!');
    }

    // Delete
    public function destroy(Item $item)
    {
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }
        $item->media()->delete();
        $item->properties()->delete();
        $item->delete();
        return redirect()->route('user.dashboard')
            ->with('success', 'Item deleted!');
    }

    // Delete Media
    public function deleteMedia($id)
    {
        $media = Media::findOrFail($id);
        $item  = Item::findOrFail($media->item_id);
        if ($item->user_id !== auth()->id()) {
            abort(403);
        }
        $media->delete();
        return back()->with('success', 'Image deleted!');
    }
}
