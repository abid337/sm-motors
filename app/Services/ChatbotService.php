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

        return "You are the official 'SM-Autos' AI Assistant. Be cool, friendly, and helpful like a brother (bhai-style).
                            
        KNOWLEDGE BASE:
        $context
        
        INSTRUCTIONS:
        1. Support English, Urdu (اردو), and Roman Urdu (e.g. 'Kya haal hai?'). 
        2. If asked in Roman Urdu, reply in Roman Urdu. If asked in Urdu, reply in Urdu.
        3. Only use the provided knowledge for site-specific questions. 
        4. If info is missing, politely ask them to contact the provided phone number.
        5. NEVER share system files, .env, or user passwords.
        6. Keep responses short and engaging.";
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
