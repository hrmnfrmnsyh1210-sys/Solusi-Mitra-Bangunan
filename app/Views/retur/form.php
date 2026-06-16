<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="max-w-3xl space-y-5" x-data="returForm()">

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Formulir Retur Penjualan</h2>
            <a href="<?= base_url('retur') ?>" class="text-xs text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <form action="<?= base_url('retur/store') ?>" method="post" class="px-6 py-5 space-y-5">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">No. Retur</label>
                    <input type="text" name="no_retur" value="<?= esc($no_retur) ?>" class="inp" readonly>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Retur</label>
                    <input type="date" name="tanggal_retur" value="<?= date('Y-m-d') ?>" class="inp" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Referensi Transaksi Penjualan <span class="text-slate-400 font-normal">(opsional)</span></label>
                <select name="id_penjualan" class="inp" @change="loadDetail($event.target.value)">
                    <option value="">— Pilih transaksi asal (opsional) —</option>
                    <?php foreach ($penjualans as $p): ?>
                    <option value="<?= $p['id'] ?>">
                        <?= esc($p['no_transaksi']) ?> — <?= date('d M Y', strtotime($p['tanggal_jual'])) ?>
                        <?= $p['nama_pembeli'] !== 'Umum' ? ' (' . esc($p['nama_pembeli']) . ')' : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alasan Retur <span class="text-rose-500">*</span></label>
                <input type="text" name="alasan" class="inp" placeholder="contoh: Barang rusak, ukuran tidak sesuai..." required>
            </div>

            <!-- Tabel Item Retur -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-semibold text-slate-600">Item yang Diretur</label>
                    <button type="button" @click="addRow()"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
                        <i class="fas fa-plus text-[10px]"></i> Tambah Baris
                    </button>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-[13px]">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="text-left px-4 py-2.5 text-[11px] font-semibold text-slate-500 uppercase">Barang</th>
                                <th class="text-center px-4 py-2.5 text-[11px] font-semibold text-slate-500 uppercase w-24">Jumlah</th>
                                <th class="text-right px-4 py-2.5 text-[11px] font-semibold text-slate-500 uppercase w-36">Harga Jual</th>
                                <th class="text-right px-4 py-2.5 text-[11px] font-semibold text-slate-500 uppercase w-36">Subtotal</th>
                                <th class="px-3 py-2.5 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, idx) in rows" :key="idx">
                                <tr class="border-t border-slate-100">
                                    <td class="px-4 py-2">
                                        <select :name="`detail[${idx}][id_barang]`" class="inp py-1.5 text-[13px]"
                                                @change="onBarangChange(idx, $event.target.value)" required>
                                            <option value="">— Pilih barang —</option>
                                            <template x-for="b in barangList" :key="b.id">
                                                <option :value="b.id"
                                                        :selected="row.id_barang == b.id"
                                                        x-text="b.nama_barang + ' (' + b.satuan + ')'"></option>
                                            </template>
                                        </select>
                                        <input type="hidden" :name="`detail[${idx}][harga_jual]`" :value="row.harga_jual">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" :name="`detail[${idx}][jumlah]`"
                                               x-model.number="row.jumlah"
                                               @input="calcSubtotal(idx)"
                                               min="1" class="inp py-1.5 text-center text-[13px]" required>
                                    </td>
                                    <td class="px-4 py-2 text-right text-slate-600 font-mono text-[12px]"
                                        x-text="'Rp ' + Number(row.harga_jual).toLocaleString('id-ID')"></td>
                                    <td class="px-4 py-2 text-right font-semibold text-slate-800 font-mono text-[12px]"
                                        x-text="'Rp ' + Number(row.subtotal).toLocaleString('id-ID')"></td>
                                    <td class="px-3 py-2">
                                        <button type="button" @click="removeRow(idx)" x-show="rows.length > 1"
                                                class="w-6 h-6 rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-100 flex items-center justify-center">
                                            <i class="fas fa-times text-[10px]"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200">
                            <tr>
                                <td colspan="3" class="px-4 py-2.5 text-right text-xs font-semibold text-slate-600 uppercase">Total Nilai Retur</td>
                                <td class="px-4 py-2.5 text-right font-bold text-rose-600 font-mono"
                                    x-text="'Rp ' + totalRetur.toLocaleString('id-ID')"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan <span class="text-slate-400 font-normal">(opsional)</span></label>
                <textarea name="keterangan" rows="2" class="inp" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="<?= base_url('retur') ?>" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="btn-primary" style="background:#ef4444">
                    <i class="fas fa-rotate-left text-xs"></i> Simpan Retur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const barangData = <?= json_encode((new \App\Models\BarangModel())->select('id,nama_barang,satuan,harga_jual,stok')->orderBy('nama_barang')->findAll()) ?>;

function returForm() {
    return {
        barangList: barangData,
        rows: [{ id_barang: '', jumlah: 1, harga_jual: 0, subtotal: 0 }],
        get totalRetur() {
            return this.rows.reduce((s, r) => s + Number(r.subtotal), 0);
        },
        addRow() {
            this.rows.push({ id_barang: '', jumlah: 1, harga_jual: 0, subtotal: 0 });
        },
        removeRow(idx) {
            this.rows.splice(idx, 1);
        },
        onBarangChange(idx, id) {
            const b = barangData.find(x => x.id == id);
            if (b) {
                this.rows[idx].id_barang  = id;
                this.rows[idx].harga_jual = parseFloat(b.harga_jual);
                this.calcSubtotal(idx);
            }
        },
        calcSubtotal(idx) {
            const r = this.rows[idx];
            r.subtotal = r.jumlah * r.harga_jual;
        },
        loadDetail(id_penjualan) {
            if (!id_penjualan) return;
            fetch(`<?= base_url('api/retur/detail/') ?>${id_penjualan}`)
                .then(r => r.json())
                .then(data => {
                    if (data.length > 0) {
                        this.rows = data.map(d => ({
                            id_barang:  d.id_barang,
                            jumlah:     0,
                            harga_jual: parseFloat(d.harga_jual),
                            subtotal:   0,
                        }));
                    }
                });
        }
    };
}
</script>

<?= $this->endSection() ?>
