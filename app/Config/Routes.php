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
   $routes->get('kelahiran/edit/(:num)', 'Sekretaris\Kelahiran::edit/$1', ['as' => 'sekretaris.kelahiran.edit']);
   $routes->post('kelahiran/simpan', 'Sekretaris\Kelahiran::simpan', ['as' => 'sekretaris.kelahiran.simpan']);
   $routes->post('kelahiran/update/(:num)', 'Sekretaris\Kelahiran::update/$1', ['as' => 'sekretaris.kelahiran.update']);
   $routes->get('kelahiran/hapus/(:num)', 'Sekretaris\Kelahiran::hapus/$1', ['as' => 'sekretaris.kelahiran.hapus']);

   // Kematian
   $routes->get('kematian', 'Sekretaris\Kematian::index', ['as' => 'sekretaris.kematian']);
   $routes->get('kematian/tambah', 'Sekretaris\Kematian::tambah', ['as' => 'sekretaris.kematian.tambah']);
   $routes->post('kematian/simpan', 'Sekretaris\Kematian::simpan', ['as' => 'sekretaris.kematian.simpan']);
   $routes->get('kematian/edit/(:num)', 'Sekretaris\Kematian::edit/$1', ['as' => 'sekretaris.kematian.edit']);
   $routes->post('kematian/update/(:num)', 'Sekretaris\Kematian::update/$1', ['as' => 'sekretaris.kematian.update']);
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
   $routes->get('surat/penduduk', 'Sekretaris\Surat::pilihPenduduk', ['as' => 'sekretaris.surat.penduduk']);
   $routes->get('surat/buat', 'Sekretaris\Surat::buat', ['as' => 'sekretaris.surat.buat']);
   $routes->post('surat/simpan', 'Sekretaris\Surat::simpan', ['as' => 'sekretaris.surat.simpan']);
   $routes->get('surat/cetak/(:num)', 'Sekretaris\Surat::cetak/$1', ['as' => 'sekretaris.surat.cetak']);

   // Logout
   $routes->get('logout', 'Auth::logout', ['as' => 'sekretaris.logout']);
});

// Kades
$routes->group('kepala-desa', ['filter' => 'auth:kepala_desa'], function ($routes) {
   // Dashboard
   $routes->get('dashboard', 'KepalaDesa\Dashboard::index', ['as' => 'kepala_desa.dashboard']);

   // Approval Surat
   $routes->get('surat', 'KepalaDesa\Surat::index', ['as' => 'kepala_desa.surat']);
   $routes->post('surat/approve/(:num)', 'KepalaDesa\Surat::approve/$1', ['as' => 'kepala_desa.surat.approve']);
   $routes->post('surat/reject/(:num)', 'KepalaDesa\Surat::reject/$1', ['as' => 'kepala_desa.surat.reject']);

   // Statistik
   $routes->get('statistik', 'KepalaDesa\Statistik::index', ['as' => 'kepala_desa.statistik']);
});
