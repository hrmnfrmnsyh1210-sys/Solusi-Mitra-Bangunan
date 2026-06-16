<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Toko Bangunan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-brand-50 to-slate-100 flex items-center justify-center p-4 antialiased">

<!-- Background decoration -->
<div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-10"
         style="background: radial-gradient(circle, #6366f1 0%, transparent 70%)"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full opacity-10"
         style="background: radial-gradient(circle, #8b5cf6 0%, transparent 70%)"></div>
</div>

<div class="w-full max-w-md relative z-10">

    <!-- Card -->
    <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/80 overflow-hidden">

        <!-- Header -->
        <div class="px-8 pt-10 pb-8 text-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%)">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4"
                 style="background: rgba(255,255,255,0.15)">
                <i class="fas fa-boxes-stacked text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Toko Bangunan</h1>
            <p class="text-brand-200 text-sm mt-1">Material & Bahan Bangunan Terlengkap</p>
        </div>

        <!-- Body -->
        <div class="px-8 py-8">
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-slate-800">Selamat Datang!</h2>
                <p class="text-slate-500 text-sm mt-1.5">
                    Masuk untuk mulai berbelanja dan melihat pesanan Anda.
                </p>
            </div>

            <!-- Google Login Button -->
            <a href="<?= base_url('shop/auth/google') ?>"
               class="group flex items-center justify-center gap-3 w-full py-3.5 px-6 rounded-2xl border-2 border-slate-200 bg-white hover:border-brand-300 hover:bg-brand-50 hover:shadow-lg hover:shadow-brand-100 active:scale-[.98] transition-all duration-200">
                <!-- Google Logo SVG -->
                <svg width="20" height="20" viewBox="0 0 48 48" class="flex-shrink-0">
                    <path fill="#FFC107" d="M43.6 20.1H42V20H24v8h11.3C33.6 33 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34.2 6.6 29.4 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.6-.4-3.9z"/>
                    <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.5 16.1 18.9 13 24 13c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34.2 6.6 29.4 4 24 4 16.3 4 9.7 8.4 6.3 14.7z"/>
                    <path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.3 35.3 26.8 36 24 36c-5.3 0-9.6-3-11.3-7.5l-6.5 5C9.5 39.4 16.2 44 24 44z"/>
                    <path fill="#1976D2" d="M43.6 20.1H42V20H24v8h11.3c-.8 2.3-2.4 4.2-4.4 5.5l6.2 5.2C36.9 36.2 44 31 44 24c0-1.3-.1-2.6-.4-3.9z"/>
                </svg>
                <span class="text-[15px] font-semibold text-slate-700 group-hover:text-brand-700">
                    Masuk dengan Google
                </span>
            </a>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-slate-100"></div>
                <span class="text-xs text-slate-400 font-medium">Aman & Mudah</span>
                <div class="flex-1 h-px bg-slate-100"></div>
            </div>

            <!-- Benefits -->
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shield-halved text-brand-500 text-xs"></i>
                    </div>
                    <p class="text-sm text-slate-600">Login aman menggunakan akun Google Anda</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-box text-green-500 text-xs"></i>
                    </div>
                    <p class="text-sm text-slate-600">Pantau riwayat dan status pesanan Anda</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cart-shopping text-amber-500 text-xs"></i>
                    </div>
                    <p class="text-sm text-slate-600">Belanja lebih mudah dengan checkout cepat</p>
                </div>
            </div>
        </div>

        <!-- Footer card -->
        <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 text-center">
            <a href="<?= base_url('shop') ?>"
               class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-brand-600 transition">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Katalog
            </a>
        </div>
    </div>

    <p class="text-center text-xs text-slate-400 mt-5">
        &copy; <?= date('Y') ?> Toko Bangunan. Semua hak dilindungi.
    </p>
</div>

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

</body>
</html>
