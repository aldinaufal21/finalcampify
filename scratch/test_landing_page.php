<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Auth;

echo "1. Testing LandingPageController@index as Guest...\n";
Auth::logout();
$controller = app(LandingPageController::class);
$response = $controller->index();

if ($response instanceof \Illuminate\View\View) {
    $html = $response->render();
    echo "SUCCESS: Landing page view rendered! Length: " . strlen($html) . " bytes\n";
    
    if (strpos($html, 'Jelajahi Alam Bebas') !== false) {
        echo "SUCCESS: Found Hero headline 'Jelajahi Alam Bebas'!\n";
    }
    if (strpos($html, 'Mulai Sewa Gear') !== false) {
        echo "SUCCESS: Found 'Mulai Sewa Gear' CTA!\n";
    }
    if (strpos($html, 'Beli Perlengkapan') !== false) {
        echo "SUCCESS: Found 'Beli Perlengkapan' CTA!\n";
    }
    if (strpos($html, 'Proteksi Jaminan Escrow') !== false) {
        echo "SUCCESS: Found Escrow feature section!\n";
    }
    if (strpos($html, 'Punya Alat Camping yang Menganggur') !== false) {
        echo "SUCCESS: Found Seller callout section!\n";
    }
} else {
    echo "FAIL: Expected View instance, got: " . get_class($response) . "\n";
}

echo "\n2. Testing Redirects for Authenticated Admin and Seller...\n";
$buyer = \App\Models\User::where('role', 'buyer')->first();
if ($buyer) {
    Auth::login($buyer);
    $buyerRes = $controller->index();
    echo "Buyer redirect status: " . $buyerRes->getStatusCode() . " -> " . $buyerRes->getTargetUrl() . "\n";
}

$seller = \App\Models\User::where('role', 'seller')->first();
if ($seller) {
    Auth::login($seller);
    $sellerRes = $controller->index();
    echo "Seller redirect status: " . $sellerRes->getStatusCode() . " -> " . $sellerRes->getTargetUrl() . "\n";
}

$admin = \App\Models\User::where('role', 'admin')->first();
if ($admin) {
    Auth::login($admin);
    $adminRes = $controller->index();
    echo "Admin redirect status: " . $adminRes->getStatusCode() . " -> " . $adminRes->getTargetUrl() . "\n";
}

echo "\nAll Landing Page tests completed!\n";
