@extends('SellerView.layouts.app_seller')

@section('content')
<div class="dashboard-header mb-5">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold m-0 text-dark">Edit Produk</h2>
            <p class="text-muted">Perbarui informasi produk dan stok barang Anda.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('seller.products.index') }}" class="btn btn-light rounded-pill px-4 border shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="card card-modern border-0 shadow-sm p-5" style="border-radius:16px;">
    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-5">
            {{-- DATA PRODUK --}}
            <div class="col-md-7">
                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Informasi Produk</h5>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control border-0 bg-light rounded-3 px-3 py-2 shadow-sm" value="{{ $product->nama_produk }}" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase ls-1">Kategori</label>
                        <select name="kategori" class="form-select border-0 bg-light rounded-3 px-3 py-2 shadow-sm" required>
                            <option value="tenda" {{ $product->kategori == 'tenda' ? 'selected' : '' }}>Tenda</option>
                            <option value="tas_gunung" {{ $product->kategori == 'tas_gunung' ? 'selected' : '' }}>Tas Gunung (Carrier)</option>
                            <option value="sepatu" {{ $product->kategori == 'sepatu' ? 'selected' : '' }}>Sepatu Outdoor</option>
                            <option value="alat_masak" {{ $product->kategori == 'alat_masak' ? 'selected' : '' }}>Alat Masak</option>
                            <option value="penerangan" {{ $product->kategori == 'penerangan' ? 'selected' : '' }}>Penerangan</option>
                            <option value="aksesoris" {{ $product->kategori == 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase ls-1">Jenis Produk</label>
                        <select name="jenis_produk" class="form-select border-0 bg-light rounded-3 px-3 py-2 shadow-sm" required>
                            <option value="jual" {{ $product->jenis_produk == 'jual' ? 'selected' : '' }}>Dijual</option>
                            <option value="sewa" {{ $product->jenis_produk == 'sewa' ? 'selected' : '' }}>Disewakan</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase ls-1">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control border-0 bg-light rounded-3 px-3 py-2 shadow-sm" value="{{ $product->harga }}" required>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Harga beli atau harga sewa per hari</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase ls-1">Stok</label>
                        <input type="number" name="stok" class="form-control border-0 bg-light rounded-3 px-3 py-2 shadow-sm" value="{{ $product->stok }}" required>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Jumlah unit yang tersedia</small>
                    </div>
                </div>

                {{-- ESCROW / JAMINAN SEWA --}}
                <div class="row g-3 mb-4" id="escrow_row" style="{{ $product->jenis_produk === 'sewa' ? '' : 'display: none;' }}">
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small text-uppercase ls-1">
                            <i class="bi bi-shield-check text-emerald me-1"></i> Escrow / Jaminan Sewa (Rp)
                        </label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text border-0 bg-light fw-bold px-3">Rp</span>
                            <input type="number" name="escrow_amount" id="escrow_amount" class="form-control border-0 bg-light px-3 py-2" 
                                   value="{{ $product->escrow_amount ?? 0 }}" placeholder="0" min="0">
                        </div>
                        <small class="text-muted mt-2 d-block" id="escrow_recommendation" style="font-size: 0.8rem;">
                            Nominal jaminan sewa saat ini: <strong class="text-emerald">Rp {{ number_format($product->escrow_amount ?? 0, 0, ',', '.') }}</strong>.<br>
                            Jaminan akan dititipkan penyewa selama masa sewa dan dikembalikan saat barang kembali tanpa kerusakan.
                        </small>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold text-muted small text-uppercase ls-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="5" class="form-control border-0 bg-light rounded-3 px-3 py-2 shadow-sm" required>{{ $product->deskripsi }}</textarea>
                </div>
            </div>

            {{-- MEDIA PRODUK --}}
            <div class="col-md-5">
                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Foto Produk</h5>
                
                <div class="mb-4 text-center">
                    <div class="p-2 bg-light rounded-4 mb-3 border dashed shadow-sm mx-auto" style="width: 250px; height: 250px;">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" class="w-100 h-100 object-fit-cover rounded-3">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center opacity-25 fs-1">🏕️</div>
                        @endif
                    </div>
                    <p class="small text-muted mb-3">Foto Saat Ini</p>
                    
                    <div class="p-4 bg-light rounded-4 border-2 border-dashed d-flex flex-column align-items-center justify-content-center" style="border-color: #cbd5e1 !important;">
                        <i class="bi bi-cloud-arrow-up fs-2 text-emerald mb-2"></i>
                        <p class="small text-muted mb-2">Pilih foto baru (opsional)</p>
                        <input type="file" name="gambar" class="form-control form-control-sm border-0 bg-transparent" accept="image/*">
                    </div>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-emerald py-3 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .object-fit-cover { object-fit: cover; }
    .dashed { border: 2px dashed #e2e8f0 !important; }
    .text-emerald { color: #10B981; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisProdukSelect = document.querySelector('select[name="jenis_produk"]');
    const escrowRow = document.getElementById('escrow_row');
    const hargaInput = document.querySelector('input[name="harga"]');
    const escrowInput = document.getElementById('escrow_amount');
    const escrowRecommendation = document.getElementById('escrow_recommendation');

    let userEditedEscrow = {{ ($product->escrow_amount ?? 0) > 0 ? 'true' : 'false' }};
    if (escrowInput) {
        escrowInput.addEventListener('input', () => { userEditedEscrow = true; });
    }

    function updateEscrowVisibility() {
        if (!escrowRow) return;
        if (jenisProdukSelect && jenisProdukSelect.value === 'sewa') {
            escrowRow.style.display = 'flex';
            updateEscrowRecommendation();
        } else {
            escrowRow.style.display = 'none';
        }
    }

    function updateEscrowRecommendation() {
        if (!escrowRecommendation || !hargaInput) return;
        const harga = parseFloat(hargaInput.value) || 0;
        const rekomendasi = Math.round(harga * 0.25);
        
        escrowRecommendation.innerHTML = `Rekomendasi jaminan (25% dari estimasi nilai sewa): <strong class="text-emerald">Rp ${rekomendasi.toLocaleString('id-ID')}</strong>.<br>Anda bebas menentukan nominal ini. Jaminan akan dikembalikan ke penyewa jika barang kembali tanpa kerusakan.`;
        
        if (!userEditedEscrow && harga > 0 && parseFloat(escrowInput.value || 0) === 0) {
            escrowInput.value = rekomendasi;
        }
    }

    if (jenisProdukSelect) {
        jenisProdukSelect.addEventListener('change', updateEscrowVisibility);
    }
    if (hargaInput) {
        hargaInput.addEventListener('input', updateEscrowRecommendation);
    }
    
    updateEscrowVisibility();
});
</script>
@endsection
