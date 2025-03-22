<?php
/**
 * Master script file for Nganh module
 * Contains common CSS and JS for all views
 */

// CSS section
function nganh_css($type = 'all') {
    ob_start();
    
    // Common CSS for DataTables
    if (in_array($type, ['all', 'table'])):
    ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
    <style>
        table.table-bordered.dataTable th:first-child:before {
            content: '';
        }
        table.table-bordered.dataTable th:first-child:after {
            content: '';
        }
        .form-check .form-check-input {
            margin-left: 0.05rem;
        }
        .table > :not(caption) > * > * {
            padding: 0.75rem !important;
        }
        .dataTables_wrapper .dataTables_length select {
            min-width: 80px;
        }
        .highlight-row {
            background-color: #e6f7ff !important;
            transition: background-color 0.3s ease;
        }
        .btn-icon {
            padding: 0.25rem 0.5rem;
        }
        .btn-icon i {
            font-size: 1.1rem;
        }
        .badge {
            padding: 0.5em 0.75em;
        }
    </style>
    <?php
    endif;
    
    // Form specific CSS
    if (in_array($type, ['all', 'form'])):
    ?>
    <!-- Form CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
    <style>
        .form-label {
            font-weight: 500;
        }
        .invalid-feedback {
            font-size: 0.85rem;
        }
        .btn {
            padding: 0.5rem 1rem;
        }
    </style>
    <?php
    endif;
    
    return ob_get_clean();
}

// JS section
function nganh_js($type = 'all') {
    ob_start();
    
    // DataTable scripts
    if (in_array($type, ['all', 'table'])):
    ?>
    <!-- DataTables JS -->
    <script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Hiển thị thông báo thành công/lỗi với SweetAlert2
            <?php if (session()->getFlashdata('success')): ?>
                Swal.fire({
                    title: 'Thành công',
                    text: '<?= session()->getFlashdata('success') ?>',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                Swal.fire({
                    title: 'Lỗi',
                    text: '<?= session()->getFlashdata('error') ?>',
                    icon: 'error'
                });
            <?php endif; ?>
        });
    </script>
    <?php
    endif;

    // Form specific scripts
    if (in_array($type, ['all', 'form'])):
    ?>
    <!-- Form JS -->
    <script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Form validation
            $('#form-nganh').validate({
                rules: {
                    ten_nganh: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    ma_nganh: {
                        required: true,
                        maxlength: 20
                    }
                },
                messages: {
                    ten_nganh: {
                        required: "Vui lòng nhập tên ngành",
                        minlength: "Tên ngành phải có ít nhất {0} ký tự",
                        maxlength: "Tên ngành không được vượt quá {0} ký tự"
                    },
                    ma_nganh: {
                        required: "Vui lòng nhập mã ngành",
                        maxlength: "Mã ngành không được vượt quá {0} ký tự"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            // Auto-generate code from name
            let typingTimer;
            $('#ten_nganh').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    const ten = $('#ten_nganh').val();
                    if (ten && !$('#ma_nganh').val()) {
                        const ma = generateCode(ten);
                        $('#ma_nganh').val(ma);
                    }
                }, 500);
            });

            // Helper function to generate code from name
            function generateCode(str) {
                str = str.toLowerCase();
                str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                str = str.replace(/đ/g, "d");
                str = str.replace(/\W+/g, " ");
                str = str.trim();
                return str.split(' ').map(word => word.charAt(0)).join('').toUpperCase();
            }
        });
    </script>
    <?php
    endif;
    
    return ob_get_clean();
}

/**
 * Trả về CSS theo mục cho module nganh
 * 
 * @param string $type Loại CSS (table, form, all)
 * @return string CSS code
 */
function nganh_section_css($type = 'all') {
    ob_start();
    
    // Table styles
    if (in_array($type, ['all', 'table'])):
    ?>
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }
        .action-btn-group {
            display: flex;
            justify-content: center;
            gap: 4px;
        }
        .badge-active {
            background-color: #28a745;
            color: white;
        }
        .badge-inactive {
            background-color: #dc3545;
            color: white;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 0;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .search-box {
            max-width: 400px;
            margin-left: auto;
        }
    </style>
    <?php
    endif;
    
    // Form styles
    if (in_array($type, ['all', 'form'])):
    ?>
    <style>
        .form-label.required::after {
            content: " *";
            color: #dc3545;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #dc3545;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .btn-save {
            min-width: 100px;
        }
    </style>
    <?php
    endif;
    
    // Modal styles
    if (in_array($type, ['all', 'modal'])):
    ?>
    <style>
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        .icon-wrapper {
            margin-bottom: 1rem;
        }
    </style>
    <?php
    endif;
    
    return ob_get_clean();
}

