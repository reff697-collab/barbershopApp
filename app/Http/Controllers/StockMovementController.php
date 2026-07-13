<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Halaman manajemen stok: daftar produk dengan status stok,
     * plus riwayat mutasi terbaru.
     */
    public function index(): View
    {
        $products = Product::where('jenis', 'dijual')
            ->orderBy('nama')
            ->get();

        $movements = StockMovement::with(['product', 'createdBy'])
            ->latest()
            ->paginate(15);

        return view('stock.index', compact('products', 'movements'));
    }

    /**
     * Proses restock manual untuk satu produk.
     */
    public function restock(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $stokSebelum = $product->stok;

        $product->increment('stok', $validated['qty']);

        StockMovement::create([
            'product_id' => $product->id,
            'tipe' => 'masuk',
            'qty' => $validated['qty'],
            'stok_sebelum' => $stokSebelum,
            'stok_sesudah' => $stokSebelum + $validated['qty'],
            'keterangan' => $validated['keterangan'] ?: 'Restock manual',
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('stock.index')
            ->with('success', "Stok {$product->nama} berhasil ditambah {$validated['qty']}.");
    }
}