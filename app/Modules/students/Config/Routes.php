<?php

namespace Config;

use CodeIgniter\Config\Services;
use App\Modules\students\Controllers\StudentsController;

// Lấy instance của RouteCollection
$routes = Services::routes();

// Student routes
$routes->group('students', function ($routes) {
    $routes->get('dashboard', '\App\Modules\students\Controllers\StudentsController::dashboard');
    $routes->get('profile', '\App\Modules\students\Controllers\StudentsController::profile');
    $routes->get('logout', '\App\Modules\students\Controllers\StudentsController::logout');
    
    // Các route khác cho student
    $routes->get('courses', '\App\Modules\students\Controllers\StudentsController::courses');
    $routes->get('schedules', '\App\Modules\students\Controllers\StudentsController::schedules');
    $routes->get('exams', '\App\Modules\students\Controllers\StudentsController::exams');
    $routes->get('grades', '\App\Modules\students\Controllers\StudentsController::grades');
    $routes->get('fees', '\App\Modules\students\Controllers\StudentsController::fees');
    $routes->get('certificates', '\App\Modules\students\Controllers\StudentsController::certificates');
    $routes->get('notifications', '\App\Modules\students\Controllers\StudentsController::notifications');
    $routes->get('help', '\App\Modules\students\Controllers\StudentsController::help');
    $routes->get('settings', '\App\Modules\students\Controllers\StudentsController::settings');
    
    // Routes cho quản lý sự kiện
    $routes->group('events', function ($routes) {
        $routes->get('/', '\App\Modules\students\Controllers\StudentsController::events');
        $routes->get('registered', '\App\Modules\students\Controllers\StudentsController::registeredEvents');
        $routes->get('completed', '\App\Modules\students\Controllers\StudentsController::completedEvents');
        $routes->get('detail/(:num)', '\App\Modules\students\Controllers\StudentsController::eventDetail/$1');
        $routes->get('certificate/(:num)', '\App\Modules\students\Controllers\StudentsController::eventCertificate/$1');
        
        // Routes xử lý AJAX
        $routes->post('register', '\App\Modules\students\Controllers\StudentsController::registerEvent');
        $routes->post('cancel', '\App\Modules\students\Controllers\StudentsController::cancelRegistration');
        $routes->post('attendance', '\App\Modules\students\Controllers\StudentsController::eventAttendance');
    });
});