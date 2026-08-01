<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Tampilkan daftar semua layanan.
     */
    public function index()
    {
        $services = Service::latest()->paginate(10);

        return view('services.index', compact('services'));
    }

    /**
     * Tampilkan form tambah layanan baru.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Simpan layanan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['hitung_pelanggan'] = $request->boolean('hitung_pelanggan');

        Service::create($validated);

        return redirect()
            ->route('services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit layanan.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update data layanan di database.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'hitung_pelanggan' => ['nullable', 'boolean'],
        ]);

        // Checkbox yang tidak dicentang tidak dikirim sama sekali oleh HTML,
        // jadi kita perlu set manual ke false kalau tidak ada di request.
        $validated['is_active'] = $request->boolean('is_active');
        $validated['hitung_pelanggan'] = $request->boolean('hitung_pelanggan');

        $service->update($validated);

        return redirect()
            ->route('services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Hapus layanan dari database.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}