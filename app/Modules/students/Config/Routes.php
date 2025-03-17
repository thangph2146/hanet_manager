<?php

namespace Config;

use CodeIgniter\Config\Services;

// Lấy instance của RouteCollection
$routes = Services::routes();

// Định nghĩa namespace cho module students
$routes->group('students', ['namespace' => 'App\Modules\students\Controllers'], function ($routes) {
    // Route chính cho dashboard
    $routes->get('dashboard', 'StudentsController::dashboard');
    
    // Routes cho events
    $routes->group('events', function ($routes) {
        $routes->get('/', 'EventsController::index');
        $routes->get('view/(:num)', 'EventsController::view/$1');
        $routes->get('register/(:num)', 'EventsController::register/$1');
        $routes->post('register/(:num)', 'EventsController::saveRegistration/$1');
    });
    
    // Routes cho certificates
    $routes->group('certificates', function ($routes) {
        $routes->get('/', 'CertificatesController::index');
        $routes->get('view/(:num)', 'CertificatesController::view/$1');
        $routes->get('download/(:num)', 'CertificatesController::download/$1');
    });
    
    // Routes cho profile
    $routes->group('profile', function ($routes) {
        $routes->get('/', 'ProfileController::index');
        $routes->get('edit', 'ProfileController::edit');
        $routes->post('update', 'ProfileController::update');
        $routes->get('change-password', 'ProfileController::changePassword');
        $routes->post('update-password', 'ProfileController::updatePassword');
    });
    
    // Routes cho registrations (đăng ký của tôi)
    $routes->group('registrations', function ($routes) {
        $routes->get('/', 'RegistrationsController::index');
        $routes->get('view/(:num)', 'RegistrationsController::view/$1');
        $routes->get('cancel/(:num)', 'RegistrationsController::cancel/$1');
        $routes->post('cancel/(:num)', 'RegistrationsController::saveCancel/$1');
    });
}); 