<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Manajemen Inventori' ?> — Toko Bangunan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* Sidebar link */
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; border-radius: 10px;
            color: #94a3b8; font-size: 13.5px; font-weight: 500;
            transition: all .18s ease; position: relative;
        }
        .nav-link:hover { background: rgba(255,255,255,.08); color: #e2e8f0; }
        .nav-link.active {
            background: rgba(99,102,241,.25);
            color: #fff;
        }
        .nav-link.active::before {
            content:''; position:absolute; left:-12px; top:50%;
            transform:translateY(-50%);
            width:4px; height:60%; background:#6366f1;
            border-radius:0 4px 4px 0;
        }
        .nav-link i { width: 18px; text-align: center; font-size: 14px; flex-shrink:0; }

        /* Card hover lift */
        .card-lift { transition: transform .2s ease, box-shadow .2s ease; }
        .card-lift:hover { transform: translateY(-2px); box-shadow: 0 10px 30px -8px rgba(0,0,0,.12); }

        /* Table */
        .tbl thead th { background: #f8fafc; font-size:11px; font-weight:600; letter-spacing:.06em; text-transform:uppercase; color:#64748b; padding: 12px 18px; }
        .tbl tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .12s; }
        .tbl tbody tr:hover { background: #fafbff; }
        .tbl tbody td { padding: 13px 18px; font-size: 13.5px; color: #374151; }
        .tbl tbody tr:last-child { border-bottom: none; }

        /* Btn */
        .btn-primary { display:inline-flex; align-items:center; gap:7px; background:#4f46e5; color:#fff; font-size:13px; font-weight:600; padding:9px 18px; border-radius:10px; transition:background .15s, transform .1s; }
        .btn-primary:hover { background:#4338ca; }
        .btn-primary:active { transform: scale(.97); }

        /* Input */
        .inp { width:100%; border:1.5px solid #e5e7eb; border-radius:10px; padding:10px 14px; font-size:13.5px; color:#1f2937; outline:none; transition:border .15s, box-shadow .15s; background:#fff; font-family:'Inter',sans-serif; }
        .inp:focus { border-color:#6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        select.inp { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position:right 10px center; background-repeat:no-repeat; background-size:18px; appearance:none; padding-right:36px; }

        /* Badge */
        .badge { display:inline-flex; align-items:center; font-size:11.5px; font-weight:600; padding:3px 10px; border-radius:99px; }

        /* Gradient sidebar */
        .sidebar-bg { background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%); }
    </style>
</head>
<body class="bg-slate-100 antialiased" x-data="{ open: window.innerWidth >= 1024 }">

<div class="flex h-screen overflow-hidden">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar-bg flex flex-col flex-shrink-0 transition-all duration-300 z-40 relative"
           :class="open ? 'w-60' : 'w-0 lg:w-[68px] overflow-hidden'">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-4 py-5 flex-shrink-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                <i class="fas fa-boxes-stacked text-white text-sm"></i>
            </div>
            <div x-show="open" x-cloak x-transition:enter="transition-opacity duration-200"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <p class="text-white font-bold text-sm leading-tight">Toko Bangunan</p>
                <p class="text-slate-400 text-[11px] mt-0.5">Manajemen Inventori</p>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-2 overflow-y-auto space-y-0.5">

            <div class="mb-3">
                <p x-show="open" x-cloak class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest px-3 mb-1.5">Utama</p>

                <a href="<?= base_url('dashboard') ?>"
                   class="nav-link <?= (uri_string() === 'dashboard' || uri_string() === '') ? 'active' : '' ?>">
                    <i class="fas fa-border-all"></i>
                    <span x-show="open" x-cloak>Dashboard</span>
                </a>
                <a href="<?= base_url('barang') ?>"
                   class="nav-link <?= str_starts_with(uri_string(), 'barang') ? 'active' : '' ?>">
                    <i class="fas fa-cube"></i>
                    <span x-show="open" x-cloak>Data Barang</span>
                </a>
                <a href="<?= base_url('supplier') ?>"
                   class="nav-link <?= str_starts_with(uri_string(), 'supplier') ? 'active' : '' ?>">
                    <i class="fas fa-truck"></i>
                    <span x-show="open" x-cloak>Supplier</span>
                </a>
                <a href="<?= base_url('stok-masuk') ?>"
                   class="nav-link <?= str_starts_with(uri_string(), 'stok-masuk') ? 'active' : '' ?>">
                    <i class="fas fa-download"></i>
                    <span x-show="open" x-cloak>Stok Masuk</span>
                </a>
                <a href="<?= base_url('penjualan') ?>"
                   class="nav-link <?= str_starts_with(uri_string(), 'penjualan') ? 'active' : '' ?>">
                    <i class="fas fa-cash-register"></i>
                    <span x-show="open" x-cloak>Penjualan</span>
                </a>
                <a href="<?= base_url('retur') ?>"
                   class="nav-link <?= str_starts_with(uri_string(), 'retur') ? 'active' : '' ?>">
                    <i class="fas fa-rotate-left"></i>
                    <span x-show="open" x-cloak>Retur & Penyesuaian</span>
                </a>
            </div>

            <div class="pt-2 border-t border-white/5">
                <p x-show="open" x-cloak class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest px-3 mb-1.5 mt-2">Master Data</p>
                <a href="<?= base_url('kategori') ?>"
                   class="nav-link <?= str_starts_with(uri_string(), 'kategori') ? 'active' : '' ?>">
                    <i class="fas fa-tag"></i>
                    <span x-show="open" x-cloak>Kategori</span>
                </a>
            </div>

            <div class="pt-2 border-t border-white/5">
                <p x-show="open" x-cloak class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest px-3 mb-1.5 mt-2">Laporan</p>
                <a href="<?= base_url('laporan') ?>"
                   class="nav-link <?= str_starts_with(uri_string(), 'laporan') ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span x-show="open" x-cloak>Laporan</span>
                </a>
            </div>
        </nav>

    </aside>

    <!-- ===== MAIN ===== -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Topbar -->
        <header class="bg-white border-b border-slate-200/80 px-6 h-[62px] flex items-center justify-between flex-shrink-0 gap-4">

            <!-- Left: hamburger + breadcrumb -->
            <div class="flex items-center gap-3 min-w-0">
                <button @click="open = !open"
                        class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition active:scale-95 flex-shrink-0"
                        title="Toggle Sidebar">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="4.5" width="16" height="2" rx="1" fill="currentColor"/>
                        <rect x="2" y="9" width="11" height="2" rx="1" fill="currentColor" opacity="0.7"/>
                        <rect x="2" y="13.5" width="16" height="2" rx="1" fill="currentColor"/>
                    </svg>
                </button>
                <div class="min-w-0">
                    <h1 class="text-[15px] font-semibold text-slate-800 truncate"><?= $title ?? 'Dashboard' ?></h1>
                    <p class="text-[11px] text-slate-400 hidden sm:block"><?= date('l, d F Y') ?></p>
                </div>
            </div>

            <!-- Right: actions -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <!-- Notif bell -->
                <?php if(isset($stok_minimum) && $stok_minimum > 0): ?>
                <a href="<?= base_url('barang') ?>"
                   class="relative w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                        <?= $stok_minimum ?>
                    </span>
                </a>
                <?php endif; ?>

                <!-- Divider -->
                <div class="w-px h-6 bg-slate-200"></div>

                <!-- User -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                        <?= strtoupper(substr(session()->get('nama') ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[13px] font-semibold text-slate-700 leading-tight"><?= esc(session()->get('nama') ?? 'Admin') ?></p>
                        <p class="text-[11px] text-slate-400">Administrator</p>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-px h-6 bg-slate-200"></div>

                <!-- Logout -->
                <button onclick="confirmLogout('<?= base_url('logout') ?>')"
                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-rose-100 hover:text-rose-600 transition"
                   title="Logout">
                    <i class="fas fa-right-from-bracket text-sm"></i>
                </button>
            </div>
        </header>

        <!-- Flash Messages via SweetAlert2 -->
        <?php if (session()->getFlashdata('success')): ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: <?= json_encode(session()->getFlashdata('success')) ?>,
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
            });
        });
        </script>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: <?= json_encode(session()->getFlashdata('error')) ?>,
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
            });
        });
        </script>
        <?php endif; ?>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<script>
function confirmDelete(url, message) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: message || 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function confirmLogout(url) {
    Swal.fire({
        title: 'Logout?',
        text: 'Anda akan keluar dari sistem.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
</body>
</html>
