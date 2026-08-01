<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-xl font-medium mb-6">Tambah Layanan Baru</h1>

        <form action="{{ route('services.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">Nama Layanan</label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('nama')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Harga</label>
                <x-currency-input name="harga" required />
                @error('harga')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="hitung_pelanggan" id="hitung_pelanggan" value="1" checked>
                <label for="hitung_pelanggan" class="text-sm">
                    Hitung sebagai pelanggan (untuk layanan utama seperti Dewasa/Anak/Bayi, bukan add-on seperti Semir/Cuci Rambut)
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Simpan
                </button>
                <a href="{{ route('services.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>