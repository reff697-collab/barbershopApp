<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-xl font-medium mb-6">Edit Produk</h1>

        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama', $product->nama) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('nama')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Jenis Produk</label>
                <select name="jenis" id="jenis" class="w-full border rounded-md px-3 py-2 text-sm">
                    <option value="dijual" @selected(old('jenis', $product->jenis) === 'dijual')>Dijual ke pelanggan</option>
                    <option value="pakai" @selected(old('jenis', $product->jenis) === 'pakai')>Bahan habis pakai (untuk jasa)</option>
                </select>
                @error('jenis')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="harga-wrapper">
                <label class="block text-sm font-medium mb-1">Harga Jual</label>
                <x-currency-input name="harga" :value="$product->harga" />
                @error('harga')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $product->stok) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('stok')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Batas Minimum Stok</label>
                <input type="number" name="min_stok" value="{{ old('min_stok', $product->min_stok) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('min_stok')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       @checked($product->is_active)>
                <label for="is_active" class="text-sm">Produk aktif</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('products.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>

    <script>
        const jenisSelect = document.getElementById('jenis');
        const hargaWrapper = document.getElementById('harga-wrapper');

        function toggleHarga() {
            hargaWrapper.style.display = jenisSelect.value === 'pakai' ? 'none' : 'block';
        }

        jenisSelect.addEventListener('change', toggleHarga);
        toggleHarga();
    </script>
</x-app-layout>