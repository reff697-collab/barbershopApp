<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['layanan', 'produk']);
            $table->unsignedBigInteger('item_id');
            $table->string('nama')
                ->comment('snapshot nama item saat transaksi, biar riwayat tetap akurat walau nama aslinya berubah nanti');
            $table->integer('qty')->default(1);
            $table->decimal('harga', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};