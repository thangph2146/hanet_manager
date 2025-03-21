<?php

/**
 * TableRender Helper
 *
 * Bộ trợ giúp tạo bảng dữ liệu với các chức năng mở rộng từ Table Class của CodeIgniter 4
 */

if (!function_exists('init_table')) {
    /**
     * Khởi tạo đối tượng Table
     *
     * @param array $attributes Các thuộc tính của bảng
     * @return \CodeIgniter\View\Table
     */
    function init_table($attributes = [])
    {
        $table = new \CodeIgniter\View\Table();
        
        // Thiết lập template cho bảng
        $template = [
            'table_open' => '<table class="table table-striped table-hover table-bordered">',
        ];
        
        // Thêm các thuộc tính tùy chỉnh
        if (!empty($attributes)) {
            $attrs = '';
            foreach ($attributes as $key => $val) {
                if ($key === 'class') {
                    $template['table_open'] = '<table class="' . $val . '">';
                } else {
                    $attrs .= ' ' . $key . '="' . $val . '"';
                }
            }
            
            if (!empty($attrs)) {
                $template['table_open'] = str_replace('>', $attrs . '>', $template['table_open']);
            }
        }
        
        $table->setTemplate($template);
        
        return $table;
    }
}

if (!function_exists('render_table')) {
    /**
     * Tạo và hiển thị bảng từ dữ liệu với checkbox và nút hành động
     *
     * @param array         $data           Dữ liệu bảng
     * @param array         $options        Tùy chọn cấu hình bảng
     *                                      - headings: Tiêu đề các cột
     *                                      - checkbox: Thêm checkbox (true/false)
     *                                      - checkbox_name: Tên của checkbox
     *                                      - actions: Mảng các hành động ['Tên' => 'url/{id}']
     *                                      - id_field: Tên trường ID sử dụng cho checkbox/actions
     *                                      - table_id: ID của bảng HTML
     *                                      - class: CSS class của bảng
     *                                      - caption: Tiêu đề bảng
     * @param object|null   $pager          Đối tượng Pager (nếu cần phân trang)
     * @return string                       HTML bảng
     */
    function render_table($data, array $options = [], $pager = null)
    {
        // Khởi tạo đối tượng Table
        $table = new \CodeIgniter\View\Table();
        
        // Xử lý tùy chọn
        $headings = $options['headings'] ?? [];
        $checkbox = $options['checkbox'] ?? false;
        $checkbox_name = $options['checkbox_name'] ?? 'item_id[]';
        $actions = $options['actions'] ?? [];
        $id_field = $options['id_field'] ?? 'id';
        $table_id = $options['table_id'] ?? 'dataTable';
        $table_class = $options['class'] ?? 'table table-striped table-bordered';
        $caption = $options['caption'] ?? '';
        
        // Thiết lập template
        $template = [
            'table_open' => '<table id="' . $table_id . '" class="' . $table_class . '">',
        ];
        $table->setTemplate($template);
        
        // Thiết lập caption nếu có
        if (!empty($caption)) {
            $table->setCaption($caption);
        }
        
        // Chuẩn bị dữ liệu cho bảng
        $table_data = [];
        
        // Kiểm tra xem dữ liệu có tồn tại không
        if (empty($data)) {
            return '<div class="alert alert-info">Không có dữ liệu để hiển thị.</div>';
        }
        
        // Thêm các tiêu đề nếu có
        $final_headings = [];
        
        // Thêm cột checkbox nếu được yêu cầu
        if ($checkbox) {
            $final_headings[] = '<input type="checkbox" id="select-all" class="form-check-input">';
        }
        
        // Thêm các tiêu đề khác
        foreach ($headings as $heading) {
            $final_headings[] = $heading;
        }
        
        // Thêm cột hành động nếu có
        if (!empty($actions)) {
            $final_headings[] = 'Thao tác';
        }
        
        // Thiết lập tiêu đề cho bảng
        if (!empty($final_headings)) {
            $table->setHeading($final_headings);
        }
        
        // Xử lý dữ liệu cho từng hàng
        foreach ($data as $row) {
            $row_data = [];
            
            // Thêm checkbox nếu được yêu cầu
            if ($checkbox) {
                $id_val = isset($row[$id_field]) ? $row[$id_field] : '';
                $row_data[] = '<input type="checkbox" name="' . $checkbox_name . '" value="' . $id_val . '" class="form-check-input">';
            }
            
            // Thêm dữ liệu cho từng cột
            foreach ($headings as $key => $heading) {
                // Nếu heading là mảng kết hợp (key => value), sử dụng key để lấy dữ liệu
                $field = is_string($key) ? $key : $heading;
                $row_data[] = isset($row[$field]) ? $row[$field] : '';
            }
            
            // Thêm cột hành động nếu có
            if (!empty($actions)) {
                $action_buttons = '';
                $id_val = isset($row[$id_field]) ? $row[$id_field] : '';
                
                foreach ($actions as $label => $url_pattern) {
                    $action_url = str_replace('{id}', $id_val, $url_pattern);
                    
                    // Xác định class và icon dựa vào tên hành động
                    $btn_class = 'btn-primary';
                    $icon_class = 'bx-edit';
                    $action_class = '';
                    
                    if (stripos($label, 'xóa') !== false || stripos($label, 'delete') !== false) {
                        $btn_class = 'btn-danger';
                        $icon_class = 'bx-trash';
                        $action_class = 'delete-item';
                    } elseif (stripos($label, 'khôi phục') !== false || stripos($label, 'restore') !== false) {
                        $btn_class = 'btn-success';
                        $icon_class = 'bx-refresh';
                        $action_class = 'restore-item';
                    } elseif (stripos($label, 'xem') !== false || stripos($label, 'view') !== false) {
                        $btn_class = 'btn-info';
                        $icon_class = 'bx-show';
                    }
                    
                    $action_buttons .= '<a href="' . $action_url . '" class="btn btn-sm ' . $btn_class . ' ' . $action_class . '" title="' . $label . '"><i class="bx ' . $icon_class . '"></i></a> ';
                }
                
                $row_data[] = $action_buttons;
            }
            
            // Thêm hàng vào bảng
            $table_data[] = $row_data;
        }
        
        // Tạo HTML cho bảng
        $html = $table->generate($table_data);
        
        // Thêm phân trang nếu có
        if ($pager !== null) {
            $html .= '<div class="pagination-container mt-4">' . $pager->links() . '</div>';
        }
        
        return $html;
    }
}

