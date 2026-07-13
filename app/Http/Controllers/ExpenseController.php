<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Tampilkan daftar pengeluaran, terbaru duluan.
     */
    public function index()
    {
        $expenses = Expense::with('inputBy')->latest('tanggal')->paginate(15);

        $totalBulanIni = Expense::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        return view('expenses.index', compact('expenses', 'totalBulanIni'));
    }

    /**
     * Tampilkan form tambah pengeluaran baru.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Simpan pengeluaran baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'kategori' => ['required', 'in:rutin,insidental'],
            'nama' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $validated['input_by'] = $request->user()->id;

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Tampilkan form edit pengeluaran.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update data pengeluaran.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'kategori' => ['required', 'in:rutin,insidental'],
            'nama' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $expense->update($validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Hapus pengeluaran.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}