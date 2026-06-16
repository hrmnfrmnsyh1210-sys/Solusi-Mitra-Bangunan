<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-5">
    <form method="get" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori</label>
            <select name="id_kategori" class="inp w-52">
                <option value="">Semua Kategori</option>
                <?php foreach ($kategoris as $k): ?>
                <option value="<?= $k['id'] ?>" <?= $id_kategori == $k['id'] ? 'selected' : '' ?>>
                    <?= esc($k['nama_kategori']) ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <button type="submit" class="btn-primary">
            <i class="fas fa-magnifying-glass"></i> Tampilkan
        </button>
        <div class="ml-auto flex gap-2">
            <a href="<?= base_url('laporan/stok/pdf') . ($id_kategori ? '?id_kategori=' . $id_kategori : '') ?>"
               target="_blank"
               class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="<?= base_url('laporan/stok/excel') . ($id_kategori ? '?id_kategori=' . $id_kategori : '') ?>"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </form>
</div>

<!-- Tabel -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800 text-sm">Laporan Stok Barang</h2>
            <p class="text-xs text-slate-400 mt-0.5">Per tanggal <?= date('d/m/Y') ?></p>
        </div>
        <span class="badge bg-indigo-100 text-indigo-700"><?= count($rows) ?> barang</span>
    </div>

    <div class="overflow-x-auto">
        <table class="tbl w-full">
            <thead>
                <tr>
                    <th class="text-left">No</th>
                    <th class="text-left">Kode</th>
                    <th class="text-left">Nama Barang</th>
                    <th class="text-left">Kategori</th>
                    <th class="text-left">Satuan</th>
                    <th class="text-right">Stok</th>
                    <th class="text-right">Stok Min.</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="8" class="text-center text-slate-400 py-10">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i>Tidak ada data barang.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($rows as $no => $r):
                    $stok    = (int) $r['stok'];
                    $minStok = (int) $r['stok_minimum'];
                    if ($stok <= 0) {
                        $badgeClass = 'bg-rose-100 text-rose-700';
                        $label = 'Habis';
                    } elseif ($stok <= $minStok) {
                        $badgeClass = 'bg-amber-100 text-amber-700';
                        $label = 'Hampir Habis';
                    } else {
                        $badgeClass = 'bg-emerald-100 text-emerald-700';
                        $label = 'Aman';
                    }
                ?>
                <tr>
                    <td><?= $no + 1 ?></td>
                    <td class="font-mono text-xs"><?= esc($r['kode_barang']) ?></td>
                    <td class="font-medium"><?= esc($r['nama_barang']) ?></td>
                    <td><?= esc($r['nama_kategori'] ?? '-') ?></td>
                    <td><?= esc($r['satuan']) ?></td>
                    <td class="text-right font-semibold <?= $stok <= $minStok ? 'text-rose-600' : 'text-slate-800' ?>">
                        <?= number_format($stok, 0, ',', '.') ?>
                    </td>
                    <td class="text-right text-slate-500"><?= number_format($minStok, 0, ',', '.') ?></td>
                    <td class="text-center"><span class="badge <?= $badgeClass ?>"><?= $label ?></span></td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
