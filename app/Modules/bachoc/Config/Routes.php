<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('bachoc', ['namespace' => '\App\Modules\bachoc\Controllers'], function ($routes) {
    // Main routes
    $routes->get('/', 'BacHoc::index');
    $routes->get('new', 'BacHoc::new');
    $routes->get('listdeleted', 'BacHoc::listdeleted');
    $routes->get('edit/(:num)', 'BacHoc::edit/$1');
    
    // Action routes
    $routes->post('create', 'BacHoc::create');
    $routes->get('delete/(:num)', 'BacHoc::delete/$1');  
    $routes->get('restore/(:num)', 'BacHoc::restore/$1');
    $routes->get('permanentDelete/(:num)', 'BacHoc::permanentDelete/$1');
    $routes->get('status/(:num)', 'BacHoc::status/$1');
    $routes->post('update/(:num)', 'BacHoc::update/$1');
    $routes->post('delete/(:num)', 'BacHoc::delete/$1');
    $routes->post('restore/(:num)', 'BacHoc::restore/$1');
    $routes->post('permanentDelete/(:num)', 'BacHoc::permanentDelete/$1');
    $routes->post('status/(:num)', 'BacHoc::status/$1');
    
    // Bulk action routes
    $routes->post('deleteMultiple', 'BacHoc::deleteMultiple');
    $routes->post('restoreMultiple', 'BacHoc::restoreMultiple');
    $routes->post('permanentDeleteMultiple', 'BacHoc::permanentDeleteMultiple');
    $routes->post('statusMultiple', 'BacHoc::statusMultiple');
});