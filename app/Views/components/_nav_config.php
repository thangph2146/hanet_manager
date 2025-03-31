<?php
namespace App\Views\Components;

/**
 * Hàm trả về mảng cấu hình menu
 *
 * @return array Mảng cấu hình menu
 */
function nav_config() {
    $items = $GLOBALS['menuItems'] ?? []; 
    
    if (empty($items) || !is_array($items)) {
        return [
            [
                'title' => 'Dashboard',
                'url' => site_url('users/dashboard'),
                'icon' => 'bx bxs-dashboard'
            ],
            // BỘ QUẢN TRỊ HỆ THỐNG
            [
                'type' => 'label',
                'title' => 'Quản Trị Hệ Thống'
            ],
            [
                'title' => 'Quản lý Users',
                'icon' => 'bx bxs-user-account',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Users',
                        'url' => site_url('users')
                    ],
                    [
                        'title' => 'Danh sách Users bị xóa',
                        'url' => site_url('users/listdeleted')
                    ],
                    [
                        'title' => 'Thêm User mới',
                        'url' => site_url('users/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Roles',
                'icon' => 'bx bxs-user-badge',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Roles',
                        'url' => site_url('roles')
                    ],
                    [
                        'title' => 'Danh sách Roles bị xóa',
                        'url' => site_url('roles/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Role mới',
                        'url' => site_url('roles/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Permissions',
                'icon' => 'bx bxs-lock-alt',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Permissions',
                        'url' => site_url('permissions')
                    ],
                    [
                        'title' => 'Danh sách Permissions bị xóa',
                        'url' => site_url('permissions/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Permission mới',
                        'url' => site_url('permissions/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Settings',
                'icon' => 'bx bxs-cog',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Settings',
                        'url' => site_url('settings')
                    ],
                    [
                        'title' => 'Thêm Setting mới',
                        'url' => site_url('settings/new')
                    ]
                ]
            ],
            
            // BỘ QUẢN LÝ NGƯỜI DÙNG
            [
                'type' => 'label',
                'title' => 'Quản Lý Người Dùng'
            ],
            [
                'title' => 'Quản lý Người Dùng',
                'icon' => 'bx bxs-user',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Người Dùng',
                        'url' => site_url('admin/nguoidung')
                    ],
                    [
                        'title' => 'Danh sách Người Dùng bị xóa',
                        'url' => site_url('admin/nguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Người Dùng mới',
                        'url' => site_url('admin/nguoidung/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Loại Người Dùng',
                'icon' => 'bx bxs-user-detail',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Loại Người Dùng',
                        'url' => site_url('admin/loainguoidung')
                    ],
                    [
                        'title' => 'Danh sách Loại Người Dùng bị xóa',
                        'url' => site_url('admin/loainguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Loại Người Dùng mới',
                        'url' => site_url('admin/loainguoidung/new')
                    ]
                ]
            ],
            [
                'title' => 'Face người dùng',
                'icon' => 'bx bxs-face',
                'submenu' => [
                    [
                        'title' => 'Face người dùng',
                        'url' => site_url('admin/facenguoidung')
                    ],
                    [
                        'title' => 'Face người dùng bị xóa',
                        'url' => site_url('admin/facenguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Face người dùng mới',
                        'url' => site_url('admin/facenguoidung/new')
                    ]
                ]
            ],
            
            // BỘ QUẢN LÝ ĐÀO TẠO
            [
                'type' => 'label',
                'title' => 'Quản Lý Đào Tạo'
            ],
            [
                'title' => 'Quản lý Phòng Khoa',
                'icon' => 'bx bxs-building',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Phòng Khoa',
                        'url' => site_url('admin/phongkhoa')
                    ],
                    [
                        'title' => 'Danh sách Phòng Khoa bị xóa',
                        'url' => site_url('admin/phongkhoa/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Phòng Khoa mới',
                        'url' => site_url('admin/phongkhoa/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Năm Học',
                'icon' => 'bx bxs-calendar',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Năm học',
                        'url' => site_url('admin/namhoc')
                    ],
                    [
                        'title' => 'Danh sách Năm học bị xóa',
                        'url' => site_url('admin/namhoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Năm học mới',
                        'url' => site_url('admin/namhoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Khóa Học',
                'icon' => 'bx bxs-book-bookmark',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Khóa Học',
                        'url' => site_url('admin/khoahoc')
                    ],
                    [
                        'title' => 'Danh sách Khóa Học bị xóa',
                        'url' => site_url('admin/khoahoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Khóa Học mới',
                        'url' => site_url('admin/khoahoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Bậc Học',
                'icon' => 'bx bxs-graduation',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Bậc Học',
                        'url' => site_url('admin/bachoc')
                    ],
                    [
                        'title' => 'Danh sách Bậc Học bị xóa',
                        'url' => site_url('admin/bachoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Bậc Học mới',
                        'url' => site_url('admin/bachoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Hệ Đào Tạo',
                'icon' => 'bx bxs-school',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Hệ Đào Tạo',
                        'url' => site_url('admin/hedaotao')
                    ],
                    [
                        'title' => 'Danh sách Hệ Đào Tạo bị xóa',
                        'url' => site_url('admin/hedaotao/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Hệ Đào Tạo mới',
                        'url' => site_url('admin/hedaotao/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Ngành',
                'icon' => 'bx bxs-book-content',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Ngành',
                        'url' => site_url('admin/nganh')
                    ],
                    [
                        'title' => 'Danh sách Ngành bị xóa',
                        'url' => site_url('admin/nganh/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Ngành mới',
                        'url' => site_url('admin/nganh/new')
                    ]
                ]
            ],
            
            // BỘ QUẢN LÝ SỰ KIỆN
            [
                'type' => 'label',
                'title' => 'Quản Lý Sự Kiện'
            ],
            [
                'title' => 'Quản lý Sự Kiện',
                'icon' => 'bx bxs-calendar-event',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Sự Kiện',
                        'url' => site_url('admin/sukien')
                    ],
                    [
                        'title' => 'Danh sách Sự Kiện bị xóa',
                        'url' => site_url('admin/sukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Sự Kiện mới',
                        'url' => site_url('admin/sukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Loại Sự Kiện',
                'icon' => 'bx bxs-calendar-star',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Loại Sự Kiện',
                        'url' => site_url('admin/loaisukien')
                    ],
                    [
                        'title' => 'Danh sách Loại Sự Kiện bị xóa',
                        'url' => site_url('admin/loaisukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Loại Sự Kiện mới',
                        'url' => site_url('admin/loaisukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Diễn giả',
                'icon' => 'bx bxs-user-voice',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Diễn giả',
                        'url' => site_url('admin/diengia')
                    ],
                    [
                        'title' => 'Danh sách Diễn giả bị xóa',
                        'url' => site_url('admin/diengia/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Diễn giả mới',
                        'url' => site_url('admin/diengia/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Template',
                'icon' => 'bx bxs-file-doc',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Template',
                        'url' => site_url('admin/template')
                    ],
                    [
                        'title' => 'Danh sách Template bị xóa',
                        'url' => site_url('admin/template/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Template mới',
                        'url' => site_url('admin/template/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Sự Kiện Diễn Giả',
                'icon' => 'bx bxs-calendar-plus',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Sự Kiện Diễn Giả',
                        'url' => site_url('admin/sukiendiengia')
                    ],
                    [
                        'title' => 'Danh sách Sự Kiện Diễn Giả bị xóa',
                        'url' => site_url('admin/sukiendiengia/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Sự Kiện Diễn Giả mới',
                        'url' => site_url('admin/sukiendiengia/new')
                    ]
                ]
            ],
            
            // BỘ QUẢN LÝ ĐĂNG KÝ VÀ THAM GIA
            [
                'type' => 'label',
                'title' => 'Quản Lý Đăng Ký & Tham Gia'
            ],
            [
                'title' => 'Quản lý Đăng Ký Sự Kiện',
                'icon' => 'bx bxs-edit',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Đăng Ký Sự Kiện',
                        'url' => site_url('admin/dangkysukien')
                    ],
                    [
                        'title' => 'Danh sách Đăng Ký Sự Kiện bị xóa',
                        'url' => site_url('admin/dangkysukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Đăng Ký Sự Kiện mới',
                        'url' => site_url('admin/dangkysukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Form Đăng Ký',
                'icon' => 'bx bxs-file-plus',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Form Đăng Ký Sự Kiện',
                        'url' => site_url('admin/formdangkysukien')
                    ],
                    [
                        'title' => 'Danh sách Form Đăng Ký Sự Kiện bị xóa',
                        'url' => site_url('admin/formdangkysukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Form Đăng Ký Sự Kiện mới',
                        'url' => site_url('admin/formdangkysukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Tham Gia Sự Kiện',
                'icon' => 'bx bxs-user-check',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Tham Gia Sự Kiện',
                        'url' => site_url('admin/thamgiasukien')
                    ],
                    [
                        'title' => 'Danh sách Tham Gia Sự Kiện bị xóa',
                        'url' => site_url('admin/thamgiasukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Tham Gia Sự Kiện mới',
                        'url' => site_url('admin/thamgiasukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Check In/Out',
                'icon' => 'bx bxs-door-open',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Check In Sự Kiện',
                        'url' => site_url('admin/checkinsukien')
                    ],
                    [
                        'title' => 'Danh sách Check In Sự Kiện bị xóa',
                        'url' => site_url('admin/checkinsukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Check In Sự Kiện mới',
                        'url' => site_url('admin/checkinsukien/new')
                    ],
                    [
                        'title' => 'Danh Sách Check Out Sự Kiện',
                        'url' => site_url('admin/checkoutsukien')
                    ],
                    [
                        'title' => 'Danh sách Check Out Sự Kiện bị xóa',
                        'url' => site_url('admin/checkoutsukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Check Out Sự Kiện mới',
                        'url' => site_url('admin/checkoutsukien/new')
                    ]
                ]
            ],
            
            // BỘ QUẢN LÝ THIẾT BỊ
            [
                'type' => 'label',
                'title' => 'Quản Lý Thiết Bị'
            ],
            [
                'title' => 'Quản lý Màn Hình',
                'icon' => 'bx bxs-monitor',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Màn Hình',
                        'url' => site_url('admin/manhinh')
                    ],
                    [
                        'title' => 'Danh sách Màn Hình bị xóa',
                        'url' => site_url('admin/manhinh/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Màn Hình mới',
                        'url' => site_url('admin/manhinh/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Camera',
                'icon' => 'bx bxs-camera',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Camera',
                        'url' => site_url('admin/camera')
                    ],
                    [
                        'title' => 'Danh sách Camera bị xóa',
                        'url' => site_url('admin/camera/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Camera mới',
                        'url' => site_url('admin/camera/new')
                    ]
                ]
            ],
            
            // TRỢ GIÚP
            [
                'type' => 'label',
                'title' => 'Trợ Giúp'
            ],
            [
                'title' => 'Support',
                'url' => 'https://phongqlcntt.hub.edu.vn/',
                'icon' => 'bx bxs-help-circle',
                'target' => '_blank'
            ]
        ];
    }
    
    return $items;
} 