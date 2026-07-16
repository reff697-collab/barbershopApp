<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Kas Keluar Harian
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

        {{-- Kartu Ringkasan --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">

            {{-- Omzet Tunai --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Omzet Tunai
                        </p>
                        <p class="text-sm sm:text-lg font-semibold text-gray-800 leading-tight break-words">
                            Rp {{ number_format($omzetTunai, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-green-50 text-green-500">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kas Keluar --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Kas Keluar
                        </p>
                        <p class="text-sm sm:text-lg font-semibold text-gray-800 leading-tight break-words">
                            Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-red-50 text-red-500">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m0 0l-5-5m5 5l5-5"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kas Seharusnya di Laci --}}
            <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-coral-400 to-coral-600 rounded-xl sm:rounded-2xl p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-white/80 mb-1">
                            Kas Seharusnya di Laci
                        </p>
                        <p class="text-lg sm:text-xl font-semibold text-white leading-tight break-words">
                            Rp {{ number_format($kasSeharusnya, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-white/20 text-white">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M5 7V5a2 2 0 012-2h10a2 2 0 012 2v2M5 7v12h14V7M9 12h6"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Form Catat Kas Keluar --}}
        <div class="mb-6 rounded-xl sm:rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Catat Kas Keluar
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Formulir pengeluaran kas operasional barbershop hari ini.
                    </p>
                </div>

                <div class="shrink-0 flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-coral-50 text-coral-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>

            <form action="{{ route('kas-keluar.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="mb-2 block text-xs text-gray-500">
                        Nominal
                    </label>
                    <x-currency-input name="nominal" placeholder="Contoh: 20.000" required />
                    @error('nominal')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-500">
                        Keterangan
                    </label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Misal: diambil Kak Ary untuk beli bensin" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm shadow-sm focus:border-coral-400 focus:ring-coral-400 transition-all">
                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full sm:w-auto rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all">
                        Catat Kas Keluar
                    </button>
                </div>
            </form>
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