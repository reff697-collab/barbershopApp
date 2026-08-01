<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Barber</h1>
    </x-slot>

    <div class="mx-auto max-w-6xl px-3 py-5 sm:px-6 sm:py-8 lg:px-8">

        {{-- Sapaan --}}
        <div class="mb-5 rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:mb-6 sm:rounded-2xl sm:p-6">
            <p class="mb-1 text-xs text-gray-400 sm:text-sm">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
            <h2 class="text-base font-medium text-gray-800 sm:text-lg">
                Selamat datang, {{ Auth::user()->name }} 👋
            </h2>
        </div>

        {{-- Kartu Ringkasan --}}
        <div class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">

            {{-- Status Kerja --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
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

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg sm:h-10 sm:w-10 sm:rounded-xl
                        @if (!$myStatus)
                            bg-gray-100 text-gray-500
                        @elseif ($myStatus->status === 'aktif')
                            bg-green-50 text-green-500
                        @else
                            bg-blue-50 text-blue-500
                        @endif">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pelanggan Hari Ini --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Pelanggan Hari Ini
                        </p>
                        <p class="text-base font-semibold leading-tight text-gray-800 sm:text-lg">
                            {{ number_format($jumlahPelanggan ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 sm:h-10 sm:w-10 sm:rounded-xl">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m14-10a4 4 0 11-8 0 4 4 0 018 0zm4 10v-2a4 4 0 00-3-3.87m1-11.13a4 4 0 010 7.75" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Komisi Hari Ini --}}
            <div class="col-span-2 rounded-xl bg-gradient-to-br from-coral-400 to-coral-600 p-4 shadow-sm sm:rounded-2xl sm:p-5 lg:col-span-1">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-white/80 sm:text-xs">
                            Komisi Hari Ini
                        </p>
                        <p class="break-words text-lg font-semibold leading-tight text-white sm:text-xl">
                            Rp {{ number_format($komisiHariIni ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/20 text-white sm:h-10 sm:w-10 sm:rounded-xl">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Breakdown Tunai/QRIS --}}
        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="mb-3 text-sm font-medium text-gray-700">Omzet Berdasarkan Metode Bayar</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="mb-1 text-xs text-gray-400">Tunai</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($omzetTunai ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="mb-1 text-xs text-gray-400">QRIS</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($omzetQris ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Breakdown Layanan --}}
        @if (isset($breakdown) && $breakdown->isNotEmpty())

            {{-- Judul Breakdown --}}
            <div class="mb-3 flex items-center justify-between gap-3">
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
            <div class="mb-6 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm sm:rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max text-center text-xs sm:text-sm">
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
                                    <td class="border-t border-gray-100 px-4 py-3 font-semibold text-gray-800">
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
            <div class="mb-6 rounded-xl border border-gray-100 bg-white p-6 text-center shadow-sm sm:rounded-2xl sm:p-8">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        @if (isset($activeLoan) && $activeLoan)
            <div class="mb-6 rounded-xl border border-orange-100 bg-orange-50 p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-orange-600 sm:h-10 sm:w-10">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="mb-1 text-xs font-medium text-orange-700 sm:text-sm">
                            Sisa Pinjaman
                        </p>
                        <p class="break-words text-lg font-semibold leading-tight text-orange-800 sm:text-xl">
                            Rp {{ number_format($activeLoan->sisa_hutang ?? 0, 0, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs leading-relaxed text-orange-600 sm:text-sm">
                            Dipotong Rp {{ number_format($activeLoan->cicilan_per_hari ?? 0, 0, ',', '.') }} per hari dari komisi.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tombol Aksi --}}
        <a href="{{ route('store-day.index') }}"
           class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-3 text-sm font-medium text-white transition hover:opacity-90 sm:w-auto">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Status Kerja Hari Ini
        </a>

    </div>
</x-app-layout>