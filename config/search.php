<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Search Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration mapping for the AI Search. Moving ranges here prevents
    | us from having to change the actual prompt logic if business requirements
    | for price ranges change due to inflation, etc.
    |
    */

    'price_ranges' => [
        '0-500000' => 'Under 5 Lac (e.g., sasti bike or under 5 lac)',
        '500001-1500000' => '5 to 15 Lac (e.g., mehran in 10 lac)',
        '1500001-3000000' => '15 to 30 Lac (e.g., alto automatic under 25 lac)',
        '3000001-6000000' => '30 to 60 Lac (e.g., civic around 45 lac)',
        '6000001-999999999' => 'Above 60 Lac (e.g., fortuner or above 70 lacs)',
    ],
    
    'categories' => [
        'new-cars',
        'used-cars',
        'new-bikes',
        'used-bikes',
        'cars',
        'bikes'
    ]
];
