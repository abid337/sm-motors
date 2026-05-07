<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    
    public static function get($key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            // Table might not exist yet during migration/deployment
            return $default;
        }
    }

    // Setting save 
    public static function set($key, $value)
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                static::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
                Cache::forget('setting_' . $key);
            }
        } catch (\Exception $e) {
            
        }
    }

    
    public static function getAllSettings()
    {
        try {
            return static::all()->pluck('value', 'key');
        } catch (\Exception $e) {
            return collect();
        }
    }
}