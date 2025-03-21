<?php

/**
 * FormRender Helper
 *
 * Bộ trợ giúp tạo form với các chức năng mở rộng từ Form Helper của CodeIgniter 4
 */

// Đảm bảo form_helper của CodeIgniter đã được tải
if (!function_exists('form_open')) {
    helper('form');
}

if (!function_exists('render_form_open')) {
    /**
     * Mở form với các tùy chọn mở rộng
     *
     * @param string $action          URL cho action của form
     * @param array  $attributes      Thuộc tính của form
     * @param array  $hidden          Các trường ẩn
     * @param bool   $multipart       Có sử dụng multipart/form-data không
     * @param bool   $addCsrfToken    Tự động thêm CSRF token
     * @return string
     */
    function render_form_open($action = '', $attributes = [], $hidden = [], $multipart = false, $addCsrfToken = true)
    {
        // Thêm CSRF token nếu cần
        if ($addCsrfToken) {
            $hidden[csrf_token()] = csrf_hash();
        }
        
        // Dùng multipart nếu cần
        if ($multipart) {
            return form_open_multipart($action, $attributes, $hidden);
        } else {
            return form_open($action, $attributes, $hidden);
        }
    }
}

if (!function_exists('render_form_input')) {
    /**
     * Tạo input field với các tùy chọn mở rộng
     *
     * @param string $name            Tên trường input
     * @param string $value           Giá trị
     * @param array  $attributes      Thuộc tính bổ sung
     * @param string $type            Loại input (text, email, number, etc.)
     * @param string $label           Nhãn cho trường input
     * @param bool   $required        Trường bắt buộc hay không
     * @param string $errorMessage    Thông báo lỗi (nếu có)
     * @return string
     */
    function render_form_input($name, $value = '', $attributes = [], $type = 'text', $label = '', $required = false, $errorMessage = '')
    {
        $html = '';
        
        // Thêm ID nếu không có
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Thêm class form-control
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-control';
        } else {
            $attributes['class'] .= ' form-control';
        }
        
        // Thêm thuộc tính required nếu cần
        if ($required) {
            $attributes['required'] = 'required';
        }
        
        // Thêm label nếu có
        if (!empty($label)) {
            $labelAttributes = [];
            if ($required) {
                $labelAttributes['class'] = 'required';
            }
            $html .= form_label($label, $attributes['id'], $labelAttributes);
        }
        
        // Tạo input field
        $html .= form_input($name, $value, $attributes, $type);
        
        // Thêm thông báo lỗi nếu có
        if (!empty($errorMessage)) {
            $html .= '<div class="error-message">' . $errorMessage . '</div>';
        }
        
        return $html;
    }
}

if (!function_exists('render_form_textarea')) {
    /**
     * Tạo textarea với các tùy chọn mở rộng
     *
     * @param string $name            Tên trường
     * @param string $value           Giá trị
     * @param array  $attributes      Thuộc tính bổ sung
     * @param string $label           Nhãn cho trường
     * @param bool   $required        Trường bắt buộc hay không
     * @param string $errorMessage    Thông báo lỗi (nếu có)
     * @return string
     */
    function render_form_textarea($name, $value = '', $attributes = [], $label = '', $required = false, $errorMessage = '')
    {
        $html = '';
        
        // Thêm ID nếu không có
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Thêm class form-control
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-control';
        } else {
            $attributes['class'] .= ' form-control';
        }
        
        // Thêm thuộc tính required nếu cần
        if ($required) {
            $attributes['required'] = 'required';
        }
        
        // Thêm rows và cols mặc định nếu không có
        if (!isset($attributes['rows'])) {
            $attributes['rows'] = 5;
        }
        
        // Thêm label nếu có
        if (!empty($label)) {
            $labelAttributes = [];
            if ($required) {
                $labelAttributes['class'] = 'required';
            }
            $html .= form_label($label, $attributes['id'], $labelAttributes);
        }
        
        // Tạo textarea
        $html .= form_textarea($name, $value, $attributes);
        
        // Thêm thông báo lỗi nếu có
        if (!empty($errorMessage)) {
            $html .= '<div class="error-message">' . $errorMessage . '</div>';
        }
        
        return $html;
    }
}

