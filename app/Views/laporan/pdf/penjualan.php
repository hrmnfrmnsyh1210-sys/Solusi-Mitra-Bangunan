<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; padding: 24px; }
  .header { text-align: center; margin-bottom: 18px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
  .header h1 { font-size: 16px; font-weight: bold; color: #1e1b4b; }
  .header p  { font-size: 10px; color: #64748b; margin-top: 3px; }

  /* Ringkasan */
  .summary { display: table; width: 100%; margin-bottom: 14px; border-collapse: separate; border-spacing: 6px 0; }
  .summary-box { display: table-cell; width: 33%; padding: 10px 12px; border-radius: 6px; }
  .summary-box.blue  { background: #eef2ff; border: 1px solid #c7d2fe; }
  .summary-box.green { background: #d1fae5; border: 1px solid #6ee7b7; }
  .summary-box.indigo { background: #ede9fe; border: 1px solid #c4b5fd; }
  .summary-box p { font-size: 8.5px; color: #64748b; margin-bottom: 2px; }
  .summary-box h3 { font-size: 12px; font-weight: bold; }
  .summary-box.blue h3  { color: #3730a3; }
  .summary-box.green h3 { color: #065f46; }
  .summary-box.indigo h3 { color: #5b21b6; }

  table { width: 100%; border-collapse: collapse; margin-top: 4px; }
  thead th {
    background: #4f46e5; color: #fff; font-size: 8.5px; font-weight: bold;
    padding: 7px 8px; text-transform: uppercase; letter-spacing: .05em;
    text-align: left;
  }
  thead th.r { text-align: right; }
  thead th.profit { background: #059669; }
  tbody td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
  tbody td.r { text-align: right; }
  tbody td.profit { text-align: right; color: #065f46; font-weight: bold; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  tfoot td { padding: 7px 8px; font-weight: bold; font-size: 10px; background: #eef2ff; border-top: 2px solid #c7d2fe; }
  tfoot td.r { text-align: right; color: #4f46e5; }
  tfoot td.profit { text-align: right; color: #059669; background: #d1fae5; border-top: 2px solid #6ee7b7; }
  .meta { font-size: 8.5px; color: #64748b; margin-top: 14px; }
</style>
</head>
<body>

<div class="header">
  <h1>LAPORAN PENJUALAN</h1>
  <p>Periode: <?= date('d/m/Y', strtotime($dari)) ?> s/d <?= date('d/m/Y', strtotime($sampai)) ?></p>
</div>

<?php
    $margin_persen = ($total > 0) ? round(($total_keuntungan / $total) * 100, 1) : 0;
?>
<div class="summary">
  <div class="summary-box blue">
    <p>Total Penjualan</p>
    <h3>Rp <?= number_format($total, 0, ',', '.') ?></h3>
  </div>
  <div class="summary-box green">
    <p>Total Keuntungan</p>
    <h3>Rp <?= number_format($total_keuntungan, 0, ',', '.') ?></h3>
  </div>
  <div class="summary-box indigo">
    <p>Rata-rata Margin</p>
    <h3><?= $margin_persen ?>%</h3>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th style="width:24px">No</th>
      <th>No Transaksi</th>
      <th>Tanggal</th>
      <th>Nama Pembeli</th>
      <th class="r">Total Harga</th>
      <th class="r">Bayar</th>
      <th class="r">Kembalian</th>
      <th class="r profit">Keuntungan</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($rows)): ?>
    <tr><td colspan="8" style="text-align:center;padding:20px;color:#94a3b8">Tidak ada data</td></tr>
    <?php else: ?>
    <?php foreach ($rows as $no => $r): ?>
    <tr>
      <td><?= $no + 1 ?></td>
      <td><?= esc($r['no_transaksi']) ?></td>
      <td><?= date('d/m/Y', strtotime($r['tanggal_jual'])) ?></td>
      <td><?= esc($r['nama_pembeli']) ?></td>
      <td class="r">Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
      <td class="r">Rp <?= number_format($r['bayar'], 0, ',', '.') ?></td>
      <td class="r">Rp <?= number_format($r['kembalian'], 0, ',', '.') ?></td>
      <td class="profit">Rp <?= number_format($r['total_keuntungan'], 0, ',', '.') ?></td>
    </tr>
    <?php endforeach ?>
    <?php endif ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="4">TOTAL (<?= count($rows) ?> transaksi)</td>
      <td class="r">Rp <?= number_format($total, 0, ',', '.') ?></td>
      <td colspan="2"></td>
      <td class="profit">Rp <?= number_format($total_keuntungan, 0, ',', '.') ?></td>
    </tr>
  </tfoot>
</table>

<p class="meta">Dicetak pada: <?= date('d/m/Y H:i') ?> WIB</p>
</body>
</html>
