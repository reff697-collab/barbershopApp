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
         class="fixed inset-0 bg-gray-900/40 z-30 md:hidden"
         style="display: none;">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-100 transform transition-transform duration-200 ease-in-out md:translate-x-0 md:sticky md:top-0 md:h-screen flex flex-col">

        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-100 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-coral-400 to-coral-600 flex items-center justify-center">
                    <span class="text-white text-sm font-semibold">B</span>
                </div>
                <span class="font-semibold text-gray-800">Barbershop</span>
            </a>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
            <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Menu</p>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-sidebar-link>

            @role('kasir')
                <x-sidebar-link :href="route('store-day.index')" :active="request()->routeIs('store-day.index')">
                    Status Toko
                </x-sidebar-link>
                <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                    Transaksi
                </x-sidebar-link>
            @endrole

            @role('barber')
                <x-sidebar-link :href="route('store-day.index')" :active="request()->routeIs('store-day.index')">
                    Status Kerja
                </x-sidebar-link>
            @endrole

            @role('owner')
                <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wide mb-2 mt-6">Laporan</p>
                <x-sidebar-link :href="route('closing.index')" :active="request()->routeIs('closing.*')">
                    Closing Harian
                </x-sidebar-link>
                <x-sidebar-link :href="route('closing-bulanan.index')" :active="request()->routeIs('closing-bulanan.*')">
                    Closing Bulanan
                </x-sidebar-link>
            @endrole

            @hasanyrole('owner|admin_it')
                <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wide mb-2 mt-6">Keuangan</p>
                <x-sidebar-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
                    Pengeluaran
                </x-sidebar-link>
                <x-sidebar-link :href="route('loans.index')" :active="request()->routeIs('loans.*')">
                    Pinjaman Barber
                </x-sidebar-link>
            @endhasanyrole

            @role('admin_it')
                <p class="px-3 text-xs font-medium text-gray-400 uppercase tracking-wide mb-2 mt-6">Master Data</p>
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

        <!-- User info & logout -->
        <div class="border-t border-gray-100 p-4 shrink-0">
            <div class="flex items-center gap-3 px-2 mb-2">
                <div class="w-9 h-9 rounded-full bg-coral-100 flex items-center justify-center text-coral-600 font-medium text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">
                Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Topbar mobile (tombol hamburger) -->
    <div class="md:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-100 flex items-center px-4 z-20">
        <button @click="sidebarOpen = true" class="p-2 -ml-2 text-gray-500">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <span class="ml-3 font-semibold text-gray-800">Barbershop</span>
    </div>
</div>