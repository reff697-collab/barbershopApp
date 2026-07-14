<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('closing_harians', function (Blueprint $table) {
            $table->decimal('total_kas_keluar', 12, 2)->default(0)->after('total_pengeluaran');
        });
    }

    public function down(): void
    {
        Schema::table('closing_harians', function (Blueprint $table) {
            $table->dropColumn('total_kas_keluar');
        });
    }
};