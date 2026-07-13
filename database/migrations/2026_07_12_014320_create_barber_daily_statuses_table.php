<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barber_daily_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_day_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barber_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            // Satu barber cuma boleh punya 1 baris status per hari
            // (sesuai keputusan kita: aktif sekali per hari, bukan bisa berkali-kali)
            $table->unique(['store_day_id', 'barber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barber_daily_statuses');
    }
};