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

echo "Testing unified buyer & seller landing page theme rendering...\n";
$html = $response->render();
echo "Rendered successfully! Length: " . strlen($html) . " bytes\n";

if (strpos($html, 'font-outfit') !== false) {
    echo "SUCCESS: Found Outfit typography!\n";
}
if (strpos($html, 'bg-emerald-gradient') !== false) {
    echo "SUCCESS: Found seller & buyer emerald gradient!\n";
}
if (strpos($html, 'bg-emerald-soft') !== false) {
    echo "SUCCESS: Found soft emerald badge styles!\n";
}
if (strpos($html, 'bg-[#f8fafc]') !== false) {
    echo "SUCCESS: Found standard canvas bg-[#f8fafc]!\n";
}
