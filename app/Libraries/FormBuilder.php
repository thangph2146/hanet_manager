<?php

namespace App\Libraries;

use CodeIgniter\HTTP\RequestInterface;

/**
 * FormBuilder - Thư viện xây dựng form linh hoạt cho CodeIgniter 4
 * Hỗ trợ tạo form dựa trên cấu hình, làm đơn giản hóa việc xây dựng form
 */
class FormBuilder
{
    protected $config = [];
    protected $fields = [];
    protected $request;
    protected $validation;
    protected $formAttributes = [];
    protected $errorMessages = [];
    protected $formData = [];
    protected $formGroups = [];
    protected $currentGroup = 'default';
    protected $layouts = [
        'default' => 'form/default',
        'horizontal' => 'form/horizontal',
        'inline' => 'form/inline',
    ];
    protected $currentLayout = 'default';
    protected $displayErrors = true;
    protected $useTimepicker = false;
    protected $timeFormats = [
        'default' => 'HH:mm',
        'seconds' => 'HH:mm:ss',
        '12hour' => 'hh:mm A'
    ];
    
    /**
     * Khởi tạo FormBuilder
     */
    public function __construct()
    {
        helper('form');
        $this->request = service('request');
        $this->validation = service('validation');
    }
    
    /**
     * Bật/tắt sử dụng timepicker
     * 
     * @param bool $use Bật/tắt sử dụng timepicker
     * @return FormBuilder
     */
    public function useTimepicker($use = true)
    {
        $this->useTimepicker = $use;
        return $this;
    }
    
    /**
     * Đặt cấu hình form
     * 
     * @param array $config Cấu hình form
     * @return FormBuilder
     */
    public function config(array $config)
    {
        $this->config = $config;
        
        // Xử lý thuộc tính form từ cấu hình
        if (isset($config['attributes'])) {
            $this->formAttributes = $config['attributes'];
        }
        
        // Xử lý layout form
        if (isset($config['layout'])) {
            $this->currentLayout = $config['layout'];
        }
        
        // Xử lý sử dụng timepicker
        if (isset($config['use_timepicker'])) {
            $this->useTimepicker = $config['use_timepicker'];
        }
        
        // Xử lý fields từ cấu hình
        if (isset($config['fields']) && is_array($config['fields'])) {
            foreach ($config['fields'] as $name => $field) {
                $this->addField($name, $field);
            }
        }
        
        // Xử lý fieldsets/groups
        if (isset($config['fieldsets']) && is_array($config['fieldsets'])) {
            foreach ($config['fieldsets'] as $name => $fieldset) {
                $this->addGroup($name, $fieldset);
            }
        }
        
        return $this;
    }
    
    /**
     * Thêm trường từ mảng cấu hình
     * 
     * @param string $name Tên trường
     * @param array $field Cấu hình trường
     * @return FormBuilder
     */
    public function addField($name, array $field)
    {
        $field['name'] = $name;
        
        // Đảm bảo type mặc định là text
        if (!isset($field['type'])) {
            $field['type'] = 'text';
        }
        
        // Đặt giá trị từ dữ liệu được gửi hoặc từ cấu hình
        $field['value'] = $this->getValue($name, $field['value'] ?? '');
        
        // Đặt nhóm cho trường
        $group = $field['group'] ?? $this->currentGroup;
        
        $this->fields[$name] = $field;
        $this->formGroups[$group][] = $name;
        
        return $this;
    }
    
    /**
     * Thêm nhóm trường (fieldset)
     * 
     * @param string $name Tên nhóm
     * @param array $group Cấu hình nhóm
     * @return FormBuilder
     */
    public function addGroup($name, array $group)
    {
        $this->formGroups[$name] = $group;
        
        // Thêm các trường trong nhóm
        if (isset($group['fields']) && is_array($group['fields'])) {
            $this->startGroup($name);
            foreach ($group['fields'] as $fieldName => $field) {
                $this->addField($fieldName, $field);
            }
            $this->endGroup();
        }
        
        return $this;
    }
    
