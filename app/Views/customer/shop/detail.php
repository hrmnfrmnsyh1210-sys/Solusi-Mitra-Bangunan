<?= $this->extend('customer/layout/main') ?>
<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="<?= base_url('shop') ?>" class="hover:text-brand-600 transition">Katalog</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-slate-700 font-medium truncate"><?= esc($barang['nama_barang']) ?></span>
</nav>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="{ qty: 1, loading: false }">

    <!-- Image -->
    <div class="bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl h-80 lg:h-auto min-h-[280px] flex items-center justify-center overflow-hidden">
        <?php if (!empty($barang['gambar'])): ?>
            <img src="<?= base_url('uploads/barang/'.$barang['gambar']) ?>"
                 alt="<?= esc($barang['nama_barang']) ?>"
                 class="w-full h-full object-cover">
        <?php else: ?>
            <i class="fas fa-cube text-slate-300 text-8xl"></i>
        <?php endif; ?>
    </div>

    <!-- Detail -->
    <div class="flex flex-col">

        <!-- Category badge -->
        <?php if ($barang['nama_kategori']): ?>
        <span class="inline-block w-fit text-xs font-semibold text-brand-600 bg-brand-50 px-3 py-1 rounded-full mb-3">
            <i class="fas fa-tag text-[10px] mr-1"></i><?= esc($barang['nama_kategori']) ?>
        </span>
        <?php endif; ?>

        <!-- Name -->
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 leading-tight">
            <?= esc($barang['nama_barang']) ?>
        </h1>

        <!-- Code -->
        <p class="text-slate-400 text-sm mt-1">Kode: <span class="font-mono font-medium text-slate-600"><?= esc($barang['kode_barang']) ?></span></p>

        <!-- Price -->
        <div class="mt-5 p-4 bg-brand-50 rounded-2xl border border-brand-100">
            <p class="text-xs text-brand-500 font-semibold uppercase tracking-wider mb-1">Harga</p>
            <p class="text-3xl font-bold text-brand-700">
                Rp <?= number_format($barang['harga_jual'], 0, ',', '.') ?>
                <span class="text-base font-normal text-brand-400">/ <?= esc($barang['satuan']) ?></span>
            </p>
        </div>

        <!-- Stock status -->
        <div class="mt-4 flex items-center gap-2">
            <?php if ($barang['stok'] > 0): ?>
                <span class="inline-flex items-center gap-1.5 text-sm text-green-600 font-medium">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Tersedia
                </span>
                <span class="text-slate-300">|</span>
                <span class="text-sm text-slate-500">Stok: <strong class="text-slate-700"><?= $barang['stok'] ?> <?= esc($barang['satuan']) ?></strong></span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 text-sm text-red-500 font-medium">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Stok Habis
                </span>
            <?php endif; ?>
        </div>

        <!-- Description -->
        <?php if (!empty($barang['keterangan'])): ?>
        <div class="mt-5">
            <p class="text-sm font-semibold text-slate-700 mb-1.5">Keterangan</p>
            <p class="text-sm text-slate-500 leading-relaxed"><?= esc($barang['keterangan']) ?></p>
        </div>
        <?php endif; ?>

        <?php if ($barang['stok'] > 0): ?>
        <!-- Qty + Add to cart -->
        <div class="mt-6 flex items-center gap-3 flex-wrap">
            <!-- Qty -->
            <div class="flex items-center border-2 border-slate-200 rounded-2xl overflow-hidden">
                <button @click="qty = Math.max(1, qty - 1)"
                        class="w-11 h-11 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition">
                    <i class="fas fa-minus text-xs"></i>
                </button>
                <input type="number" x-model="qty" min="1" max="<?= $barang['stok'] ?>"
                       class="w-14 h-11 text-center font-bold text-slate-800 text-base border-x-2 border-slate-200 focus:outline-none bg-white">
                <button @click="qty = Math.min(<?= $barang['stok'] ?>, qty + 1)"
                        class="w-11 h-11 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition">
                    <i class="fas fa-plus text-xs"></i>
                </button>
            </div>

            <!-- Add to cart button -->
            <button @click="addToCartDetail(<?= $barang['id'] ?>, qty)"
                    :disabled="loading"
                    class="flex-1 min-w-[160px] flex items-center justify-center gap-2 h-11 rounded-2xl bg-brand-600 text-white font-semibold hover:bg-brand-700 active:scale-[.98] transition disabled:opacity-60">
                <i class="fas fa-cart-plus" x-show="!loading"></i>
                <i class="fas fa-spinner fa-spin" x-show="loading" x-cloak></i>
                <span x-text="loading ? 'Menambahkan...' : 'Tambah ke Keranjang'"></span>
            </button>
        </div>

        <!-- Direct checkout -->
        <div class="mt-3">
            <a href="<?= base_url('shop/cart') ?>"
               class="flex items-center justify-center gap-2 h-11 rounded-2xl border-2 border-brand-200 text-brand-600 font-semibold hover:bg-brand-50 transition">
                <i class="fas fa-shopping-cart text-sm"></i>
                Lihat Keranjang
            </a>
        </div>
        <?php else: ?>
        <div class="mt-6 p-4 bg-red-50 rounded-2xl border border-red-100 text-center">
            <i class="fas fa-box-open text-red-300 text-2xl mb-2"></i>
            <p class="text-red-500 font-semibold">Produk Sedang Tidak Tersedia</p>
        </div>
        <?php endif; ?>

        <!-- Back link -->
        <a href="<?= base_url('shop') ?>"
           class="mt-4 inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-brand-600 transition">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Katalog
        </a>
    </div>
</div>

<script>
function addToCartDetail(idBarang, qty) {
    const comp = Alpine.$data(document.querySelector('[x-data]'));
    comp.loading = true;

    fetch('<?= base_url('shop/cart/add') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: `id_barang=${idBarang}&qty=${qty}`,
    })
    .then(r => r.json())
    .then(data => {
        comp.loading = false;
        if (data.success) {
            updateCartBadge(data.cart_count);
            Swal.fire({
                icon: 'success',
                title: 'Ditambahkan!',
                text: data.message,
                showConfirmButton: true,
                confirmButtonText: 'Lihat Keranjang',
                showCancelButton: true,
                cancelButtonText: 'Lanjut Belanja',
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then(r => {
                if (r.isConfirmed) window.location.href = '<?= base_url('shop/cart') ?>';
            });
        } else {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'warning',
                title: data.message, showConfirmButton: false, timer: 3500, timerProgressBar: true,
            });
        }
    })
    .catch(() => {
        comp.loading = false;
    });
}
</script>

<?= $this->endSection() ?>
