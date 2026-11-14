<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SellerController\DashboardController_seller;

$user = User::where('role', 'seller')->first() ?? User::find(1);
Auth::login($user);

try {
    $controller = new DashboardController_seller();
    $response = $controller->index();
    $view = $response->render();
    echo "Dashboard view rendered successfully! Length: " . strlen($view) . " bytes\n";
    if (strpos($view, 'SEWA') !== false) {
        echo "Found 'SEWA' badge in output!\n";
    }
    if (strpos($view, 'seller/rentals/') !== false) {
        echo "Found 'seller/rentals/' link in output!\n";
    }
} catch (\Throwable $e) {
    echo "Error rendering dashboard: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
