<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module PhongKhoa
$routes->group('phongkhoa', ['namespace' => 'App\Modules\phongkhoa\Controllers'], function ($routes) {
    $routes->get('/', 'PhongKhoa::index');
    $routes->get('listdeleted', 'PhongKhoa::listdeleted');
    $routes->get('new', 'PhongKhoa::new');
    $routes->post('create', 'PhongKhoa::create');
    $routes->get('edit/(:num)', 'PhongKhoa::edit/$1');
    $routes->post('update/(:num)', 'PhongKhoa::update/$1');
    $routes->get('delete/(:num)', 'PhongKhoa::delete/$1');
    $routes->post('delete/(:num)', 'PhongKhoa::delete/$1');
    $routes->get('restore/(:num)', 'PhongKhoa::restore/$1');
    $routes->post('restore/(:num)', 'PhongKhoa::restore/$1');
    $routes->post('purge/(:num)', 'PhongKhoa::purge/$1');
    $routes->post('status/(:num)', 'PhongKhoa::status/$1');
    $routes->post('deleteMultiple', 'PhongKhoa::deleteMultiple');
    $routes->post('restoreMultiple', 'PhongKhoa::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'PhongKhoa::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'PhongKhoa::permanentDelete/$1');
}); 