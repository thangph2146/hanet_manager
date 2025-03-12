<?php
namespace Config;

$routes = Services::routes();

$routes->group('nguoidung', ['namespace' => 'App\Modules\nguoidung\Controllers'], function ($routes) {
    $routes->get('/', 'NguoiDungController::index');
    $routes->get('new', 'NguoiDungController::new');
    $routes->post('store', 'NguoiDungController::store');
    $routes->get('edit/(:num)', 'NguoiDungController::edit/$1');
    $routes->post('update/(:num)', 'NguoiDungController::update/$1');
    $routes->post('delete', 'NguoiDungController::deleteUsers');
    $routes->post('restore', 'NguoiDungController::restoreUsers');
    $routes->post('force-delete', 'NguoiDungController::forceDeleteUsers');
});

