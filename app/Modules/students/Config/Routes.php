<?php

namespace Config;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

// Routes cho dashboard sinh viên
$routes->get('students/dashboard', 'App\Modules\students\Controllers\StudentsController::dashboard');
$routes->get('@dashboard.php', 'App\Modules\students\Controllers\StudentsController::dashboard');

// Routes cho các tính năng khác của sinh viên
$routes->get('students/events', 'App\Modules\students\Controllers\StudentsController::events');
$routes->get('students/events/view/(:num)', 'App\Modules\students\Controllers\StudentsController::viewEvent/$1');
$routes->get('students/my-registrations', 'App\Modules\students\Controllers\StudentsController::myRegistrations');
$routes->get('students/certificates', 'App\Modules\students\Controllers\StudentsController::certificates');
$routes->get('students/profile', 'App\Modules\students\Controllers\StudentsController::profile');
$routes->get('students/change-password', 'App\Modules\students\Controllers\StudentsController::changePassword'); 