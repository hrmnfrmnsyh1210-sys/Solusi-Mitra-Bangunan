<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div>
                <h2 class="font-semibold text-slate-800 text-[15px]">Data Kategori</h2>
                <p class="text-[12px] text-slate-400 mt-0.5"><?= count($kategoris) ?> kategori terdaftar</p>
            </div>
            <a href="<?= base_url('kategori/create') ?>" class="btn-primary">
                <i class="fas fa-plus text-xs"></i> Tambah
            </a>
        </div>

        <table class="tbl w-full">
            <thead>
                <tr>
                    <th class="text-left w-10">#</th>
                    <th class="text-left">Nama Kategori</th>
                    <th class="text-left">Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($kategoris)): ?>
                <tr><td colspan="4" class="py-14 text-center">
                    <i class="fas fa-tag text-4xl text-slate-200 block mb-3"></i>
                    <p class="text-slate-400 text-sm">Belum ada kategori</p>
                </td></tr>
                <?php else: ?>
                <?php foreach($kategoris as $i => $k): ?>
                <tr>
                    <td class="text-slate-400 text-[12px]"><?= $i+1 ?></td>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                            <span class="font-semibold text-slate-800 text-[13.5px]"><?= esc($k['nama_kategori']) ?></span>
                        </div>
                    </td>
                    <td class="text-slate-400 text-[12.5px]"><?= date('d M Y', strtotime($k['created_at'])) ?></td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="<?= base_url('kategori/edit/'.$k['id']) ?>"
                               class="w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button onclick="confirmDelete('<?= base_url('kategori/delete/'.$k['id']) ?>','Hapus kategori ini?')"
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
