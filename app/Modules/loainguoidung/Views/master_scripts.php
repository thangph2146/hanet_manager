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
    
    return ob_get_clean();
}

// JS section
function loainguoidung_js($type = 'all') {
    ob_start();
    
    // Form validation script
    if (in_array($type, ['all', 'form'])):
    ?>
    <script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
    <?php
    endif;
    
    // DataTable initialization script
    if (in_array($type, ['all', 'table'])):
    ?>
    <!-- DataTables JS -->
    <script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // DataTable
            $('#dataTable').DataTable({
                language: {
                    url: '<?= base_url('assets/plugins/datatable/locale/vi.json') ?>'
                }
            });
            
            // Xử lý checkbox select all
            $('#select-all').on('click', function() {
                $('.checkbox-item').prop('checked', $(this).prop('checked'));
            });
        });
    </script>
    <?php
    endif;
    
    return ob_get_clean();
} 