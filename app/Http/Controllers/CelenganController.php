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

        return redirect()
            ->route('celengan.show', $celengan)
            ->with('success', 'Transaksi celengan berhasil dicatat.');
    }

    /**
     * Hapus satu baris transaksi celengan. Saldo otomatis ter-update
     * lewat database trigger, tidak perlu dihitung manual di sini.
     */
    public function destroyTransaksi(Celengan $celengan, CelenganTransaksi $transaksi): RedirectResponse
    {
        $transaksi->delete();

        return redirect()
            ->route('celengan.show', $celengan)
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Hitung ulang saldo SEMUA celengan berdasarkan riwayat transaksi
     * aslinya. Berguna untuk membetulkan data lama yang mungkin sempat
     * salah (misal dari bug lama, atau edit manual lewat Supabase
     * sebelum trigger database dipasang).
     */
    public function recalculateAll(): RedirectResponse
    {
        $celengans = Celengan::all();
        $jumlahDiperbaiki = 0;

        foreach ($celengans as $celengan) {
            $saldoBenar = $celengan->transaksis()
                ->get()
                ->sum(fn ($t) => $t->tipe === 'masuk' ? $t->nominal : -$t->nominal);

            if ((float) $celengan->saldo !== (float) $saldoBenar) {
                $celengan->update(['saldo' => $saldoBenar]);
                $jumlahDiperbaiki++;
            }
        }

        $pesan = $jumlahDiperbaiki > 0
            ? "{$jumlahDiperbaiki} celengan diperbaiki karena saldonya tidak sesuai."
            : 'Semua saldo celengan sudah sesuai, tidak ada yang perlu diperbaiki.';

        return redirect()
            ->route('celengan.index')
            ->with('success', $pesan);
    }
}