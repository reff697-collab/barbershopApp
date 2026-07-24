<?php

namespace App\Http\Controllers;

use App\Models\Celengan;
use App\Models\CelenganTransaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CelenganController extends Controller
{
    /**
     * Daftar semua celengan beserta saldo masing-masing.
     */
    public function index(): View
    {
        $celengans = Celengan::orderBy('nama')->get();

        return view('celengan.index', compact('celengans'));
    }

    /**
     * Bikin celengan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:celengans,nama'],
        ]);

        Celengan::create([
            'nama' => $validated['nama'],
            'saldo' => 0,
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('celengan.index')
            ->with('success', 'Celengan berhasil dibuat.');
    }

    /**
     * Detail satu celengan beserta riwayat transaksinya.
     */
    public function show(Celengan $celengan): View
    {
        $riwayat = $celengan->transaksis()->with('createdBy')->latest()->get();

        return view('celengan.show', compact('celengan', 'riwayat'));
    }

    /**
     * Tambah transaksi (uang masuk/keluar) ke celengan.
     */
    public function addTransaksi(Request $request, Celengan $celengan): RedirectResponse
    {
        $validated = $request->validate([
            'tipe' => ['required', 'in:masuk,keluar'],
            'nominal' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validated['tipe'] === 'keluar' && $validated['nominal'] > $celengan->saldo) {
            return back()->with('error', 'Saldo celengan tidak cukup untuk penarikan ini.');
        }

        CelenganTransaksi::create([
            'celengan_id' => $celengan->id,
            'tipe' => $validated['tipe'],
            'nominal' => $validated['nominal'],
            'keterangan' => $validated['keterangan'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        if ($validated['tipe'] === 'masuk') {
            $celengan->increment('saldo', $validated['nominal']);
        } else {
            $celengan->decrement('saldo', $validated['nominal']);
        }

        return redirect()
            ->route('celengan.show', $celengan)
            ->with('success', 'Transaksi celengan berhasil dicatat.');
    }
}