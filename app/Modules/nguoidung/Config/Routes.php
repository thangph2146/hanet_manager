<?php

namespace Config;

// Lấy instance của RouteCollection
$routes = Services::routes();
$route_url = "nguoi-dung";
$module_name = "nguoidung";
$controller_name = "NguoiDung";


// Nguoi dung routes
$routes->group($route_url, ['namespace' => 'App\Modules\\' . $module_name . '\Controllers'], 
function ($routes) use ($controller_name) {
    $routes->get('/', $controller_name . '::index');
    $routes->get('profile', $controller_name . '::profile');
    $routes->get('dashboard', $controller_name . '::dashboard');
    $routes->get('events-history-register', $controller_name . '::eventsHistoryRegister');
    $routes->get('events-checkin', $controller_name . '::eventsCheckin');
    $routes->get('events-list', $controller_name . '::eventsList');
});