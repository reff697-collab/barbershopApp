<div x-data="{ sidebarOpen: false }">
    <!-- Overlay untuk mobile -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-gray-900/40 md:hidden"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-40 flex w-64 transform flex-col border-r border-gray-100 bg-white transition-transform duration-200 ease-in-out md:sticky md:top-0 md:h-screen md:translate-x-0">

        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center border-b border-gray-100 px-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-coral-400 to-coral-600">
                    <span class="text-sm font-semibold text-white">R</span>
                </div>
                <span class="font-semibold text-gray-800">Rafel</span>
            </a>
        </div>

        <!-- Menu Navigation -->
        <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
            <p class="mb-2 px-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                Menu
            </p>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-sidebar-link>

            {{-- Menu Kasir --}}
            @role('kasir')
                <x-sidebar-link :href="route('store-day.index')" :active="request()->routeIs('store-day.index')">
                    Status Toko
                </x-sidebar-link>
                <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                    Transaksi
                </x-sidebar-link>
                <x-sidebar-link :href="route('kas-keluar.index')" :active="request()->routeIs('kas-keluar.*')">
                    Kas Keluar
                </x-sidebar-link>
            @endrole

            {{-- Menu Barber --}}
            @role('barber')
                <x-sidebar-link :href="route('store-day.index')" :active="request()->routeIs('store-day.index')">
                    Status Kerja
                </x-sidebar-link>
            @endrole

            {{-- Menu Laporan (Owner) --}}
            @role('owner')
                <p class="mb-2 mt-6 px-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                    Laporan
                </p>

                <x-sidebar-link :href="route('closing.index')" :active="request()->routeIs('closing.*')">
                    Closing Harian
                </x-sidebar-link>

                <x-sidebar-link :href="route('closing-bulanan.index')" :active="request()->routeIs('closing-bulanan.*')">
                    Closing Bulanan
                </x-sidebar-link>

                <x-sidebar-link :href="route('absensi.index')" :active="request()->routeIs('absensi.*')">
                    Rekap Absensi
                </x-sidebar-link>
            @endrole

            {{-- Menu Keuangan (Owner & Admin IT) --}}
            @hasanyrole('owner|admin_it')
                <p class="mb-2 mt-6 px-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                    Keuangan
                </p>

                <x-sidebar-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                    Pengeluaran
                </x-sidebar-link>

                <x-sidebar-link :href="route('celengan.index')" :active="request()->routeIs('celengan.*')">
                    Celengan
                </x-sidebar-link>

                <x-sidebar-link :href="route('loans.index')" :active="request()->routeIs('loans.*')">
                    Pinjaman Barber
                </x-sidebar-link>
            @endhasanyrole

            {{-- Menu Master Data & Admin (Admin IT) --}}
            @role('admin_it')
                <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                    Transaksi
                </x-sidebar-link>
                <x-sidebar-link :href="route('kas-keluar.index')" :active="request()->routeIs('kas-keluar.*')">
                    Kas Keluar
                </x-sidebar-link>

                <p class="mb-2 mt-6 px-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                    Master Data
                </p>

                <x-sidebar-link :href="route('services.index')" :active="request()->routeIs('services.*')">
                    Layanan
                </x-sidebar-link>
                <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    Produk
                </x-sidebar-link>
                <x-sidebar-link :href="route('stock.index')" :active="request()->routeIs('stock.*')">
                    Manajemen Stok
                </x-sidebar-link>
                <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    Kelola User
                </x-sidebar-link>
            @endrole
        </nav>

        <!-- User info & Logout -->
        <div class="shrink-0 border-t border-gray-100 p-4">
            <div class="mb-2 flex items-center gap-3 px-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-coral-100 text-sm font-medium text-coral-600">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-gray-800">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="truncate text-xs text-gray-400">
                        {{ Auth::user()->email }}
                    </p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
               class="block rounded-lg px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 hover:text-gray-900">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full rounded-lg px-3 py-2 text-left text-sm text-gray-600 transition hover:bg-gray-50 hover:text-gray-900">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Topbar Mobile (Tombol Hamburger) -->
    <div class="fixed left-0 right-0 top-0 z-20 flex h-16 items-center border-b border-gray-100 bg-white px-4 md:hidden">
        <button @click="sidebarOpen = true"
                type="button"
                class="-ml-2 rounded-md p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <span class="ml-3 font-semibold text-gray-800">Rafel</span>
    </div>
</div>