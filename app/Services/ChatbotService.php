<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Item;
use App\Models\SiteSetting;

class ChatbotService
{
    /**
     * Build the complete system prompt for the AI
     */
    public function getSystemPrompt()
    {
        $context = $this->getWebsiteContext();

        return "Persona: You are a friendly 'Lahori Bhai' working at SM-Autos. 

        RULES:
        1. Only use Roman Urdu.
        2. NEVER use English translations or brackets ().
        3. Keep answers extremely short (max 1-2 sentences).
        4. GREETINGS: If user says Salam or Hi, just say 'Walaikum Assalam bhai, kia haal hain? Kia madad krun?'
        5. If someone asks for a specific car (like Grande), check the featured list below. If it's not there, say 'Bhai abhi to stock mein nahi hai, aap hamara number save kr lo'.
        
        REFERENCE DATA:
        $context";
    }

    /**
     * Gather public website information
     */
    private function getWebsiteContext()
    {
        $settings = SiteSetting::getAllSettings();
        $siteName = $settings['site_name'] ?? 'SM-Autos';
        $phone = $settings['site_phone'] ?? '+92 309 6527842';
        $email = $settings['site_email'] ?? 'info@sm-autos.com';
        $address = $settings['site_address'] ?? 'Lahore, Pakistan';

        $categories = Category::pluck('name')->implode(', ');
        
        $featuredItems = Item::where('status', 'published')
            ->where('featured', true)
            ->latest()
            ->take(5)
            ->get(['title', 'price', 'condition']);

        $itemsList = "";
        foreach($featuredItems as $item) {
            $itemsList .= "- {$item->title} (Price: Rs. " . number_format($item->price) . ", Condition: {$item->condition})\n";
        }

        return "
        Site Name: $siteName
        Contact Phone: $phone
        Email: $email
        Address: $address
        Available Categories: $categories
        
        Featured Inventory:
        $itemsList
        ";
    }
}
