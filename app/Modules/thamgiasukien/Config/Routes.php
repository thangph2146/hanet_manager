<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('thamgiasukien', ['namespace' => 'App\Modules\thamgiasukien\Controllers'], function ($routes) {
    $routes->get('/', 'ThamGiaSuKien::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'ThamGiaSuKien::listdeleted');
    $routes->get('new', 'ThamGiaSuKien::new');
    $routes->post('create', 'ThamGiaSuKien::create');
    $routes->get('edit/(:num)', 'ThamGiaSuKien::edit/$1');
    $routes->post('update/(:num)', 'ThamGiaSuKien::update/$1');
    $routes->get('delete/(:num)', 'ThamGiaSuKien::delete/$1');
    $routes->post('delete/(:num)', 'ThamGiaSuKien::delete/$1');
    $routes->get('restore/(:num)', 'ThamGiaSuKien::restore/$1');
    $routes->post('restore/(:num)', 'ThamGiaSuKien::restore/$1');
    $routes->post('purge/(:num)', 'ThamGiaSuKien::purge/$1');
    $routes->post('status/(:num)', 'ThamGiaSuKien::status/$1');
    $routes->post('deleteMultiple', 'ThamGiaSuKien::deleteMultiple');
    $routes->post('restoreMultiple', 'ThamGiaSuKien::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'ThamGiaSuKien::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'ThamGiaSuKien::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'ThamGiaSuKien::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'ThamGiaSuKien::deletePermanentMultiple');
    $routes->post('statusMultiple', 'ThamGiaSuKien::statusMultiple');
    $routes->get('deleted', 'ThamGiaSuKien::deleted');
    $routes->get('view/(:num)', 'ThamGiaSuKien::view/$1');
    $routes->get('exportPdf', 'ThamGiaSuKien::exportPdf');
    $routes->get('exportExcel', 'ThamGiaSuKien::exportExcel');
    $routes->get('exportDeletedPdf', 'ThamGiaSuKien::exportDeletedPdf');
    $routes->get('exportDeletedExcel', 'ThamGiaSuKien::exportDeletedExcel');
}); 