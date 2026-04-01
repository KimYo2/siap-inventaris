<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kondisi_barang_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->string('kode_barang', 20);
            $table->integer('nup');
            $table->enum('kondisi_lama', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->enum('kondisi_baru', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->text('catatan')->nullable();
            $table->enum('source', ['return', 'manual', 'import', 'stock_opname']);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['barang_id', 'created_at']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kondisi_barang_history');
    }
};
