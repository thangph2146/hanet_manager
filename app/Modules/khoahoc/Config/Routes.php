<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module KhoaHoc
$routes->group('khoahoc', ['namespace' => 'App\Modules\khoahoc\Controllers'], function ($routes) {
    $routes->get('/', 'KhoaHoc::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'KhoaHoc::listdeleted');
    $routes->get('new', 'KhoaHoc::new');
    $routes->post('create', 'KhoaHoc::create');
    $routes->get('edit/(:num)', 'KhoaHoc::edit/$1');
    $routes->post('update/(:num)', 'KhoaHoc::update/$1');
    $routes->get('delete/(:num)', 'KhoaHoc::delete/$1');
    $routes->post('delete/(:num)', 'KhoaHoc::delete/$1');
    $routes->get('restore/(:num)', 'KhoaHoc::restore/$1');
    $routes->post('restore/(:num)', 'KhoaHoc::restore/$1');
    $routes->post('purge/(:num)', 'KhoaHoc::purge/$1');
    $routes->post('status/(:num)', 'KhoaHoc::status/$1');
    $routes->post('deleteMultiple', 'KhoaHoc::deleteMultiple');
    $routes->post('restoreMultiple', 'KhoaHoc::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'KhoaHoc::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'KhoaHoc::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'KhoaHoc::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'KhoaHoc::deletePermanentMultiple');
    $routes->post('statusMultiple', 'KhoaHoc::statusMultiple');
    $routes->get('deleted', 'KhoaHoc::deleted');
}); 