    /**
     * Bắt đầu nhóm mới
     * 
     * @param string $name Tên nhóm
     * @return FormBuilder
     */
    public function startGroup($name)
    {
        $this->currentGroup = $name;
        return $this;
    }
    
    /**
     * Kết thúc nhóm hiện tại
     * 
     * @return FormBuilder
     */
    public function endGroup()
    {
        $this->currentGroup = 'default';
        return $this;
    }
    
    /**
     * Đặt giá trị cho trường
     * 
     * @param string $name Tên trường
     * @param mixed $value Giá trị
     * @return FormBuilder
     */
    public function setValue($name, $value)
    {
        $this->formData[$name] = $value;
        
        if (isset($this->fields[$name])) {
            $this->fields[$name]['value'] = $value;
        }
        
        return $this;
    }
    
    /**
     * Lấy giá trị của trường
     * 
     * @param string $name Tên trường
     * @param mixed $default Giá trị mặc định
     * @return mixed
     */
    public function getValue($name, $default = '')
    {
        // Thứ tự ưu tiên: dữ liệu cũ -> dữ liệu POST -> dữ liệu được đặt -> mặc định
        return old($name) ?? $this->request->getPost($name) ?? ($this->formData[$name] ?? $default);
    }
    
    /**
     * Đặt dữ liệu form từ một mảng
     * 
     * @param array $data Dữ liệu form
     * @return FormBuilder
     */
    public function setData(array $data)
    {
        $this->formData = array_merge($this->formData, $data);
        
        // Cập nhật giá trị của các trường
        foreach ($this->fields as $name => $field) {
            if (array_key_exists($name, $this->formData)) {
                $this->fields[$name]['value'] = $this->formData[$name];
            }
        }
        
        return $this;
    }
    
    /**
     * Xác thực dữ liệu form
     * 
     * @param array|null $rules Quy tắc xác thực
     * @return bool
     */
    public function validate($rules = null)
    {
        // Nếu không có quy tắc cụ thể, sử dụng quy tắc từ cấu hình
        if ($rules === null && isset($this->config['validation'])) {
            $rules = $this->config['validation'];
        }
        
        if (empty($rules)) {
            return true;
        }
        
        $this->validation->setRules($rules);
        
        if ($this->validation->withRequest($this->request)->run()) {
            return true;
        }
        
        $this->errorMessages = $this->validation->getErrors();
        return false;
    }
    
