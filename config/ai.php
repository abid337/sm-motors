<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the default AI provider and its settings.
    | Currently optimized for OpenRouter (Gemini / OpenAI).
    |
    */

    'provider' => env('AI_PROVIDER', 'openrouter'),

    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
        'base_url' => 'https://openrouter.ai/api/v1',
        'default_model' => env('OPENROUTER_MODEL', 'google/gemini-2.0-flash-001'),
        'timeout' => 45,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sales Personality Settings
    |--------------------------------------------------------------------------
    */
    
    'persona' => [
        'name' => 'SM Autos Sales Assistant',
        'tone' => 'professional, helpful, persuasive',
        'max_tokens' => 500,
        'temperature' => 0.7,
    ],

    /*
    |--------------------------------------------------------------------------
    | Business Rules
    |--------------------------------------------------------------------------
    */

    'leads' => [
        'enabled' => true,
        'notify_email' => env('SALES_NOTIFY_EMAIL', 'sales@sm-autos.com'),
    ],
];
