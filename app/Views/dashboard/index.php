<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Welcome Banner -->
<?php
    date_default_timezone_set('Asia/Jakarta');
    $hour = (int) date('H');
    if ($hour >= 5 && $hour < 12)       { $greeting = 'Selamat Pagi';  $greetIcon = 'fa-sun';           $greetColor = 'from-amber-400 to-orange-500'; }
    elseif ($hour >= 12 && $hour < 15)  { $greeting = 'Selamat Siang'; $greetIcon = 'fa-cloud-sun';     $greetColor = 'from-sky-400 to-blue-500'; }
    elseif ($hour >= 15 && $hour < 19)  { $greeting = 'Selamat Sore';  $greetIcon = 'fa-cloud-sun-rain'; $greetColor = 'from-orange-400 to-rose-500'; }
    else                                { $greeting = 'Selamat Malam'; $greetIcon = 'fa-moon';           $greetColor = 'from-indigo-600 to-violet-700'; }

    $nama = session()->get('nama') ?? 'Admin';
    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][(int)date('w')];
    $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][(int)date('n')-1];
    $tanggal = $hari . ', ' . date('d') . ' ' . $bulan . ' ' . date('Y');
?>
<div class="relative overflow-hidden rounded-2xl mb-6 shadow-lg"
     style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #0ea5e9 100%);">

    <!-- Decorative blobs -->
    <div class="absolute -top-10 -right-10 w-56 h-56 rounded-full opacity-10"
         style="background: radial-gradient(circle, #fff 0%, transparent 70%)"></div>
    <div class="absolute -bottom-12 -left-8 w-48 h-48 rounded-full opacity-10"
         style="background: radial-gradient(circle, #bae6fd 0%, transparent 70%)"></div>
    <div class="absolute top-4 right-40 w-24 h-24 rounded-full opacity-[0.07]"
         style="background: radial-gradient(circle, #fff 0%, transparent 70%)"></div>

    <!-- Dots grid decoration -->
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 22px 22px;"></div>

    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5 px-7 py-6">

        <!-- Left: greeting -->
        <div class="flex items-center gap-4">
            <!-- Avatar -->
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-bold flex-shrink-0 shadow-md"
                 style="background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); border: 1.5px solid rgba(255,255,255,0.25)">
                <?= strtoupper(substr($nama, 0, 1)) ?>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <i class="fas <?= $greetIcon ?> text-amber-300 text-sm"></i>
                    <span class="text-blue-200 text-[12px] font-medium tracking-wide"><?= $greeting ?></span>
                </div>
                <h2 class="text-white text-[22px] font-bold leading-tight">
                    <?= esc($nama) ?> <span class="wave inline-block">👋</span>
                </h2>
                <p class="text-blue-200 text-[12px] mt-1 flex items-center gap-1.5">
                    <i class="fas fa-calendar-day text-[11px]"></i>
                    <?= $tanggal ?>
                </p>
            </div>
        </div>

        <!-- Right: quick actions -->
        <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
            <a href="<?= base_url('penjualan/create') ?>"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[12.5px] font-semibold text-blue-700 transition-all hover:scale-105 active:scale-95 shadow-sm"
               style="background: rgba(255,255,255,0.95)">
                <i class="fas fa-cash-register text-blue-500 text-xs"></i>
                Transaksi Baru
            </a>
            <a href="<?= base_url('stok-masuk/create') ?>"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[12.5px] font-semibold transition-all hover:scale-105 active:scale-95 shadow-sm"
               style="background: rgba(255,255,255,0.15); color:#e0f2fe; border: 1.5px solid rgba(255,255,255,0.2); backdrop-filter:blur(4px)">
                <i class="fas fa-boxes-stacked text-[11px]"></i>
                Input Stok
            </a>
            <a href="<?= base_url('laporan') ?>"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-[12.5px] font-semibold transition-all hover:scale-105 active:scale-95 shadow-sm"
               style="background: rgba(255,255,255,0.15); color:#e0f2fe; border: 1.5px solid rgba(255,255,255,0.2); backdrop-filter:blur(4px)">
                <i class="fas fa-chart-bar text-[11px]"></i>
                Laporan
            </a>
        </div>
    </div>
</div>

