<?php
/**
 * Master script file for BacHoc module
 * Contains common CSS and JS for all views
 */

// CSS section
function bachoc_css($type = 'all') {
    ob_start();
    
    // Form CSS
    if (in_array($type, ['all', 'form'])):
    ?>
    <!-- Form CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">
    <?php
    endif;

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
function bachoc_js($type = 'all') {
    ob_start();
    
    // Form validation script
    if (in_array($type, ['all', 'form'])):
    ?>
    <!-- Form JS -->
    <script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            // Form validation
            (function() {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        });
    </script>
    <?php
    endif;
    
    // DataTable initialization script
    if (in_array($type, ['all', 'table'])):
    ?>
    <!-- DataTables JS -->
    <script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.responsive.min.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Initialize DataTable
            var dataTable = $("#dataTable").DataTable({
                responsive: true,
                language: {
                    url: '<?= base_url("assets/plugins/datatable/language/vi.json") ?>'
                }
            });

            // Handle select all checkbox
            $('#select-all').on('click', function() {
                $('.checkbox-item').prop('checked', $(this).prop('checked'));
                toggleBulkActionButtons();
            });

            $('.checkbox-item').on('click', function() {
                toggleBulkActionButtons();
            });

            function toggleBulkActionButtons() {
                var checkedCount = $('.checkbox-item:checked').length;
                if (checkedCount > 0) {
                    $('#delete-selected, #status-selected, #restore-selected, #permanent-delete-selected').removeClass('d-none');
                } else {
                    $('#delete-selected, #status-selected, #restore-selected, #permanent-delete-selected').addClass('d-none');
                }
            }
        });
    </script>
    <?php
    endif;
    
    return ob_get_clean();
}
?>