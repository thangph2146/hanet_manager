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
    $routes->get('thong-tin-ca-nhan', $controller_name . '::profile');
    $routes->post('profile/update', $controller_name . '::updateProfile');
    $routes->get('dashboard', $controller_name . '::dashboard');
    $routes->get('su-kien-da-tham-gia', $controller_name . '::eventsHistoryRegister');
    $routes->get('danh-sach-su-kien', $controller_name . '::eventsList');
    $routes->get('su-kien-da-dang-ky', $controller_name . '::eventsCheckin');
});

// API Routes
$routes->group('api/' . $route_url, ['namespace' => 'App\Modules\\' . $module_name . '\Controllers'], 
function ($routes) use ($controller_name) {
    $routes->post('profile/update', $controller_name . '::updateProfile');
});