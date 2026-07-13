<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-xl font-medium mb-6">Edit Pengeluaran</h1>

        <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $expense->tanggal->toDateString()) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('tanggal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nama Pengeluaran</label>
                <input type="text" name="nama" value="{{ old('nama', $expense->nama) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('nama')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="kategori" class="w-full border rounded-md px-3 py-2 text-sm">
                    <option value="rutin" @selected(old('kategori', $expense->kategori) === 'rutin')>Rutin (listrik, air, wifi, sewa)</option>
                    <option value="insidental" @selected(old('kategori', $expense->kategori) === 'insidental')>Insidental (servis alat, dll)</option>
                </select>
                @error('kategori')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nominal</label>
                <x-currency-input name="nominal" :value="$expense->nominal" required />
                @error('nominal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Catatan (opsional)</label>
                <textarea name="catatan" rows="2" class="w-full border rounded-md px-3 py-2 text-sm">{{ old('catatan', $expense->catatan) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('expenses.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>