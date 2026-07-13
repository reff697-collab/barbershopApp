<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto"
         x-data="transactionForm({
             services: {{ $services->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama, 'harga' => (float) $s->harga])->toJson() }},
             products: {{ $products->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama, 'harga' => (float) $p->harga])->toJson() }}
         })">
        <h1 class="text-xl font-medium mb-6">Transaksi Baru</h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transactions.store') }}" method="POST" @submit="beforeSubmit">
            @csrf

            {{-- Pilih barber --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Barber</label>
                <select name="barber_id" class="w-full border rounded-md px-3 py-2 text-sm" required>
                    <option value="">-- Pilih barber aktif --</option>
                    @forelse ($barbers as $barber)
                        <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                    @empty
                        <option value="" disabled>Tidak ada barber aktif</option>
                    @endforelse
                </select>
            </div>

            {{-- Metode pembayaran --}}
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
                <div class="flex gap-3">
                    <label class="flex-1">
                        <input type="radio" name="payment_method" value="tunai" checked class="peer sr-only">
                        <div class="px-4 py-2.5 border rounded-xl text-sm text-center cursor-pointer peer-checked:bg-gradient-to-r peer-checked:from-coral-400 peer-checked:to-coral-500 peer-checked:text-white peer-checked:border-transparent">
                            Tunai
                        </div>
                    </label>
                    <label class="flex-1">
                        <input type="radio" name="payment_method" value="qris" class="peer sr-only">
                        <div class="px-4 py-2.5 border rounded-xl text-sm text-center cursor-pointer peer-checked:bg-gradient-to-r peer-checked:from-coral-400 peer-checked:to-coral-500 peer-checked:text-white peer-checked:border-transparent">
                            QRIS
                        </div>
                    </label>
                </div>
            </div>

            {{-- Tambah item --}}
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <p class="text-sm font-medium mb-3">Tambah Item</p>
                <div class="grid grid-cols-3 gap-2">
                    <select x-model="pickerType" class="border rounded-md px-2 py-2 text-sm">
                        <option value="layanan">Layanan</option>
                        <option value="produk">Produk</option>
                    </select>
                    <select x-model="pickerId" class="border rounded-md px-2 py-2 text-sm col-span-2">
                        <option value="">-- Pilih --</option>
                        <template x-for="opt in (pickerType === 'layanan' ? services : products)" :key="opt.id">
                            <option :value="opt.id" x-text="opt.nama + ' - Rp ' + opt.harga.toLocaleString('id-ID')"></option>
                        </template>
                    </select>
                </div>
                <button type="button" @click="addItem()"
                        class="mt-3 px-3 py-1.5 bg-gray-800 text-white rounded-md text-sm">
                    + Tambahkan ke Transaksi
                </button>
            </div>

            {{-- Daftar item yang sudah ditambahkan --}}
            <div class="bg-white rounded-lg shadow overflow-hidden mb-4" x-show="items.length > 0">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2 w-20">Qty</th>
                            <th class="px-3 py-2 w-32">Subtotal</th>
                            <th class="px-3 py-2 w-16"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-3 py-2" x-text="item.nama"></td>
                                <td class="px-3 py-2">
                                    <input type="number" min="1" x-model.number="item.qty" @input="recalc()"
                                           class="w-16 border rounded px-2 py-1 text-sm">
                                </td>
                                <td class="px-3 py-2" x-text="'Rp ' + (item.harga * item.qty).toLocaleString('id-ID')"></td>
                                <td class="px-3 py-2 text-right">
                                    <button type="button" @click="removeItem(index)" class="text-red-600 text-xs">Hapus</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="items.length === 0" class="text-sm text-gray-400 mb-4">
                Belum ada item ditambahkan.
            </div>

            {{-- Total --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6 flex items-center justify-between">
                <span class="text-sm font-medium">Total</span>
                <span class="text-lg font-semibold" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
            </div>

            {{-- Hidden inputs untuk dikirim ke server --}}
            <template x-for="(item, index) in items" :key="'hidden-' + index">
                <div>
                    <input type="hidden" :name="'items[' + index + '][item_type]'" :value="item.item_type">
                    <input type="hidden" :name="'items[' + index + '][item_id]'" :value="item.id">
                    <input type="hidden" :name="'items[' + index + '][qty]'" :value="item.qty">
                </div>
            </template>

            <div class="flex items-center gap-3">
                <button type="submit"
                        :disabled="items.length === 0"
                        :class="items.length === 0 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-gray-800 text-white'"
                        class="px-4 py-2 rounded-md text-sm">
                    Simpan Transaksi
                </button>
                <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function transactionForm({ services, products }) {
            return {
                services,
                products,
                pickerType: 'layanan',
                pickerId: '',
                items: [],
                total: 0,

                addItem() {
                    if (!this.pickerId) return;

                    const source = this.pickerType === 'layanan' ? this.services : this.products;
                    const found = source.find(o => o.id == this.pickerId);
                    if (!found) return;

                    this.items.push({
                        item_type: this.pickerType,
                        id: found.id,
                        nama: found.nama,
                        harga: found.harga,
                        qty: 1,
                    });

                    this.pickerId = '';
                    this.recalc();
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.recalc();
                },

                recalc() {
                    this.total = this.items.reduce((sum, item) => sum + (item.harga * item.qty), 0);
                },

                beforeSubmit(event) {
                    if (this.items.length === 0) {
                        event.preventDefault();
                        alert('Tambahkan minimal 1 item sebelum menyimpan transaksi.');
                    }
                },
            };
        }
    </script>
</x-app-layout>