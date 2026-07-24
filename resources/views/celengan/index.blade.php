<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">
                Celengan
            </h1>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Section Kontrol Atas --}}
        <div class="mb-6 flex flex-col gap-4">

            {{-- Baris 1: Tanggal --}}
            <div class="flex justify-end items-center h-6">
                <p class="text-xs sm:text-sm text-gray-400">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

        {{-- Notifikasi Berhasil --}}
        @if (session('success'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi Error --}}
        @if (session('error'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Error Validasi --}}
        @if ($errors->any())
            <div class="mb-5 sm:mb-6 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Kartu Ringkasan Total Saldo --}}
        <div class="mb-6">
            <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Total Saldo Celengan
                        </p>

                        <p class="text-lg sm:text-xl font-semibold text-gray-800 leading-tight">
                            Rp {{ number_format($celengans->sum('saldo'), 0, ',', '.') }}
                        </p>

                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                            Akumulasi saldo dari seluruh celengan usaha.
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center
                                w-10 h-10 sm:w-12 sm:h-12
                                rounded-xl bg-coral-50 text-coral-500">

                        <svg class="w-5 h-5 sm:w-6 sm:h-6"
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
            </div>
        </div>

        {{-- Form Tambah Celengan --}}
        <div id="form-celengan" class="mb-6 scroll-mt-6">
            <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">

                {{-- Header Form --}}
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Buat Celengan Baru
                    </h3>

                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Tambahkan tujuan tabungan baru untuk kebutuhan usaha.
                    </p>
                </div>

                {{-- Isi Form --}}
                <div class="p-4 sm:p-5">
                    <form action="{{ route('celengan.store') }}"
                          method="POST"
                          class="flex flex-col sm:flex-row gap-3">

                        @csrf

                        <div class="flex-1">
                            <label for="nama"
                                   class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">
                                Nama Celengan
                            </label>

                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   value="{{ old('nama') }}"
                                   placeholder="Contoh: Dana Renovasi atau Beli Kursi Baru"
                                   required
                                   class="w-full rounded-xl border border-gray-200
                                          px-4 py-2.5 text-sm text-gray-700
                                          placeholder:text-gray-400
                                          focus:border-coral-400
                                          focus:outline-none
                                          focus:ring-2 focus:ring-coral-100">

                            @error('nama')
                                <p class="text-xs text-red-500 mt-1.5">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="sm:self-end">
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5
                                           rounded-xl bg-gradient-to-r
                                           from-coral-400 to-coral-500
                                           px-4 py-2.5 text-sm font-medium text-white
                                           hover:from-coral-500 hover:to-coral-600
                                           shadow-sm transition-all whitespace-nowrap">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 4v16m8-8H4"/>
                                </svg>

                                <span>Buat Celengan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel Daftar Celengan --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">

                {{-- Header Tabel --}}
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">
                            <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                                Daftar Celengan
                            </h3>

                            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                                Kelola celengan dan pantau saldo tabungan usaha.
                            </p>
                        </div>

                        <span class="shrink-0 inline-flex items-center rounded-full
                                     bg-coral-50 px-2.5 py-1
                                     text-[10px] sm:text-xs font-medium text-coral-500">
                            {{ $celengans->count() }} Celengan
                        </span>
                    </div>
                </div>

                {{-- Isi Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-left text-sm">

                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">
                                    Nama Celengan
                                </th>

                                <th class="px-4 py-3 font-medium sm:px-6">
                                    Saldo
                                </th>

                                <th class="px-4 py-3 font-medium sm:px-6">
                                    Status
                                </th>

                                <th class="px-4 py-3 font-medium sm:px-6 text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($celengans as $celengan)
                                <tr class="text-gray-700 hover:bg-gray-50/70 transition-colors">

                                    {{-- Nama --}}
                                    <td class="px-4 py-3 sm:px-6">
                                        <div class="flex items-center gap-3">

                                            <div class="shrink-0 flex items-center justify-center
                                                        w-9 h-9 sm:w-10 sm:h-10
                                                        rounded-xl bg-coral-50 text-coral-500">

                                                <svg class="w-4 h-4 sm:w-5 sm:h-5"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="1.8"
                                                          d="M5 11a7 7 0 0114 0v4a3 3 0 01-3 3h-1l-1 2h-4l-1-2H8a3 3 0 01-3-3v-4zm2-2L5 7m12 2l2-2M8 13h.01M16 13h.01"/>
                                                </svg>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 truncate">
                                                    {{ $celengan->nama }}
                                                </p>

                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    Tabungan usaha
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Saldo --}}
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800 whitespace-nowrap">
                                        Rp {{ number_format($celengan->saldo, 0, ',', '.') }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-3 sm:px-6">
                                        @if ($celengan->saldo > 0)
                                            <span class="inline-flex items-center rounded-full
                                                         bg-green-50 px-2.5 py-1
                                                         text-[10px] sm:text-xs
                                                         font-medium text-green-600">
                                                Memiliki Saldo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full
                                                         bg-gray-100 px-2.5 py-1
                                                         text-[10px] sm:text-xs
                                                         font-medium text-gray-500">
                                                Belum Ada Saldo
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-3 sm:px-6 text-right">
                                        <a href="{{ route('celengan.show', $celengan) }}"
                                           class="inline-flex items-center gap-1
                                                  text-xs sm:text-sm font-medium
                                                  text-coral-500 hover:text-coral-600
                                                  transition-colors">

                                            <span>Lihat Detail</span>

                                            <svg class="w-4 h-4"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">

                                        <div class="flex items-center justify-center
                                                    w-12 h-12 rounded-xl
                                                    bg-gray-100 text-gray-400
                                                    mx-auto mb-3">

                                            <svg class="w-6 h-6"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="1.8"
                                                      d="M5 11a7 7 0 0114 0v4a3 3 0 01-3 3h-1l-1 2h-4l-1-2H8a3 3 0 01-3-3v-4zm2-2L5 7m12 2l2-2"/>
                                            </svg>
                                        </div>

                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada celengan
                                        </p>

                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Celengan yang dibuat akan ditampilkan pada bagian ini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>