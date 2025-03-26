<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();
$controller = 'LoaiNguoiDung';
$module = 'loainguoidung';
// Định nghĩa routes cho module Nganh
$routes->group($module, ['namespace' => 'App\Modules\\' . $module . '\Controllers'], 
function ($routes) use ($controller, $module) {
    $routes->get('/', $controller . '::index');
    $routes->get('dashboard', $controller . '::dashboard');
    $routes->get('statistics', $controller . '::statistics');
    $routes->get('listdeleted', $controller . '::listdeleted');
    $routes->get('new', $controller . '::new');
    $routes->post('create', $controller . '::create');
    $routes->get('edit/(:num)', $controller . '::edit/$1');
    $routes->post('update/(:num)', $controller . '::update/$1');
    $routes->get('delete/(:num)', $controller . '::delete/$1');
    $routes->post('delete/(:num)', $controller . '::delete/$1');
    $routes->get('restore/(:num)', $controller . '::restore/$1');
    $routes->post('restore/(:num)', $controller . '::restore/$1');
    $routes->post('purge/(:num)', $controller . '::purge/$1');
    $routes->post('status/(:num)', $controller . '::status/$1');
    $routes->post('deleteMultiple', $controller . '::deleteMultiple');
    $routes->post('restoreMultiple', $controller . '::restoreMultiple');
    $routes->get('permanentDelete/(:num)', $controller . '::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', $controller . '::permanentDelete/$1');
    $routes->post('permanentDeleteMultiple', $controller . '::permanentDeleteMultiple');
    $routes->post('deletePermanentMultiple', $controller . '::deletePermanentMultiple');
    $routes->post('statusMultiple', $controller . '::statusMultiple');
    $routes->get('deleted', $controller . '::deleted');
    $routes->get('view/(:num)', $controller . '::view/$1');
    $routes->get('exportPdf', $controller . '::exportPdf');
    $routes->get('exportExcel', $controller . '::exportExcel');
    $routes->get('exportDeletedPdf', $controller . '::exportDeletedPdf');
    $routes->get('exportDeletedExcel', $controller . '::exportDeletedExcel');
}); 