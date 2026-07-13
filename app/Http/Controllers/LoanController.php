<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Daftar semua pinjaman barber, beserta sisa hutangnya.
     */
    public function index(): View
    {
        $loans = Loan::with('barber')->latest('tanggal_pinjam')->paginate(15);

        $barbers = User::role('barber')->get();

        return view('loans.index', compact('loans', 'barbers'));
    }

    /**
     * Simpan pinjaman baru untuk seorang barber.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'barber_id' => ['required', 'exists:users,id'],
            'jumlah_pinjaman' => ['required', 'numeric', 'min:1'],
            'cicilan_per_hari' => ['required', 'numeric', 'min:1'],
            'catatan' => ['nullable', 'string'],
        ]);

        Loan::create([
            'barber_id' => $validated['barber_id'],
            'jumlah_pinjaman' => $validated['jumlah_pinjaman'],
            'cicilan_per_hari' => $validated['cicilan_per_hari'],
            'sisa_hutang' => $validated['jumlah_pinjaman'],
            'status' => 'aktif',
            'tanggal_pinjam' => now()->toDateString(),
            'catatan' => $validated['catatan'] ?? null,
            'input_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('loans.index')
            ->with('success', 'Pinjaman berhasil dicatat.');
    }
}