function nganh_form_style() {
    return nganh_section_css('form');
}

function nganh_select2_assets() {
    ?>
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
    <script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
    <?php
}

function nganh_form_script() {
    ?>
    <script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $('#form-nganh').validate({
                rules: {
                    ten_nganh: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    ma_nganh: {
                        required: true,
                        maxlength: 20
                    }
                },
                messages: {
                    ten_nganh: {
                        required: "Vui lòng nhập tên ngành",
                        minlength: "Tên ngành phải có ít nhất {0} ký tự",
                        maxlength: "Tên ngành không được vượt quá {0} ký tự"
                    },
                    ma_nganh: {
                        required: "Vui lòng nhập mã ngành",
                        maxlength: "Mã ngành không được vượt quá {0} ký tự"
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.after(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
            
            // Auto-generate code from name
            let typingTimer;
            $('#ten_nganh').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    const ten = $('#ten_nganh').val();
                    if (ten && !$('#ma_nganh').val()) {
                        const ma = generateCode(ten);
                        $('#ma_nganh').val(ma);
                    }
                }, 500);
            });

            // Helper function to generate code from name
            function generateCode(str) {
                str = str.toLowerCase();
                str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                str = str.replace(/đ/g, "d");
                str = str.replace(/\W+/g, " ");
                str = str.trim();
                return str.split(' ').map(word => word.charAt(0)).join('').toUpperCase();
            }
        });
    </script>
    <?php
}

function nganh_error($field) {
    if (session('errors') && array_key_exists($field, session('errors'))) {
        return session('errors')[$field];
    }
    
    return '';
}

function nganh_has_error($field) {
    if (session('errors') && array_key_exists($field, session('errors'))) {
        return true;
    }
    
    return false;
}

function nganh_invalid_class($field) {
    return nganh_has_error($field) ? 'is-invalid' : '';
}

function nganh_old($field, $default = '') {
    return old($field, $default);
}

/**
 * Trả về JS theo mục cho module nganh
 * 
 * @param string $type Loại JS (table, form, all)
 * @return string JavaScript code
 */
function nganh_section_js($type = 'all') {
    ob_start();
    
    // DataTable scripts
    if (in_array($type, ['all', 'table'])):
    ?>
    <!-- DataTables JS -->
    <script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Hiển thị thông báo thành công/lỗi với SweetAlert2
            <?php if (session()->getFlashdata('success')): ?>
                Swal.fire({
                    title: 'Thành công',
                    text: '<?= session()->getFlashdata('success') ?>',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                Swal.fire({
                    title: 'Lỗi',
                    text: '<?= session()->getFlashdata('error') ?>',
                    icon: 'error'
                });
            <?php endif; ?>
        });
    </script>
    <?php
    endif;

    // Form specific scripts
    if (in_array($type, ['all', 'form'])):
    ?>
    <!-- Form JS -->
    <script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Form validation
            $('#form-nganh').validate({
                rules: {
                    ten_nganh: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    ma_nganh: {
                        required: true,
                        maxlength: 20
                    }
                },
                messages: {
                    ten_nganh: {
                        required: "Vui lòng nhập tên ngành",
                        minlength: "Tên ngành phải có ít nhất {0} ký tự",
                        maxlength: "Tên ngành không được vượt quá {0} ký tự"
                    },
                    ma_nganh: {
                        required: "Vui lòng nhập mã ngành",
                        maxlength: "Mã ngành không được vượt quá {0} ký tự"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            // Auto-generate code from name
            let typingTimer;
            $('#ten_nganh').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    const ten = $('#ten_nganh').val();
                    if (ten && !$('#ma_nganh').val()) {
                        const ma = generateCode(ten);
                        $('#ma_nganh').val(ma);
                    }
                }, 500);
            });

            // Helper function to generate code from name
            function generateCode(str) {
                str = str.toLowerCase();
                str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
                str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
                str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
                str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
                str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
                str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
                str = str.replace(/đ/g, "d");
                str = str.replace(/\W+/g, " ");
                str = str.trim();
                return str.split(' ').map(word => word.charAt(0)).join('').toUpperCase();
            }
        });
    </script>
    <?php
    endif;
    
    return ob_get_clean();
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