    /**
     * Render trường form
     * 
     * @param string|int $name Tên trường
     * @return string
     */
    public function renderField($name)
    {
        // Kiểm tra kiểu dữ liệu của $name
        if (!is_string($name) && !is_int($name)) {
            // Ghi log hoặc debug khi cần
            log_message('error', 'Invalid field name type: ' . gettype($name));
            return '';
        }
        
        if (!isset($this->fields[$name])) {
            return '';
        }
        
        $field = $this->fields[$name];
        $type = $field['type'];
        $errorMessage = isset($this->errorMessages[$name]) ? $this->errorMessages[$name] : '';
        $label = $field['label'] ?? ucfirst($name);
        $required = isset($field['rules']) && strpos($field['rules'], 'required') !== false;
        $hasError = !empty($errorMessage);
        
        // Xây dựng thuộc tính cho trường
        $attributes = $field['attributes'] ?? [];
        
        // Thêm class cho trường nếu có lỗi
        if ($hasError && $this->displayErrors) {
            $attributes['class'] = isset($attributes['class']) 
                ? $attributes['class'] . ' is-invalid' 
                : 'is-invalid';
        }
        
        // Tạo HTML cho trường dựa trên loại
        $html = '';
        
        switch ($type) {
            case 'text':
            case 'email':
            case 'password':
            case 'number':
            case 'url':
            case 'tel':
            case 'color':
            case 'range':
            case 'month':
            case 'week':
            case 'search':
                $html = form_input(array_merge([
                    'name' => $name,
                    'type' => $type,
                    'value' => $field['value'],
                    'id' => $field['id'] ?? $name,
                ], $attributes));
                break;
                
            case 'date':
                // Nếu sử dụng timepicker và có tùy chọn datepicker
                if ($this->useTimepicker && !empty($field['datepicker'])) {
                    $attributes['class'] = ($attributes['class'] ?? '') . ' datepicker';
                    $attributes['data-date-format'] = $field['date_format'] ?? 'yyyy-mm-dd';
                }
                $html = form_input(array_merge([
                    'name' => $name,
                    'type' => 'date',
                    'value' => $field['value'],
                    'id' => $field['id'] ?? $name,
                ], $attributes));
                break;
                
            case 'time':
                // Sử dụng timepicker nếu được bật
                if ($this->useTimepicker) {
                    $attributes['class'] = ($attributes['class'] ?? '') . ' timepicker';
                    $timeFormat = $field['time_format'] ?? 'default';
                    $attributes['data-time-format'] = $this->timeFormats[$timeFormat] ?? $this->timeFormats['default'];
                    
                    $html = form_input(array_merge([
                        'name' => $name,
                        'type' => 'text',
                        'value' => $field['value'],
                        'id' => $field['id'] ?? $name,
                    ], $attributes));
                } else {
                    $html = form_input(array_merge([
                        'name' => $name,
                        'type' => 'time',
                        'value' => $field['value'],
                        'id' => $field['id'] ?? $name,
                    ], $attributes));
                }
                break;
                
            case 'datetime':
                // Sử dụng datetimepicker
                if ($this->useTimepicker) {
                    $attributes['class'] = ($attributes['class'] ?? '') . ' datetimepicker';
                    $dateTimeFormat = $field['datetime_format'] ?? 'yyyy-mm-dd HH:mm';
                    $attributes['data-date-format'] = $dateTimeFormat;
                    
                    $html = form_input(array_merge([
                        'name' => $name,
                        'type' => 'text',
                        'value' => $field['value'],
                        'id' => $field['id'] ?? $name,
                    ], $attributes));
                } else {
                    $html = form_input(array_merge([
                        'name' => $name,
                        'type' => 'datetime-local',
                        'value' => $field['value'],
                        'id' => $field['id'] ?? $name,
                    ], $attributes));
                }
                break;
                
            case 'timepicker':
                // Trường chọn giờ tùy chỉnh
                $attributes['class'] = ($attributes['class'] ?? '') . ' timepicker';
                $timeFormat = $field['time_format'] ?? 'default';
                $attributes['data-time-format'] = $this->timeFormats[$timeFormat] ?? $this->timeFormats['default'];
                
                $html = form_input(array_merge([
                    'name' => $name,
                    'type' => 'text',
                    'value' => $field['value'],
                    'id' => $field['id'] ?? $name,
                ], $attributes));
                
                // Thêm script nếu chưa có
                $html .= $this->getTimepickerScript($name, $field);
                break;
                
            case 'textarea':
                $html = form_textarea(array_merge([
                    'name' => $name,
                    'value' => $field['value'],
                    'id' => $field['id'] ?? $name,
                ], $attributes));
                break;
                
            case 'select':
                $options = $field['options'] ?? [];
                $html = form_dropdown(
                    $name,
                    $options,
                    $field['value'],
                    array_merge(['id' => $field['id'] ?? $name], $attributes)
                );
                break;
                
            case 'checkbox':
                $html = form_checkbox(array_merge([
                    'name' => $name,
                    'id' => $field['id'] ?? $name,
                    'value' => $field['value'] ?? '1',
                    'checked' => $field['checked'] ?? false,
                ], $attributes));
                break;
                
            case 'radio':
                $options = $field['options'] ?? [];
                $html = '';
                foreach ($options as $value => $option_label) {
                    $html .= '<div class="form-check">';
                    $html .= form_radio(array_merge([
                        'name' => $name,
                        'id' => $name . '_' . $value,
                        'value' => $value,
                        'checked' => $field['value'] == $value,
                    ], $attributes));
                    $html .= form_label($option_label, $name . '_' . $value, ['class' => 'form-check-label']);
                    $html .= '</div>';
                }
                break;
                
            case 'file':
                $html = form_upload(array_merge([
                    'name' => $name,
                    'id' => $field['id'] ?? $name,
                ], $attributes));
                break;
                
            case 'hidden':
                $html = form_hidden($name, $field['value']);
                break;
                
            case 'submit':
                $html = form_submit(array_merge([
                    'name' => $name,
                    'id' => $field['id'] ?? $name,
                    'value' => $field['value'] ?? 'Submit',
                ], $attributes));
                break;
                
            case 'button':
                $html = form_button(array_merge([
                    'name' => $name,
                    'id' => $field['id'] ?? $name,
                    'content' => $field['value'] ?? 'Button',
                    'type' => $field['button_type'] ?? 'button',
                ], $attributes));
                break;
                
            case 'html':
                // Trường HTML tùy chỉnh
                $html = $field['value'] ?? '';
                break;
        }
        
        // Tạo wrapper cho trường
        $fieldWrapper = $field['wrapper'] ?? 'div';
        $fieldWrapperAttr = $field['wrapper_attr'] ?? ['class' => 'form-group mb-3'];
        
        // Tạo HTML cho wrapper
        $wrapperStart = "<{$fieldWrapper} " . $this->parseAttributes($fieldWrapperAttr) . ">";
        $wrapperEnd = "</{$fieldWrapper}>";
        
        // Tạo label nếu cần
        $labelHtml = '';
        if ($label && $type != 'hidden' && $type != 'submit' && $type != 'button') {
            $labelAttr = $field['label_attr'] ?? ['class' => 'form-label'];
            if ($required) {
                $labelHtml = form_label($label . ' <span class="text-danger">*</span>', $field['id'] ?? $name, $labelAttr);
            } else {
                $labelHtml = form_label($label, $field['id'] ?? $name, $labelAttr);
            }
        }
        
        // Tạo thông báo lỗi nếu có
        $errorHtml = '';
        if ($hasError && $this->displayErrors) {
            $errorHtml = '<div class="invalid-feedback">' . $errorMessage . '</div>';
        }
        
        // Tạo mô tả cho trường nếu có
        $helpHtml = '';
        if (isset($field['help'])) {
            $helpHtml = '<div class="form-text text-muted">' . $field['help'] . '</div>';
        }
        
        // Tạo kết quả cuối cùng
        return $wrapperStart . $labelHtml . $html . $errorHtml . $helpHtml . $wrapperEnd;
    }
    
