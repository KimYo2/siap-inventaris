<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label QR — Sistem BMN</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        /* ---------- Screen styles ---------- */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .toolbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .toolbar h1 {
            font-size: 16px;
            font-weight: 700;
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: background .15s;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* ---------- Label grid ---------- */
        .label-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding: 24px;
            justify-content: center;
        }

        .label-card {
            width: 85mm;
            height: 54mm;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #fff;
            display: flex;
            align-items: center;
            padding: 8px 12px;
            gap: 12px;
            overflow: hidden;
            page-break-inside: avoid;
        }

        .label-qr {
            flex-shrink: 0;
            width: 110px;
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .label-qr canvas,
        .label-qr img {
            width: 110px !important;
            height: 110px !important;
        }

        .label-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 2px;
        }

        .label-header {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1e40af;
            border-bottom: 1px solid #bfdbfe;
            padding-bottom: 3px;
            margin-bottom: 3px;
        }

        .label-field {
            font-size: 9px;
            color: #64748b;
            line-height: 1.2;
        }

        .label-field strong {
            color: #1e293b;
        }

        .label-bmn {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.3px;
            margin: 2px 0;
        }

        .label-brand {
            font-size: 11px;
            font-weight: 600;
            color: #334155;
        }

        /* ---------- Print styles ---------- */
        @media print {

            html,
            body {
                background: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .toolbar {
                display: none !important;
            }

            .label-grid {
                padding: 0;
                gap: 4mm;
                justify-content: flex-start;
            }

            .label-card {
                border: 0.5px solid #94a3b8;
                border-radius: 2px;
                box-shadow: none;
                margin: 0;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <h1>Label QR — {{ $items->count() }} barang</h1>
        <div class="toolbar-actions">
            <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">← Kembali</a>
            <button onclick="window.print()" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak
            </button>
        </div>
    </div>

    <div class="label-grid">
        @foreach ($items as $item)
            @php
                $nomorBmn = $item->kode_barang . '-' . $item->nup;
                $qrValue = url('/barang/' . $nomorBmn);
            @endphp
            <div class="label-card">
                <div class="label-qr" id="qr-{{ $item->id }}"></div>
                <div class="label-info">
                    <div class="label-header">Barang Milik Negara — BPS Jepara</div>
                    <div class="label-bmn">{{ $nomorBmn }}</div>
                    <div class="label-brand">{{ $item->brand }} {{ $item->tipe }}</div>
                    <div class="label-field"><strong>Kode:</strong> {{ $item->kode_barang }}</div>
                    <div class="label-field"><strong>NUP:</strong> {{ $item->nup }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($items as $item)
                new QRCode(document.getElementById('qr-{{ $item->id }}'), {
                    text: @json(url('/barang/' . $item->kode_barang . '-' . $item->nup)),
                    width: 110,
                    height: 110,
                    correctLevel: QRCode.CorrectLevel.M,
                });
            @endforeach
        });
    </script>
</body>

</html>
