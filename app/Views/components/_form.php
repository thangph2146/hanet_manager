<?php
/**
 * Form Component sử dụng Bootstrap 5.2
 * 
 * Component này giúp tạo form và các phần tử form một cách linh hoạt
 * Hỗ trợ các tính năng: Bootstrap Grid, Floating Labels, Responsive layout
 * 
 * ==== CÁCH SỬ DỤNG ====
 * 
 * 1. Form cơ bản:
 * 
 * renderForm([
 *     'method' => 'post',
 *     'action' => 'your-action-url',
 *     'elements' => [
 *         ['type' => 'text', 'name' => 'username', 'label' => 'Tên người dùng']
 *     ]
 * ]);
 * 
 * 2. Form với grid layout:
 * 
 * renderForm([
 *     'layout' => ['useGrid' => true],
 *     'elements' => [
 *         [
 *             'isRow' => true,
 *             'elements' => [
 *                 ['type' => 'text', 'colClass' => 'col-md-6', ...],
 *                 ['type' => 'email', 'colClass' => 'col-md-6', ...]
 *             ]
 *         ]
 *     ]
 * ]);
 * 
 * 3. Form với floating labels:
 * 
 * renderForm([
 *     'layout' => ['useFloatingLabels' => true],
 *     'elements' => [...]
 * ]);
 * 
 * ==== THAM SỐ CHÍNH ====
 * 
 * @param array $attributes - Các thuộc tính cho form (id, class, etc.)
 * @param string $method - Phương thức form (GET/POST)
 * @param string $action - URL xử lý form
 * @param bool $hasFiles - Form có upload file hay không
 * @param array $elements - Các phần tử form
 * @param array $layout - Cấu hình layout form (grid, floating labels)
 * @param array $buttonOptions - Tùy chỉnh nút submit/reset
 */

/**
 * Render một phần tử form (input, select, textarea, checkbox, etc.)
 *
 * @param array $element Mảng cấu hình phần tử
 * @param array $layoutOptions Tùy chọn layout được truyền từ hàm renderForm
 * @return void
 */
