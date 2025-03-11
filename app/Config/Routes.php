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
    if ($module === '.' || $module === '..') continue;
    
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
$routes->get('Permissions', 'Permissions::index');
$routes->match(['get', 'post'], 'Permissions/new', 'Permissions::new');
$routes->match(['get', 'post'], 'Permissions/create', 'Permissions::create');
$routes->get('Permissions/edit/(:num)', 'Permissions::edit/$1');
$routes->match(['get', 'post'], 'Permissions/update/(:num)', 'Permissions::update/$1');
$routes->get('Permissions/delete/(:num)', 'Permissions::delete/$1');
$routes->get('Permissions/listDeleted', 'Permissions::listDeleted');
$routes->get('Permissions/restorePermission/(:num)', 'Permissions::restorePermission/$1');

// Role
$routes->get('Roles', 'Roles::index');
$routes->match(['get', 'post'], 'Roles/new', 'Roles::new');
$routes->match(['get', 'post'], 'Roles/create', 'Roles::create');
$routes->get('Roles/edit/(:num)', 'Roles::edit/$1');
$routes->match(['get', 'post'], 'Roles/update/(:num)', 'Roles::update/$1');
$routes->get('Roles/delete/(:num)', 'Roles::delete/$1');
$routes->get('Roles/listDeleted', 'Roles::listDeleted');
$routes->get('Roles/restoreRole/(:num)', 'Roles::restoreRole/$1');
$routes->get('Roles/assignPermissions/(:num)', 'Roles::assignPermissions/$1');
$routes->match(['get', 'post'], 'Roles/UpdateAssignPermissions/(:num)', 'Roles::UpdateAssignPermissions/$1');

// User
$routes->get('Users', 'Users::index');
$routes->match(['get', 'post'], 'Users/new', 'Users::new');
$routes->match(['get', 'post'], 'Users/create', 'Users::create');
$routes->get('Users/edit/(:num)', 'Users::edit/$1');
$routes->match(['get', 'post'], 'Users/update/(:num)', 'Users::update/$1');
$routes->get('Users/delete/(:num)', 'Users::delete/$1');
$routes->get('Users/listDeleted', 'Users::listDeleted');
$routes->get('Users/restoreUser/(:num)', 'Users::restoreUser/$1');
$routes->get('Users/assignRoles/(:num)', 'Users::assignRoles/$1');
$routes->match(['get', 'post'], 'Users/UpdateAssignRoles/(:num)', 'Users::UpdateAssignRoles/$1');
$routes->get('Users/dashboard', 'Users::dashboard');
$routes->post('Users/resetPassWord', 'Users::resetPassWord');

// Login user
$routes->post('Login/create', 'Login::create');
$routes->get('Login/admin', 'Login::admin');
$routes->get('Login/logout', 'Login::delete');
$routes->get('Login/showLogoutMessage', 'Login::showLogoutMessage');
$routes->get('login/admin', 'Login::admin');
$routes->get('google-callback', 'Login::googleCallback');

// Settings
$routes->get('Settings', 'Settings::index');
$routes->match(['get', 'post'], 'Settings/new', 'Settings::new');
$routes->match(['get', 'post'], 'Settings/create', 'Settings::create');
$routes->get('Settings/edit/(:num)', 'Settings::edit/$1');
$routes->match(['get', 'post'], 'Settings/update/(:num)', 'Settings::update/$1');
$routes->get('Settings/delete/(:num)', 'Settings::delete/$1');


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
