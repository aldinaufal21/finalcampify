<?php

namespace App\Models\SellerModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Store;
use App\Models\User;

class StoreProfile_seller extends Store
{
    use HasFactory;

    protected $table = 'stores';
    
    protected $fillable = [
        'user_id', 'nama_toko', 'deskripsi', 'alamat', 'no_telp', 'logo', 'banner',
        'bank_name', 'bank_account_number', 'bank_account_name',
        'latitude', 'longitude'
    ];

    public function getNoTelpAttribute()
    {
        return $this->attributes['no_telp'] ?? $this->user?->phone;
    }

    public function setNoTelpAttribute($value): void
    {
        $this->attributes['no_telp'] = $value;
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

    public function user() {
        return $this->belongsTo(User::class);
    }
}
