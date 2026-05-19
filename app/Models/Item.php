<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\DTOs\AISearchCriteria;

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

    // Apply AI Search Criteria Scope
    public function scopeApplyAISearch(Builder $query, AISearchCriteria $criteria): Builder
    {
        return $query->when($criteria->category, function ($q, $category) {
                if ($category == 'cars') {
                    $q->whereHas('category', function ($subQ) {
                        $subQ->whereIn('slug', ['new-cars', 'used-cars']);
                    });
                } elseif ($category == 'bikes') {
                    $q->whereHas('category', function ($subQ) {
                        $subQ->whereIn('slug', ['new-bikes', 'used-bikes']);
                    });
                } else {
                    $q->whereHas('category', function ($subQ) use ($category) {
                        $subQ->where('slug', $category);
                    });
                }
            })
            ->when($criteria->cityId, function ($q, $cityId) {
                $q->where('city_id', $cityId);
            })
            ->when($criteria->minPrice, function ($q, $minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($criteria->maxPrice, function ($q, $maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($criteria->keyword, function ($q, $keyword) {
                $q->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('title', 'like', "%{$keyword}%")
                             ->orWhere('description', 'like', "%{$keyword}%");
                });
            });
    }
}