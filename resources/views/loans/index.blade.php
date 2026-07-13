<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <h1 class="text-xl font-medium mb-6">Pinjaman Barber</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form tambah pinjaman --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <p class="text-sm font-medium mb-3">Catat Pinjaman Baru</p>
            <form action="{{ route('loans.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Barber</label>
                        <select name="barber_id" class="w-full border rounded-md px-2 py-2 text-sm" required>
                            <option value="">-- Pilih barber --</option>
                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                            @endforeach
                        </select>
                        @error('barber_id')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jumlah Pinjaman</label>
                        <x-currency-input name="jumlah_pinjaman" required />
                        @error('jumlah_pinjaman')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Cicilan per Hari</label>
                    <x-currency-input name="cicilan_per_hari" required />
                    @error('cicilan_per_hari')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Catatan (opsional)</label>
                    <input type="text" name="catatan" placeholder="Misal: pinjam buat servis motor"
                           class="w-full border rounded-md px-2 py-2 text-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Simpan Pinjaman
                </button>
            </form>
        </div>

        {{-- Daftar pinjaman --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Barber</th>
                        <th class="px-4 py-3">Tanggal Pinjam</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Cicilan/Hari</th>
                        <th class="px-4 py-3">Sisa Hutang</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($loans as $loan)
                        <tr>
                            <td class="px-4 py-3">{{ $loan->barber->name }}</td>
                            <td class="px-4 py-3">{{ $loan->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($loan->jumlah_pinjaman, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($loan->cicilan_per_hari, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($loan->sisa_hutang, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @if ($loan->status === 'aktif')
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                Belum ada pinjaman tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $loans->links() }}
        </div>
    </div>
</x-app-layout>