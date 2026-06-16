<?= $this->extend('customer/layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">

    <!-- Success header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
            <i class="fas fa-check text-green-500 text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Pesanan Berhasil!</h1>
        <p class="text-slate-500 mt-2">
            Terima kasih telah berbelanja. Pesanan Anda sedang diproses.
        </p>
    </div>

    <!-- Invoice card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">No. Transaksi</p>
                <p class="font-bold text-slate-800 text-lg font-mono"><?= esc($penjualan['no_transaksi']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Tanggal</p>
                <p class="font-semibold text-slate-700">
                    <?php
                    $ts = strtotime($penjualan['tanggal_jual']);
                    $bulanArr = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
                    echo date('d', $ts) . ' ' . $bulanArr[date('n',$ts)-1] . ' ' . date('Y', $ts);
                    ?>
                </p>
            </div>
        </div>

        <!-- Customer info -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-2">Pemesan</p>
            <div class="flex items-center gap-3">
                <?php if (session()->get('customer_foto')): ?>
                <img src="<?= esc(session()->get('customer_foto')) ?>" class="w-10 h-10 rounded-full object-cover" alt="">
                <?php else: ?>
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
                     style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                    <?= strtoupper(substr($penjualan['nama_pembeli'] ?? 'C', 0, 1)) ?>
                </div>
                <?php endif; ?>
                <div>
                    <p class="font-semibold text-slate-800"><?= esc($penjualan['nama_pembeli']) ?></p>
                    <p class="text-xs text-slate-500"><?= esc(session()->get('customer_email')) ?></p>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="px-6 py-4 border-b border-slate-100">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-3">Detail Produk</p>
            <div class="space-y-3">
                <?php foreach ($details as $d): ?>
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cube text-slate-300 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700"><?= esc($d['nama_barang']) ?></p>
                            <p class="text-xs text-slate-400">
                                Rp <?= number_format($d['harga_jual'], 0, ',', '.') ?> × <?= $d['jumlah'] ?> <?= esc($d['satuan']) ?>
                            </p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-800 flex-shrink-0">
                        Rp <?= number_format($d['subtotal'], 0, ',', '.') ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Total -->
        <div class="px-6 py-5">
            <div class="flex justify-between items-center">
                <span class="text-base font-bold text-slate-700">Total Pembayaran</span>
                <span class="text-xl font-bold text-brand-700">
                    Rp <?= number_format($penjualan['total_harga'], 0, ',', '.') ?>
                </span>
            </div>
            <?php $isQris = ($penjualan['metode_bayar'] ?? 'cod') === 'qris'; ?>
            <div class="mt-2 flex items-center gap-2 text-sm text-slate-500">
                <?php if ($isQris): ?>
                    <i class="fas fa-qrcode text-indigo-500"></i>
                    <span>QRIS</span>
                <?php else: ?>
                    <i class="fas fa-money-bill-wave text-green-500"></i>
                    <span>Bayar di Tempat (COD)</span>
                <?php endif; ?>
            </div>

            <?php if ($isQris): ?>
            <div class="mt-4 p-5 rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white">
                <div class="flex flex-col items-center text-center">
                    <p class="text-sm font-semibold text-slate-700 mb-1">Scan QRIS untuk membayar</p>
                    <p class="text-xs text-slate-500 mb-3">Gunakan e-wallet / m-banking apa pun</p>
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100">
                        <img src="<?= base_url('image/qris.jpeg') ?>" alt="QRIS" class="w-56 h-56 object-contain">
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($penjualan['keterangan']): ?>
            <div class="mt-3 p-3 bg-amber-50 rounded-xl border border-amber-100 text-sm text-amber-700">
                <i class="fas fa-sticky-note mr-1.5 text-amber-400"></i>
                <strong>Catatan:</strong> <?= esc($penjualan['keterangan']) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-3 mt-6">
        <a href="<?= base_url('shop/orders') ?>"
           class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-2xl border-2 border-brand-200 text-brand-600 font-semibold hover:bg-brand-50 transition">
            <i class="fas fa-box"></i> Lihat Pesanan Saya
        </a>
        <a href="<?= base_url('shop') ?>"
           class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition">
            <i class="fas fa-store"></i> Lanjut Belanja
        </a>
    </div>
</div>

<?= $this->endSection() ?>
