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

        {{-- Performa Barber --}}
        <div class="mb-6">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Performa Barber Hari Ini
                    </h3>

                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Ringkasan pelanggan, layanan, dan komisi setiap barber.
                    </p>
                </div>

                <div class="shrink-0 flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-coral-50 text-coral-500">
                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h2m4 6v-2a4 4 0 00-4-4m4 6h4m-8-9a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>
            </div>

            @if ($barbers->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">

                    @foreach ($barbers as $barber)
                        <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">

                            {{-- Identitas dan status barber --}}
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3 min-w-0">

                                    <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-coral-50 text-coral-500 font-semibold">
                                        {{ strtoupper(substr($barber['nama'], 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-sm sm:text-base font-semibold text-gray-800 truncate">
                                            {{ $barber['nama'] }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Barber
                                        </p>
                                    </div>
                                </div>

                                <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-medium
                                    @if (($barber['status'] ?? '') === 'Aktif')
                                        bg-green-50 text-green-600
                                    @elseif (($barber['status'] ?? '') === 'Selesai')
                                        bg-blue-50 text-blue-600
                                    @else
                                        bg-gray-100 text-gray-500
                                    @endif">

                                    {{ $barber['status'] ?? 'Belum Aktif' }}
                                </span>
                            </div>

                            {{-- Ringkasan barber --}}
                            <div class="space-y-3">

                            </div>
                            {{-- Ringkasan kode layanan barber --}}
                            @if (isset($barber['breakdown']) && $barber['breakdown']->isNotEmpty())

                                <div class="mt-4 pt-4 border-t border-gray-100">

                                    <p class="text-xs font-medium text-gray-500 mb-2">
                                        Layanan Hari Ini
                                    </p>

                                    <div class="rounded-xl border border-gray-100 overflow-hidden">

                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-max text-xs sm:text-sm text-center">

                                                <thead class="bg-gray-50 text-gray-500">
                                                    <tr>
                                                        @foreach ($barber['breakdown'] as $item)
                                                            <th class="px-4 py-2.5 font-medium">
                                                                {{ $item['kode'] }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        @foreach ($barber['breakdown'] as $item)
                                                            <td class="px-4 py-2.5 font-semibold text-gray-800 border-t border-gray-100">
                                                                {{ number_format($item['jumlah'], 0, ',', '.') }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>

                                    </div>
                                </div>

                            @else

                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-xs text-gray-400">
                                        Belum ada layanan hari ini.
                                    </p>
                                </div>

                            @endif

                        </div>
                    @endforeach

                </div>
            @else

                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-6 sm:p-8 text-center shadow-sm">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                        <svg class="w-6 h-6"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h2m4 6v-2a4 4 0 00-4-4m4 6h4m-8-9a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </div>

                    <p class="text-sm font-medium text-gray-600">
                        Belum ada data barber
                    </p>

                    <p class="text-xs sm:text-sm text-gray-400 mt-1">
                        Data performa barber akan ditampilkan di bagian ini.
                    </p>
                </div>

            @endif
        </div>

        {{-- Tombol Aksi --}}
<div class="space-y-3">

    {{-- Baris 1 --}}
    <a href="{{ route('store-day.index') }}"
       class="block w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 text-center">
        Status Buka/Tutup Toko
    </a>

    {{-- Baris 2 --}}
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('transactions.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-coral-400 to-coral-500 text-white rounded-xl text-sm text-center">
            Transaksi
        </a>

        <a href="{{ route('kas-keluar.index') }}"
           class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 hover:bg-gray-50 text-center">
            Kas Keluar
        </a>
    </div>

</div>

    </div>
</x-app-layout>