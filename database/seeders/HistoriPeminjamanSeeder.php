<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriPeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now('Asia/Jakarta');

        // Users: 1=Admin, 2=Dewi(admin), 3=Budi, 4=Siti, 5=Ahmad, 6=Rina, 7=Fajar

        $histori = [
            // === Peminjaman yang sudah dikembalikan ===
            [
                'kode_barang' => '3100102002', 'nup' => 20,
                'nip_peminjam' => '199001012015041001', 'nama_peminjam' => 'Budi Santoso',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $now->copy()->subDays(30),
                'waktu_pinjam' => $now->copy()->subDays(29),
                'waktu_kembali' => $now->copy()->subDays(22),
                'tanggal_jatuh_tempo' => $now->copy()->subDays(22)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 1, 'approved_at' => $now->copy()->subDays(29),
            ],
            [
                'kode_barang' => '3100102002', 'nup' => 23,
                'nip_peminjam' => '199505012018041001', 'nama_peminjam' => 'Siti Nurhaliza',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $now->copy()->subDays(25),
                'waktu_pinjam' => $now->copy()->subDays(24),
                'waktu_kembali' => $now->copy()->subDays(18),
                'tanggal_jatuh_tempo' => $now->copy()->subDays(17)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 1, 'approved_at' => $now->copy()->subDays(24),
            ],
            [
                'kode_barang' => '3100203003', 'nup' => 12,
                'nip_peminjam' => '199808012020041001', 'nama_peminjam' => 'Ahmad Wijaya',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $now->copy()->subDays(20),
                'waktu_pinjam' => $now->copy()->subDays(19),
                'waktu_kembali' => $now->copy()->subDays(10),
                'tanggal_jatuh_tempo' => $now->copy()->subDays(0)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'rusak_ringan',
                'catatan_kondisi' => 'Ada goresan pada badan printer',
                'approved_by' => 2, 'approved_at' => $now->copy()->subDays(19),
            ],
            [
                'kode_barang' => '3100102001', 'nup' => 47,
                'nip_peminjam' => '199203152019041003', 'nama_peminjam' => 'Rina Wulandari',
                'status' => 'dikembalikan',
                'waktu_pengajuan' => $now->copy()->subDays(15),
                'waktu_pinjam' => $now->copy()->subDays(14),
                'waktu_kembali' => $now->copy()->subDays(7),
                'tanggal_jatuh_tempo' => $now->copy()->subDays(0)->toDateString(),
                'kondisi_awal' => 'baik', 'kondisi_kembali' => 'baik',
                'approved_by' => 1, 'approved_at' => $now->copy()->subDays(14),
            ],

            // === Peminjaman aktif (sedang dipinjam) ===
            [
                'kode_barang' => '3100102001', 'nup' => 13,
                'nip_peminjam' => '199001012015041001', 'nama_peminjam' => 'Budi Santoso',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $now->copy()->subDays(5),
                'waktu_pinjam' => $now->copy()->subDays(4),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(10)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $now->copy()->subDays(4),
            ],
            [
                'kode_barang' => '3100102002', 'nup' => 22,
                'nip_peminjam' => '199505012018041001', 'nama_peminjam' => 'Siti Nurhaliza',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $now->copy()->subDays(3),
                'waktu_pinjam' => $now->copy()->subDays(2),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(5)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 2, 'approved_at' => $now->copy()->subDays(2),
            ],
            [
                'kode_barang' => '3100102002', 'nup' => 25,
                'nip_peminjam' => '199607202021041004', 'nama_peminjam' => 'Fajar Prasetyo',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $now->copy()->subDays(6),
                'waktu_pinjam' => $now->copy()->subDays(5),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(2)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $now->copy()->subDays(5),
                'perpanjangan_status' => 'menunggu',
                'perpanjangan_hari' => 7,
                'perpanjangan_diajukan_at' => $now->copy()->subDays(1),
            ],
            [
                'kode_barang' => '3100102001', 'nup' => 38,
                'nip_peminjam' => '199203152019041003', 'nama_peminjam' => 'Rina Wulandari',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $now->copy()->subDays(8),
                'waktu_pinjam' => $now->copy()->subDays(7),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(7)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $now->copy()->subDays(7),
                'perpanjangan_status' => 'disetujui',
                'perpanjangan_hari' => 7,
                'perpanjangan_diajukan_at' => $now->copy()->subDays(2),
                'perpanjangan_disetujui_by' => 1,
                'perpanjangan_disetujui_at' => $now->copy()->subDays(1),
            ],
            [
                'kode_barang' => '3100203003', 'nup' => 17,
                'nip_peminjam' => '199808012020041001', 'nama_peminjam' => 'Ahmad Wijaya',
                'status' => 'dipinjam',
                'waktu_pengajuan' => $now->copy()->subDays(2),
                'waktu_pinjam' => $now->copy()->subDays(1),
                'tanggal_jatuh_tempo' => $now->copy()->addDays(29)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 2, 'approved_at' => $now->copy()->subDays(1),
            ],

            // === Terlambat (overdue) ===
            [
                'kode_barang' => '3100102001', 'nup' => 12,
                'nip_peminjam' => '199607202021041004', 'nama_peminjam' => 'Fajar Prasetyo',
                'status' => 'terlambat',
                'waktu_pengajuan' => $now->copy()->subDays(20),
                'waktu_pinjam' => $now->copy()->subDays(19),
                'tanggal_jatuh_tempo' => $now->copy()->subDays(5)->toDateString(),
                'kondisi_awal' => 'baik',
                'approved_by' => 1, 'approved_at' => $now->copy()->subDays(19),
            ],

            // === Menunggu persetujuan ===
            [
                'kode_barang' => '3100102001', 'nup' => 55,
                'nip_peminjam' => '199808012020041001', 'nama_peminjam' => 'Ahmad Wijaya',
                'status' => 'menunggu',
                'waktu_pengajuan' => $now->copy()->subHours(3),
            ],
            [
                'kode_barang' => '3100203004', 'nup' => 2,
                'nip_peminjam' => '199203152019041003', 'nama_peminjam' => 'Rina Wulandari',
                'status' => 'menunggu',
                'waktu_pengajuan' => $now->copy()->subHours(1),
            ],

            // === Ditolak ===
            [
                'kode_barang' => '3100102001', 'nup' => 45,
                'nip_peminjam' => '199607202021041004', 'nama_peminjam' => 'Fajar Prasetyo',
                'status' => 'ditolak',
                'waktu_pengajuan' => $now->copy()->subDays(10),
                'rejected_at' => $now->copy()->subDays(9),
                'rejection_reason' => 'Barang sedang digunakan untuk kebutuhan Kepala BPS',
                'approved_by' => 1,
            ],
        ];

        foreach ($histori as $row) {
            $data = array_merge([
                'waktu_pinjam' => null,
                'waktu_kembali' => null,
                'tanggal_jatuh_tempo' => null,
                'kondisi_awal' => null,
                'kondisi_kembali' => null,
                'catatan_kondisi' => null,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_at' => null,
                'rejection_reason' => null,
                'perpanjangan_status' => 'tidak_ada',
                'perpanjangan_hari' => null,
                'perpanjangan_diajukan_at' => null,
                'perpanjangan_disetujui_by' => null,
                'perpanjangan_disetujui_at' => null,
                'perpanjangan_ditolak_at' => null,
                'perpanjangan_reject_reason' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ], $row);

            DB::table('histori_peminjaman')->insert($data);
        }
    }
}
