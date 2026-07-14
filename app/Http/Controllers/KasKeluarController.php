<?php

namespace App\Http\Controllers;

use App\Models\KasKeluar;
use App\Models\StoreDay;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KasKeluarController extends Controller
{
    /**
     * Daftar kas keluar hari ini + ringkasan kas seharusnya di laci.
     */
    public function index(): View
    {
        $storeDay = StoreDay::today();

        $kasKeluarList = KasKeluar::where('store_day_id', $storeDay->id)
            ->with('inputBy')
            ->latest()
            ->get();

        $totalKasKeluar = $kasKeluarList->sum('nominal');

        // Kas seharusnya = total transaksi TUNAI hari ini - kas keluar
        // (transaksi QRIS tidak masuk hitungan kas fisik di laci)
        $omzetTunai = \App\Models\Transaction::where('store_day_id', $storeDay->id)
            ->where('payment_method', 'tunai')
            ->sum('total');

        $kasSeharusnya = $omzetTunai - $totalKasKeluar;

        return view('kas-keluar.index', compact(
            'storeDay', 'kasKeluarList', 'totalKasKeluar', 'omzetTunai', 'kasSeharusnya'
        ));
    }

    /**
     * Simpan kas keluar baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $storeDay = StoreDay::today();

        $validated = $request->validate([
            'nominal' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'keterangan' => ['required', 'string', 'max:255'],
        ]);

        KasKeluar::create([
            'store_day_id' => $storeDay->id,
            'nominal' => $validated['nominal'],
            'keterangan' => $validated['keterangan'],
            'input_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('kas-keluar.index')
            ->with('success', 'Kas keluar berhasil dicatat.');
    }
}