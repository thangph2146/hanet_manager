<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('manhinh', ['namespace' => 'App\Modules\manhinh\Controllers'], function ($routes) {
    $routes->get('/', 'Manhinh::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'Manhinh::listdeleted');
    $routes->get('new', 'Manhinh::new');
    $routes->post('create', 'Manhinh::create');
    $routes->get('edit/(:num)', 'Manhinh::edit/$1');
    $routes->post('update/(:num)', 'Manhinh::update/$1');
    $routes->get('delete/(:num)', 'Manhinh::delete/$1');
    $routes->post('delete/(:num)', 'Manhinh::delete/$1');
    $routes->get('restore/(:num)', 'Manhinh::restore/$1');
    $routes->post('restore/(:num)', 'Manhinh::restore/$1');
    $routes->post('purge/(:num)', 'Manhinh::purge/$1');
    $routes->post('status/(:num)', 'Manhinh::status/$1');
    $routes->post('deleteMultiple', 'Manhinh::deleteMultiple');
    $routes->post('restoreMultiple', 'Manhinh::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'Manhinh::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'Manhinh::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'Manhinh::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'Manhinh::deletePermanentMultiple');
    $routes->post('statusMultiple', 'Manhinh::statusMultiple');
    $routes->get('deleted', 'Manhinh::deleted');
    $routes->get('view/(:num)', 'Manhinh::view/$1');
    $routes->get('exportPdf', 'Manhinh::exportPdf');
    $routes->get('exportExcel', 'Manhinh::exportExcel');
    $routes->get('exportDeletedPdf', 'Manhinh::exportDeletedPdf');
    $routes->get('exportDeletedExcel', 'Manhinh::exportDeletedExcel');
}); 