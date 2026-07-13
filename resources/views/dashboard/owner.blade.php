<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Owner</h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Filter rentang waktu --}}
        <div class="flex flex-wrap items-center gap-2 mb-6">
            @foreach (['hari_ini' => 'Hari Ini', 'minggu_ini' => 'Minggu Ini', 'bulan_ini' => 'Bulan Ini'] as $key => $text)
                <a href="{{ route('dashboard', ['range' => $key]) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium {{ $range === $key ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $text }}
                </a>
            @endforeach

            <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="range" value="custom">
                <input type="date" name="from" value="{{ request('from') }}"
                       class="px-3 py-2 rounded-xl text-sm border border-gray-200">
                <span class="text-gray-400 text-sm">s/d</span>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="px-3 py-2 rounded-xl text-sm border border-gray-200">
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-sm font-medium {{ $range === 'custom' ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    Terapkan
                </button>
            </form>
        </div>

        @if ($belumFinal)
            <div class="mb-4 p-3 bg-yellow-50 text-yellow-700 rounded-xl text-sm">
                Data hari ini masih berjalan (belum difinalisasi closing). Angka bisa berubah sampai kasir finalisasi closing.
            </div>
        @endif

        {{-- Kartu ringkasan --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">

    {{-- Total Omzet --}}
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                    Total Omzet
                </p>

                <p class="text-base sm:text-xl font-semibold text-gray-800 leading-tight break-words">
                    Rp {{ number_format($stats['total_omzet'], 0, ',', '.') }}
                </p>
            </div>

            <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-orange-50 text-coral-500">
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

    {{-- Komisi Barber --}}
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                    Komisi Barber
                </p>

                <p class="text-base sm:text-xl font-semibold text-gray-800 leading-tight break-words">
                    Rp {{ number_format($stats['total_komisi'], 0, ',', '.') }}
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
                          d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87m8-4.13a4 4 0 11-8 0 4 4 0 018 0zm-8 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Pengeluaran --}}
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                    Pengeluaran
                </p>

                <p class="text-base sm:text-xl font-semibold text-gray-800 leading-tight break-words">
                    Rp {{ number_format($stats['total_pengeluaran'], 0, ',', '.') }}
                </p>
            </div>

            <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-red-50 text-red-500">
                <svg class="w-4 h-4 sm:w-5 sm:h-5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Laba Bersih --}}
    <div class="bg-gradient-to-br from-coral-400 to-coral-600 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm">
        <div class="flex items-start justify-between gap-2">
            <div class="min-w-0">
                <p class="text-[11px] sm:text-xs text-white/80 mb-1">
                    Laba Bersih
                </p>

                <p class="text-base sm:text-xl font-semibold text-white leading-tight break-words">
                    Rp {{ number_format($stats['laba_bersih'], 0, ',', '.') }}
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
                          d="M3 17l6-6 4 4 8-8m0 0h-6m6 0v6"/>
                </svg>
            </div>
        </div>
    </div>

</div>

        {{-- Chart tren omzet --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <p class="text-sm font-medium text-gray-700 mb-4">Tren Omzet — {{ $label }}</p>
            @if ($chartData->isEmpty())
                <p class="text-sm text-gray-400 text-center py-12">Belum ada data closing harian di rentang ini.</p>
            @else
                <canvas id="omzetChart" height="80"></canvas>
            @endif
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