if (!function_exists('render_table_with_checkbox')) {
    /**
     * Tạo bảng với cột checkbox đầu tiên
     *
     * @param array $data           Dữ liệu bảng
     * @param array $heading        Tiêu đề cột (không bao gồm cột checkbox)
     * @param string $checkbox_name Tên của checkbox
     * @param string $id_field      Tên trường ID
     * @param array $attributes     Thuộc tính của bảng
     * @param string $caption       Tiêu đề bảng
     * @return string
     */
    function render_table_with_checkbox($data, $heading = [], $checkbox_name = 'item_id[]', $id_field = 'id', $attributes = [], $caption = '')
    {
        // Tạo bảng
        $table = init_table($attributes);
        
        // Thêm cột checkbox vào đầu heading
        array_unshift($heading, '<input type="checkbox" id="select-all" class="form-check-input">');
        $table->setHeading($heading);
        
        // Thiết lập caption nếu có
        if (!empty($caption)) {
            $table->setCaption($caption);
        }
        
        // Xử lý dữ liệu để thêm checkbox vào mỗi hàng
        $processed_data = [];
        
        foreach ($data as $row) {
            $row_data = [];
            
            // Checkbox đầu tiên
            $id_val = isset($row[$id_field]) ? $row[$id_field] : '';
            $row_data[] = '<input type="checkbox" name="' . $checkbox_name . '" value="' . $id_val . '" class="form-check-input">';
            
            // Các cột dữ liệu khác
            foreach ($row as $value) {
                $row_data[] = $value;
            }
            
            $processed_data[] = $row_data;
        }
        
        return $table->generate($processed_data);
    }
}

if (!function_exists('render_table_with_actions')) {
    /**
     * Tạo bảng với cột hành động ở cuối
     *
     * @param array $data       Dữ liệu bảng
     * @param array $heading    Tiêu đề cột (không bao gồm cột hành động)
     * @param array $actions    Mảng các hành động với key là tên hành động, value là URL pattern
     * @param string $id_field  Tên trường ID để tạo URL
     * @param array $attributes Thuộc tính của bảng
     * @param string $caption   Tiêu đề bảng
     * @return string
     */
    function render_table_with_actions($data, $heading = [], $actions = [], $id_field = 'id', $attributes = [], $caption = '')
    {
        // Tạo bảng
        $table = init_table($attributes);
        
        // Thêm cột hành động vào cuối heading
        $heading[] = lang('App.actions');
        $table->setHeading($heading);
        
        // Thiết lập caption nếu có
        if (!empty($caption)) {
            $table->setCaption($caption);
        }
        
        // Xử lý dữ liệu để thêm các nút hành động vào mỗi hàng
        $processed_data = [];
        
        foreach ($data as $row) {
            $row_data = [];
            
            // Các cột dữ liệu
            foreach ($row as $value) {
                $row_data[] = $value;
            }
            
            // Thêm cột hành động
            $action_buttons = '';
            foreach ($actions as $label => $url_pattern) {
                $id_val = isset($row[$id_field]) ? $row[$id_field] : '';
                $action_url = str_replace('{id}', $id_val, $url_pattern);
                
                // Xác định class và icon dựa vào tên hành động
                $btn_class = 'btn-primary'; // Mặc định
                $icon_class = 'bx-edit';
                
                if (stripos($label, 'xóa') !== false || stripos($label, 'delete') !== false) {
                    $btn_class = 'btn-danger';
                    $icon_class = 'bx-trash';
                } elseif (stripos($label, 'khôi phục') !== false || stripos($label, 'restore') !== false) {
                    $btn_class = 'btn-success';
                    $icon_class = 'bx-refresh';
                } elseif (stripos($label, 'xem') !== false || stripos($label, 'view') !== false) {
                    $btn_class = 'btn-info';
                    $icon_class = 'bx-show';
                }
                
                $action_buttons .= '<a href="' . $action_url . '" class="btn btn-sm ' . $btn_class . '" title="' . $label . '"><i class="bx ' . $icon_class . '"></i></a> ';
            }
            
            $row_data[] = $action_buttons;
            $processed_data[] = $row_data;
        }
        
        return $table->generate($processed_data);
    }
}

