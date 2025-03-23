<?php
/**
 * Master script file for Facenguoidung module
 * Contains common CSS and JS for all views
 */

// CSS section
function facenguoidung_css($type = 'all') {
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
function facenguoidung_js($type = 'all') {
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
            $('#form-facenguoidung').validate({
                rules: {
                    nguoi_dung_id: {
                        required: true
                    },
                    duong_dan_anh: {
                        required: true
                    }
                },
                messages: {
                    nguoi_dung_id: {
                        required: "Vui lòng chọn người dùng"
                    },
                    duong_dan_anh: {
                        required: "Vui lòng tải lên ảnh khuôn mặt"
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
        });
    </script>
    <?php
    endif;
    
    return ob_get_clean();
}

// Section CSS function
function facenguoidung_section_css($section) {
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
function facenguoidung_section_js($section) {
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
<link rel="stylesheet" href="<?= base_url('css/modules/nganh/style.css') ?>" />

<script>
// Hàm xem trước ảnh trước khi upload
function setupImagePreview() {
    const imageInput = document.getElementById('duong_dan_anh');
    const previewContainer = document.getElementById('image-preview-container');
    
    if (!imageInput || !previewContainer) return;
    
    imageInput.addEventListener('change', function() {
        // Xóa preview cũ nếu có
        while (previewContainer.firstChild) {
            previewContainer.removeChild(previewContainer.firstChild);
        }
        
        const file = this.files[0];
        if (!file) return;
        
        // Kiểm tra loại file
        if (!file.type.match('image.*')) {
            const errorMsg = document.createElement('p');
            errorMsg.textContent = 'Vui lòng chọn file hình ảnh';
            errorMsg.className = 'text-danger mt-2';
            previewContainer.appendChild(errorMsg);
            return;
        }
        
        // Tạo preview
        const img = document.createElement('img');
        img.className = 'img-thumbnail mt-2';
        img.style.maxWidth = '200px';
        img.style.maxHeight = '200px';
        
        // Tạo reader để đọc file
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Tạo container cho preview
        const previewBox = document.createElement('div');
        previewBox.className = 'mt-2';
        
        // Tạo caption
        const caption = document.createElement('p');
        caption.className = 'small text-muted';
        caption.textContent = 'Kích thước: ' + formatFileSize(file.size);
        
        // Thêm vào container
        previewBox.appendChild(img);
        previewBox.appendChild(caption);
        previewContainer.appendChild(previewBox);
    });
}

// Hàm định dạng kích thước file
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Khởi tạo xem trước ảnh
document.addEventListener('DOMContentLoaded', function() {
    setupImagePreview();
});
</script>