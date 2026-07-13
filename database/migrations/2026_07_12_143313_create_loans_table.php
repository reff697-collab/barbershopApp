<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('jumlah_pinjaman', 12, 2);
            $table->decimal('cicilan_per_hari', 12, 2);
            $table->decimal('sisa_hutang', 12, 2);
            $table->enum('status', ['aktif', 'lunas'])->default('aktif');
            $table->date('tanggal_pinjam');
            $table->text('catatan')->nullable();
            $table->foreignId('input_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};