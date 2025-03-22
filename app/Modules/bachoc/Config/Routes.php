<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Định nghĩa routes cho module BacHoc
$routes->group('bachoc', ['namespace' => 'App\Modules\bachoc\Controllers'], function ($routes) {
    $routes->get('/', 'BacHoc::index');
    $routes->get('listdeleted', 'BacHoc::listdeleted');
    $routes->get('new', 'BacHoc::new');
    $routes->post('create', 'BacHoc::create');
    $routes->get('edit/(:num)', 'BacHoc::edit/$1');
    $routes->post('update/(:num)', 'BacHoc::update/$1');
    $routes->get('delete/(:num)', 'BacHoc::delete/$1');
    $routes->post('delete/(:num)', 'BacHoc::delete/$1');
    $routes->get('restore/(:num)', 'BacHoc::restore/$1');
    $routes->post('restore/(:num)', 'BacHoc::restore/$1');
    $routes->get('toggleStatus/(:num)', 'BacHoc::toggleStatus/$1');
    $routes->post('toggleStatus/(:num)', 'BacHoc::toggleStatus/$1');
    $routes->post('deleteMultiple', 'BacHoc::deleteMultiple');
    $routes->post('restoreMultiple', 'BacHoc::restoreMultiple');
    $routes->get('permanentDelete/(:num)', 'BacHoc::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', 'BacHoc::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', 'BacHoc::permanentDeleteMultiple');
}); 