<?php

namespace App\Http\Controllers;

use App\Models\ClosingBulanan;
use App\Models\ClosingHarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClosingBulananController extends Controller
{
    /**
     * Daftar semua closing bulanan yang pernah dibuat.
     */
    public function index(): View
    {
        $closings = ClosingBulanan::orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(12);

        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $sudahClosingBulanIni = ClosingBulanan::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->exists();

        // Hitung berapa closing harian yang tersedia bulan ini,
        // buat ditampilkan sebagai preview sebelum Owner klik "Tutup Bulan Ini".
        $closingHarianBulanIni = ClosingHarian::whereHas('storeDay', function ($query) use ($bulanIni, $tahunIni) {
            $query->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
        })->get();

        return view('closing-bulanan.index', compact(
            'closings',
            'sudahClosingBulanIni',
            'closingHarianBulanIni',
            'bulanIni',
            'tahunIni'
        ));
    }

    /**
     * Generate closing bulanan untuk bulan berjalan.
     * Murni SUM dari semua closing harian di bulan itu, tanpa perhitungan tambahan.
     */
    public function store(Request $request): RedirectResponse
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $sudahAda = ClosingBulanan::where('bulan', $bulan)->where('tahun', $tahun)->exists();

        if ($sudahAda) {
            return redirect()
                ->route('closing-bulanan.index')
                ->with('error', 'Closing bulan ini sudah pernah dibuat.');
        }

        $closingHarians = ClosingHarian::whereHas('storeDay', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        })->get();

        if ($closingHarians->isEmpty()) {
            return redirect()
                ->route('closing-bulanan.index')
                ->with('error', 'Belum ada closing harian di bulan ini untuk direkap.');
        }

        ClosingBulanan::create([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total_omzet' => $closingHarians->sum('total_omzet'),
            'total_omzet_layanan' => $closingHarians->sum('total_omzet_layanan'),
            'total_omzet_produk' => $closingHarians->sum('total_omzet_produk'),
            'total_komisi_barber' => $closingHarians->sum('total_komisi_barber'),
            'total_pengeluaran' => $closingHarians->sum('total_pengeluaran'),
            'laba_bersih' => $closingHarians->sum('laba_bersih'),
            'jumlah_hari_closing' => $closingHarians->count(),
            'closed_by' => $request->user()->id,
            'closed_at' => now(),
        ]);

        return redirect()
            ->route('closing-bulanan.index')
            ->with('success', 'Closing bulanan berhasil dibuat.');
    }
}