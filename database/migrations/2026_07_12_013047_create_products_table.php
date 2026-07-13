<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['dijual', 'pakai'])
                ->comment('dijual = produk dijual ke pelanggan, pakai = bahan habis pakai untuk jasa');
            $table->decimal('harga', 12, 2)->default(0)
                ->comment('harga jual, 0 untuk produk jenis pakai');
            $table->integer('stok')->default(0);
            $table->integer('min_stok')->default(5)
                ->comment('ambang batas untuk peringatan stok menipis');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};