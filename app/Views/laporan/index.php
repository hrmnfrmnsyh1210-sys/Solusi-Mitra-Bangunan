<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <p class="text-slate-500 text-sm">Pilih jenis laporan yang ingin dicetak atau diexport.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">

    <!-- Laporan Penjualan -->
    <a href="<?= base_url('laporan/penjualan') ?>"
       class="card-lift bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col gap-4 hover:border-indigo-200 transition">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
            <i class="fas fa-cash-register text-white text-lg"></i>
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-base">Laporan Penjualan</h3>
            <p class="text-slate-500 text-sm mt-1">Rekap transaksi penjualan berdasarkan periode tanggal.</p>
        </div>
        <div class="flex gap-2 mt-auto">
            <span class="badge bg-rose-100 text-rose-600"><i class="fas fa-file-pdf mr-1 text-xs"></i>PDF</span>
            <span class="badge bg-emerald-100 text-emerald-700"><i class="fas fa-file-excel mr-1 text-xs"></i>Excel</span>
        </div>
    </a>

    <!-- Laporan Stok -->
    <a href="<?= base_url('laporan/stok') ?>"
       class="card-lift bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col gap-4 hover:border-indigo-200 transition">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#0ea5e9,#6366f1)">
            <i class="fas fa-boxes-stacked text-white text-lg"></i>
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-base">Laporan Stok Barang</h3>
            <p class="text-slate-500 text-sm mt-1">Kondisi stok seluruh barang per hari ini, bisa filter per kategori.</p>
        </div>
        <div class="flex gap-2 mt-auto">
            <span class="badge bg-rose-100 text-rose-600"><i class="fas fa-file-pdf mr-1 text-xs"></i>PDF</span>
            <span class="badge bg-emerald-100 text-emerald-700"><i class="fas fa-file-excel mr-1 text-xs"></i>Excel</span>
        </div>
    </a>

    <!-- Laporan Barang Masuk -->
    <a href="<?= base_url('laporan/barang-masuk') ?>"
       class="card-lift bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col gap-4 hover:border-indigo-200 transition">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#10b981,#0ea5e9)">
            <i class="fas fa-download text-white text-lg"></i>
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-base">Laporan Barang Masuk</h3>
            <p class="text-slate-500 text-sm mt-1">Rekap penerimaan barang dari supplier berdasarkan periode.</p>
        </div>
        <div class="flex gap-2 mt-auto">
            <span class="badge bg-rose-100 text-rose-600"><i class="fas fa-file-pdf mr-1 text-xs"></i>PDF</span>
            <span class="badge bg-emerald-100 text-emerald-700"><i class="fas fa-file-excel mr-1 text-xs"></i>Excel</span>
        </div>
    </a>

</div>

<?= $this->endSection() ?>
