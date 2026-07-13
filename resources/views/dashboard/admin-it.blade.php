<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Dashboard Admin IT</h1>
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

            {{-- Total User --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Total User
                        </p>

                        <p class="text-base sm:text-lg font-semibold text-gray-800 leading-tight">
                            {{ number_format($totalUser, 0, ',', '.') }}
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
                                  d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m10-10a4 4 0 11-8 0 4 4 0 018 0zm4 10v-2a4 4 0 00-3-3.87m1-11.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Produk Aktif --}}
            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Produk Aktif
                        </p>

                        <p class="text-base sm:text-lg font-semibold text-gray-800 leading-tight">
                            {{ number_format($totalProduk, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-green-50 text-green-500">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Stok Menipis --}}
            <div class="col-span-2 lg:col-span-1 rounded-xl sm:rounded-2xl border p-4 sm:p-5 shadow-sm
                {{ $produkMenipis > 0
                    ? 'bg-red-50 border-red-100'
                    : 'bg-white border-gray-100' }}">

                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs mb-1
                            {{ $produkMenipis > 0 ? 'text-red-400' : 'text-gray-400' }}">
                            Stok Menipis
                        </p>

                        <p class="text-lg sm:text-xl font-semibold leading-tight
                            {{ $produkMenipis > 0 ? 'text-red-600' : 'text-gray-800' }}">
                            {{ number_format($produkMenipis, 0, ',', '.') }}
                        </p>

                        <p class="text-[11px] sm:text-xs mt-1
                            {{ $produkMenipis > 0 ? 'text-red-500' : 'text-gray-400' }}">
                            {{ $produkMenipis > 0
                                ? 'Produk perlu segera ditambah'
                                : 'Semua stok masih aman' }}
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl
                        {{ $produkMenipis > 0
                            ? 'bg-red-100 text-red-600'
                            : 'bg-gray-100 text-gray-500' }}">

                        <svg class="w-4 h-4 sm:w-5 sm:h-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="1.8"
                                  d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        {{-- Menu pengelolaan --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            <a href="{{ route('services.index') }}"
               class="flex items-center justify-center gap-2 w-full px-3 sm:px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition">

                <svg class="w-4 h-4 shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m12 14a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>

                <span>Kelola Layanan</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="flex items-center justify-center gap-2 w-full px-3 sm:px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition">

                <svg class="w-4 h-4 shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>

                <span>Kelola Produk</span>
            </a>

            <a href="{{ route('users.index') }}"
               class="flex items-center justify-center gap-2 w-full px-3 sm:px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition">

                <svg class="w-4 h-4 shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2m10-10a4 4 0 11-8 0 4 4 0 018 0zm4 10v-2a4 4 0 00-3-3.87"/>
                </svg>

                <span>Kelola User</span>
            </a>

            <a href="{{ route('stock.index') }}"
               class="flex items-center justify-center gap-2 w-full px-3 sm:px-4 py-3 bg-gradient-to-r from-coral-400 to-coral-500 text-white rounded-xl text-xs sm:text-sm font-medium hover:opacity-90 transition">

                <svg class="w-4 h-4 shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M4 7h16M4 12h16M4 17h10"/>
                </svg>

                <span>Manajemen Stok</span>
            </a>

        </div>

    </div>
</x-app-layout>