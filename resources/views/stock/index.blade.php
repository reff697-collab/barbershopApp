<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-xl font-medium mb-6">Manajemen Stok</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form restock --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <p class="text-sm font-medium mb-3">Tambah Stok (Restock)</p>
            <form action="{{ route('stock.restock') }}" method="POST" class="grid grid-cols-4 gap-2">
                @csrf
                <select name="product_id" class="border rounded-md px-2 py-2 text-sm col-span-2" required>
                    <option value="">-- Pilih produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->nama }} (stok: {{ $product->stok }})</option>
                    @endforeach
                </select>
                <input type="number" name="qty" min="1" placeholder="Qty" class="border rounded-md px-2 py-2 text-sm" required>
                <input type="text" name="keterangan" placeholder="Keterangan (opsional)" class="border rounded-md px-2 py-2 text-sm">
                <button type="submit" class="col-span-4 mt-1 px-3 py-2 bg-gray-800 text-white rounded-md text-sm w-fit">
                    Tambah Stok
                </button>
            </form>
            @error('product_id')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
            @error('qty')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status stok semua produk --}}
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <p class="text-sm font-medium p-4 pb-0">Status Stok Produk</p>
            <table class="w-full text-sm text-left mt-2">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Jenis</th>
                        <th class="px-4 py-2">Stok</th>
                        <th class="px-4 py-2">Batas Minimum</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-4 py-2">{{ $product->nama }}</td>
                            <td class="px-4 py-2">
                                @if ($product->jenis === 'dijual')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Dijual</span>
                                @else
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">Bahan Pakai</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $product->stok }}</td>
                            <td class="px-4 py-2">{{ $product->min_stok }}</td>
                            <td class="px-4 py-2">
                                @if ($product->isStokMenipis())
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Menipis</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Belum ada produk aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Riwayat mutasi --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <p class="text-sm font-medium p-4 pb-0">Riwayat Mutasi Stok</p>
            <table class="w-full text-sm text-left mt-2">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Tipe</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Stok Sebelum → Sesudah</th>
                        <th class="px-4 py-2">Keterangan</th>
                        <th class="px-4 py-2">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($movements as $mv)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $mv->created_at->format('d/m/y H:i') }}</td>
                            <td class="px-4 py-2">{{ $mv->product->nama ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if ($mv->tipe === 'masuk')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Masuk</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Keluar</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $mv->qty }}</td>
                            <td class="px-4 py-2">{{ $mv->stok_sebelum }} → {{ $mv->stok_sesudah }}</td>
                            <td class="px-4 py-2">{{ $mv->keterangan }}</td>
                            <td class="px-4 py-2">{{ $mv->createdBy->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-500">Belum ada riwayat mutasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>