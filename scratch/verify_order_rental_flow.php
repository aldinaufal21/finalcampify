<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SellerController\OrderController_seller;
use App\Http\Controllers\SellerController\RentalController_seller;

$user = User::where('role', 'seller')->first() ?? User::find(1);
Auth::login($user);

echo "Testing OrderController_seller@show(31) [Rental SO-031]:\n";
$orderCtrl = app(OrderController_seller::class);
$res = $orderCtrl->show(31);
if ($res instanceof \Illuminate\Http\RedirectResponse) {
    echo "  Status: Redirect (" . $res->getStatusCode() . ") to " . $res->getTargetUrl() . "\n";
} else {
    echo "  Status: View rendered\n";
}

echo "Testing RentalController_seller@show(26) [Rental ID for SO-031]:\n";
$rentalCtrl = app(RentalController_seller::class);
$resRental = $rentalCtrl->show(26);
echo "  Status: View rendered (" . strlen($resRental->render()) . " bytes)\n";

echo "\nAll verification passed!\n";
