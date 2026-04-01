<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Models\Barang;
use App\Models\HistoriPeminjaman;
use App\Models\User;
use App\Services\NotifikasiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoriController extends Controller
{
    use LogsAudit;
    public function index(Request $request)
    {
        $query = HistoriPeminjaman::query()
            ->filter($request->only(['status', 'search']));

        if ($request->filled('date_from')) {
            $query->where('waktu_pinjam', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('waktu_pinjam', '<=', $request->date_to . ' 23:59:59');
        }

        $histori = $query->orderBy('waktu_pinjam', 'desc')
            ->paginate(15)
            ->withQueryString();

        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        return view('admin.histori.index', compact('histori', 'dateFrom', 'dateTo'));
    }

    public function export(Request $request)
    {
        $filters   = $request->only(['status', 'search']);
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        $query = HistoriPeminjaman::query()
            ->select([
                'id',
                'kode_barang',
                'nup',
                'nip_peminjam',
                'nama_peminjam',
                'status',
                'waktu_pengajuan',
                'waktu_pinjam',
                'waktu_kembali',
                'tanggal_jatuh_tempo',
                'kondisi_awal',
                'kondisi_kembali',
                'catatan_kondisi',
            ])
            ->filter($filters);

        if ($dateFrom) {
            $query->where('waktu_pinjam', '>=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $query->where('waktu_pinjam', '<=', $dateTo . ' 23:59:59');
        }

        $fromPart = $dateFrom ? str_replace('-', '', $dateFrom) : 'awal';
        $toPart   = $dateTo   ? str_replace('-', '', $dateTo)   : 'sekarang';
        $filename = 'histori_peminjaman_' . $fromPart . '_to_' . $toPart . '_' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.csv';

        $this->logAudit('export', 'histori_peminjaman', null, [
            'filters'   => array_filter($filters, fn ($value) => $value !== null && $value !== ''),
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'format'    => 'csv',
        ]);

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            $csvSafe = function ($value): string {
                if ($value === null) {
                    return '';
                }

                $value = (string) $value;

                if ($value === '') {
                    return '';
                }

                // Prevent CSV injection in Excel/Sheets
                if (preg_match('/^[=+\-@]/', $value)) {
                    return "'" . $value;
                }

                return $value;
            };

            $csvText = function ($value) use ($csvSafe): string {
                $value = $csvSafe($value);

                if ($value === '') {
                    return '';
                }

                // Force Excel to keep identifiers as text (NIP/NUP/kode)
                return "\t" . $value;
            };

            fputcsv($handle, [
                'Kode Barang',
                'NUP',
                'NIP Peminjam',
                'Nama Peminjam',
                'Status',
                'Waktu Pengajuan',
                'Waktu Pinjam',
                'Waktu Kembali',
                'Jatuh Tempo',
                'Kondisi Awal',
                'Kondisi Kembali',
                'Catatan Kondisi',
            ]);

            $query->orderBy('waktu_pinjam', 'desc')
                ->orderBy('id', 'desc')
                ->chunk(200, function ($rows) use ($handle, $csvSafe, $csvText) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $csvText($row->kode_barang),
                        $csvText($row->nup),
                        $csvText($row->nip_peminjam),
                        $csvSafe($row->nama_peminjam),
                        $csvSafe($row->status),
                        optional($row->waktu_pengajuan)->format('Y-m-d H:i:s'),
                        optional($row->waktu_pinjam)->format('Y-m-d H:i:s'),
                        optional($row->waktu_kembali)->format('Y-m-d H:i:s'),
                        optional($row->tanggal_jatuh_tempo)->format('Y-m-d'),
                        $csvSafe($row->kondisi_awal),
                        $csvSafe($row->kondisi_kembali),
                        $csvSafe($row->catatan_kondisi),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function approve($id)
    {
        $histori = HistoriPeminjaman::findOrFail($id);

        if ($histori->status !== 'menunggu') {
            return redirect()->back()->withErrors(['status' => 'Pengajuan ini sudah diproses.']);
        }

        $barang = Barang::where('kode_barang', $histori->kode_barang)
            ->where('nup', $histori->nup)
            ->first();

        if (!$barang || $barang->ketersediaan !== 'tersedia') {
            return redirect()->back()->withErrors(['status' => 'Barang tidak tersedia untuk dipinjam.']);
        }

        $now = Carbon::now('Asia/Jakarta');

        DB::transaction(function () use ($histori, $barang, $now) {
            $durasi = $barang->kategori->durasi_pinjam_default ?? 7;

            $histori->update([
                'status' => 'dipinjam',
                'approved_by' => Auth::id(),
                'approved_at' => $now,
                'waktu_pinjam' => $now,
                'tanggal_jatuh_tempo' => $now->copy()->addDays($durasi),
                'kondisi_awal' => $histori->kondisi_awal ?: $barang->kondisi_terakhir,
            ]);

            $barang->update([
                'ketersediaan' => 'dipinjam',
                'peminjam_terakhir' => $histori->nama_peminjam,
                'waktu_pinjam' => $now,
                'waktu_kembali' => null,
            ]);
        });

        $this->logAudit('approve', 'histori_peminjaman', $histori->id, [
            'kode_barang' => $histori->kode_barang,
            'nup' => $histori->nup,
            'nip_peminjam' => $histori->nip_peminjam,
        ]);

        $borrower = User::where('nip', $histori->nip_peminjam)->first();
        if ($borrower) {
            $namaBarang = $histori->kode_barang . ' NUP ' . $histori->nup;
            app(NotifikasiService::class)->send(
                $borrower->id,
                'Peminjaman Disetujui',
                "Peminjaman {$namaBarang} Anda telah disetujui. Silakan ambil barang.",
                'approval',
                'histori_peminjaman',
                $histori->id
            );
        }

        return redirect()->back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        $histori = HistoriPeminjaman::findOrFail($id);

        if ($histori->status !== 'menunggu') {
            return redirect()->back()->withErrors(['status' => 'Pengajuan ini sudah diproses.']);
        }

        $now = Carbon::now('Asia/Jakarta');

        $histori->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'rejected_at' => $now,
            'rejection_reason' => $request->rejection_reason,
        ]);

        $this->logAudit('reject', 'histori_peminjaman', $histori->id, [
            'kode_barang' => $histori->kode_barang,
            'nup' => $histori->nup,
            'nip_peminjam' => $histori->nip_peminjam,
            'reason' => $request->rejection_reason,
        ]);

        $borrower = User::where('nip', $histori->nip_peminjam)->first();
        if ($borrower) {
            $namaBarang = $histori->kode_barang . ' NUP ' . $histori->nup;
            $alasan = $request->rejection_reason ? 'Alasan: ' . $request->rejection_reason : 'Tidak ada alasan yang diberikan.';
            app(NotifikasiService::class)->send(
                $borrower->id,
                'Peminjaman Ditolak',
                "Peminjaman {$namaBarang} Anda ditolak. {$alasan}",
                'approval',
                'histori_peminjaman',
                $histori->id
            );
        }

        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }

    public function approveExtension($id)
    {
        $histori = HistoriPeminjaman::findOrFail($id);

        if ($histori->status !== 'dipinjam' || $histori->perpanjangan_status !== 'menunggu') {
            return redirect()->back()->withErrors(['status' => 'Tidak ada pengajuan perpanjangan yang aktif.']);
        }

        $now = Carbon::now('Asia/Jakarta');
        $hari = $histori->perpanjangan_hari ?: 7;
        $base = $histori->tanggal_jatuh_tempo
            ? Carbon::parse($histori->tanggal_jatuh_tempo)
            : $now;

        $histori->update([
            'tanggal_jatuh_tempo' => $base->copy()->addDays($hari),
            'perpanjangan_status' => 'disetujui',
            'perpanjangan_disetujui_by' => Auth::id(),
            'perpanjangan_disetujui_at' => $now,
            'perpanjangan_ditolak_at' => null,
            'perpanjangan_reject_reason' => null,
        ]);

        $this->logAudit('approve_extend', 'histori_peminjaman', $histori->id, [
            'kode_barang' => $histori->kode_barang,
            'nup' => $histori->nup,
            'nip_peminjam' => $histori->nip_peminjam,
            'hari' => $hari,
        ]);

        return redirect()->back()->with('success', 'Perpanjangan peminjaman disetujui.');
    }

    public function rejectExtension(Request $request, $id)
    {
        $request->validate([
            'perpanjangan_reject_reason' => 'nullable|string|max:255',
        ]);

        $histori = HistoriPeminjaman::findOrFail($id);

        if ($histori->status !== 'dipinjam' || $histori->perpanjangan_status !== 'menunggu') {
            return redirect()->back()->withErrors(['status' => 'Tidak ada pengajuan perpanjangan yang aktif.']);
        }

        $now = Carbon::now('Asia/Jakarta');

        $histori->update([
            'perpanjangan_status' => 'ditolak',
            'perpanjangan_ditolak_at' => $now,
            'perpanjangan_reject_reason' => $request->perpanjangan_reject_reason,
        ]);

        $this->logAudit('reject_extend', 'histori_peminjaman', $histori->id, [
            'kode_barang' => $histori->kode_barang,
            'nup' => $histori->nup,
            'nip_peminjam' => $histori->nip_peminjam,
            'reason' => $request->perpanjangan_reject_reason,
        ]);

        return redirect()->back()->with('success', 'Perpanjangan peminjaman ditolak.');
    }
}
