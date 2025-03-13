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
    $routes->get('deleteusers/(:num)', 'NguoiDungController::deleteUsers/$1');
    $routes->post('deleteusers', 'NguoiDungController::deleteUsers');
    $routes->post('deleteusers/(:num)', 'NguoiDungController::deleteUsers/$1');
    $routes->post('restoreusers', 'NguoiDungController::restoreUsers');
    $routes->get('restoreusers/(:num)', 'NguoiDungController::restoreUsers/$1');
    $routes->post('force-delete', 'NguoiDungController::forceDeleteUsers');
    
    // Routes cho chức năng reset password
    $routes->post('resetpassword', 'NguoiDungController::resetPassword');
});

// Routes cho chức năng đăng nhập không cần xác thực
$routes->group('', ['namespace' => 'App\Modules\nguoidung\Controllers'], function ($routes) {
    // Đăng nhập thông thường
    $routes->get('nguoidung/login', 'Login::index');
    $routes->post('nguoidung/login/authenticate', 'Login::authenticate');
    $routes->get('nguoidung/logout', 'Login::logout');

    // Đăng nhập với Google
    $routes->get('nguoidung/login/google', 'Login::google');
    $routes->get('nguoidung/login/google-callback', 'Login::googleCallback');

    // Quên mật khẩu
    $routes->get('nguoidung/forgot-password', 'Login::forgotPassword');
    $routes->post('nguoidung/forgot-password', 'Login::forgotPassword');
    
    // Đặt lại mật khẩu
    $routes->get('nguoidung/reset-password/(:any)', 'Login::resetPassword/$1');
    $routes->post('nguoidung/reset-password/(:any)', 'Login::resetPassword/$1');

    // Dashboard
    $routes->get('nguoidung/dashboard', 'Dashboard::index');
});

