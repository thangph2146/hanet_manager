<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module Nganh
$routes->group('nganh', ['namespace' => 'App\Modules\nganh\Controllers'], function ($routes) {
    $routes->get('/', 'Nganh::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'Nganh::listdeleted');
    $routes->get('new', 'Nganh::new');
    $routes->post('create', 'Nganh::create');
    $routes->get('edit/(:num)', 'Nganh::edit/$1');
    $routes->post('update/(:num)', 'Nganh::update/$1');
    $routes->get('delete/(:num)', 'Nganh::delete/$1');
    $routes->post('delete/(:num)', 'Nganh::delete/$1');
    $routes->get('restore/(:num)', 'Nganh::restore/$1');
    $routes->post('restore/(:num)', 'Nganh::restore/$1');
    $routes->post('purge/(:num)', 'Nganh::purge/$1');
    $routes->post('status/(:num)', 'Nganh::status/$1');
    $routes->post('deleteMultiple', 'Nganh::deleteMultiple');
    $routes->post('restoreMultiple', 'Nganh::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'Nganh::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'Nganh::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'Nganh::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'Nganh::deletePermanentMultiple');
    $routes->post('statusMultiple', 'Nganh::statusMultiple');
    $routes->get('deleted', 'Nganh::deleted');
    $routes->get('view/(:num)', 'Nganh::view/$1');
}); 