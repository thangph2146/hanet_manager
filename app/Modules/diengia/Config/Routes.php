<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module DienGia
$routes->group('diengia', ['namespace' => 'App\Modules\diengia\Controllers'], function ($routes) {
    $routes->get('/', 'DienGia::index');
    $routes->get('dashboard', 'DienGia::index');
    $routes->get('statistics', 'DienGia::statistics');
    $routes->get('listdeleted', 'DienGia::listdeleted');
    $routes->get('new', 'DienGia::new');
    $routes->post('create', 'DienGia::create');
    $routes->get('edit/(:num)', 'DienGia::edit/$1');
    $routes->post('update/(:num)', 'DienGia::update/$1');
    $routes->get('delete/(:num)', 'DienGia::delete/$1');
    $routes->post('delete/(:num)', 'DienGia::delete/$1');
    $routes->get('restore/(:num)', 'DienGia::restore/$1');
    $routes->post('restore/(:num)', 'DienGia::restore/$1');
    $routes->post('purge/(:num)', 'DienGia::purge/$1');
    $routes->post('status/(:num)', 'DienGia::status/$1');
    $routes->post('deleteMultiple', 'DienGia::deleteMultiple');
    $routes->post('restoreMultiple', 'DienGia::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'DienGia::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'DienGia::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'DienGia::permanentDeleteMultiple');
    $routes->post('permanentDeleteAll', 'DienGia::permanentDeleteAll');
    $routes->post('statusMultiple', 'DienGia::statusMultiple');
    $routes->get('deleted', 'DienGia::deleted');
    $routes->get('view/(:num)', 'DienGia::view/$1');
    $routes->get('exportPdf', 'DienGia::exportPdf');
    $routes->get('exportExcel', 'DienGia::exportExcel');
}); 