<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20);
            $table->integer('nup');
            $table->string('nama_barang', 255)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('tipe', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->nullOnDelete();
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->nullOnDelete();
            $table->enum('kondisi_terakhir', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('ketersediaan', ['tersedia', 'dipinjam', 'reparasi'])->default('tersedia');
            $table->enum('status_barang', ['aktif', 'rusak_total', 'hilang', 'dihapuskan'])->default('aktif');
            $table->text('catatan_status')->nullable();
            $table->timestamp('status_diupdate_at')->nullable();
            $table->foreignId('status_diupdate_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('peminjam_terakhir', 100)->nullable();
            $table->timestamp('waktu_pinjam')->nullable();
            $table->timestamp('waktu_kembali')->nullable();
            $table->timestamps();

            $table->unique(['kode_barang', 'nup']);
            $table->index('kode_barang');
            $table->index('ketersediaan');
            $table->index('kondisi_terakhir');
            $table->index('status_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
