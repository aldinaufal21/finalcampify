<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Auth;

Auth::logout();
$controller = app(LandingPageController::class);
$response = $controller->index();

echo "Testing beautified landing page view rendering...\n";
$html = $response->render();
echo "Rendered successfully! Length: " . strlen($html) . " bytes\n";

if (strpos($html, 'Petualangan Nyaman') !== false) {
    echo "SUCCESS: Found updated headline 'Petualangan Nyaman'!\n";
}
if (strpos($html, 'bg-[#FAF9F5]') !== false) {
    echo "SUCCESS: Found clean off-white canvas bg-[#FAF9F5]!\n";
}
if (strpos($html, 'forest-950') !== false) {
    echo "SUCCESS: Harmonious forest palette active!\n";
}
