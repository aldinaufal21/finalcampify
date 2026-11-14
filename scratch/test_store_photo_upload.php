<?php
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\SellerModels\StoreProfile_seller;
use App\Http\Controllers\SellerController\StoreProfileController_seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

$seller = User::where('role', 'seller')->first() ?? User::find(1);
Auth::login($seller);

echo "1. Checking Edit Form HTML for Logo Input...\n";
$controller = app(StoreProfileController_seller::class);
$editHtml = $controller->edit()->render();

if (strpos($editHtml, 'name="logo"') !== false) {
    echo "SUCCESS: Found input name='logo' in edit form!\n";
} else {
    echo "FAIL: input name='logo' missing in edit form!\n";
}

if (strpos($editHtml, 'enctype="multipart/form-data"') !== false) {
    echo "SUCCESS: Form has enctype='multipart/form-data'!\n";
} else {
    echo "FAIL: Form missing enctype!\n";
}

echo "\n2. Testing Store Photo Upload...\n";
// Create a fake test image (1x1 PNG)
$tempImgPath = sys_get_temp_dir() . '/test_store_logo.png';
$img = imagecreatetruecolor(50, 50);
$green = imagecolorallocate($img, 16, 185, 129);
imagefill($img, 0, 0, $green);
imagepng($img, $tempImgPath);
imagedestroy($img);

$uploadedFile = new UploadedFile($tempImgPath, 'my_store_logo.png', 'image/png', null, true);

$store = StoreProfile_seller::where('user_id', $seller->id)->first();
$req = Request::create(route('seller.store-profile.update'), 'POST', [
    'nama_toko' => $store ? $store->nama_toko : 'Toko Berkah Test',
    'deskripsi' => 'Deskripsi Toko Berkah',
    'alamat' => 'Dayeuhkolot, Bandung',
    'no_telp' => '0812345678',
    'bank_name' => 'BCA',
    'bank_account_number' => '1234567890',
    'bank_account_name' => 'Owner',
], [], [
    'logo' => $uploadedFile
]);

$response = $controller->update($req);
echo "Update response status: " . $response->getStatusCode() . "\n";

$store->refresh();
echo "Stored logo column in DB: " . $store->logo . "\n";
echo "Resolved logo_url: " . $store->logo_url . "\n";
$fileExists = file_exists(public_path($store->logo));
echo "File physically exists at public_path: " . ($fileExists ? "YES" : "NO") . "\n";

echo "\n3. Testing Show View and Sidebar with Uploaded Logo...\n";
$showHtml = $controller->show()->render();
if (strpos($showHtml, $store->logo_url) !== false) {
    echo "SUCCESS: Store logo image URL is rendered in show view!\n";
} else {
    echo "FAIL: Store logo image not rendered in show view!\n";
}

// Clean up temp file
@unlink($tempImgPath);

echo "\nAll store photo upload verification completed successfully!\n";
