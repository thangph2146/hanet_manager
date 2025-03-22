<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('namhoc', ['namespace' => 'App\Modules\namhoc\Controllers'], function ($routes) {
    $routes->get('/', 'NamHoc::index');    
    $routes->get('new', 'NamHoc::new');
    $routes->post('create', 'NamHoc::create');
    $routes->get('edit/(:num)', 'NamHoc::edit/$1');
    $routes->post('update/(:num)', 'NamHoc::update/$1');
    $routes->post('delete/(:num)', 'NamHoc::delete/$1');
    $routes->post('status/(:num)', 'NamHoc::status/$1');
    $routes->post('bulkDelete', 'NamHoc::bulkDelete');
    $routes->post('deleteMultiple', 'NamHoc::bulkDelete');
    $routes->post('statusMultiple', 'NamHoc::statusMultiple');
    $routes->get('listdeleted', 'NamHoc::listdeleted');
    $routes->post('restore/(:num)', 'NamHoc::restore/$1');
    $routes->post('bulkRestore', 'NamHoc::bulkRestore');
    $routes->post('restore', 'NamHoc::bulkRestore');
    $routes->post('permanentDelete/(:num)', 'NamHoc::permanentDelete/$1');
    $routes->post('bulkPermanentDelete', 'NamHoc::bulkPermanentDelete');
    $routes->post('permanentDelete', 'NamHoc::bulkPermanentDelete');
    $routes->get('debug', 'NamHoc::debug');
}); 