if (!function_exists('render_form_dropdown')) {
    /**
     * Tạo dropdown với các tùy chọn mở rộng
     *
     * @param string $name            Tên trường
     * @param array  $options         Các tùy chọn cho dropdown
     * @param string|array $selected  Giá trị đã chọn
     * @param array  $attributes      Thuộc tính bổ sung
     * @param string $label           Nhãn cho trường
     * @param bool   $required        Trường bắt buộc hay không
     * @param string $errorMessage    Thông báo lỗi (nếu có)
     * @return string
     */
    function render_form_dropdown($name, $options = [], $selected = '', $attributes = [], $label = '', $required = false, $errorMessage = '')
    {
        $html = '';
        
        // Thêm ID nếu không có
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Thêm class form-control
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-control';
        } else {
            $attributes['class'] .= ' form-control';
        }
        
        // Thêm thuộc tính required nếu cần
        if ($required) {
            $attributes['required'] = 'required';
        }
        
        // Thêm placeholder option nếu không có
        if (!isset($attributes['placeholder'])) {
            // Thêm một option rỗng vào đầu danh sách
            $options = ['' => '-- Chọn --'] + $options;
        } else {
            $placeholder = $attributes['placeholder'];
            unset($attributes['placeholder']);
            $options = ['' => $placeholder] + $options;
        }
        
        // Thêm label nếu có
        if (!empty($label)) {
            $labelAttributes = [];
            if ($required) {
                $labelAttributes['class'] = 'required';
            }
            $html .= form_label($label, $attributes['id'], $labelAttributes);
        }
        
        // Tạo dropdown
        $html .= form_dropdown($name, $options, $selected, $attributes);
        
        // Thêm thông báo lỗi nếu có
        if (!empty($errorMessage)) {
            $html .= '<div class="error-message">' . $errorMessage . '</div>';
        }
        
        return $html;
    }
}

if (!function_exists('render_form_checkbox')) {
    /**
     * Tạo checkbox với các tùy chọn mở rộng
     *
     * @param string $name            Tên trường
     * @param string $value           Giá trị khi chọn
     * @param bool   $checked         Trạng thái checked
     * @param array  $attributes      Thuộc tính bổ sung
     * @param string $label           Nhãn cho checkbox
     * @return string
     */
    function render_form_checkbox($name, $value = '1', $checked = false, $attributes = [], $label = '')
    {
        $html = '<div class="form-check">';
        
        // Thêm ID nếu không có
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Thêm class form-check-input
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-check-input';
        } else {
            $attributes['class'] .= ' form-check-input';
        }
        
        // Tạo checkbox
        $html .= form_checkbox($name, $value, $checked, $attributes);
        
        // Thêm label nếu có
        if (!empty($label)) {
            $html .= form_label($label, $attributes['id'], ['class' => 'form-check-label']);
        }
        
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('render_form_radio')) {
    /**
     * Tạo radio button với các tùy chọn mở rộng
     *
     * @param string $name            Tên trường
     * @param string $value           Giá trị khi chọn
     * @param bool   $checked         Trạng thái checked
     * @param array  $attributes      Thuộc tính bổ sung
     * @param string $label           Nhãn cho radio
     * @return string
     */
    function render_form_radio($name, $value, $checked = false, $attributes = [], $label = '')
    {
        $html = '<div class="form-check">';
        
        // Thêm ID nếu không có
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name . '_' . $value;
        }
        
        // Thêm class form-check-input
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-check-input';
        } else {
            $attributes['class'] .= ' form-check-input';
        }
        
        // Tạo radio
        $html .= form_radio($name, $value, $checked, $attributes);
        
        // Thêm label nếu có
        if (!empty($label)) {
            $html .= form_label($label, $attributes['id'], ['class' => 'form-check-label']);
        }
        
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('render_form_radio_group')) {
    /**
     * Tạo nhóm radio buttons
     *
     * @param string $name            Tên trường
     * @param array  $options         Các tùy chọn (value => label)
     * @param string $selected        Giá trị đã chọn
     * @param array  $attributes      Thuộc tính bổ sung cho mỗi radio
     * @param string $groupLabel      Nhãn cho nhóm radio
     * @return string
     */
    function render_form_radio_group($name, $options = [], $selected = '', $attributes = [], $groupLabel = '')
    {
        $html = '';
        
        // Thêm label cho nhóm nếu có
        if (!empty($groupLabel)) {
            $html .= '<div class="form-group-label">' . $groupLabel . '</div>';
        }
        
        $html .= '<div class="radio-group">';
        
        // Tạo các radio buttons
        foreach ($options as $value => $label) {
            $radioAttrs = $attributes;
            $radioAttrs['id'] = $name . '_' . $value;
            $checked = ($selected == $value);
            
            $html .= render_form_radio($name, $value, $checked, $radioAttrs, $label);
        }
        
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('render_form_submit')) {
    /**
     * Tạo nút submit với các tùy chọn mở rộng
     *
     * @param string $name            Tên nút
     * @param string $value           Nhãn hiển thị trên nút
     * @param array  $attributes      Thuộc tính bổ sung
     * @return string
     */
    function render_form_submit($name = 'submit', $value = 'Gửi', $attributes = [])
    {
        // Thêm class btn btn-primary
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'btn btn-primary';
        } else {
            $attributes['class'] .= ' btn btn-primary';
        }
        
        return form_submit($name, $value, $attributes);
    }
}

