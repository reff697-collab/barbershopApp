<x-app-layout>
    <div class="mx-auto max-w-2xl p-6">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between gap-3">
            <h1 class="truncate text-xl font-medium text-gray-800">
                {{ $celengan->nama }}
            </h1>

            <a href="{{ route('celengan.index') }}"
               class="text-sm text-gray-600 transition hover:text-gray-900">
                Kembali
            </a>
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi Error --}}
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- Validasi Error --}}
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700 border border-red-200">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Informasi Saldo --}}
        <div class="mb-6 flex items-center justify-between gap-4 rounded-xl border border-gray-100 bg-gray-50/80 p-4">
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">
                    Saldo Saat Ini
                </p>

                <p class="text-2xl font-semibold text-gray-800">
                    Rp {{ number_format($celengan->saldo, 0, ',', '.') }}
                </p>
            </div>

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 shadow-sm">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 11a7 7 0 0114 0v4a3 3 0 01-3 3h-1l-1 2h-4l-1-2H8a3 3 0 01-3-3v-4zm2-2L5 7m12 2l2-2M8 13h.01M16 13h.01" />
                </svg>
            </div>
        </div>

        {{-- Form Transaksi --}}
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-sm font-medium text-gray-800">
                Catat Transaksi
            </h2>

            <form action="{{ route('celengan.transaksi', $celengan) }}"
                  method="POST"
                  class="space-y-4">
                @csrf

                {{-- Jenis Transaksi --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Jenis Transaksi
                    </label>

                    <div class="flex gap-3">
                        {{-- Nabung --}}
                        <label class="flex-1 cursor-pointer">
                            <input type="radio"
                                   name="tipe"
                                   value="masuk"
                                   class="peer sr-only"
                                   @checked(old('tipe', 'masuk') === 'masuk')>

                            <div class="rounded-xl border border-gray-200 px-4 py-2.5 text-center text-sm transition peer-checked:border-transparent peer-checked:bg-gradient-to-r peer-checked:from-coral-400 peer-checked:to-coral-500 peer-checked:text-white peer-checked:shadow-sm">
                                Nabung
                            </div>
                        </label>

                        {{-- Ambil --}}
                        <label class="flex-1 cursor-pointer">
                            <input type="radio"
                                   name="tipe"
                                   value="keluar"
                                   class="peer sr-only"
                                   @checked(old('tipe') === 'keluar')>

                            <div class="rounded-xl border border-gray-200 px-4 py-2.5 text-center text-sm transition peer-checked:border-transparent peer-checked:bg-gradient-to-r peer-checked:from-coral-400 peer-checked:to-coral-500 peer-checked:text-white peer-checked:shadow-sm">
                                Ambil
                            </div>
                        </label>
                    </div>

                    @error('tipe')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nominal --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Nominal
                    </label>

                    <x-currency-input name="nominal"
                                      :value="old('nominal')"
                                      placeholder="Contoh: 50.000"
                                      required />

                    @error('nominal')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label for="keterangan"
                           class="mb-1 block text-sm font-medium text-gray-700">
                        Keterangan
                    </label>

                    <input type="text"
                           id="keterangan"
                           name="keterangan"
                           value="{{ old('keterangan') }}"
                           placeholder="Keterangan (opsional)"
                           class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm transition focus:border-coral-500 focus:outline-none focus:ring-1 focus:ring-coral-500">

                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-800 focus:ring-offset-2">
                        Simpan Transaksi
                    </button>

                    <a href="{{ route('celengan.index') }}"
                       class="text-sm text-gray-600 transition hover:text-gray-900">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        {{-- Riwayat Transaksi --}}
        <div class="mb-4 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
            <div class="border-b border-gray-100 p-4">
                <p class="text-sm font-medium text-gray-800">
                    Riwayat Transaksi
                </p>

                <p class="mt-1 text-xs text-gray-400">
                    Catatan saldo masuk dan keluar dari celengan ini.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2.5 font-medium">Waktu</th>
                            <th class="px-4 py-2.5 font-medium">Tipe</th>
                            <th class="px-4 py-2.5 font-medium">Nominal</th>
                            <th class="px-4 py-2.5 font-medium">Keterangan</th>
                            <th class="px-4 py-2.5 font-medium"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y border-t border-gray-100">
                        @forelse ($riwayat as $r)
                            <tr class="transition hover:bg-gray-50/50">
                                {{-- Waktu --}}
                                <td class="whitespace-nowrap px-4 py-3 text-gray-500">
                                    {{ $r->created_at->format('d/m/y H:i') }}
                                </td>

                                {{-- Tipe --}}
                                <td class="px-4 py-3">
                                    @if ($r->tipe === 'masuk')
                                        <span class="inline-block rounded px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-block rounded px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700">
                                            Keluar
                                        </span>
                                    @endif
                                </td>

                                {{-- Nominal --}}
                                <td class="whitespace-nowrap px-4 py-3 font-medium">
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
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $r->keterangan ?: '-' }}
                                </td>

                                {{-- Aksi --}}
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <form action="{{ route('celengan.transaksi.destroy', [$celengan, $r]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin mau hapus transaksi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 transition hover:text-red-800 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
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