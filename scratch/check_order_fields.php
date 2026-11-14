<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SellerOrder;

$orders = SellerOrder::latest()->take(3)->get();
foreach ($orders as $so) {
    echo "SellerOrder ID: {$so->id}\n";
    echo "  seller_order_number: '{$so->seller_order_number}'\n";
    echo "  order_id: '{$so->order_id}'\n";
    if ($so->order) {
        echo "  parent order id: '{$so->order->id}'\n";
        echo "  parent order_number: '{$so->order->order_number}'\n";
    }
}
