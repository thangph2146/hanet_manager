<?php

namespace Config;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// Routes cho login student sử dụng controller mới
$routes->get('login', function() {
    return redirect()->to('login/student');
});
$routes->get('login/student', 'App\Modules\login\Controllers\LoginController::index');
$routes->post('login/create_student', 'App\Modules\login\Controllers\LoginController::create_student');

// Routes cho logout student
$routes->get('login/logoutstudent', 'App\Modules\login\Controllers\LoginController::deleteStudent');
$routes->get('login/showlogoutmessagestudent', 'App\Modules\login\Controllers\LoginController::showLogoutMessageStudent');

// Routes cho login admin - sử dụng AdminController
$routes->get('login/admin', 'App\Modules\login\Controllers\AdminController::index');
$routes->post('login/create', 'App\Modules\login\Controllers\AdminController::create');
$routes->get('login/logout', 'App\Modules\login\Controllers\AdminController::logout');
$routes->get('login/showlogoutmessage', 'App\Modules\login\Controllers\AdminController::showLogoutMessage');

// Route cho Google OAuth
$routes->get('google-callback', 'App\Modules\login\Controllers\AdminController::googleCallback');
$routes->get('google-callback/(:any)', 'App\Modules\login\Controllers\AdminController::googleCallback/$1'); 