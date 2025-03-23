<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Facenguoidung
$routes->group('facenguoidung', ['namespace' => 'App\Modules\facenguoidung\Controllers'], function ($routes) {
    $routes->get('/', 'Facenguoidung::index');
    $routes->get('dashboard', 'Facenguoidung::index');
    $routes->get('statistics', 'Facenguoidung::statistics');
    $routes->get('listdeleted', 'Facenguoidung::listdeleted');
    $routes->get('new', 'Facenguoidung::new');
    $routes->post('create', 'Facenguoidung::create');
    $routes->get('edit/(:num)', 'Facenguoidung::edit/$1');
    $routes->post('update/(:num)', 'Facenguoidung::update/$1');
    $routes->get('delete/(:num)', 'Facenguoidung::delete/$1');
    $routes->post('delete/(:num)', 'Facenguoidung::delete/$1');
    $routes->get('restore/(:num)', 'Facenguoidung::restore/$1');
    $routes->post('restore/(:num)', 'Facenguoidung::restore/$1');
    $routes->post('purge/(:num)', 'Facenguoidung::purge/$1');
    $routes->post('status/(:num)', 'Facenguoidung::status/$1');
    $routes->post('deleteMultiple', 'Facenguoidung::deleteMultiple');
    $routes->post('restoreMultiple', 'Facenguoidung::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'Facenguoidung::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'Facenguoidung::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'Facenguoidung::permanentDeleteMultiple');
    $routes->post('permanentDeleteAll', 'Facenguoidung::permanentDeleteAll');
    $routes->post('statusMultiple', 'Facenguoidung::statusMultiple');
    $routes->get('deleted', 'Facenguoidung::deleted');
    $routes->get('view/(:num)', 'Facenguoidung::view/$1');
    $routes->get('exportPdf', 'Facenguoidung::exportPdf');
    $routes->get('exportExcel', 'Facenguoidung::exportExcel');
}); 