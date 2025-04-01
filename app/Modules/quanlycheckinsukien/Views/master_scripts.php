<?php
/**
 * Master script file for BacHoc module
 * Contains common CSS and JS for all views
 */
// Lấy biến module_name từ biến được truyền vào từ view
$GLOBALS['module_name'] = 'quanlycamera';
if (isset($module_name_php)) {
    $GLOBALS['module_name'] = $module_name_php;
}

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
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        /* Flatpickr custom styles */
        .flatpickr-input {
            background-color: #fff !important;
        }
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, 
        .flatpickr-day.startRange.inRange, 
        .flatpickr-day.endRange.inRange, 
        .flatpickr-day.selected:focus, 
        .flatpickr-day.startRange:focus, 
        .flatpickr-day.endRange:focus, 
        .flatpickr-day.selected:hover, 
        .flatpickr-day.startRange:hover, 
        .flatpickr-day.endRange:hover, 
        .flatpickr-day.selected.prevMonthDay, 
        .flatpickr-day.startRange.prevMonthDay, 
        .flatpickr-day.endRange.prevMonthDay, 
        .flatpickr-day.selected.nextMonthDay, 
        .flatpickr-day.startRange.nextMonthDay, 
        .flatpickr-day.endRange.nextMonthDay {
            background: #435ebe;
            border-color: #435ebe;
        }
    </style>
    <?php
    endif;
    
    return ob_get_clean();
}

