<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Pinjaman Barber
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

        {{-- Form tambah pinjaman --}}
        <div class="mb-6">
            <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Catat Pinjaman Baru
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Input data pinjaman atau kasbon barber beserta komitmen cicilan hariannya.
                    </p>
                </div>

                <div class="p-4 sm:p-5">
                    <form action="{{ route('loans.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5">Barber</label>
                                <select name="barber_id" 
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent transition-all" required>
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
                                <label class="block text-xs font-medium text-gray-400 mb-1.5">Jumlah Pinjaman</label>
                                <x-currency-input name="jumlah_pinjaman" required />
                                @error('jumlah_pinjaman')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5">Cicilan per Hari</label>
                                <x-currency-input name="cicilan_per_hari" required />
                                @error('cicilan_per_hari')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-400 mb-1.5">Catatan (opsional)</label>
                                <input type="text" name="catatan" placeholder="Misal: pinjam buat servis motor"
                                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent transition-all">
                            </div>
                        </div>

                        {{-- Tombol diletakkan di sebelah kanan dengan lebar otomatis (tidak melebar berlebih) --}}
                        <div class="flex justify-end pt-2">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-5 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Simpan Pinjaman</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar pinjaman --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Daftar Pinjaman Barber
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Memantau sisa hutang dan status pelunasan pinjaman aktif barber.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Barber</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Tanggal Pinjam</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Jumlah</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Cicilan/Hari</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Sisa Hutang</th>
                                <th class="px-4 py-3 font-medium sm:px-6 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($loans as $loan)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        {{ $loan->barber->name }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-500">
                                        {{ $loan->tanggal_pinjam->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-700">
                                        Rp {{ number_format($loan->jumlah_pinjaman, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
                                        Rp {{ number_format($loan->cicilan_per_hari, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        Rp {{ number_format($loan->sisa_hutang, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-right">
                                        @if ($loan->status === 'aktif')
                                            <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-orange-600">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-green-600">
                                                Lunas
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada pinjaman tercatat
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Daftar pinjaman barber akan ditampilkan secara lengkap di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $loans->links() }}
        </div>

    </div>
</x-app-layout>