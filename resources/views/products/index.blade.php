<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-medium">Daftar Produk</h1>
            <a href="{{ route('products.create') }}"
               class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                + Tambah Produk
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-4 py-3">{{ $product->nama }}</td>
                            <td class="px-4 py-3">
                                @if ($product->jenis === 'dijual')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Dijual</span>
                                @else
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs">Bahan Pakai</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {{ $product->jenis === 'dijual' ? 'Rp ' . number_format($product->harga, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $product->stok }}
                                @if ($product->isStokMenipis())
                                    <span class="ml-1 px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Menipis</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($product->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('products.edit', $product) }}"
                                   class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin mau hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                Belum ada produk. Klik "Tambah Produk" untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>