<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Owner</h1>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Filter rentang waktu --}}
        <div class="mb-6 flex flex-wrap items-center gap-2">
            @foreach (['hari_ini' => 'Hari Ini', 'minggu_ini' => 'Minggu Ini', 'bulan_ini' => 'Bulan Ini'] as $key => $text)
                <a href="{{ route('dashboard', ['range' => $key]) }}"
                   class="rounded-xl px-4 py-2 text-sm font-medium {{ $range === $key ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">
                    {{ $text }}
                </a>
            @endforeach

            <form action="{{ route('dashboard') }}" method="GET" class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
                <input type="hidden" name="range" value="custom">
                <input type="date" name="from" value="{{ request('from') }}"
                       class="rounded-xl border border-gray-200 px-3 py-2 text-sm">
                <span class="text-sm text-gray-400">s/d</span>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="rounded-xl border border-gray-200 px-3 py-2 text-sm">
                <button type="submit"
                        class="rounded-xl px-4 py-2 text-sm font-medium {{ $range === 'custom' ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white' : 'border border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">
                    Terapkan
                </button>
            </form>
        </div>

        @if ($belumFinal)
            <div class="mb-4 rounded-xl bg-yellow-50 p-3 text-sm text-yellow-700">
                Data hari ini masih berjalan (belum difinalisasi closing). Angka bisa berubah sampai kasir finalisasi closing.
            </div>
        @endif

        {{-- Kartu ringkasan --}}
        <div class="mb-8 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-5">

            {{-- Total Omzet --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Total Omzet
                        </p>
                        <p class="break-words text-base font-semibold leading-tight text-gray-800 sm:text-xl">
                            Rp {{ number_format($stats['total_omzet'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-50 text-coral-500 sm:h-10 sm:w-10 sm:rounded-xl">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Komisi Barber --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Komisi Barber
                        </p>
                        <p class="break-words text-base font-semibold leading-tight text-gray-800 sm:text-xl">
                            Rp {{ number_format($stats['total_komisi'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-500 sm:h-10 sm:w-10 sm:rounded-xl">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-4.13a4 4 0 11-8 0 4 4 0 018 0zm-8 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kas Keluar --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Kas Keluar
                        </p>
                        <p class="break-words text-base font-semibold leading-tight text-gray-800 sm:text-xl">
                            Rp {{ number_format($stats['total_kas_keluar'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-500 sm:h-10 sm:w-10 sm:rounded-xl">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Jumlah Pelanggan --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Jumlah Pelanggan
                        </p>
                        <p class="break-words text-base font-semibold leading-tight text-gray-800 sm:text-xl">
                            {{ $stats['jumlah_pelanggan'] }}
                        </p>
                    </div>
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-50 text-purple-500 sm:h-10 sm:w-10 sm:rounded-xl">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-4.13a4 4 0 11-8 0 4 4 0 018 0zm-8 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Laba Bersih --}}
            <div class="rounded-xl bg-gradient-to-br from-coral-400 to-coral-600 p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-white/80 sm:text-xs">
                            Laba Bersih
                        </p>
                        <p class="break-words text-base font-semibold leading-tight text-white sm:text-xl">
                            Rp {{ number_format($stats['laba_bersih'], 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/20 text-white sm:h-10 sm:w-10 sm:rounded-xl">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 17l6-6 4 4 8-8m0 0h-6m6 0v6"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Breakdown Tunai/QRIS --}}
        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-sm font-medium text-gray-700">Omzet Berdasarkan Metode Bayar — {{ $label }}</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="mb-1 text-xs text-gray-400">Tunai</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($stats['omzet_tunai'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="mb-1 text-xs text-gray-400">QRIS</p>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($stats['omzet_qris'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Chart tren omzet --}}
        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6">
            <p class="mb-4 text-sm font-medium text-gray-700">Tren Omzet — {{ $label }}</p>
            @if ($chartData->isEmpty())
                <p class="py-12 text-center text-sm text-gray-400">Belum ada data closing harian di rentang ini.</p>
            @else
                <canvas id="omzetChart" height="80"></canvas>
            @endif
        </div>

        {{-- Performa Barber Hari Ini --}}
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

            @if ($barberBreakdown->isNotEmpty())
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                    @foreach ($barberBreakdown as $barber)
                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">

                            <div class="mb-4 flex items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-coral-50 font-semibold text-coral-500">
                                        {{ strtoupper(substr($barber['nama'] ?? 'B', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-800 sm:text-base">
                                            {{ $barber['nama'] ?? 'Barber' }}
                                        </p>
                                        <p class="text-xs text-gray-400">Barber</p>
                                    </div>
                                </div>

                                <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-[10px] font-medium sm:text-xs
                                    @if (($barber['status'] ?? '') === 'Aktif') bg-green-50 text-green-600
                                    @elseif (($barber['status'] ?? '') === 'Selesai') bg-blue-50 text-blue-600
                                    @else bg-gray-100 text-gray-500 @endif">
                                    {{ $barber['status'] ?? 'Belum Aktif' }}
                                </span>
                            </div>

                            @if (isset($barber['breakdown']) && $barber['breakdown']->isNotEmpty())
                                <div class="mt-4 border-t border-gray-100 pt-4">
                                    <p class="mb-2 text-xs font-medium text-gray-500">Layanan Hari Ini</p>
                                    <div class="overflow-hidden rounded-xl border border-gray-100">
                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-max text-center text-xs sm:text-sm">
                                                <thead class="bg-gray-50 text-gray-500">
                                                    <tr>
                                                        @foreach ($barber['breakdown'] as $item)
                                                            <th class="px-4 py-2.5 font-medium">{{ $item['kode'] ?? '-' }}</th>
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
                                    <p class="text-xs text-gray-400">Belum ada layanan hari ini.</p>
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
                    <p class="text-sm font-medium text-gray-600">Belum ada data barber</p>
                    <p class="mt-1 text-xs text-gray-400 sm:text-sm">Data performa barber akan ditampilkan di bagian ini.</p>
                </div>
            @endif
        </div>

        {{-- Riwayat Kas Keluar --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm sm:rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Waktu</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Nominal</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Keterangan</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Dicatat Oleh</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kasKeluarList as $kk)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 text-gray-500 sm:px-6">
                                        {{ $kk->created_at->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800 sm:px-6">
                                        Rp {{ number_format($kk->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 sm:px-6">
                                        {{ $kk->keterangan }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-700 sm:px-6">
                                        {{ $kk->inputBy->name ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada kas keluar hari ini
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400 sm:text-sm">
                                            Data pengeluaran kas akan ditampilkan pada bagian ini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @if ($chartData->isNotEmpty())
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('omzetChart');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData->pluck('tanggal')) !!},
                    datasets: [{
                        label: 'Omzet',
                        data: {!! json_encode($chartData->pluck('omzet')) !!},
                        borderColor: '#FF6B4A',
                        backgroundColor: 'rgba(255, 107, 74, 0.1)',
                        fill: true,
                        tension: 0.3,
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            ticks: {
                                callback: (value) => 'Rp ' + value.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        </script>
    @endif
</x-app-layout>