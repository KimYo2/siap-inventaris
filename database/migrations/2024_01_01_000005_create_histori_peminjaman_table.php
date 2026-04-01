<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('histori_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20);
            $table->integer('nup');
            $table->string('nip_peminjam', 18);
            $table->string('nama_peminjam', 100);
            $table->enum('status', ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat'])->default('menunggu');
            $table->timestamp('waktu_pengajuan')->nullable();
            $table->timestamp('waktu_pinjam')->nullable();
            $table->timestamp('waktu_kembali')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('kondisi_awal', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->text('catatan_kondisi')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejection_reason', 255)->nullable();
            $table->enum('perpanjangan_status', ['tidak_ada', 'menunggu', 'disetujui', 'ditolak'])->default('tidak_ada');
            $table->unsignedInteger('perpanjangan_hari')->nullable();
            $table->timestamp('perpanjangan_diajukan_at')->nullable();
            $table->foreignId('perpanjangan_disetujui_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('perpanjangan_disetujui_at')->nullable();
            $table->timestamp('perpanjangan_ditolak_at')->nullable();
            $table->string('perpanjangan_reject_reason', 255)->nullable();
            $table->timestamps();

            $table->index('kode_barang');
            $table->index('nip_peminjam');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_peminjaman');
    }
};
