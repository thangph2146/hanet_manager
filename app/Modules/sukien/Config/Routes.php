<?php
namespace Config;
// Create a new instance of our RouteCollection class.
$routes = Services::routes();
$module_name = 'su-kien';
$controller_name = 'SuKien';
$sitemap_controller_name = 'Sitemap';

$routes->group($module_name, ['namespace' => 'App\Modules\sukien\Controllers'], function ($routes) use ($controller_name, $sitemap_controller_name) {
    $routes->get('/', $controller_name . '::index');
    
    // Sử dụng slug thay vì ID cho trang chi tiết
    $routes->get('chi-tiet/(:segment)', $controller_name . '::detail/$1', ['as' => 'sukien_detail']); // Thêm alias cho route
    
    // Sử dụng slug cho danh mục
    $routes->get('loai/(:segment)', $controller_name . '::category/$1');
    
    // Route for registering to events
    $routes->post('register', $controller_name . '::register');
    $routes->post('checkin', $controller_name . '::checkin');
    $routes->post('checkout', $controller_name . '::checkout');
    
    // Route for AJAX getting event views
    $routes->post('su-kien/get-events-view', $controller_name . '::getEventsView');
    
    // Sitemap for SEO
    $routes->get('sitemap.xml', $sitemap_controller_name . '::index');
    
    // Routes for Hanet integration - Thứ tự quan trọng, chi tiết hơn đặt trước
    $routes->get('checkin-display/preview', $controller_name . '::previewCheckinDisplay'); // Route mới để xem trước màn hình checkin
    $routes->get('checkin-display/(:num)', $controller_name . '::displayCheckin/$1'); // Route với tham số sự kiện
    $routes->get('checkin-display', $controller_name . '::displayCheckin'); // Route mặc định
    $routes->get('api/checkin-display', $controller_name . '::getCheckinDisplay');
    
    // Route cho đăng ký ngay sự kiện - Phải đặt dạng match để chấp nhận cả GET và POST
    $routes->match(['get', 'post'], 'register-now', $controller_name . '::registerNow');
    
    // Route debug đăng ký sự kiện
    $routes->get('debug-register/(:num)', $controller_name . '::debugRegisterNow/$1');
    $routes->get('debug-register', $controller_name . '::debugRegisterNow');
});