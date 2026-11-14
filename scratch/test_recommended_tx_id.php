<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SellerController\DashboardController_seller;

$seller = User::where('role', 'seller')->first() ?? User::find(1);
Auth::login($seller);

$controller = app(DashboardController_seller::class);
$view = $controller->index();
$html = $view->render();

echo "Testing Dashboard Pesanan Terbaru format...\n";

if (strpos($html, 'No. Transaksi') !== false) {
    echo "SUCCESS: Column header 'No. Transaksi' is present!\n";
} else {
    echo "FAIL: 'No. Transaksi' header missing!\n";
}

if (strpos($html, 'Sewa #26') !== false) {
    echo "SUCCESS: Found 'Sewa #26' matching detail page!\n";
} else {
    echo "INFO: 'Sewa #26' not found directly, checking regex:\n";
    if (preg_match('/Sewa #\d+/', $html, $m)) {
        echo "  Found: " . $m[0] . "\n";
    }
}

if (preg_match('/Pesanan #\d+/', $html, $m2)) {
    echo "SUCCESS: Found " . $m2[0] . "!\n";
}

echo "All checks completed.\n";
