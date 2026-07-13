<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-xl font-medium mb-6">Tambah Produk Baru</h1>

        <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('nama')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Jenis Produk</label>
                <select name="jenis" id="jenis" class="w-full border rounded-md px-3 py-2 text-sm">
                    <option value="">-- Pilih jenis --</option>
                    <option value="dijual" @selected(old('jenis') === 'dijual')>Dijual ke pelanggan</option>
                    <option value="pakai" @selected(old('jenis') === 'pakai')>Bahan habis pakai (untuk jasa)</option>
                </select>
                @error('jenis')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="harga-wrapper">
                <label class="block text-sm font-medium mb-1">Harga Jual</label>
                <x-currency-input name="harga" />
                <p class="text-xs text-gray-500 mt-1">Kosongkan kalau jenisnya bahan habis pakai.</p>
                @error('harga')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Stok Awal</label>
                <input type="number" name="stok" value="{{ old('stok', 0) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('stok')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Batas Minimum Stok</label>
                <input type="number" name="min_stok" value="{{ old('min_stok', 5) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">Sistem akan tandai "stok menipis" kalau stok di bawah angka ini.</p>
                @error('min_stok')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Simpan
                </button>
                <a href="{{ route('products.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>

    <script>
        // Sembunyikan field harga kalau jenisnya "pakai", biar nggak bikin
        // bingung user pas isi form (bahan habis pakai memang tidak dijual).
        const jenisSelect = document.getElementById('jenis');
        const hargaWrapper = document.getElementById('harga-wrapper');

        function toggleHarga() {
            hargaWrapper.style.display = jenisSelect.value === 'pakai' ? 'none' : 'block';
        }

        jenisSelect.addEventListener('change', toggleHarga);
        toggleHarga();
    </script>
</x-app-layout>