function renderFormElement($element, $layoutOptions = []) {
    // ===== 1. LẤY THÔNG TIN CƠ BẢN =====
    
    // Thông tin cần thiết của phần tử
    $type = $element['type'] ?? 'text';           // Loại input (text, email, password...)
    $name = $element['name'] ?? '';               // Tên trường
    $id = $element['id'] ?? $name;                // ID của trường (mặc định = name)
    $label = $element['label'] ?? ucfirst($name); // Nhãn hiển thị
    $value = $element['value'] ?? '';             // Giá trị
    $placeholder = $element['placeholder'] ?? ''; // Placeholder
    
    // Thuộc tính HTML cơ bản
    $required = isset($element['required']) && $element['required'] ? 'required' : '';
    $disabled = isset($element['disabled']) && $element['disabled'] ? 'disabled' : '';
    $readonly = isset($element['readonly']) && $element['readonly'] ? 'readonly' : '';
    $multiple = isset($element['multiple']) && $element['multiple'] ? 'multiple' : '';
    $style = $element['style'] ?? '';
    $attributes = $element['attributes'] ?? [];
    
    // Các class CSS
    $class = $element['class'] ?? '';
    $inputClass = 'form-control';
    
    // Dữ liệu bổ sung
    $options = $element['options'] ?? [];         // Tùy chọn cho select, radio
    $help = $element['help'] ?? '';               // Text trợ giúp
    $error = $element['error'] ?? '';             // Thông báo lỗi
    
    // Thiết lập layout
    $useFloatingLabel = $element['floating'] ?? $layoutOptions['useFloatingLabels'] ?? false;
    $wrapperClass = $element['wrapperClass'] ?? $layoutOptions['wrapperClass'] ?? 'mb-3';
    $labelClass = $element['labelClass'] ?? $layoutOptions['labelClass'] ?? 'form-label';
    $colClass = $element['colClass'] ?? '';
    
    // Xử lý trạng thái lỗi
    if (!empty($error)) {
        $inputClass .= ' is-invalid';
    }
    
    // Tạo chuỗi thuộc tính bổ sung
    $attributesStr = '';
    foreach ($attributes as $attrKey => $attrValue) {
        $attributesStr .= ' ' . $attrKey . '="' . $attrValue . '"';
    }
    
    // ===== 2. BẮT ĐẦU RENDER PHẦN TỬ =====
    
    // Mở thẻ wrapper column nếu có
    if (!empty($colClass)) {
        echo '<div class="' . $colClass . '">';
    }
    
    // Mở thẻ wrapper form-group
    echo '<div class="' . $wrapperClass . '">';
    
    // Mở thẻ floating label nếu có
    if ($useFloatingLabel) {
        echo '<div class="form-floating">';
    }
    
    // ===== 3. RENDER THEO LOẠI PHẦN TỬ =====
    switch ($type) {
        // Trường ẩn
        case 'hidden':
            echo '<input type="hidden" id="' . $id . '" name="' . $name . '" value="' . $value . '">';
            break;
            
        // Vùng text nhiều dòng
        case 'textarea':
            renderTextarea($id, $name, $value, $label, $placeholder, $required, 
                           $disabled, $readonly, $style, $attributesStr, 
                           $inputClass, $class, $useFloatingLabel, $labelClass);
            break;
            
        // Dropdown select
        case 'select':
            renderSelect($id, $name, $value, $label, $options, $required, 
                        $disabled, $multiple, $style, $attributesStr, 
                        $inputClass, $class, $useFloatingLabel, $labelClass);
            break;
            
        // Checkbox
        case 'checkbox':
            renderCheckbox($id, $name, $value, $label, $required, 
                          $disabled, $readonly, $style, $attributesStr, $class);
            break;
            
        // Radio buttons
        case 'radio':
            renderRadioGroup($id, $name, $value, $options, $required, 
                            $disabled, $readonly, $style, $attributesStr, $class);
            break;
            
        // Upload file
        case 'file':
            renderFileInput($id, $name, $label, $required, $disabled, 
                           $multiple, $style, $attributesStr, 
                           $inputClass, $class, $useFloatingLabel, $labelClass);
            break;
            
        // Trường nhập mặc định (text, email, password, number, date, etc.)
        default:
            renderDefaultInput($type, $id, $name, $value, $label, $placeholder, 
                              $required, $disabled, $readonly, $style, 
                              $attributesStr, $inputClass, $class, 
                              $useFloatingLabel, $labelClass);
            break;
    }
    
    // Đóng thẻ floating label nếu có
    if ($useFloatingLabel) {
        echo '</div>';
    }
    
    // Hiển thị help text
    if (!empty($help)) {
        echo '<div class="form-text">' . $help . '</div>';
    }
    
    // Hiển thị thông báo lỗi
    if (!empty($error)) {
        echo '<div class="invalid-feedback">' . $error . '</div>';
    }
    
    // Đóng thẻ form-group wrapper
    echo '</div>';
    
    // Đóng thẻ wrapper column nếu có
    if (!empty($colClass)) {
        echo '</div>';
    }
}

/**
 * Render thẻ textarea
 */
function renderTextarea($id, $name, $value, $label, $placeholder, $required, 
                        $disabled, $readonly, $style, $attributesStr, 
                        $inputClass, $class, $useFloatingLabel, $labelClass) {
    if ($useFloatingLabel) {
        echo '<textarea class="' . $inputClass . ' ' . $class . '" id="' . $id . '" name="' . $name . '" '
            . 'placeholder="' . $placeholder . '" ' . $required . ' ' . $disabled . ' ' . $readonly . ' '
            . 'style="' . $style . '"' . $attributesStr . '>' . $value . '</textarea>';
        echo '<label for="' . $id . '">' . $label . '</label>';
    } else {
        // Label trước (cách thông thường)
        if (!empty($label)) {
            echo '<label for="' . $id . '" class="' . $labelClass . '">' . $label . '</label>';
        }
        echo '<textarea class="' . $inputClass . ' ' . $class . '" id="' . $id . '" name="' . $name . '" '
            . 'placeholder="' . $placeholder . '" ' . $required . ' ' . $disabled . ' ' . $readonly . ' '
            . 'style="' . $style . '"' . $attributesStr . '>' . $value . '</textarea>';
    }
}

/**
 * Render thẻ select dropdown
 */
function renderSelect($id, $name, $value, $label, $options, $required, 
                     $disabled, $multiple, $style, $attributesStr, 
                     $inputClass, $class, $useFloatingLabel, $labelClass) {
    // Mở thẻ select
    $selectHtml = '<select class="' . $inputClass . ' ' . $class . '" id="' . $id . '" name="' . $name . '" '
        . $required . ' ' . $disabled . ' ' . $multiple . ' style="' . $style . '"' . $attributesStr . '>';
    
    // Thêm các options
    foreach ($options as $optionValue => $optionLabel) {
        $selected = ($value == $optionValue) ? 'selected' : '';
        $selectHtml .= '<option value="' . $optionValue . '" ' . $selected . '>' . $optionLabel . '</option>';
    }
    
    // Đóng thẻ select
    $selectHtml .= '</select>';
    
    // Hiển thị với floating label hoặc label thông thường
    if ($useFloatingLabel) {
        echo $selectHtml;
        echo '<label for="' . $id . '">' . $label . '</label>';
    } else {
        if (!empty($label)) {
            echo '<label for="' . $id . '" class="' . $labelClass . '">' . $label . '</label>';
        }
        echo $selectHtml;
    }
}

