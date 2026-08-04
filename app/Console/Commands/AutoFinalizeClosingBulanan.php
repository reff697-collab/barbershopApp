<?php

namespace App\Console\Commands;

use App\Services\ClosingBulananService;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class AutoFinalizeClosingBulanan extends Command
{
    /**
     * Nama & signature command Artisan.
     */
    protected $signature = 'store:auto-finalize-bulanan';

    /**
     * Deskripsi command.
     */
    protected $description = 'Otomatis membuat closing bulanan untuk bulan sebelumnya, dijalankan setiap tanggal 1';

    /**
     * Jalankan command.
     */
    public function handle(ClosingBulananService $closingBulananService): void
    {
        if (! now()->isSameDay(now()->startOfMonth())) {
            $pesan = 'Auto-finalize bulanan: bukan tanggal 1, command dilewati.';
            $this->info($pesan);
            Log::info($pesan);
            return;
        }

        $bulanLalu = now()->subMonthNoOverflow();

        $closing = $closingBulananService->generate($bulanLalu->month, $bulanLalu->year, null);

        if (! $closing) {
            $pesan = "Auto-finalize bulanan: tidak ada yang direkap untuk bulan {$bulanLalu->format('F Y')} (mungkin sudah pernah dibuat, atau tidak ada data).";
            $this->info($pesan);
            Log::info($pesan);
            return;
        }

        $pesan = "Auto-finalize bulanan: closing bulanan {$bulanLalu->format('F Y')} berhasil dibuat otomatis oleh sistem.";
        $this->info($pesan);
        Log::info($pesan);
    }
}