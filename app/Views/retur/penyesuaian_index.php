<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="space-y-4">
    <div class="flex items-center justify-between">
        <a href="<?= base_url('retur') ?>" class="text-xs text-slate-500 hover:text-slate-700 flex items-center gap-1">
            <i class="fas fa-arrow-left text-[10px]"></i> Retur Penjualan
        </a>
        <a href="<?= base_url('retur/penyesuaian/create') ?>" class="btn-primary" style="background:#d97706">
            <i class="fas fa-sliders text-xs"></i> Penyesuaian Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-sliders text-amber-600 text-sm"></i>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800 text-[15px]">Penyesuaian Stok</h2>
                <p class="text-[12px] text-slate-400"><?= count($penyesuaians) ?> catatan penyesuaian</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="tbl w-full">
                <thead>
                    <tr>
                        <th class="text-left">No. Penyesuaian</th>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">Barang</th>
                        <th class="text-center">Stok Sebelum</th>
                        <th class="text-center">Stok Sesudah</th>
                        <th class="text-center">Selisih</th>
                        <th class="text-left">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($penyesuaians)): ?>
                    <tr><td colspan="7" class="py-16 text-center">
                        <i class="fas fa-sliders text-4xl text-slate-200 block mb-3"></i>
                        <p class="text-slate-400 text-sm">Belum ada penyesuaian stok</p>
                    </td></tr>
                    <?php else: ?>
                    <?php foreach ($penyesuaians as $p): ?>
                    <tr>
                        <td><span class="font-mono text-[12px] bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg font-semibold"><?= esc($p['no_penyesuaian']) ?></span></td>
                        <td class="text-slate-600"><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                        <td>
                            <p class="font-semibold text-slate-800 text-[13px]"><?= esc($p['nama_barang']) ?></p>
                            <p class="text-[11px] text-slate-400 font-mono"><?= esc($p['kode_barang']) ?></p>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-slate-100 text-slate-600"><?= number_format($p['stok_sebelum']) ?> <?= esc($p['satuan']) ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-indigo-50 text-indigo-700"><?= number_format($p['stok_sesudah']) ?> <?= esc($p['satuan']) ?></span>
                        </td>
                        <td class="text-center">
                            <?php $s = (int)$p['selisih']; ?>
                            <span class="badge <?= $s > 0 ? 'bg-emerald-100 text-emerald-700' : ($s < 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-100 text-slate-500') ?>">
                                <?= $s > 0 ? '+' : '' ?><?= number_format($s) ?>
                            </span>
                        </td>
                        <td class="text-slate-600 text-[13px]"><?= esc($p['alasan']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
