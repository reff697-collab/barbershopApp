<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('closing_harian_barbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('closing_harian_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barber_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('komisi_kotor', 12, 2)->default(0);
            $table->decimal('potongan_cicilan', 12, 2)->default(0)
                ->comment('diisi di fase berikutnya (modul pinjaman), 0 dulu untuk sekarang');
            $table->decimal('komisi_bersih', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('closing_harian_barbers');
    }
};