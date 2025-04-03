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
    $routes->get('events', $controller_name . '::events');
    $routes->get('events/current', $controller_name . '::currentEvents');
    $routes->get('events/list', $controller_name . '::eventsList');
    $routes->get('events/history', $controller_name . '::eventsHistory');
    $routes->get('events/details/(:num)', $controller_name . '::eventDetails/$1');
    $routes->post('events/register', $controller_name . '::registerEvent');
    $routes->post('events/cancel', $controller_name . '::cancelRegistration');
    $routes->post('events/register-again', $controller_name . '::registerAgain');
    $routes->get('events/join/(:num)', $controller_name . '::joinEvent/$1');
    $routes->get('certificate/download/(:segment)', $controller_name . '::downloadCertificate/$1');
});