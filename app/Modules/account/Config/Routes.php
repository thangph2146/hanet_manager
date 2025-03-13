<?php
namespace Config;

$routes = Services::routes();


$routes->group('account', ['namespace' => 'App\Modules\account\Controllers'], function ($routes) {
    $routes->get('login', 'Login::index');
    $routes->post('login/authenticate', 'Login::authenticate');
    $routes->get('logout', 'Login::logout');

    // Google login routes
    $routes->get('login/google', 'LoginGoogle::index');
    $routes->get('login/google/callback', 'LoginGoogle::googleCallback');

    // dashboard
    $routes->get('dashboard', 'Dashboard::index');
});

