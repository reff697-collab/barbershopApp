<?php

namespace App\Http\Controllers;

use App\Models\ClosingHarian;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect user ke dashboard sesuai role-nya.
     *
     * Ini dipanggil sekali setelah login berhasil (lihat routes/web.php).
     * Kalau nanti mau nambah role baru, tinggal tambah case di sini.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return match (true) {
            $user->hasRole('owner')    => $this->ownerDashboard($request),
            $user->hasRole('kasir')    => $this->kasirDashboard(),
            $user->hasRole('barber')   => $this->barberDashboard($request),
            $user->hasRole('admin_it') => $this->adminItDashboard(),
            default => abort(403, 'Role tidak dikenali. Hubungi Admin IT.'),
        };
    }

    /**
     * Dashboard Owner dengan filter rentang waktu.
     * Default: hari ini. Bisa juga minggu ini, bulan ini, atau custom.
     */
    private function ownerDashboard(Request $request)
    {
        $range = $request->query('range', 'hari_ini');

        [$from, $to, $label] = match ($range) {
            'minggu_ini' => [now()->startOfWeek(), now()->endOfWeek(), 'Minggu Ini'],
            'bulan_ini' => [now()->startOfMonth(), now()->endOfMonth(), 'Bulan Ini'],
            'custom' => [
                $request->query('from') ? \Carbon\Carbon::parse($request->query('from')) : now()->startOfMonth(),
                $request->query('to') ? \Carbon\Carbon::parse($request->query('to')) : now(),
                'Rentang Kustom',
            ],
            default => [now()->startOfDay(), now()->endOfDay(), 'Hari Ini'],
        };

        // Data dari closing harian yang sudah difinalisasi dalam rentang ini
        $closingHarians = ClosingHarian::whereHas('storeDay', function ($query) use ($from, $to) {
            $query->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()]);
        })->with('storeDay')->get();

        $stats = [
            'total_omzet' => $closingHarians->sum('total_omzet'),
            'total_komisi' => $closingHarians->sum('total_komisi_barber'),
            'total_pengeluaran' => $closingHarians->sum('total_pengeluaran'),
            'laba_bersih' => $closingHarians->sum('laba_bersih'),
        ];

        // Kalau filter "hari ini" dan belum ada closing harian (belum difinalisasi),
        // tampilkan data live dari transaksi yang sedang berjalan.
        $belumFinal = false;
        if ($range === 'hari_ini' && $closingHarians->isEmpty()) {
            $todayTransactions = Transaction::whereHas('storeDay', function ($query) {
                $query->whereDate('tanggal', now()->toDateString());
            })->get();

            if ($todayTransactions->isNotEmpty()) {
                $belumFinal = true;
                $stats = [
                    'total_omzet' => $todayTransactions->sum('total'),
                    'total_komisi' => $todayTransactions->sum('komisi_barber'),
                    'total_pengeluaran' => 0,
                    'laba_bersih' => $todayTransactions->sum('total') - $todayTransactions->sum('komisi_barber'),
                ];
            }
        }

        // Data tren harian untuk chart, urut tanggal
        $chartData = $closingHarians
            ->sortBy(fn ($c) => $c->storeDay->tanggal)
            ->map(fn ($c) => [
                'tanggal' => $c->storeDay->tanggal->format('d/m'),
                'omzet' => (float) $c->total_omzet,
            ])
            ->values();

        return view('dashboard.owner', [
            'range' => $range,
            'label' => $label,
            'stats' => $stats,
            'belumFinal' => $belumFinal,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Dashboard Kasir: ringkasan transaksi hari ini + status toko.
     */
    private function kasirDashboard()
    {
        $storeDay = \App\Models\StoreDay::today();

        $transactions = Transaction::where('store_day_id', $storeDay->id)->get();

        return view('dashboard.kasir', [
            'storeDay' => $storeDay,
            'jumlahTransaksi' => $transactions->count(),
            'totalOmzetHariIni' => $transactions->sum('total'),
        ]);
    }

    /**
     * Dashboard Barber: status kerja hari ini, komisi live, dan sisa pinjaman.
     */
    private function barberDashboard(Request $request)
    {
        $storeDay = \App\Models\StoreDay::today();
        $barberId = $request->user()->id;

        $myStatus = \App\Models\BarberDailyStatus::where('store_day_id', $storeDay->id)
            ->where('barber_id', $barberId)
            ->first();

        $myTransactions = Transaction::where('store_day_id', $storeDay->id)
            ->where('barber_id', $barberId)
            ->get();

        $activeLoan = \App\Models\Loan::where('barber_id', $barberId)
            ->where('status', 'aktif')
            ->first();

        return view('dashboard.barber', [
            'myStatus' => $myStatus,
            'jumlahPelanggan' => $myTransactions->count(),
            'komisiHariIni' => $myTransactions->sum('komisi_barber'),
            'activeLoan' => $activeLoan,
        ]);
    }

    /**
     * Dashboard Admin IT: ringkasan sistem (user, produk, stok menipis).
     */
    private function adminItDashboard()
    {
        $totalUser = \App\Models\User::count();
        $totalProduk = \App\Models\Product::where('is_active', true)->count();
        $produkMenipis = \App\Models\Product::where('is_active', true)
            ->whereColumn('stok', '<=', 'min_stok')
            ->count();

        return view('dashboard.admin-it', compact('totalUser', 'totalProduk', 'produkMenipis'));
    }

}