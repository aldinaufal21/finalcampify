<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$address = "Jl. Sukabirus.A1a Citeureup Kec. Dayeuhkolot, Jawa, Barat, Kabupaten Bandung, Jawa Barat";

echo "Testing Nominatim request for address: $address\n";
try {
    $response = Http::withoutVerifying()->withHeaders([
        'User-Agent' => 'CampifyApp/1.0'
    ])->timeout(5)->get('https://nominatim.openstreetmap.org/search', [
        'q' => $address,
        'format' => 'json',
        'limit' => 1
    ]);

    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
