<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\CustomerModel;

class CheckoutController extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to(base_url('shop/cart'))->with('error', 'Keranjang belanja kosong.');
        }

        $items       = [];
        $total       = 0;
        $barangModel = new BarangModel();

        foreach ($cart as $id => $qty) {
            $barang = $barangModel->find($id);
            if ($barang) {
                $subtotal = $barang['harga_jual'] * $qty;
                $total   += $subtotal;
                $items[]  = [
                    'id'       => $id,
                    'nama'     => $barang['nama_barang'],
                    'harga'    => $barang['harga_jual'],
                    'satuan'   => $barang['satuan'],
                    'qty'      => $qty,
                    'subtotal' => $subtotal,
                ];
            }
        }

        $customer = (new CustomerModel())->find(session()->get('customer_id'));

        return view('customer/checkout/index', [
            'title'    => 'Checkout',
            'items'    => $items,
            'total'    => $total,
            'customer' => $customer,
        ]);
    }

    public function store()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to(base_url('shop/cart'))->with('error', 'Keranjang belanja kosong.');
        }

        // Tentukan metode bayar. Untuk QRIS, bukti pembayaran WAJIB diunggah
        // lebih dulu sebelum pesanan diproses.
        $metodeBayar = $this->request->getPost('metode_bayar') === 'qris' ? 'qris' : 'cod';
        $buktiName   = null;
        if ($metodeBayar === 'qris') {
            [$buktiName, $errBukti] = $this->simpanBukti($this->request->getFile('bukti_bayar'));
            if ($errBukti !== null) {
                return redirect()->to(base_url('shop/checkout'))->withInput()
                    ->with('error', $errBukti);
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $barangModel    = new BarangModel();
        $penjualanModel = new PenjualanModel();
        $detailModel    = new DetailPenjualanModel();

        $detail     = [];
        $totalHarga = 0;

        foreach ($cart as $id => $qty) {
            $barang = $barangModel->find($id);
            if (!$barang) {
                continue;
            }

            if ($barang['stok'] < $qty) {
                $db->transRollback();
                return redirect()->to(base_url('shop/checkout'))
                    ->with('error', "Stok {$barang['nama_barang']} tidak mencukupi. Tersisa: {$barang['stok']} {$barang['satuan']}.");
            }

            $subtotal    = $barang['harga_jual'] * $qty;
            $totalHarga += $subtotal;
            $detail[]    = [
                'id_barang'  => (int) $id,
                'jumlah'     => $qty,
                'harga_jual' => $barang['harga_jual'],
            ];
        }

        $noHp        = $this->request->getPost('no_hp');
        $alamat      = $this->request->getPost('alamat');
        $catatan     = $this->request->getPost('catatan');
        $customerId  = session()->get('customer_id');

        if ($noHp || $alamat) {
            (new CustomerModel())->update($customerId, array_filter([
                'no_hp'  => $noHp,
                'alamat' => $alamat,
            ]));
        }

        // Pesanan dari toko online belum lunas: menunggu validasi pembayaran
        // oleh admin (QRIS: cek bukti transfer, COD: setelah barang diterima).
        $penjualanModel->skipValidation(true)->insert([
            'no_transaksi' => $penjualanModel->generateNoTransaksi(),
            'tanggal_jual' => date('Y-m-d'),
            'nama_pembeli' => session()->get('customer_nama'),
            'id_customer'  => $customerId,
            'total_harga'  => $totalHarga,
            'bayar'        => 0,
            'kembalian'    => 0,
            'metode_bayar' => $metodeBayar,
            'status_bayar' => 'pending',
            'bukti_bayar'  => $buktiName,
            'keterangan'   => $catatan ?: null,
        ]);

        $idPenjualan = $penjualanModel->getInsertID();
        $detailModel->simpanDanKurangiStok($detail, $idPenjualan);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->to(base_url('shop/checkout'))
                ->with('error', 'Gagal memproses pesanan. Silakan coba lagi.');
        }

        session()->remove('cart');

        $pesan = $metodeBayar === 'qris'
            ? 'Pesanan & bukti pembayaran terkirim! Menunggu validasi admin.'
            : 'Pesanan dibuat! Pesanan COD akan diproses dan dibayar saat barang diterima.';

        return redirect()->to(base_url('shop/checkout/success/' . $idPenjualan))
            ->with('success', $pesan);
    }

    /**
     * Customer mengunggah bukti pembayaran (QRIS) untuk pesanannya.
     */
    public function uploadBukti($id)
    {
        $penjualanModel = new PenjualanModel();
        $penjualan      = $penjualanModel->find($id);

        if (!$penjualan || (int) $penjualan['id_customer'] !== (int) session()->get('customer_id')) {
            return redirect()->to(base_url('shop/orders'))->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($penjualan['status_bayar'] !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan ini tidak menunggu pembayaran.');
        }

        [$newName, $err] = $this->simpanBukti($this->request->getFile('bukti_bayar'), $penjualan['bukti_bayar'] ?? null);
        if ($err !== null) {
            return redirect()->back()->with('error', $err);
        }

        $penjualanModel->skipValidation(true)->update($id, ['bukti_bayar' => $newName]);

        return redirect()->to(base_url('shop/checkout/success/' . $id))
            ->with('success', 'Bukti pembayaran terkirim. Menunggu validasi admin.');
    }

    /**
     * Validasi & simpan file bukti pembayaran ke public/uploads/bukti/.
     *
     * @param mixed       $file    Objek UploadedFile dari request.
     * @param string|null $oldName Nama bukti lama untuk dihapus (saat unggah ulang).
     * @return array{0: ?string, 1: ?string} [namaFileBaru|null, pesanError|null]
     */
    private function simpanBukti($file, ?string $oldName = null): array
    {
        if (!$file || !$file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return [null, 'Silakan unggah bukti pembayaran (JPG/PNG/WEBP) terlebih dahulu.'];
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($file->getMimeType(), $allowedMime, true) ||
            !in_array(strtolower($file->getExtension()), $allowedExt, true)) {
            return [null, 'Format bukti harus JPG, PNG, atau WEBP.'];
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            return [null, 'Ukuran bukti maksimal 2 MB.'];
        }

        $targetDir = FCPATH . 'uploads/bukti';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }
        if (!empty($oldName)) {
            @unlink($targetDir . '/' . $oldName);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);

        return [$newName, null];
    }

    public function success($id)
    {
        $penjualan = (new PenjualanModel())->find($id);

        if (!$penjualan || (int) $penjualan['id_customer'] !== (int) session()->get('customer_id')) {
            return redirect()->to(base_url('shop'));
        }

        $details = (new DetailPenjualanModel())->getByPenjualan($id);

        return view('customer/checkout/success', [
            'title'     => 'Pesanan Berhasil',
            'penjualan' => $penjualan,
            'details'   => $details,
        ]);
    }

    public function orders()
    {
        $customerId = (int) session()->get('customer_id');

        $orders = (new PenjualanModel())
            ->select('penjualan.*, COUNT(dp.id) as jumlah_item')
            ->join('detail_penjualan dp', 'dp.id_penjualan = penjualan.id', 'left')
            ->where('penjualan.id_customer', $customerId)
            ->groupBy('penjualan.id')
            ->orderBy('penjualan.tanggal_jual', 'DESC')
            ->orderBy('penjualan.id', 'DESC')
            ->findAll();

        return view('customer/checkout/orders', [
            'title'  => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }
}
