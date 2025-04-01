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
                        'url' => site_url('quanlynguoidung')
                    ],
                    [
                        'title' => 'Danh sách Người Dùng bị xóa',
                        'url' => site_url('quanlynguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Người Dùng mới',
                        'url' => site_url('quanlynguoidung/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Loại Người Dùng',
                'icon' => 'bx bxs-user-detail',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Loại Người Dùng',
                        'url' => site_url('quanlyloainguoidung')
                    ],
                    [
                        'title' => 'Danh sách Loại Người Dùng bị xóa',
                        'url' => site_url('quanlyloainguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Loại Người Dùng mới',
                        'url' => site_url('quanlyloainguoidung/new')
                    ]
                ]
            ],
            [
                'title' => 'Face người dùng',
                'icon' => 'bx bxs-face',
                'submenu' => [
                    [
                        'title' => 'Face người dùng',
                        'url' => site_url('quanlyfacenguoidung')
                    ],
                    [
                        'title' => 'Face người dùng bị xóa',
                        'url' => site_url('quanlyfacenguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Face người dùng mới',
                        'url' => site_url('quanlyfacenguoidung/new')
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
                        'url' => site_url('quanlyphongkhoa')
                    ],
                    [
                        'title' => 'Danh sách Phòng Khoa bị xóa',
                        'url' => site_url('quanlyphongkhoa/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Phòng Khoa mới',
                        'url' => site_url('quanlyphongkhoa/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Năm Học',
                'icon' => 'bx bxs-calendar',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Năm học',
                        'url' => site_url('quanlynamhoc')
                    ],
                    [
                        'title' => 'Danh sách Năm học bị xóa',
                        'url' => site_url('quanlynamhoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Năm học mới',
                        'url' => site_url('quanlynamhoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Khóa Học',
                'icon' => 'bx bxs-book-bookmark',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Khóa Học',
                        'url' => site_url('quanlykhoahoc')
                    ],
                    [
                        'title' => 'Danh sách Khóa Học bị xóa',
                        'url' => site_url('quanlykhoahoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Khóa Học mới',
                        'url' => site_url('quanlykhoahoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Bậc Học',
                'icon' => 'bx bxs-graduation',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Bậc Học',
                        'url' => site_url('quanlybachoc')
                    ],
                    [
                        'title' => 'Danh sách Bậc Học bị xóa',
                        'url' => site_url('quanlybachoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Bậc Học mới',
                        'url' => site_url('quanlybachoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Hệ Đào Tạo',
                'icon' => 'bx bxs-school',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Hệ Đào Tạo',
                        'url' => site_url('quanlyhedaotao')
                    ],
                    [
                        'title' => 'Danh sách Hệ Đào Tạo bị xóa',
                        'url' => site_url('quanlyhedaotao/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Hệ Đào Tạo mới',
                        'url' => site_url('quanlyhedaotao/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Ngành',
                'icon' => 'bx bxs-book-content',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Ngành',
                        'url' => site_url('quanlynganh')
                    ],
                    [
                        'title' => 'Danh sách Ngành bị xóa',
                        'url' => site_url('quanlynganh/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Ngành mới',
                        'url' => site_url('quanlynganh/new')
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
                        'url' => site_url('quanlysukien')
                    ],
                    [
                        'title' => 'Danh sách Sự Kiện bị xóa',
                        'url' => site_url('quanlysukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Sự Kiện mới',
                        'url' => site_url('quanlysukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Loại Sự Kiện',
                'icon' => 'bx bxs-calendar-star',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Loại Sự Kiện',
                        'url' => site_url('quanlyloaisukien')
                    ],
                    [
                        'title' => 'Danh sách Loại Sự Kiện bị xóa',
                        'url' => site_url('quanlyloaisukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Loại Sự Kiện mới',
                        'url' => site_url('quanlyloaisukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Diễn giả',
                'icon' => 'bx bxs-user-voice',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Diễn giả',
                        'url' => site_url('quanlydiengia')
                    ],
                    [
                        'title' => 'Danh sách Diễn giả bị xóa',
                        'url' => site_url('quanlydiengia/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Diễn giả mới',
                        'url' => site_url('quanlydiengia/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Template',
                'icon' => 'bx bxs-file-doc',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Template',
                        'url' => site_url('quanlytemplate')
                    ],
                    [
                        'title' => 'Danh sách Template bị xóa',
                        'url' => site_url('quanlytemplate/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Template mới',
                        'url' => site_url('quanlytemplate/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Sự Kiện Diễn Giả',
                'icon' => 'bx bxs-calendar-plus',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Sự Kiện Diễn Giả',
                        'url' => site_url('quanlysukien/sukiendiengia')
                    ],
                    [
                        'title' => 'Danh sách Sự Kiện Diễn Giả bị xóa',
                        'url' => site_url('quanlysukien/sukiendiengia/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Sự Kiện Diễn Giả mới',
                        'url' => site_url('quanlysukien/sukiendiengia/new')
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
                        'url' => site_url('quanlydangkysukien')
                    ],
                    [
                        'title' => 'Danh sách Đăng Ký Sự Kiện bị xóa',
                        'url' => site_url('quanlydangkysukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Đăng Ký Sự Kiện mới',
                        'url' => site_url('quanlydangkysukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Form Đăng Ký',
                'icon' => 'bx bxs-file-plus',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Form Đăng Ký Sự Kiện',
                        'url' => site_url('quanlyformdangkysukien')
                    ],
                    [
                        'title' => 'Danh sách Form Đăng Ký Sự Kiện bị xóa',
                        'url' => site_url('quanlyformdangkysukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Form Đăng Ký Sự Kiện mới',
                        'url' => site_url('quanlyformdangkysukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Tham Gia Sự Kiện',
                'icon' => 'bx bxs-user-check',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Tham Gia Sự Kiện',
                        'url' => site_url('quanlythamgiasukien')
                    ],
                    [
                        'title' => 'Danh sách Tham Gia Sự Kiện bị xóa',
                        'url' => site_url('quanlythamgiasukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Tham Gia Sự Kiện mới',
                        'url' => site_url('quanlythamgiasukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Check In/Out',
                'icon' => 'bx bxs-door-open',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Check In Sự Kiện',
                        'url' => site_url('quanlycheckinsukien')
                    ],
                    [
                        'title' => 'Danh sách Check In Sự Kiện bị xóa',
                        'url' => site_url('quanlycheckinsukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Check In Sự Kiện mới',
                        'url' => site_url('quanlycheckinsukien/new')
                    ],
                    [
                        'title' => 'Danh Sách Check Out Sự Kiện',
                        'url' => site_url('quanlycheckoutsukien')
                    ],
                    [
                        'title' => 'Danh sách Check Out Sự Kiện bị xóa',
                        'url' => site_url('quanlycheckoutsukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Check Out Sự Kiện mới',
                        'url' => site_url('quanlycheckoutsukien/new')
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
                        'url' => site_url('quanlymanhinh')
                    ],
                    [
                        'title' => 'Danh sách Màn Hình bị xóa',
                        'url' => site_url('quanlymanhinh/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Màn Hình mới',
                        'url' => site_url('quanlymanhinh/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Camera',
                'icon' => 'bx bxs-camera',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Camera',
                        'url' => site_url('quanlycamera')
                    ],
                    [
                        'title' => 'Danh sách Camera bị xóa',
                        'url' => site_url('quanlycamera/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Camera mới',
                        'url' => site_url('quanlycamera/new')
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