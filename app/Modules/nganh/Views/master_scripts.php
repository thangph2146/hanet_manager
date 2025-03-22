<?php
/**
 * Các helper function cho views trong module nganh
 */

/**
 * Trả về URL của file CSS trong module nganh
 *
 * @param string $filename Tên file CSS (không bao gồm đuôi .css)
 * @return string URL đầy đủ của file CSS
 */
function nganh_css($filename) {
    return '<link rel="stylesheet" href="' . base_url("assets/modules/nganh/css/{$filename}.css") . '">';
}

/**
 * Trả về URL của file JavaScript trong module nganh
 *
 * @param string $filename Tên file JavaScript (không bao gồm đuôi .js)
 * @return string Thẻ script với URL đầy đủ
 */
function nganh_js($filename) {
    return '<script src="' . base_url("assets/modules/nganh/js/{$filename}.js") . '"></script>';
}

/**
 * Trả về CSS cho form ngành với style chuyên nghiệp
 * 
 * @return string Style CSS cho form
 */
function nganh_form_style() {
    return '<style>
    /* Form styling */
    .form-floating > .form-control:focus,
    .form-floating > .form-control:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: .65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    .form-control {
        border-left: none;
    }
    .input-group:focus-within .input-group-text {
        border-color: #86b7fe;
    }
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        background-color: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    .btn {
        border-radius: 0.25rem;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    .fade-in {
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .select2-container--bootstrap-5 .select2-selection {
        border-left: none;
        min-height: 38px;
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .required-label::after {
        content: " *";
        color: #dc3545;
    }
</style>';
}

/**
 * Trả về mã JavaScript và CSS cần thiết cho Select2
 * 
 * @return string Mã JS và CSS cho Select2
 */
function nganh_select2_assets() {
    return '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>';
}

/**
 * Trả về mã JavaScript khởi tạo Select2 và xử lý form
 * 
 * @return string Mã JavaScript khởi tạo Select2 và form handling
 */
function nganh_form_script() {
    return '<script>
(function () {
    "use strict";
    
    // Khởi tạo Select2
    $(document).ready(function() {
        $(".select2bs5").select2({
            theme: "bootstrap-5",
            width: "100%",
            dropdownParent: $("#nganh-form")
        });
        
        // Xử lý switch status
        $("#status_toggle").on("change", function() {
            if ($(this).is(":checked")) {
                $("#status_label").removeClass("bg-secondary").addClass("bg-success").text("Hoạt động");
                $(this).val("1");
            } else {
                $("#status_label").removeClass("bg-success").addClass("bg-secondary").text("Không hoạt động");
                $(this).val("0");
            }
        });
        
        // Focus vào trường đầu tiên
        $("#ma_nganh").focus();
    });

    // Hàm xác thực form khi submit
    window.addEventListener("load", function() {
        // Lấy tất cả các form có class needs-validation
        var forms = document.getElementsByClassName("needs-validation");
        
        // Lặp qua và ngăn chặn việc submit nếu có lỗi
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener("submit", function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Tự động scroll đến trường lỗi đầu tiên
                    const invalidField = form.querySelector(":invalid");
                    if (invalidField) {
                        invalidField.scrollIntoView({ behavior: "smooth", block: "center" });
                        invalidField.focus();
                    }
                }
                form.classList.add("was-validated");
            }, false);
        });
    }, false);
})();
</script>';
}

/**
 * Hiển thị thông báo lỗi cho field
 *
 * @param string $field Tên field
 * @return string HTML thông báo lỗi
 */
function nganh_error($field) {
    $errors = service('session')->get('errors');
    if ($errors && array_key_exists($field, $errors)) {
        return '<div class="invalid-feedback d-block">' . $errors[$field] . '</div>';
    }
    return '';
}

/**
 * Kiểm tra xem field có lỗi không
 *
 * @param string $field Tên field
 * @return bool True nếu field có lỗi
 */
