<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('camera', ['namespace' => 'App\Modules\camera\Controllers'], function ($routes) {
    $routes->get('/', 'Camera::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'Camera::listdeleted');
    $routes->get('new', 'Camera::new');
    $routes->post('create', 'Camera::create');
    $routes->get('edit/(:num)', 'Camera::edit/$1');
    $routes->post('update/(:num)', 'Camera::update/$1');
    $routes->get('delete/(:num)', 'Camera::delete/$1');
    $routes->post('delete/(:num)', 'Camera::delete/$1');
    $routes->get('restore/(:num)', 'Camera::restore/$1');
    $routes->post('restore/(:num)', 'Camera::restore/$1');
    $routes->post('purge/(:num)', 'Camera::purge/$1');
    $routes->post('status/(:num)', 'Camera::status/$1');
    $routes->post('deleteMultiple', 'Camera::deleteMultiple');
    $routes->post('restoreMultiple', 'Camera::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'Camera::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'Camera::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'Camera::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'Camera::deletePermanentMultiple');
    $routes->post('statusMultiple', 'Camera::statusMultiple');
    $routes->get('deleted', 'Camera::deleted');
    $routes->get('view/(:num)', 'Camera::view/$1');
    $routes->get('exportPdf', 'Camera::exportPdf');
    $routes->get('exportExcel', 'Camera::exportExcel');
    $routes->get('exportDeletedPdf', 'Camera::exportDeletedPdf');
    $routes->get('exportDeletedExcel', 'Camera::exportDeletedExcel');
}); 