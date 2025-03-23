<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('loaisukien', ['namespace' => 'App\Modules\loaisukien\Controllers'], function ($routes) {
    $routes->get('/', 'LoaiSukien::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'LoaiSukien::listdeleted');
    $routes->get('new', 'LoaiSukien::new');
    $routes->post('create', 'LoaiSukien::create');
    $routes->get('edit/(:num)', 'LoaiSukien::edit/$1');
    $routes->post('update/(:num)', 'LoaiSukien::update/$1');
    $routes->get('delete/(:num)', 'LoaiSukien::delete/$1');
    $routes->post('delete/(:num)', 'LoaiSukien::delete/$1');
    $routes->get('restore/(:num)', 'LoaiSukien::restore/$1');
    $routes->post('restore/(:num)', 'LoaiSukien::restore/$1');
    $routes->post('purge/(:num)', 'LoaiSukien::purge/$1');
    $routes->post('status/(:num)', 'LoaiSukien::status/$1');
    $routes->post('deleteMultiple', 'LoaiSukien::deleteMultiple');
    $routes->post('restoreMultiple', 'LoaiSukien::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'LoaiSukien::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'LoaiSukien::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'LoaiSukien::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'LoaiSukien::deletePermanentMultiple');
    $routes->post('statusMultiple', 'LoaiSukien::statusMultiple');
    $routes->get('deleted', 'LoaiSukien::deleted');
    $routes->get('view/(:num)', 'LoaiSukien::view/$1');
    $routes->get('exportPdf', 'LoaiSukien::exportPdf');
    $routes->get('exportExcel', 'LoaiSukien::exportExcel');
}); 