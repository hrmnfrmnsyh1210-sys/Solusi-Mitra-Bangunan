<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-800 text-[15px]">Data Supplier</h2>
            <p class="text-[12px] text-slate-400 mt-0.5"><?= count($suppliers) ?> supplier terdaftar</p>
        </div>
        <a href="<?= base_url('supplier/create') ?>" class="btn-primary">
            <i class="fas fa-plus text-xs"></i> Tambah Supplier
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="tbl w-full">
            <thead>
                <tr>
                    <th class="text-left w-10">#</th>
                    <th class="text-left">Nama Supplier</th>
                    <th class="text-left">No. HP / Telp</th>
                    <th class="text-left">Alamat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($suppliers)): ?>
                <tr><td colspan="5" class="py-16 text-center">
                    <i class="fas fa-truck text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">Belum ada data supplier</p>
                </td></tr>
                <?php else: ?>
                <?php foreach($suppliers as $i => $s): ?>
                <tr>
                    <td class="text-slate-400 text-[12px]"><?= $i+1 ?></td>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <span class="text-emerald-600 text-xs font-bold"><?= strtoupper(substr($s['nama_supplier'],0,1)) ?></span>
                            </div>
                            <span class="font-semibold text-slate-800 text-[13.5px]"><?= esc($s['nama_supplier']) ?></span>
                        </div>
                    </td>
                    <td class="text-slate-600 text-[13px]"><?= esc($s['no_hp'] ?? '—') ?></td>
                    <td class="text-slate-500 text-[13px] max-w-[220px] truncate"><?= esc($s['alamat'] ?? '—') ?></td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="<?= base_url('supplier/edit/'.$s['id']) ?>"
                               class="w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button onclick="confirmDelete('<?= base_url('supplier/delete/'.$s['id']) ?>','Hapus supplier ini?')"
                               class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-500 flex items-center justify-center transition">
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
</div>

<?= $this->endSection() ?>
