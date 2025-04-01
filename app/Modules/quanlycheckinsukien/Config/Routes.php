<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Cấu hình URL route cho module quanlycheckinsukien
$module_name = 'quanlycheckinsukien';
$controller_name = 'QuanLyCheckInSuKien';

// Định nghĩa routes cho module QuanLyCheckInSuKien
$routes->group($module_name, ['namespace' => 'App\Modules\\' . $module_name . '\Controllers'], 
function ($routes) use ($controller_name) {
    // Route hiển thị danh sách check-in sự kiện
    $routes->get('/', $controller_name . '::index');
    
    // Route cho dashboard và thống kê
    $routes->get('dashboard', $controller_name . '::dashboard');
    $routes->get('statistics', $controller_name . '::statistics');
    
    // Route quản lý danh sách đã xóa
    $routes->get('listdeleted', $controller_name . '::listdeleted');
    
    // Route thêm mới check-in sự kiện
    $routes->get('new', $controller_name . '::new');
    $routes->post('create', $controller_name . '::create');
    
    // Route chỉnh sửa check-in sự kiện
    $routes->get('edit/(:num)', $controller_name . '::edit/$1');
    $routes->post('update/(:num)', $controller_name . '::update/$1');
    
    // Route xóa (soft delete) một check-in sự kiện
    $routes->get('delete/(:num)', $controller_name . '::delete/$1');
    $routes->post('delete/(:num)', $controller_name . '::delete/$1');
    
    // Route khôi phục một check-in sự kiện đã xóa
    $routes->get('restore/(:num)', $controller_name . '::restore/$1');
    $routes->post('restore/(:num)', $controller_name . '::restore/$1');
    
    // Route xóa vĩnh viễn một check-in sự kiện
    $routes->post('purge/(:num)', $controller_name . '::purge/$1');
    $routes->get('permanentDelete/(:num)', $controller_name . '::permanentDelete/$1');
    $routes->post('permanentDelete/(:num)', $controller_name . '::permanentDelete/$1');
    
    // Route cập nhật trạng thái một check-in sự kiện
    $routes->post('status/(:num)', $controller_name . '::status/$1');
    
    // Route xử lý nhiều check-in sự kiện một lúc
    $routes->post('deleteMultiple', $controller_name . '::deleteMultiple');          // Xóa nhiều mục đã chọn (soft delete)
    $routes->post('restoreMultiple', $controller_name . '::restoreMultiple');        // Khôi phục nhiều mục đã chọn
    $routes->post('permanentDeleteMultiple', $controller_name . '::permanentDeleteMultiple');  // Xóa vĩnh viễn nhiều mục đã chọn
    $routes->post('deletePermanentMultiple', $controller_name . '::deletePermanentMultiple');  // Alias cho xóa vĩnh viễn nhiều mục
    $routes->post('statusMultiple', $controller_name . '::statusMultiple');          // Thay đổi trạng thái nhiều mục cùng lúc
    
    // Route chi tiết
    $routes->get('detail/(:num)', $controller_name . '::detail/$1');
    
    // Route xuất dữ liệu
    $routes->get('exportPdf', $controller_name . '::exportPdf');                    // Xuất danh sách camera ra PDF
    $routes->get('exportExcel', $controller_name . '::exportExcel');                // Xuất danh sách camera ra Excel
    $routes->get('exportDeletedPdf', $controller_name . '::exportDeletedPdf');      // Xuất danh sách camera đã xóa ra PDF
    $routes->get('exportDeletedExcel', $controller_name . '::exportDeletedExcel');  // Xuất danh sách camera đã xóa ra Excel
}); 