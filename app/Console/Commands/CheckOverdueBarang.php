<?php

namespace App\Console\Commands;

use App\Models\HistoriPeminjaman;
use App\Models\Notifikasi;
use App\Models\User;
use App\Services\NotifikasiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOverdueBarang extends Command
{
    protected $signature = 'barang:check-overdue';

    protected $description = 'Mark overdue borrowings as terlambat and send notifications';

    public function handle(NotifikasiService $notifikasiService): int
    {
        $today = Carbon::today('Asia/Jakarta');

        $overdueList = HistoriPeminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->where('tanggal_jatuh_tempo', '<', $today)
            ->get();

        if ($overdueList->isEmpty()) {
            $this->info('No overdue borrowings found.');
            return Command::SUCCESS;
        }

        // Cache admin IDs to avoid repeated queries
        $adminIds = User::where('role', 'admin')
            ->where('is_active', true)
            ->pluck('id');

        $count = 0;

        foreach ($overdueList as $histori) {
            // Update status to terlambat
            $histori->update(['status' => 'terlambat']);

            // Skip if overdue notification was already sent for this histori record
            $alreadyNotified = Notifikasi::where('type', 'overdue')
                ->where('related_model', 'histori_peminjaman')
                ->where('related_id', $histori->id)
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            $jatuhTempo = Carbon::parse($histori->tanggal_jatuh_tempo)->format('d M Y');
            $namaBarang = $histori->kode_barang . ' NUP ' . $histori->nup;

            // Notify the borrower
            $borrower = User::where('nip', $histori->nip_peminjam)->first();
            if ($borrower) {
                $notifikasiService->send(
                    $borrower->id,
                    'Peminjaman Melewati Batas Waktu',
                    "Peminjaman {$namaBarang} Anda telah melewati batas waktu pengembalian ({$jatuhTempo}). Segera kembalikan.",
                    'overdue',
                    'histori_peminjaman',
                    $histori->id
                );
            }

            // Notify all active admins
            foreach ($adminIds as $adminId) {
                $notifikasiService->send(
                    $adminId,
                    'Peminjaman Terlambat',
                    "{$histori->nama_peminjam} terlambat mengembalikan {$namaBarang} (jatuh tempo: {$jatuhTempo}).",
                    'overdue',
                    'histori_peminjaman',
                    $histori->id
                );
            }

            $count++;
        }

        $this->info("Processed {$count} new overdue borrowing(s).");

        return Command::SUCCESS;
    }
}
