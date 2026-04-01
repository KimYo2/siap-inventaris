<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketKerusakan extends Model
{
    protected $table = 'tiket_kerusakan';

    protected $fillable = [
        'nomor_bmn',
        'pelapor',
        'jenis_kerusakan',
        'deskripsi',
        'status',
        'tanggal_lapor',
        'priority',
        'assigned_to',
        'target_selesai_at',
        'closed_at',
        'admin_notes',
        'resolusi',
        'catatan_resolusi',
        'diselesaikan_at',
        'diselesaikan_by',
    ];

    protected $casts = [
        'tanggal_lapor'    => 'datetime',
        'target_selesai_at'=> 'datetime',
        'closed_at'        => 'datetime',
        'diselesaikan_at'  => 'datetime',
    ];

    public $timestamps = false;

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'diselesaikan_by');
    }

    public function getResolusiLabelAttribute(): string
    {
        return match ($this->resolusi) {
            'diperbaiki'  => 'Diperbaiki',
            'dihapuskan'  => 'Rusak Total',
            'hilang'      => 'Hilang',
            'diabaikan'   => 'Diabaikan',
            default       => '-',
        };
    }

    public function getResolusiBadgeClassAttribute(): string
    {
        return match ($this->resolusi) {
            'diperbaiki'  => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'dihapuskan'  => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'hilang'      => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
            'diabaikan'   => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
            default       => 'bg-slate-100 text-slate-500',
        };
    }
}