    /**
     * Tạo script cho timepicker
     * 
     * @param string $fieldId Field ID
     * @param array $fieldConfig Cấu hình trường
     * @return string
     */
    protected function getTimepickerScript($fieldId, $fieldConfig)
    {
        $id = $fieldConfig['id'] ?? $fieldId;
        $timeFormat = $fieldConfig['time_format'] ?? 'default';
        $format = $this->timeFormats[$timeFormat] ?? $this->timeFormats['default'];
        
        return "<script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof flatpickr !== 'undefined') {
                    flatpickr('#$id', {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: '$format',
                        time_24hr: " . (strpos($format, 'H') !== false ? 'true' : 'false') . "
                    });
                }
            });
        </script>";
    }
    
    /**
     * Render nhóm trường
     * 
     * @param string $name Tên nhóm
     * @return string
     */
    public function renderGroup($name)
    {
        if (!isset($this->formGroups[$name])) {
            return '';
        }
        
        $group = $this->formGroups[$name];
        $legend = isset($group['legend']) ? "<legend>{$group['legend']}</legend>" : '';
        $html = "<fieldset id=\"{$name}\">{$legend}";
        
        // Render các trường trong nhóm
        if (isset($group[0]) && is_string($group[0])) {
            // Danh sách tên trường
            foreach ($group as $key => $fieldName) {
                // Kiểm tra xem $fieldName có phải là chuỗi hoặc số nguyên không
                if (is_string($fieldName) || is_int($fieldName)) {
                    $html .= $this->renderField($fieldName);
                } else {
                    // Bỏ qua các phần tử không phải chuỗi hoặc số nguyên
                    log_message('warning', 'Skipping field with invalid name type: ' . gettype($fieldName) . ' at key ' . $key);
                }
            }
        } elseif (isset($group['fields']) && is_array($group['fields'])) {
            // Mảng cấu hình trường
            foreach ($group['fields'] as $fieldName => $field) {
                // Kiểm tra xem $fieldName có phải là chuỗi hoặc số nguyên không
                if (is_string($fieldName) || is_int($fieldName)) {
                    $html .= $this->renderField($fieldName);
                } else {
                    // Bỏ qua các phần tử không phải chuỗi hoặc số nguyên
                    log_message('warning', 'Skipping field with invalid name type in field configuration: ' . gettype($fieldName));
                }
            }
        }
        
        $html .= "</fieldset>";
        return $html;
    }
    
    /**
     * Render toàn bộ form
     * 
     * @param string|null $view View tùy chỉnh
     * @param array $data Dữ liệu bổ sung
     * @param bool $return Trả về HTML thay vì hiển thị
     * @return string|void
     */
    public function render($view = null, $data = [], $return = false)
    {
        // Nếu không có view cụ thể, sử dụng layout mặc định
        if ($view === null) {
            $view = $this->layouts[$this->currentLayout];
        }
        
        // Mở form
        $formOpen = form_open(
            $this->formAttributes['action'] ?? '',
            array_merge(['id' => $this->formAttributes['id'] ?? 'form-' . uniqid()], $this->formAttributes)
        );
        
        // Tạo HTML cho các trường
        $fieldsHtml = '';
        
        // Thêm CSS và JS nếu sử dụng timepicker
        $headerScripts = '';
        if ($this->useTimepicker) {
            $headerScripts .= '
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof flatpickr !== "undefined") {
                    flatpickr(".timepicker", {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true
                    });
                    
                    flatpickr(".datepicker", {
                        enableTime: false,
                        dateFormat: "Y-m-d"
                    });
                    
                    flatpickr(".datetimepicker", {
                        enableTime: true,
                        dateFormat: "Y-m-d H:i"
                    });
                }
            });
            </script>';
        }
        
        // Nếu có nhóm, render theo nhóm
        if (!empty($this->formGroups)) {
            foreach ($this->formGroups as $groupName => $group) {
                if ($groupName !== 'default') {
                    $fieldsHtml .= $this->renderGroup($groupName);
                }
            }
        }
        
        // Render các trường còn lại không thuộc nhóm nào
        if (isset($this->formGroups['default'])) {
            foreach ($this->formGroups['default'] as $fieldName) {
                if (is_string($fieldName) || is_int($fieldName)) {
                    $fieldsHtml .= $this->renderField($fieldName);
                } else {
                    log_message('warning', 'Skipping default field with invalid name type: ' . gettype($fieldName));
                }
            }
        }
        
        // Đóng form
        $formClose = form_close();
        
        // Dữ liệu cho view
        $viewData = array_merge($data, [
            'form_open' => $formOpen,
            'form_close' => $formClose,
            'fields_html' => $fieldsHtml,
            'fields' => $this->fields,
            'form_data' => $this->formData,
            'errors' => $this->errorMessages,
            'form_builder' => $this,
            'header_scripts' => $headerScripts
        ]);
        
        // Render view
        $output = view($view, $viewData);
        
        if ($return) {
            return $output;
        }
        
        echo $output;
    }
    
    /**
     * Chuyển đổi mảng thuộc tính thành chuỗi HTML
     * 
     * @param array $attributes Mảng thuộc tính
     * @return string
     */
    protected function parseAttributes($attributes)
    {
        if (empty($attributes)) {
            return '';
        }
        
        $html = '';
        foreach ($attributes as $key => $value) {
            $html .= " {$key}=\"{$value}\"";
        }
        
        return $html;
    }
} 