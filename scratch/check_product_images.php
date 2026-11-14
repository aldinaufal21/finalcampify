<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SellerModels\Product_seller;

$products = Product_seller::latest()->take(10)->get();
foreach ($products as $p) {
    echo "ID: " . $p->id . " | UserID: " . $p->user_id . " | Name: " . $p->nama_produk . "\n";
    echo "  gambar: '" . $p->gambar . "'\n";
    echo "  image: '" . ($p->image ?? '') . "'\n";
    echo "  image_url: '" . ($p->image_url ?? '') . "'\n";
    
    // Check file existence
    $val = $p->image ?: $p->gambar;
    echo "  file in public/assets/images/$val: " . (file_exists(public_path('assets/images/' . $val)) ? 'YES' : 'NO') . "\n";
    echo "  file in public/storage/$val: " . (file_exists(public_path('storage/' . $val)) ? 'YES' : 'NO') . "\n";
    echo "  file in public/storage/products/$val: " . (file_exists(public_path('storage/products/' . $val)) ? 'YES' : 'NO') . "\n";
    echo "  file in public/$val: " . (file_exists(public_path($val)) ? 'YES' : 'NO') . "\n";
}
