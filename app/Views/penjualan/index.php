<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <!-- Header + Search -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-800 text-[15px]">Transaksi Penjualan</h2>
            <p class="text-[12px] text-slate-400 mt-0.5"><?= number_format($total) ?> transaksi tercatat</p>
        </div>
        <a href="<?= base_url('penjualan/create') ?>" class="btn-primary flex-shrink-0">
            <i class="fas fa-plus text-xs"></i> Transaksi Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <form method="get" action="" class="flex flex-wrap items-end gap-3 px-6 py-3 bg-slate-50 border-b border-slate-100">
        <div class="relative flex-1 min-w-[180px]">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="<?= esc($search ?? '') ?>"
                   placeholder="No. transaksi / nama pembeli..."
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
        <a href="<?= base_url('penjualan') ?>" class="text-xs text-slate-500 hover:text-slate-700 px-3 py-2 rounded-lg border border-slate-200 hover:bg-white transition">
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
                    <th class="text-left">Pembeli</th>
                    <th class="text-center">Item</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Keuntungan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($penjualans)): ?>
                <tr><td colspan="7" class="py-16 text-center">
                    <i class="fas fa-cash-register text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">
                        <?= ($search || $dari || $sampai) ? 'Tidak ada transaksi yang cocok' : 'Belum ada transaksi penjualan' ?>
                    </p>
                </td></tr>
                <?php else: ?>
                <?php foreach ($penjualans as $p): ?>
                <tr>
                    <td>
                        <span class="font-mono text-[12px] bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg font-semibold">
                            <?= esc($p['no_transaksi']) ?>
                        </span>
                    </td>
                    <td class="text-slate-600"><?= date('d M Y', strtotime($p['tanggal_jual'])) ?></td>
                    <td class="font-medium text-slate-700"><?= esc($p['nama_pembeli'] ?? 'Umum') ?></td>
                    <td class="text-center">
                        <span class="badge bg-slate-100 text-slate-600"><?= $p['jumlah_item'] ?> item</span>
                    </td>
                    <td class="text-right font-bold text-slate-800">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                    <td class="text-right">
                        <span class="font-semibold text-emerald-600">Rp <?= number_format($p['total_keuntungan'], 0, ',', '.') ?></span>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="<?= base_url('penjualan/show/' . $p['id']) ?>"
                               class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition" title="Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="<?= base_url('penjualan/invoice/' . $p['id']) ?>" target="_blank"
                               class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition" title="Invoice">
                                <i class="fas fa-file-invoice text-xs"></i>
                            </a>
                            <button onclick="confirmDelete('<?= base_url('penjualan/delete/' . $p['id']) ?>','Hapus transaksi ini? Stok barang akan dikembalikan.')"
                               class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition" title="Hapus">
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
