<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    // Single setting get karo
    public static function get($key, $default = null)
    {
        return Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
            try {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            } catch (\Exception $e) {
                // Table might not exist yet during migration/deployment
                return $default;
            }
        });
    }

    // Setting save 
    public static function set($key, $value)
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        Cache::forget('setting_' . $key);
    }

    
    public static function getAllSettings()
    {
        return static::all()->pluck('value', 'key');
    }
}