<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= esc($penjualan['no_transaksi']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            padding: 32px 16px;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Top action bar - hidden on print */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 13px; font-weight: 600; padding: 9px 18px;
            border-radius: 10px; cursor: pointer; text-decoration: none;
            transition: all .15s;
        }
        .btn-print { background: #4f46e5; color: white; border: none; }
        .btn-print:hover { background: #4338ca; }
        .btn-back { background: white; color: #64748b; border: 1.5px solid #e2e8f0; }
        .btn-back:hover { background: #f8fafc; }

        /* Invoice card */
        .invoice {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
        }

        /* Header */
        .inv-header {
            background: linear-gradient(135deg, #1d4ed8, #2563eb, #0ea5e9);
            padding: 32px 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }
        .inv-header .brand-name {
            font-size: 22px; font-weight: 800; letter-spacing: -.3px;
        }
        .inv-header .brand-sub {
            font-size: 12px; opacity: .75; margin-top: 4px;
        }
        .inv-header .brand-address {
            font-size: 11px; opacity: .65; margin-top: 8px; line-height: 1.7;
        }
        .inv-title {
            text-align: right;
        }
        .inv-title .label {
            font-size: 32px; font-weight: 800; letter-spacing: 2px;
            opacity: .9; text-transform: uppercase;
        }
        .inv-title .no {
            font-size: 13px; font-weight: 600; opacity: .8;
            margin-top: 4px; font-family: 'Courier New', monospace;
        }
        .status-badge {
            display: inline-block;
            background: rgba(255,255,255,.2);
            border: 1.5px solid rgba(255,255,255,.35);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 99px;
            letter-spacing: .5px;
            margin-top: 8px;
        }

        /* Meta info */
        .inv-meta {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
            border-bottom: 1.5px solid #f1f5f9;
        }
        .inv-meta-cell {
            padding: 18px 24px;
            border-right: 1.5px solid #f1f5f9;
        }
        .inv-meta-cell:last-child { border-right: none; }
        .inv-meta-cell .meta-label {
            font-size: 10px; font-weight: 600; color: #94a3b8;
            text-transform: uppercase; letter-spacing: .06em; margin-bottom: 5px;
        }
        .inv-meta-cell .meta-value {
            font-size: 13.5px; font-weight: 600; color: #1e293b;
        }

        /* Items table */
        .inv-items { padding: 0; }
        .inv-items table { width: 100%; border-collapse: collapse; }
        .inv-items thead th {
            background: #f8fafc;
            padding: 12px 24px;
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .07em;
            border-bottom: 1.5px solid #f1f5f9;
        }
        .inv-items thead th:first-child { text-align: left; }
        .inv-items thead th:nth-child(2) { text-align: center; }
        .inv-items thead th:last-child { text-align: right; }
        .inv-items tbody td {
            padding: 14px 24px;
            font-size: 13.5px;
            color: #374151;
            border-bottom: 1px solid #f8fafc;
        }
        .inv-items tbody tr:last-child td { border-bottom: none; }
        .item-name { font-weight: 600; color: #1e293b; }
        .item-code { font-size: 11px; color: #94a3b8; font-family: 'Courier New', monospace; margin-top: 2px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Summary */
        .inv-summary {
            display: flex;
            justify-content: flex-end;
            padding: 20px 24px;
            background: #f8fafc;
            border-top: 1.5px solid #f1f5f9;
        }
        .summary-box { width: 280px; }
        .summary-row {
            display: flex; justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }
        .summary-row .s-label { color: #64748b; }
        .summary-row .s-value { font-weight: 600; color: #1e293b; }
        .summary-row.total {
            border-top: 2px solid #1d4ed8;
            margin-top: 8px; padding-top: 12px;
        }
        .summary-row.total .s-label { color: #1d4ed8; font-weight: 700; font-size: 14px; }
        .summary-row.total .s-value { color: #1d4ed8; font-weight: 800; font-size: 18px; }
        .summary-row.change .s-value { color: #059669; }

        /* Footer */
        .inv-footer {
            padding: 24px 40px;
            border-top: 1.5px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
        }
        .footer-note {
            font-size: 11px; color: #94a3b8; line-height: 1.7;
        }
        .footer-note strong { color: #64748b; }
        .signature-area {
            text-align: center;
            min-width: 140px;
        }
        .signature-line {
            border-bottom: 1.5px solid #94a3b8;
            width: 140px; height: 48px; margin-bottom: 6px;
        }
        .signature-label {
            font-size: 11px; color: #94a3b8;
        }
        .signature-name {
            font-size: 12px; color: #475569; font-weight: 600; margin-top: 2px;
        }

        /* Print styles */
        @media print {
            body { background: white; padding: 0; }
            .action-bar { display: none !important; }
            .invoice { box-shadow: none; border-radius: 0; }
            @page { size: A4; margin: 10mm; }
        }

        @media screen and (max-width: 600px) {
            .inv-header { flex-direction: column; padding: 24px 20px; }
            .inv-title { text-align: left; }
            .inv-meta { grid-template-columns: repeat(2, 1fr); }
            .inv-footer { flex-direction: column; }
        }
    </style>
</head>
<body>

<div class="invoice-wrapper">

    <!-- Action Bar -->
    <div class="action-bar">
        <a href="<?= base_url('penjualan/show/' . $penjualan['id']) ?>" class="btn btn-back">
            ← Kembali
        </a>
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak Invoice
        </button>
    </div>

    <!-- Invoice Card -->
    <div class="invoice">

        <!-- Header -->
        <div class="inv-header">
            <div>
                <div class="brand-name">🏗️ TOKO BANGUNAN</div>
                <div class="brand-sub">Manajemen Inventori</div>
                <div class="brand-address">
                    Jl. Contoh No. 123, Kota Anda<br>
                    Telp: (021) 000-0000
                </div>
            </div>
            <div class="inv-title">
                <div class="label">Invoice</div>
                <div class="no"><?= esc($penjualan['no_transaksi']) ?></div>
                <div><span class="status-badge">✓ LUNAS</span></div>
            </div>
        </div>

        <!-- Meta info -->
        <div class="inv-meta">
            <div class="inv-meta-cell">
                <div class="meta-label">Tanggal</div>
                <div class="meta-value"><?= date('d M Y', strtotime($penjualan['tanggal_jual'])) ?></div>
            </div>
            <div class="inv-meta-cell">
                <div class="meta-label">Kepada</div>
                <div class="meta-value"><?= esc($penjualan['nama_pembeli'] ?? 'Umum') ?></div>
            </div>
            <div class="inv-meta-cell">
                <div class="meta-label">Kasir</div>
                <div class="meta-value"><?= esc(session()->get('nama') ?: 'Admin') ?></div>
            </div>
            <div class="inv-meta-cell">
                <div class="meta-label">Jumlah Item</div>
                <div class="meta-value"><?= count($details) ?> jenis barang</div>
            </div>
        </div>

        <!-- Items -->
        <div class="inv-items">
            <table>
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $i => $d): ?>
                    <tr>
                        <td>
                            <div class="item-name"><?= esc($d['nama_barang']) ?></div>
                            <div class="item-code"><?= esc($d['kode_barang']) ?></div>
                        </td>
                        <td class="text-center"><?= number_format($d['jumlah']) ?> <?= esc($d['satuan']) ?></td>
                        <td class="text-right">Rp <?= number_format($d['harga_jual'], 0, ',', '.') ?></td>
                        <td class="text-right" style="font-weight:600">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="inv-summary">
            <div class="summary-box">
                <div class="summary-row">
                    <span class="s-label">Subtotal</span>
                    <span class="s-value">Rp <?= number_format($penjualan['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="summary-row">
                    <span class="s-label">Uang Diterima</span>
                    <span class="s-value">Rp <?= number_format($penjualan['bayar'], 0, ',', '.') ?></span>
                </div>
                <div class="summary-row change">
                    <span class="s-label">Kembalian</span>
                    <span class="s-value">Rp <?= number_format($penjualan['kembalian'], 0, ',', '.') ?></span>
                </div>
                <div class="summary-row total">
                    <span class="s-label">TOTAL</span>
                    <span class="s-value">Rp <?= number_format($penjualan['total_harga'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="inv-footer">
            <div class="footer-note">
                <strong>Catatan:</strong><br>
                • Barang yang sudah dibeli tidak dapat dikembalikan.<br>
                • Simpan invoice ini sebagai bukti pembayaran.<br>
                <?php if ($penjualan['keterangan']): ?>
                • <?= esc($penjualan['keterangan']) ?>
                <?php endif; ?>
            </div>
            <div class="signature-area">
                <div class="signature-line"></div>
                <div class="signature-label">Kasir / Penjual</div>
                <div class="signature-name"><?= esc(session()->get('nama') ?: 'Admin') ?></div>
            </div>
        </div>

    </div><!-- /invoice -->

    <p style="text-align:center; font-size:11px; color:#94a3b8; margin-top:16px;">
        Dicetak pada <?= date('d M Y H:i') ?> &bull; Sistem Manajemen Inventori Toko Bangunan
    </p>
</div>

</body>
</html>
