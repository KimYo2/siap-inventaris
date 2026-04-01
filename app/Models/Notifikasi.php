<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    public $timestamps = false;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'type',
        'is_read',
        'related_model',
        'related_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'is_read'    => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'approval'  => 'Persetujuan',
            'waitlist'  => 'Antrean',
            'overdue'   => 'Keterlambatan',
            'info'      => 'Informasi',
            default     => ucfirst($this->type),
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'approval' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
            'waitlist' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
            'overdue'  => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
            'info'     => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
            default    => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
        };
    }
}