// JS section
function page_js($type = 'all', $module_name) {
    
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
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Initialize Flatpickr datepickers
            if (typeof flatpickr !== 'undefined') {
                const datepickerConfig = {
                    dateFormat: "Y-m-d",
                    locale: "vn",
                    allowInput: true,
                    altInput: true,
                    altFormat: "d/m/Y",
                    disableMobile: true
                };
                
                const datepickers = document.querySelectorAll(".datepicker");
                if (datepickers.length > 0) {
                    datepickers.forEach(function(elem) {
                        flatpickr(elem, datepickerConfig);
                    });
                }
            }

            // Form validation
            $('#form-<?= $module_name ?>').validate({
                rules: {
                    nguoi_dung_id: {
                        required: true,
                        number: true
                    },
                    su_kien_id: {
                        required: true,
                        number: true
                    },
                    phuong_thuc_diem_danh: {
                        required: true
                    }
                },
                messages: {
                    nguoi_dung_id: {
                        required: "Vui lòng nhập ID người dùng",
                        number: "ID người dùng phải là số"
                    },
                    su_kien_id: {
                        required: "Vui lòng nhập ID sự kiện",
                        number: "ID sự kiện phải là số"
                    },
                    phuong_thuc_diem_danh: {
                        required: "Vui lòng chọn phương thức điểm danh"
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
function page_section_js($section, $module_name) {
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

// Thêm hàm đồng bộ JavaScript cho bảng ThamGiaSuKien
function page_table_js($module_name) {
    // Lấy module_name từ biến JS hoặc mặc định là $module_name
    $module_name = isset($GLOBALS['module_name']) ? $GLOBALS['module_name'] : (isset($module_name) ? $module_name : 'admin/bachoc');
    
    ob_start();
    ?>
    <script>
    $(document).ready(function() {
        // Kiểm tra xem bảng đã được khởi tạo thành DataTable chưa
        if (!$.fn.DataTable.isDataTable('#dataTable')) {
            // Khởi tạo tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltips].map(t => new bootstrap.Tooltip(t));
            
            // Khởi tạo DataTable với cấu hình tiếng Việt
            const dataTable = $('#dataTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json',
                },
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                dom: '<"row mx-0"<"col-sm-12 px-0"tr>><"row mx-0 mt-2"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"p>>',
                ordering: true,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [0, 5] },
                    { className: 'align-middle', targets: '_all' }
                ],
                searching: false, // Tắt tìm kiếm của DataTable vì đã có form tìm kiếm
                paging: false, // Tắt phân trang của DataTable vì đã có phân trang CodeIgniter
                info: false // Tắt thông tin của DataTable
            });
            
            // Xử lý form tìm kiếm
            $('#search-form').on('submit', function() {
                // Form sẽ gửi yêu cầu GET nên không cần xử lý gì thêm
                return true;
            });
            
            // Xử lý nhấn Enter trong ô tìm kiếm
            $('#table-search').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    $('#search-form').submit();
                }
            });
        }
        
        // Làm mới bảng
        $('#refresh-table').on('click', function() {
            $('#loading-indicator').css('display', 'flex').fadeIn(100);
            location.reload();
        });
        
        // Kiểm tra xem đang ở trang listdeleted hay không
        const isListDeletedPage = window.location.href.indexOf('listdeleted') > -1;
        
        // Xử lý nút xóa (xóa thường hoặc xóa vĩnh viễn tùy thuộc vào trang)
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#delete-item-name').text(name);
            
            // Lấy đường dẫn tương đối (path + query string)
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Tạo URL xóa với tham số truy vấn return_url
            let deleteUrl;
            
            // Kiểm tra xem đang ở trang listdeleted hay không
            if (isListDeletedPage) {
                deleteUrl = '<?= site_url($module_name . '/permanentDelete/') ?>' + id + '?return_url=' + encodeURIComponent(pathAndQuery);
            } else {
                deleteUrl = '<?= site_url($module_name . '/delete/') ?>' + id + '?return_url=' + encodeURIComponent(pathAndQuery);
            }
            
            $('#delete-form').attr('action', deleteUrl);
            $('#deleteModal').modal('show');
        });
        
        // Xử lý nút khôi phục
        $('.btn-restore').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#restore-item-id').text(id);
            $('#restore-item-name').text(name);
            
            // Lấy đường dẫn tương đối (path + query string)
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Tạo URL khôi phục
            const restoreUrl = '<?= site_url($module_name . '/restore/') ?>' + id;
            
            // Cập nhật action của form và thêm input return_url
            const $form = $('#restore-form');
            $form.attr('action', restoreUrl);
            
            // Debug log
            console.log('Restore URL:', restoreUrl);
            console.log('Return URL:', pathAndQuery);
            console.log('Item ID:', id);
            console.log('Item name:', name);
            
            // Hiển thị modal
            $('#restoreModal').modal('show');
        });
        
        // Xử lý nút xóa vĩnh viễn
        $('.btn-permanent-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            // Lấy đường dẫn tương đối (path + query string)
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Tạo URL xóa với tham số truy vấn return_url
            const deleteUrl = '<?= site_url($module_name . '/permanentDelete/') ?>' + id + '?return_url=' + encodeURIComponent(pathAndQuery);
            $('.force-delete-form').attr('action', deleteUrl);
            
            $('#forceDeleteModal').modal('show');
        });
        
        // Xử lý nút xóa nhiều
        $('#delete-selected-multiple').on('click', function() {
            const selectedCount = $('.checkbox-item:checked').length;
            
            if (selectedCount === 0) {
                Swal.fire({
                    title: 'Cảnh báo',
                    text: 'Vui lòng chọn ít nhất một mục để xóa',
                    icon: 'warning'
                });
                return;
            }
            
            // Hiển thị số lượng mục đã chọn trong modal
            $('#selected-count').text(selectedCount);
            // Hiển thị modal xác nhận xóa nhiều
            $('#deleteMultipleModal').modal('show');
        });
        
        // Xử lý nút đổi trạng thái nhiều (hỗ trợ cả ID status-selected và status-selected-multiple)
        $('#status-selected, #status-selected-multiple').on('click', function() {
            const selectedCount = $('.checkbox-item:checked').length;
            
            if (selectedCount === 0) {
                Swal.fire({
                    title: 'Cảnh báo',
                    text: 'Vui lòng chọn ít nhất một mục để thay đổi trạng thái',
                    icon: 'warning'
                });
                return;
            }
            
            // Hiển thị số lượng mục đã chọn trong modal statusMultipleModal
            $('#status-count').text(selectedCount);
            $('#statusMultipleModal').modal('show');
        });
        
        // Xử lý xác nhận đổi trạng thái
        $('#confirm-status-multiple').on('click', function() {
            // Tạo form tạm thời chứa các checkbox đã chọn
            const tempForm = $('#form-status-multiple');
            
            // Xóa các input cũ
            tempForm.empty();
            
            // Lấy đường dẫn tương đối (path + query string)
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Thêm URL hiện tại làm return_url
            tempForm.append($('<input>').attr({
                type: 'hidden',
                name: 'return_url',
                value: pathAndQuery
            }));

            // Thêm CSRF token
            tempForm.append($('<input>').attr({
                type: 'hidden',
                name: '<?= csrf_token() ?>',
                value: '<?= csrf_hash() ?>'
            }));
            
            // Thêm các checkbox đã chọn vào form
            const selectedIds = [];
            $('.checkbox-item:checked').each(function() {
                const id = $(this).val();
                selectedIds.push(id);
                const input = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_ids[]',
                    value: id
                });
                tempForm.append(input);
            });
            
            // Debug - hiển thị thông tin trực tiếp
            console.log('Form action:', tempForm.attr('action'));
            console.log('Form method:', tempForm.attr('method'));
            console.log('Changing status for multiple items with return URL:', pathAndQuery);
            console.log('Selected IDs:', selectedIds);
            console.log('Form data:', {
                return_url: pathAndQuery,
                selected_ids: selectedIds,
                csrf_token: '<?= csrf_hash() ?>'
            });
            
            tempForm.submit();
            
            // Đóng modal
            $('#statusMultipleModal').modal('hide');
        });
        
        // Xử lý xác nhận xóa nhiều
        $('#confirm-delete-multiple').on('click', function() {
            // Lấy đường dẫn hiện tại cho return_url
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Xóa các input cũ
            $('#form-delete-multiple').empty();
            
            // Thêm CSRF token - đảm bảo tên chính xác
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            
            // Thêm CSRF token
            $('<input>').attr({
                type: 'hidden',
                name: csrfName,
                value: csrfHash
            }).appendTo('#form-delete-multiple');
            
            // Thêm input return_url
            $('<input>').attr({
                type: 'hidden',
                name: 'return_url',
                value: pathAndQuery
            }).appendTo('#form-delete-multiple');
            
            // Thêm các ID đã chọn vào form
            $('.checkbox-item:checked').each(function() {
                const input = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_ids[]',
                    value: $(this).val()
                });
                $('#form-delete-multiple').append(input);
            });
            
            // Log dữ liệu form trước khi submit để debug
            console.log('Form delete data before submit:', $('#form-delete-multiple').serialize());
            
            // Submit form
            $('#form-delete-multiple').submit();
            
            // Đóng modal
            $('#deleteMultipleModal').modal('hide');
        });
        
        // Xử lý nút khôi phục nhiều
        $('#restore-selected').on('click', function() {
            const selectedCount = $('.checkbox-item:checked').length;
            
            if (selectedCount === 0) {
                Swal.fire({
                    title: 'Cảnh báo',
                    text: 'Vui lòng chọn ít nhất một mục để khôi phục',
                    icon: 'warning'
                });
                return;
            }
            
            // Hiển thị số lượng mục đã chọn trong modal
            $('#restore-multiple-count').html(`Bạn đã chọn <strong>${selectedCount}</strong> check-in sự kiện để khôi phục.`);
            $('#restoreMultipleModal').modal('show');
        });
        
        // Xử lý xác nhận khôi phục nhiều
        $('#confirm-restore-multiple').on('click', function() {
            // Lấy đường dẫn hiện tại cho return_url
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Xóa các input cũ
            const $form = $('#form-restore-multiple');
            $form.empty();
            
            // Thêm CSRF token
            $('<input>').attr({
                type: 'hidden',
                name: '<?= csrf_token() ?>',
                value: '<?= csrf_hash() ?>'
            }).appendTo($form);
            
            // Thêm input return_url
            $('<input>').attr({
                type: 'hidden',
                name: 'return_url',
                value: pathAndQuery
            }).appendTo($form);
            
            // Thêm các ID đã chọn vào form
            $('.checkbox-item:checked').each(function() {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_ids[]',
                    value: $(this).val()
                }).appendTo($form);
            });
            
            // Log dữ liệu form trước khi submit để debug
            console.log('Form restore data:', $form.serialize());
            
            // Submit form
            $form.submit();
            
            // Đóng modal
            $('#restoreMultipleModal').modal('hide');
        });
        
        // Xử lý chọn tất cả hoặc hủy chọn
        $('#select-all').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.checkbox-item').prop('checked', isChecked);
            updateMultipleButtonsState();
        });
        
        // Cập nhật trạng thái nút xóa, khôi phục nhiều mục
        function updateMultipleButtonsState() {
            const selectedCount = $('.checkbox-item:checked').length;
            if (selectedCount > 0) {
                $('#delete-selected-multiple').prop('disabled', false);
                $('#restore-selected').prop('disabled', false);
                $('#delete-permanent-multiple').prop('disabled', false);
                $('#status-selected-multiple').prop('disabled', false);
                $('#status-selected').prop('disabled', false);
            } else {
                $('#delete-selected-multiple').prop('disabled', true);
                $('#restore-selected').prop('disabled', true);
                $('#delete-permanent-multiple').prop('disabled', true);
                $('#status-selected-multiple').prop('disabled', true);
                $('#status-selected').prop('disabled', true);
            }
        }
        
        // Cập nhật trạng thái nút khi chọn checkbox
        $('.checkbox-item').on('change', function() {
            updateMultipleButtonsState();
            
            // Kiểm tra tất cả checkbox đã được chọn chưa
            const totalCheckboxes = $('.checkbox-item').length;
            const checkedCheckboxes = $('.checkbox-item:checked').length;
            
            // Cập nhật trạng thái checkbox select-all
            $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
        });
        
        // Ban đầu cập nhật trạng thái nút
        updateMultipleButtonsState();
        
        // Xử lý thay đổi số bản ghi mỗi trang
        $('#perPageSelect').on('change', function() {
            const selectedValue = $(this).val();
            const currentUrl = new URL(window.location.href);
            
            // Cập nhật tham số perPage
            currentUrl.searchParams.set('perPage', selectedValue);
            
            // Reset về trang 1
            currentUrl.searchParams.set('page', 1);
            
            // Chuyển hướng đến URL mới
            window.location.href = currentUrl.toString();
        });

        // Xử lý nút xóa vĩnh viễn nhiều mục
        $('#delete-permanent-multiple').on('click', function() {
            const selectedCount = $('.checkbox-item:checked').length;
            
            if (selectedCount === 0) {
                Swal.fire({
                    title: 'Cảnh báo',
                    text: 'Vui lòng chọn ít nhất một mục để xóa vĩnh viễn',
                    icon: 'warning'
                });
                return;
            }
            
            // Hiển thị số lượng mục đã chọn trong modal
            $('#permanent-delete-multiple-count').html(`Bạn đã chọn <strong>${selectedCount}</strong> camera để xóa vĩnh viễn.`);
            $('#permanentDeleteMultipleModal').modal('show');
        });
        
        // Xử lý xác nhận xóa vĩnh viễn nhiều mục
        $('#confirm-permanent-delete-multiple').on('click', function() {
            // Lấy đường dẫn hiện tại cho return_url
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Xóa các input cũ
            $('#form-permanent-delete-multiple').empty();
            
            // Thêm CSRF token
            $('<input>').attr({
                type: 'hidden',
                name: '<?= csrf_token() ?>',
                value: '<?= csrf_hash() ?>'
            }).appendTo('#form-permanent-delete-multiple');
            
            // Thêm input return_url
            $('<input>').attr({
                type: 'hidden',
                name: 'return_url',
                value: pathAndQuery
            }).appendTo('#form-permanent-delete-multiple');
            
            // Thêm các ID đã chọn vào form
            $('.checkbox-item:checked').each(function() {
                const input = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_ids[]',
                    value: $(this).val()
                });
                $('#form-permanent-delete-multiple').append(input);
            });
            
            // Log dữ liệu form trước khi submit để debug
            console.log('Form permanent delete data before submit:', $('#form-permanent-delete-multiple').serialize());
            
            // Submit form
            $('#form-permanent-delete-multiple').submit();
            
            // Đóng modal
            $('#permanentDeleteMultipleModal').modal('hide');
        });
    });
    </script>
    <?php
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

<?php
// Module JavaScript end
?>