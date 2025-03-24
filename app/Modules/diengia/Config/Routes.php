<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('diengia', ['namespace' => 'App\Modules\diengia\Controllers'], function ($routes) {
    $routes->get('/', 'Diengia::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'Diengia::listdeleted');
    $routes->get('new', 'Diengia::new');
    $routes->post('create', 'Diengia::create');
    $routes->get('edit/(:num)', 'Diengia::edit/$1');
    $routes->post('update/(:num)', 'Diengia::update/$1');
    $routes->get('delete/(:num)', 'Diengia::delete/$1');
    $routes->post('delete/(:num)', 'Diengia::delete/$1');
    $routes->get('restore/(:num)', 'Diengia::restore/$1');
    $routes->post('restore/(:num)', 'Diengia::restore/$1');
    $routes->post('purge/(:num)', 'Diengia::purge/$1');
    $routes->post('status/(:num)', 'Diengia::status/$1');
    $routes->post('deleteMultiple', 'Diengia::deleteMultiple');
    $routes->post('restoreMultiple', 'Diengia::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'Diengia::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'Diengia::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'Diengia::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'Diengia::deletePermanentMultiple');
    $routes->post('statusMultiple', 'Diengia::statusMultiple');
    $routes->get('deleted', 'Diengia::deleted');
    $routes->get('view/(:num)', 'Diengia::view/$1');
    $routes->get('exportPdf', 'Diengia::exportPdf');
    $routes->get('exportExcel', 'Diengia::exportExcel');
    $routes->get('exportDeletedPdf', 'Diengia::exportDeletedPdf');
    $routes->get('exportDeletedExcel', 'Diengia::exportDeletedExcel');
}); 