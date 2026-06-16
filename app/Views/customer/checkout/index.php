<?= $this->extend('customer/layout/main') ?>
<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="<?= base_url('shop/cart') ?>" class="hover:text-brand-600 transition">Keranjang</a>
    <i class="fas fa-chevron-right text-[10px]"></i>
    <span class="text-slate-700 font-medium">Checkout</span>
</nav>

<!-- Progress steps -->
<div class="flex items-center justify-center gap-3 mb-8">
    <div class="flex items-center gap-2 text-slate-400">
        <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-xs">
            <i class="fas fa-check text-white" style="color:#94a3b8"></i>
        </div>
        <span class="text-xs hidden sm:block">Keranjang</span>
    </div>
    <div class="flex-1 max-w-16 h-px bg-slate-200"></div>
    <div class="flex items-center gap-2 text-brand-600">
        <div class="w-7 h-7 rounded-full bg-brand-600 flex items-center justify-center text-xs text-white font-bold">2</div>
        <span class="text-xs font-semibold hidden sm:block">Checkout</span>
    </div>
    <div class="flex-1 max-w-16 h-px bg-slate-200"></div>
    <div class="flex items-center gap-2 text-slate-400">
        <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-xs text-slate-400 font-bold">3</div>
        <span class="text-xs hidden sm:block">Selesai</span>
    </div>
</div>

<form action="<?= base_url('shop/checkout/store') ?>" method="POST">
<?= csrf_field() ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Form -->
    <div class="lg:col-span-2 space-y-5">

        <!-- Customer info -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                <i class="fas fa-user-circle text-brand-500"></i> Informasi Pemesan
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Nama</label>
                    <input type="text" value="<?= esc($customer['nama']) ?>" readonly
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" value="<?= esc($customer['email']) ?>" readonly
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                        No. Telepon <span class="text-slate-400 font-normal normal-case">(opsional)</span>
                    </label>
                    <input type="tel" name="no_hp" value="<?= esc($customer['no_hp'] ?? '') ?>"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                        Alamat <span class="text-slate-400 font-normal normal-case">(opsional)</span>
                    </label>
                    <input type="text" name="alamat" value="<?= esc($customer['alamat'] ?? '') ?>"
                           placeholder="Jl. Contoh No. 1, Kota"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                    Catatan <span class="text-slate-400 font-normal normal-case">(opsional)</span>
                </label>
                <textarea name="catatan" rows="3"
                          placeholder="Catatan pesanan (misal: warna, ukuran spesifik, dll.)"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 transition resize-none"></textarea>
            </div>
        </div>

        <!-- Payment method -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-wallet text-brand-500"></i> Metode Pembayaran
            </h3>

            <div class="space-y-3">
                <!-- COD -->
                <label class="payment-option flex items-center gap-3 p-4 rounded-xl border-2 border-brand-300 bg-brand-50 cursor-pointer transition"
                       data-method="cod">
                    <input type="radio" name="metode_bayar" value="cod" class="sr-only" checked>
                    <div class="w-10 h-10 bg-brand-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-brand-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">Bayar di Tempat (COD)</p>
                        <p class="text-xs text-slate-500 mt-0.5">Pembayaran dilakukan saat produk diterima</p>
                    </div>
                    <div class="payment-radio w-5 h-5 rounded-full border-2 border-brand-500 flex items-center justify-center flex-shrink-0">
                        <div class="payment-radio-dot w-2.5 h-2.5 rounded-full bg-brand-500"></div>
                    </div>
                </label>

                <!-- QRIS -->
                <label class="payment-option flex items-center gap-3 p-4 rounded-xl border-2 border-slate-200 bg-white cursor-pointer transition hover:border-brand-200"
                       data-method="qris">
                    <input type="radio" name="metode_bayar" value="qris" class="sr-only">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-qrcode text-indigo-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-800">QRIS</p>
                        <p class="text-xs text-slate-500 mt-0.5">Scan QR untuk membayar dengan e-wallet / mobile banking</p>
                    </div>
                    <div class="payment-radio w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center flex-shrink-0">
                        <div class="payment-radio-dot w-2.5 h-2.5 rounded-full bg-brand-500 hidden"></div>
                    </div>
                </label>

                <!-- QRIS preview (hidden by default) -->
                <div id="qris-preview" class="hidden p-5 rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-white">
                    <div class="flex flex-col items-center text-center">
                        <p class="text-sm font-semibold text-slate-700 mb-1">Scan QRIS di bawah ini</p>
                        <p class="text-xs text-slate-500 mb-3">Gunakan aplikasi e-wallet / m-banking apa pun</p>
                        <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100">
                            <img src="<?= base_url('image/qris.jpeg') ?>" alt="QRIS"
                                 class="w-56 h-56 object-contain">
                        </div>
                        <p class="text-xs text-slate-500 mt-3">
                            Setelah pembayaran berhasil, klik <strong>Konfirmasi Pesanan</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function () {
                const options = document.querySelectorAll('.payment-option');
                const preview = document.getElementById('qris-preview');

                function selectMethod(method) {
                    options.forEach(opt => {
                        const isActive = opt.dataset.method === method;
                        const radio = opt.querySelector('.payment-radio');
                        const dot   = opt.querySelector('.payment-radio-dot');
                        const input = opt.querySelector('input[type="radio"]');

                        opt.classList.toggle('border-brand-300', isActive);
                        opt.classList.toggle('bg-brand-50',     isActive);
                        opt.classList.toggle('border-slate-200', !isActive);
                        opt.classList.toggle('bg-white',         !isActive);

                        radio.classList.toggle('border-brand-500', isActive);
                        radio.classList.toggle('border-slate-300', !isActive);
                        dot.classList.toggle('hidden', !isActive);

                        input.checked = isActive;
                    });
                    preview.classList.toggle('hidden', method !== 'qris');
                }

                options.forEach(opt => {
                    opt.addEventListener('click', () => selectMethod(opt.dataset.method));
                });
            })();
        </script>
    </div>

    <!-- Order summary -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sticky top-24">
            <h3 class="font-bold text-slate-800 mb-4">Ringkasan Pesanan</h3>

            <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                <?php foreach ($items as $item): ?>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cube text-slate-300 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-700 leading-snug truncate"><?= esc($item['nama']) ?></p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Rp <?= number_format($item['harga'], 0, ',', '.') ?> × <?= $item['qty'] ?> <?= esc($item['satuan']) ?>
                        </p>
                    </div>
                    <p class="text-xs font-bold text-slate-700 flex-shrink-0">
                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-slate-100 mt-4 pt-4 space-y-2">
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Subtotal</span>
                    <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Ongkos Kirim</span>
                    <span class="text-green-600 font-medium">Gratis</span>
                </div>
                <div class="flex justify-between text-base font-bold text-slate-800 pt-2 border-t border-slate-100">
                    <span>Total</span>
                    <span class="text-brand-700">Rp <?= number_format($total, 0, ',', '.') ?></span>
                </div>
            </div>

            <button type="submit"
                    class="mt-5 w-full flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-brand-600 text-white font-bold hover:bg-brand-700 active:scale-[.98] transition">
                <i class="fas fa-check-circle"></i> Konfirmasi Pesanan
            </button>
            <p class="text-xs text-slate-400 text-center mt-2">
                Dengan menekan tombol di atas, Anda menyetujui pesanan ini.
            </p>
        </div>
    </div>
</div>
</form>

<?= $this->endSection() ?>
