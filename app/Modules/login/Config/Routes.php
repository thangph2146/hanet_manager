<?php

namespace Config;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// Routes cho login student sử dụng controller mới
$routes->get('login', 'App\Modules\login\Controllers\LoginController::index');
$routes->get('@login.php', 'App\Modules\login\Controllers\LoginController::index');
$routes->get('login/student', 'App\Modules\login\Controllers\LoginController::index');
$routes->post('login/create_student', 'App\Modules\login\Controllers\LoginController::create_student');

// Routes cho logout student
$routes->get('login/logoutstudent', 'App\Modules\login\Controllers\LoginController::deleteStudent');
$routes->get('login/showlogoutmessagestudent', 'App\Modules\login\Controllers\LoginController::showLogoutMessageStudent');

// Routes cho login admin
$routes->post('login/create', 'App\Controllers\Login::create');
$routes->get('login/admin', 'App\Controllers\Login::admin');
$routes->get('login/logout', 'App\Controllers\Login::delete');
$routes->get('login/showlogoutmessage', 'App\Controllers\Login::showLogoutMessage');

// Route cho Google OAuth
$routes->get('google-callback', 'App\Controllers\Login::googleCallback');
$routes->get('google-callback/(:any)', 'App\Controllers\Login::googleCallback/$1'); 