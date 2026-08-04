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
    public function store(Request $request, ClosingBulananService $closingBulananService): RedirectResponse
    {
        $bulan = now()->month;
        $tahun = now()->year;

        $closing = $closingBulananService->generate($bulan, $tahun, $request->user()->id);

        if (! $closing) {
            return redirect()
                ->route('closing-bulanan.index')
                ->with('error', 'Closing bulan ini sudah pernah dibuat, atau belum ada closing harian untuk direkap.');
        }

        return redirect()
            ->route('closing-bulanan.index')
            ->with('success', 'Closing bulanan berhasil dibuat.');
    }
}