<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-xl font-medium mb-6">Edit Layanan</h1>

        <form action="{{ route('services.update', $service) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">
                    Nama Layanan
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $service->nama) }}"
                    class="w-full border rounded-md px-3 py-2 text-sm"
                >

                @error('nama')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Harga
                </label>

                <x-currency-input
                    name="harga"
                    :value="old('harga', $service->harga)"
                    required
                />

                @error('harga')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Status Layanan
                </label>

                <div class="flex items-start gap-2">
                    <input
                        type="checkbox"
                        name="is_active"
                        id="is_active"
                        value="1"
                        class="mt-0.5 rounded border-gray-300"
                        @checked(old('is_active', $service->is_active))
                    >

                    <label for="is_active" class="text-sm">
                        Layanan aktif
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Perhitungan Pelanggan
                </label>

                <div class="flex items-start gap-2">
                    <input
                        type="checkbox"
                        name="hitung_pelanggan"
                        id="hitung_pelanggan"
                        value="1"
                        class="mt-0.5 rounded border-gray-300"
                        @checked(old('hitung_pelanggan', $service->hitung_pelanggan))
                    >

                    <label for="hitung_pelanggan" class="text-sm">
                        Hitung sebagai pelanggan
                        <span class="block mt-1 text-xs text-gray-500">
                            Digunakan untuk layanan utama seperti Dewasa, Anak,
                            atau Bayi. Jangan aktifkan untuk layanan tambahan
                            seperti Semir atau Cuci Rambut.
                        </span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('services.index') }}"
                    class="text-sm text-gray-600"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>