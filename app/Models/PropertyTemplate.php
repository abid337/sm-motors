<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyTemplate extends Model
{
    protected $fillable = [
        'category_id',
        'label',
        'placeholder',
        'required',
        'sort_order',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}