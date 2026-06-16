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
        $metodeBayar = $this->request->getPost('metode_bayar') === 'qris' ? 'qris' : 'cod';
        $customerId  = session()->get('customer_id');

        if ($noHp || $alamat) {
            (new CustomerModel())->update($customerId, array_filter([
                'no_hp'  => $noHp,
                'alamat' => $alamat,
            ]));
        }

        $penjualanModel->skipValidation(true)->insert([
            'no_transaksi' => $penjualanModel->generateNoTransaksi(),
            'tanggal_jual' => date('Y-m-d'),
            'nama_pembeli' => session()->get('customer_nama'),
            'id_customer'  => $customerId,
            'total_harga'  => $totalHarga,
            'bayar'        => $totalHarga,
            'kembalian'    => 0,
            'metode_bayar' => $metodeBayar,
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

        return redirect()->to(base_url('shop/checkout/success/' . $idPenjualan))
            ->with('success', 'Pesanan berhasil dibuat!');
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
