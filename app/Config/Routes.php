<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Route Login & Auth (Publik)
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::process');
$routes->get('logout', 'Auth::logout');

// Route Checkout / Kasir Pelanggan
$routes->get('checkout', 'Checkout::index');
$routes->post('checkout/process', 'Checkout::process');
$routes->get('checkout/success', 'Checkout::success');
$routes->get('checkout/success/(:any)', 'Checkout::success/$1');


/* ==========================================================
   AREA RIWAYAT (Admin dan Staff hanya bisa melihat daftar)
   ========================================================== */
$routes->group('admin', ['filter' => 'auth:admin,staff'], function ($routes) {
    $routes->get('orders', 'Admin\Orders::index');
});

/* ==========================================================
   AREA ADMIN (Diproteksi dengan Auth Filter dan role Admin)
   ========================================================== */
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Orders / Pesanan masuk
    $routes->get('orders/detail/(:num)', 'Admin\Orders::detail/$1');
    $routes->get('orders/process/(:num)', 'Admin\Orders::updateStatus/$1');

    // Route Menu BrewNest (Admin)
    $routes->get('menus', 'Admin\Menus::index');
    $routes->get('menus/create', 'Admin\Menus::create');
    $routes->post('menus/store', 'Admin\Menus::store');
    $routes->get('menus/edit/(:num)', 'Admin\Menus::edit/$1');
    $routes->post('menus/update/(:num)', 'Admin\Menus::update/$1');
    $routes->post('menus/delete/(:num)', 'Admin\Menus::delete/$1');

    // Route Users Admin
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');

    // Route Categories Admin
    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/store', 'Admin\Categories::store');
    $routes->get('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\Categories::update/$1');
    $routes->post('categories/delete/(:num)', 'Admin\Categories::delete/$1');

    // Route Settings Admin
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/update', 'Admin\Settings::update');
});
