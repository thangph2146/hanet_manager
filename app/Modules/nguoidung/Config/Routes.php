<?php
namespace Config;

$routes = Services::routes();

$routes->group('nguoidung', ['namespace' => 'App\Modules\nguoidung\Controllers'], function ($routes) {
    $routes->get('/', 'NguoiDungController::index');
    $routes->get('create', 'NguoiDungController::create');
    $routes->post('store', 'NguoiDungController::store');
    $routes->get('show/(:num)', 'NguoiDungController::show/$1');
    $routes->get('edit/(:num)', 'NguoiDungController::edit/$1');
    $routes->post('update/(:num)', 'NguoiDungController::update/$1');
    $routes->get('delete/(:num)', 'NguoiDungController::delete/$1');
    $routes->get('trash', 'NguoiDungController::trash');
    $routes->get('restore/(:num)', 'NguoiDungController::restore/$1');
    $routes->get('purge/(:num)', 'NguoiDungController::purge/$1');
    $routes->get('search', 'NguoiDungController::search');
});
