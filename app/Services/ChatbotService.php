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

        return "You are a local Pakistani automotive expert assistant at 'SM-Autos' Lahore. 
        
        STRICT RULES:
        1. LANGUAGE: Use Pakistani Urdu and Roman Urdu. NEVER use Hindi words like 'Swagat' or 'Namaste'. Use 'Khush Amdeed', 'Assalam-o-Alaikum', and 'Bhai'.
        2. CONVERSATION: Be natural. If someone says 'Aur sunao' or 'Hi', just say 'Walaikum Assalam/Hi, sab theek, aap batayein kia help chahiye?'.
        3. NO DATA DUMPING: Do NOT give the whole knowledge base at once. Only answer exactly what is asked.
        4. KNOWLEDGE: Use the below info ONLY when needed.
        
        SITE INFO:
        $context
        
        5. TONE: Keep it very short, cool, and typical Pakistani 'Bhai' style. Don't be a robot.";
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
