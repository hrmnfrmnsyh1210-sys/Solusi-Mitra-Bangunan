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
  table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  thead th {
    background: #4f46e5; color: #fff; font-size: 9px; font-weight: bold;
    padding: 7px 10px; text-transform: uppercase; letter-spacing: .05em; text-align: left;
  }
  thead th.r { text-align: right; }
  tbody td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; font-size: 9.5px; }
  tbody td.r { text-align: right; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  tfoot td { padding: 7px 10px; font-weight: bold; font-size: 10px; background: #eef2ff; border-top: 2px solid #c7d2fe; }
  tfoot td.r { text-align: right; color: #4f46e5; }
  .meta { font-size: 9px; color: #64748b; margin-top: 14px; }
</style>
</head>
<body>

<div class="header">
  <h1>LAPORAN BARANG MASUK</h1>
  <p>Periode: <?= date('d/m/Y', strtotime($dari)) ?> s/d <?= date('d/m/Y', strtotime($sampai)) ?></p>
</div>

<table>
  <thead>
    <tr>
      <th style="width:30px">No</th>
      <th>No Transaksi</th>
      <th>Tanggal</th>
      <th>Supplier</th>
      <th class="r">Total Harga</th>
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($rows)): ?>
    <tr><td colspan="6" style="text-align:center;padding:20px;color:#94a3b8">Tidak ada data</td></tr>
    <?php else: ?>
    <?php foreach ($rows as $no => $r): ?>
    <tr>
      <td><?= $no + 1 ?></td>
      <td><?= esc($r['no_transaksi']) ?></td>
      <td><?= date('d/m/Y', strtotime($r['tanggal_masuk'])) ?></td>
      <td><?= esc($r['nama_supplier'] ?? '-') ?></td>
      <td class="r">Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
      <td><?= esc($r['keterangan'] ?? '-') ?></td>
    </tr>
    <?php endforeach ?>
    <?php endif ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="4">TOTAL (<?= count($rows) ?> transaksi)</td>
      <td class="r">Rp <?= number_format($total, 0, ',', '.') ?></td>
      <td></td>
    </tr>
  </tfoot>
</table>

<p class="meta">Dicetak pada: <?= date('d/m/Y H:i') ?> WIB</p>
</body>
</html>
