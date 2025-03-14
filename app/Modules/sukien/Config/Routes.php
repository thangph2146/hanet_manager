<?php

$routes->group('su-kien', ['namespace' => 'App\Modules\sukien\Controllers'], function ($routes) {
    $routes->get('/', 'Sukien::index');
    $routes->get('list', 'Sukien::list');
    $routes->get('detail/(:num)', 'Sukien::detail/$1');
    $routes->get('detail/(:any)', 'Sukien::detail/$1');
    $routes->get('category/(:any)', 'Sukien::category/$1');
    
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

    // // Trang chủ sự kiện
    // $routes->get('sukien', 'App\Modules\sukien\Controllers\Sukien::index');

    // // Danh sách sự kiện
    // $routes->get('su-kien/list', 'App\Modules\sukien\Controllers\Sukien::list');

    // // Danh mục sự kiện
    // $routes->get('su-kien/category/(:any)', 'App\Modules\sukien\Controllers\Sukien::category/$1');

    // // Chi tiết sự kiện theo ID (legacy)
    // $routes->get('sukien/view/(:num)', 'App\Modules\sukien\Controllers\Sukien::detail/$1');

    // // Chi tiết sự kiện theo slug (SEO friendly)
    // $routes->get('su-kien/detail/(:any)', 'App\Modules\sukien\Controllers\Sukien::detail/$1');

    // // Xử lý đăng ký sự kiện
    // $routes->post('sukien/register', 'App\Modules\sukien\Controllers\Sukien::register');

    // // Sitemap
    // $routes->get('sukien/sitemap.xml', 'App\Modules\sukien\Controllers\Sitemap::index');
});