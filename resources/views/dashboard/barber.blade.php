<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Barber</h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 py-5 sm:py-8">

        {{-- Sapaan --}}
        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-6 mb-5 sm:mb-6 shadow-sm">
            <p class="text-xs sm:text-sm text-gray-400 mb-1">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>

            <h2 class="text-base sm:text-lg font-medium text-gray-800">
                Selamat datang, {{ Auth::user()->name }} 👋
            </h2>
        </div>

        {{-- Kartu ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">

            {{-- Status Kerja --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Status Kerja
                        </p>

                        <p class="text-sm sm:text-lg font-semibold text-gray-800 leading-tight">
                            @if (! $myStatus)
                                Belum Aktif
                            @elseif ($myStatus->status === 'aktif')
                                Aktif
                            @else
                                Selesai
                            @endif
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl
                        @if (! $myStatus)
                            bg-gray-100 text-gray-500
                        @elseif ($myStatus->status === 'aktif')
                            bg-green-50 text-green-500
                        @else
                            bg-blue-50 text-blue-500
                        @endif">

                        <svg class="w-4 h-4 sm:w-5 sm:h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pelanggan Hari Ini --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Pelanggan Hari Ini
                        </p>

                        <p class="text-base sm:text-lg font-semibold text-gray-800 leading-tight">
                            {{ number_format($jumlahPelanggan, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-blue-50 text-blue-500">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14-10a4 4 0 11-8 0 4 4 0 018 0zm4 10v-2a4 4 0 00-3-3.87m1-11.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Komisi Hari Ini --}}
            <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-coral-400 to-coral-600 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-white/80 mb-1">
                            Komisi Hari Ini
                        </p>

                        <p class="text-lg sm:text-xl font-semibold text-white leading-tight break-words">
                            Rp {{ number_format($komisiHariIni, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-white/20 text-white">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Breakdown layanan --}}
        @if ($breakdown->isNotEmpty())

            {{-- Judul breakdown --}}
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Layanan Hari Ini
                    </h3>

                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Ringkasan layanan yang kamu tangani.
                    </p>
                </div>
            </div>

            {{-- Tabel ringkas kode layanan --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 overflow-hidden shadow-sm mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max text-xs sm:text-sm text-center">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                @foreach ($breakdown as $item)
                                    <th class="px-4 py-3 font-medium">
                                        {{ $item['kode'] }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                @foreach ($breakdown as $item)
                                    <td class="px-4 py-3 font-semibold text-gray-800 border-t border-gray-100">
                                        {{ number_format($item['jumlah'], 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            {{-- Belum ada layanan --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-6 sm:p-8 text-center mb-6 shadow-sm">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                    <svg class="w-6 h-6"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M9 12h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <p class="text-sm font-medium text-gray-600">
                    Belum ada layanan hari ini
                </p>

                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Layanan yang kamu tangani akan muncul di bagian ini.
                </p>
            </div>
        @endif

        {{-- Informasi pinjaman --}}
        @if ($activeLoan)
            <div class="bg-orange-50 border border-orange-100 rounded-xl sm:rounded-2xl p-4 sm:p-5 mb-6 shadow-sm">
                <div class="flex items-start gap-3">

                    <div class="shrink-0 flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-orange-100 text-orange-600">
                        <svg class="w-5 h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-orange-700 mb-1">
                            Sisa Pinjaman
                        </p>

                        <p class="text-lg sm:text-xl font-semibold text-orange-800 leading-tight break-words">
                            Rp {{ number_format($activeLoan->sisa_hutang, 0, ',', '.') }}
                        </p>

                        <p class="text-xs sm:text-sm text-orange-600 mt-1 leading-relaxed">
                            Dipotong Rp {{ number_format($activeLoan->cicilan_per_hari, 0, ',', '.') }}
                            per hari dari komisi.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tombol aksi --}}
        <a href="{{ route('store-day.index') }}"
           class="flex items-center justify-center gap-2 w-full sm:w-auto px-4 py-3 bg-gradient-to-r from-coral-400 to-coral-500 text-white rounded-xl text-sm font-medium hover:opacity-90 transition">

            <svg class="w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>

            Status Kerja Hari Ini
        </a>

    </div>
</x-app-layout>