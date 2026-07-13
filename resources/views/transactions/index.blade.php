    <x-app-layout>
        <div class="p-6 max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-xl font-medium">Transaksi Hari Ini</h1>
                <a href="{{ route('transactions.create') }}"
                class="px-4 py-2 bg-gradient-to-r from-coral-400 to-coral-500 text-white rounded-xl text-sm">
                    + Transaksi Baru
                </a>
            </div>
            <p class="text-sm text-gray-400 mb-6">{{ now()->translatedFormat('l, d F Y') }}</p>

            {{-- Filter --}}
            <form action="{{ route('transactions.index') }}" method="GET" class="flex flex-wrap gap-2 mb-6">
                <select name="barber_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-sm border border-gray-200">
                    <option value="">Semua Barber</option>
                    @foreach ($barbers as $barber)
                        <option value="{{ $barber->id }}" @selected($barberId == $barber->id)>{{ $barber->name }}</option>
                    @endforeach
                </select>

                <select name="payment_method" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-sm border border-gray-200">
                    <option value="">Semua Metode Bayar</option>
                    <option value="tunai" @selected($paymentMethod === 'tunai')>Tunai</option>
                    <option value="qris" @selected($paymentMethod === 'qris')>QRIS</option>
                </select>

                <select name="service_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl text-sm border border-gray-200">
                    <option value="">Semua Layanan</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected($serviceId == $service->id)>{{ $service->nama }}</option>
                    @endforeach
                </select>

                @if ($barberId || $paymentMethod || $serviceId)
                    <a href="{{ route('transactions.index') }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">
                        Reset Filter
                    </a>
                @endif
            </form>

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
                        @forelse ($layananItems->reverse() as $key => $item)
                            <tr>
                                <td class="px-4 py-2">#{{ $key + 1 }}</td>
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
                        @forelse ($produkItems->reverse() as $key => $item)
                        <tr>
                            <td class="px-4 py-2">#{{ $key + 1 }}</td>
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