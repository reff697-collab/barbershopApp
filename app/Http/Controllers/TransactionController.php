<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\StoreDay;
use App\Models\TransactionItem;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Daftar transaksi hari ini, dipisah antara item layanan dan item produk,
     * dengan filter opsional: barber, metode pembayaran, dan layanan.
     */
    public function index(Request $request): View
    {
        $storeDay = StoreDay::today();

        $barberId = $request->query('barber_id');
        $paymentMethod = $request->query('payment_method');
        $serviceId = $request->query('service_id');

        $layananQuery = TransactionItem::where('item_type', 'layanan')
            ->whereHas('transaction', function ($q) use ($storeDay, $barberId, $paymentMethod) {
                $q->where('store_day_id', $storeDay->id);
                if ($barberId) {
                    $q->where('barber_id', $barberId);
                }
                if ($paymentMethod) {
                    $q->where('payment_method', $paymentMethod);
                }
            })
            ->with('transaction.barber');

        if ($serviceId) {
            $layananQuery->where('item_id', $serviceId);
        }

        $layananItems = $layananQuery->oldest()->get()->values();

        $produkItems = TransactionItem::where('item_type', 'produk')
            ->whereHas('transaction', function ($q) use ($storeDay, $barberId, $paymentMethod) {
                $q->where('store_day_id', $storeDay->id);
                if ($barberId) {
                    $q->where('barber_id', $barberId);
                }
                if ($paymentMethod) {
                    $q->where('payment_method', $paymentMethod);
                }
            })
            ->with('transaction')
            ->oldest()
            ->get()
            ->values();

        $barbers = \App\Models\User::role('barber')->get();
        $services = \App\Models\Service::orderBy('nama')->get();

        return view('transactions.index', compact(
            'storeDay', 'layananItems', 'produkItems', 'barbers', 'services',
            'barberId', 'paymentMethod', 'serviceId'
        ));
    }

    /**
     * Form input transaksi baru.
     */
    public function create(): RedirectResponse|View
    {
        $storeDay = StoreDay::today();

        if ($storeDay->status !== 'buka') {
            return redirect()
                ->route('store-day.index')
                ->with('error', 'Toko belum dibuka. Tidak bisa input transaksi.');
        }

        // Barber yang statusnya aktif hari ini saja yang bisa dipilih
        $barberIds = $storeDay->barberStatuses()
            ->where('status', 'aktif')
            ->pluck('barber_id');

        $barbers = User::whereIn('id', $barberIds)->get();

        $services = Service::where('is_active', true)->orderBy('nama')->get();
        $products = Product::where('is_active', true)
            ->where('jenis', 'dijual')
            ->orderBy('nama')
            ->get();

        return view('transactions.create', compact('barbers', 'services', 'products'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $storeDay = StoreDay::today();

        if ($storeDay->status !== 'buka') {
            return redirect()
                ->route('store-day.index')
                ->with('error', 'Toko belum dibuka. Tidak bisa input transaksi.');
        }

        $validated = $request->validate([
            'barber_id' => ['required', 'exists:users,id'],
            'payment_method' => ['required', 'in:tunai,qris'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:layanan,produk'],
            'items.*.item_id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        // Pastikan barber yang dipilih memang aktif hari ini
        $barberAktif = $storeDay->barberStatuses()
            ->where('barber_id', $validated['barber_id'])
            ->where('status', 'aktif')
            ->exists();

        if (! $barberAktif) {
            return back()
                ->withInput()
                ->with('error', 'Barber yang dipilih tidak sedang aktif.');
        }

        DB::transaction(function () use ($validated, $storeDay, $request) {
            $nomorTransaksi = Transaction::where('store_day_id', $storeDay->id)->count() + 1;

            $total = 0;
            $totalLayanan = 0;
            $itemsToSave = [];

            foreach ($validated['items'] as $item) {
                if ($item['item_type'] === 'layanan') {
                    $source = Service::findOrFail($item['item_id']);
                    $harga = $source->harga;
                } else {
                    $source = Product::findOrFail($item['item_id']);

                    if ($source->stok < $item['qty']) {
                        abort(422, "Stok {$source->nama} tidak cukup. Sisa stok: {$source->stok}.");
                    }

                    $harga = $source->harga;
                    $stokSebelum = $source->stok;
                    $source->decrement('stok', $item['qty']);

                    \App\Models\StockMovement::create([
                        'product_id' => $source->id,
                        'tipe' => 'keluar',
                        'qty' => $item['qty'],
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSebelum - $item['qty'],
                        'keterangan' => 'Terjual di transaksi POS',
                        'created_by' => $request->user()->id,
                    ]);
                }

                $subtotal = $harga * $item['qty'];
                $total += $subtotal;

                if ($item['item_type'] === 'layanan') {
                    $totalLayanan += $subtotal;
                }

                $itemsToSave[] = [
                    'item_type' => $item['item_type'],
                    'item_id' => $source->id,
                    'nama' => $source->nama,
                    'qty' => $item['qty'],
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ];
            }

            $komisiBarber = $totalLayanan * 0.5;

            $transaction = Transaction::create([
                'store_day_id' => $storeDay->id,
                'nomor_transaksi' => $nomorTransaksi,
                'kasir_id' => $request->user()->id,
                'barber_id' => $validated['barber_id'],
                'payment_method' => $validated['payment_method'],
                'total' => $total,
                'total_layanan' => $totalLayanan,
                'komisi_barber' => $komisiBarber,
            ]);

            foreach ($itemsToSave as $itemData) {
                $transaction->items()->create($itemData);
            }
        });

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }
}