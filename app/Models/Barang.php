<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nup',
        'brand',
        'tipe',
        'kondisi_terakhir',
        'keterangan',
        'nama_barang',
        'ketersediaan',
        'status_barang',
        'catatan_status',
        'status_diupdate_at',
        'status_diupdate_by',
        'pic_user_id',
        'kategori_id',
        'ruangan_id',
        'peminjam_terakhir',
        'waktu_pinjam',
        'waktu_kembali'
    ];

    public function scopeFilter($query, array $filters): void
    {
        if (!empty($filters['ketersediaan'])) {
            $query->where('ketersediaan', $filters['ketersediaan']);
        }

        if (!empty($filters['kategori_id'])) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if (!empty($filters['ruangan_id'])) {
            $query->where('ruangan_id', $filters['ruangan_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('tipe', 'like', "%{$search}%")
                    ->orWhere('peminjam_terakhir', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status_barang'])) {
            $query->where('status_barang', $filters['status_barang']);
        }
    }

    private function normalizedKondisi(): string
    {
        $value = strtolower(trim((string) $this->kondisi_terakhir));
        $value = str_replace(' ', '_', $value);

        return $value === '' ? 'baik' : $value;
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->normalizedKondisi()) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => 'Tidak Diketahui',
        };
    }

    public function getKondisiBadgeClassAttribute(): string
    {
        return match ($this->normalizedKondisi()) {
            'baik' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'rusak_ringan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'rusak_berat' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            default => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
        };
    }

    public function getKondisiDotClassAttribute(): string
    {
        return match ($this->normalizedKondisi()) {
            'baik' => 'bg-green-500',
            'rusak_ringan' => 'bg-yellow-500',
            'rusak_berat' => 'bg-red-500',
            default => 'bg-slate-400',
        };
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function kondisiHistory()
    {
        return $this->hasMany(KondisiBarangHistory::class);
    }

    public function statusUpdater()
    {
        return $this->belongsTo(User::class, 'status_diupdate_by');
    }

    // ── Status Barang helpers ──────────────────────────────────────────

    public function isAktif(): bool
    {
        return ($this->status_barang ?? 'aktif') === 'aktif';
    }

    public function isLost(): bool
    {
        return $this->status_barang === 'hilang';
    }

    public function isTotallyDamaged(): bool
    {
        return $this->status_barang === 'rusak_total';
    }

    public function isDisposed(): bool
    {
        return $this->status_barang === 'dihapuskan';
    }

    public function canBeBorrowed(): bool
    {
        return $this->isAktif() && $this->ketersediaan === 'tersedia';
    }

    public function getStatusBarangLabelAttribute(): string
    {
        return match ($this->status_barang ?? 'aktif') {
            'aktif'       => 'Aktif',
            'rusak_total' => 'Rusak Total',
            'hilang'      => 'Hilang',
            'dihapuskan'  => 'Dihapuskan',
            default       => 'Aktif',
        };
    }

    public function getStatusBarangBadgeClassAttribute(): string
    {
        return match ($this->status_barang ?? 'aktif') {
            'aktif'       => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            'rusak_total' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'hilang'      => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
            'dihapuskan'  => 'bg-zinc-800 text-zinc-100 dark:bg-zinc-700 dark:text-zinc-200',
            default       => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        };
    }
}
