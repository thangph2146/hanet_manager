<?php
/**
 * Master script file for HeDaoTao module
 * Contains common CSS and JS for all views
 */

// CSS section
function hedaotao_css($type = 'all') {
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
function hedaotao_js($type = 'all') {
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
            $('#form-he-dao-tao').validate({
                rules: {
                    ten_he_dao_tao: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    ma_he_dao_tao: {
                        maxlength: 20
                    }
                },
                messages: {
                    ten_he_dao_tao: {
                        required: "Vui lòng nhập tên hệ đào tạo",
                        minlength: "Tên hệ đào tạo phải có ít nhất {0} ký tự",
                        maxlength: "Tên hệ đào tạo không được vượt quá {0} ký tự"
                    },
                    ma_he_dao_tao: {
                        maxlength: "Mã hệ đào tạo không được vượt quá {0} ký tự"
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
            $('#ten_he_dao_tao').on('input', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    const ten = $('#ten_he_dao_tao').val();
                    if (ten && !$('#ma_he_dao_tao').val()) {
                        const ma = generateCode(ten);
                        $('#ma_he_dao_tao').val(ma);
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