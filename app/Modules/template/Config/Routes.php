<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('template', ['namespace' => 'App\Modules\template\Controllers'], function ($routes) {
    $routes->get('/', 'Template::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'Template::listdeleted');
    $routes->get('new', 'Template::new');
    $routes->post('create', 'Template::create');
    $routes->get('edit/(:num)', 'Template::edit/$1');
    $routes->post('update/(:num)', 'Template::update/$1');
    $routes->get('delete/(:num)', 'Template::delete/$1');
    $routes->post('delete/(:num)', 'Template::delete/$1');
    $routes->get('restore/(:num)', 'Template::restore/$1');
    $routes->post('restore/(:num)', 'Template::restore/$1');
    $routes->post('purge/(:num)', 'Template::purge/$1');
    $routes->post('status/(:num)', 'Template::status/$1');
    $routes->post('deleteMultiple', 'Template::deleteMultiple');
    $routes->post('restoreMultiple', 'Template::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'Template::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'Template::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'Template::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'Template::deletePermanentMultiple');
    $routes->post('statusMultiple', 'Template::statusMultiple');
    $routes->get('deleted', 'Template::deleted');
    $routes->get('view/(:num)', 'Template::view/$1');
    $routes->get('exportPdf', 'Template::exportPdf');
    $routes->get('exportExcel', 'Template::exportExcel');
    $routes->get('exportDeletedPdf', 'Template::exportDeletedPdf');
    $routes->get('exportDeletedExcel', 'Template::exportDeletedExcel');
}); 