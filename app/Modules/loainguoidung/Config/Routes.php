<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module LoaiNguoiDung
$routes->group('loainguoidung', ['namespace' => 'App\Modules\loainguoidung\Controllers'], function ($routes) {
    $routes->get('/', 'LoaiNguoiDung::index');
    $routes->get('listdeleted', 'LoaiNguoiDung::listdeleted');
    $routes->get('new', 'LoaiNguoiDung::new');
    $routes->post('create', 'LoaiNguoiDung::create');
    $routes->get('edit/(:num)', 'LoaiNguoiDung::edit/$1');
    $routes->post('update/(:num)', 'LoaiNguoiDung::update/$1');
    $routes->get('delete/(:num)', 'LoaiNguoiDung::delete/$1');
    $routes->post('delete/(:num)', 'LoaiNguoiDung::delete/$1');
    $routes->post('restore/(:num)', 'LoaiNguoiDung::restore/$1');
    $routes->post('purge/(:num)', 'LoaiNguoiDung::purge/$1');
    $routes->post('status/(:num)', 'LoaiNguoiDung::status/$1');
    $routes->post('deleteMultiple', 'LoaiNguoiDung::deleteMultiple');
    $routes->post('restoreMultiple', 'LoaiNguoiDung::restoreMultiple');
    $routes->post('permanentDelete/(:num)', 'LoaiNguoiDung::permanentDelete/$1');
}); 