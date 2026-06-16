<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-800 text-[15px]">Stok Masuk</h2>
            <p class="text-[12px] text-slate-400 mt-0.5"><?= number_format($total) ?> transaksi tercatat</p>
        </div>
        <a href="<?= base_url('stok-masuk/create') ?>" class="btn-primary">
            <i class="fas fa-plus text-xs"></i> Tambah Stok Masuk
        </a>
    </div>

    <!-- Filter Bar -->
    <form method="get" action="" class="flex flex-wrap items-end gap-3 px-6 py-3 bg-slate-50 border-b border-slate-100">
        <div class="relative flex-1 min-w-[180px]">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="<?= esc($search ?? '') ?>"
                   placeholder="No. transaksi / nama supplier..."
                   class="inp pl-8 py-2 text-[13px]">
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <input type="date" name="dari" value="<?= esc($dari ?? '') ?>" class="inp py-2 text-[13px] w-36" title="Dari tanggal">
            <span class="text-slate-400 text-xs">s/d</span>
            <input type="date" name="sampai" value="<?= esc($sampai ?? '') ?>" class="inp py-2 text-[13px] w-36" title="Sampai tanggal">
        </div>
        <button type="submit" class="btn-primary py-2 px-4">
            <i class="fas fa-filter text-xs"></i> Filter
        </button>
        <?php if ($search || $dari || $sampai): ?>
        <a href="<?= base_url('stok-masuk') ?>" class="text-xs text-slate-500 hover:text-slate-700 px-3 py-2 rounded-lg border border-slate-200 hover:bg-white transition">
            <i class="fas fa-times mr-1"></i> Reset
        </a>
        <?php endif; ?>
    </form>

    <div class="overflow-x-auto">
        <table class="tbl w-full">
            <thead>
                <tr>
                    <th class="text-left">No. Transaksi</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-left">Supplier</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stok_masuks)): ?>
                <tr><td colspan="5" class="py-16 text-center">
                    <i class="fas fa-arrow-down-to-bracket text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">
                        <?= ($search || $dari || $sampai) ? 'Tidak ada data yang cocok' : 'Belum ada data stok masuk' ?>
                    </p>
                </td></tr>
                <?php else: ?>
                <?php foreach ($stok_masuks as $s): ?>
                <tr>
                    <td>
                        <span class="font-mono text-[12px] bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg font-semibold">
                            <?= esc($s['no_transaksi']) ?>
                        </span>
                    </td>
                    <td class="text-slate-600"><?= date('d M Y', strtotime($s['tanggal_masuk'])) ?></td>
                    <td>
                        <?php if ($s['nama_supplier']): ?>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center">
                                <i class="fas fa-truck text-emerald-500 text-[10px]"></i>
                            </div>
                            <span class="text-slate-700 text-[13px]"><?= esc($s['nama_supplier']) ?></span>
                        </div>
                        <?php else: ?>
                        <span class="text-slate-400 text-[13px]">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right font-bold text-slate-800">Rp <?= number_format($s['total_harga'], 0, ',', '.') ?></td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="<?= base_url('stok-masuk/show/' . $s['id']) ?>"
                               class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <button onclick="confirmDelete('<?= base_url('stok-masuk/delete/' . $s['id']) ?>','Hapus data ini? Stok akan dikembalikan.')"
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

    <?= $this->include('layout/pagination') ?>
</div>

<?= $this->endSection() ?>
