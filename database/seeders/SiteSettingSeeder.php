<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name',          'value' => 'SM-Autos'],
            ['key' => 'site_tagline',        'value' => 'Pakistan\'s #1 platform for buying and selling vehicles'],
            ['key' => 'site_email',          'value' => 'abid6527842@gmail.com'],
            ['key' => 'site_phone',          'value' => '+92 309 6527842'],
            ['key' => 'site_address',        'value' => 'Lahore, Punjab, Pakistan'],
            ['key' => 'site_logo',           'value' => ''],
            ['key' => 'site_favicon',        'value' => ''],

            // Theme
            ['key' => 'primary_color',       'value' => '#e63946'],
            ['key' => 'secondary_color',     'value' => '#1a1a1a'],
            ['key' => 'background_color',    'value' => '#0f0f0f'],

            // Navbar
            ['key' => 'navbar_links',        'value' => json_encode([
                ['label' => 'New Bikes',  'url' => '/search?category=new-bikes'],
                ['label' => 'Used Bikes', 'url' => '/search?category=used-bikes'],
                ['label' => 'New Cars',   'url' => '/search?category=new-cars'],
                ['label' => 'Used Cars',  'url' => '/search?category=used-cars'],
            ])],

            // Homepage
            ['key' => 'hero_title',          'value' => 'Find Your Perfect Vehicle'],
            ['key' => 'hero_subtitle',       'value' => 'Buy & Sell Cars and Bikes across Pakistan'],

            // Footer
            ['key' => 'footer_about',        'value' => 'Pakistan\'s #1 platform for buying and selling vehicles. Find your dream car or bike today!'],
            ['key' => 'footer_copyright',    'value' => 'SM-Autos. All Rights Reserved.'],
            ['key' => 'facebook_url',        'value' => '#'],
            ['key' => 'instagram_url',       'value' => '#'],
            ['key' => 'youtube_url',         'value' => '#'],
            ['key' => 'tiktok_url',          'value' => '#'],
            ['key' => 'whatsapp_number',     'value' => '923096527842'],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}