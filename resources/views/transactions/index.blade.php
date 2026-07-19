<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">
                Transaksi Hari Ini
            </h1>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Section Kontrol Atas (Tanggal, Filter, & Tombol Sejajar di Desktop) --}}
        <div class="mb-6 flex flex-col gap-4">
            
            {{-- Baris 1: Tanggal (Rata kanan di desktop, rapi di HP) --}}
            <div class="flex justify-end items-center h-6">
                <p class="text-xs sm:text-sm text-gray-400">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            {{-- Baris 2: Filter & Tombol (Satu Baris Sejajar di Desktop, Bertumpuk di HP) --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                
                {{-- Sisi Kiri: Filter Section (Bisa di-scroll horizontal jika terlalu panjang di HP) --}}
                <div class="overflow-x-auto no-scrollbar -mx-3 sm:mx-0 px-3 sm:px-0 lg:flex-1">
                    <form action="{{ route('transactions.index') }}" method="GET" class="flex items-center gap-2 pb-1 lg:pb-0 min-w-max">
                        <select name="barber_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-sm border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent">
                            <option value="">Semua Barber</option>
                            @foreach ($barbers as $barber)
                                <option value="{{ $barber->id }}" @selected($barberId == $barber->id)>{{ $barber->name }}</option>
                            @endforeach
                        </select>

                        <select name="payment_method" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-sm border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent">
                            <option value="">Semua Metode Bayar</option>
                            <option value="tunai" @selected($paymentMethod === 'tunai')>Tunai</option>
                            <option value="qris" @selected($paymentMethod === 'qris')>QRIS</option>
                        </select>

                        <select name="service_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-sm border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-coral-400 focus:border-transparent">
                            <option value="">Semua Layanan</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected($serviceId == $service->id)>{{ $service->nama }}</option>
                            @endforeach
                        </select>

                        @if ($barberId || $paymentMethod || $serviceId)
                            <a href="{{ route('transactions.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 whitespace-nowrap transition-colors">
                                Reset Filter
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Sisi Kanan: Tombol Transaksi Baru --}}
                <div class="flex justify-end lg:justify-start shrink-0">
                    <a href="{{ route('transactions.create') }}"
                       class="w-1/3 lg:w-auto inline-flex items-center justify-center gap-1.5 shrink-0 rounded-xl bg-gray-800 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all whitespace-nowrap min-w-[140px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Transaksi Baru</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($storeDay->status !== 'buka')
            <div class="mb-5 sm:mb-6 rounded-xl border border-yellow-100 bg-yellow-50 px-4 py-3 text-sm text-yellow-700">
                Toko tidak sedang buka. Transaksi baru tidak bisa diinput.
            </div>
        @endif

        {{-- Tabel Transaksi Layanan --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Transaksi Layanan
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Daftar transaksi potong rambut dan layanan lainnya hari ini.
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">No.</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Waktu</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Barber</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Layanan</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Qty</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Subtotal</th>
                                @role('admin_it')
                                    <th class="px-4 py-3 font-medium sm:px-6"></th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($layananItems->reverse() as $key => $item)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-500">#{{ $key + 1 }}</td>
                                    <td class="px-4 py-3 sm:px-6">{{ $item->transaction->created_at->format('H:i') }}</td>
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">{{ $item->transaction->barber->name }}</td>
                                    <td class="px-4 py-3 sm:px-6">{{ $item->nama }}</td>
                                    <td class="px-4 py-3 sm:px-6">{{ $item->qty }}</td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    @role('admin_it')
                                        <td class="px-4 py-3 sm:px-6 text-right">
                                            <form action="{{ route('transactions.destroy', $item->transaction_id) }}" method="POST"
                                                  onsubmit="return confirm('Yakin mau hapus transaksi ini? Stok produk (jika ada) akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 text-xs font-medium hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    @endrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-10 text-center"> 
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Belum ada transaksi layanan hari ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tabel Transaksi Produk --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Transaksi Produk
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Daftar penjualan produk penunjang hari ini.
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">No.</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Waktu</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Produk</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Qty</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Subtotal</th>
                                @role('admin_it')
                                    <th class="px-4 py-3 font-medium sm:px-6"></th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($produkItems->reverse() as $key => $item)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-500">#{{ $key + 1 }}</td>
                                    <td class="px-4 py-3 sm:px-6">{{ $item->transaction->created_at->format('H:i') }}</td>
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">{{ $item->nama }}</td>
                                    <td class="px-4 py-3 sm:px-6">{{ $item->qty }}</td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    @role('admin_it')
                                        <td class="px-4 py-3 sm:px-6 text-right">
                                            <form action="{{ route('transactions.destroy', $item->transaction_id) }}" method="POST"
                                                  onsubmit="return confirm('Yakin mau hapus transaksi ini? Stok produk (jika ada) akan dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 text-xs font-medium hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    @endrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V5"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Belum ada transaksi produk hari ini</p>
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