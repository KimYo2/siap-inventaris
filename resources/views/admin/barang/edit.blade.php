@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 transition-colors">

            <div class="mb-6">
                <h1 class="text-xl font-bold text-slate-800 dark:text-white">Edit Barang</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $barang->kode_barang }}-{{ sprintf('%03d', $barang->nup) }}
                </p>
            </div>

            <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Kode
                            Barang</label>
                        <input type="text" value="{{ $barang->kode_barang }}" disabled
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed transition-colors">
                    </div>

                    <div class="col-span-1">
                        <label
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">NUP</label>
                        <input type="text" value="{{ $barang->nup }}" disabled
                            class="w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 cursor-not-allowed transition-colors">
                    </div>
                </div>

                <div>
                    <label for="brand"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Brand
                        / Merk</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $barang->brand) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipe"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Tipe /
                        Model</label>
                    <input type="text" name="tipe" id="tipe" value="{{ old('tipe', $barang->tipe) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    @error('tipe')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="pic_user_id"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">PIC
                        (Penanggung Jawab)</label>
                    <select name="pic_user_id" id="pic_user_id"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                        <option value="">- Tidak ada -</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('pic_user_id', $barang->pic_user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->nama }} ({{ $user->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('pic_user_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label for="kategori_id"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Kategori</label>
                        <select name="kategori_id" id="kategori_id"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="">- Tidak ada -</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ old('kategori_id', $barang->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="ruangan_id"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Ruangan</label>
                        <select name="ruangan_id" id="ruangan_id"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="">- Tidak ada -</option>
                            @foreach($ruanganList as $rng)
                                <option value="{{ $rng->id }}"
                                    {{ old('ruangan_id', $barang->ruangan_id) == $rng->id ? 'selected' : '' }}>
                                    {{ $rng->nama_ruangan }} ({{ $rng->kode_ruangan }})
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="col-span-1">
                        <label for="kondisi"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Kondisi
                            Terakhir</label>
                        <select name="kondisi" id="kondisi" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="baik" {{ old('kondisi', $barang->kondisi_terakhir) == 'baik' ? 'selected' : '' }}>
                                Baik</option>
                            <option value="rusak_ringan" {{ old('kondisi', $barang->kondisi_terakhir) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="rusak_berat" {{ old('kondisi', $barang->kondisi_terakhir) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label for="ketersediaan"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Status
                            Ketersediaan</label>
                        <select name="ketersediaan" id="ketersediaan" required
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                            <option value="tersedia" {{ old('ketersediaan', $barang->ketersediaan) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="dipinjam" {{ old('ketersediaan', $barang->ketersediaan) == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="hilang" {{ old('ketersediaan', $barang->ketersediaan) == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            <option value="reparasi" {{ old('ketersediaan', $barang->ketersediaan) == 'reparasi' ? 'selected' : '' }}>Dalam Perbaikan</option>
                        </select>
                        @error('ketersediaan')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="keterangan"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition"
                        placeholder="Catatan tambahan tentang barang (opsional)">{{ old('keterangan', $barang->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('admin.barang.qr-label', $barang->id) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-emerald-700 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-lg font-medium text-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                        Cetak QR
                    </a>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.barang.index') }}"
                            class="px-5 py-2.5 text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg font-medium transition">Batal</a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-8 py-2.5 rounded-lg font-medium shadow-sm transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- ===================== RIWAYAT KONDISI ===================== --}}
        <div class="mt-8 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 transition-colors">

            <div class="flex items-center gap-2 mb-6">
                <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Riwayat Kondisi Barang</h2>
                <span class="ml-auto text-xs text-slate-400 dark:text-slate-500">{{ $kondisiHistory->count() }} entri</span>
            </div>

            @if($kondisiHistory->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">Belum ada riwayat perubahan kondisi</p>
                </div>
            @else
                <ol class="relative border-l border-slate-200 dark:border-slate-700 ml-3 space-y-0">
                    @foreach($kondisiHistory as $entry)
                        <li class="mb-6 ml-6">
                            {{-- Timeline dot --}}
                            <span class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full ring-4 ring-white dark:ring-slate-800
                                {{ $entry->kondisi_baru === 'baik' ? 'bg-green-100 dark:bg-green-900/40' : ($entry->kondisi_baru === 'rusak_berat' ? 'bg-red-100 dark:bg-red-900/40' : 'bg-yellow-100 dark:bg-yellow-900/40') }}">
                                <svg class="w-3 h-3 {{ $entry->kondisi_baru === 'baik' ? 'text-green-600 dark:text-green-400' : ($entry->kondisi_baru === 'rusak_berat' ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400') }}"
                                    fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                            </span>

                            {{-- Header row: date + source badge --}}
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <time class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                    {{ $entry->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                </time>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $entry->source_badge_class }}">
                                    {{ $entry->source_label }}
                                    @if($entry->source === 'return' && $entry->source_id)
                                        &nbsp;#{{ $entry->source_id }}
                                    @elseif($entry->source === 'stock_opname' && $entry->source_id)
                                        &nbsp;#{{ $entry->source_id }}
                                    @endif
                                </span>
                                @if($entry->source === 'return' && $entry->source_id)
                                    <a href="{{ route('admin.histori.index') }}"
                                       class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                        Lihat histori →
                                    </a>
                                @endif
                            </div>

                            {{-- Kondisi change row --}}
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                @if($entry->kondisi_lama)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $entry->kondisi_lama_badge_class }}">
                                        {{ $entry->kondisi_lama_label }}
                                    </span>
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                @else
                                    <span class="text-xs text-slate-400 italic">kondisi awal</span>
                                    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $entry->kondisi_baru_badge_class }}">
                                    {{ $entry->kondisi_baru_label }}
                                </span>
                            </div>

                            {{-- Catatan & changed_by --}}
                            <div class="text-xs text-slate-500 dark:text-slate-400 space-y-0.5">
                                @if($entry->catatan)
                                    <p class="italic">"{{ $entry->catatan }}"</p>
                                @endif
                                <p>
                                    Oleh:
                                    <span class="font-medium text-slate-700 dark:text-slate-300">
                                        {{ $entry->changedBy?->nama ?? 'Sistem' }}
                                    </span>
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif

        </div>
        {{-- ============================================================ --}}

    </div>
@endsection
