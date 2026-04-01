<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tiket_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20);
            $table->integer('nup');
            $table->foreignId('histori_id')->nullable()->constrained('histori_peminjaman')->nullOnDelete();
            $table->foreignId('dilaporkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('deskripsi');
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('resolusi', ['diperbaiki', 'dihapuskan', 'hilang', 'diabaikan'])->nullable();
            $table->text('catatan_resolusi')->nullable();
            $table->timestamp('diselesaikan_at')->nullable();
            $table->foreignId('diselesaikan_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('kode_barang');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_kerusakan');
    }
};
