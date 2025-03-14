<?php
namespace Config;

$routes = Services::routes();

$routes->group('sukien', ['namespace' => 'App\Modules\sukien\Controllers'], function ($routes) {
    $routes->get('/', 'Sukien::index');
    $routes->get('welcome', 'Sukien::index');
    $routes->get('checkin', 'Sukien::checkin');
    $routes->get('register', 'Sukien::register');
});

