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
                'icon' => 'bx bx-home-circle'
            ],
            [
                'type' => 'label',
                'title' => 'Phần Quản trị'
            ],
            [
                'title' => 'Quản lý Users',
                'icon' => 'bx bx-user-pin',
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
                'icon' => 'bx bxs-group',
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
                'icon' => 'fadeIn animated bx bx-accessibility',
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
                'icon' => 'bx bx-cog',
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
            [
                'type' => 'label',
                'title' => 'Quản lý Người Dùng'
            ],
            [
                'title' => 'Quản lý Người Dùng',
                'icon' => 'bx bx-user',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Người Dùng',
                        'url' => site_url('nguoidung')
                    ],
                    [
                        'title' => 'Danh sách Người Dùng bị xóa',
                        'url' => site_url('nguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Người Dùng mới',
                        'url' => site_url('nguoidung/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Loại Người Dùng',
                'icon' => 'bx bx-category',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Loại Người Dùng',
                        'url' => site_url('loainguoidung')
                    ],
                    [
                        'title' => 'Danh sách Loại Người Dùng bị xóa',
                        'url' => site_url('loainguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Loại Người Dùng mới',
                        'url' => site_url('loainguoidung/new')
                    ]
                ]
            ],
            [
                'title' => 'Face người dùng',
                'icon' => 'bx bx-face',
                'submenu' => [
                    [
                        'title' => 'Face người dùng',
                        'url' => site_url('facenguoidung')
                    ],
                    [
                        'title' => 'Face người dùng bị xóa',
                        'url' => site_url('facenguoidung/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Face người dùng mới',
                        'url' => site_url('facenguoidung/new')
                    ]
                ]
            ],
            [
                'type' => 'label',
                'title' => 'Quản lý Đào Tạo'
            ],
            [
                'title' => 'Quản lý Phòng Khoa',
                'icon' => 'bx bx-building-house',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Phòng Khoa',
                        'url' => site_url('phongkhoa')
                    ],
                    [
                        'title' => 'Danh sách Phòng Khoa bị xóa',
                        'url' => site_url('phongkhoa/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Phòng Khoa mới',
                        'url' => site_url('phongkhoa/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Năm Học',
                'icon' => 'bx bx-calendar',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Năm học',
                        'url' => site_url('namhoc')
                    ],
                    [
                        'title' => 'Danh sách Năm học bị xóa',
                        'url' => site_url('namhoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Năm học mới',
                        'url' => site_url('namhoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Khóa Học',
                'icon' => 'bx bx-book-content',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Khóa Học',
                        'url' => site_url('khoahoc')
                    ],
                    [
                        'title' => 'Danh sách Khóa Học bị xóa',
                        'url' => site_url('khoahoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Khóa Học mới',
                        'url' => site_url('khoahoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Bậc Học',
                'icon' => 'bx bx-award',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Bậc Học',
                        'url' => site_url('bachoc')
                    ],
                    [
                        'title' => 'Danh sách Bậc Học bị xóa',
                        'url' => site_url('bachoc/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Bậc Học mới',
                        'url' => site_url('bachoc/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Hệ Đào Tạo',
                'icon' => 'bx bx-certification',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Hệ Đào Tạo',
                        'url' => site_url('hedaotao')
                    ],
                    [
                        'title' => 'Danh sách Hệ Đào Tạo bị xóa',
                        'url' => site_url('hedaotao/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Hệ Đào Tạo mới',
                        'url' => site_url('hedaotao/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Ngành',
                'icon' => 'bx bx-folder',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Ngành',
                        'url' => site_url('nganh')
                    ],
                    [
                        'title' => 'Danh sách Ngành bị xóa',
                        'url' => site_url('nganh/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Ngành mới',
                        'url' => site_url('nganh/new')
                    ]
                ]
            ],
            [
                'type' => 'label',
                'title' => 'Quản lý Sự Kiện'
            ],
            [
                'title' => 'Quản lý Loại Sự Kiện',
                'icon' => 'bx bx-calendar-event',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Loại Sự Kiện',
                        'url' => site_url('loaisukien')
                    ],
                    [
                        'title' => 'Danh sách Loại Sự Kiện bị xóa',
                        'url' => site_url('loaisukien/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Loại Sự Kiện mới',
                        'url' => site_url('loaisukien/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Template',
                'icon' => 'bx bx-calendar-event',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Template',
                        'url' => site_url('template')
                    ],
                    [
                        'title' => 'Danh sách Template bị xóa',
                        'url' => site_url('template/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Template mới',
                        'url' => site_url('template/new')
                    ]
                ]
            ],
            [
                'type' => 'label',
                'title' => 'Quản lý Thiết Bị'
            ],
            [
                'title' => 'Quản lý Màn Hình',
                'icon' => 'bx bx-desktop',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Màn Hình',
                        'url' => site_url('manhinh')
                    ],
                    [
                        'title' => 'Danh sách Màn Hình bị xóa',
                        'url' => site_url('manhinh/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Màn Hình mới',
                        'url' => site_url('manhinh/new')
                    ]
                ]
            ],
            [
                'title' => 'Quản lý Camera',
                'icon' => 'bx bx-desktop',
                'submenu' => [
                    [
                        'title' => 'Danh Sách Camera',
                        'url' => site_url('camera')
                    ],
                    [
                        'title' => 'Danh sách Camera bị xóa',
                        'url' => site_url('camera/listdeleted')
                    ],
                    [
                        'title' => 'Thêm Camera mới',
                        'url' => site_url('camera/new')
                    ]
                ]
            ],
            [
                'type' => 'label',
                'title' => 'Trợ giúp'
            ],
            [
                'title' => 'Support',
                'url' => 'https://phongqlcntt.hub.edu.vn/',
                'icon' => 'bx bx-support',
                'target' => '_blank'
            ]
        ];
    }
    
    return $items;
} 