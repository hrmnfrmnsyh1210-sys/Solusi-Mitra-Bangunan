<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Bangunan — Material & Bahan Bangunan Terpercaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 40%, #faf5ff 100%);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 99px;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-white antialiased overflow-x-hidden">

    <!-- ===== NAVBAR ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/60"
        x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                        <i class="fas fa-boxes-stacked text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[14px] leading-tight">Toko Bangunan</p>
                        <p class="text-slate-400 text-[11px]">Material & Bahan Bangunan</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="<?= base_url('shop') ?>"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition">
                        <i class="fas fa-store text-xs mr-1.5"></i>Katalog Produk
                    </a>
                    <a href="<?= base_url('login') ?>"
                        class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition">
                        <i class="fas fa-lock text-xs mr-1.5"></i>Admin
                    </a>
                </div>

                <!-- Mobile Toggle -->
                <button @click="open = !open"
                    class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 transition">
                    <i :class="open ? 'fas fa-times' : 'fas fa-bars'" class="text-sm"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="md:hidden pb-4 border-t border-slate-100 pt-3 space-y-1">
                <a href="<?= base_url('shop') ?>"
                    class="flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg transition">
                    <i class="fas fa-store text-brand-500 w-4 text-center"></i> Katalog Produk
                </a>
                <a href="<?= base_url('login') ?>"
                    class="flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg transition">
                    <i class="fas fa-lock text-slate-400 w-4 text-center"></i> Login Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="relative hero-gradient pt-28 pb-20 lg:pt-36 lg:pb-28 overflow-hidden">
        <!-- Decorative blobs -->
        <div class="blob w-96 h-96 bg-brand-400 top-0 -left-20"></div>
        <div class="blob w-80 h-80 bg-purple-400 bottom-0 right-0"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

                <!-- Text content -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand-100 text-brand-700 text-xs font-semibold mb-5">
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                        Toko Bangunan Online #1
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight tracking-tight">
                        Material Bangunan<br>
                        <span class="text-transparent bg-clip-text"
                            style="background-image:linear-gradient(135deg,#6366f1,#8b5cf6)">
                            Berkualitas Tinggi
                        </span>
                    </h1>
                    <p class="mt-5 text-lg text-slate-500 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Temukan semua kebutuhan bahan bangunan Anda — semen, cat, besi, paku, dan ribuan produk lainnya.
                        Harga terjangkau, stok selalu tersedia.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3">
                        <a href="<?= base_url('shop') ?>"
                            class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl bg-brand-600 text-white font-semibold text-sm hover:bg-brand-700 active:scale-95 transition shadow-lg shadow-brand-200/50">
                            <i class="fas fa-store"></i>
                            Lihat Katalog Produk
                        </a>
                        <a href="<?= base_url('shop/login') ?>"
                            class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-2xl border-2 border-slate-200 bg-white text-slate-700 font-semibold text-sm hover:border-brand-300 hover:text-brand-600 hover:bg-brand-50 active:scale-95 transition">
                            <svg class="w-4 h-4" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.35-8.16 2.35-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                            </svg>
                            Masuk dengan Google
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="mt-10 flex items-center justify-center lg:justify-start gap-8">
                        <div class="text-center lg:text-left">
                            <p class="text-2xl font-extrabold text-slate-900">500+</p>
                            <p class="text-xs text-slate-500 mt-0.5">Produk Tersedia</p>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div class="text-center lg:text-left">
                            <p class="text-2xl font-extrabold text-slate-900">Cepat</p>
                            <p class="text-xs text-slate-500 mt-0.5">Proses Pesanan</p>
                        </div>
                        <div class="w-px h-10 bg-slate-200"></div>
                        <div class="text-center lg:text-left">
                            <p class="text-2xl font-extrabold text-slate-900">COD</p>
                            <p class="text-xs text-slate-500 mt-0.5">Bayar di Tempat</p>
                        </div>
                    </div>
                </div>

                <!-- Visual card -->
                <div class="flex-shrink-0 w-full max-w-sm lg:max-w-md">
                    <div class="relative">
                        <!-- Main card -->
                        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200 p-6 border border-slate-100">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center"
                                    style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                                    <i class="fas fa-boxes-stacked text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Katalog Produk</p>
                                    <p class="text-xs text-slate-400">Toko Bangunan</p>
                                </div>
                            </div>

                            <!-- Mock product cards -->
                            <div class="space-y-3">
                                <?php
                                $items = [
                                    ['icon' => 'fa-layer-group', 'color' => '#f97316', 'name' => 'Semen Portland 50kg', 'harga' => 'Rp 68.000', 'stok' => '120 Sak'],
                                    ['icon' => 'fa-paint-roller', 'color' => '#06b6d4', 'name' => 'Cat Tembok Premium 5kg', 'harga' => 'Rp 95.000', 'stok' => '48 Kaleng'],
                                    ['icon' => 'fa-ruler-combined', 'color' => '#8b5cf6', 'name' => 'Besi Beton 10mm', 'harga' => 'Rp 55.000', 'stok' => '200 Batang'],
                                ];
                                foreach ($items as $item): ?>
                                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 hover:bg-slate-100 transition">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                            style="background:<?= $item['color'] ?>1a">
                                            <i class="fas <?= $item['icon'] ?> text-sm" style="color:<?= $item['color'] ?>"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-slate-700 truncate"><?= $item['name'] ?></p>
                                            <p class="text-[11px] text-slate-400"><?= $item['stok'] ?></p>
                                        </div>
                                        <p class="text-xs font-bold text-brand-600 flex-shrink-0"><?= $item['harga'] ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <a href="<?= base_url('shop') ?>"
                                class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 rounded-2xl bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition">
                                <i class="fas fa-store text-xs"></i>
                                Lihat Semua Produk
                            </a>
                        </div>

                        <!-- Floating badge -->
                        <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-lg border border-slate-100 px-3 py-2 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-700">Stok Tersedia</p>
                                <p class="text-[10px] text-slate-400">Update Real-time</p>
                            </div>
                        </div>

                        <!-- Floating cart badge -->
                        <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-lg border border-slate-100 px-3 py-2 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center"
                                style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                                <i class="fas fa-shopping-cart text-white text-[10px]"></i>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-slate-700">Keranjang Belanja</p>
                                <p class="text-[10px] text-slate-400">Checkout Mudah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-sm font-semibold text-brand-600 uppercase tracking-widest mb-2">Kenapa Pilih Kami?</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Belanja Lebih Mudah, Lebih Cepat</h2>
                <p class="mt-3 text-slate-500 max-w-xl mx-auto">Kami menyediakan pengalaman berbelanja material bangunan yang nyaman dan terpercaya.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                $features = [
                    [
                        'icon' => 'fa-boxes-stacked',
                        'gradient' => 'linear-gradient(135deg,#6366f1,#8b5cf6)',
                        'title' => 'Produk Lengkap',
                        'desc' => 'Ribuan produk material bangunan dari brand terpercaya tersedia di satu tempat.',
                    ],
                    [
                        'icon' => 'fa-bolt',
                        'gradient' => 'linear-gradient(135deg,#f97316,#fb923c)',
                        'title' => 'Stok Real-time',
                        'desc' => 'Info ketersediaan stok selalu diperbarui secara otomatis setiap transaksi.',
                    ],
                    [
                        'icon' => 'fa-shield-halved',
                        'gradient' => 'linear-gradient(135deg,#10b981,#34d399)',
                        'title' => 'Aman & Terpercaya',
                        'desc' => 'Login aman dengan akun Google. Data Anda terlindungi sepenuhnya.',
                    ],
                    [
                        'icon' => 'fa-hand-holding-dollar',
                        'gradient' => 'linear-gradient(135deg,#f59e0b,#fbbf24)',
                        'title' => 'Harga Terjangkau',
                        'desc' => 'Harga kompetitif langsung dari supplier. Hemat lebih banyak untuk proyek Anda.',
                    ],
                ];
                foreach ($features as $f): ?>
                    <div class="group p-6 rounded-3xl border border-slate-100 bg-white hover:border-brand-200 hover:shadow-lg hover:shadow-brand-50 transition-all duration-200">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4"
                            style="background:<?= $f['gradient'] ?>">
                            <i class="fas <?= $f['icon'] ?> text-white text-lg"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-2"><?= $f['title'] ?></h3>
                        <p class="text-sm text-slate-500 leading-relaxed"><?= $f['desc'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-sm font-semibold text-brand-600 uppercase tracking-widest mb-2">Cara Pemesanan</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">3 Langkah Mudah</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Connector line (desktop) -->
                <div class="hidden md:block absolute top-10 left-[calc(16.66%+1rem)] right-[calc(16.66%+1rem)] h-0.5 bg-gradient-to-r from-brand-200 via-brand-400 to-brand-200"></div>

                <?php
                $steps = [
                    ['num' => '01', 'icon' => 'fa-magnifying-glass', 'title' => 'Pilih Produk', 'desc' => 'Telusuri katalog produk kami. Gunakan pencarian atau filter kategori untuk menemukan yang Anda butuhkan.'],
                    ['num' => '02', 'icon' => 'fa-cart-plus', 'title' => 'Tambah ke Keranjang', 'desc' => 'Masukkan produk pilihan ke keranjang belanja. Bisa tambah tanpa harus login terlebih dahulu.'],
                    ['num' => '03', 'icon' => 'fa-circle-check', 'title' => 'Checkout & Selesai', 'desc' => 'Login dengan Google, isi data pengiriman, dan konfirmasi pesanan. Mudah dan cepat!'],
                ];
                foreach ($steps as $i => $step): ?>
                    <div class="relative text-center">
                        <div class="w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-lg"
                            style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                            <i class="fas <?= $step['icon'] ?> text-white text-2xl"></i>
                        </div>
                        <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1 text-[10px] font-black text-brand-400 tracking-widest"><?= $step['num'] ?></span>
                        <h3 class="text-lg font-bold text-slate-800 mb-2"><?= $step['title'] ?></h3>
                        <p class="text-sm text-slate-500 leading-relaxed max-w-xs mx-auto"><?= $step['desc'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== CTA SECTION ===== -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl overflow-hidden text-center p-10 sm:p-14"
                style="background:linear-gradient(135deg,#4f46e5,#7c3aed)">
                <!-- Decorative circles -->
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10" style="background:#fff;transform:translate(30%,-30%)"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-10" style="background:#fff;transform:translate(-30%,30%)"></div>

                <div class="relative">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-white/20 mb-6">
                        <i class="fas fa-store text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                        Siap Mulai Belanja?
                    </h2>
                    <p class="mt-4 text-indigo-200 text-base max-w-lg mx-auto leading-relaxed">
                        Bergabung sekarang dan nikmati kemudahan berbelanja material bangunan secara online. Gratis, cepat, dan terpercaya.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="<?= base_url('shop/login') ?>"
                            class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-2xl bg-white text-brand-700 font-bold text-sm hover:bg-slate-50 active:scale-95 transition shadow-lg">
                            <svg class="w-4 h-4" viewBox="0 0 48 48">
                                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" />
                                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" />
                                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.35-8.16 2.35-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                            </svg>
                            Login dengan Google
                        </a>
                        <a href="<?= base_url('shop') ?>"
                            class="w-full sm:w-auto flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-2xl bg-white/15 border border-white/30 text-white font-semibold text-sm hover:bg-white/25 active:scale-95 transition">
                            <i class="fas fa-arrow-right text-xs"></i>
                            Lihat Produk Dulu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-slate-900 text-slate-400 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                        style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                        <i class="fas fa-boxes-stacked text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white">Toko Bangunan</p>
                        <p class="text-xs">Material & Bahan Bangunan</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 text-xs">
                    <a href="<?= base_url('shop') ?>" class="hover:text-white transition">Katalog</a>
                    <a href="<?= base_url('shop/cart') ?>" class="hover:text-white transition">Keranjang</a>
                    <a href="<?= base_url('shop/login') ?>" class="hover:text-white transition">Masuk</a>
                    <a href="<?= base_url('login') ?>" class="hover:text-white transition">Admin</a>
                </div>

                <p class="text-xs">&copy; <?= date('Y') ?> Toko Bangunan. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

</body>

</html>