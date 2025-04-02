<?php
namespace Config;
// Create a new instance of our RouteCollection class.
$routes = Services::routes();
$module_url = 'su-kien';
$module_name = 'sukien';
$controller_name = 'Sukien';
$sitemap_controller_name = 'Sitemap';

$routes->group($module_url, ['namespace' => 'App\Modules\sukien\Controllers'], function ($routes) use ($controller_name, $sitemap_controller_name) {
    $routes->get('/', $controller_name . '::index');
    $routes->get('list', $controller_name . '::list');
    
    // Sử dụng slug thay vì ID cho trang chi tiết
    $routes->get('detail/(:num)', $controller_name . '::redirectToSlug/$1'); // Redirect từ ID sang slug
    $routes->get('detail/(:segment)', $controller_name . '::detail/$1'); // Sử dụng segment thay vì any để hỗ trợ slug
    
    // Sử dụng slug cho danh mục
    $routes->get('loai/(:segment)', $controller_name . '::category/$1');
    
    // Các route xử lý đăng ký và check-in/check-out
    $routes->post('register', $controller_name . '::register');
    $routes->post('checkin', $controller_name . '::checkin');
    $routes->post('checkout', $controller_name . '::checkout');
    
    // Route cho AJAX lấy chế độ xem sự kiện
    $routes->post('su-kien/get-events-view', $controller_name . '::getEventsView');
    
    // Sitemap cho SEO
    $routes->get('sitemap.xml', $sitemap_controller_name . '::index');
    
    // Route cho màn hình check-in
    $routes->get('checkin-display/(:any)', $controller_name . '::displayCheckin/$1');
    $routes->get('checkin-display', $controller_name . '::displayCheckin');
    $routes->get('api/checkin-display', $controller_name . '::getCheckinDisplay');
    
    // Thêm vào app/Modules/sukien/Config/Routes.php
    $routes->post('webhook-hanet', $controller_name . '::processHanetWebhook');
    $routes->post('webhook-proxy', $controller_name . '::webhookProxy');
});