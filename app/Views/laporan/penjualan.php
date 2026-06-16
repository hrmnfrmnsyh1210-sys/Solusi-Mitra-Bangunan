<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-5">
    <form method="get" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
            <input type="date" name="dari" value="<?= esc($dari) ?>" class="inp w-44">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="sampai" value="<?= esc($sampai) ?>" class="inp w-44">
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-magnifying-glass"></i> Tampilkan
        </button>
        <div class="ml-auto flex gap-2">
            <a href="<?= base_url('laporan/penjualan/pdf') . '?' . http_build_query(['dari' => $dari, 'sampai' => $sampai]) ?>"
               target="_blank"
               class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="<?= base_url('laporan/penjualan/excel') . '?' . http_build_query(['dari' => $dari, 'sampai' => $sampai]) ?>"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </form>
</div>

<!-- Ringkasan Periode -->
<?php if (!empty($rows)):
    $margin_persen = ($total > 0) ? round(($total_keuntungan / $total) * 100, 1) : 0;
?>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-xs text-slate-400 font-medium mb-1">Total Penjualan</p>
        <p class="text-xl font-bold text-slate-800">Rp <?= number_format($total, 0, ',', '.') ?></p>
        <p class="text-xs text-slate-400 mt-1"><?= count($rows) ?> transaksi</p>
    </div>
    <div class="bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm p-5">
        <p class="text-xs text-emerald-600 font-medium mb-1">Total Keuntungan</p>
        <p class="text-xl font-bold text-emerald-700">Rp <?= number_format($total_keuntungan, 0, ',', '.') ?></p>
        <p class="text-xs text-emerald-500 mt-1">Margin bersih periode ini</p>
    </div>
    <div class="bg-indigo-50 rounded-2xl border border-indigo-100 shadow-sm p-5">
        <p class="text-xs text-indigo-600 font-medium mb-1">Rata-rata Margin</p>
        <p class="text-xl font-bold text-indigo-700"><?= $margin_persen ?>%</p>
        <p class="text-xs text-indigo-400 mt-1">Keuntungan / Total Penjualan</p>
    </div>
</div>
<?php endif; ?>

<!-- Tabel -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800 text-sm">Laporan Penjualan</h2>
            <p class="text-xs text-slate-400 mt-0.5"><?= date('d/m/Y', strtotime($dari)) ?> s/d <?= date('d/m/Y', strtotime($sampai)) ?></p>
        </div>
        <span class="badge bg-indigo-100 text-indigo-700"><?= count($rows) ?> transaksi</span>
    </div>

    <div class="overflow-x-auto">
        <table class="tbl w-full">
            <thead>
                <tr>
                    <th class="text-left">No</th>
                    <th class="text-left">No Transaksi</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">Nama Pembeli</th>
                    <th class="text-right">Total Harga</th>
                    <th class="text-right">Bayar</th>
                    <th class="text-right">Kembalian</th>
                    <th class="text-right" style="color:#059669">Keuntungan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="8" class="text-center text-slate-400 py-10">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i>Tidak ada data penjualan pada periode ini.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($rows as $no => $r): ?>
                <tr>
                    <td><?= $no + 1 ?></td>
                    <td class="font-mono text-xs"><?= esc($r['no_transaksi']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['tanggal_jual'])) ?></td>
                    <td><?= esc($r['nama_pembeli']) ?></td>
                    <td class="text-right font-semibold">Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($r['bayar'], 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($r['kembalian'], 0, ',', '.') ?></td>
                    <td class="text-right font-semibold text-emerald-600">Rp <?= number_format($r['total_keuntungan'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
            <?php if (!empty($rows)): ?>
            <tfoot>
                <tr class="bg-indigo-50">
                    <td colspan="4" class="px-[18px] py-3 text-sm font-bold text-slate-700">TOTAL</td>
                    <td class="px-[18px] py-3 text-right font-bold text-indigo-700">Rp <?= number_format($total, 0, ',', '.') ?></td>
                    <td colspan="2"></td>
                    <td class="px-[18px] py-3 text-right font-bold text-emerald-700">Rp <?= number_format($total_keuntungan, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
            <?php endif ?>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
