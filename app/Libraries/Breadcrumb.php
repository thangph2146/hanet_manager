<?php

namespace App\Libraries;

/**
 * Lớp Breadcrumb - Quản lý đường dẫn breadcrumb
 * 
 * Cho phép thêm các mục breadcrumb và hiển thị chúng
 */
class Breadcrumb
{
    /**
     * Mảng chứa các mục breadcrumb
     *
     * @var array
     */
    protected $items = [];
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Khởi tạo mảng rỗng
        $this->items = [];
    }
    
    /**
     * Thêm một mục vào breadcrumb
     *
     * @param string $title Tiêu đề của mục
     * @param string|null $url URL của mục, nếu null thì sẽ là mục cuối cùng không có link
     * @return $this
     */
    public function add(string $title, ?string $url = null)
    {
        $this->items[] = [
            'title' => $title,
            'url' => $url
        ];
        
        return $this;
    }
    
    /**
     * Xóa tất cả các mục breadcrumb
     *
     * @return $this
     */
    public function clear()
    {
        $this->items = [];
        
        return $this;
    }
    
    /**
     * Lấy tất cả các mục breadcrumb
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * Hiển thị breadcrumb dưới dạng HTML
     *
     * @return string
     */
    public function render()
    {
        if (empty($this->items)) {
            return '';
        }
        
        $html = '<nav aria-label="breadcrumb">';
        $html .= '<ol class="breadcrumb">';
        
        $count = count($this->items);
        foreach ($this->items as $key => $item) {
            $isLast = ($key === $count - 1);
            
            if ($isLast) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">' . esc($item['title']) . '</li>';
            } else {
                $html .= '<li class="breadcrumb-item"><a href="' . $item['url'] . '">' . esc($item['title']) . '</a></li>';
            }
        }
        
        $html .= '</ol>';
        $html .= '</nav>';
        
        return $html;
    }
    
    /**
     * Hiển thị breadcrumb dưới dạng JSON
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->items);
    }
} 