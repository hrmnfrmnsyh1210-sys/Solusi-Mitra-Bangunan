<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-3xl space-y-5">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Detail Stok Masuk</h2>
            <a href="<?= base_url('stok-masuk') ?>"
               class="text-xs text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
               <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
        <div class="px-6 py-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <p class="text-xs text-slate-400 mb-1">No. Transaksi</p>
                <span class="font-mono text-sm bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg"><?= esc($stok_masuk['no_transaksi']) ?></span>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Tanggal</p>
                <p class="text-sm font-medium text-slate-700"><?= date('d M Y', strtotime($stok_masuk['tanggal_masuk'])) ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Supplier</p>
                <p class="text-sm font-medium text-slate-700"><?= esc($stok_masuk['nama_supplier'] ?? '-') ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Total</p>
                <p class="text-sm font-bold text-slate-800">Rp <?= number_format($stok_masuk['total_harga'], 0, ',', '.') ?></p>
            </div>
        </div>
        <?php if ($stok_masuk['keterangan']): ?>
        <div class="px-6 pb-4">
            <p class="text-xs text-slate-400 mb-1">Keterangan</p>
            <p class="text-sm text-slate-600"><?= esc($stok_masuk['keterangan']) ?></p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Detail Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-700 text-sm">Barang yang Masuk</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Kode</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Nama Barang</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Harga Beli</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($details as $d): ?>
                    <tr>
                        <td class="px-6 py-3.5">
                            <span class="font-mono text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded"><?= esc($d['kode_barang']) ?></span>
                        </td>
                        <td class="px-6 py-3.5 font-medium text-slate-800"><?= esc($d['nama_barang']) ?></td>
                        <td class="px-6 py-3.5 text-center text-slate-700"><?= number_format($d['jumlah']) ?> <?= esc($d['satuan']) ?></td>
                        <td class="px-6 py-3.5 text-right text-slate-700">Rp <?= number_format($d['harga_beli'], 0, ',', '.') ?></td>
                        <td class="px-6 py-3.5 text-right font-semibold text-slate-800">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 font-bold">
                        <td colspan="4" class="px-6 py-3 text-right text-slate-600">Total:</td>
                        <td class="px-6 py-3 text-right text-slate-800">Rp <?= number_format($stok_masuk['total_harga'], 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
