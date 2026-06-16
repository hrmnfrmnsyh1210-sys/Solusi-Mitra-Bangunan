<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div x-data="penjualanForm()" class="max-w-4xl">
    <div class="flex items-center gap-2 text-[12px] text-slate-400 mb-4">
        <a href="<?= base_url('penjualan') ?>" class="hover:text-indigo-600 transition">Penjualan</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-medium">Transaksi Baru</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#10b981,#34d399)">
                <i class="fas fa-cash-register text-white text-sm"></i>
            </div>
            <h2 class="font-semibold text-slate-800">Form Transaksi Penjualan</h2>
        </div>

        <form method="post" action="<?= base_url('penjualan/store') ?>" class="px-6 py-5">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                <div>
                    <label class="block text-[12.5px] font-semibold text-slate-600 mb-1.5">No. Transaksi</label>
                    <input type="text" name="no_transaksi" value="<?= esc($no_transaksi) ?>" class="inp font-mono bg-slate-50">
                </div>
                <div>
                    <label class="block text-[12.5px] font-semibold text-slate-600 mb-1.5">Tanggal Jual <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_jual" value="<?= date('Y-m-d') ?>" class="inp">
                </div>
                <div>
                    <label class="block text-[12.5px] font-semibold text-slate-600 mb-1.5">Nama Pembeli</label>
                    <input type="text" name="nama_pembeli" placeholder="Kosongkan jika umum" class="inp">
                </div>
            </div>

            <!-- Tabel Barang -->
            <div class="border border-slate-200 rounded-xl overflow-hidden mb-5">
                <div class="flex items-center justify-between bg-slate-50 px-4 py-3 border-b border-slate-200">
                    <span class="text-[13px] font-semibold text-slate-700">Daftar Barang</span>
                    <button type="button" @click="tambahBaris()" class="btn-primary text-xs py-1.5 px-3">
                        <i class="fas fa-plus text-xs"></i> Tambah Baris
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="text-left px-4 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Barang</th>
                                <th class="text-center px-4 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-28">Jumlah</th>
                                <th class="text-right px-4 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-44">Harga Jual</th>
                                <th class="text-right px-4 py-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wide w-44">Subtotal</th>
                                <th class="w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-b border-slate-100">
                                    <td class="px-4 py-2.5">
                                        <!-- Searchable Dropdown Trigger -->
                                        <div class="inp py-2 text-[13px] flex items-center cursor-pointer gap-2 select-none"
                                             @click.stop="toggleDropdown(index, $el)">
                                            <span class="flex-1 truncate"
                                                  :class="item.id_barang ? 'text-slate-800' : 'text-slate-400'"
                                                  x-text="item.id_barang ? item.nama_barang : '-- Pilih Barang --'"></span>
                                            <i class="fas fa-chevron-down text-[10px] text-slate-400 shrink-0 transition-transform duration-200"
                                               :class="item.dropdownOpen ? 'rotate-180' : ''"></i>
                                        </div>
                                        <input type="hidden" :name="`detail[${index}][id_barang]`" :value="item.id_barang">
                                        <p class="text-[11px] text-emerald-600 mt-1 pl-1 font-medium" x-show="item.stok>0">
                                            Stok: <span x-text="item.stok+' '+item.satuan"></span>
                                        </p>
                                        <!-- Dropdown Panel (teleported to body agar tidak terpotong overflow) -->
                                        <template x-teleport="body">
                                            <div x-show="item.dropdownOpen"
                                                 @click.outside="item.dropdownOpen = false; item.searchText = ''"
                                                 :style="`top:${item.dropTop}px; left:${item.dropLeft}px; width:${item.dropWidth}px`"
                                                 class="fixed z-[9999] bg-white border border-slate-200 rounded-xl shadow-2xl overflow-hidden"
                                                 style="min-width:240px">
                                                <div class="p-2 border-b border-slate-100 bg-slate-50">
                                                    <div class="relative">
                                                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                                                        <input type="text" x-model="item.searchText"
                                                               placeholder="Ketik nama barang..."
                                                               class="w-full pl-8 pr-3 py-1.5 text-[12px] border border-slate-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300"
                                                               @click.stop
                                                               x-effect="item.dropdownOpen && $nextTick(() => $el.focus())">
                                                    </div>
                                                </div>
                                                <ul class="max-h-52 overflow-y-auto">
                                                    <template x-for="b in barangs.filter(b => b.nama.toLowerCase().includes((item.searchText||'').toLowerCase()))" :key="b.id">
                                                        <li @click="selectBarang(index, b)"
                                                            class="px-4 py-2.5 cursor-pointer text-[12px] flex items-center justify-between border-b border-slate-50 last:border-0 transition-colors"
                                                            :class="item.id_barang == b.id ? 'bg-emerald-50' : 'hover:bg-slate-50'">
                                                            <span x-text="b.nama" class="font-medium truncate mr-3"
                                                                  :class="item.id_barang == b.id ? 'text-emerald-700' : 'text-slate-700'"></span>
                                                            <span x-text="b.stok + ' ' + b.satuan"
                                                                  class="text-slate-400 text-[11px] shrink-0 tabular-nums"></span>
                                                        </li>
                                                    </template>
                                                    <li x-show="barangs.filter(b => b.nama.toLowerCase().includes((item.searchText||'').toLowerCase())).length === 0"
                                                        class="px-4 py-4 text-[12px] text-slate-400 text-center italic">
                                                        <i class="fas fa-search mr-1 text-[10px]"></i> Barang tidak ditemukan
                                                    </li>
                                                </ul>
                                            </div>
                                        </template>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <input type="number" :name="`detail[${index}][jumlah]`"
                                               x-model.number="item.jumlah" @input="hitungSubtotal(index)"
                                               :max="item.stok" min="1" placeholder="0"
                                               class="inp py-2 text-center text-[13px]">
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">Rp</span>
                                            <input type="number" :name="`detail[${index}][harga_jual]`"
                                                   x-model.number="item.harga_jual" @input="hitungSubtotal(index)"
                                                   min="0" placeholder="0"
                                                   class="inp py-2 pl-8 text-right text-[13px]">
                                        </div>
                                    </td>
                                    <td class="px-4 py-2.5 text-right font-bold text-slate-700 text-[13px]"
                                        x-text="'Rp '+fmt(item.subtotal)"></td>
                                    <td class="px-4 py-2.5 text-center">
                                        <button type="button" @click="hapusBaris(index)"
                                                class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-400 hover:text-rose-600 flex items-center justify-center transition mx-auto">
                                            <i class="fas fa-xmark text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="bg-emerald-50 border-t-2 border-emerald-100">
                                <td colspan="3" class="px-4 py-3 text-right text-[13px] font-semibold text-slate-600">Total Belanja:</td>
                                <td class="px-4 py-3 text-right font-bold text-[15px] text-emerald-700" x-text="'Rp '+fmt(total)"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Bayar & Kembalian -->
            <div class="grid grid-cols-3 gap-4 p-5 bg-slate-50 rounded-xl border border-slate-200 mb-5">
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Total</p>
                    <p class="text-[20px] font-bold text-slate-800" x-text="'Rp '+fmt(total)"></p>
                </div>
                <div>
                    <label class="block text-[11.5px] font-semibold text-slate-500 uppercase tracking-wide mb-1">
                        Uang Bayar <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                        <input type="number" name="bayar" x-model.number="bayar" min="0" placeholder="0"
                               class="inp pl-8 text-[15px] font-bold">
                    </div>
                </div>
                <div>
                    <p class="text-[11.5px] font-semibold text-slate-500 uppercase tracking-wide mb-1">Kembalian</p>
                    <p class="text-[20px] font-bold transition-colors"
                       :class="kembalian>=0?'text-emerald-600':'text-rose-600'"
                       x-text="'Rp '+fmt(kembalian)"></p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="btn-primary" style="background:linear-gradient(135deg,#10b981,#059669)">
                    <i class="fas fa-floppy-disk text-sm"></i> Simpan Transaksi
                </button>
                <a href="<?= base_url('penjualan') ?>"
                   class="text-[13px] text-slate-500 px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 transition font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function penjualanForm() {
    return {
        barangs: <?= json_encode(array_map(fn($b) => [
            'id'     => (string)$b['id'],
            'nama'   => $b['nama_barang'],
            'harga'  => (float)$b['harga_jual'],
            'stok'   => (int)$b['stok'],
            'satuan' => $b['satuan'],
        ], $barangs)) ?>,
        items: [{id_barang:'',jumlah:1,harga_jual:0,subtotal:0,stok:0,satuan:'',nama_barang:'',dropdownOpen:false,searchText:'',dropTop:0,dropLeft:0,dropWidth:0}],
        bayar: 0,
        get total() { return this.items.reduce((s,i)=>s+(i.subtotal||0),0); },
        get kembalian() { return this.bayar-this.total; },
        tambahBaris() {
            this.items.push({id_barang:'',jumlah:1,harga_jual:0,subtotal:0,stok:0,satuan:'',nama_barang:'',dropdownOpen:false,searchText:'',dropTop:0,dropLeft:0,dropWidth:0});
        },
        hapusBaris(i) { if(this.items.length>1) this.items.splice(i,1); },
        toggleDropdown(i, el) {
            const isOpen = this.items[i].dropdownOpen;
            this.items.forEach(it => { it.dropdownOpen = false; it.searchText = ''; });
            if (!isOpen) {
                const r = el.getBoundingClientRect();
                const dropH = 280;
                this.items[i].dropTop   = (window.innerHeight - r.bottom > dropH || r.top < dropH) ? r.bottom + 4 : r.top - dropH - 4;
                this.items[i].dropLeft  = r.left;
                this.items[i].dropWidth = r.width;
                this.items[i].dropdownOpen = true;
            }
        },
        selectBarang(i, b) {
            this.items[i].id_barang    = b.id;
            this.items[i].nama_barang  = b.nama;
            this.items[i].harga_jual   = b.harga;
            this.items[i].stok         = b.stok;
            this.items[i].satuan       = b.satuan;
            this.items[i].dropdownOpen = false;
            this.items[i].searchText   = '';
            this.hitungSubtotal(i);
        },
        hitungSubtotal(i) {
            const it = this.items[i];
            it.subtotal = (it.jumlah||0) * (it.harga_jual||0);
        },
        fmt(n) { return new Intl.NumberFormat('id-ID').format(n||0); }
    }
}
</script>

<?= $this->endSection() ?>