/**
 * Render thẻ checkbox
 */
function renderCheckbox($id, $name, $value, $label, $required, 
                       $disabled, $readonly, $style, $attributesStr, $class) {
    $inputClass = 'form-check-input';
    echo '<div class="form-check">';
    echo '<input type="checkbox" class="' . $inputClass . ' ' . $class . '" id="' . $id . '" name="' . $name . '" '
        . 'value="1" ' . ($value ? 'checked' : '') . ' ' . $required . ' ' . $disabled . ' ' . $readonly . ' '
        . 'style="' . $style . '"' . $attributesStr . '>';
    echo '<label class="form-check-label" for="' . $id . '">' . $label . '</label>';
    echo '</div>';
}

/**
 * Render nhóm radio buttons
 */
function renderRadioGroup($id, $name, $value, $options, $required, 
                         $disabled, $readonly, $style, $attributesStr, $class) {
    $inputClass = 'form-check-input';
    echo '<div class="form-check">';
    foreach ($options as $optionValue => $optionLabel) {
        $optionId = $id . '_' . $optionValue;
        $checked = ($value == $optionValue) ? 'checked' : '';
        echo '<div class="form-check">';
        echo '<input class="' . $inputClass . ' ' . $class . '" type="radio" name="' . $name . '" id="' . $optionId . '" '
            . 'value="' . $optionValue . '" ' . $checked . ' ' . $required . ' ' . $disabled . ' ' . $readonly . ' '
            . 'style="' . $style . '"' . $attributesStr . '>';
        echo '<label class="form-check-label" for="' . $optionId . '">' . $optionLabel . '</label>';
        echo '</div>';
    }
    echo '</div>';
}

/**
 * Render thẻ input file
 */
function renderFileInput($id, $name, $label, $required, $disabled, 
                        $multiple, $style, $attributesStr, 
                        $inputClass, $class, $useFloatingLabel, $labelClass) {
    $fileInput = '<input type="file" class="' . $inputClass . ' ' . $class . '" id="' . $id . '" name="' . $name . '" '
        . $required . ' ' . $disabled . ' ' . $multiple . ' style="' . $style . '"' . $attributesStr . '>';
    
    if ($useFloatingLabel) {
        echo $fileInput;
        echo '<label for="' . $id . '">' . $label . '</label>';
    } else {
        if (!empty($label)) {
            echo '<label for="' . $id . '" class="' . $labelClass . '">' . $label . '</label>';
        }
        echo $fileInput;
    }
}

/**
 * Render thẻ input thông thường (text, email, password, etc.)
 */
function renderDefaultInput($type, $id, $name, $value, $label, $placeholder, 
                           $required, $disabled, $readonly, $style, 
                           $attributesStr, $inputClass, $class, 
                           $useFloatingLabel, $labelClass) {
    $input = '<input type="' . $type . '" class="' . $inputClass . ' ' . $class . '" id="' . $id . '" name="' . $name . '" '
        . 'value="' . $value . '" placeholder="' . $placeholder . '" ' . $required . ' ' . $disabled . ' ' . $readonly . ' '
        . 'style="' . $style . '"' . $attributesStr . '>';
    
    if ($useFloatingLabel) {
        echo $input;
        echo '<label for="' . $id . '">' . $label . '</label>';
    } else {
        if (!empty($label)) {
            echo '<label for="' . $id . '" class="' . $labelClass . '">' . $label . '</label>';
        }
        echo $input;
    }
}

/**
 * Render form hoàn chỉnh
 *
 * @param array $params Các tham số cấu hình form
 * @return void
 */
