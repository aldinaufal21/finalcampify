<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\SellerModels\Product_seller;
use App\Http\Controllers\SellerController\ProductController_seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

$seller = User::where('role', 'seller')->first() ?? User::find(1);
Auth::login($seller);

// Find a rental product
$rentProduct = Product_seller::where('user_id', $seller->id)->where('jenis_produk', 'sewa')->first();
if (!$rentProduct) {
    echo "Creating dummy rental product for testing...\n";
    $rentProduct = Product_seller::create([
        'user_id' => $seller->id,
        'nama_produk' => 'Tenda Uji Coba',
        'kategori' => 'tenda',
        'jenis_produk' => 'sewa',
        'harga' => 50000,
        'stok' => 5,
        'escrow_amount' => 20000,
        'deskripsi' => 'Testing rental',
        'status' => 'waiting'
    ]);
}

echo "1. Testing Edit View Rendering for Rental Product ID: {$rentProduct->id}...\n";
$controller = app(ProductController_seller::class);
$view = $controller->edit($rentProduct);
$html = $view->render();

if (strpos($html, 'name="escrow_amount"') !== false) {
    echo "SUCCESS: Found input name='escrow_amount' in edit view!\n";
} else {
    echo "FAIL: escrow_amount input missing in edit view!\n";
}

if (strpos($html, 'Escrow / Jaminan Sewa') !== false) {
    echo "SUCCESS: Found label 'Escrow / Jaminan Sewa'!\n";
}

echo "\n2. Testing Product Update with Escrow Amount...\n";
$testEscrowVal = 35000;
$req = Request::create(route('seller.products.update', $rentProduct->id), 'POST', [
    '_method' => 'PUT',
    'nama_produk' => $rentProduct->nama_produk,
    'kategori' => $rentProduct->kategori,
    'jenis_produk' => 'sewa',
    'harga' => $rentProduct->harga,
    'stok' => $rentProduct->stok,
    'escrow_amount' => $testEscrowVal,
    'deskripsi' => $rentProduct->deskripsi,
]);

$response = $controller->update($req, $rentProduct);
echo "Update response status: " . $response->getStatusCode() . "\n";

// Refresh product from DB
$rentProduct->refresh();
echo "Updated escrow_amount in DB: " . $rentProduct->escrow_amount . "\n";

if ((int)$rentProduct->escrow_amount === $testEscrowVal) {
    echo "SUCCESS: escrow_amount was successfully saved to DB as {$testEscrowVal}!\n";
} else {
    echo "FAIL: escrow_amount in DB is " . var_export($rentProduct->escrow_amount, true) . "\n";
}

echo "\nAll verification completed successfully!\n";
