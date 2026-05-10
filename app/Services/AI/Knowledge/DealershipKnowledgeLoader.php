<?php

namespace App\Services\AI\Knowledge;

use App\Models\SiteSetting;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class DealershipKnowledgeLoader
{
    /**
     * Load dynamic dealership information from settings and database.
     */
    public function getGeneralKnowledge(): string
    {
        $settings = SiteSetting::getAllSettings();
        $categories = Category::pluck('name')->toArray();
        $citiesCount = \App\Models\City::count();
        $totalItems = \App\Models\Item::where('status', 'published')->count();
        $featuredItems = \App\Models\Item::where('status', 'published')->where('featured', true)->count();
        
        $siteName = $settings['site_name'] ?? 'SM Autos';
        $siteAddress = $settings['site_address'] ?? $settings['address'] ?? 'Not specified';
        $sitePhone = $settings['site_phone'] ?? $settings['phone'] ?? 'Not specified';
        $siteEmail = $settings['site_email'] ?? $settings['email'] ?? 'Not specified';
        
        $knowledge = "DEALERSHIP DASHBOARD OVERVIEW (LIVE DATA):\n";
        $knowledge .= "- Site Name: {$siteName}\n";
        $knowledge .= "- Total Inventory: {$totalItems} published items\n";
        $knowledge .= "- Featured Stock: {$featuredItems} premium units\n";
        $knowledge .= "- Coverage: {$citiesCount} cities across Pakistan\n";
        $knowledge .= "- Categories: " . implode(', ', $categories) . "\n";
        $knowledge .= "- Contact: {$siteAddress} | {$sitePhone} | {$siteEmail}\n";

        // Category Breakdown
        $breakdown = \App\Models\Item::where('status', 'published')
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map->count();
            
        if ($breakdown->isNotEmpty()) {
            $knowledge .= "- Inventory Breakdown: ";
            foreach ($breakdown as $name => $count) {
                $knowledge .= "{$name} ({$count}), ";
            }
            $knowledge = rtrim($knowledge, ', ') . "\n";
        }

        if (isset($settings['about_us'])) {
            $knowledge .= "- About Us: " . $settings['about_us'] . "\n";
        }

        return $knowledge;
    }
}
