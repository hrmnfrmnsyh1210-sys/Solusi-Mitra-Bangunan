<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BarangModel;

class CartController extends BaseController
{
    public function index()
    {
        $cart    = session()->get('cart') ?? [];
        $items   = [];
        $total   = 0;

        if (!empty($cart)) {
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
                        'stok'     => $barang['stok'],
                        'qty'      => $qty,
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }

        return view('customer/cart/index', [
            'title' => 'Keranjang Belanja',
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add()
    {
        $id  = (int) $this->request->getPost('id_barang');
        $qty = (int) $this->request->getPost('qty');

        if ($id <= 0 || $qty <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak valid.']);
        }

        $barang = (new BarangModel())->find($id);
        if (!$barang || $barang['stok'] <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Produk tidak tersedia.']);
        }

        $cart       = session()->get('cart') ?? [];
        $currentQty = $cart[$id] ?? 0;
        $newQty     = $currentQty + $qty;

        if ($newQty > $barang['stok']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Stok tidak cukup. Tersedia: {$barang['stok']} {$barang['satuan']}.",
            ]);
        }

        $cart[$id] = $newQty;
        session()->set('cart', $cart);

        return $this->response->setJSON([
            'success'    => true,
            'message'    => "'{$barang['nama_barang']}' ditambahkan ke keranjang.",
            'cart_count' => array_sum($cart),
        ]);
    }

    public function update()
    {
        $id  = (int) $this->request->getPost('id_barang');
        $qty = (int) $this->request->getPost('qty');

        $cart   = session()->get('cart') ?? [];
        $barang = (new BarangModel())->find($id);

        if ($qty <= 0) {
            unset($cart[$id]);
        } elseif ($barang) {
            $cart[$id] = min($qty, $barang['stok']);
        }

        session()->set('cart', $cart);

        $total    = 0;
        $subtotal = 0;
        $bModel   = new BarangModel();

        foreach ($cart as $bid => $bqty) {
            $b = $bModel->find($bid);
            if ($b) {
                $s     = $b['harga_jual'] * $bqty;
                $total += $s;
                if ((int)$bid === $id) {
                    $subtotal = $s;
                }
            }
        }

        return $this->response->setJSON([
            'success'    => true,
            'cart_count' => array_sum($cart),
            'subtotal'   => $subtotal,
            'total'      => $total,
        ]);
    }

    public function remove()
    {
        $id   = (int) $this->request->getPost('id_barang');
        $cart = session()->get('cart') ?? [];

        unset($cart[$id]);
        session()->set('cart', $cart);

        $total  = 0;
        $bModel = new BarangModel();
        foreach ($cart as $bid => $bqty) {
            $b = $bModel->find($bid);
            if ($b) {
                $total += $b['harga_jual'] * $bqty;
            }
        }

        return $this->response->setJSON([
            'success'    => true,
            'cart_count' => array_sum($cart),
            'total'      => $total,
        ]);
    }
}
