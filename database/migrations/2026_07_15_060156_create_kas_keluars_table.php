<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_day_id')->constrained()->cascadeOnDelete();
            $table->decimal('nominal', 12, 2);
            $table->string('keterangan');
            $table->foreignId('input_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_keluars');
    }
};