if (!function_exists('render_form_button')) {
    /**
     * Tạo nút button với các tùy chọn mở rộng
     *
     * @param string $name            Tên nút
     * @param string $content         Nội dung trong nút
     * @param array  $attributes      Thuộc tính bổ sung
     * @return string
     */
    function render_form_button($name = '', $content = 'Button', $attributes = [])
    {
        // Thêm class btn
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'btn';
        } else {
            $attributes['class'] .= ' btn';
        }
        
        return form_button($name, $content, $attributes);
    }
}

if (!function_exists('render_form_close')) {
    /**
     * Đóng form
     *
     * @return string
     */
    function render_form_close()
    {
        return form_close();
    }
}

if (!function_exists('render_form_multiselect')) {
    /**
     * Tạo multiselect dropdown với các tùy chọn mở rộng
     *
     * @param string $name            Tên trường
     * @param array  $options         Các tùy chọn cho dropdown
     * @param array  $selected        Các giá trị đã chọn
     * @param array  $attributes      Thuộc tính bổ sung
     * @param string $label           Nhãn cho trường
     * @param bool   $required        Trường bắt buộc hay không
     * @param string $errorMessage    Thông báo lỗi (nếu có)
     * @return string
     */
    function render_form_multiselect($name, $options = [], $selected = [], $attributes = [], $label = '', $required = false, $errorMessage = '')
    {
        $html = '';
        
        // Thêm ID nếu không có
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Thêm class form-control
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-control';
        } else {
            $attributes['class'] .= ' form-control';
        }
        
        // Thêm thuộc tính required nếu cần
        if ($required) {
            $attributes['required'] = 'required';
        }
        
        // Thêm thuộc tính multiple
        $attributes['multiple'] = 'multiple';
        
        // Thêm label nếu có
        if (!empty($label)) {
            $labelAttributes = [];
            if ($required) {
                $labelAttributes['class'] = 'required';
            }
            $html .= form_label($label, $attributes['id'], $labelAttributes);
        }
        
        // Tạo multiselect
        $html .= form_multiselect($name, $options, $selected, $attributes);
        
        // Thêm thông báo lỗi nếu có
        if (!empty($errorMessage)) {
            $html .= '<div class="error-message">' . $errorMessage . '</div>';
        }
        
        return $html;
    }
}

if (!function_exists('render_form_upload')) {
    /**
     * Tạo trường upload file với các tùy chọn mở rộng
     *
     * @param string $name            Tên trường
     * @param array  $attributes      Thuộc tính bổ sung
     * @param string $label           Nhãn cho trường
     * @param bool   $required        Trường bắt buộc hay không
     * @param string $errorMessage    Thông báo lỗi (nếu có)
     * @return string
     */
    function render_form_upload($name, $attributes = [], $label = '', $required = false, $errorMessage = '')
    {
        $html = '';
        
        // Thêm ID nếu không có
        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }
        
        // Thêm class form-control-file
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'form-control-file';
        } else {
            $attributes['class'] .= ' form-control-file';
        }
        
        // Thêm thuộc tính required nếu cần
        if ($required) {
            $attributes['required'] = 'required';
        }
        
        // Thêm label nếu có
        if (!empty($label)) {
            $labelAttributes = [];
            if ($required) {
                $labelAttributes['class'] = 'required';
            }
            $html .= form_label($label, $attributes['id'], $labelAttributes);
        }
        
        // Tạo trường upload
        $html .= form_upload($name, '', $attributes);
        
        // Thêm thông báo lỗi nếu có
        if (!empty($errorMessage)) {
            $html .= '<div class="error-message">' . $errorMessage . '</div>';
        }
        
        return $html;
    }
}

if (!function_exists('render_form_hidden')) {
    /**
     * Tạo trường ẩn
     *
     * @param string|array $name      Tên trường hoặc mảng key=>value
     * @param string $value           Giá trị (nếu $name là string)
     * @return string
     */
    function render_form_hidden($name, $value = '')
    {
        return form_hidden($name, $value);
    }
}
