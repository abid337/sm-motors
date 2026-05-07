<?php

use App\Models\SiteSetting;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }
}