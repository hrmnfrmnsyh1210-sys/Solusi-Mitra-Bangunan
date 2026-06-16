<?= $this->extend('customer/layout/main') ?>
<?= $this->section('content') ?>

<!-- Page header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Keranjang Belanja</h1>
    <p class="text-slate-500 text-sm mt-1">
        <?= count($items) ?> jenis produk dalam keranjang Anda
    </p>
</div>

<?php if (empty($items)): ?>
<!-- Empty cart -->
<div class="bg-white rounded-3xl border border-slate-200/80 p-16 text-center shadow-sm">
    <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-5">
        <i class="fas fa-cart-shopping text-slate-300 text-4xl"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-700 mb-2">Keranjang Kosong</h3>
    <p class="text-slate-400 text-sm mb-6">Belum ada produk di keranjang Anda. Yuk mulai belanja!</p>
    <a href="<?= base_url('shop') ?>"
       class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-brand-600 text-white font-semibold hover:bg-brand-700 active:scale-95 transition">
        <i class="fas fa-store"></i> Mulai Belanja
    </a>
</div>

<?php else: ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="cart-container">

    <!-- Cart items -->
    <div class="lg:col-span-2 space-y-3">
        <?php foreach ($items as $item): ?>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex items-center gap-4"
             id="cart-item-<?= $item['id'] ?>"
             x-data="{ qty: <?= $item['qty'] ?>, loading: false }">

            <!-- Icon -->
            <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-cube text-slate-300 text-2xl"></i>
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-slate-800 text-sm truncate"><?= esc($item['nama']) ?></h4>
                <p class="text-brand-600 font-bold text-sm mt-0.5">
                    Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                    <span class="text-slate-400 font-normal text-xs">/ <?= esc($item['satuan']) ?></span>
                </p>

                <!-- Qty controls (mobile/tablet) -->
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden">
                        <button @click="updateQty(<?= $item['id'] ?>, qty - 1)"
                                class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition">
                            <i class="fas fa-minus text-[10px]"></i>
                        </button>
                        <input type="number" x-model="qty" min="1" max="<?= $item['stok'] ?>"
                               @change="updateQty(<?= $item['id'] ?>, qty)"
                               class="w-12 h-8 text-center text-sm font-semibold text-slate-700 border-x border-slate-200 focus:outline-none bg-white">
                        <button @click="updateQty(<?= $item['id'] ?>, qty + 1)"
                                class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition">
                            <i class="fas fa-plus text-[10px]"></i>
                        </button>
                    </div>
                    <span class="text-xs text-slate-400">Max: <?= $item['stok'] ?></span>
                </div>
            </div>

            <!-- Subtotal + remove -->
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold text-slate-800" id="subtotal-<?= $item['id'] ?>">
                    Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                </p>
                <button @click="removeItem(<?= $item['id'] ?>)"
                        class="mt-2 text-xs text-red-400 hover:text-red-600 transition flex items-center gap-1 ml-auto">
                    <i class="fas fa-trash text-[10px]"></i> Hapus
                </button>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Continue shopping -->
        <a href="<?= base_url('shop') ?>"
           class="inline-flex items-center gap-2 text-sm text-brand-600 hover:text-brand-700 font-medium mt-2 transition">
            <i class="fas fa-arrow-left text-xs"></i> Lanjut Belanja
        </a>
    </div>

    <!-- Order Summary -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4">Ringkasan Pesanan</h3>

            <div class="space-y-2.5 text-sm">
                <?php foreach ($items as $item): ?>
                <div class="flex justify-between text-slate-600" id="summary-<?= $item['id'] ?>">
                    <span class="truncate pr-2"><?= esc($item['nama']) ?> <span class="text-slate-400">×<?= $item['qty'] ?></span></span>
                    <span class="font-medium flex-shrink-0">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-slate-100 mt-4 pt-4">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-slate-700">Total</span>
                    <span class="text-lg font-bold text-brand-700" id="grand-total">
                        Rp <?= number_format($total, 0, ',', '.') ?>
                    </span>
                </div>
            </div>

            <?php if (session()->get('customer_logged_in')): ?>
            <a href="<?= base_url('shop/checkout') ?>"
               class="mt-5 flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl bg-brand-600 text-white font-bold hover:bg-brand-700 active:scale-[.98] transition">
                <i class="fas fa-credit-card"></i> Lanjut ke Checkout
            </a>
            <?php else: ?>
            <a href="<?= base_url('shop/login') ?>"
               class="mt-5 flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl bg-brand-600 text-white font-bold hover:bg-brand-700 active:scale-[.98] transition">
                <i class="fas fa-right-to-bracket"></i> Login untuk Checkout
            </a>
            <p class="text-xs text-slate-400 text-center mt-2">Login diperlukan untuk melanjutkan</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endif; ?>

<script>
function updateQty(id, qty) {
    qty = parseInt(qty);
    if (qty < 1) qty = 1;

    fetch('<?= base_url('shop/cart/update') ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_barang=${id}&qty=${qty}`,
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            updateCartBadge(data.cart_count);
            document.getElementById(`subtotal-${id}`).textContent =
                'Rp ' + data.subtotal.toLocaleString('id-ID');
            document.getElementById('grand-total').textContent =
                'Rp ' + data.total.toLocaleString('id-ID');

            const comp = Alpine.$data(document.getElementById(`cart-item-${id}`));
            if (comp) comp.qty = qty;
        }
    });
}

function removeItem(id) {
    Swal.fire({
        title: 'Hapus produk?',
        text: 'Produk akan dihapus dari keranjang.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch('<?= base_url('shop/cart/remove') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_barang=${id}`,
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const el = document.getElementById(`cart-item-${id}`);
                if (el) el.remove();
                updateCartBadge(data.cart_count);
                document.getElementById('grand-total').textContent =
                    'Rp ' + data.total.toLocaleString('id-ID');

                if (data.cart_count === 0) {
                    location.reload();
                }
            }
        });
    });
}
</script>

<?= $this->endSection() ?>