<style>
.wave { animation: wave-hand 2.2s ease-in-out infinite; transform-origin: 70% 70%; display: inline-block; }
@keyframes wave-hand {
    0%,100% { transform: rotate(0deg); }
    15%      { transform: rotate(14deg); }
    30%      { transform: rotate(-8deg); }
    45%      { transform: rotate(14deg); }
    60%      { transform: rotate(-4deg); }
    75%      { transform: rotate(10deg); }
}
</style>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <!-- Penjualan Hari Ini -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 card-lift shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                <i class="fas fa-cash-register text-white text-sm"></i>
            </div>
            <span class="badge bg-indigo-50 text-indigo-600"><?= $transaksi_hari_ini ?> transaksi</span>
        </div>
        <p class="text-[22px] font-bold text-slate-800 leading-tight">Rp <?= number_format($penjualan_hari_ini, 0, ',', '.') ?></p>
        <p class="text-xs text-slate-400 mt-1 font-medium">Penjualan Hari Ini</p>
    </div>

    <!-- Keuntungan Hari Ini -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 card-lift shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:linear-gradient(135deg,#10b981,#34d399)">
                <i class="fas fa-arrow-trend-up text-white text-sm"></i>
            </div>
            <?php
                $margin = ($penjualan_hari_ini > 0)
                    ? round(($keuntungan_hari_ini / $penjualan_hari_ini) * 100, 1)
                    : 0;
            ?>
            <span class="badge bg-emerald-50 text-emerald-600"><?= $margin ?>% margin</span>
        </div>
        <p class="text-[22px] font-bold text-emerald-600 leading-tight">Rp <?= number_format($keuntungan_hari_ini, 0, ',', '.') ?></p>
        <p class="text-xs text-slate-400 mt-1 font-medium">Keuntungan Hari Ini</p>
    </div>

    <!-- Jenis Barang -->
    <div class="bg-white rounded-2xl p-5 border border-slate-100 card-lift shadow-sm">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:linear-gradient(135deg,#8b5cf6,#a78bfa)">
                <i class="fas fa-cube text-white text-sm"></i>
            </div>
            <span class="badge bg-violet-50 text-violet-600">SKU</span>
        </div>
        <p class="text-[22px] font-bold text-slate-800 leading-tight"><?= number_format($total_barang) ?></p>
        <p class="text-xs text-slate-400 mt-1 font-medium">Jenis Barang</p>
    </div>

    <!-- Stok Menipis -->
    <div class="rounded-2xl p-5 card-lift shadow-sm <?= $stok_minimum > 0 ? 'bg-rose-500 border border-rose-400' : 'bg-white border border-slate-100' ?>">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center <?= $stok_minimum > 0 ? 'bg-white/20' : 'bg-orange-50' ?>">
                <i class="fas fa-triangle-exclamation text-sm <?= $stok_minimum > 0 ? 'text-white' : 'text-orange-500' ?>"></i>
            </div>
            <?php if($stok_minimum > 0): ?>
            <span class="badge bg-white/20 text-white">Perlu Restock</span>
            <?php endif; ?>
        </div>
        <p class="text-[22px] font-bold leading-tight <?= $stok_minimum > 0 ? 'text-white' : 'text-slate-800' ?>"><?= number_format($stok_minimum) ?></p>
        <p class="text-xs mt-1 font-medium <?= $stok_minimum > 0 ? 'text-rose-100' : 'text-slate-400' ?>">Stok Menipis</p>
    </div>
</div>

<!-- ===== CHARTS ROW ===== -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-4">

    <!-- Grafik Penjualan Harian (line) -->
    <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-1.5 h-5 bg-blue-500 rounded-full"></div>
                <h3 class="font-semibold text-slate-800 text-[14px]">Tren Penjualan & Keuntungan</h3>
            </div>
            <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1" id="chart-mode-btns">
                <button onclick="switchChartMode('7d')" id="btn-7d"
                        class="text-[11px] font-semibold px-3 py-1 rounded-md transition bg-white shadow text-slate-800">7 Hari</button>
                <button onclick="switchChartMode('30d')" id="btn-30d"
                        class="text-[11px] font-semibold px-3 py-1 rounded-md transition text-slate-400 hover:text-slate-600">30 Hari</button>
            </div>
        </div>
        <div class="p-4" style="height:230px; position:relative">
            <canvas id="chartHarian"></canvas>
        </div>
    </div>

    <!-- Grafik Kategori (donut) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
            <div class="w-1.5 h-5 bg-purple-500 rounded-full"></div>
            <h3 class="font-semibold text-slate-800 text-[14px]">Penjualan per Kategori</h3>
        </div>
        <div class="p-4 flex items-center justify-center" style="height:230px; position:relative">
            <canvas id="chartKategori"></canvas>
        </div>
    </div>
</div>

<!-- Grafik Tren Bulanan (bar) -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-4">
    <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
        <div class="w-1.5 h-5 bg-emerald-500 rounded-full"></div>
        <h3 class="font-semibold text-slate-800 text-[14px]">Perbandingan Penjualan & Keuntungan Bulanan</h3>
        <span class="text-[11px] text-slate-400 ml-1">(12 bulan terakhir)</span>
    </div>
    <div class="p-4" style="height:220px; position:relative">
        <canvas id="chartBulanan"></canvas>
    </div>
</div>

<!-- Row 2 -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-4">

    <!-- Transaksi Terbaru -->
    <div class="xl:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-1.5 h-5 bg-indigo-500 rounded-full"></div>
                <h3 class="font-semibold text-slate-800 text-[14px]">Transaksi Terbaru</h3>
            </div>
            <a href="<?= base_url('penjualan') ?>"
               class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                Lihat semua <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <?php if (empty($transaksi_terbaru)): ?>
        <div class="flex flex-col items-center justify-center py-14 text-slate-300">
            <i class="fas fa-receipt text-4xl mb-3"></i>
            <p class="text-sm text-slate-400">Belum ada transaksi</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-slate-50">
            <?php foreach ($transaksi_terbaru as $trx): ?>
            <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-receipt text-indigo-500 text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-slate-800 font-mono"><?= esc($trx['no_transaksi']) ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5"><?= esc($trx['nama_pembeli'] ?? 'Umum') ?> &bull; <?= date('d M Y', strtotime($trx['tanggal_jual'])) ?></p>
                </div>
                <span class="text-[13px] font-bold text-slate-700">Rp <?= number_format($trx['total_harga'], 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Barang Terlaris -->
    <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
            <div class="w-1.5 h-5 bg-violet-500 rounded-full"></div>
            <h3 class="font-semibold text-slate-800 text-[14px]">Barang Terlaris</h3>
        </div>

        <?php if (empty($barang_terlaris)): ?>
        <div class="flex flex-col items-center justify-center py-14 text-slate-300">
            <i class="fas fa-chart-bar text-4xl mb-3"></i>
            <p class="text-sm text-slate-400">Belum ada data</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-slate-50">
            <?php
            $rankColors = ['bg-amber-400','bg-slate-300','bg-orange-400','bg-slate-200','bg-slate-200'];
            foreach ($barang_terlaris as $i => $brg):
            ?>
            <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition">
                <span class="w-6 h-6 rounded-full text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 <?= $rankColors[$i] ?? 'bg-slate-200' ?>">
                    <?= $i + 1 ?>
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-slate-800 truncate"><?= esc($brg['nama_barang']) ?></p>
                    <p class="text-[11px] text-slate-400 mt-0.5"><?= number_format($brg['total_terjual']) ?> <?= esc($brg['satuan']) ?> terjual</p>
                </div>
                <span class="text-[12px] font-bold text-emerald-600 flex-shrink-0">Rp <?= number_format($brg['total_pendapatan'], 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Stok Menipis Alert -->
<?php if (!empty($stok_menipis)): ?>
<div class="bg-white rounded-2xl border border-rose-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 bg-rose-50 border-b border-rose-100">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-rose-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-triangle-exclamation text-white text-xs"></i>
            </div>
            <div>
                <h3 class="font-semibold text-rose-800 text-[14px]">Peringatan Stok Menipis</h3>
                <p class="text-[11px] text-rose-500"><?= count($stok_menipis) ?> barang perlu segera direstock</p>
            </div>
        </div>
        <a href="<?= base_url('stok-masuk/create') ?>" class="btn-primary text-xs py-2 px-4" style="background:#ef4444">
            <i class="fas fa-plus text-xs"></i> Tambah Stok
        </a>
    </div>
    <table class="tbl w-full">
        <thead>
            <tr>
                <th class="text-left">Nama Barang</th>
                <th class="text-left">Kategori</th>
                <th class="text-center">Stok Saat Ini</th>
                <th class="text-center">Minimum</th>
                <th class="text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stok_menipis as $brg): ?>
            <tr>
                <td class="font-semibold text-slate-800"><?= esc($brg['nama_barang']) ?></td>
                <td><span class="badge bg-slate-100 text-slate-600"><?= esc($brg['nama_kategori'] ?? '-') ?></span></td>
                <td class="text-center">
                    <span class="badge bg-rose-100 text-rose-700">
                        <?= number_format($brg['stok']) ?> <?= esc($brg['satuan']) ?>
                    </span>
                </td>
                <td class="text-center text-slate-500 font-medium"><?= number_format($brg['stok_minimum']) ?></td>
                <td>
                    <a href="<?= base_url('stok-masuk/create') ?>"
                       class="text-[12px] text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                        Restock <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<script>
(function () {
    var chartHarian   = null;
    var chartKategori = null;
    var chartBulanan  = null;
    var currentMode   = '7d';

    var CHART_API = '<?= base_url('api/dashboard/chart') ?>';

    var rupiah = function (v) {
        if (v >= 1000000) return 'Rp ' + (v / 1000000).toFixed(1) + 'jt';
        if (v >= 1000)    return 'Rp ' + (v / 1000).toFixed(0) + 'rb';
        return 'Rp ' + v;
    };

    function loadCharts(mode) {
        fetch(CHART_API + '?mode=' + mode)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                renderHarian(data.harian);
                renderKategori(data.kategori);
                renderBulanan(data.bulanan);
            })
            .catch(function (e) { console.error('Chart API error:', e); });
    }

    function renderHarian(d) {
        var ctx = document.getElementById('chartHarian');
        if (!ctx) return;
        if (chartHarian) chartHarian.destroy();
        chartHarian = new Chart(ctx, {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [
                    {
                        label: 'Penjualan',
                        data: d.totals,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79,70,229,.09)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: .4,
                        pointBackgroundColor: '#4f46e5',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    },
                    {
                        label: 'Keuntungan',
                        data: d.profits,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,.07)',
                        borderWidth: 2,
                        fill: true,
                        tension: .4,
                        borderDash: [5, 3],
                        pointBackgroundColor: '#10b981',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11 }, usePointStyle: true, padding: 16 } },
                    tooltip: { callbacks: { label: function (c) { return ' ' + rupiah(c.raw); } } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: rupiah } }
                }
            }
        });
    }

    function renderKategori(d) {
        var ctx = document.getElementById('chartKategori');
        if (!ctx) return;
        if (chartKategori) chartKategori.destroy();

        var hasData = d.totals.some(function (v) { return v > 0; });
        var colors  = ['#4f46e5','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4'];

        chartKategori = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: hasData ? d.labels : ['Belum ada data'],
                datasets: [{
                    data: hasData ? d.totals : [1],
                    backgroundColor: hasData ? colors : ['#e2e8f0'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 10 }, usePointStyle: true, padding: 10 } },
                    tooltip: { callbacks: { label: function (c) { return hasData ? ' ' + rupiah(c.raw) : ' Belum ada data'; } } }
                }
            }
        });
    }

    function renderBulanan(d) {
        var ctx = document.getElementById('chartBulanan');
        if (!ctx) return;
        if (chartBulanan) chartBulanan.destroy();
        chartBulanan = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    {
                        label: 'Penjualan',
                        data: d.totals,
                        backgroundColor: 'rgba(79,70,229,.75)',
                        borderRadius: 5,
                        borderSkipped: false,
                    },
                    {
                        label: 'Keuntungan',
                        data: d.profits,
                        backgroundColor: 'rgba(16,185,129,.75)',
                        borderRadius: 5,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11 }, usePointStyle: true, padding: 16 } },
                    tooltip: { callbacks: { label: function (c) { return ' ' + rupiah(c.raw); } } }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: rupiah } }
                }
            }
        });
    }

    // Toggle 7 hari / 30 hari
    window.switchChartMode = function (mode) {
        currentMode = mode;
        document.getElementById('btn-7d').className  = 'text-[11px] font-semibold px-3 py-1 rounded-md transition ' + (mode === '7d'  ? 'bg-white shadow text-slate-800' : 'text-slate-400 hover:text-slate-600');
        document.getElementById('btn-30d').className = 'text-[11px] font-semibold px-3 py-1 rounded-md transition ' + (mode === '30d' ? 'bg-white shadow text-slate-800' : 'text-slate-400 hover:text-slate-600');
        loadCharts(mode);
    };

    // Canvas sudah ada di DOM saat script ini dieksekusi, panggil langsung
    loadCharts(currentMode);
})();
</script>

<?= $this->endSection() ?>
