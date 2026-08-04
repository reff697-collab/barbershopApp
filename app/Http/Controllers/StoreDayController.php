<?php

namespace App\Http\Controllers;

use App\Models\BarberDailyStatus;
use App\Models\ClosingHarian;
use App\Models\ClosingHarianBarber;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\StoreDay;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use App\Services\ClosingHarianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StoreDayController extends Controller
{
    /**
     * Halaman utama status toko hari ini.
     * Isinya sama untuk semua role, tapi tombol yang muncul beda-beda
     * (diatur langsung di view berdasarkan role user yang login).
     */
    public function index(Request $request): View
    {
        $storeDay = StoreDay::today();

        $barbers = User::role('barber')
            ->with(['barberDailyStatuses' => function ($query) use ($storeDay) {
                $query->where('store_day_id', $storeDay->id);
            }])
            ->get();

        $myStatus = null;
        if ($request->user()->hasRole('barber')) {
            $myStatus = BarberDailyStatus::where('store_day_id', $storeDay->id)
                ->where('barber_id', $request->user()->id)
                ->first();
        }

        $adaBarberAktif = BarberDailyStatus::where('store_day_id', $storeDay->id)
            ->where('status', 'aktif')
            ->exists();

        $closingHarian = null;
        if ($storeDay->status === 'selesai') {
            $closingHarian = \App\Models\ClosingHarian::where('store_day_id', $storeDay->id)
                ->with('barberDetails.barber')
                ->first();
        }

        return view('store-day.index', compact('storeDay', 'barbers', 'myStatus', 'adaBarberAktif', 'closingHarian'));
    }

    /**
     * Barber mengaktifkan status untuk hari ini.
     * Hanya bisa sekali per hari — kalau sudah pernah ada baris
     * (aktif atau sudah selesai), tidak bisa aktifkan lagi.
     */
    public function activateBarber(Request $request): RedirectResponse
    {
        $storeDay = StoreDay::today();
        $barberId = $request->user()->id;

        $sudahAda = BarberDailyStatus::where('store_day_id', $storeDay->id)
            ->where('barber_id', $barberId)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Kamu sudah mengaktifkan status hari ini.');
        }

        BarberDailyStatus::create([
            'store_day_id' => $storeDay->id,
            'barber_id' => $barberId,
            'status' => 'aktif',
            'activated_at' => now(),
        ]);

        return back()->with('success', 'Status kamu aktif. Selamat bekerja!');
    }

    /**
     * Barber menutup status (selesai kerja) untuk hari ini.
     */
    public function deactivateBarber(Request $request): RedirectResponse
    {
        $storeDay = StoreDay::today();
        $barberId = $request->user()->id;

        $status = BarberDailyStatus::where('store_day_id', $storeDay->id)
            ->where('barber_id', $barberId)
            ->where('status', 'aktif')
            ->first();

        if (! $status) {
            return back()->with('error', 'Status aktif tidak ditemukan.');
        }

        $status->update([
            'status' => 'selesai',
            'deactivated_at' => now(),
        ]);

        return back()->with('success', 'Status kamu ditutup. Sampai jumpa besok!');
    }

    /**
     * Kasir konfirmasi buka toko.
     * Syarat: minimal 1 barber sudah aktifkan status hari ini.
     */
    public function confirmOpen(Request $request): RedirectResponse
    {
        $storeDay = StoreDay::today();

        if ($storeDay->status !== 'belum_buka') {
            return back()->with('error', 'Toko sudah dibuka atau sudah ditutup hari ini.');
        }

        $adaBarberAktif = BarberDailyStatus::where('store_day_id', $storeDay->id)
            ->where('status', 'aktif')
            ->exists();

        if (! $adaBarberAktif) {
            return back()->with('error', 'Belum ada barber yang aktif. Toko belum bisa dibuka.');
        }

        $storeDay->update([
            'status' => 'buka',
            'opened_by' => $request->user()->id,
            'opened_at' => now(),
        ]);

        return back()->with('success', 'Toko resmi dibuka.');
    }

    /**
     * Kasir konfirmasi tutup toko (sementara).
     * Status masih reversible - bisa dibuka lagi kalau ternyata masih
     * ada pelanggan dadakan. Belum generate closing harian di sini.
     */
    public function confirmClose(Request $request): RedirectResponse
    {
        $storeDay = StoreDay::today();

        if ($storeDay->status !== 'buka') {
            return back()->with('error', 'Toko belum dibuka hari ini.');
        }

        $masihAdaBarberAktif = BarberDailyStatus::where('store_day_id', $storeDay->id)
            ->where('status', 'aktif')
            ->exists();

        if ($masihAdaBarberAktif) {
            return back()->with('error', 'Masih ada barber yang belum menutup status. Toko belum bisa ditutup.');
        }

        $storeDay->update(['status' => 'tutup']);

        return back()->with('success', 'Toko ditutup sementara. Klik "Finalisasi Closing" kalau sudah yakin tidak ada transaksi lagi hari ini.');
    }

    /**
     * Buka toko lagi setelah sempat ditutup sementara.
     * Dipakai kalau ternyata masih ada pelanggan dadakan.
     */
    public function reopen(Request $request): RedirectResponse
    {
        $storeDay = StoreDay::today();

        if ($storeDay->status !== 'tutup') {
            return back()->with('error', 'Toko tidak dalam status tutup sementara.');
        }

        $storeDay->update(['status' => 'buka']);

        return back()->with('success', 'Toko dibuka kembali.');
    }

    /**
     * Finalisasi closing harian. Ini titik akhir yang tidak bisa dibatalkan -
     * generate rekap omzet, komisi barber, potongan cicilan, dan mengunci
     * data hari itu secara permanen.
     */
    public function finalizeClosing(Request $request, ClosingHarianService $closingService): RedirectResponse
    {
        $storeDay = StoreDay::today();

        if ($storeDay->status !== 'tutup') {
            return back()->with('error', 'Toko harus ditutup sementara dulu sebelum bisa difinalisasi.');
        }

        $closingService->finalize($storeDay, $request->user()->id);

        return back()->with('success', 'Closing harian berhasil difinalisasi dan datanya sudah dikunci.');
    }
}