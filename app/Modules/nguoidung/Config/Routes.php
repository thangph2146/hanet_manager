<?php
namespace Config;

$routes = Services::routes();

$routes->group('nguoidung', ['namespace' => 'App\Modules\nguoidung\Controllers'], function ($routes) {
    $routes->get('/', 'NguoiDungController::index');
    $routes->get('new', 'NguoiDungController::new');
    $routes->post('store', 'NguoiDungController::store');
    $routes->get('edit/(:num)', 'NguoiDungController::edit/$1');
    $routes->post('update/(:num)', 'NguoiDungController::update/$1');
    
    // Routes cho chức năng xóa mềm và khôi phục
    $routes->get('listdeleted', 'NguoiDungController::listDeleted');
    $routes->post('delete', 'NguoiDungController::deleteUsers');
    $routes->post('restoreusers', 'NguoiDungController::restoreUsers');
    $routes->get('restoreusers/(:num)', 'NguoiDungController::restoreUsers/$1');
    $routes->post('force-delete', 'NguoiDungController::forceDeleteUsers');
    
    // Routes cho chức năng reset password
    $routes->post('resetpassword', 'NguoiDungController::resetPassword');
});

