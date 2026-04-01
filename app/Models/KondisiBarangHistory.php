<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KondisiBarangHistory extends Model
{
    public $timestamps = false;

    protected $table = 'kondisi_barang_history';

    protected $fillable = [
        'barang_id',
        'kode_barang',
        'nup',
        'kondisi_lama',
        'kondisi_baru',
        'catatan',
        'source',
        'source_id',
        'changed_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getKondisiLamaLabelAttribute(): string
    {
        return match ($this->kondisi_lama) {
            'baik'        => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default        => '—',
        };
    }

    public function getKondisiBaruLabelAttribute(): string
    {
        return match ($this->kondisi_baru) {
            'baik'        => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default        => $this->kondisi_baru,
        };
    }

    public function getKondisiLamaBadgeClassAttribute(): string
    {
        return match ($this->kondisi_lama) {
            'baik'        => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'rusak_ringan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'rusak_berat'  => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default        => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
        };
    }

    public function getKondisiBaruBadgeClassAttribute(): string
    {
        return match ($this->kondisi_baru) {
            'baik'        => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'rusak_ringan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'rusak_berat'  => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default        => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
        };
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'return'       => 'Pengembalian',
            'manual'       => 'Manual (Admin)',
            'import'       => 'Import',
            'stock_opname' => 'Stock Opname',
            default        => $this->source,
        };
    }

    public function getSourceBadgeClassAttribute(): string
    {
        return match ($this->source) {
            'return'       => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
            'manual'       => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
            'import'       => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
            'stock_opname' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
            default        => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
        };
    }
}
