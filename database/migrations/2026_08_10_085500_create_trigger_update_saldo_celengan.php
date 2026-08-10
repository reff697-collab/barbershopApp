<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Function yang menghitung ulang saldo celengan berdasarkan
        // SEMUA baris di celengan_transaksis untuk celengan itu.
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_saldo_celengan()
            RETURNS TRIGGER AS $$
            BEGIN
                UPDATE celengans
                SET saldo = (
                    SELECT COALESCE(SUM(
                        CASE WHEN tipe = \'masuk\' THEN nominal ELSE -nominal END
                    ), 0)
                    FROM celengan_transaksis
                    WHERE celengan_id = COALESCE(NEW.celengan_id, OLD.celengan_id)
                )
                WHERE id = COALESCE(NEW.celengan_id, OLD.celengan_id);

                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Trigger yang manggil function di atas setiap kali ada
        // INSERT, UPDATE, atau DELETE di tabel celengan_transaksis -
        // baik lewat aplikasi Laravel MAUPUN lewat Supabase manual.
        DB::unprepared('
            DROP TRIGGER IF EXISTS trigger_update_saldo_celengan ON celengan_transaksis;

            CREATE TRIGGER trigger_update_saldo_celengan
            AFTER INSERT OR UPDATE OR DELETE ON celengan_transaksis
            FOR EACH ROW
            EXECUTE FUNCTION update_saldo_celengan();
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_update_saldo_celengan ON celengan_transaksis;');
        DB::unprepared('DROP FUNCTION IF EXISTS update_saldo_celengan();');
    }
};