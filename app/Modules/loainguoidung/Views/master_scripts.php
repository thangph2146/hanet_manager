<?php
/**
 * Master script file for LoaiNguoiDung module
 * Contains common CSS and JS for all views
 */

// CSS section
function loainguoidung_css($type = 'all') {
    ob_start();
    
    // Common CSS for DataTables
    if (in_array($type, ['all', 'table'])):
    ?>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
    <style>
        .highlight-row {
            background-color: #e6f7ff !important;
            transition: background-color 1s ease;
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
    <?php
    endif;
    
    return ob_get_clean();
}

// JS section
function loainguoidung_js($type = 'all') {
    ob_start();
    
    // DataTable scripts
    if (in_array($type, ['all', 'table'])):
    ?>
    <!-- DataTables JS -->
    <script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.responsive.min.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#dataTable').DataTable({
                responsive: true,
                language: {
                    url: "<?= base_url('assets/plugins/datatable/language/vi.json') ?>"
                },
                order: [[1, 'asc']] // Sắp xếp theo cột thứ 2 (tên)
            });
            
            // Select all checkboxes
            $('#select-all').on('click', function() {
                $('.checkbox-item').prop('checked', this.checked);
            });
            
            // Update "select all" state based on checkbox states
            $('.checkbox-item').on('click', function() {
                if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });
        });
    </script>
    <?php
    endif;
    
    // Form validation scripts
    if (in_array($type, ['all', 'form'])):
    ?>
    <!-- Form Validation JS -->
    <script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            
            // Validate form
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    var forms = document.getElementsByClassName('needs-validation');
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        });
    </script>
    <?php
    endif;
    
    return ob_get_clean();
}