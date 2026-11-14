<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nama_toko',
        'status',        // pending | active | rejected | suspended | banned
        'last_active',
        'alasan_ban',
        'deskripsi',
        'alamat',
        'logo',
        'catatan_admin',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'last_active' => 'datetime',
    ];

    // 🔥 RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔥 RELASI KE PRODUK
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (empty($this->logo)) {
            return null;
        }

        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        $cleanPath = str_replace('\\', '/', $this->logo);

        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        if (file_exists(public_path('assets/images/stores/' . basename($cleanPath)))) {
            return asset('assets/images/stores/' . basename($cleanPath));
        }

        if (file_exists(public_path('storage/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        return asset($cleanPath);
    }

    // 🔥 RELASI KE TRANSAKSI MELALUI PRODUK
    public function transactions()
    {
        return $this->hasManyThrough(\App\Models\Transaction::class, \App\Models\Product::class);
    }
}
