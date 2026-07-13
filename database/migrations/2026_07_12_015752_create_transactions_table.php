<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_day_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kasir_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barber_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('total_layanan', 12, 2)->default(0)
                ->comment('subtotal khusus item jenis layanan, dasar hitung komisi');
            $table->decimal('komisi_barber', 12, 2)->default(0)
                ->comment('50% dari total_layanan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};