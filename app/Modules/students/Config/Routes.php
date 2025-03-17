<?php

namespace Config;

use CodeIgniter\Config\Services;
use App\Modules\students\Controllers\StudentsController;

// Lấy instance của RouteCollection
$routes = Services::routes();

// Student routes
$routes->group('students', function ($routes) {
    $routes->get('dashboard', '\App\Modules\students\Controllers\StudentsController::dashboard');
 
    

});