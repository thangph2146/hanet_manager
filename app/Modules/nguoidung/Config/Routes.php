<?php

namespace Config;

// Tạo một instance của Routes
$routes = Services::routes();

// Định nghĩa các route cho module nguoidung
$routes->group('nguoidung', ['namespace' => 'App\Modules\nguoidung\Controllers'], function ($routes) {
    // Danh sách người dùng
    $routes->get('/', 'NguoiDungController::index');
    
    // Thêm người dùng mới
    $routes->get('create', 'NguoiDungController::create');
    $routes->post('store', 'NguoiDungController::store');
    
    // Xem chi tiết người dùng
    $routes->get('show/(:num)', 'NguoiDungController::show/$1');
    
    // Chỉnh sửa người dùng
    $routes->get('edit/(:num)', 'NguoiDungController::edit/$1');
    $routes->post('update/(:num)', 'NguoiDungController::update/$1');
    
    // Xóa người dùng (soft delete)
    $routes->get('delete/(:num)', 'NguoiDungController::delete/$1');
    
    // Thùng rác
    $routes->get('trash', 'NguoiDungController::trash');
    
    // Khôi phục người dùng đã xóa
    $routes->get('restore/(:num)', 'NguoiDungController::restore/$1');
    
    // Xóa vĩnh viễn người dùng
    $routes->get('purge/(:num)', 'NguoiDungController::purge/$1');
    
    // Tìm kiếm người dùng
    $routes->get('search', 'NguoiDungController::search');
});

