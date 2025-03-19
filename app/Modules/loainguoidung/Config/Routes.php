<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module LoaiNguoiDung
$routes->group('loainguoidung', ['namespace' => 'App\Modules\loainguoidung\Controllers'], function ($routes) {
    $routes->get('/', 'LoaiNguoiDung::index');
    $routes->get('deleted', 'LoaiNguoiDung::deleted');
    $routes->get('new', 'LoaiNguoiDung::new');
    $routes->post('create', 'LoaiNguoiDung::create');
    $routes->get('edit/(:num)', 'LoaiNguoiDung::edit/$1');
    $routes->post('update/(:num)', 'LoaiNguoiDung::update/$1');
    $routes->delete('delete/(:num)', 'LoaiNguoiDung::delete/$1');
    $routes->post('restore/(:num)', 'LoaiNguoiDung::restore/$1');
    $routes->delete('purge/(:num)', 'LoaiNguoiDung::purge/$1');
    $routes->post('status/(:num)', 'LoaiNguoiDung::status/$1');
    $routes->post('delete-multiple', 'LoaiNguoiDung::deleteMultiple');
}); 