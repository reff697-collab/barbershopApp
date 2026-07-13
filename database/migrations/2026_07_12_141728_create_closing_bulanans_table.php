<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closing_bulanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->decimal('total_omzet', 12, 2)->default(0);
            $table->decimal('total_omzet_layanan', 12, 2)->default(0);
            $table->decimal('total_omzet_produk', 12, 2)->default(0);
            $table->decimal('total_komisi_barber', 12, 2)->default(0);
            $table->decimal('total_pengeluaran', 12, 2)->default(0);
            $table->decimal('laba_bersih', 12, 2)->default(0);
            $table->integer('jumlah_hari_closing')->default(0)
                ->comment('berapa hari closing harian yang masuk rekap ini');
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->unique(['bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closing_bulanans');
    }
};