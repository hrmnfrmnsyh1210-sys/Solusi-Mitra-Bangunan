<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Toko Bangunan') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50:'#eef2ff', 100:'#e0e7ff', 200:'#c7d2fe',
                            300:'#a5b4fc', 400:'#818cf8', 500:'#6366f1',
                            600:'#4f46e5', 700:'#4338ca', 800:'#3730a3', 900:'#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen flex flex-col">

<!-- ===== NAVBAR ===== -->
<header class="bg-white shadow-sm sticky top-0 z-40" x-data="{ userMenuOpen: false, mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            <!-- Logo -->
            <a href="<?= base_url('shop') ?>" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                     style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                    <i class="fas fa-boxes-stacked text-white text-sm"></i>
                </div>
                <div class="hidden sm:block">
                    <p class="font-bold text-slate-800 text-[14px] leading-tight">Toko Bangunan</p>
                    <p class="text-slate-400 text-[11px]">Material & Bahan Bangunan</p>
                </div>
            </a>

            <!-- Search bar -->
            <form action="<?= base_url('shop') ?>" method="get" class="flex-1 max-w-lg hidden md:flex">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search"
                           value="<?= esc(service('request')->getGet('search') ?? '') ?>"
                           placeholder="Cari produk..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition">
                </div>
            </form>

            <!-- Right actions -->
            <div class="flex items-center gap-2 flex-shrink-0">

                <!-- Cart -->
                <a href="<?= base_url('shop/cart') ?>"
                   class="relative w-10 h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition">
                    <i class="fas fa-shopping-cart text-[15px]"></i>
                    <?php $cartCount = array_sum(session()->get('cart') ?? []); ?>
                    <span id="cart-badge"
                          class="absolute -top-0.5 -right-0.5 w-[18px] h-[18px] bg-brand-600 text-white text-[9px] font-bold rounded-full flex items-center justify-center <?= $cartCount > 0 ? '' : 'hidden' ?>">
                        <?= $cartCount > 0 ? $cartCount : '' ?>
                    </span>
                </a>

                <!-- User / Login -->
                <?php if (session()->get('customer_logged_in')): ?>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100 transition">
                        <?php if (session()->get('customer_foto')): ?>
                            <img src="<?= esc(session()->get('customer_foto')) ?>"
                                 class="w-8 h-8 rounded-full object-cover ring-2 ring-brand-200" alt="">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                                <?= strtoupper(substr(session()->get('customer_nama') ?? 'C', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <span class="hidden sm:block text-sm font-medium text-slate-700 max-w-[120px] truncate">
                            <?= esc(session()->get('customer_nama')) ?>
                        </span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"
                           :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.outside="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-1.5 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-1.5 z-50">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-800 truncate">
                                <?= esc(session()->get('customer_nama')) ?>
                            </p>
                            <p class="text-xs text-slate-400 truncate mt-0.5">
                                <?= esc(session()->get('customer_email')) ?>
                            </p>
                        </div>
                        <a href="<?= base_url('shop/orders') ?>"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition">
                            <i class="fas fa-box-open w-4 text-center text-slate-400 text-xs"></i>
                            Pesanan Saya
                        </a>
                        <div class="border-t border-slate-100 mt-1 pt-1">
                            <button onclick="confirmLogoutCustomer('<?= base_url('shop/auth/logout') ?>')"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-500 hover:bg-rose-50 transition">
                                <i class="fas fa-right-from-bracket w-4 text-center text-xs"></i>
                                Logout
                            </button>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?= base_url('shop/login') ?>"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white text-sm font-medium hover:bg-brand-700 active:scale-95 transition">
                    <i class="fas fa-right-to-bracket text-xs"></i>
                    <span>Masuk</span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mobile search -->
        <div class="md:hidden pb-3">
            <form action="<?= base_url('shop') ?>" method="get">
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search"
                           value="<?= esc(service('request')->getGet('search') ?? '') ?>"
                           placeholder="Cari produk..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/30 focus:border-brand-400 transition">
                </div>
            </form>
        </div>
    </div>
</header>

<!-- Flash messages via SweetAlert2 -->
<?php if (session()->getFlashdata('success')): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        toast: true, position: 'top-end', icon: 'success',
        title: <?= json_encode(session()->getFlashdata('success')) ?>,
        showConfirmButton: false, timer: 3500, timerProgressBar: true,
    });
});
</script>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        toast: true, position: 'top-end', icon: 'error',
        title: <?= json_encode(session()->getFlashdata('error')) ?>,
        showConfirmButton: false, timer: 5000, timerProgressBar: true,
    });
});
</script>
<?php endif; ?>

<!-- ===== CONTENT ===== -->
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?= $this->renderSection('content') ?>
</main>

<!-- ===== FOOTER ===== -->
<footer class="bg-white border-t border-slate-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-boxes-stacked text-white text-[11px]"></i>
            </div>
            <span class="text-sm font-semibold text-slate-600">Toko Bangunan</span>
        </div>
        <p class="text-xs text-slate-400">&copy; <?= date('Y') ?> Toko Bangunan. Semua hak dilindungi.</p>
    </div>
</footer>

<script>
function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

function confirmLogoutCustomer(url) {
    Swal.fire({
        title: 'Logout?',
        text: 'Anda akan keluar dari akun.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then(r => { if (r.isConfirmed) window.location.href = url; });
}
</script>
</body>
</html>
