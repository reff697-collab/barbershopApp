<?php

namespace App\Services;

use App\Models\ClosingBulanan;
use App\Models\ClosingHarian;

class ClosingBulananService
{
    /**
     * Generate closing bulanan untuk bulan & tahun tertentu.
     * Return null kalau sudah pernah dibuat atau belum ada data closing
     * harian untuk bulan itu (tidak ada yang perlu direkap).
     */
    public function generate(int $bulan, int $tahun, ?int $closedByUserId = null): ?ClosingBulanan
    {
        $sudahAda = ClosingBulanan::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if ($sudahAda) {
            return null;
        }

        $closingHarians = ClosingHarian::whereHas('storeDay', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        })->get();

        if ($closingHarians->isEmpty()) {
            return null;
        }

        return ClosingBulanan::create([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total_omzet' => $closingHarians->sum('total_omzet'),
            'total_omzet_layanan' => $closingHarians->sum('total_omzet_layanan'),
            'total_omzet_produk' => $closingHarians->sum('total_omzet_produk'),
            'total_komisi_barber' => $closingHarians->sum('total_komisi_barber'),
            'total_pengeluaran' => $closingHarians->sum('total_pengeluaran'),
            'laba_bersih' => $closingHarians->sum('laba_bersih'),
            'jumlah_hari_closing' => $closingHarians->count(),
            'closed_by' => $closedByUserId,
            'closed_at' => now(),
        ]);
    }
}