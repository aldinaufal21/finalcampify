<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "--- stores columns ---\n";
print_r(Schema::getColumnListing('stores'));

echo "\n--- sample stores row ---\n";
$firstStore = DB::table('stores')->first();
print_r($firstStore);
