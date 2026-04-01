<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\KondisiBarangHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KondisiHistoryService
{
    /**
     * Record a condition change for a barang.
     *
     * Only records an entry when kondisi_baru differs from the current
     * kondisi_terakhir, or when no prior history exists (initial snapshot).
     * After recording, updates barang->kondisi_terakhir.
     */
    public function record(
        Barang $barang,
        string $kondisiBaru,
        string $source,
        ?int $sourceId = null,
        ?string $catatan = null,
        ?int $changedBy = null
    ): void {
        $kondisiLama = $barang->kondisi_terakhir;

        $hasHistory = KondisiBarangHistory::where('barang_id', $barang->id)->exists();

        // Skip if no real change and history already exists
        if ($hasHistory && $kondisiLama === $kondisiBaru) {
            return;
        }

        KondisiBarangHistory::create([
            'barang_id'  => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nup'        => $barang->nup,
            'kondisi_lama' => $kondisiLama ?: null,
            'kondisi_baru' => $kondisiBaru,
            'catatan'    => $catatan,
            'source'     => $source,
            'source_id'  => $sourceId,
            'changed_by' => $changedBy ?? (Auth::check() ? Auth::id() : null),
            'created_at' => Carbon::now('Asia/Jakarta'),
        ]);

        $barang->update(['kondisi_terakhir' => $kondisiBaru]);
    }
}
