<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Sekretaris;
use App\Filters\SekretarisFilter;

/**
 * @var RouteCollection $routes
 */

// Route utama
$routes->get('/', 'Auth::login'); // Arahkan root ke login
$routes->get('home', 'Home::index', ['filter' => 'auth']);

// Login route
$routes->group('', ['filter' => 'guest'], function ($routes) {
   $routes->get('/login', 'Auth::login');
   $routes->post('/login', 'Auth::attemptLogin');
});

$routes->get('/logout', 'Auth::logout');

// Admin dashboard
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
   $routes->get('dashboard', 'Admin\Dashboard::index');
   $routes->get('manage_users', 'Admin\UserController::manageUsers');
   $routes->get('manage_pejabat', 'Admin\PejabatController::managePejabat');

   $routes->get('users', 'Admin\UserController::manageUsers');
   $routes->get('users/create', 'Admin\UserController::create');
   $routes->post('users/store', 'Admin\UserController::store');
   $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
   $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
   $routes->get('users/delete/(:num)', 'Admin\UserController::delete/$1');

   $routes->get('pejabat', 'Admin\PejabatController::managePejabat');
   $routes->get('pejabat/create', 'Admin\PejabatController::create');
   $routes->post('pejabat/store', 'Admin\PejabatController::store');
   $routes->get('pejabat/edit/(:num)', 'Admin\PejabatController::edit/$1');
   $routes->post('pejabat/update/(:num)', 'Admin\PejabatController::update/$1');
   $routes->get('pejabat/delete/(:num)', 'Admin\PejabatController::delete/$1');
});

$routes->group('sekretaris', ['filter' => 'auth:sekretaris'], function ($routes) {
   // Dashboard
   $routes->get('dashboard', 'Sekretaris\Dashboard::index', ['as' => 'sekretaris.dashboard']);

   // Data Penduduk
   $routes->get('penduduk', 'Sekretaris\Penduduk::index', ['as' => 'sekretaris.penduduk']);
   $routes->get('penduduk/tambah', 'Sekretaris\Penduduk::tambah', ['as' => 'sekretaris.penduduk.tambah']);
   $routes->get('penduduk/edit/(:num)', 'Sekretaris\Penduduk::edit/$1', ['as' => 'sekretaris.penduduk.edit']);
   $routes->post('penduduk/simpan', 'Sekretaris\Penduduk::simpan', ['as' => 'sekretaris.penduduk.simpan']);
   $routes->post('penduduk/update/(:num)', 'Sekretaris\Penduduk::update/$1', ['as' => 'sekretaris.penduduk.update']);
   $routes->get('penduduk/hapus/(:num)', 'Sekretaris\Penduduk::hapus/$1', ['as' => 'sekretaris.penduduk.hapus']);

   // Kelahiran
   $routes->get('kelahiran', 'Sekretaris\Kelahiran::index', ['as' => 'sekretaris.kelahiran']);
   $routes->get('kelahiran/tambah', 'Sekretaris\Kelahiran::tambah', ['as' => 'sekretaris.kelahiran.tambah']);
   $routes->post('kelahiran/simpan', 'Sekretaris\Kelahiran::simpan', ['as' => 'sekretaris.kelahiran.simpan']);
   $routes->get('kelahiran/hapus/(:num)', 'Sekretaris\Kelahiran::hapus/$1', ['as' => 'sekretaris.kelahiran.hapus']);

   // Kematian
   $routes->get('kematian', 'Sekretaris\Kematian::index', ['as' => 'sekretaris.kematian']);
   $routes->get('kematian/tambah', 'Sekretaris\Kematian::tambah', ['as' => 'sekretaris.kematian.tambah']);
   $routes->post('kematian/simpan', 'Sekretaris\Kematian::simpan', ['as' => 'sekretaris.kematian.simpan']);
   $routes->get('kematian/hapus/(:num)', 'Sekretaris\Kematian::hapus/$1', ['as' => 'sekretaris.kematian.hapus']);

   // Perkawinan
   $routes->get('perkawinan', 'Sekretaris\Perkawinan::index', ['as' => 'sekretaris.perkawinan']);
   $routes->get('perkawinan/tambah', 'Sekretaris\Perkawinan::tambah', ['as' => 'sekretaris.perkawinan.tambah']);
   $routes->get('perkawinan/edit/(:num)', 'Sekretaris\Perkawinan::edit/$1', ['as' => 'sekretaris.perkawinan.edit']);
   $routes->post('perkawinan/simpan', 'Sekretaris\Perkawinan::simpan', ['as' => 'sekretaris.perkawinan.simpan']);
   $routes->post('perkawinan/update/(:num)', 'Sekretaris\Perkawinan::update/$1', ['as' => 'sekretaris.perkawinan.update']);
   $routes->get('perkawinan/hapus/(:num)', 'Sekretaris\Perkawinan::hapus/$1', ['as' => 'sekretaris.perkawinan.hapus']);

   // Surat Keterangan
   $routes->get('surat', 'Sekretaris\Surat::index', ['as' => 'sekretaris.surat']);
   $routes->get('surat/(:any)', 'Sekretaris\Surat::jenis/$1', ['as' => 'sekretaris.surat.jenis']);
   $routes->get('surat/(:any)/buat', 'Sekretaris\Surat::buat/$1', ['as' => 'sekretaris.surat.buat']);
   $routes->post('surat/simpan', 'Sekretaris\Surat::simpan', ['as' => 'sekretaris.surat.simpan']);
   $routes->get('surat/cetak/(:num)', 'Sekretaris\Surat::cetak/$1', ['as' => 'sekretaris.surat.cetak']);
   $routes->get('surat/detail/(:num)', 'Sekretaris\Surat::detail/$1', ['as' => 'sekretaris.surat.detail']);

   // Logout
   $routes->get('logout', 'Auth::logout', ['as' => 'sekretaris.logout']);
});
