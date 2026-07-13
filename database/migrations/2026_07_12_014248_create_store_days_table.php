<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_days', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->enum('status', ['belum_buka', 'buka', 'tutup'])
                ->default('belum_buka');
            $table->foreignId('opened_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('opened_at')->nullable();
            $table->foreignId('closed_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_days');
    }
};