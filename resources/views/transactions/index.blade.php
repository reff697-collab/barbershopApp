<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-medium">Transaksi Hari Ini</h1>
            <a href="{{ route('transactions.create') }}"
               class="px-4 py-2 bg-gradient-to-r from-coral-400 to-coral-500 text-white rounded-xl text-sm">
                + Transaksi Baru
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($storeDay->status !== 'buka')
            <div class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded-md text-sm">
                Toko tidak sedang buka. Transaksi baru tidak bisa diinput.
            </div>
        @endif

        {{-- Tabel Transaksi Layanan --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-x-auto mb-6">
            <p class="text-sm font-medium text-gray-700 p-4 pb-0">Transaksi Layanan (Potong Rambut, dll)</p>
            <table class="w-full text-sm text-left mt-2">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">No.</th>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Barber</th>
                        <th class="px-4 py-2">Layanan</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($layananItems as $item)
                        <tr>
                            <td class="px-4 py-2">#{{ $item->transaction->nomor_transaksi }}</td>
                            <td class="px-4 py-2">{{ $item->transaction->created_at->format('H:i') }}</td>
                            <td class="px-4 py-2">{{ $item->transaction->barber->name }}</td>
                            <td class="px-4 py-2">{{ $item->nama }}</td>
                            <td class="px-4 py-2">{{ $item->qty }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada transaksi layanan hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel Transaksi Produk --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-x-auto">
            <p class="text-sm font-medium text-gray-700 p-4 pb-0">Transaksi Produk (Penjualan)</p>
            <table class="w-full text-sm text-left mt-2">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">No.</th>
                        <th class="px-4 py-2">Waktu</th>
                        <th class="px-4 py-2">Produk</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($produkItems as $item)
                        <tr>
                            <td class="px-4 py-2">#{{ $item->transaction->nomor_transaksi }}</td>
                            <td class="px-4 py-2">{{ $item->transaction->created_at->format('H:i') }}</td>
                            <td class="px-4 py-2">{{ $item->nama }}</td>
                            <td class="px-4 py-2">{{ $item->qty }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada transaksi produk hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>