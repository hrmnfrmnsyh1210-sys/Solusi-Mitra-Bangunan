<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-md">
    <div class="flex items-center gap-2 text-[12px] text-slate-400 mb-4">
        <a href="<?= base_url('kategori') ?>" class="hover:text-indigo-600 transition">Kategori</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-medium"><?= $title ?></span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#6366f1,#818cf8)">
                <i class="fas fa-tag text-white text-sm"></i>
            </div>
            <h2 class="font-semibold text-slate-800"><?= $title ?></h2>
        </div>

        <form method="post" action="<?= $kategori ? base_url('kategori/update/'.$kategori['id']) : base_url('kategori/store') ?>" class="px-6 py-5">
            <?= csrf_field() ?>

            <div class="mb-5">
                <label class="block text-[12.5px] font-semibold text-slate-600 mb-1.5">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_kategori"
                       value="<?= old('nama_kategori',$kategori['nama_kategori']??'') ?>"
                       placeholder="Contoh: Material Bangunan" class="inp">
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-floppy-disk text-sm"></i> Simpan
                </button>
                <a href="<?= base_url('kategori') ?>"
                   class="text-[13px] text-slate-500 px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 transition font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
