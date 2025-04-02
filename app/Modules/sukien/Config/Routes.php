<?php

$routes->group('su-kien', ['namespace' => 'App\Modules\sukien\Controllers'], function ($routes) {
    $routes->get('/', 'Sukien::index');
    $routes->get('list', 'Sukien::list');
    
    // Sử dụng slug thay vì ID cho trang chi tiết
    $routes->get('detail/(:num)', 'Sukien::redirectToSlug/$1'); // Redirect từ ID sang slug
    $routes->get('detail/(:segment)', 'Sukien::detail/$1'); // Sử dụng segment thay vì any để hỗ trợ slug
    
    // Sử dụng slug cho danh mục
    $routes->get('loai/(:segment)', 'Sukien::category/$1');
    
    // Các route xử lý đăng ký và check-in/check-out
    $routes->post('register', 'Sukien::register');
    $routes->post('checkin', 'Sukien::checkin');
    $routes->post('checkout', 'Sukien::checkout');
    
    // Các route cho admin (sẽ triển khai sau)
    $routes->group('admin', function ($routes) {
        $routes->get('events', 'Admin\Events::index');
        $routes->get('events/new', 'Admin\Events::new');
        $routes->post('events', 'Admin\Events::create');
        $routes->get('events/edit/(:num)', 'Admin\Events::edit/$1');
        $routes->post('events/update/(:num)', 'Admin\Events::update/$1');
        $routes->get('events/delete/(:num)', 'Admin\Events::delete/$1');
        
        $routes->get('registrations', 'Admin\Registrations::index');
        $routes->get('registrations/event/(:num)', 'Admin\Registrations::byEvent/$1');
    });

    // Route cho AJAX lấy chế độ xem sự kiện
    $routes->post('su-kien/get-events-view', 'SukienController::getEventsView');
    
    // Sitemap cho SEO
    $routes->get('sitemap.xml', 'Sitemap::index');
});