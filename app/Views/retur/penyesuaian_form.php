<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl" x-data="penyesuaianForm()">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sliders text-amber-600 text-sm"></i>
                </div>
                <h2 class="font-semibold text-slate-800">Penyesuaian Stok (Stock Opname)</h2>
            </div>
            <a href="<?= base_url('retur/penyesuaian') ?>" class="text-xs text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="<?= base_url('retur/penyesuaian/store') ?>" method="post" class="px-6 py-5 space-y-5">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">No. Penyesuaian</label>
                    <input type="text" name="no_penyesuaian" value="<?= esc($no_penyesuaian) ?>" class="inp" readonly>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" class="inp" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Barang <span class="text-rose-500">*</span></label>
                <select name="id_barang" class="inp" @change="onBarangChange($event.target.value)" required>
                    <option value="">— Pilih barang —</option>
                    <?php foreach ($barangs as $b): ?>
                    <option value="<?= $b['id'] ?>"
                            data-stok="<?= $b['stok'] ?>"
                            data-satuan="<?= esc($b['satuan']) ?>"
                            data-nama="<?= esc($b['nama_barang']) ?>">
                        <?= esc($b['nama_barang']) ?> (<?= esc($b['nama_kategori'] ?? 'Umum') ?>) — Stok: <?= number_format($b['stok']) ?> <?= esc($b['satuan']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Info stok saat ini -->
            <div x-show="selectedBarang" x-cloak class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                <p class="text-xs text-slate-500 mb-1">Stok saat ini di sistem</p>
                <p class="text-2xl font-bold text-slate-800" x-text="stokSekarang + ' ' + satuan"></p>
            </div>

            <div x-show="selectedBarang" x-cloak>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Stok Aktual (hasil hitung fisik) <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="stok_sesudah" x-model.number="stokSesudah"
                       @input="calcSelisih()"
                       min="0" class="inp" placeholder="Masukkan jumlah stok aktual" required>
            </div>

            <!-- Preview selisih -->
            <div x-show="selectedBarang && stokSesudah !== ''" x-cloak
                 :class="selisih > 0 ? 'bg-emerald-50 border-emerald-200' : (selisih < 0 ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-200')"
                 class="rounded-xl p-4 border">
                <p class="text-xs font-semibold mb-1"
                   :class="selisih > 0 ? 'text-emerald-600' : (selisih < 0 ? 'text-rose-600' : 'text-slate-500')">
                    Selisih Stok
                </p>
                <p class="text-xl font-bold"
                   :class="selisih > 0 ? 'text-emerald-700' : (selisih < 0 ? 'text-rose-700' : 'text-slate-600')"
                   x-text="(selisih > 0 ? '+' : '') + selisih + ' ' + satuan"></p>
                <p class="text-xs mt-1"
                   :class="selisih > 0 ? 'text-emerald-500' : (selisih < 0 ? 'text-rose-500' : 'text-slate-400')"
                   x-text="selisih > 0 ? 'Stok akan bertambah' : (selisih < 0 ? 'Stok akan berkurang' : 'Tidak ada perubahan')"></p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alasan Penyesuaian <span class="text-rose-500">*</span></label>
                <select name="alasan" class="inp" required>
                    <option value="">— Pilih alasan —</option>
                    <option value="Stock opname rutin">Stock opname rutin</option>
                    <option value="Barang rusak/cacat">Barang rusak/cacat</option>
                    <option value="Barang hilang">Barang hilang</option>
                    <option value="Kesalahan input sebelumnya">Kesalahan input sebelumnya</option>
                    <option value="Penyusutan alami">Penyusutan alami</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan <span class="text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="keterangan" rows="2" class="inp" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="<?= base_url('retur/penyesuaian') ?>" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="btn-primary" style="background:#d97706" :disabled="!selectedBarang">
                    <i class="fas fa-check text-xs"></i> Simpan Penyesuaian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function penyesuaianForm() {
    return {
        selectedBarang: null,
        stokSekarang: 0,
        stokSesudah: '',
        selisih: 0,
        satuan: '',
        onBarangChange(id) {
            const opt = document.querySelector(`select[name="id_barang"] option[value="${id}"]`);
            if (opt) {
                this.selectedBarang = id;
                this.stokSekarang   = parseInt(opt.dataset.stok) || 0;
                this.satuan         = opt.dataset.satuan || '';
                this.stokSesudah    = '';
                this.selisih        = 0;
            } else {
                this.selectedBarang = null;
            }
        },
        calcSelisih() {
            this.selisih = parseInt(this.stokSesudah || 0) - this.stokSekarang;
        }
    };
}
</script>

<?= $this->endSection() ?>
