<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'parent_id', 'order'];

    // Parent Category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Child Categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Items in this category
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // Property Templates
    public function propertyTemplates()
    {
        return $this->hasMany(PropertyTemplate::class)->orderBy('sort_order');
    }
}