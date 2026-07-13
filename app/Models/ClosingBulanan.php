<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClosingBulanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bulan',
        'tahun',
        'total_omzet',
        'total_omzet_layanan',
        'total_omzet_produk',
        'total_komisi_barber',
        'total_pengeluaran',
        'laba_bersih',
        'jumlah_hari_closing',
        'closed_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Nama bulan dalam Bahasa Indonesia, misal "Juli 2026".
     */
    public function getNamaBulanAttribute(): string
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $namaBulan[$this->bulan] . ' ' . $this->tahun;
    }
}