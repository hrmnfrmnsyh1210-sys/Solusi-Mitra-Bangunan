<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Manajemen Inventori</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --indigo: #6366f1;
            --violet: #8b5cf6;
            --purple: #a855f7;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f0e17;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px;
            overflow: hidden;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 35%, #4c1d95 70%, #2d1b69 100%);
        }

        /* Animated blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .45;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 { width: 420px; height: 420px; background: #6366f1; top: -120px; left: -80px; animation-delay: 0s; }
        .blob-2 { width: 340px; height: 340px; background: #a855f7; bottom: -80px; right: -60px; animation-delay: 3s; }
        .blob-3 { width: 220px; height: 220px; background: #06b6d4; top: 50%; left: 50%; transform: translate(-50%,-50%); animation-delay: 1.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* Grid lines overlay */
        .grid-overlay {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .left-content { position: relative; z-index: 2; text-align: center; max-width: 400px; }

        .brand-icon {
            width: 88px; height: 88px;
            background: rgba(255,255,255,.12);
            border: 1.5px solid rgba(255,255,255,.2);
            border-radius: 28px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 32px;
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0,0,0,.3), inset 0 1px 0 rgba(255,255,255,.15);
        }
        .brand-icon i { font-size: 36px; color: #fff; }

        .left-content h1 {
            font-size: 32px; font-weight: 800; color: #fff;
            letter-spacing: -.5px; line-height: 1.2;
        }
        .left-content p {
            margin-top: 12px; font-size: 15px; color: rgba(255,255,255,.6);
            line-height: 1.6; font-weight: 400;
        }

        /* Stats pills */
        .stats {
            display: flex; gap: 12px; margin-top: 40px; justify-content: center; flex-wrap: wrap;
        }
        .stat-pill {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 999px;
            padding: 8px 18px;
            display: flex; align-items: center; gap: 8px;
            backdrop-filter: blur(8px);
        }
        .stat-pill i { font-size: 13px; color: #a5b4fc; }
        .stat-pill span { font-size: 13px; color: rgba(255,255,255,.8); font-weight: 500; }

        /* Floating cards */
        .float-card {
            position: absolute; z-index: 2;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 16px;
            padding: 14px 18px;
            backdrop-filter: blur(12px);
            display: flex; align-items: center; gap: 12px;
        }
        .float-card-1 { top: 14%; right: 6%; animation: floatCard 6s ease-in-out infinite; }
        .float-card-2 { bottom: 18%; left: 6%; animation: floatCard 7s ease-in-out infinite reverse; }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }
        .fc-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .fc-icon-green { background: rgba(16,185,129,.25); }
        .fc-icon-amber { background: rgba(245,158,11,.25); }
        .fc-icon i { font-size: 16px; }
        .fc-icon-green i { color: #34d399; }
        .fc-icon-amber i { color: #fbbf24; }
        .fc-text p { font-size: 11px; color: rgba(255,255,255,.5); font-weight: 500; }
        .fc-text h4 { font-size: 14px; color: #fff; font-weight: 700; margin-top: 1px; }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 480px;
            min-height: 100vh;
            background: #fafafa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            overflow-y: auto;
        }

        .form-box { width: 100%; max-width: 360px; }

        .form-header { margin-bottom: 36px; }
        .form-header .eyebrow {
            display: inline-flex; align-items: center; gap: 6px;
            background: #ede9fe; color: #7c3aed;
            font-size: 12px; font-weight: 600;
            padding: 4px 12px; border-radius: 999px;
            margin-bottom: 14px; letter-spacing: .4px; text-transform: uppercase;
        }
        .form-header h2 { font-size: 26px; font-weight: 800; color: #111827; letter-spacing: -.4px; }
        .form-header p  { margin-top: 6px; font-size: 14px; color: #6b7280; font-weight: 400; }

        /* Alert */
        .alert-error {
            display: flex; align-items: flex-start; gap: 10px;
            background: #fff1f2; border: 1px solid #fecdd3;
            color: #be123c; border-radius: 12px;
            padding: 12px 14px; font-size: 13.5px;
            margin-bottom: 24px;
        }
        .alert-error i { margin-top: 1px; flex-shrink: 0; font-size: 14px; }

        /* Form fields */
        .field { margin-bottom: 20px; }
        .field label {
            display: block; font-size: 13px; font-weight: 600;
            color: #374151; margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-wrap .lead-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 14px; pointer-events: none;
            transition: color .2s;
        }
        .inp {
            width: 100%;
            height: 48px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 0 14px 0 42px;
            font-size: 14px; color: #111827;
            font-family: 'Inter', sans-serif;
            outline: none;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
        }
        .inp::placeholder { color: #d1d5db; }
        .inp:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99,102,241,.1);
        }
        .inp:focus + .lead-icon,
        .input-wrap:focus-within .lead-icon { color: #6366f1; }
        .inp-password { padding-right: 46px; }

        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #9ca3af; font-size: 14px;
            transition: color .2s; padding: 2px;
        }
        .toggle-pw:hover { color: #6366f1; }

        /* Submit button */
        .btn-submit {
            width: 100%; height: 50px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none; border-radius: 12px;
            color: #fff; font-size: 15px; font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer; position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: transform .15s, box-shadow .15s, filter .15s;
            box-shadow: 0 4px 18px rgba(99,102,241,.45);
            letter-spacing: .1px;
            margin-top: 8px;
        }
        .btn-submit:hover  { filter: brightness(1.08); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(99,102,241,.5); }
        .btn-submit:active { transform: scale(.98) translateY(0); }
        .btn-submit::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.1) 0%, transparent 100%);
        }

        /* Divider */
        .divider { display: flex; align-items: center; gap: 12px; margin: 28px 0 0; }
        .divider span { height: 1px; flex: 1; background: #e5e7eb; }
        .divider p { font-size: 12px; color: #9ca3af; white-space: nowrap; }

        /* Footer */
        .form-footer {
            margin-top: 40px; text-align: center;
            font-size: 12px; color: #9ca3af;
        }
        .form-footer a { color: #6366f1; text-decoration: none; font-weight: 500; }

        /* Security badge */
        .security-badge {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            margin-top: 20px; font-size: 12px; color: #9ca3af;
        }
        .security-badge i { color: #10b981; font-size: 13px; }

        /* Responsive */
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 32px 24px; }
        }
    </style>
</head>
<body>

<!-- ══════════ LEFT PANEL ══════════ -->
<div class="left-panel">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="grid-overlay"></div>

    <!-- Floating card 1 -->
    <div class="float-card float-card-1">
        <div class="fc-icon fc-icon-green"><i class="fas fa-arrow-trend-up"></i></div>
        <div class="fc-text">
            <p>Stok Hari Ini</p>
            <h4>+248 Item</h4>
        </div>
    </div>

    <!-- Floating card 2 -->
    <div class="float-card float-card-2">
        <div class="fc-icon fc-icon-amber"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="fc-text">
            <p>Stok Menipis</p>
            <h4>12 Produk</h4>
        </div>
    </div>

    <div class="left-content">
        <div class="brand-icon">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <h1>Kendali Inventori<br>di Genggaman Anda</h1>
        <p>Kelola stok, transaksi, dan laporan toko bangunan Anda secara real-time dari satu dasbor terpadu.</p>

        <div class="stats">
            <div class="stat-pill">
                <i class="fas fa-layer-group"></i>
                <span>Multi-kategori</span>
            </div>
            <div class="stat-pill">
                <i class="fas fa-file-chart-column"></i>
                <span>Laporan PDF &amp; Excel</span>
            </div>
            <div class="stat-pill">
                <i class="fas fa-bell"></i>
                <span>Alert Stok Otomatis</span>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ RIGHT PANEL ══════════ -->
<div class="right-panel">
    <div class="form-box">

        <div class="form-header">
            <div class="eyebrow">
                <i class="fas fa-shield-halved" style="font-size:11px;"></i>
                Sistem Manajemen Inventori
            </div>
            <h2>Selamat Datang</h2>
            <p>Masuk untuk mengakses dasbor inventori toko Anda.</p>
        </div>

        <!-- Error flash -->
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-error">
            <i class="fas fa-circle-xmark"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post" autocomplete="on">
            <?= csrf_field() ?>

            <!-- Username -->
            <div class="field">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="inp"
                        placeholder="Masukkan username Anda"
                        value="<?= esc(old('username')) ?>"
                        autocomplete="username"
                        required
                        autofocus
                    >
                    <span class="lead-icon"><i class="fas fa-user"></i></span>
                </div>
            </div>

            <!-- Password -->
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="inp inp-password"
                        placeholder="Masukkan password Anda"
                        autocomplete="current-password"
                        required
                    >
                    <span class="lead-icon"><i class="fas fa-lock"></i></span>
                    <button type="button" class="toggle-pw" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password visibility">
                        <i class="fas fa-eye" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-right-to-bracket"></i>
                Masuk ke Sistem
            </button>
        </form>

        <div class="security-badge">
            <i class="fas fa-lock"></i>
            <span>Koneksi aman &amp; terenkripsi</span>
        </div>

        <div class="divider">
            <span></span>
            <p>Toko Bangunan &copy; <?= date('Y') ?></p>
            <span></span>
        </div>

        <div class="form-footer">
            Butuh bantuan? Hubungi <a href="#">Administrator</a>
        </div>

    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
