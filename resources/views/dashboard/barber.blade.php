<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Barber</h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 py-5 sm:px-6 sm:py-8 lg:px-8">

        {{-- Sapaan --}}
        <div class="mb-5 p-4 bg-white border border-gray-100 rounded-xl shadow-sm sm:mb-6 sm:p-6 sm:rounded-2xl">
            <p class="mb-1 text-xs text-gray-400 sm:text-sm">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
            <h2 class="text-base font-medium text-gray-800 sm:text-lg">
                Selamat datang, {{ Auth::user()->name }} 👋
            </h2>
        </div>

        {{-- Kartu Ringkasan --}}
        <div class="grid grid-cols-2 gap-3 mb-6 lg:grid-cols-3 sm:gap-4">

            {{-- Status Kerja --}}
            <div class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm sm:p-5 sm:rounded-2xl">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Status Kerja
                        </p>
                        <p class="text-sm font-semibold leading-tight text-gray-800 sm:text-lg">
                            @if (!$myStatus)
                                Belum Aktif
                            @elseif ($myStatus->status === 'aktif')
                                Aktif
                            @else
                                Selesai
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center justify-center shrink-0 w-8 h-8 rounded-lg sm:w-10 sm:h-10 sm:rounded-xl
                        @if (!$myStatus)
                            bg-gray-100 text-gray-500
                        @elseif ($myStatus->status === 'aktif')
                            bg-green-50 text-green-500
                        @else
                            bg-blue-50 text-blue-500
                        @endif">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pelanggan Hari Ini --}}
            <div class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm sm:p-5 sm:rounded-2xl">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Pelanggan Hari Ini
                        </p>
                        <p class="text-base font-semibold leading-tight text-gray-800 sm:text-lg">
                            {{ number_format($jumlahPelanggan, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-50 rounded-lg sm:w-10 sm:h-10 sm:rounded-xl">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14-10a4 4 0 11-8 0 4 4 0 018 0zm4 10v-2a4 4 0 00-3-3.87m1-11.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Komisi Hari Ini --}}
            <div class="col-span-2 p-4 bg-gradient-to-br from-coral-400 to-coral-600 rounded-xl shadow-sm lg:col-span-1 sm:p-5 sm:rounded-2xl">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-white/80 sm:text-xs">
                            Komisi Hari Ini
                        </p>
                        <p class="text-lg font-semibold leading-tight text-white break-words sm:text-xl">
                            Rp {{ number_format($komisiHariIni, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center shrink-0 w-8 h-8 text-white bg-white/20 rounded-lg sm:w-10 sm:h-10 sm:rounded-xl">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Breakdown Layanan --}}
        @if ($breakdown->isNotEmpty())

            {{-- Judul Breakdown --}}
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 sm:text-base">
                        Layanan Hari Ini
                    </h3>
                    <p class="mt-0.5 text-xs text-gray-400 sm:text-sm">
                        Ringkasan layanan yang kamu tangani.
                    </p>
                </div>
            </div>

            {{-- Tabel Ringkas Kode Layanan --}}
            <div class="mb-6 bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden sm:rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max text-xs text-center sm:text-sm">
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

            {{-- Belum Ada Layanan --}}
            <div class="mb-6 p-6 bg-white border border-gray-100 rounded-xl shadow-sm text-center sm:p-8 sm:rounded-2xl">
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 text-gray-400 bg-gray-100 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-600">
                    Belum ada layanan hari ini
                </p>
                <p class="mt-1 text-xs text-gray-400 sm:text-sm">
                    Layanan yang kamu tangani akan muncul di bagian ini.
                </p>
            </div>

        @endif

        {{-- Informasi Pinjaman --}}
        @if ($activeLoan)
            <div class="mb-6 p-4 bg-orange-50 border border-orange-100 rounded-xl shadow-sm sm:p-5 sm:rounded-2xl">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center shrink-0 w-9 h-9 text-orange-600 bg-orange-100 rounded-xl sm:w-10 sm:h-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="mb-1 text-xs font-medium text-orange-700 sm:text-sm">
                            Sisa Pinjaman
                        </p>
                        <p class="text-lg font-semibold leading-tight text-orange-800 break-words sm:text-xl">
                            Rp {{ number_format($activeLoan->sisa_hutang, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-orange-600 leading-relaxed sm:text-sm">
                            Dipotong Rp {{ number_format($activeLoan->cicilan_per_hari, 0, ',', '.') }} per hari dari komisi.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tombol Aksi --}}
        <a href="{{ route('store-day.index') }}"
           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gradient-to-r from-coral-400 to-coral-500 text-white text-sm font-medium rounded-xl transition hover:opacity-90 sm:w-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Status Kerja Hari Ini
        </a>

    </div>
</x-app-layout>