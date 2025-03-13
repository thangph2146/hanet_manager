<?php
namespace Config;

$routes = Services::routes();

$routes->group('account', ['namespace' => 'App\Modules\account\Controllers'], function ($routes) {
    $routes->get('login', 'Login::index');
    $routes->post('login/authenticate', 'Login::authenticate');
    $routes->get('logout', 'Login::logout');
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('google', 'GoogleAuthController::login');
    $routes->get('google-callback', 'GoogleAuthController::callback');
});

