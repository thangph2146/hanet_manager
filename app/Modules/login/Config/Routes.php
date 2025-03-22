<?php

namespace Config;

if (!isset($routes)) {
    $routes = \Config\Services::routes();
}

// Cấu hình namespace cho module này
$routes->setDefaultNamespace('App\Modules\login\Controllers');

// Routes cho login student sử dụng controller mới
$routes->get('login', function() {
    return redirect()->to('login/student');
});
$routes->get('@login.php', 'LoginController::index');
$routes->get('login/student', 'LoginController::index');
$routes->post('login/create_student', 'LoginController::create_student');

// Routes cho logout student
$routes->get('login/logoutstudent', 'LoginController::deleteStudent');
$routes->get('login/showlogoutmessagestudent', 'LoginController::showLogoutMessageStudent');

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