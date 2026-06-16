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
  thead th.c { text-align: center; }
  tbody td { padding: 6px 10px; border-bottom: 1px solid #e2e8f0; font-size: 9.5px; }
  tbody td.r { text-align: right; }
  tbody td.c { text-align: center; }
  tbody tr:nth-child(even) { background: #f8fafc; }
  .badge-aman   { background:#d1fae5; color:#065f46; padding:2px 7px; border-radius:99px; font-size:8.5px; font-weight:bold; }
  .badge-hampir { background:#fef9c3; color:#854d0e; padding:2px 7px; border-radius:99px; font-size:8.5px; font-weight:bold; }
  .badge-habis  { background:#fee2e2; color:#991b1b; padding:2px 7px; border-radius:99px; font-size:8.5px; font-weight:bold; }
  .meta { font-size: 9px; color: #64748b; margin-top: 14px; }
</style>
</head>
<body>

<div class="header">
  <h1>LAPORAN STOK BARANG</h1>
  <p>Kategori: <?= esc($kategoriNama) ?> &nbsp;|&nbsp; Per Tanggal: <?= date('d/m/Y') ?></p>
</div>

<table>
  <thead>
    <tr>
      <th style="width:28px">No</th>
      <th>Kode</th>
      <th>Nama Barang</th>
      <th>Kategori</th>
      <th>Satuan</th>
      <th class="r">Stok</th>
      <th class="r">Min. Stok</th>
      <th class="c">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($rows)): ?>
    <tr><td colspan="8" style="text-align:center;padding:20px;color:#94a3b8">Tidak ada data</td></tr>
    <?php else: ?>
    <?php foreach ($rows as $no => $r):
      $stok    = (int) $r['stok'];
      $minStok = (int) $r['stok_minimum'];
      if ($stok <= 0)           { $cls = 'badge-habis';  $lbl = 'Habis'; }
      elseif ($stok <= $minStok){ $cls = 'badge-hampir'; $lbl = 'Hampir Habis'; }
      else                      { $cls = 'badge-aman';   $lbl = 'Aman'; }
    ?>
    <tr>
      <td><?= $no + 1 ?></td>
      <td><?= esc($r['kode_barang']) ?></td>
      <td><?= esc($r['nama_barang']) ?></td>
      <td><?= esc($r['nama_kategori'] ?? '-') ?></td>
      <td><?= esc($r['satuan']) ?></td>
      <td class="r" style="<?= $stok <= $minStok ? 'color:#dc2626;font-weight:bold' : '' ?>"><?= number_format($stok, 0, ',', '.') ?></td>
      <td class="r"><?= number_format($minStok, 0, ',', '.') ?></td>
      <td class="c"><span class="<?= $cls ?>"><?= $lbl ?></span></td>
    </tr>
    <?php endforeach ?>
    <?php endif ?>
  </tbody>
</table>

<p class="meta">Dicetak pada: <?= date('d/m/Y H:i') ?> WIB &nbsp;|&nbsp; Total: <?= count($rows) ?> barang</p>
</body>
</html>
