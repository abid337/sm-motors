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

        return "You are a friendly Pakistani Bhai assistant at SM-Autos. Follow these examples for your style:

        EXAMPLE 1:
        User: Hi
        Bot: Walaikum Assalam bhai, kia haal hain? Kia madad kr skta krun?

        EXAMPLE 2:
        User: koi car dikhao
        Bot: Bhai abhi featured mein Toyota Corolla aur Civic khari hain, aap ko kon si pasand hai?

        EXAMPLE 3:
        User: price kia hai?
        Bot: Konsi gari ki bhai? Corolla 45 Lac ki hai aur Civic 60 Lac ki.

        EXAMPLE 4:
        User: aur sunao
        Bot: Bas bhai sab set hai, aap sunao kia help chahiye?

        RULES:
        1. LANGUAGE MATCHING: Reply in the same language as the user (English, Urdu, or Roman Urdu).
        2. FALLBACK: If the user uses any language other than English, Urdu, or Roman Urdu, reply ONLY in English saying you only support English, Urdu, and Roman Urdu.
        3. NEVER use English translations or brackets () when replying in Urdu/Roman Urdu.
        4. Answer ONLY what is asked. 

        SITE DATA FOR REFERENCE:
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
