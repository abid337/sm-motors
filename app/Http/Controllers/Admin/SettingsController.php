<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class SettingsController extends Controller
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

    public function index()
    {
        $settings = SiteSetting::getAllSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'       => 'required|string|max:255',
            'site_tagline'    => 'nullable|string|max:500',
            'site_email'      => 'nullable|email',
            'site_phone'      => 'nullable|string|max:20',
            'site_address'    => 'nullable|string|max:255',
            'primary_color'   => 'nullable|string|max:10',
            'secondary_color' => 'nullable|string|max:10',
            'hero_title'      => 'nullable|string|max:255',
            'hero_subtitle'   => 'nullable|string|max:500',
            'footer_about'    => 'nullable|string|max:500',
            'footer_copyright'=> 'nullable|string|max:255',
            'facebook_url'    => 'nullable|string|max:255',
            'instagram_url'   => 'nullable|string|max:255',
            'youtube_url'     => 'nullable|string|max:255',
            'tiktok_url'      => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        // Logo Upload
        if ($request->hasFile('site_logo')) {
            $cloudinary = $this->getCloudinary();
            $result = $cloudinary->uploadApi()->upload(
                $request->file('site_logo')->getRealPath(),
                ['folder' => 'site']
            );
            SiteSetting::set('site_logo', $result['secure_url']);
        }

        // Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $cloudinary = $this->getCloudinary();
            $result = $cloudinary->uploadApi()->upload(
                $request->file('site_favicon')->getRealPath(),
                ['folder' => 'site']
            );
            SiteSetting::set('site_favicon', $result['secure_url']);
        }

        
        $keys = [
            'site_name', 'site_tagline', 'site_email', 'site_phone',
            'site_address', 'primary_color', 'secondary_color',
            'hero_title', 'hero_subtitle', 'footer_about',
            'footer_copyright', 'facebook_url', 'instagram_url',
            'youtube_url', 'tiktok_url', 'whatsapp_number',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SiteSetting::set($key, $request->input($key));
            }
        }

        return back()->with('success', 'Settings saved successfully!');
    }
}