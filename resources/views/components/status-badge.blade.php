@props(['status' => ''])

@php
    $map = [
        // green
        'tersedia'   => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Tersedia'],
        'aktif'      => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Aktif'],
        'baik'       => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Baik'],
        'disetujui'  => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Disetujui'],
        'selesai'    => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Selesai'],
        'dikembalikan' => ['bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'Dikembalikan'],
        // blue / indigo
        'dipinjam'   => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'Dipinjam'],
        'diproses'   => ['bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400', 'Diproses'],
        // yellow
        'menunggu'   => ['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Menunggu'],
        // orange
        'reparasi'      => ['bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400', 'Reparasi'],
        'rusak_ringan'  => ['bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400', 'Rusak Ringan'],
        'kurang_baik'   => ['bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400', 'Kurang Baik'],
        // red
        'terlambat'     => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'Terlambat'],
        'rusak_berat'   => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'Rusak Berat'],
        'rusak_total'   => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'Rusak Total'],
        'ditolak'       => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'Ditolak'],
        // gray
        'hilang'     => ['bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400', 'Hilang'],
        'dihapuskan' => ['bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400', 'Dihapuskan'],
        'nonaktif'   => ['bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400', 'Nonaktif'],
    ];

    [$classes, $label] = $map[$status] ?? ['bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400', ucfirst($status)];
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
