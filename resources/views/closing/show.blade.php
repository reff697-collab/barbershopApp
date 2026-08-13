<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('closing.index') }}" 
               class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-gray-200 bg-white text-gray-500 hover:text-coral-500 hover:border-coral-100 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-xl font-semibold text-gray-800">
                Closing Harian — {{ $closing->storeDay->tanggal->format('d/m/Y') }}
            </h1>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Informasi Halaman --}}
        <div class="h-8 flex items-center justify-end">
            <p class="text-xs sm:text-sm text-gray-400">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-6">
            
            {{-- Ringkasan Keuangan Harian --}}
            <div class="lg:col-span-1 rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Ringkasan Kas
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Rincian arus kas masuk dan keluar operasional.
                    </p>
                </div>
                
                <div class="p-4 sm:p-5 space-y-3 text-sm">
                    <div class="flex justify-between items-center text-gray-600">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Omzet Layanan</span>
                        <span class="text-gray-700 font-medium">Rp {{ number_format($closing->total_omzet_layanan, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-gray-600">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Omzet Produk</span>
                        <span class="text-gray-700 font-medium">Rp {{ number_format($closing->total_omzet_produk, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center border-t border-gray-100 pt-3 text-gray-700">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Omzet</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($closing->total_omzet, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center pl-4">
                        <span class="text-gray-400 text-xs">— Tunai</span>
                        <span class="text-xs">Rp {{ number_format($closing->total_omzet_tunai, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center pl-4">
                        <span class="text-gray-400 text-xs">— QRIS</span>
                        <span class="text-xs">Rp {{ number_format($closing->total_omzet_qris, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-gray-600">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Komisi Barber</span>
                        <span class="text-gray-700 font-medium">Rp {{ number_format($closing->total_komisi_barber, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-gray-600">
                        <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kas Keluar Harian</span>
                        <span class="text-gray-700 font-medium">Rp {{ number_format($closing->total_kas_keluar, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center border-t border-gray-100 pt-3 bg-gray-50 -mx-4 sm:-mx-5 px-4 sm:px-5 pb-1">
                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Laba Bersih</span>
                        <span class="text-base font-bold text-coral-500">Rp {{ number_format($closing->laba_bersih, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="pt-2 border-t border-gray-100 text-[11px] text-gray-400 space-y-0.5">
                        <p>Ditutup oleh: <span class="font-medium text-gray-600">{{ $closing->closedBy->name ?? '-' }}</span></p>
                        <p>Waktu: <span class="font-medium text-gray-600">{{ $closing->closed_at->format('d/m/Y H:i') }}</span></p>
                    </div>
                </div>
            </div>

            {{-- Tabel Rincian Komisi Per Barber --}}
            <div class="lg:col-span-2 overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Rincian Komisi Per Barber
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Distribusi pendapatan bersih dan pemotongan cicilan/kasbon barber hari ini.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[550px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Barber</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Total Layanan</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Komisi Kotor</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Potongan Cicilan</th>
                                <th class="px-4 py-3 font-medium sm:px-6 text-right">Komisi Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($closing->barberDetails as $detail)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        {{ $detail->barber->name }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
                                        Rp {{ number_format($detail->total_layanan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
                                        Rp {{ number_format($detail->komisi_kotor, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-red-500 font-medium">
                                        @if($detail->potongan_cicilan > 0)
                                            -Rp {{ number_format($detail->potongan_cicilan, 0, ',', '.') }}
                                        @else
                                            Rp 0
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800 text-right">
                                        Rp {{ number_format($detail->komisi_bersih, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Tidak ada transaksi barber
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Tidak ada aktivitas potong rambut atau penjualan produk oleh barber hari ini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

{{-- Riwayat Kas Keluar Hari Ini --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
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
                                    <td class="px-4 py-3 sm:px-6 text-gray-500">
                                        {{ $kk->created_at->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        Rp {{ number_format($kk->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
                                        {{ $kk->keterangan }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-700">
                                        {{ $kk->inputBy->name ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada kas keluar hari ini
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
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

    </div>
</x-app-layout>