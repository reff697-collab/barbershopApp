<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('closing_harian_barbers', function (Blueprint $table) {
            $table->decimal('total_layanan', 12, 2)->default(0)->after('barber_id');
        });
    }

    public function down(): void
    {
        Schema::table('closing_harian_barbers', function (Blueprint $table) {
            $table->dropColumn('total_layanan');
        });
    }
};