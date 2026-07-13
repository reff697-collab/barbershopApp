<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closing_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_day_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('total_omzet', 12, 2)->default(0)
                ->comment('total layanan + produk terjual');
            $table->decimal('total_omzet_layanan', 12, 2)->default(0);
            $table->decimal('total_omzet_produk', 12, 2)->default(0);
            $table->decimal('total_komisi_barber', 12, 2)->default(0);
            $table->decimal('total_pengeluaran', 12, 2)->default(0)
                ->comment('diisi di fase berikutnya, 0 dulu untuk sekarang');
            $table->decimal('laba_bersih', 12, 2)->default(0);
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closing_harians');
    }
};