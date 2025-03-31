<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Cấu hình URL route cho module, sử dụng 'admin/bachoc' thay vì 'bachoc'
$module_namespace = 'bachoc';
$controller_name = 'BacHoc';
$route_url = 'admin/bachoc';

// Định nghĩa routes cho module Bachoc
$routes->group($route_url, ['namespace' => 'App\Modules\\' . $module_namespace . '\Controllers'], 
function ($routes) use ($controller_name) {
    $routes->get('/', $controller_name . '::index');
    $routes->get('dashboard', $controller_name . '::dashboard');
    $routes->get('statistics', $controller_name . '::statistics');
    $routes->get('listdeleted', $controller_name . '::listdeleted');
    $routes->get('new', $controller_name . '::new');
    $routes->post('create', $controller_name . '::create');
    $routes->get('edit/(:num)', $controller_name . '::edit/$1');
    $routes->post('update/(:num)', $controller_name . '::update/$1');
    $routes->get('delete/(:num)', $controller_name . '::delete/$1');
    $routes->post('delete/(:num)', $controller_name . '::delete/$1');
    $routes->get('restore/(:num)', $controller_name . '::restore/$1');
    $routes->post('restore/(:num)', $controller_name . '::restore/$1');
    $routes->post('purge/(:num)', $controller_name . '::purge/$1');
    $routes->post('status/(:num)', $controller_name . '::status/$1');
    $routes->post('deleteMultiple', $controller_name . '::deleteMultiple');
    $routes->post('restoreMultiple', $controller_name . '::restoreMultiple');
    $routes->get('permanentDelete/(:num)', $controller_name . '::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', $controller_name . '::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', $controller_name . '::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', $controller_name . '::deletePermanentMultiple');
    $routes->post('statusMultiple', $controller_name . '::statusMultiple');
    $routes->get('deleted', $controller_name . '::deleted');
    $routes->get('show/(:num)', $controller_name . '::show/$1');
    $routes->get('exportPdf', $controller_name . '::exportPdf');
    $routes->get('exportExcel', $controller_name . '::exportExcel');
    $routes->get('exportDeletedPdf', $controller_name . '::exportDeletedPdf');
    $routes->get('exportDeletedExcel', $controller_name . '::exportDeletedExcel');
}); 