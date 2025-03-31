<?php
/**
 * 01/04/2023
 * Thư viện để gọi và quản lý scripts và styles trong module thamgiasukien
 */

namespace App\Modules\thamgiasukien\Libraries;

class MasterScript {
    protected $route_url = 'admin/thamgiasukien';
    protected $module_name = 'thamgiasukien';
    
    /**
     * Constructor - thiết lập giá trị route_url và module_name
     * 
     * @param string $route_url Đường dẫn URL của route
     * @param string $module_name Tên module
     */
    public function __construct($route_url = null, $module_name = null)
    {
        if (!empty($route_url)) {
            $this->route_url = $route_url;
        }
        
        if (!empty($module_name)) {
            $this->module_name = $module_name;
        }
    }
    
    /**
     * Load CSS cho một kiểu cụ thể
     * 
     * @param string $type Kiểu CSS (all, table, form, view)
     * @return string HTML chứa CSS
     */
    public function pageCss($type = 'all')
    {
        include_once APPPATH . 'Modules/' . $this->module_name . '/Views/master_scripts.php';
        return page_css($type, $this->route_url);
    }
    
    /**
     * Load JavaScript cho một kiểu cụ thể
     * 
     * @param string $type Kiểu JS (all, table, form, view)
     * @return string HTML chứa JavaScript
     */
    public function pageJs($type = 'all')
    {
        include_once APPPATH . 'Modules/' . $this->module_name . '/Views/master_scripts.php';
        return page_js($type, $this->route_url);
    }
    
    /**
     * Load CSS cho một phần cụ thể
     * 
     * @param string $section Phần cần load CSS
     * @return string HTML chứa CSS
     */
    public function pageSectionCss($section)
    {
        include_once APPPATH . 'Modules/' . $this->module_name . '/Views/master_scripts.php';
        return page_section_css($section);
    }
    
    /**
     * Load JavaScript cho một phần cụ thể
     * 
     * @param string $section Phần cần load JavaScript
     * @return string HTML chứa JavaScript
     */
    public function pageSectionJs($section)
    {
        include_once APPPATH . 'Modules/' . $this->module_name . '/Views/master_scripts.php';
        return page_section_js($section, $this->route_url);
    }
    
    /**
     * Load JavaScript cho bảng dữ liệu
     * 
     * @return string HTML chứa JavaScript cho bảng
     */
    public function pageTableJs()
    {
        include_once APPPATH . 'Modules/' . $this->module_name . '/Views/master_scripts.php';
        return page_table_js($this->route_url);
    }
    
    /**
     * Thiết lập giá trị route_url mới
     * 
     * @param string $route_url Đường dẫn URL của route
     * @return $this
     */
    public function setRouteUrl($route_url)
    {
        $this->route_url = $route_url;
        return $this;
    }
    
    /**
     * Lấy giá trị route_url hiện tại
     * 
     * @return string
     */
    public function getRouteUrl()
    {
        return $this->route_url;
    }
    
    /**
     * Thiết lập giá trị module_name mới
     * 
     * @param string $module_name Tên module
     * @return $this
     */
    public function setModuleName($module_name)
    {
        $this->module_name = $module_name;
        return $this;
    }
    
    /**
     * Lấy giá trị module_name hiện tại
     * 
     * @return string
     */
    public function getModuleName()
    {
        return $this->module_name;
    }
} 