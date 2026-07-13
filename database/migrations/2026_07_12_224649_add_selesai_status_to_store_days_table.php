<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE store_days DROP CONSTRAINT store_days_status_check');
        DB::statement("ALTER TABLE store_days ADD CONSTRAINT store_days_status_check CHECK (status IN ('belum_buka', 'buka', 'tutup', 'selesai'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE store_days DROP CONSTRAINT store_days_status_check');
        DB::statement("ALTER TABLE store_days ADD CONSTRAINT store_days_status_check CHECK (status IN ('belum_buka', 'buka', 'tutup'))");
    }
};