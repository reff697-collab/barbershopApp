<?php

namespace App\Console\Commands;

use App\Models\BarberDailyStatus;
use App\Models\StoreDay;
use App\Services\ClosingHarianService;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class AutoFinalizeClosing extends Command
{
    /**
     * Nama & signature command Artisan.
     */
    protected $signature = 'store:auto-finalize';

    /**
     * Deskripsi command.
     */
    protected $description = 'Otomatis finalisasi closing harian untuk hari sebelumnya jika belum dilakukan manual oleh kasir';

    /**
     * Jalankan command.
     */
    public function handle(ClosingHarianService $closingService): void
    {
        $tanggalKemarin = now()->subDay()->toDateString();

        $storeDay = StoreDay::where('tanggal', $tanggalKemarin)->first();

        if (! $storeDay) {
            $pesan = "Auto-finalize harian: tidak ada data toko untuk tanggal {$tanggalKemarin}.";
            $this->info($pesan);
            Log::info($pesan);
            return;
        }

        if ($storeDay->status === 'selesai') {
            $pesan = "Auto-finalize harian: closing tanggal {$tanggalKemarin} sudah difinalisasi sebelumnya. Dilewati.";
            $this->info($pesan);
            Log::info($pesan);
            return;
        }

        BarberDailyStatus::where('store_day_id', $storeDay->id)
            ->where('status', 'aktif')
            ->update([
                'status' => 'selesai',
                'deactivated_at' => now(),
            ]);

        $closingService->finalize($storeDay, null);

        $pesan = "Auto-finalize harian: closing tanggal {$tanggalKemarin} berhasil difinalisasi otomatis oleh sistem.";
        $this->info($pesan);
        Log::info($pesan);
    }
}