<?php

namespace Config;

use CodeIgniter\Config\Services;
use App\Modules\students\Controllers\StudentsController;

// Lấy instance của RouteCollection
$routes = Services::routes();
$route_url = "student";
$module_name = "students";
// Student routes
$routes->group($route_url, function ($routes) use ($module_name) {
    $routes->get('dashboard', '\App\Modules\\$module_name\Controllers\\$module_name\Controller::dashboard');
 
    

});