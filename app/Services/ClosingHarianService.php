<?php

namespace App\Services;

use App\Models\ClosingHarian;
use App\Models\ClosingHarianBarber;
use App\Models\Expense;
use App\Models\KasKeluar;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\StoreDay;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ClosingHarianService
{
    /**
     * Finalisasi closing harian untuk satu StoreDay.
     * Dipakai baik dari tombol manual kasir maupun dari sistem otomatis
     * (auto-finalize tengah malam), makanya closedByUserId boleh null.
     */
    public function finalize(StoreDay $storeDay, ?int $closedByUserId = null): ClosingHarian
    {
        return DB::transaction(function () use ($storeDay, $closedByUserId) {
            $storeDay->update([
                'status' => 'selesai',
                'closed_by' => $closedByUserId,
                'closed_at' => now(),
            ]);

            $transactions = Transaction::where('store_day_id', $storeDay->id)->get();

            $totalOmzetLayanan = $transactions->sum('total_layanan');
            $totalOmzetProduk = $transactions->sum('total') - $totalOmzetLayanan;
            $totalOmzet = $transactions->sum('total');
            $totalKomisi = $transactions->sum('komisi_barber');
            $totalOmzetTunai = $transactions->where('payment_method', 'tunai')->sum('total');
            $totalOmzetQris = $transactions->where('payment_method', 'qris')->sum('total');
            $totalPengeluaran = Expense::whereDate('tanggal', $storeDay->tanggal)->sum('nominal');
            $totalKasKeluar = KasKeluar::where('store_day_id', $storeDay->id)->sum('nominal');

            $closingHarian = ClosingHarian::create([
                'store_day_id' => $storeDay->id,
                'total_omzet' => $totalOmzet,
                'total_omzet_layanan' => $totalOmzetLayanan,
                'total_omzet_produk' => $totalOmzetProduk,
                'total_omzet_tunai' => $totalOmzetTunai,
                'total_omzet_qris' => $totalOmzetQris,
                'total_komisi_barber' => $totalKomisi,
                'total_pengeluaran' => $totalPengeluaran,
                'total_kas_keluar' => $totalKasKeluar,
                'laba_bersih' => $totalOmzet - $totalKomisi - $totalKasKeluar,
                'closed_by' => $closedByUserId,
                'closed_at' => now(),
            ]);

            $transaksiPerBarber = $transactions->groupBy('barber_id');

            foreach ($transaksiPerBarber as $barberId => $items) {
                $totalLayananBarber = $items->sum('total_layanan');
                $komisiKotor = $items->sum('komisi_barber');
                $potonganCicilan = 0;

                $loan = Loan::where('barber_id', $barberId)
                    ->where('status', 'aktif')
                    ->first();

                if ($loan && $komisiKotor >= $loan->cicilan_per_hari) {
                    $potonganCicilan = $loan->cicilan_per_hari;

                    LoanPayment::create([
                        'loan_id' => $loan->id,
                        'closing_harian_id' => $closingHarian->id,
                        'jumlah_dipotong' => $potonganCicilan,
                        'tanggal' => $storeDay->tanggal,
                    ]);

                    $sisaBaru = $loan->sisa_hutang - $potonganCicilan;
                    $loan->update([
                        'sisa_hutang' => $sisaBaru,
                        'status' => $sisaBaru <= 0 ? 'lunas' : 'aktif',
                    ]);
                }

                ClosingHarianBarber::create([
                    'closing_harian_id' => $closingHarian->id,
                    'barber_id' => $barberId,
                    'total_layanan' => $totalLayananBarber,
                    'komisi_kotor' => $komisiKotor,
                    'potongan_cicilan' => $potonganCicilan,
                    'komisi_bersih' => $komisiKotor - $potonganCicilan,
                ]);
            }

            return $closingHarian;
        });
    }
}