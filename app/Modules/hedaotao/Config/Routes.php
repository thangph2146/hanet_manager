<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module HeDaoTao
$routes->group('hedaotao', ['namespace' => 'App\Modules\hedaotao\Controllers'], function ($routes) {
    $routes->get('/', 'HeDaoTao::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('statistics', 'Dashboard::statistics');
    $routes->get('listdeleted', 'HeDaoTao::listdeleted');
    $routes->get('new', 'HeDaoTao::new');
    $routes->post('create', 'HeDaoTao::create');
    $routes->get('edit/(:num)', 'HeDaoTao::edit/$1');
    $routes->post('update/(:num)', 'HeDaoTao::update/$1');
    $routes->get('delete/(:num)', 'HeDaoTao::delete/$1');
    $routes->post('delete/(:num)', 'HeDaoTao::delete/$1');
    $routes->get('restore/(:num)', 'HeDaoTao::restore/$1');
    $routes->post('restore/(:num)', 'HeDaoTao::restore/$1');
    $routes->post('purge/(:num)', 'HeDaoTao::purge/$1');
    $routes->post('status/(:num)', 'HeDaoTao::status/$1');
    $routes->post('deleteMultiple', 'HeDaoTao::deleteMultiple');
    $routes->post('restoreMultiple', 'HeDaoTao::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'HeDaoTao::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'HeDaoTao::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'HeDaoTao::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', 'HeDaoTao::deletePermanentMultiple');
    $routes->post('statusMultiple', 'HeDaoTao::statusMultiple');
    $routes->get('deleted', 'HeDaoTao::deleted');
}); 