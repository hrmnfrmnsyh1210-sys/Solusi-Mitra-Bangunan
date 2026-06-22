<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-3xl space-y-5">

    <!-- Struk Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Detail Penjualan</h2>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('penjualan/invoice/' . $penjualan['id']) ?>" target="_blank"
                   class="text-xs px-3 py-1.5 rounded-lg font-medium text-white flex items-center gap-1.5 transition"
                   style="background:linear-gradient(135deg,#4f46e5,#6366f1)">
                    <i class="fas fa-file-invoice"></i> Invoice A4
                </a>
                <button onclick="window.print()"
                        class="text-xs px-3 py-1.5 rounded-lg font-medium text-white flex items-center gap-1.5 transition"
                        style="background:linear-gradient(135deg,#10b981,#059669)">
                    <i class="fas fa-print"></i> Cetak Struk
                </button>
                <a href="<?= base_url('penjualan') ?>"
                   class="text-xs text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
                   <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Info Transaksi -->
        <div class="px-6 py-5 grid grid-cols-2 sm:grid-cols-4 gap-4 border-b border-slate-100">
            <div>
                <p class="text-xs text-slate-400 mb-1">No. Transaksi</p>
                <span class="font-mono text-sm bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg"><?= esc($penjualan['no_transaksi']) ?></span>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Tanggal</p>
                <p class="text-sm font-medium text-slate-700"><?= date('d M Y', strtotime($penjualan['tanggal_jual'])) ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Pembeli</p>
                <p class="text-sm font-medium text-slate-700"><?= esc($penjualan['nama_pembeli'] ?? 'Umum') ?></p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-1">Status</p>
                <?php $badge = \App\Models\PenjualanModel::statusBadge($penjualan['status_bayar'] ?? 'lunas'); ?>
                <span class="<?= $badge['class'] ?> text-xs font-semibold px-2.5 py-1 rounded-full"><?= $badge['label'] ?></span>
            </div>
        </div>

        <!-- Pembayaran & Catatan Pelanggan -->
        <?php
            $metode = $penjualan['metode_bayar'] ?? 'cod';
            $isPending = ($penjualan['status_bayar'] ?? '') === 'pending';
        ?>
        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-3 gap-4 border-b border-slate-100">
            <div>
                <p class="text-xs text-slate-400 mb-1">Metode Pembayaran</p>
                <p class="text-sm font-medium text-slate-700">
                    <?php if ($metode === 'qris'): ?>
                        <i class="fas fa-qrcode text-indigo-500 mr-1"></i> QRIS
                    <?php else: ?>
                        <i class="fas fa-money-bill-wave text-emerald-500 mr-1"></i> COD (Bayar di Tempat)
                    <?php endif; ?>
                </p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs text-slate-400 mb-1">Catatan Pelanggan</p>
                <?php if (!empty($penjualan['keterangan'])): ?>
                <p class="text-sm text-slate-700 bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                    <i class="fas fa-sticky-note text-amber-400 mr-1"></i> <?= esc($penjualan['keterangan']) ?>
                </p>
                <?php else: ?>
                <p class="text-sm text-slate-400 italic">— tidak ada catatan —</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bukti pembayaran (QRIS) + aksi validasi -->
        <?php if ($metode === 'qris' || $isPending): ?>
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                <div class="flex-shrink-0">
                    <p class="text-xs text-slate-400 mb-2">Bukti Pembayaran</p>
                    <?php if (!empty($penjualan['bukti_bayar'])): ?>
                    <a href="<?= base_url('uploads/bukti/' . $penjualan['bukti_bayar']) ?>" target="_blank">
                        <img src="<?= base_url('uploads/bukti/' . $penjualan['bukti_bayar']) ?>" alt="Bukti bayar"
                             class="w-40 h-40 object-cover rounded-xl border border-slate-200 hover:opacity-90 transition">
                    </a>
                    <p class="text-[11px] text-slate-400 mt-1">Klik untuk perbesar</p>
                    <?php else: ?>
                    <div class="w-40 h-40 rounded-xl border-2 border-dashed border-slate-200 flex items-center justify-center text-center text-slate-300 text-xs px-2">
                        <?= $metode === 'qris' ? 'Pelanggan belum mengunggah bukti' : 'Tidak ada (COD)' ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($isPending): ?>
                <div class="flex-1">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm font-semibold text-slate-700 mb-1">Validasi Pembayaran</p>
                        <p class="text-xs text-slate-500 mb-3">
                            <?= $metode === 'qris'
                                ? 'Periksa bukti transfer di samping. Jika nominal & tujuan sesuai, tandai Lunas.'
                                : 'Untuk COD, tandai Lunas setelah barang diterima & dibayar pelanggan.' ?>
                        </p>
                        <div class="flex items-center gap-2">
                            <form action="<?= base_url('penjualan/validasi/' . $penjualan['id']) ?>" method="post"
                                  onsubmit="return confirm('Tandai transaksi ini sebagai LUNAS?')">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold flex items-center gap-2 transition">
                                    <i class="fas fa-check-circle"></i> Validasi & Tandai Lunas
                                </button>
                            </form>
                            <form action="<?= base_url('penjualan/batal/' . $penjualan['id']) ?>" method="post"
                                  onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                                <?= csrf_field() ?>
                                <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 text-sm font-semibold flex items-center gap-2 transition">
                                    <i class="fas fa-times-circle"></i> Tolak / Batalkan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Detail Barang -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Barang</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Jml</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Harga Beli</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Harga Jual</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500 uppercase">Subtotal</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-emerald-600 uppercase">Keuntungan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($details as $d):
                        $keuntungan_item = ($d['harga_jual'] - $d['harga_beli']) * $d['jumlah'];
                    ?>
                    <tr>
                        <td class="px-6 py-3.5">
                            <p class="font-medium text-slate-800"><?= esc($d['nama_barang']) ?></p>
                            <p class="text-xs text-slate-400 font-mono"><?= esc($d['kode_barang']) ?></p>
                        </td>
                        <td class="px-4 py-3.5 text-center text-slate-700"><?= number_format($d['jumlah']) ?> <?= esc($d['satuan']) ?></td>
                        <td class="px-4 py-3.5 text-right text-slate-500 text-xs">Rp <?= number_format($d['harga_beli'], 0, ',', '.') ?></td>
                        <td class="px-4 py-3.5 text-right text-slate-700">Rp <?= number_format($d['harga_jual'], 0, ',', '.') ?></td>
                        <td class="px-4 py-3.5 text-right font-semibold text-slate-800">Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                        <td class="px-6 py-3.5 text-right font-semibold <?= $keuntungan_item >= 0 ? 'text-emerald-600' : 'text-rose-500' ?>">
                            Rp <?= number_format($keuntungan_item, 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Bayar -->
        <div class="px-6 py-5 bg-slate-50 rounded-b-2xl space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Total Belanja</span>
                <span class="font-semibold text-slate-800">Rp <?= number_format($penjualan['total_harga'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Uang Bayar</span>
                <span class="font-semibold text-slate-800">Rp <?= number_format($penjualan['bayar'], 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between text-sm pt-2 border-t border-slate-200">
                <span class="font-semibold text-slate-700">Kembalian</span>
                <span class="font-bold text-lg text-slate-700">Rp <?= number_format($penjualan['kembalian'], 0, ',', '.') ?></span>
            </div>

            <!-- Keuntungan bersih -->
            <div class="flex justify-between items-center text-sm pt-3 mt-1 border-t-2 border-emerald-200">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-arrow-trend-up text-emerald-600 text-xs"></i>
                    </div>
                    <span class="font-bold text-emerald-700">Keuntungan Transaksi</span>
                </div>
                <?php
                    $margin_trx = ($penjualan['total_harga'] > 0)
                        ? round(($total_keuntungan / $penjualan['total_harga']) * 100, 1)
                        : 0;
                ?>
                <div class="text-right">
                    <span class="font-bold text-xl text-emerald-600">Rp <?= number_format($total_keuntungan, 0, ',', '.') ?></span>
                    <span class="ml-2 text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-semibold"><?= $margin_trx ?>% margin</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== STRUK PRINT AREA - hidden on screen, only visible when printing ===== -->
<div id="struk-area" style="display:none">
    <div style="font-family:'Courier New',Courier,monospace; font-size:12px; width:80mm; margin:0 auto;">

        <!-- Header Toko -->
        <div style="text-align:center; padding-bottom:8px; border-bottom:1px dashed #000; margin-bottom:8px;">
            <div style="font-size:15px; font-weight:bold; letter-spacing:1px;">TOKO BANGUNAN</div>
            <div style="font-size:10px; margin-top:2px;">Sistem Manajemen Inventori</div>
        </div>

        <!-- Info Transaksi -->
        <div style="font-size:11px; margin-bottom:8px; line-height:1.6;">
            <div>No   : <strong><?= esc($penjualan['no_transaksi']) ?></strong></div>
            <div>Tgl  : <?= date('d/m/Y', strtotime($penjualan['tanggal_jual'])) ?></div>
            <div>Kasir: <?= esc(session()->get('nama') ?: '-') ?></div>
        </div>

        <!-- Garis -->
        <div style="border-top:1px dashed #000; margin-bottom:8px;"></div>

        <!-- Daftar Item -->
        <?php foreach ($details as $d): ?>
        <div style="margin-bottom:6px;">
            <div style="font-weight:bold; font-size:12px;"><?= esc($d['nama_barang']) ?></div>
            <div style="display:flex; justify-content:space-between; padding-left:4px; font-size:11px;">
                <span><?= number_format($d['jumlah']) ?> <?= esc($d['satuan']) ?> &times; Rp&nbsp;<?= number_format($d['harga_jual'], 0, ',', '.') ?></span>
                <span>Rp&nbsp;<?= number_format($d['subtotal'], 0, ',', '.') ?></span>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Garis -->
        <div style="border-top:1px dashed #000; margin:8px 0;"></div>

        <!-- Ringkasan Bayar -->
        <div style="font-size:12px; line-height:1.9;">
            <div style="display:flex; justify-content:space-between;">
                <span>TOTAL</span>
                <span><strong>Rp&nbsp;<?= number_format($penjualan['total_harga'], 0, ',', '.') ?></strong></span>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span>BAYAR</span>
                <span>Rp&nbsp;<?= number_format($penjualan['bayar'], 0, ',', '.') ?></span>
            </div>
            <div style="display:flex; justify-content:space-between; font-weight:bold; font-size:13px; border-top:1px solid #000; padding-top:4px; margin-top:2px;">
                <span>KEMBALIAN</span>
                <span>Rp&nbsp;<?= number_format($penjualan['kembalian'], 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Footer -->
        <div style="border-top:1px dashed #000; margin-top:10px; padding-top:8px; text-align:center; font-size:11px; line-height:1.7;">
            <div>*** Terima kasih telah berbelanja! ***</div>
            <div style="font-size:10px; margin-top:2px;">Barang yang sudah dibeli</div>
            <div style="font-size:10px;">tidak dapat dikembalikan.</div>
        </div>

    </div>
</div>

<style>
@media screen {
    #struk-area { display: none; }
}
@media print {
    body { visibility: hidden; }
    #struk-area {
        visibility: visible !important;
        display: block !important;
        position: fixed;
        top: 0;
        left: 0;
        width: 80mm;
        padding: 6mm;
        background: white;
    }
    #struk-area * { visibility: visible !important; }
    @page {
        size: 80mm auto;
        margin: 0;
    }
}
</style>

<?= $this->endSection() ?>
