<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar semua produk.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Tampilkan form tambah produk baru.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Simpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit produk.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update data produk di database.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request);
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk dari database.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Validasi input produk.
     *
     * Field 'harga' cuma wajib diisi kalau jenisnya 'dijual' — karena
     * bahan habis pakai (jenis 'pakai') nggak punya harga jual ke
     * pelanggan, jadi default 0.
     */
    private function validateProduct(Request $request): array
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jenis' => ['required', 'in:dijual,pakai'],
            'harga' => ['required_if:jenis,dijual', 'nullable', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'min_stok' => ['nullable', 'integer', 'min:0'],
        ]);

        // Kalau jenisnya 'pakai', harga selalu 0 walaupun user isi sesuatu di form
        if ($validated['jenis'] === 'pakai') {
            $validated['harga'] = 0;
        }

        $validated['min_stok'] = $validated['min_stok'] ?? 5;

        return $validated;
    }
}