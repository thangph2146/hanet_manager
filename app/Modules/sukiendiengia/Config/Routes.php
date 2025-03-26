<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('sukiendiengia', ['namespace' => 'App\Modules\sukiendiengia\Controllers'], function ($routes) {
    $routes->get('/', 'SuKienDienGia::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'SuKienDienGia::listdeleted');
    $routes->get('new', 'SuKienDienGia::new');
    $routes->post('create', 'SuKienDienGia::create');
    $routes->get('edit/(:num)', 'SuKienDienGia::edit/$1');
    $routes->post('update/(:num)', 'SuKienDienGia::update/$1');
    $routes->get('delete/(:num)', 'SuKienDienGia::delete/$1');
    $routes->post('delete/(:num)', 'SuKienDienGia::delete/$1');
    $routes->get('restore/(:num)', 'SuKienDienGia::restore/$1');
    $routes->post('restore/(:num)', 'SuKienDienGia::restore/$1');
    $routes->add('restore/(:num)', 'SuKienDienGia::restore/$1');
    $routes->post('purge/(:num)', 'SuKienDienGia::purge/$1');
    $routes->post('status/(:num)', 'SuKienDienGia::status/$1');
    $routes->post('deleteMultiple', 'SuKienDienGia::deleteMultiple');
    $routes->post('restoreMultiple', 'SuKienDienGia::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'SuKienDienGia::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'SuKienDienGia::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'SuKienDienGia::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'SuKienDienGia::deletePermanentMultiple');
    $routes->post('statusMultiple', 'SuKienDienGia::statusMultiple');
    $routes->get('deleted', 'SuKienDienGia::deleted');
    $routes->get('view/(:num)', 'SuKienDienGia::view/$1');
    $routes->get('exportPdf', 'SuKienDienGia::exportPdf');
    $routes->get('exportExcel', 'SuKienDienGia::exportExcel');
    $routes->get('exportDeletedPdf', 'SuKienDienGia::exportDeletedPdf');
    $routes->get('exportDeletedExcel', 'SuKienDienGia::exportDeletedExcel');
}); 