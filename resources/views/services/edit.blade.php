<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-xl font-medium mb-6">Edit Layanan</h1>

        <form action="{{ route('services.update', $service) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Nama Layanan</label>
                <input type="text" name="nama" value="{{ old('nama', $service->nama) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('nama')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Harga</label>
                <x-currency-input name="harga" :value="$service->harga" required />
                @error('harga')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       @checked($service->is_active)>
                <label for="is_active" class="text-sm">Layanan aktif</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('services.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>