function renderForm($params = []) {
    // ===== 1. CẤU HÌNH FORM =====
    
    // Thuộc tính cơ bản
    $attributes = $params['attributes'] ?? [];
    $method = strtoupper($params['method'] ?? 'POST');
    $action = $params['action'] ?? '';
    $hasFiles = $params['hasFiles'] ?? false;
    $elements = $params['elements'] ?? [];
    
    // Tùy chọn layout
    $layout = $params['layout'] ?? [];
    $useGrid = $layout['useGrid'] ?? false;                  // Sử dụng grid layout
    $gridClass = $layout['gridClass'] ?? 'row g-3';          // Class cho grid
    $useFloatingLabels = $layout['useFloatingLabels'] ?? false; // Sử dụng floating labels
    $wrapperClass = $layout['wrapperClass'] ?? 'mb-3';       // Class cho wrapper
    
    // Tùy chọn nút
    $buttonOptions = $params['buttonOptions'] ?? [];
    $submitText = $buttonOptions['submitText'] ?? $params['submitText'] ?? 'Lưu';
    $resetText = $buttonOptions['resetText'] ?? $params['resetText'] ?? 'Hủy';
    $showReset = $buttonOptions['showReset'] ?? $params['showReset'] ?? true;
    $submitClass = $buttonOptions['submitClass'] ?? 'btn btn-primary';
    $resetClass = $buttonOptions['resetClass'] ?? 'btn btn-secondary';
    $buttonWrapperClass = $buttonOptions['wrapperClass'] ?? 'mb-3';
    $buttonContainerClass = $buttonOptions['containerClass'] ?? '';
    
    // ===== 2. RENDER FORM TAG =====
    
    // Tạo chuỗi thuộc tính HTML
    $attributesStr = '';
    foreach ($attributes as $key => $value) {
        $attributesStr .= ' ' . $key . '="' . $value . '"';
    }
    
    // Mở thẻ form
    echo '<form method="' . $method . '" action="' . $action . '"' . 
        ($hasFiles ? ' enctype="multipart/form-data"' : '') . 
        $attributesStr . '>';
    
    // Thêm token CSRF cho bảo mật (CodeIgniter)
    if (function_exists('csrf_field')) {
        echo csrf_field();
    }
    
    // ===== 3. RENDER FORM ELEMENTS =====
    
    // Tạo options layout cho các phần tử
    $layoutOptions = [
        'useFloatingLabels' => $useFloatingLabels,
        'wrapperClass' => $wrapperClass
    ];
    
    // Theo dõi trạng thái grid
    $inRow = false;
    
    // Render từng phần tử form
    foreach ($elements as $element) {
        // Trường hợp đặc biệt: Phần tử là một row (chứa nhiều phần tử con)
        if (isset($element['isRow']) && $element['isRow'] && $useGrid) {
            // Đóng row trước đó nếu cần
            if ($inRow) {
                echo '</div>'; // Đóng row
            }
            
            // Mở row mới
            $customRowClass = $element['rowClass'] ?? $gridClass;
            echo '<div class="' . $customRowClass . '">';
            $inRow = true;
            
            // Render các phần tử con trong row
            if (isset($element['elements']) && is_array($element['elements'])) {
                foreach ($element['elements'] as $childElement) {
                    renderFormElement($childElement, $layoutOptions);
                }
            }
            
            // Đóng row nếu cần
            if (!isset($element['keepOpen']) || !$element['keepOpen']) {
                echo '</div>'; // Đóng row
                $inRow = false;
            }
        } 
        // Trường hợp thông thường: Phần tử đơn lẻ
        else {
            // Nếu sử dụng grid mà chưa mở row nào
            if ($useGrid && !$inRow) {
                echo '<div class="' . $gridClass . '">';
                $inRow = true;
            }
            
            // Render phần tử form
            renderFormElement($element, $layoutOptions);
        }
    }
    
    // Đóng row cuối cùng nếu cần
    if ($useGrid && $inRow) {
        echo '</div>'; // Đóng row
    }
    
    // ===== 4. RENDER FORM BUTTONS =====
    
    // Container cho nút (nếu có)
    if (!empty($buttonContainerClass)) {
        echo '<div class="' . $buttonContainerClass . '">';
    }
    
    // Wrapper cho nút
    echo '<div class="' . $buttonWrapperClass . '">';
    
    // Nút Submit
    echo '<button type="submit" class="' . $submitClass . '">' . $submitText . '</button>';
    
    // Nút Reset (tùy chọn)
    if ($showReset) {
        echo ' <button type="reset" class="' . $resetClass . '">' . $resetText . '</button>';
    }
    
    // Đóng thẻ wrapper
    echo '</div>';
    
    // Đóng container nếu có
    if (!empty($buttonContainerClass)) {
        echo '</div>';
    }
    
    // Đóng thẻ form
    echo '</form>';
}
?>
