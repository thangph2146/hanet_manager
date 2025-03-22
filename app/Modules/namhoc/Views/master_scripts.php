<?php
/**
 * Master script file for NamHoc module
 * Contains common CSS and JS for all views
 */

// CSS section
function namhoc_css($type = 'all') {
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
function namhoc_js($type = 'all') {
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
                    url: '<?= base_url('assets/plugins/datatable/locale/vi.json') ?>'
                }
            });
            
            // Select-all functionality
            $(document).on("click", "#select-all", function() {
                $(".checkbox-item").prop("checked", $(this).prop("checked"));
                checkSelected();
            });
            
            // Individual checkbox
            $(document).on("change", ".checkbox-item", function() {
                checkSelected();
            });
            
            // Function to enable/disable action buttons
            function checkSelected() {
                var totalCheckboxes = $(".checkbox-item").length;
                var checkedCheckboxes = $(".checkbox-item:checked").length;
                
                if (checkedCheckboxes > 0) {
                    $("#delete-selected, #status-selected, #restore-selected, #permanent-delete-selected").prop("disabled", false);
                } else {
                    $("#delete-selected, #status-selected, #restore-selected, #permanent-delete-selected").prop("disabled", true);
                }
                
                // Update select-all checkbox
                if (checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0) {
                    $("#select-all").prop("checked", true);
                } else {
                    $("#select-all").prop("checked", false);
                }
            }
            
            // Initialize buttons state
            checkSelected();
        });
    </script>
    <?php
    endif;
    
    return ob_get_clean();
} 