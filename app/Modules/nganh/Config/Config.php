<?php

namespace App\Modules\nganh\Config;

use CodeIgniter\Config\BaseConfig;

class Config extends BaseConfig
{
    /**
     * Tên module
     *
     * @var string
     */
    public $moduleName = 'nganh';
    
    /**
     * Tiêu đề module
     *
     * @var string
     */
    public $moduleTitle = 'Quản lý Ngành';
    
    /**
     * Mô tả module
     *
     * @var string
     */
    public $moduleDescription = 'Module quản lý ngành đào tạo';
    
    /**
     * Phiên bản module
     *
     * @var string
     */
    public $moduleVersion = '1.0.0';
    
    /**
     * Menu items cho module
     *
     * @var array
     */
    public $moduleMenu = [
        'dashboard' => [
            'title' => 'Tổng quan',
            'url' => 'nganh/dashboard',
            'icon' => 'bx bx-home-circle',
        ],
        'list' => [
            'title' => 'Danh sách ngành',
            'url' => 'nganh',
            'icon' => 'bx bx-list-ul',
        ],
        'create' => [
            'title' => 'Thêm ngành mới',
            'url' => 'nganh/new',
            'icon' => 'bx bx-plus-circle',
        ],
        'trash' => [
            'title' => 'Thùng rác',
            'url' => 'nganh/listdeleted',
            'icon' => 'bx bx-trash',
        ],
    ];
} 