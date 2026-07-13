<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Kasir</h1>
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

            {{-- Status Toko --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Status Toko
                        </p>

                        <p class="text-sm sm:text-lg font-semibold text-gray-800 leading-tight">
                            @if ($storeDay->status === 'belum_buka')
                                Belum Buka
                            @elseif ($storeDay->status === 'buka')
                                Buka
                            @elseif ($storeDay->status === 'tutup')
                                Tutup Sementara
                            @else
                                Selesai
                            @endif
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl
                        @if ($storeDay->status === 'buka')
                            bg-green-50 text-green-500
                        @elseif ($storeDay->status === 'tutup')
                            bg-yellow-50 text-yellow-500
                        @elseif ($storeDay->status === 'selesai')
                            bg-blue-50 text-blue-500
                        @else
                            bg-gray-100 text-gray-500
                        @endif">

                        <svg class="w-4 h-4 sm:w-5 sm:h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M3 10h18M5 10v9h14v-9M4 10l2-6h12l2 6M9 19v-5h6v5"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Transaksi Hari Ini --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Transaksi Hari Ini
                        </p>

                        <p class="text-base sm:text-lg font-semibold text-gray-800 leading-tight">
                            {{ number_format($jumlahTransaksi, 0, ',', '.') }}
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
                                  d="M9 14l2 2 4-4m-7 8h8a2 2 0 002-2V6a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Omzet Hari Ini --}}
            <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-coral-400 to-coral-600 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-white/80 mb-1">
                            Omzet Hari Ini
                        </p>

                        <p class="text-lg sm:text-xl font-semibold text-white leading-tight break-words">
                            Rp {{ number_format($totalOmzetHariIni, 0, ',', '.') }}
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

        {{-- Tombol aksi --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

            <a href="{{ route('store-day.index') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">

                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M3 10h18M5 10v9h14v-9M4 10l2-6h12l2 6"/>
                </svg>

                Status Buka/Tutup Toko
            </a>

            <a href="{{ route('transactions.index') }}"
               class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-coral-400 to-coral-500 text-white rounded-xl text-sm font-medium hover:opacity-90 transition">

                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Buat Transaksi
            </a>

        </div>

    </div>
</x-app-layout>