<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->enum('status_barang', ['aktif', 'rusak_total', 'hilang', 'dihapuskan'])
                  ->default('aktif')
                  ->after('ketersediaan');
            $table->text('catatan_status')->nullable()->after('status_barang');
            $table->timestamp('status_diupdate_at')->nullable()->after('catatan_status');
            $table->foreignId('status_diupdate_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('status_diupdate_at');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['status_diupdate_by']);
            $table->dropColumn(['status_barang', 'catatan_status', 'status_diupdate_at', 'status_diupdate_by']);
        });
    }
};
