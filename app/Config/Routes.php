<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route utama
// $routes->get('/', 'Home::index');
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