function nganh_has_error($field) {
    $errors = service('session')->get('errors');
    return ($errors && array_key_exists($field, $errors));
}

/**
 * Trả về class 'is-invalid' nếu field có lỗi
 *
 * @param string $field Tên field
 * @return string 'is-invalid' hoặc chuỗi rỗng
 */
function nganh_invalid_class($field) {
    return nganh_has_error($field) ? 'is-invalid' : '';
}

/**
 * Lấy giá trị cũ của field
 *
 * @param string $field Tên field
 * @param mixed $default Giá trị mặc định
 * @return mixed Giá trị cũ hoặc giá trị mặc định
 */
function nganh_old($field, $default = '') {
    return old($field, $default);
}

// JavaScript cho trang danh sách
?>
<script>
$(document).ready(function() {
    // Xử lý chọn tất cả checkbox
    $('#select-all').on('change', function() {
        $('.checkbox-item').prop('checked', $(this).prop('checked'));
        updateBulkActionButtons();
    });
    
    // Cập nhật trạng thái các nút hành động hàng loạt
    $('.checkbox-item').on('change', function() {
        updateBulkActionButtons();
    });
    
    function updateBulkActionButtons() {
        var count = $('.checkbox-item:checked').length;
        if (count > 0) {
            $('#delete-selected, #status-selected').removeAttr('disabled');
        } else {
            $('#delete-selected, #status-selected').attr('disabled', 'disabled');
        }
    }
    
    // Xử lý nút xóa
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#delete-item-name').text(name);
        $('#delete-form').attr('action', base_url + '/nganh/delete/' + id);
        $('#deleteModal').modal('show');
    });
    
    // Xử lý nút xóa nhiều
    $('#delete-selected').on('click', function() {
        var count = $('.checkbox-item:checked').length;
        $('#selected-count').text(count);
        $('#deleteMultipleModal').modal('show');
    });
    
    // Xử lý xác nhận xóa nhiều
    $('#confirm-delete-multiple').on('click', function() {
        var selectedIds = [];
        $('.checkbox-item:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length > 0) {
            // Thêm các ID đã chọn vào form
            $('#form-delete-multiple').html('');
            selectedIds.forEach(function(id) {
                $('#form-delete-multiple').append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
            });
            
            // Submit form
            $('#form-delete-multiple').submit();
        }
    });
    
    // Xử lý nút đổi trạng thái nhiều
    $('#status-selected').on('click', function() {
        var count = $('.checkbox-item:checked').length;
        $('#status-count').text(count);
        $('#statusMultipleModal').modal('show');
    });
    
    // Xử lý xác nhận đổi trạng thái nhiều
    $('#confirm-status-multiple').on('click', function() {
        var selectedIds = [];
        $('.checkbox-item:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length > 0) {
            // Thêm các ID đã chọn vào form
            $('#form-status-multiple').html('');
            selectedIds.forEach(function(id) {
                $('#form-status-multiple').append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
            });
            $('#form-status-multiple').append('<input type="hidden" name="status" value="1">');
            
            // Submit form
            $('#form-status-multiple').submit();
        }
    });
    
    // Tìm kiếm
    $('#search-btn').on('click', function() {
        performSearch();
    });
    
    $('#table-search').on('keypress', function(e) {
        if (e.which === 13) {
            performSearch();
        }
    });
    
    function performSearch() {
        var searchTerm = $('#table-search').val().trim().toLowerCase();
        
        if (searchTerm === '') {
            // Hiển thị tất cả nếu không có từ khóa
            $('table tbody tr').show();
        } else {
            // Ẩn các dòng không khớp
            $('table tbody tr').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(searchTerm) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }
        
        // Cập nhật số lượng bản ghi hiển thị
        $('#total-records').text($('table tbody tr:visible').length);
    }
    
    // Nút làm mới bảng
    $('#refresh-table').on('click', function() {
        location.reload();
    });
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script> 