<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-3xl space-y-5">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Detail Retur Penjualan</h2>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('retur') ?>"
                   class="text-xs text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Info Retur -->
        <div class="px-6 py-5 grid grid-cols-2 sm:grid-cols-3 gap-4 border-b border-slate-100">
            <div>
                <p class="text-xs text-slate-400 mb-1">No. Retur</p>
                <span class="font-mono text-sm bg-rose-50 text-rose-700 px-2.5 py-1 rounded-lg"><?= esc($retur['no_retur']) ?></span>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Tanggal Retur</p>
                <p class="text-sm font-medium text-slate-700"><?= date('d M Y', strtotime($retur['tanggal_retur'])) ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Transaksi Asal</p>
                <?php if ($retur['no_transaksi_asal']): ?>
                <span class="font-mono text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md"><?= esc($retur['no_transaksi_asal']) ?></span>
                <?php else: ?>
                <span class="text-slate-400 text-sm">—</span>
                <?php endif; ?>
            </div>
            <div class="col-span-2 sm:col-span-3">
                <p class="text-xs text-slate-400 mb-1">Alasan</p>
                <p class="text-sm font-medium text-slate-700"><?= esc($retur['alasan']) ?></p>
            </div>
            <?php if ($retur['keterangan']): ?>
            <div class="col-span-2 sm:col-span-3">
                <p class="text-xs text-slate-400 mb-1">Keterangan</p>
                <p class="text-sm text-slate-600"><?= esc($retur['keterangan']) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Detail Item -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Barang</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Harga Jual</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-rose-600 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $totalNilai = 0; foreach ($details as $d): $totalNilai += $d['subtotal']; ?>
                    <tr>
                        <td class="px-6 py-3.5">
                            <p class="font-medium text-slate-800"><?= esc($d['nama_barang']) ?></p>
                            <p class="text-xs text-slate-400 font-mono"><?= esc($d['kode_barang']) ?></p>
                        </td>
                        <td class="px-4 py-3.5 text-center text-slate-700"><?= number_format($d['jumlah']) ?> <?= esc($d['satuan']) ?></td>
                        <td class="px-4 py-3.5 text-right text-slate-600">Rp <?= number_format($d['harga_jual'], 0, ',', '.') ?></td>
                        <td class="px-6 py-3.5 text-right font-semibold text-rose-600">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="px-6 py-4 bg-rose-50 rounded-b-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-rose-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-rotate-left text-white text-xs"></i>
                </div>
                <span class="font-bold text-rose-800 text-sm">Total Nilai Retur</span>
                <span class="text-xs bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full">Stok sudah dikembalikan</span>
            </div>
            <span class="font-bold text-xl text-rose-600">Rp <?= number_format($totalNilai, 0, ',', '.') ?></span>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
