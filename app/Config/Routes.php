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
$routes->get('/', 'Home::index');

$routes->get('Roles', 'Roles::index');

// Permissions
$routes->get('permissions', 'Permissions::index');
$routes->match(['get', 'post'], 'permissions/new', 'Permissions::new');
$routes->match(['get', 'post'], 'permissions/create', 'Permissions::create');
$routes->get('permissions/edit/(:num)', 'Permissions::edit/$1');
$routes->match(['get', 'post'], 'permissions/update/(:num)', 'Permissions::update/$1');
$routes->get('permissions/delete/(:num)', 'Permissions::delete/$1');
$routes->get('permissions/listdeleted', 'Permissions::listDeleted');
$routes->get('permissions/restorepermission/(:num)', 'Permissions::restorePermission/$1');

// Role
$routes->get('roles', 'Roles::index');
$routes->match(['get', 'post'], 'roles/new', 'Roles::new');
$routes->match(['get', 'post'], 'roles/create', 'Roles::create');
$routes->get('roles/edit/(:num)', 'Roles::edit/$1');
$routes->match(['get', 'post'], 'roles/update/(:num)', 'Roles::update/$1');
$routes->get('roles/delete/(:num)', 'Roles::delete/$1');
$routes->get('roles/listdeleted', 'Roles::listDeleted');
$routes->get('roles/restorerole/(:num)', 'Roles::restoreRole/$1');
$routes->get('roles/assignpermissions/(:num)', 'Roles::assignPermissions/$1');
$routes->match(['get', 'post'], 'roles/updateassignpermissions/(:num)', 'Roles::UpdateAssignPermissions/$1');

// User
$routes->get('users', 'Users::index');
$routes->match(['get', 'post'], 'users/new', 'Users::new');
$routes->match(['get', 'post'], 'users/create', 'Users::create');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->match(['get', 'post'], 'users/update/(:num)', 'Users::update/$1');
$routes->get('users/delete/(:num)', 'Users::delete/$1');
$routes->get('users/listdeleted', 'Users::listDeleted');
$routes->get('users/restoreuser/(:num)', 'Users::restoreUser/$1');
$routes->get('users/assignroles/(:num)', 'Users::assignRoles/$1');
$routes->match(['get', 'post'], 'users/updateassignroles/(:num)', 'Users::UpdateAssignRoles/$1');
$routes->get('users/dashboard', 'Users::dashboard');
$routes->post('users/resetpassword', 'Users::resetPassWord');

// Settings
$routes->get('settings', 'Settings::index');
$routes->match(['get', 'post'], 'settings/new', 'Settings::new');
$routes->match(['get', 'post'], 'settings/create', 'Settings::create');
$routes->get('settings/edit/(:num)', 'Settings::edit/$1');
$routes->match(['get', 'post'], 'settings/update/(:num)', 'Settings::update/$1');
$routes->get('settings/delete/(:num)', 'Settings::delete/$1');

// Student dashboard và other student routes
$routes->post('students/create_student', 'Students::create_student');
$routes->get('students/logout', 'Students::logout');

// Sidebar routes
$routes->post('sidebar/update-state', 'SidebarController::updateState');
$routes->get('sidebar/get-state', 'SidebarController::getState');

// Thêm routes cho việc xóa loại người dùng
$routes->post('loainguoidung/delete/(:num)', 'App\Modules\loainguoidung\Controllers\LoaiNguoiDung::delete/$1');
$routes->post('loainguoidung/deleteMultiple', 'App\Modules\loainguoidung\Controllers\LoaiNguoiDung::deleteMultiple');
$routes->get('loainguoidung/deleted', 'App\Modules\loainguoidung\Controllers\LoaiNguoiDung::deleted');
$routes->get('loainguoidung/restore/(:num)', 'App\Modules\loainguoidung\Controllers\LoaiNguoiDung::restore/$1');
$routes->post('loainguoidung/restoreMultiple', 'App\Modules\loainguoidung\Controllers\LoaiNguoiDung::restoreMultiple');
$routes->get('loainguoidung/permanentDelete/(:num)', 'App\Modules\loainguoidung\Controllers\LoaiNguoiDung::permanentDelete/$1');

// Form builder example
$routes->group('form', static function ($routes) {
    $routes->get('basic', 'FormExampleController::basic');
    $routes->post('basic', 'FormExampleController::basic');
    
    $routes->get('advanced', 'FormExampleController::advanced');
    $routes->post('advanced', 'FormExampleController::advanced');
    
    $routes->get('edit-user', 'FormExampleController::editUser');
    $routes->post('edit-user', 'FormExampleController::editUser');
    
    $routes->get('time', 'FormExampleController::timeExample');
    $routes->post('time', 'FormExampleController::timeExample');
    
    $routes->get('product-table', 'FormExampleController::productTableExample');
    $routes->post('product-table', 'FormExampleController::productTableExample');
    
    $routes->get('timeline', 'FormExampleController::timelineExample');
    $routes->post('timeline', 'FormExampleController::timelineExample');
    
    $routes->get('upload', 'FormExampleController::uploadExample');
    $routes->post('upload', 'FormExampleController::uploadExample');
});

// Thêm routes cho TableBuilder
$routes->group('/table', static function ($routes) {
    $routes->get('basic', 'TableExampleController::basicExample');
    $routes->get('heading-footing', 'TableExampleController::headingFootingExample');
    $routes->get('custom-template', 'TableExampleController::customTemplateExample');
    $routes->get('datatable', 'TableExampleController::dataTableExample');
    $routes->get('export', 'TableExampleController::exportExample');
    $routes->get('database', 'TableExampleController::databaseExample');
    $routes->get('report', 'TableExampleController::reportExample');
});

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
