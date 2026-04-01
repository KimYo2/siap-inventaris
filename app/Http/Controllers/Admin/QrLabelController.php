<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\LogsAudit;
use App\Models\Barang;
use Illuminate\Http\Request;

class QrLabelController extends Controller
{
    use LogsAudit;

    public function show($id)
    {
        $barang = Barang::findOrFail($id);

        return view('admin.barang.qr-label', ['items' => collect([$barang])]);
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1|max:50',
            'ids.*' => 'integer|exists:barang,id',
        ]);

        $items = Barang::whereIn('id', $request->ids)->get();

        $this->logAudit('print_qr_bulk', 'barang', null, [
            'count' => $items->count(),
            'ids' => $items->pluck('id')->toArray(),
        ]);

        return view('admin.barang.qr-label', compact('items'));
    }
}
