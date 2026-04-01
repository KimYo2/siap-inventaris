<?php

namespace App\Services;

use App\Models\Notifikasi;
use Carbon\Carbon;

class NotifikasiService
{
    /**
     * Send an in-app notification to a user.
     */
    public function send(
        int $userId,
        string $judul,
        string $pesan,
        string $type,
        ?string $relatedModel = null,
        ?int $relatedId = null
    ): void {
        Notifikasi::create([
            'user_id'       => $userId,
            'judul'         => $judul,
            'pesan'         => $pesan,
            'type'          => $type,
            'is_read'       => false,
            'related_model' => $relatedModel,
            'related_id'    => $relatedId,
            'created_at'    => Carbon::now('Asia/Jakarta'),
        ]);
    }
}
