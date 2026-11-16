 @extends('layouts.app_pembeli')

@section('content')
<div class="py-12 max-w-4xl mx-auto px-4">

    <a href="{{ route('cart.index') }}" class="text-sm text-green-600 mb-4 inline-block">
        ← Kembali ke Keranjang
    </a>

    <h1 class="text-3xl font-bold mb-6">Checkout</h1>

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border-2 border-rose-200 rounded-2xl text-rose-800">
            <div class="flex items-center gap-2 font-bold mb-1">
                <span>⚠️</span>
                <span>Mohon lengkapi data berikut:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkout-form">
        @csrf

        {{-- Hidden inputs for selected items --}}
        @foreach($cart as $item)
            <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
        @endforeach

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- LEFT: FORM -->
            <div class="space-y-6">

                {{-- ALAMAT PENGIRIMAN --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <h2 class="text-lg font-bold mb-4">Alamat Pengiriman</h2>
                    
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="nama" value="{{ auth()->user()->name }}" class="w-full rounded-lg border-gray-300" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="w-full rounded-lg border-gray-300" required>{{ old('alamat', auth()->user()->address) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                                <select name="kota" id="city-checkout" class="w-full rounded-lg border-gray-300" required>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                <select name="kecamatan" id="district-checkout" class="w-full rounded-lg border-gray-300" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos <span class="text-rose-500">*</span></label>
                                <input type="text" name="kode_pos" id="postal_code-checkout" class="w-full rounded-lg border-gray-300 focus:border-green-500" required value="{{ old('kode_pos', auth()->user()->postal_code) }}" placeholder="Kode Pos">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="tel" name="telepon" value="{{ old('telepon', auth()->user()->phone) }}" class="w-full rounded-lg border-gray-300" required>
                        </div>
                    </div>
                </div>

                {{-- METODE PEMBAYARAN --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <h2 class="text-lg font-bold mb-4">Metode Pembayaran</h2>

                    <div class="grid grid-cols-1 gap-4">
                        <label class="border rounded-lg p-4 bg-gray-50 flex items-center">
                            <input type="radio" name="metode_pembayaran" value="transfer" checked class="mr-2 text-green-600">
                            <span class="font-semibold text-slate-800">Transfer Bank</span>
                        </label>
                    </div>

                    {{-- INFO REKENING --}}
                    <div id="checkout_payment_info" class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <div id="checkout_info_transfer">
                            <p class="text-sm font-bold mb-2">Transfer ke Rekening Admin:</p>
                            @if(isset($admin) && $admin->bank_account_number)
                                <div class="p-3 bg-white border rounded flex justify-between items-center">
                                    <div>
                                        <p class="text-xs font-bold text-green-600 uppercase">{{ $admin->bank_name ?? 'Transfer Bank' }}</p>
                                        <p class="text-sm font-bold text-lg text-slate-800">{{ $admin->bank_account_number }}</p>
                                        <p class="text-xs text-gray-500">A/N: {{ $admin->bank_account_name }}</p>
                                    </div>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ $admin->bank_account_number }}').then(() => alert('Nomor rekening disalin!'))" class="text-green-600 font-bold text-xs hover:underline">Salin</button>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Admin belum mengatur informasi rekening tujuan.</p>
                            @endif
                        </div>
                    </div>

                    {{-- UPLOAD BUKTI --}}
                    <div id="proof_upload_container" class="mt-4 pt-4 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-800 mb-1">
                            Upload Bukti Pembayaran <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-xs text-slate-500 mb-2">Unggah bukti transfer (JPG, PNG, WebP, maks. 2MB) sebelum menekan tombol Bayar Sekarang.</p>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran_input" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-100 file:text-green-800 hover:file:bg-green-200 cursor-pointer" required>
                        <div id="proof_preview_box" class="mt-3 hidden">
                            <p class="text-xs text-slate-500 mb-1">Preview Bukti:</p>
                            <img id="proof_preview_img" src="" alt="Preview Bukti" class="h-32 object-contain rounded-xl border border-slate-200 bg-slate-50 p-1">
                        </div>
                    </div>
                </div>

                {{-- METODE PENGIRIMAN --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border">
                    <h2 class="text-lg font-bold mb-4">Opsi Pengiriman</h2>
                    <div class="space-y-3">
                        @foreach($couriers as $index => $courier)
                        <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <input type="radio" name="shipping_method" value="{{ $courier->service }}" data-price="{{ $courier->price }}" {{ $index === 0 ? 'checked' : '' }} class="mr-3 text-green-600">
                                <div>
                                    <p class="font-semibold text-base">{{ $courier->service }}</p>
                                    <p class="text-xs text-gray-500">Estimasi {{ $courier->estimate ?? '-' }} &bull; Kurir: {{ $courier->name }}</p>
                                </div>
                            </div>
                            <span class="font-bold text-green-700">Rp {{ number_format($courier->price, 0, ',', '.') }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIGHT: SUMMARY -->
            <div class="space-y-6">
                <div class="bg-gray-900 text-white p-6 rounded-xl shadow-lg sticky top-24">
                    <h2 class="text-xl font-bold mb-6">Ringkasan Pesanan</h2>

                    <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2">
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                            @php
                                $price = $item->type === 'buy' ? $item->product->buy_price : $item->product->rent_price * $item->duration;
                                $subtotal = $price * $item->qty;
                                $total += $subtotal;
                            @endphp
                            <div class="flex gap-3">
                                <img src="{{ $item->product->image_url }}" class="w-12 h-12 object-cover rounded">
                                <div class="flex-1 min-w-0 text-sm">
                                    <p class="font-bold truncate">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $item->qty }}x | {{ $item->type === 'buy' ? 'Beli' : 'Sewa' }}</p>
                                </div>
                                <p class="text-sm font-bold">Rp {{ number_format($subtotal) }}</p>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $selectedCourierPrice = $couriers->first()->price ?? 0;
                    @endphp

                    <div class="border-t border-gray-800 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Ongkir</span>
                            <span id="shippingTotal">Rp {{ number_format($selectedCourierPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold pt-4 text-white">
                            <span>Total</span>
                            <span id="grandTotal" class="text-emerald-400">Rp {{ number_format($total + $selectedCourierPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" id="submitOrderBtn" class="w-full mt-8 bg-green-600 hover:bg-green-700 text-white py-3.5 rounded-xl font-bold transition shadow-lg shadow-green-900/40 flex items-center justify-center gap-2 cursor-pointer">
                        Bayar Sekarang
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataAlamat = [
            {
                kota: 'Kota Bandung',
                kecamatan: [
                    { nama: 'Andir', kode_pos: '40184' }, { nama: 'Antapani', kode_pos: '40291' }, { nama: 'Arcamanik', kode_pos: '40293' }, { nama: 'Astanaanyar', kode_pos: '40242' },
                    { nama: 'Babakan Ciparay', kode_pos: '40223' }, { nama: 'Bandung Kidul', kode_pos: '40266' }, { nama: 'Bandung Kulon', kode_pos: '40214' }, { nama: 'Bandung Wetan', kode_pos: '40116' },
                    { nama: 'Batununggal', kode_pos: '40275' }, { nama: 'Bojongloa Kaler', kode_pos: '40232' }, { nama: 'Bojongloa Kidul', kode_pos: '40233' }, { nama: 'Buahbatu', kode_pos: '40286' },
                    { nama: 'Cibeunying Kaler', kode_pos: '40191' }, { nama: 'Cibeunying Kidul', kode_pos: '40125' }, { nama: 'Cibiru', kode_pos: '40614' }, { nama: 'Cicendo', kode_pos: '40171' },
                    { nama: 'Cidadap', kode_pos: '40142' }, { nama: 'Cinambo', kode_pos: '40294' }, { nama: 'Coblong', kode_pos: '40132' }, { nama: 'Gedebage', kode_pos: '40294' },
                    { nama: 'Kiaracondong', kode_pos: '40284' }, { nama: 'Lengkong', kode_pos: '40261' }, { nama: 'Mandalajati', kode_pos: '40194' }, { nama: 'Panyileukan', kode_pos: '40614' },
                    { nama: 'Rancasari', kode_pos: '40286' }, { nama: 'Regol', kode_pos: '40252' }, { nama: 'Sukajadi', kode_pos: '40161' }, { nama: 'Sukasari', kode_pos: '40151' },
                    { nama: 'Sumur Bandung', kode_pos: '40111' }, { nama: 'Ujungberung', kode_pos: '40611' }
                ]
            },
            {
                kota: 'Kabupaten Bandung',
                kecamatan: [
                    { nama: 'Arjasari', kode_pos: '40379' }, { nama: 'Baleendah', kode_pos: '40375' }, { nama: 'Banjaran', kode_pos: '40377' }, { nama: 'Bojongsoang', kode_pos: '40288' },
                    { nama: 'Cangkuang', kode_pos: '40238' }, { nama: 'Cicalengka', kode_pos: '40395' }, { nama: 'Cikancung', kode_pos: '40396' }, { nama: 'Cilengkrang', kode_pos: '40615' },
                    { nama: 'Cileunyi', kode_pos: '40622' }, { nama: 'Cimaung', kode_pos: '40374' }, { nama: 'Cimenyan', kode_pos: '40197' }, { nama: 'Ciparay', kode_pos: '40381' },
                    { nama: 'Ciwidey', kode_pos: '40973' }, { nama: 'Dayeuhkolot', kode_pos: '40258' }, { nama: 'Katapang', kode_pos: '40921' }, { nama: 'Majalaya', kode_pos: '40382' },
                    { nama: 'Margaasih', kode_pos: '40215' }, { nama: 'Margahayu', kode_pos: '40226' }, { nama: 'Pangalengan', kode_pos: '40378' }, { nama: 'Paseh', kode_pos: '40383' },
                    { nama: 'Rancaekek', kode_pos: '40394' }, { nama: 'Soreang', kode_pos: '40911' }
                ]
            },
            {
                kota: 'Kabupaten Bandung Barat',
                kecamatan: [
                    { nama: 'Batujajar', kode_pos: '40561' }, { nama: 'Cihampelas', kode_pos: '40562' }, { nama: 'Cikalongwetan', kode_pos: '40556' }, { nama: 'Cililin', kode_pos: '40562' },
                    { nama: 'Cipatat', kode_pos: '40554' }, { nama: 'Cipeundeuy', kode_pos: '40558' }, { nama: 'Cipongkor', kode_pos: '40564' }, { nama: 'Cisarua', kode_pos: '40551' },
                    { nama: 'Gununghalu', kode_pos: '40565' }, { nama: 'Lembang', kode_pos: '40391' }, { nama: 'Ngamprah', kode_pos: '40552' }, { nama: 'Padalarang', kode_pos: '40553' },
                    { nama: 'Parongpong', kode_pos: '40559' }, { nama: 'Sindangkerta', kode_pos: '40563' }
                ]
            },
            {
                kota: 'Kota Cimahi',
                kecamatan: [
                    { nama: 'Cimahi Selatan', kode_pos: '40531' }, { nama: 'Cimahi Tengah', kode_pos: '40521' }, { nama: 'Cimahi Utara', kode_pos: '40511' }
                ]
            },
            {
                kota: 'Kota Depok',
                kecamatan: [
                    { nama: 'Beji', kode_pos: '16421' }, { nama: 'Bojongsari', kode_pos: '16516' }, { nama: 'Cilodong', kode_pos: '16413' }, { nama: 'Cimanggis', kode_pos: '16451' },
                    { nama: 'Cinere', kode_pos: '16514' }, { nama: 'Cipayung', kode_pos: '16437' }, { nama: 'Limo', kode_pos: '16515' }, { nama: 'Pancoran Mas', kode_pos: '16431' },
                    { nama: 'Sawangan', kode_pos: '16511' }, { nama: 'Sukmajaya', kode_pos: '16411' }, { nama: 'Tapos', kode_pos: '16457' }
                ]
            },
            {
                kota: 'Kota Bogor',
                kecamatan: [
                    { nama: 'Bogor Barat', kode_pos: '16111' }, { nama: 'Bogor Selatan', kode_pos: '16131' }, { nama: 'Bogor Tengah', kode_pos: '16121' }, { nama: 'Bogor Timur', kode_pos: '16141' },
                    { nama: 'Bogor Utara', kode_pos: '16151' }, { nama: 'Tanah Sareal', kode_pos: '16161' }
                ]
            },
            {
                kota: 'Majalengka',
                kecamatan: [
                    { nama: 'Kadipaten', kode_pos: '45452' }, { nama: 'Jatiwangi', kode_pos: '45454' }, { nama: 'Majalengka', kode_pos: '45411' }, { nama: 'Bantarujeg', kode_pos: '45464' }
                ]
            },
            {
                kota: 'DKI Jakarta',
                kecamatan: [
                    { nama: 'Gambir', kode_pos: '10110' }, { nama: 'Menteng', kode_pos: '10310' }, { nama: 'Kebayoran Baru', kode_pos: '12110' },
                    { nama: 'Cilandak', kode_pos: '12430' }, { nama: 'Kelapa Gading', kode_pos: '14240' }, { nama: 'Pondok Indah', kode_pos: '12310' }
                ]
            },
            {
                kota: 'Kota Bekasi',
                kecamatan: [
                    { nama: 'Bekasi Barat', kode_pos: '17145' }, { nama: 'Bekasi Timur', kode_pos: '17111' }, { nama: 'Bekasi Selatan', kode_pos: '17148' }, { nama: 'Bekasi Utara', kode_pos: '17121' }
                ]
            },
            {
                kota: 'Kota Tangerang',
                kecamatan: [
                    { nama: 'Tangerang', kode_pos: '15111' }, { nama: 'Cipondoh', kode_pos: '15148' }, { nama: 'Karawaci', kode_pos: '15115' }, { nama: 'Serpong', kode_pos: '15310' }
                ]
            }
        ];

        const citySelect = document.getElementById('city-checkout');
        const districtSelect = document.getElementById('district-checkout');
        const postalInput = document.getElementById('postal_code-checkout');
        const checkoutForm = document.getElementById('checkout-form');
        const submitOrderBtn = document.getElementById('submitOrderBtn');
        const proofInput = document.getElementById('bukti_pembayaran_input');
        const proofPreviewBox = document.getElementById('proof_preview_box');
        const proofPreviewImg = document.getElementById('proof_preview_img');

        // Populate Cities
        if (citySelect && districtSelect && postalInput) {
            dataAlamat.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.kota;
                opt.textContent = item.kota;
                citySelect.appendChild(opt);
            });

            // Option to add custom city
            const otherCityOpt = document.createElement('option');
            otherCityOpt.value = '__custom_city__';
            otherCityOpt.textContent = '+ Kota/Kabupaten Lainnya...';
            citySelect.appendChild(otherCityOpt);

            function updateDistricts(selectedCity) {
                districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                const found = dataAlamat.find(k => k.kota.toLowerCase() === (selectedCity || '').toLowerCase());
                if (found) {
                    found.kecamatan.forEach(kec => {
                        const opt = document.createElement('option');
                        opt.value = kec.nama;
                        opt.textContent = kec.nama;
                        districtSelect.appendChild(opt);
                    });
                }
                const otherDistOpt = document.createElement('option');
                otherDistOpt.value = '__custom_district__';
                otherDistOpt.textContent = '+ Kecamatan Lainnya...';
                districtSelect.appendChild(otherDistOpt);
            }

            citySelect.addEventListener('change', function() {
                if (citySelect.value === '__custom_city__') {
                    const customCity = prompt('Masukkan Nama Kota / Kabupaten Anda:');
                    if (customCity && customCity.trim()) {
                        const opt = document.createElement('option');
                        opt.value = customCity.trim();
                        opt.textContent = customCity.trim();
                        citySelect.insertBefore(opt, otherCityOpt);
                        citySelect.value = customCity.trim();
                    } else {
                        citySelect.value = '';
                    }
                }
                postalInput.value = '';
                updateDistricts(citySelect.value);
            });

            districtSelect.addEventListener('change', function() {
                if (districtSelect.value === '__custom_district__') {
                    const customDist = prompt('Masukkan Nama Kecamatan Anda:');
                    if (customDist && customDist.trim()) {
                        const opt = document.createElement('option');
                        opt.value = customDist.trim();
                        opt.textContent = customDist.trim();
                        districtSelect.insertBefore(opt, districtSelect.lastElementChild);
                        districtSelect.value = customDist.trim();
                    } else {
                        districtSelect.value = '';
                    }
                }
                const kota = dataAlamat.find(k => k.kota.toLowerCase() === (citySelect.value || '').toLowerCase());
                if (kota) {
                    const kec = kota.kecamatan.find(d => d.nama.toLowerCase() === (districtSelect.value || '').toLowerCase());
                    if (kec && kec.kode_pos) {
                        postalInput.value = kec.kode_pos;
                    }
                }
            });

            // Set default / user address if already exists in DB
            const defaultCity = "{{ old('kota', auth()->user()->city ?? '') }}";
            const defaultDistrict = "{{ old('kecamatan', auth()->user()->district ?? '') }}";

            if (defaultCity) {
                let cityOpt = Array.from(citySelect.options).find(o => o.value.toLowerCase() === defaultCity.toLowerCase());
                if (!cityOpt) {
                    cityOpt = document.createElement('option');
                    cityOpt.value = defaultCity;
                    cityOpt.textContent = defaultCity;
                    citySelect.insertBefore(cityOpt, otherCityOpt);
                }
                citySelect.value = defaultCity;
                updateDistricts(defaultCity);
            }

            if (defaultDistrict) {
                let distOpt = Array.from(districtSelect.options).find(o => o.value.toLowerCase() === defaultDistrict.toLowerCase());
                if (!distOpt) {
                    distOpt = document.createElement('option');
                    distOpt.value = defaultDistrict;
                    distOpt.textContent = defaultDistrict;
                    districtSelect.insertBefore(distOpt, districtSelect.lastElementChild);
                }
                districtSelect.value = defaultDistrict;
            }
        }

        // Preview & validation for proof of payment
        if (proofInput) {
            proofInput.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('⚠️ Ukuran file bukti pembayaran maksimal 2MB. Silakan gunakan file gambar yang lebih kecil.');
                        this.value = '';
                        if (proofPreviewBox) proofPreviewBox.classList.add('hidden');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (proofPreviewImg && proofPreviewBox) {
                            proofPreviewImg.src = e.target.result;
                            proofPreviewBox.classList.remove('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    if (proofPreviewBox) proofPreviewBox.classList.add('hidden');
                }
            });
        }

        // Shipping selection total updates
        const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
        const shippingTotal = document.getElementById('shippingTotal');
        const grandTotal = document.getElementById('grandTotal');
        const orderSubtotal = {{ $total }};

        function updateTotals() {
            let shipping = 0;
            const selected = document.querySelector('input[name="shipping_method"]:checked');
            if (selected) {
                shipping = parseFloat(selected.dataset.price) || 0;
            }
            if (shippingTotal) {
                shippingTotal.textContent = 'Rp ' + shipping.toLocaleString('id-ID');
            }
            if (grandTotal) {
                grandTotal.textContent = 'Rp ' + (orderSubtotal + shipping).toLocaleString('id-ID');
            }
        }

        shippingRadios.forEach(radio => radio.addEventListener('change', updateTotals));
        updateTotals();

        // Form submit validation & user feedback
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                const nameInput = document.querySelector('input[name="nama"]');
                const addrInput = document.querySelector('textarea[name="alamat"]');
                const phoneInput = document.querySelector('input[name="telepon"]');
                const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');

                if (nameInput && !nameInput.value.trim()) {
                    e.preventDefault();
                    alert('⚠️ Mohon isi Nama Penerima.');
                    nameInput.focus();
                    nameInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (addrInput && !addrInput.value.trim()) {
                    e.preventDefault();
                    alert('⚠️ Mohon isi Alamat Lengkap pengiriman.');
                    addrInput.focus();
                    addrInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (citySelect && !citySelect.value) {
                    e.preventDefault();
                    alert('⚠️ Mohon pilih Kota pengiriman.');
                    citySelect.focus();
                    citySelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (districtSelect && !districtSelect.value) {
                    e.preventDefault();
                    alert('⚠️ Mohon pilih Kecamatan pengiriman.');
                    districtSelect.focus();
                    districtSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (postalInput && !postalInput.value.trim()) {
                    e.preventDefault();
                    alert('⚠️ Mohon isi Kode Pos pengiriman.');
                    postalInput.focus();
                    postalInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (phoneInput && !phoneInput.value.trim()) {
                    e.preventDefault();
                    alert('⚠️ Mohon isi Nomor Telepon yang dapat dihubungi.');
                    phoneInput.focus();
                    phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (!selectedShipping) {
                    e.preventDefault();
                    alert('⚠️ Mohon pilih salah satu Opsi Pengiriman kurir.');
                    return false;
                }

                if (proofInput && (!proofInput.files || proofInput.files.length === 0)) {
                    e.preventDefault();
                    alert('⚠️ Bukti Pembayaran wajib diunggah! Silakan pilih foto/gambar bukti transfer Anda terlebih dahulu.');
                    proofInput.focus();
                    proofInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                // If all valid, set button to loading state
                if (submitOrderBtn) {
                    submitOrderBtn.disabled = true;
                    submitOrderBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    submitOrderBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Memproses Pesanan...
                    `;
                }
            });
        }
    });
</script>
@endsection
