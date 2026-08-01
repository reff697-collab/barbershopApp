<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Kasir</h1>
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

            {{-- Status Toko --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Status Toko
                        </p>

                        <p class="text-sm font-semibold leading-tight text-gray-800 sm:text-lg">
                            @if (($storeDay->status ?? '') === 'belum_buka')
                                Belum Buka
                            @elseif (($storeDay->status ?? '') === 'buka')
                                Buka
                            @elseif (($storeDay->status ?? '') === 'tutup')
                                Tutup Sementara
                            @else
                                Selesai
                            @endif
                        </p>
                    </div>

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg sm:h-10 sm:w-10 sm:rounded-xl
                        @if (($storeDay->status ?? '') === 'buka')
                            bg-green-50 text-green-500
                        @elseif (($storeDay->status ?? '') === 'tutup')
                            bg-yellow-50 text-yellow-500
                        @elseif (($storeDay->status ?? '') === 'selesai')
                            bg-blue-50 text-blue-500
                        @else
                            bg-gray-100 text-gray-500
                        @endif">

                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M5 10v9h14v-9M4 10l2-6h12l2 6M9 19v-5h6v5" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h2m4 6v-2a4 4 0 00-4-4m4 6h4m-8-9a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Omzet Hari Ini --}}
            <div class="col-span-2 rounded-xl bg-gradient-to-br from-coral-400 to-coral-600 p-4 shadow-sm sm:rounded-2xl sm:p-5 lg:col-span-1">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-white/80 sm:text-xs">
                            Omzet Hari Ini
                        </p>

                        <p class="break-words text-lg font-semibold leading-tight text-white sm:text-xl">
                            Rp {{ number_format($totalOmzetHariIni ?? 0, 0, ',', '.') }}
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
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
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

        {{-- Performa Barber --}}
        <div class="mb-6">
            <div class="mb-3 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 sm:text-base">
                        Performa Barber Hari Ini
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-400 sm:text-sm">
                        Ringkasan pelanggan, layanan, dan komisi setiap barber.
                    </p>
                </div>

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-coral-50 text-coral-500 sm:h-10 sm:w-10">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h2m4 6v-2a4 4 0 00-4-4m4 6h4m-8-9a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                </div>
            </div>

            @if (!empty($barbers) && count($barbers) > 0)
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">

                    @foreach ($barbers as $barber)
                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">

                            {{-- Identitas dan Status Barber --}}
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-coral-50 font-semibold text-coral-500">
                                        {{ strtoupper(substr($barber['nama'] ?? 'B', 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-800 sm:text-base">
                                            {{ $barber['nama'] ?? 'Barber' }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Barber
                                        </p>
                                    </div>
                                </div>

                                <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-[10px] font-medium sm:text-xs
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

                            {{-- Breakdown Kode Layanan Barber --}}
                            @if (isset($barber['breakdown']) && count($barber['breakdown']) > 0)
                                <div class="mt-4 border-t border-gray-100 pt-4">
                                    <p class="mb-2 text-xs font-medium text-gray-500">
                                        Layanan Hari Ini
                                    </p>

                                    <div class="overflow-hidden rounded-xl border border-gray-100">
                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-max text-center text-xs sm:text-sm">
                                                <thead class="bg-gray-50 text-gray-500">
                                                    <tr>
                                                        @foreach ($barber['breakdown'] as $item)
                                                            <th class="px-4 py-2.5 font-medium">
                                                                {{ $item['kode'] ?? '-' }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        @foreach ($barber['breakdown'] as $item)
                                                            <td class="border-t border-gray-100 px-4 py-2.5 font-semibold text-gray-800">
                                                                {{ number_format($item['jumlah'] ?? 0, 0, ',', '.') }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 border-t border-gray-100 pt-4">
                                    <p class="text-xs text-gray-400">
                                        Belum ada layanan hari ini.
                                    </p>
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>
            @else
                <div class="rounded-xl border border-gray-100 bg-white p-6 text-center shadow-sm sm:rounded-2xl sm:p-8">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h2m4 6v-2a4 4 0 00-4-4m4 6h4m-8-9a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>

                    <p class="text-sm font-medium text-gray-600">
                        Belum ada data barber
                    </p>

                    <p class="mt-1 text-xs text-gray-400 sm:text-sm">
                        Data performa barber akan ditampilkan di bagian ini.
                    </p>
                </div>
            @endif
        </div>

        {{-- Tombol Aksi --}}
        <div class="space-y-3">

            {{-- Baris 1 --}}
            <a href="{{ route('store-day.index') }}"
               class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-center text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Status Buka/Tutup Toko
            </a>

            {{-- Baris 2 --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('transactions.index') }}"
                   class="rounded-xl bg-gray-800 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-gray-900">
                    Transaksi
                </a>

                <a href="{{ route('kas-keluar.index') }}"
                   class="rounded-xl border border-red-200 bg-red-600 px-4 py-2.5 text-center text-sm font-medium text-white transition hover:bg-red-700">
                    Kas Keluar
                </a>
            </div>

        </div>

    </div>
</x-app-layout>