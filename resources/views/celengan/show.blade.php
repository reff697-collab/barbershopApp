<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 mb-6">
            <h1 class="text-xl font-medium text-gray-800 truncate">
                {{ $celengan->nama }}
            </h1>

            <a href="{{ route('celengan.index') }}"
               class="text-sm text-gray-600 hover:text-gray-800">
                Kembali
            </a>
        </div>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi error --}}
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validasi --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Saldo --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">
                    Saldo Saat Ini
                </p>

                <p class="text-2xl font-semibold text-gray-800">
                    Rp {{ number_format($celengan->saldo, 0, ',', '.') }}
                </p>
            </div>

            <div class="shrink-0 flex items-center justify-center w-11 h-11 rounded-lg bg-white border border-gray-200 text-gray-500">
                <svg class="w-5 h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M5 11a7 7 0 0114 0v4a3 3 0 01-3 3h-1l-1 2h-4l-1-2H8a3 3 0 01-3-3v-4zm2-2L5 7m12 2l2-2M8 13h.01M16 13h.01"/>
                </svg>
            </div>
        </div>

        {{-- Form transaksi --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <p class="text-sm font-medium text-gray-800 mb-3">
                Catat Transaksi
            </p>

            <form action="{{ route('celengan.transaksi', $celengan) }}"
                  method="POST"
                  class="space-y-4">

                @csrf

                {{-- Jenis transaksi --}}
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Jenis Transaksi
                    </label>

                    <div class="flex gap-3">

                        {{-- Nabung --}}
                        <label class="flex-1">
                            <input type="radio"
                                   name="tipe"
                                   value="masuk"
                                   class="peer sr-only"
                                   {{ old('tipe', 'masuk') === 'masuk' ? 'checked' : '' }}>

                            <div class="px-4 py-2.5 border rounded-xl text-sm text-center cursor-pointer
                                        peer-checked:bg-gradient-to-r
                                        peer-checked:from-coral-400
                                        peer-checked:to-coral-500
                                        peer-checked:text-white
                                        peer-checked:border-transparent">
                                Nabung
                            </div>
                        </label>

                        {{-- Ambil --}}
                        <label class="flex-1">
                            <input type="radio"
                                   name="tipe"
                                   value="keluar"
                                   class="peer sr-only"
                                   {{ old('tipe') === 'keluar' ? 'checked' : '' }}>

                            <div class="px-4 py-2.5 border rounded-xl text-sm text-center cursor-pointer
                                        peer-checked:bg-gradient-to-r
                                        peer-checked:from-coral-400
                                        peer-checked:to-coral-500
                                        peer-checked:text-white
                                        peer-checked:border-transparent">
                                Ambil
                            </div>
                        </label>
                    </div>

                    @error('tipe')
                        <p class="text-red-600 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nominal --}}
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Nominal
                    </label>

                    <x-currency-input
                        name="nominal"
                        placeholder="Contoh: 50.000"
                        required
                    />

                    @error('nominal')
                        <p class="text-red-600 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan"
                           class="block text-sm font-medium mb-1">
                        Keterangan
                    </label>

                    <input type="text"
                           id="keterangan"
                           name="keterangan"
                           value="{{ old('keterangan') }}"
                           placeholder="Keterangan (opsional)"
                           class="w-full border rounded-md px-3 py-2 text-sm">

                    @error('keterangan')
                        <p class="text-red-600 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tombol simpan --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm hover:bg-gray-700 transition-colors">
                        Simpan Transaksi
                    </button>

                    <a href="{{ route('celengan.index') }}"
                       class="text-sm text-gray-600">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Riwayat transaksi --}}
        <div class="bg-white rounded-lg shadow overflow-hidden mb-4">

            {{-- Header tabel --}}
            <div class="p-4 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-800">
                    Riwayat Transaksi
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    Catatan saldo masuk dan keluar dari celengan ini.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-sm text-left">

                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-3 py-2">
                                Waktu
                            </th>

                            <th class="px-3 py-2">
                                Tipe
                            </th>

                            <th class="px-3 py-2">
                                Nominal
                            </th>

                            <th class="px-3 py-2">
                                Keterangan
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($riwayat as $r)
                            <tr>
                                {{-- Waktu --}}
                                <td class="px-3 py-2 text-gray-500 whitespace-nowrap">
                                    {{ $r->created_at->format('d/m/y H:i') }}
                                </td>

                                {{-- Tipe --}}
                                <td class="px-3 py-2">
                                    @if ($r->tipe === 'masuk')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                            Keluar
                                        </span>
                                    @endif
                                </td>

                                {{-- Nominal --}}
                                <td class="px-3 py-2 font-medium whitespace-nowrap">
                                    @if ($r->tipe === 'masuk')
                                        <span class="text-green-700">
                                            + Rp {{ number_format($r->nominal, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-red-700">
                                            - Rp {{ number_format($r->nominal, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Keterangan --}}
                                <td class="px-3 py-2 text-gray-600">
                                    {{ $r->keterangan ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-4 py-6 text-center text-gray-500">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if (method_exists($riwayat, 'links'))
            <div class="mt-4">
                {{ $riwayat->links() }}
            </div>
        @endif

    </div>
</x-app-layout>