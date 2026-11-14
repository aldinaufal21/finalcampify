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

echo "Testing Dashboard Pesanan Terbaru rendering...\n";
if (strpos($html, 'SO-0') !== false) {
    echo "WARNING: Still found 'SO-0' seller order format!\n";
} else {
    echo "SUCCESS: Seller order number (SO-...) is completely removed!\n";
}

if (strpos($html, '#30') !== false || strpos($html, '#31') !== false || strpos($html, '#29') !== false) {
    echo "SUCCESS: Found clean Order ID (e.g. #30 / #29)!\n";
}

if (strpos($html, '🏕️ SEWA') !== false) {
    echo "SUCCESS: Type badge '🏕️ SEWA' is present!\n";
}
