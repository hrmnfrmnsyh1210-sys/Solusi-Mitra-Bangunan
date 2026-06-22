<?= $this->extend('customer/layout/main') ?>
<?= $this->section('content') ?>

<!-- Page header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Pesanan Saya</h1>
    <p class="text-slate-500 text-sm mt-1">Riwayat semua pesanan Anda</p>
</div>

<?php if (empty($orders)): ?>
<div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm">
    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-5">
        <i class="fas fa-box-open text-slate-300 text-4xl"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-700 mb-2">Belum Ada Pesanan</h3>
    <p class="text-slate-400 text-sm mb-6">Anda belum pernah melakukan pemesanan.</p>
    <a href="<?= base_url('shop') ?>"
       class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-brand-600 text-white font-semibold hover:bg-brand-700 active:scale-95 transition">
        <i class="fas fa-store"></i> Mulai Belanja
    </a>
</div>

<?php else: ?>

<div class="space-y-4">
    <?php foreach ($orders as $order):
        $ts      = strtotime($order['tanggal_jual']);
        $bulanArr = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        $tglStr  = date('d', $ts) . ' ' . $bulanArr[date('n',$ts)-1] . ' ' . date('Y', $ts);
    ?>
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:shadow-md transition">
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-2">
            <div>
                <?php $badge = \App\Models\PenjualanModel::statusBadge($order['status_bayar'] ?? 'pending'); ?>
                <span class="inline-block px-2.5 py-0.5 <?= $badge['class'] ?> text-[11px] font-semibold rounded-full mb-1">
                    <?= $badge['label'] ?>
                </span>
                <p class="font-bold text-slate-800 font-mono"><?= esc($order['no_transaksi']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-400"><?= $tglStr ?></p>
                <p class="text-lg font-bold text-brand-700 mt-0.5">
                    Rp <?= number_format($order['total_harga'], 0, ',', '.') ?>
                </p>
            </div>
        </div>

        <!-- Info row -->
        <div class="px-5 py-3 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-4 text-sm text-slate-500">
                <span>
                    <i class="fas fa-box text-slate-400 mr-1.5 text-xs"></i>
                    <?= $order['jumlah_item'] ?> item
                </span>
                <?php $isQris = ($order['metode_bayar'] ?? 'cod') === 'qris'; ?>
                <span>
                    <?php if ($isQris): ?>
                        <i class="fas fa-qrcode text-slate-400 mr-1.5 text-xs"></i>
                        QRIS
                    <?php else: ?>
                        <i class="fas fa-money-bill-wave text-slate-400 mr-1.5 text-xs"></i>
                        COD
                    <?php endif; ?>
                </span>
                <?php if ($order['keterangan']): ?>
                <span class="truncate max-w-[200px]" title="<?= esc($order['keterangan']) ?>">
                    <i class="fas fa-sticky-note text-slate-400 mr-1.5 text-xs"></i>
                    <?= esc($order['keterangan']) ?>
                </span>
                <?php endif; ?>
            </div>

            <?php $perluBayar = ($order['status_bayar'] ?? 'pending') === 'pending' && $isQris; ?>
            <a href="<?= base_url('shop/checkout/success/' . $order['id']) ?>"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition
                      <?= $perluBayar ? 'text-white bg-brand-600 hover:bg-brand-700' : 'text-brand-600 bg-brand-50 hover:bg-brand-100' ?>">
                <?php if ($perluBayar): ?>
                    <i class="fas fa-upload text-[10px]"></i> Bayar / Upload Bukti
                <?php else: ?>
                    <i class="fas fa-eye text-[10px]"></i> Lihat Detail
                <?php endif; ?>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<?= $this->endSection() ?>
