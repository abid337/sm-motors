<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'price',
        'thumbnail', 'status', 'condition',
        'featured', 'category_id', 'city_id', 'user_id', 'views'
    ];

    // Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // City
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Media/Images
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    // Primary Image
    public function primaryMedia()
    {
        return $this->hasOne(Media::class)->where('is_primary', true);
    }

    // Properties (mileage, engine, etc)
    public function properties()
    {
        return $this->hasMany(ItemProperty::class);
    }

    // Inquiries
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    // Reports
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Increment Views
    public function incrementViews()
    {
        $this->increment('views');
    }
}