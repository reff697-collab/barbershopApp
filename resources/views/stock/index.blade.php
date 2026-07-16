<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Manajemen Stok
        </h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Informasi halaman --}}
        <div class="h-8 flex items-center justify-end">
            <p class="text-xs sm:text-sm text-gray-400">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Restock --}}
        <div class="mb-6">
            <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="shrink-0 flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-coral-50 text-coral-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                            Tambah Stok (Restock)
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                            Pilih produk dan masukkan kuantitas untuk menambahkan stok secara akurat.
                        </p>
                    </div>
                </div>

                {{-- Grid Form yang diperbaiki agar tidak tabrakan di ukuran layar apa pun --}}
                <form action="{{ route('stock.restock') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        {{-- Dropdown Produk (Mengambil 5 kolom di desktop) --}}
                        <div class="md:col-span-5">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5">Produk</label>
                            <select name="product_id" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent transition-all" required>
                                <option value="">-- Pilih produk --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->nama }} (stok: {{ $product->stok }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Quantity (Mengambil 2 kolom di desktop) --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5">Qty</label>
                            <input type="number" name="qty" min="1" placeholder="0" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent transition-all" required>
                        </div>

                        {{-- Input Keterangan (Mengambil 5 kolom di desktop) --}}
                        <div class="md:col-span-5">
                            <label class="block text-xs font-medium text-gray-400 mb-1.5">Keterangan (opsional)</label>
                            <input type="text" name="keterangan" placeholder="Misal: pengiriman dari supplier" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent transition-all">
                        </div>
                    </div>

                    {{-- Tombol Aksi di baris tersendiri dan diletakkan rapat kanan --}}
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-5 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Tambah Stok</span>
                        </button>
                    </div>
                </form>

                @error('product_id')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
                @error('qty')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Status Stok Produk --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-100 bg-white">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">Status Stok Produk</h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Overview batas minimum dan ketersediaan barang.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Nama</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Jenis</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Stok</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Batas Minimum</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($products as $product)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">{{ $product->nama }}</td>
                                    <td class="px-4 py-3 sm:px-6">
                                        @if ($product->jenis === 'dijual')
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-blue-600">
                                                Dijual
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-purple-600">
                                                Bahan Pakai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">{{ $product->stok }}</td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">{{ $product->min_stok }}</td>
                                    <td class="px-4 py-3 sm:px-6">
                                        @if ($product->isStokMenipis())
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-red-600">
                                                Menipis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-green-600">
                                                Aman
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Belum ada produk aktif</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Riwayat Mutasi --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-100 bg-white">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">Riwayat Mutasi Stok</h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Catatan log aktivitas keluar masuknya persediaan barang.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Waktu</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Produk</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Tipe</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Qty</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Perubahan Stok</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Keterangan</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Oleh</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($movements as $mv)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 whitespace-nowrap text-xs text-gray-500">
                                        {{ $mv->created_at->format('d/m/y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">
                                        {{ $mv->product->nama ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">
                                        @if ($mv->tipe === 'masuk')
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-green-600">
                                                Masuk
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-red-600">
                                                Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">
                                        {{ $mv->qty }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-500">
                                        {{ $mv->stok_sebelum }} <span class="text-gray-300">→</span> {{ $mv->stok_sesudah }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-500">
                                        {{ $mv->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600 font-medium">
                                        {{ $mv->createdBy->name ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Belum ada riwayat mutasi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Riwayat Mutasi --}}
                @if($movements->hasPages())
                    <div class="p-4 sm:p-5 border-t border-gray-100 bg-white">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>