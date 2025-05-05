<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Sekretaris;
use App\Filters\SekretarisFilter;

/**
 * @var RouteCollection $routes
 */

// Route utama
$routes->get('/', 'Auth::login'); // Arahkan root ke login
$routes->get('home', 'Home::index', ['filter' => 'auth']); // Proteksi home

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

// Sekretaris Route
$routes->group('sekretaris', ['filter' => 'auth:sekretaris'], function ($routes) {
   // Dashboard
   // $routes->get('dashboard', 'Sekretaris\Dashboard::index');
   $routes->get('dashboard', 'Sekretaris\Dashboard::index', ['as' => 'sekretaris.dashboard']);

   // Data Penduduk
   $routes->get('penduduk', 'Sekretaris::penduduk');
   $routes->get('penduduk/create', 'Sekretaris::tambahPenduduk');
   $routes->post('penduduk/store', 'Sekretaris::simpanPenduduk');
   $routes->get('penduduk/edit/(:num)', 'Sekretaris::editPenduduk/$1');
   $routes->post('penduduk/update/(:num)', 'Sekretaris::updatePenduduk/$1');
   $routes->get('penduduk/delete/(:num)', 'Sekretaris::hapusPenduduk/$1');

   // Pendidikan
   $routes->get('pendidikan/(:num)', 'Sekretaris::pendidikan/$1');
   $routes->get('pendidikan/(:num)/create', 'Sekretaris::tambahPendidikan/$1');
   $routes->post('pendidikan/(:num)/store', 'Sekretaris::simpanPendidikan/$1');
   $routes->get('pendidikan/(:num)/delete/(:num)', 'Sekretaris::hapusPendidikan/$1/$2');

   // Kelahiran
   $routes->get('kelahiran', 'Sekretaris::kelahiran');
   $routes->get('kelahiran/create', 'Sekretaris::tambahKelahiran');
   $routes->post('kelahiran/store', 'Sekretaris::simpanKelahiran');
   $routes->get('kelahiran/delete/(:num)', 'Sekretaris::hapusKelahiran/$1');

   // Kematian
   $routes->get('kematian', 'Sekretaris::kematian');
   $routes->get('kematian/create', 'Sekretaris::tambahKematian');
   $routes->post('kematian/store', 'Sekretaris::simpanKematian');
   $routes->get('kematian/delete/(:num)', 'Sekretaris::hapusKematian/$1');

   // Perkawinan
   $routes->get('perkawinan', 'Sekretaris::perkawinan');
   $routes->get('perkawinan/create', 'Sekretaris::tambahPerkawinan');
   $routes->post('perkawinan/store', 'Sekretaris::simpanPerkawinan');
   $routes->get('perkawinan/delete/(:num)', 'Sekretaris::hapusPerkawinan/$1');

   // Surat Keterangan
   $routes->get('surat', 'Sekretaris::surat');
   $routes->get('surat/(:any)', 'Sekretaris::surat/$1');
   $routes->get('surat/(:any)/create', 'Sekretaris::buatSurat/$1');
   $routes->post('surat/store', 'Sekretaris::simpanSurat');
   $routes->get('surat/cetak/(:num)', 'Sekretaris::cetakSurat/$1');

   // Logout
   $routes->get('logout', 'Auth::logout');
});
