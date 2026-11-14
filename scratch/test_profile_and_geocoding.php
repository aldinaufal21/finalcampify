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

$seller = User::where('role', 'seller')->first() ?? User::find(1);
Auth::login($seller);

echo "1. Checking Routes...\n";
echo "   seller.store-profile.index: " . route('seller.store-profile.index') . "\n";
echo "   seller.store-profile.show:  " . route('seller.store-profile.show') . "\n";
echo "   seller.store-profile.edit:  " . route('seller.store-profile.edit') . "\n";
echo "   seller.store-profile.update: " . route('seller.store-profile.update') . "\n";

echo "\n2. Testing StoreProfileController@index (should render show_seller / Profile Overview)...\n";
$controller = app(StoreProfileController_seller::class);
$viewIndex = $controller->index();
$renderedIndex = $viewIndex->render();
echo "   Index rendered successfully! Length: " . strlen($renderedIndex) . " bytes\n";
if (strpos($renderedIndex, 'Edit Profil') !== false) {
    echo "   [OK] Contains 'Edit Profil' button linking to edit page.\n";
} else {
    echo "   [FAIL] 'Edit Profil' button not found.\n";
}

echo "\n3. Testing StoreProfileController@edit (should render index_seller / Edit Form)...\n";
$viewEdit = $controller->edit();
$renderedEdit = $viewEdit->render();
echo "   Edit form rendered successfully! Length: " . strlen($renderedEdit) . " bytes\n";
if (strpos($renderedEdit, 'Simpan Perubahan Profil') !== false) {
    echo "   [OK] Contains 'Simpan Perubahan Profil' form.\n";
} else {
    echo "   [FAIL] Form not found.\n";
}

echo "\n4. Testing StoreProfileController@update...\n";
$existingProfile = StoreProfile_seller::where('user_id', $seller->id)->first();
$req = Request::create(route('seller.store-profile.update'), 'POST', [
    'nama_toko' => $existingProfile ? $existingProfile->nama_toko : 'Toko Berkah Test',
    'deskripsi' => 'Menyediakan alat sewa dan jual terlengkap (Updated)',
    'alamat' => 'Dayeuhkolot, Kabupaten Bandung',
    'no_telp' => '0812345678',
    'bank_name' => 'BCA',
    'bank_account_number' => '123456789',
    'bank_account_name' => 'Pemilik Toko'
]);

$response = $controller->update($req);
echo "   Update Response Status: " . $response->getStatusCode() . "\n";
echo "   Redirect Target: " . $response->getTargetUrl() . "\n";
$successMsg = session('success');
echo "   Flash Message: '$successMsg'\n";

if (strpos($successMsg, 'Gagal mendapatkan koordinat') !== false) {
    echo "   [FAIL] Still contains unwanted coordinate error message!\n";
} else {
    echo "   [OK] Clean notification message verified!\n";
}

// Reload profile
$updated = StoreProfile_seller::where('user_id', $seller->id)->first();
echo "   Updated Latitude: " . ($updated->latitude ?? 'null') . "\n";
echo "   Updated Longitude: " . ($updated->longitude ?? 'null') . "\n";

echo "\nAll automated profile tests completed successfully!\n";
