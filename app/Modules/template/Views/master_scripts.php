<?php
/**
 * Master script file for Template module
 * Contains common CSS and JS for all views
 */

// CSS section
function page_css($type = 'all') {
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
        table.table-bordered.dataTable th:first-child:before, table.dataTable > thead .sorting_asc::before, table.dataTable > thead .sorting_desc::before, table.dataTable > thead .sorting_asc_disabled::before, table.dataTable > thead .sorting_desc_disabled::before {
            content: '' !important;
        }
        table.table-bordered.dataTable th:first-child:after, table.dataTable > thead .sorting_asc::after, table.dataTable > thead .sorting_desc::after, table.dataTable > thead .sorting_asc_disabled::after, table.dataTable > thead .sorting_desc_disabled::after {
            content: '' !important;
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
        .btn i {
            margin-right: 0;
        }
        
        /* Cải thiện hiển thị phân trang */
        .pagination {
            gap: 3px;
            margin-bottom: 0;
        }
        
        .pagination .page-item .page-link {
            color: #435ebe;
            padding: 0.375rem 0.75rem;
            border-color: #dee2e6;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            transition: all 0.3s ease;
            border-radius: 0.25rem;
            margin: 0 1px;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #435ebe;
            border-color: #435ebe;
            color: #fff;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(67, 94, 190, 0.3);
            z-index: 3;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
            pointer-events: none;
        }
        
        .pagination .page-item .page-link:hover:not(.disabled) {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #435ebe;
            z-index: 2;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }
        
        .pagination .page-item .page-link:focus {
            box-shadow: 0 0 0 0.15rem rgba(67, 94, 190, 0.25);
            z-index: 3;
        }
        
        .pagination-container {
            margin-bottom: 1rem;
        }
        
        /* Nút select số bản ghi */
        #perPage {
            min-width: 70px;
            cursor: pointer;
            border-color: #ced4da;
            background-color: #fff;
            transition: all 0.2s;
        }
        
        #perPage:hover, #perPage:focus {
            border-color: #435ebe;
        }
        
        /* Thêm hiệu ứng cho nút phân trang */
        .pagination .page-link {
            border-radius: 0.25rem;
            margin: 0 2px;
        }
        
        /* Hiệu ứng shadow khi hover */
        .pagination .page-item:not(.disabled) .page-link:hover {
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }
        
        /* Cải thiện text align cho active page */
        .pagination .page-item.active .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
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
function page_js($type = 'all') {
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
            // Xử lý thay đổi số lượng bản ghi trên mỗi trang
            function changePerPage(perPage) {
                // Lấy URL hiện tại
                let url = new URL(window.location.href);
                let params = new URLSearchParams(url.search);
                
                // Cập nhật tham số perPage
                params.set('perPage', perPage);
                
                // Quay về trang 1 khi thay đổi số lượng bản ghi
                params.set('page', '1');
                
                // Cập nhật URL và chuyển hướng
                url.search = params.toString();
                window.location.href = url.toString();
            }
            
            // Xử lý sự kiện change cho select perPage
            $('#perPage').on('change', function() {
                changePerPage($(this).val());
            });
            
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
            $('#form-template').validate({
                rules: {
                    ten_template: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    ma_template: {
                        maxlength: 20
                    }
                },
                messages: {
                    ten_template: {
                        required: "Vui lòng nhập tên template",
                        minlength: "Tên template phải có ít nhất {0} ký tự",
                        maxlength: "Tên template không được vượt quá {0} ký tự"
                    },
                    ma_template: {
                        maxlength: "Mã template không được vượt quá {0} ký tự"
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
            $('#ten_template').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    const ten = $('#ten_template').val();
                    if (ten && !$('#ma_template').val()) {
                        const ma = generateCode(ten);
                        $('#ma_template').val(ma);
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

// Section CSS function
function page_section_css($section) {
    ob_start();

    // Modal CSS
    if ($section === 'modal'):
    ?>
    <style>
        .modal .icon-wrapper i {
            font-size: 3rem;
            display: inline-block;
        }
        .modal-content {
            border-radius: 0.5rem;
        }
        .modal-header {
            border-bottom: 1px solid #eee;
        }
        .modal-footer {
            border-top: 1px solid #eee;
        }
    </style>
    <?php
    endif;

    return ob_get_clean();
}

// Section JS function
function page_section_js($section) {
    ob_start();

    // Table specific additional JS
    if ($section === 'table'):
    ?>
    <script>
        $(document).ready(function() {
            // Highlight row on hover
            $(document).on('mouseenter', 'table tbody tr', function() {
                $(this).addClass('highlight-row');
            }).on('mouseleave', 'table tbody tr', function() {
                $(this).removeClass('highlight-row');
            });
            
            // Thêm hiệu ứng cho các nút phân trang
            $('.pagination .page-link').hover(function() {
                $(this).parent().addClass('hover-effect');
            }, function() {
                $(this).parent().removeClass('hover-effect');
            });
        });
    </script>
    <?php
    endif;

    return ob_get_clean();
}

// Plugin CSS
?>

<!-- Plugin CSS -->
<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/select2/select2.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/vendor/libs/sweetalert2/sweetalert2.css') ?>" />

<!-- Module CSS -->
<link rel="stylesheet" href="<?= base_url('css/modules/template/style.css') ?>" />