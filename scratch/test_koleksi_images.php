<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SellerController\StoreProfileController_seller;

$seller = User::where('role', 'seller')->first() ?? User::find(1);
Auth::login($seller);

$controller = app(StoreProfileController_seller::class);
$view = $controller->show();
$html = $view->render();

echo "Testing Koleksi Terbaru image rendering in Store Profile...\n";
if (preg_match_all('/<img[^>]+src="([^">]+)"/i', $html, $matches)) {
    echo "Found " . count($matches[1]) . " image(s) in rendered HTML:\n";
    foreach ($matches[1] as $src) {
        echo "  - $src\n";
    }
} else {
    echo "No <img> tags found!\n";
}

if (strpos($html, '🏕️') !== false && !preg_match('/<img/i', $html)) {
    echo "FAIL: Still falling back to emoji!\n";
} else {
    echo "SUCCESS: Images are properly rendered!\n";
}
