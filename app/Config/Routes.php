<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth
$routes->get('login',  'AuthController::login');
$routes->post('login', 'AuthController::doLogin');
$routes->get('logout', 'AuthController::logout');

$routes->get('/', 'LandingController::index');

// Dashboard
$routes->get('dashboard', 'DashboardController::index');

// Kategori
$routes->get('kategori',              'KategoriController::index');
$routes->get('kategori/create',       'KategoriController::create');
$routes->post('kategori/store',       'KategoriController::store');
$routes->get('kategori/edit/(:num)',  'KategoriController::edit/$1');
$routes->post('kategori/update/(:num)', 'KategoriController::update/$1');
$routes->get('kategori/delete/(:num)', 'KategoriController::delete/$1');

// Supplier
$routes->get('supplier',               'SupplierController::index');
$routes->get('supplier/create',        'SupplierController::create');
$routes->post('supplier/store',        'SupplierController::store');
$routes->get('supplier/edit/(:num)',   'SupplierController::edit/$1');
$routes->post('supplier/update/(:num)', 'SupplierController::update/$1');
$routes->get('supplier/delete/(:num)', 'SupplierController::delete/$1');

// Barang
$routes->get('barang',               'BarangController::index');
$routes->get('barang/create',        'BarangController::create');
$routes->post('barang/store',        'BarangController::store');
$routes->get('barang/edit/(:num)',   'BarangController::edit/$1');
$routes->post('barang/update/(:num)', 'BarangController::update/$1');
$routes->get('barang/delete/(:num)', 'BarangController::delete/$1');
$routes->get('barang/show/(:num)',   'BarangController::show/$1');

// Stok Masuk
$routes->get('stok-masuk',               'StokMasukController::index');
$routes->get('stok-masuk/create',        'StokMasukController::create');
$routes->post('stok-masuk/store',        'StokMasukController::store');
$routes->get('stok-masuk/show/(:num)',   'StokMasukController::show/$1');
$routes->get('stok-masuk/delete/(:num)', 'StokMasukController::delete/$1');

// Penjualan
$routes->get('penjualan',               'PenjualanController::index');
$routes->get('penjualan/create',        'PenjualanController::create');
$routes->post('penjualan/store',        'PenjualanController::store');
$routes->get('penjualan/show/(:num)',    'PenjualanController::show/$1');
$routes->get('penjualan/invoice/(:num)','PenjualanController::invoice/$1');
$routes->get('penjualan/delete/(:num)', 'PenjualanController::delete/$1');

// Laporan
$routes->get('laporan',                  'LaporanController::index');
$routes->get('laporan/penjualan',        'LaporanController::penjualan');
$routes->get('laporan/penjualan/pdf',    'LaporanController::penjualanPdf');
$routes->get('laporan/penjualan/excel',  'LaporanController::penjualanExcel');
$routes->get('laporan/stok',             'LaporanController::stok');
$routes->get('laporan/stok/pdf',         'LaporanController::stokPdf');
$routes->get('laporan/stok/excel',       'LaporanController::stokExcel');
$routes->get('laporan/barang-masuk',     'LaporanController::barangMasuk');
$routes->get('laporan/barang-masuk/pdf', 'LaporanController::barangMasukPdf');
$routes->get('laporan/barang-masuk/excel', 'LaporanController::barangMasukExcel');

// Retur Penjualan
$routes->get('retur',                        'ReturController::index');
$routes->get('retur/create',                 'ReturController::create');
$routes->post('retur/store',                 'ReturController::store');
$routes->get('retur/show/(:num)',            'ReturController::show/$1');
$routes->get('retur/delete/(:num)',          'ReturController::delete/$1');

// Penyesuaian Stok
$routes->get('retur/penyesuaian',            'ReturController::penyesuaian');
$routes->get('retur/penyesuaian/create',     'ReturController::penyesuaianCreate');
$routes->post('retur/penyesuaian/store',     'ReturController::penyesuaianStore');

// API untuk AJAX
$routes->get('api/barang/(:num)',            'BarangController::getHarga/$1');
$routes->get('api/retur/detail/(:num)',      'ReturController::getDetailPenjualan/$1');

// API Dashboard Charts
$routes->get('api/dashboard/chart',         'DashboardController::chartData');

// ===== CUSTOMER / SHOP =====
$routes->get('shop',                             'Customer\ShopController::index');
$routes->get('shop/detail/(:num)',               'Customer\ShopController::detail/$1');

// Auth Google
$routes->get('shop/login',                       'Customer\AuthController::login');
$routes->get('shop/auth/google',                 'Customer\AuthController::redirectToGoogle');
$routes->get('shop/auth/callback',               'Customer\AuthController::callback');
$routes->get('shop/auth/logout',                 'Customer\AuthController::logout');

// Cart (session-based, no login required)
$routes->post('shop/cart/add',                   'Customer\CartController::add');
$routes->post('shop/cart/update',                'Customer\CartController::update');
$routes->post('shop/cart/remove',                'Customer\CartController::remove');
$routes->get('shop/cart',                        'Customer\CartController::index');

// Checkout & Orders (login required — filter diterapkan di Filters.php)
$routes->get('shop/checkout',                    'Customer\CheckoutController::index',        ['filter' => 'customer_auth']);
$routes->post('shop/checkout/store',             'Customer\CheckoutController::store',        ['filter' => 'customer_auth']);
$routes->get('shop/checkout/success/(:num)',     'Customer\CheckoutController::success/$1',   ['filter' => 'customer_auth']);
$routes->get('shop/orders',                      'Customer\CheckoutController::orders',       ['filter' => 'customer_auth']);
