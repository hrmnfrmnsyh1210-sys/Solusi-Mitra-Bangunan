<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Detail Barang</h2>
            <div class="flex gap-2">
                <a href="<?= base_url('barang/edit/' . $barang['id']) ?>"
                   class="text-xs bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition">
                    <i class="fas fa-pen mr-1"></i> Edit
                </a>
                <a href="<?= base_url('barang') ?>"
                   class="text-xs text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
                    Kembali
                </a>
            </div>
        </div>

        <div class="px-6 py-5 space-y-4">
            <div class="flex items-center gap-4 pb-4 border-b border-slate-100">
                <?php if (!empty($barang['gambar'])): ?>
                <a href="<?= base_url('uploads/barang/'.$barang['gambar']) ?>" target="_blank" class="block w-20 h-20 rounded-2xl overflow-hidden border border-slate-200 flex-shrink-0">
                    <img src="<?= base_url('uploads/barang/'.$barang['gambar']) ?>"
                         alt="<?= esc($barang['nama_barang']) ?>"
                         class="w-full h-full object-cover hover:scale-105 transition">
                </a>
                <?php else: ?>
                <div class="w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cube text-blue-500 text-2xl"></i>
                </div>
                <?php endif; ?>
                <div>
                    <p class="font-bold text-lg text-slate-800"><?= esc($barang['nama_barang']) ?></p>
                    <span class="font-mono text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded"><?= esc($barang['kode_barang']) ?></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-400 mb-1">Kategori</p>
                    <span class="bg-blue-50 text-blue-700 text-sm px-3 py-1 rounded-full"><?= esc($barang['nama_kategori'] ?? 'Tanpa Kategori') ?></span>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Satuan</p>
                    <p class="text-sm font-medium text-slate-700"><?= esc($barang['satuan']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Harga Beli</p>
                    <p class="text-sm font-semibold text-slate-800">Rp <?= number_format($barang['harga_beli'], 0, ',', '.') ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Harga Jual</p>
                    <p class="text-sm font-semibold text-emerald-600">Rp <?= number_format($barang['harga_jual'], 0, ',', '.') ?></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Stok Saat Ini</p>
                    <?php $stokClass = $barang['stok'] <= $barang['stok_minimum'] && $barang['stok_minimum'] > 0 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'; ?>
                    <span class="<?= $stokClass ?> text-sm font-bold px-3 py-1 rounded-full">
                        <?= number_format($barang['stok']) ?> <?= esc($barang['satuan']) ?>
                    </span>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-1">Stok Minimum</p>
                    <p class="text-sm font-medium text-slate-700"><?= number_format($barang['stok_minimum']) ?> <?= esc($barang['satuan']) ?></p>
                </div>
            </div>

            <?php if ($barang['keterangan']): ?>
            <div class="pt-2 border-t border-slate-100">
                <p class="text-xs text-slate-400 mb-1">Keterangan</p>
                <p class="text-sm text-slate-600"><?= esc($barang['keterangan']) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
