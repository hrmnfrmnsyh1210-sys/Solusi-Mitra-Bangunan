<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="space-y-4">
    <!-- Header actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div></div>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('retur/penyesuaian') ?>"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-[13px] font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 transition border border-amber-200">
                <i class="fas fa-sliders text-amber-500 text-xs"></i> Penyesuaian Stok
            </a>
            <a href="<?= base_url('retur/create') ?>" class="btn-primary">
                <i class="fas fa-plus text-xs"></i> Tambah Retur
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="font-semibold text-slate-800 text-[15px]">Retur Penjualan</h2>
                <p class="text-[12px] text-slate-400 mt-0.5"><?= count($returs) ?> retur tercatat</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="tbl w-full">
                <thead>
                    <tr>
                        <th class="text-left">No. Retur</th>
                        <th class="text-left">Tanggal</th>
                        <th class="text-left">No. Transaksi Asal</th>
                        <th class="text-left">Alasan</th>
                        <th class="text-center">Item</th>
                        <th class="text-right">Total Nilai</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($returs)): ?>
                    <tr><td colspan="7" class="py-16 text-center">
                        <i class="fas fa-rotate-left text-4xl text-slate-200 block mb-3"></i>
                        <p class="text-slate-400 text-sm">Belum ada data retur</p>
                    </td></tr>
                    <?php else: ?>
                    <?php foreach ($returs as $r): ?>
                    <tr>
                        <td>
                            <span class="font-mono text-[12px] bg-rose-50 text-rose-700 px-2.5 py-1 rounded-lg font-semibold">
                                <?= esc($r['no_retur']) ?>
                            </span>
                        </td>
                        <td class="text-slate-600"><?= date('d M Y', strtotime($r['tanggal_retur'])) ?></td>
                        <td>
                            <?php if ($r['no_transaksi']): ?>
                            <span class="font-mono text-[12px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md">
                                <?= esc($r['no_transaksi']) ?>
                            </span>
                            <?php else: ?>
                            <span class="text-slate-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-slate-700 max-w-[180px] truncate"><?= esc($r['alasan']) ?></td>
                        <td class="text-center">
                            <span class="badge bg-slate-100 text-slate-600"><?= $r['jumlah_item'] ?> item</span>
                        </td>
                        <td class="text-right font-semibold text-rose-600">
                            Rp <?= number_format($r['total_retur'], 0, ',', '.') ?>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="<?= base_url('retur/show/' . $r['id']) ?>"
                                   class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <button onclick="confirmDelete('<?= base_url('retur/delete/' . $r['id']) ?>','Hapus retur ini? Stok barang akan dikurangi kembali.')"
                                   class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
