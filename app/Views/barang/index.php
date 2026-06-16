<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-800 text-[15px]">Data Barang</h2>
            <p class="text-[12px] text-slate-400 mt-0.5"><?= number_format($total) ?> barang terdaftar</p>
        </div>
        <a href="<?= base_url('barang/create') ?>" class="btn-primary flex-shrink-0">
            <i class="fas fa-plus text-xs"></i> Tambah
        </a>
    </div>

    <!-- Filter Bar -->
    <form method="get" action="" class="flex flex-wrap items-end gap-3 px-6 py-3 bg-slate-50 border-b border-slate-100">
        <div class="relative flex-1 min-w-[180px]">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="<?= esc($search ?? '') ?>"
                   placeholder="Nama / kode barang..."
                   class="inp pl-8 py-2 text-[13px]">
        </div>
        <select name="kategori" class="inp py-2 text-[13px] w-44">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategoris as $k): ?>
            <option value="<?= $k['id'] ?>" <?= ($kategori ?? '') == $k['id'] ? 'selected' : '' ?>>
                <?= esc($k['nama_kategori']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn-primary py-2 px-4">
            <i class="fas fa-filter text-xs"></i> Filter
        </button>
        <?php if ($search || $kategori): ?>
        <a href="<?= base_url('barang') ?>" class="text-xs text-slate-500 hover:text-slate-700 px-3 py-2 rounded-lg border border-slate-200 hover:bg-white transition">
            <i class="fas fa-times mr-1"></i> Reset
        </a>
        <?php endif; ?>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="tbl w-full">
            <thead>
                <tr>
                    <th class="text-left w-10">#</th>
                    <th class="text-left w-14">Foto</th>
                    <th class="text-left">Kode</th>
                    <th class="text-left">Nama Barang</th>
                    <th class="text-left">Kategori</th>
                    <th class="text-left">Satuan</th>
                    <th class="text-right">Harga Jual</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($barangs)): ?>
                <tr><td colspan="9" class="py-16 text-center">
                    <i class="fas fa-cube text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">
                        <?= ($search || $kategori) ? 'Tidak ada barang yang cocok' : 'Belum ada data barang' ?>
                    </p>
                    <?php if (!$search && !$kategori): ?>
                    <a href="<?= base_url('barang/create') ?>" class="btn-primary mt-4 mx-auto w-fit">Tambah Barang Pertama</a>
                    <?php endif; ?>
                </td></tr>
                <?php else: ?>
                <?php
                $pager     = $pager ?? null;
                $perPage   = 10;
                $offset    = $pager ? ($pager->getCurrentPage() - 1) * $perPage : 0;
                foreach ($barangs as $i => $b):
                ?>
                <tr>
                    <td class="text-slate-400 text-[12px]"><?= $offset + $i + 1 ?></td>
                    <td>
                        <?php if (!empty($b['gambar'])): ?>
                        <img src="<?= base_url('uploads/barang/'.$b['gambar']) ?>"
                             alt="<?= esc($b['nama_barang']) ?>"
                             class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                        <?php else: ?>
                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center">
                            <i class="fas fa-image text-slate-300 text-sm"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><span class="font-mono text-[12px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md"><?= esc($b['kode_barang']) ?></span></td>
                    <td>
                        <p class="font-semibold text-slate-800 text-[13.5px]"><?= esc($b['nama_barang']) ?></p>
                        <?php if ($b['keterangan']): ?>
                        <p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[200px]"><?= esc($b['keterangan']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-indigo-50 text-indigo-700"><?= esc($b['nama_kategori'] ?? 'Umum') ?></span></td>
                    <td class="text-slate-500 text-[13px]"><?= esc($b['satuan']) ?></td>
                    <td class="text-right font-semibold text-slate-800">Rp <?= number_format($b['harga_jual'], 0, ',', '.') ?></td>
                    <td class="text-center">
                        <?php $low = $b['stok_minimum'] > 0 && $b['stok'] <= $b['stok_minimum']; ?>
                        <span class="badge <?= $low ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' ?>">
                            <?= number_format($b['stok']) ?> <?= esc($b['satuan']) ?>
                        </span>
                        <?php if ($low): ?><p class="text-[10px] text-rose-400 mt-0.5 text-center">⚠ Menipis</p><?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="<?= base_url('barang/show/' . $b['id']) ?>"
                               class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition" title="Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="<?= base_url('barang/edit/' . $b['id']) ?>"
                               class="w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button onclick="confirmDelete('<?= base_url('barang/delete/' . $b['id']) ?>','Yakin hapus barang ini?')"
                               class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?= $this->include('layout/pagination') ?>
</div>

<?= $this->endSection() ?>
