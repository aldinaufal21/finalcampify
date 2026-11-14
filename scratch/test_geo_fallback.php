<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

function searchGeo($query) {
    $res = Http::withoutVerifying()->withHeaders([
        'User-Agent' => 'CampifyApp/1.0'
    ])->timeout(5)->get('https://nominatim.openstreetmap.org/search', [
        'q' => $query,
        'format' => 'json',
        'limit' => 1
    ]);
    $data = $res->json();
    return [
        'query' => $query,
        'found' => !empty($data[0]),
        'lat' => $data[0]['lat'] ?? null,
        'lon' => $data[0]['lon'] ?? null,
        'display_name' => $data[0]['display_name'] ?? null
    ];
}

$queries = [
    "Jl. Sukabirus.A1a Citeureup Kec. Dayeuhkolot, Jawa, Barat, Kabupaten Bandung, Jawa Barat",
    "Citeureup, Dayeuhkolot, Bandung",
    "Dayeuhkolot, Kabupaten Bandung",
    "Bandung, Jawa Barat"
];

foreach ($queries as $q) {
    echo json_encode(searchGeo($q), JSON_PRETTY_PRINT) . "\n";
    sleep(1);
}
