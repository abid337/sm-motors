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

        $siteName = $settings['site_name'] ?? 'SM Autos';
        $siteAddress = $settings['site_address'] ?? $settings['address'] ?? 'Not specified';
        $sitePhone = $settings['site_phone'] ?? $settings['phone'] ?? 'Not specified';
        $siteEmail = $settings['site_email'] ?? $settings['email'] ?? 'Not specified';
        
        $knowledge = "DEALERSHIP OVERVIEW (LIVE DATA):\n";
        $knowledge .= "- Name: {$siteName}\n";
        $knowledge .= "- Available Sections/Categories: " . implode(', ', $categories) . "\n";
        $knowledge .= "- Contact Address: {$siteAddress}\n";
        $knowledge .= "- Phone: {$sitePhone}\n";
        $knowledge .= "- Email: {$siteEmail}\n";

        // Check if there are any specific about or policy settings
        if (isset($settings['about_us'])) {
            $knowledge .= "- About Us: " . $settings['about_us'] . "\n";
        }

        return $knowledge;
    }
}
