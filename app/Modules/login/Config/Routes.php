<?php

namespace Config;

if (!isset($routes)) {
    $routes = \Config\Services::routes();
}

// Cấu hình namespace cho module này
$routes->setDefaultNamespace('App\Modules\login\Controllers');

// Routes cho login student sử dụng controller mới
$routes->get('login', function() {
    return redirect()->to('login/nguoi-dung');
});
$routes->get('dang-ky', 'LoginController::register');
$routes->get('@login.php', 'LoginController::index');
$routes->get('login/nguoi-dung', 'LoginController::index');
$routes->post('login/create_nguoidung', 'LoginController::create_nguoidung');
$routes->post('login/nguoidung/create', 'LoginController::create_nguoidung_account');

// Routes cho logout student
$routes->get('login/logoutnguoidung', 'LoginController::deletenguoidung');
$routes->get('login/showlogoutmessagenguoidung', 'LoginController::showLogoutMessageNguoidung');

// Routes cho login admin - sử dụng AdminController
$routes->get('login/admin', 'AdminController::index');
$routes->post('login/create', 'AdminController::create');
$routes->get('login/logout', 'AdminController::logout');
$routes->get('login/showlogoutmessage', 'AdminController::showLogoutMessage');

// Route cho Google OAuth - Phân loại theo state
$routes->get('google-callback', function() {
    $request = \Config\Services::request();
    $state = $request->getGet('state');
    
    if ($state == 'student') {
        return redirect()->to('google-callback/student?' . http_build_query($_GET));
    } else {
        return redirect()->to('google-callback/admin?' . http_build_query($_GET));
    }
});
$routes->get('google-callback/student', 'LoginController::googleCallback');
$routes->get('google-callback/admin', 'AdminController::googleCallback'); 