if (!function_exists('render_table_with_checkbox_and_actions')) {
    /**
     * Tạo bảng với cột checkbox đầu tiên và cột hành động ở cuối
     *
     * @param array $data           Dữ liệu bảng
     * @param array $heading        Tiêu đề cột (không bao gồm cột checkbox và hành động)
     * @param string $checkbox_name Tên của checkbox
     * @param array $actions        Mảng các hành động với key là tên hành động, value là URL pattern
     * @param string $id_field      Tên trường ID
     * @param array $attributes     Thuộc tính của bảng
     * @param string $caption       Tiêu đề bảng
     * @return string
     */
    function render_table_with_checkbox_and_actions($data, $heading = [], $checkbox_name = 'item_id[]', $actions = [], $id_field = 'id', $attributes = [], $caption = '')
    {
        // Tạo bảng
        $table = init_table($attributes);
        
        // Thêm cột checkbox vào đầu heading và cột hành động vào cuối
        array_unshift($heading, '<input type="checkbox" id="select-all" class="form-check-input">');
        $heading[] = lang('App.actions');
        $table->setHeading($heading);
        
        // Thiết lập caption nếu có
        if (!empty($caption)) {
            $table->setCaption($caption);
        }
        
        // Xử lý dữ liệu để thêm checkbox và các nút hành động vào mỗi hàng
        $processed_data = [];
        
        foreach ($data as $row) {
            $row_data = [];
            
            // Checkbox đầu tiên
            $id_val = isset($row[$id_field]) ? $row[$id_field] : '';
            $row_data[] = '<input type="checkbox" name="' . $checkbox_name . '" value="' . $id_val . '" class="form-check-input">';
            
            // Các cột dữ liệu
            foreach ($row as $value) {
                $row_data[] = $value;
            }
            
            // Thêm cột hành động
            $action_buttons = '';
            foreach ($actions as $label => $url_pattern) {
                $action_url = str_replace('{id}', $id_val, $url_pattern);
                
                // Xác định class và icon dựa vào tên hành động
                $btn_class = 'btn-primary'; // Mặc định
                $icon_class = 'bx-edit';
                
                if (stripos($label, 'xóa') !== false || stripos($label, 'delete') !== false) {
                    $btn_class = 'btn-danger';
                    $icon_class = 'bx-trash';
                    $action_class = 'delete-item';
                } elseif (stripos($label, 'khôi phục') !== false || stripos($label, 'restore') !== false) {
                    $btn_class = 'btn-success';
                    $icon_class = 'bx-refresh';
                    $action_class = 'restore-item';
                } elseif (stripos($label, 'xem') !== false || stripos($label, 'view') !== false) {
                    $btn_class = 'btn-info';
                    $icon_class = 'bx-show';
                    $action_class = '';
                } else {
                    $action_class = '';
                }
                
                $action_buttons .= '<a href="' . $action_url . '" class="btn btn-sm ' . $btn_class . ' ' . $action_class . '" title="' . $label . '"><i class="bx ' . $icon_class . '"></i></a> ';
            }
            
            $row_data[] = $action_buttons;
            $processed_data[] = $row_data;
        }
        
        return $table->generate($processed_data);
    }
}

if (!function_exists('render_pagination_links')) {
    /**
     * Tạo các liên kết phân trang
     *
     * @param \CodeIgniter\Pager\Pager $pager Đối tượng Pager
     * @param string $group Nhóm phân trang
     * @return string
     */
    function render_pagination_links($pager, $group = 'default')
    {
        if ($pager->getPageCount($group) <= 1) {
            return '';
        }
        
        return '<div class="pagination-container mt-4">' . $pager->links($group) . '</div>';
    }
}

if (!function_exists('render_table_with_pagination')) {
    /**
     * Tạo bảng với phân trang
     *
     * @param array $data       Dữ liệu bảng
     * @param array $heading    Tiêu đề cột
     * @param object $pager     Đối tượng Pager
     * @param array $attributes Thuộc tính của bảng
     * @param string $caption   Tiêu đề bảng
     * @param string $group     Nhóm phân trang
     * @return string
     */
    function render_table_with_pagination($data, $heading = [], $pager = null, $attributes = [], $caption = '', $group = 'default')
    {
        $html = render_table($data, $heading, $attributes, $caption);
        
        if ($pager !== null) {
            $html .= render_pagination_links($pager, $group);
        }
        
        return $html;
    }
}
