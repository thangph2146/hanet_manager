<?php
namespace Config;
// Create a new instance of our RouteCollection class.
$routes = Services::routes();
$module_url = 'su-kien';
$module_name = 'sukien';
$controller_name = 'SuKien';
$sitemap_controller_name = 'Sitemap';

$routes->group($module_url, ['namespace' => 'App\Modules\sukien\Controllers'], function ($routes) use ($controller_name, $sitemap_controller_name) {
    $routes->get('/', $controller_name . '::index');
    $routes->get('list', $controller_name . '::list');
    
    // Sử dụng slug thay vì ID cho trang chi tiết
    $routes->get('detail/(:num)', $controller_name . '::redirectToSlug/$1'); // Redirect từ ID sang slug
    $routes->get('detail/(:segment)', $controller_name . '::detail/$1', ['as' => 'sukien_detail']); // Thêm alias cho route
    
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
    
    // Webhook cho Hanet Camera
    $routes->post('hanet-webhook', $controller_name . '::hanetWebhook');
    $routes->get('hanet-webhook', $controller_name . '::hanetWebhook'); // For testing
    
    // Các webhook khác
    $routes->post('webhook-hanet', $controller_name . '::processHanetWebhook');
    $routes->post('webhook-proxy', $controller_name . '::webhookProxy');
    
    // Routes for HUB webhook processing
    $routes->post('hub-webhook', $controller_name . '::processHubWebhook');
    $routes->get('hub-webhook', $controller_name . '::processHubWebhook'); // For testing
    
    // Routes for viewing webhook logs
    $routes->get('webhook-logs/(:alpha)', $controller_name . '::viewWebhookLogs/$1');
    $routes->get('webhook-logs', $controller_name . '::viewWebhookLogs');
    $routes->get('webhook-log-content/(:alpha)/(:any)', $controller_name . '::getWebhookLogContent/$1/$2');
    
    // Route for WebSocket check-in data (optional)
    $routes->get('checkin-data/(:any)', $controller_name . '::getCheckinData/$1');
});