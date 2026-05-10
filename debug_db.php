<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$items = DB::table('items')->limit(10)->get();
$categories = DB::table('categories')->get();

echo "CATEGORIES:\n";
foreach($categories as $c) echo "- ID: {$c->id}, Name: {$c->name}\n";

echo "\nITEMS (First 10):\n";
foreach($items as $i) {
    echo "- Title: {$i->title}, Status: {$i->status}, Category ID: {$i->category_id}\n";
}
