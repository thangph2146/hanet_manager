<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();


// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

// Load routes của từng module
$modulesPath = APPPATH . 'Modules/';
$modules = scandir($modulesPath);

foreach ($modules as $module) {
    if ($module === '.' || $module === '..') {
        continue;
    }

    $routesPath = $modulesPath . $module . '/Config/Routes.php';
    if (file_exists($routesPath)) {
        require $routesPath;
    }
}
/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->GET('/', 'Home::index');

$routes->GET('Roles', 'Roles::index');

// Permissions
$routes->GET('permissions', 'Permissions::index');
$routes->match(['GET', 'POST'], 'permissions/new', 'Permissions::new');
$routes->match(['GET', 'POST'], 'permissions/create', 'Permissions::create');
$routes->GET('permissions/edit/(:num)', 'Permissions::edit/$1');
$routes->match(['GET', 'POST'], 'permissions/update/(:num)', 'Permissions::update/$1');
$routes->GET('permissions/delete/(:num)', 'Permissions::delete/$1');
$routes->GET('permissions/listdeleted', 'Permissions::listDeleted');
$routes->GET('permissions/restorepermission/(:num)', 'Permissions::restorePermission/$1');

// Role
$routes->GET('roles', 'Roles::index');
$routes->match(['GET', 'POST'], 'roles/new', 'Roles::new');
$routes->match(['GET', 'POST'], 'roles/create', 'Roles::create');
$routes->GET('roles/edit/(:num)', 'Roles::edit/$1');
$routes->match(['GET', 'POST'], 'roles/update/(:num)', 'Roles::update/$1');
$routes->GET('roles/delete/(:num)', 'Roles::delete/$1');
$routes->GET('roles/listdeleted', 'Roles::listDeleted');
$routes->GET('roles/restorerole/(:num)', 'Roles::restoreRole/$1');
$routes->GET('roles/assignpermissions/(:num)', 'Roles::assignPermissions/$1');
$routes->match(['GET', 'POST'], 'roles/updateassignpermissions/(:num)', 'Roles::UpdateAssignPermissions/$1');

// User
$routes->GET('users', 'Users::index');
$routes->match(['GET', 'POST'], 'users/new', 'Users::new');
$routes->match(['GET', 'POST'], 'users/create', 'Users::create');
$routes->GET('users/edit/(:num)', 'Users::edit/$1');
$routes->match(['GET', 'POST'], 'users/update/(:num)', 'Users::update/$1');
$routes->GET('users/delete/(:num)', 'Users::delete/$1');
$routes->GET('users/listdeleted', 'Users::listDeleted');
$routes->GET('users/restoreuser/(:num)', 'Users::restoreUser/$1');
$routes->GET('users/assignroles/(:num)', 'Users::assignRoles/$1');
$routes->match(['GET', 'POST'], 'users/updateassignroles/(:num)', 'Users::UpdateAssignRoles/$1');
$routes->GET('users/dashboard', 'Users::dashboard');
$routes->POST('users/resetpassword', 'Users::resetPassWord');

// Settings
$routes->GET('settings', 'Settings::index');
$routes->match(['GET', 'POST'], 'settings/new', 'Settings::new');
$routes->match(['GET', 'POST'], 'settings/create', 'Settings::create');
$routes->GET('settings/edit/(:num)', 'Settings::edit/$1');
$routes->match(['GET', 'POST'], 'settings/update/(:num)', 'Settings::update/$1');
$routes->GET('settings/delete/(:num)', 'Settings::delete/$1');

// Student dashboard và other student routes
$routes->POST('students/create_student', 'Students::create_student');
$routes->GET('students/logout', 'Students::logout');

// Sidebar routes
$routes->POST('sidebar/update-state', 'SidebarController::updateState');
$routes->GET('sidebar/get-state', 'SidebarController::getState');

// New routes for TestDeletedRecords
$routes->get('quanlycheckoutsukien/test-deleted-records', '\App\Modules\quanlycheckoutsukien\Controllers\TestDeletedRecords::index');
$routes->get('quanlycheckoutsukien/test-deleted-records/detail/(:num)', '\App\Modules\quanlycheckoutsukien\Controllers\TestDeletedRecords::detail/$1');
$routes->get('quanlycheckoutsukien/test-deleted-records/get-all', '\App\Modules\quanlycheckoutsukien\Controllers\TestDeletedRecords::getAllDeleted');
$routes->get('quanlycheckoutsukien/test-deleted-records/compare-methods', '\App\Modules\quanlycheckoutsukien\Controllers\TestDeletedRecords::compareModelMethods');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
