<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\BarangModel;

class PenjualanController extends BaseController
{
    protected PenjualanModel $model;
    protected DetailPenjualanModel $detailModel;

    public function __construct()
    {
        $this->model       = new PenjualanModel();
        $this->detailModel = new DetailPenjualanModel();
    }

    public function index()
    {
        $perPage = 10;
        $search  = $this->request->getGet('search');
        $dari    = $this->request->getGet('dari');
        $sampai  = $this->request->getGet('sampai');
        $status  = $this->request->getGet('status');

        $builder = $this->model->select('
                    penjualan.*,
                    COUNT(detail_penjualan.id) as jumlah_item,
                    COALESCE(SUM((detail_penjualan.harga_jual - detail_penjualan.harga_beli) * detail_penjualan.jumlah), 0) as total_keuntungan
                ')
                ->join('detail_penjualan', 'detail_penjualan.id_penjualan = penjualan.id', 'left')
                ->groupBy('penjualan.id')
                ->orderBy('penjualan.tanggal_jual', 'DESC');

        if ($search) {
            $builder->groupStart()
                    ->like('penjualan.no_transaksi', $search)
                    ->orLike('penjualan.nama_pembeli', $search)
                    ->groupEnd();
        }
        if ($dari)   $builder->where('penjualan.tanggal_jual >=', $dari);
        if ($sampai) $builder->where('penjualan.tanggal_jual <=', $sampai);
        if (in_array($status, ['pending', 'lunas', 'batal'], true)) {
            $builder->where('penjualan.status_bayar', $status);
        }

        $total      = $builder->countAllResults(false);
        $penjualans = $builder->paginate($perPage, 'default');
        $pager      = $this->model->pager;

        return view('penjualan/index', [
            'title'         => 'Transaksi Penjualan',
            'penjualans'    => $penjualans,
            'pager'         => $pager,
            'total'         => $total,
            'search'        => $search,
            'dari'          => $dari,
            'sampai'        => $sampai,
            'status'        => $status,
            'pending_count' => (new PenjualanModel())->where('status_bayar', 'pending')->countAllResults(),
        ]);
    }

    public function create()
    {
        return view('penjualan/form', [
            'title'        => 'Transaksi Penjualan Baru',
            'barangs'      => (new BarangModel())->orderBy('nama_barang')->findAll(),
            'no_transaksi' => $this->model->generateNoTransaksi(),
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $detail = $this->request->getPost('detail') ?? [];
        if (empty($detail)) {
            return redirect()->back()->withInput()->with('error', 'Tambahkan minimal 1 barang.');
        }

        $totalHarga = array_sum(array_map(fn($d) => $d['jumlah'] * $d['harga_jual'], $detail));
        $bayar      = (float) $this->request->getPost('bayar');

        // Cek stok cukup
        $barangModel = new BarangModel();
        foreach ($detail as $d) {
            $brg = $barangModel->find($d['id_barang']);
            if ($brg && $brg['stok'] < $d['jumlah']) {
                $db->transRollback();
                return redirect()->back()->withInput()
                    ->with('error', "Stok {$brg['nama_barang']} tidak cukup. Stok tersedia: {$brg['stok']} {$brg['satuan']}");
            }
        }

        $header = [
            'no_transaksi'  => $this->request->getPost('no_transaksi'),
            'tanggal_jual'  => $this->request->getPost('tanggal_jual'),
            'nama_pembeli'  => $this->request->getPost('nama_pembeli') ?: 'Umum',
            'total_harga'   => $totalHarga,
            'bayar'         => $bayar,
            'kembalian'     => $bayar - $totalHarga,
            'keterangan'    => $this->request->getPost('keterangan'),
            // Transaksi kasir/POS langsung lunas (dibayar tunai di tempat).
            'status_bayar'  => 'lunas',
            'tanggal_bayar' => date('Y-m-d H:i:s'),
        ];

        $this->model->skipValidation(true)->insert($header);
        $id = $this->model->getInsertID();

        $this->detailModel->simpanDanKurangiStok($detail, $id);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi.');
        }

        return redirect()->to('penjualan/show/' . $id)->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show($id)
    {
        $penjualan = $this->model->find($id);
        if (!$penjualan) return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan.');

        $details = $this->detailModel->getByPenjualan($id);

        $totalKeuntungan = array_sum(array_map(
            fn($d) => ($d['harga_jual'] - $d['harga_beli']) * $d['jumlah'],
            $details
        ));

        return view('penjualan/show', [
            'title'            => 'Detail Penjualan',
            'penjualan'        => $penjualan,
            'details'          => $details,
            'total_keuntungan' => $totalKeuntungan,
        ]);
    }

    public function invoice($id)
    {
        $penjualan = $this->model->find($id);
        if (!$penjualan) return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan.');

        $details = $this->detailModel->getByPenjualan($id);

        $totalKeuntungan = array_sum(array_map(
            fn($d) => ($d['harga_jual'] - $d['harga_beli']) * $d['jumlah'],
            $details
        ));

        return view('penjualan/invoice', [
            'title'            => 'Invoice ' . $penjualan['no_transaksi'],
            'penjualan'        => $penjualan,
            'details'          => $details,
            'total_keuntungan' => $totalKeuntungan,
        ]);
    }

    public function delete($id)
    {
        $penjualan = $this->model->find($id);
        if (!$penjualan) return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan.');

        // Kembalikan stok hanya jika transaksi belum dibatalkan
        // (transaksi 'batal' stoknya sudah dikembalikan saat pembatalan).
        if (($penjualan['status_bayar'] ?? '') !== 'batal') {
            $this->kembalikanStok($id);
        }

        // Hapus bukti bayar bila ada
        if (!empty($penjualan['bukti_bayar'])) {
            @unlink(FCPATH . 'uploads/bukti/' . $penjualan['bukti_bayar']);
        }

        $this->detailModel->where('id_penjualan', $id)->delete();
        $this->model->delete($id);

        return redirect()->to('penjualan')->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Validasi pembayaran: tandai transaksi sebagai LUNAS.
     */
    public function validasi($id)
    {
        $penjualan = $this->model->find($id);
        if (!$penjualan) return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan.');

        if ($penjualan['status_bayar'] === 'lunas') {
            return redirect()->back()->with('error', 'Transaksi sudah lunas.');
        }
        if ($penjualan['status_bayar'] === 'batal') {
            return redirect()->back()->with('error', 'Transaksi sudah dibatalkan, tidak bisa divalidasi.');
        }

        $this->model->skipValidation(true)->update($id, [
            'status_bayar'  => 'lunas',
            'bayar'         => $penjualan['total_harga'],
            'kembalian'     => 0,
            'tanggal_bayar' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Pembayaran divalidasi. Transaksi ditandai LUNAS.');
    }

    /**
     * Batalkan/tolak pesanan: tandai BATAL dan kembalikan stok.
     */
    public function batal($id)
    {
        $penjualan = $this->model->find($id);
        if (!$penjualan) return redirect()->to('penjualan')->with('error', 'Data tidak ditemukan.');

        if ($penjualan['status_bayar'] === 'batal') {
            return redirect()->back()->with('error', 'Transaksi sudah dibatalkan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // Kembalikan stok yang sempat dikurangi saat pesanan dibuat
        $this->kembalikanStok($id);

        $this->model->skipValidation(true)->update($id, [
            'status_bayar' => 'batal',
            'bayar'        => 0,
            'kembalian'    => 0,
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Gagal membatalkan transaksi.');
        }

        return redirect()->back()->with('success', 'Transaksi dibatalkan & stok dikembalikan.');
    }

    /**
     * Kembalikan stok barang dari sebuah transaksi penjualan.
     */
    private function kembalikanStok($idPenjualan): void
    {
        $db      = \Config\Database::connect();
        $details = $this->detailModel->where('id_penjualan', $idPenjualan)->findAll();
        foreach ($details as $d) {
            $db->table('barang')
               ->where('id', (int) $d['id_barang'])
               ->set('stok', 'stok + ' . (int) $d['jumlah'], false)
               ->update();
        }
    }
}
