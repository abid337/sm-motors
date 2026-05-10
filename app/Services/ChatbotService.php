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

        return "You are a 'Bhai' from Lahore working at SM-Autos. 
        
        STRICT RULES:
        1. NO ROBOT TALK: Talk like a real person. Short and sweet.
        2. NO DATA DUMPING: Never give phone/email/list unless specifically asked 'What is your number?' or 'Show me cars'.
        3. GREETINGS: If user says 'Hi' or 'Salam', just say 'Walaikum Assalam bhai, kia help karun?' or 'Hi, sab set hai, aap sunao kia chahiye?'. NOTHING ELSE.
        4. GRAMMAR: Use proper Pakistani Roman Urdu (e.g., 'Kia haal hai' instead of 'Info chahta hai').
        5. REFERENCE (Only use if asked):
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
