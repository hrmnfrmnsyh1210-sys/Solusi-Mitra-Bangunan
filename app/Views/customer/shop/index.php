<?= $this->extend('customer/layout/main') ?>
<?= $this->section('content') ?>

<div x-data="{}">

    <!-- Page header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Katalog Produk</h1>
        <p class="text-slate-500 text-sm mt-1">Temukan material dan bahan bangunan berkualitas</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        <!-- ===== SIDEBAR FILTER ===== -->
        <aside class="lg:w-56 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sticky top-24">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Filter Kategori</h3>
                <div class="space-y-1">
                    <a href="<?= base_url('shop') . ($search ? '?search=' . urlencode($search) : '') ?>"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition
                              <?= !$kategoriId ? 'bg-brand-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' ?>">
                        <i class="fas fa-border-all text-xs w-4 text-center"></i>
                        Semua Produk
                    </a>
                    <?php foreach ($kategoris as $k): ?>
                    <a href="<?= base_url('shop?kategori=' . $k['id'] . ($search ? '&search=' . urlencode($search) : '')) ?>"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition
                              <?= (int)$kategoriId === (int)$k['id'] ? 'bg-brand-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-100' ?>">
                        <i class="fas fa-tag text-xs w-4 text-center"></i>
                        <?= esc($k['nama_kategori']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>

        <!-- ===== PRODUCT GRID ===== -->
        <div class="flex-1 min-w-0">

            <!-- Result info -->
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm text-slate-500">
                    <?= count($barangs) ?> produk ditemukan
                    <?php if ($search): ?>
                        untuk <span class="font-semibold text-slate-700">"<?= esc($search) ?>"</span>
                    <?php endif; ?>
                </p>
            </div>

            <?php if (empty($barangs)): ?>
            <!-- Empty state -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-16 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-slate-400 text-2xl"></i>
                </div>
                <h3 class="font-semibold text-slate-700 mb-1">Produk Tidak Ditemukan</h3>
                <p class="text-slate-400 text-sm mb-4">
                    <?= $search ? 'Tidak ada produk yang cocok dengan pencarian Anda.' : 'Belum ada produk tersedia saat ini.' ?>
                </p>
                <?php if ($search): ?>
                <a href="<?= base_url('shop') ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white text-sm font-medium hover:bg-brand-700 transition">
                    <i class="fas fa-arrow-left text-xs"></i> Lihat Semua Produk
                </a>
                <?php endif; ?>
            </div>
            <?php else: ?>

            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <?php foreach ($barangs as $b): ?>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden
                            hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group"
                     x-data="{ qty: 1, loading: false }">

                    <!-- Product image -->
                    <a href="<?= base_url('shop/detail/' . $b['id']) ?>"
                       class="block h-40 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center relative overflow-hidden">
                        <?php if (!empty($b['gambar'])): ?>
                            <img src="<?= base_url('uploads/barang/'.$b['gambar']) ?>"
                                 alt="<?= esc($b['nama_barang']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <?php else: ?>
                            <div class="absolute inset-0 bg-gradient-to-br from-brand-50/50 to-slate-100 opacity-0 group-hover:opacity-100 transition"></div>
                            <i class="fas fa-cube text-slate-300 text-5xl relative z-10"></i>
                        <?php endif; ?>
                        <?php if ($b['stok'] <= $b['stok_minimum'] && $b['stok_minimum'] > 0): ?>
                        <span class="absolute top-2 right-2 bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Stok Terbatas</span>
                        <?php endif; ?>
                    </a>

                    <div class="p-4">
                        <!-- Category -->
                        <?php if ($b['nama_kategori']): ?>
                        <span class="inline-block text-[10px] font-semibold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-full mb-2">
                            <?= esc($b['nama_kategori']) ?>
                        </span>
                        <?php endif; ?>

                        <!-- Name -->
                        <h3 class="font-semibold text-slate-800 text-sm leading-snug mb-1 line-clamp-2">
                            <?= esc($b['nama_barang']) ?>
                        </h3>

                        <!-- Price -->
                        <p class="text-brand-600 font-bold text-lg mt-2">
                            Rp <?= number_format($b['harga_jual'], 0, ',', '.') ?>
                            <span class="text-slate-400 font-normal text-xs">/ <?= esc($b['satuan']) ?></span>
                        </p>

                        <!-- Stok -->
                        <p class="text-xs text-slate-400 mt-1">
                            Stok: <span class="font-medium text-slate-600"><?= $b['stok'] ?> <?= esc($b['satuan']) ?></span>
                        </p>

                        <!-- Actions -->
                        <div class="mt-3 flex items-center gap-2">
                            <!-- Qty selector -->
                            <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden">
                                <button @click="qty = Math.max(1, qty - 1)"
                                        class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-sm">
                                    <i class="fas fa-minus text-[10px]"></i>
                                </button>
                                <input type="number" x-model="qty" min="1" max="<?= $b['stok'] ?>"
                                       class="w-10 h-8 text-center text-sm font-semibold text-slate-700 border-x border-slate-200 focus:outline-none bg-white">
                                <button @click="qty = Math.min(<?= $b['stok'] ?>, qty + 1)"
                                        class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-slate-100 transition text-sm">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </button>
                            </div>

                            <!-- Add to cart -->
                            <button @click="addToCart(<?= $b['id'] ?>, qty, $el)"
                                    :disabled="loading"
                                    class="flex-1 flex items-center justify-center gap-1.5 h-8 rounded-xl bg-brand-600 text-white text-xs font-semibold hover:bg-brand-700 active:scale-95 transition disabled:opacity-60">
                                <i class="fas fa-cart-plus text-[11px]" x-show="!loading"></i>
                                <i class="fas fa-spinner fa-spin text-[11px]" x-show="loading" x-cloak></i>
                                <span x-text="loading ? 'Menambah...' : 'Keranjang'"></span>
                            </button>

                            <!-- Detail -->
                            <a href="<?= base_url('shop/detail/' . $b['id']) ?>"
                               class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 hover:border-slate-300 transition">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function addToCart(idBarang, qty, btn) {
    const card = btn.closest('[x-data]');
    const comp = Alpine.$data(card);
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
                toast: true, position: 'top-end', icon: 'success',
                title: data.message,
                showConfirmButton: false, timer: 2500, timerProgressBar: true,
            });
            comp.qty = 1;
        } else {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'warning',
                title: data.message,
                showConfirmButton: false, timer: 3500, timerProgressBar: true,
            });
        }
    })
    .catch(() => {
        comp.loading = false;
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Terjadi kesalahan.', showConfirmButton: false, timer: 3000 });
    });
}
</script>

<?= $this->endSection() ?>
