<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Riwayat Closing Harian
        </h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Informasi halaman --}}
        <div class="h-8 flex items-center justify-end">
            <p class="text-xs sm:text-sm text-gray-400">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Catatan Pembukuan Harian
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Memantau riwayat omzet, alokasi komisi barber, serta laba bersih yang masuk setiap harinya.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Tanggal</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Omzet</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Komisi Barber</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Laba Bersih</th>
                                <th class="px-4 py-3 font-medium sm:px-6 text-right">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($closings as $closing)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 text-gray-500">
                                        {{ $closing->storeDay->tanggal->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-700">
                                        Rp {{ number_format($closing->total_omzet, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
                                        Rp {{ number_format($closing->total_komisi_barber, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        Rp {{ number_format($closing->laba_bersih, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-right">
                                        <a href="{{ route('closing.show', $closing) }}" 
                                           class="inline-flex items-center gap-1 rounded-lg bg-gray-50 border border-gray-200/60 px-2.5 py-1.5 text-xs font-medium text-coral-500 hover:bg-coral-50 hover:border-coral-100 transition-all">
                                            <span>Lihat</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada closing harian
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Seluruh data closing harian yang telah dikunci akan terdaftar secara rapi di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $closings->links() }}
        </div>

    </div>
</x-app-layout>