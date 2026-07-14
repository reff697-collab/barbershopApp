<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_day_id',
        'total_omzet',
        'total_omzet_layanan',
        'total_omzet_produk',
        'total_komisi_barber',
        'total_pengeluaran',
        'total_kas_keluar',
        'laba_bersih',
        'closed_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function storeDay()
    {
        return $this->belongsTo(StoreDay::class);
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function barberDetails()
    {
        return $this->hasMany(ClosingHarianBarber::class);
    }
}