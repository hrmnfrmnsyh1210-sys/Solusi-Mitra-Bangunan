<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-xl">
    <div class="flex items-center gap-2 text-[12px] text-slate-400 mb-4">
        <a href="<?= base_url('supplier') ?>" class="hover:text-indigo-600 transition">Supplier</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-medium"><?= $title ?></span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#10b981,#34d399)">
                <i class="fas fa-truck text-white text-sm"></i>
            </div>
            <h2 class="font-semibold text-slate-800"><?= $title ?></h2>
        </div>

        <form method="post" action="<?= $supplier ? base_url('supplier/update/'.$supplier['id']) : base_url('supplier/store') ?>" class="px-6 py-5 space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-[12.5px] font-semibold text-slate-600 mb-1.5">Nama Supplier <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_supplier"
                       value="<?= old('nama_supplier',$supplier['nama_supplier']??'') ?>"
                       placeholder="Contoh: PT Semen Indonesia" class="inp">
            </div>
            <div>
                <label class="block text-[12.5px] font-semibold text-slate-600 mb-1.5">No. HP / Telepon</label>
                <input type="text" name="no_hp"
                       value="<?= old('no_hp',$supplier['no_hp']??'') ?>"
                       placeholder="0812-3456-7890" class="inp">
            </div>
            <div>
                <label class="block text-[12.5px] font-semibold text-slate-600 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="3" placeholder="Alamat lengkap supplier..."
                          class="inp resize-none"><?= old('alamat',$supplier['alamat']??'') ?></textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-floppy-disk text-sm"></i> Simpan
                </button>
                <a href="<?= base_url('supplier') ?>"
                   class="text-[13px] text-slate-500 px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 transition font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
