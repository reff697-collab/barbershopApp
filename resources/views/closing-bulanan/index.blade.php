<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Closing Bulanan
        </h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Informasi halaman --}}
        <div class="h-8 flex items-center justify-end">
            <p class="text-xs sm:text-sm text-gray-400">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Panel bulan berjalan --}}
        <div class="mb-6">
            <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Bulan Berjalan
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Rekapitulasi total omzet dan laba bersih dari akumulasi closing harian periode saat ini.
                    </p>
                </div>

                <div class="p-4 sm:p-5">
                    @if ($sudahClosingBulanIni)
                        <p class="text-sm text-gray-500 bg-gray-50 rounded-xl p-4 border border-gray-100">
                            Closing bulan ini sudah pernah dibuat. Anda dapat melihat detailnya di tabel riwayat di bawah.
                        </p>
                    @else
                        <p class="text-sm font-medium text-gray-700 mb-4">
                            Ada <span class="text-coral-500 font-semibold">{{ $closingHarianBulanIni->count() }}</span> closing harian bulan ini yang siap direkap.
                        </p>

                        @if ($closingHarianBulanIni->count() > 0)
                            <div class="max-w-md text-sm text-gray-600 mb-5 space-y-2.5 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400 text-xs font-medium">TOTAL OMZET (PREVIEW)</span>
                                    <span class="font-semibold text-gray-800 text-base">Rp {{ number_format($closingHarianBulanIni->sum('total_omzet'), 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center border-t border-gray-200/60 pt-2">
                                    <span class="text-gray-400 text-xs font-medium">TOTAL LABA BERSIH (PREVIEW)</span>
                                    <span class="font-bold text-gray-900 text-base">Rp {{ number_format($closingHarianBulanIni->sum('laba_bersih'), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('closing-bulanan.store') }}" method="POST"
                              onsubmit="return confirm('Yakin mau tutup bulan ini? Aksi ini tidak bisa dibatalkan.');">
                            @csrf
                            
                            <div class="flex justify-start">
                                @if ($closingHarianBulanIni->count() > 0)
                                    <button type="submit"
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-5 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        <span>Tutup Bulan Ini</span>
                                    </button>
                                @else
                                    <button type="button" disabled
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-400 cursor-not-allowed border border-gray-200">
                                        <span>Tutup Bulan Ini</span>
                                    </button>
                                @endif
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Riwayat closing bulanan --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Riwayat Closing Bulanan
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Daftar riwayat pembukuan dan performa bisnis yang telah dikunci setiap bulannya.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Bulan</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Hari Closing</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Total Omzet</th>
                                <th class="px-4 py-3 font-medium sm:px-6 text-right">Laba Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($closings as $closing)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        {{ $closing->nama_bulan }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-500">
                                        {{ $closing->jumlah_hari_closing }} hari
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-700">
                                        Rp {{ number_format($closing->total_omzet, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-900 text-right">
                                        Rp {{ number_format($closing->laba_bersih, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada closing bulanan
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Rekap tahunan atau bulanan yang dikunci akan tampil secara berkala di sini.
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