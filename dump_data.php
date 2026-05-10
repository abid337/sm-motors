<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$data = [
    'categories' => DB::table('categories')->get()->toArray(),
    'cities' => DB::table('cities')->get()->toArray(),
    'items_count' => DB::table('items')->count(),
    'items_sample' => DB::table('items')->limit(5)->get()->toArray(),
    'settings' => DB::table('site_settings')->get()->pluck('value', 'key')->toArray(),
];

file_put_contents('database_dump.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Dump created.";
