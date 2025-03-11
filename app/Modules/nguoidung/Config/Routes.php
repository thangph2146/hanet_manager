<?php

namespace Modules\Users\Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('nguoidung', ['namespace' => 'Modules\nguoidung\Controllers'], function ($routes) {
    $routes->get('/', 'nguoidung::index');
});

