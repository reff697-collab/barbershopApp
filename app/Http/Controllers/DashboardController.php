<?php

namespace App\Http\Controllers;

use App\Models\BarberDailyStatus;
use App\Models\ClosingHarian;
use App\Models\Loan;
use App\Models\Product;
use App\Models\StoreDay;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mengarahkan user ke dashboard sesuai role.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return match (true) {
            $user->hasRole('owner')    => $this->ownerDashboard($request),
            $user->hasRole('kasir')    => $this->kasirDashboard(),
            $user->hasRole('barber')   => $this->barberDashboard($request),
            $user->hasRole('admin_it') => $this->adminItDashboard(),
            default                    => abort(403, 'Role tidak dikenali. Hubungi Admin IT.'),
        };
    }

    /**
     * Dashboard Owner dengan filter rentang waktu.
     */
    private function ownerDashboard(Request $request)
    {
        $range = $request->query('range', 'hari_ini');

        [$from, $to, $label] = match ($range) {
            'minggu_ini' => [
                now()->startOfWeek(),
                now()->endOfWeek(),
                'Minggu Ini',
            ],

            'bulan_ini' => [
                now()->startOfMonth(),
                now()->endOfMonth(),
                'Bulan Ini',
            ],

            'custom' => [
                $request->query('from')
                    ? Carbon::parse($request->query('from'))->startOfDay()
                    : now()->startOfMonth(),

                $request->query('to')
                    ? Carbon::parse($request->query('to'))->endOfDay()
                    : now()->endOfDay(),

                'Rentang Kustom',
            ],

            default => [
                now()->startOfDay(),
                now()->endOfDay(),
                'Hari Ini',
            ],
        };

        $closingHarians = ClosingHarian::whereHas(
            'storeDay',
            function ($query) use ($from, $to) {
                $query->whereBetween('tanggal', [
                    $from->toDateString(),
                    $to->toDateString(),
                ]);
            }
        )
            ->with('storeDay')
            ->get();

        $stats = [
            'total_omzet'       => $closingHarians->sum('total_omzet'),
            'total_komisi'      => $closingHarians->sum('total_komisi_barber'),
            'total_pengeluaran' => $closingHarians->sum('total_pengeluaran'),
            'laba_bersih'       => $closingHarians->sum('laba_bersih'),
        ];

        $belumFinal = false;

        if ($range === 'hari_ini' && $closingHarians->isEmpty()) {
            $todayTransactions = Transaction::whereHas(
                'storeDay',
                function ($query) {
                    $query->whereDate('tanggal', now()->toDateString());
                }
            )->get();

            if ($todayTransactions->isNotEmpty()) {
                $belumFinal = true;

                $totalOmzet = $todayTransactions->sum('total');
                $totalKomisi = $todayTransactions->sum('komisi_barber');

                $stats = [
                    'total_omzet'       => $totalOmzet,
                    'total_komisi'      => $totalKomisi,
                    'total_pengeluaran' => 0,
                    'laba_bersih'       => $totalOmzet - $totalKomisi,
                ];
            }
        }

        $chartData = $closingHarians
            ->sortBy(fn($closing) => $closing->storeDay->tanggal)
            ->map(fn($closing) => [
                'tanggal' => $closing->storeDay->tanggal->format('d/m'),
                'omzet'   => (float) $closing->total_omzet,
            ])
            ->values();

        return view('dashboard.owner', [
            'range'      => $range,
            'label'      => $label,
            'stats'      => $stats,
            'belumFinal' => $belumFinal,
            'chartData'  => $chartData,
        ]);
    }

    /**
     * Dashboard Kasir:
     * ringkasan transaksi, status toko, dan layanan setiap barber.
     */
    private function kasirDashboard()
    {
        $storeDay = StoreDay::today();

        $transactions = Transaction::where(
            'store_day_id',
            $storeDay->id
        )->get();

        $barbers = User::role('barber')
            ->get()
            ->map(function ($barber) use ($storeDay) {
                $breakdown = $this->layananBreakdown(
                    $storeDay->id,
                    $barber->id
                );

                $status = BarberDailyStatus::where(
                    'store_day_id',
                    $storeDay->id
                )
                    ->where('barber_id', $barber->id)
                    ->first();

                return [
                    'nama'             => $barber->name,
                    'status'           => match ($status?->status) {
                        'aktif'   => 'Aktif',
                        'selesai' => 'Selesai',
                        default   => 'Belum Aktif',
                    },
                    'breakdown'        => $breakdown,
                    'jumlah_pelanggan' => $breakdown->sum('jumlah'),
                ];
            });

        $jumlahPelanggan = $barbers->sum('jumlah_pelanggan');

        return view('dashboard.kasir', [
            'storeDay'          => $storeDay,
            'jumlahPelanggan'   => $jumlahPelanggan,
            'totalOmzetHariIni' => $transactions->sum('total'),
            'barbers'           => $barbers,
        ]);
    }

    /**
     * Dashboard Barber:
     * status kerja, layanan, komisi, pelanggan, dan pinjaman.
     */
    private function barberDashboard(Request $request)
    {
        $storeDay = StoreDay::today();
        $barberId = $request->user()->id;

        $myStatus = BarberDailyStatus::where(
            'store_day_id',
            $storeDay->id
        )
            ->where('barber_id', $barberId)
            ->first();

        $myTransactions = Transaction::where(
            'store_day_id',
            $storeDay->id
        )
            ->where('barber_id', $barberId)
            ->get();

        $breakdown = $this->layananBreakdown(
            $storeDay->id,
            $barberId
        );

        $jumlahPelanggan = $breakdown->sum('jumlah');

        $activeLoan = Loan::where('barber_id', $barberId)
            ->where('status', 'aktif')
            ->first();

        return view('dashboard.barber', [
            'myStatus'        => $myStatus,
            'breakdown'       => $breakdown,
            'jumlahPelanggan' => $jumlahPelanggan,
            'komisiHariIni'   => $myTransactions->sum('komisi_barber'),
            'activeLoan'      => $activeLoan,
        ]);
    }

    /**
     * Breakdown jumlah layanan satu barber dalam satu hari.
     */
    private function layananBreakdown(int $storeDayId, int $barberId)
    {
        return TransactionItem::query()
            ->where('item_type', 'layanan')
            ->whereHas(
                'transaction',
                function ($query) use ($storeDayId, $barberId) {
                    $query->where('store_day_id', $storeDayId)
                        ->where('barber_id', $barberId);
                }
            )
            ->get()
            ->groupBy('nama')
            ->map(function ($items, $nama) {
                return [
                    'kode'   => strtoupper(substr($nama, 0, 1)),
                    'jumlah' => $items->sum('qty'),
                ];
            })
            ->values();
    }

    /**
     * Dashboard Admin IT.
     */
    private function adminItDashboard()
    {
        $totalUser = User::count();

        $totalProduk = Product::where('is_active', true)
            ->count();

        $produkMenipis = Product::where('is_active', true)
            ->whereColumn('stok', '<=', 'min_stok')
            ->count();

        return view('dashboard.admin-it', compact(
            'totalUser',
            'totalProduk',
            'produkMenipis'
        ));
    }
}