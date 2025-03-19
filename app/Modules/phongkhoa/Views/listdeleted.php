<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH PHÒNG KHOA ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Danh sách Phòng Khoa đã xóa',
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Phòng Khoa', 'url' => site_url('/phongkhoa')],
        ['title' => 'Danh sách Phòng Khoa đã xóa', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/phongkhoa/new'), 'title' => 'Tạo Phòng Khoa'],
		['url' => site_url('/phongkhoa'), 'title' => 'Danh sách Phòng Khoa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
    <?= form_open("phongkhoa/restoreMultiple", ['class' => 'row g3', 'id' => 'restore-form']) ?>
    <div class="col-12 mb-3">
        <button type="submit" class="btn btn-success">Khôi phục phòng khoa đã chọn</button>
        <button type="button" id="force-delete-btn" class="btn btn-danger">Xóa vĩnh viễn</button>
    </div>
    <input type="hidden" name="selected_ids" id="selected_ids" value="">
    <?= view('components/_table', [
        'caption' => 'Danh Sách Phòng Khoa đã xóa',
        'headers' => [
            '<input type="checkbox" id="select-all" />', 
            'ID', 
            'Mã phòng khoa',
            'Tên phòng khoa', 
            'Ghi chú',
            'Ngày xóa',
            'Thao tác'
        ],    
        'data' => $phong_khoas,
        'columns' => [
            [
                'type' => 'checkbox',
                'id_field' => 'phong_khoa_id',
                'name' => 'phong_khoa_id[]'
            ],
            [
                'field' => 'phong_khoa_id'
            ],
            [
                'field' => 'ma_phong_khoa'
            ],
            [
                'field' => 'ten_phong_khoa'
            ],
            [
                'field' => 'ghi_chu'
            ],
            [
                'field' => 'deleted_at',
                'format' => 'date'
            ],
            [
                'type' => 'actions',
                'buttons' => [
                    [
                        'url_prefix' => site_url('phongkhoa/restore/'),
                        'id_field' => 'phong_khoa_id',
                        'title_field' => 'ten_phong_khoa',
                        'title' => 'Khôi phục %s',
                        'icon' => 'fadeIn animated bx bx-reset',
                    ],
                    [
                        'url_prefix' => site_url('phongkhoa/permanentDelete/'),
                        'id_field' => 'phong_khoa_id',
                        'title_field' => 'ten_phong_khoa',
                        'title' => 'Xóa vĩnh viễn %s',
                        'icon' => 'lni lni-trash',
                    ]
                ]
            ]
        ],
        'options' => [
            'table_id' => setting('App.table_id')
        ]
    ]) 
    ?>
    </form>
</div>
<?= $this->endSection() ?> 

<?= $this->section('script') ?>
<!-- DataTables JS -->
<script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/responsive.bootstrap5.min.js') ?>"></script>

<script>
$(document).ready(function() {
    // Khởi tạo DataTable
    var dataTable = $('#<?= setting('App.table_id') ?>').DataTable();
    
    // Biến lưu trữ CSRF token
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';
    
    // Xử lý sự kiện xóa vĩnh viễn
    $('#force-delete-btn').on('click', function(e) {
        e.preventDefault();
        
        var selectedIds = [];
        
        // Lấy tất cả ID đã chọn
        $('input[name="phong_khoa_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một phòng khoa để xóa vĩnh viễn.');
            return false;
        }
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn những phòng khoa này? Hành động này không thể hoàn tác!')) {
            // Cập nhật hidden input với danh sách ID
            $('#selected_ids').val(selectedIds.join(','));
            
            // Xử lý xóa vĩnh viễn cho từng ID
            var successCount = 0;
            var totalIds = selectedIds.length;
            var processedCount = 0;
            
            selectedIds.forEach(function(id) {
                var data = {};
                data[csrfName] = csrfHash;
                
                $.ajax({
                    url: '<?= site_url('phongkhoa/permanentDelete') ?>/' + id,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        processedCount++;
                        
                        // Cập nhật CSRF hash nếu có
                        if (response.csrf_hash) {
                            csrfHash = response.csrf_hash;
                        }
                        
                        if (response.success) {
                            successCount++;
                            
                            // Xóa dòng khỏi DataTable
                            var row = $('input[name="phong_khoa_id[]"][value="' + id + '"]').closest('tr');
                            dataTable.row(row).remove();
                            
                            // Kiểm tra nếu đã xử lý tất cả các ID
                            if (processedCount === totalIds) {
                                // Vẽ lại bảng
                                dataTable.draw(false);
                                
                                // Quay lại trang hiện tại nếu cần
                                if (dataTable.page() !== currentPage && dataTable.page.info().pages > currentPage) {
                                    dataTable.page(currentPage).draw(false);
                                }
                                
                                // Bỏ chọn checkbox "chọn tất cả"
                                $('#select-all').prop('checked', false);
                                
                                // Hiển thị thông báo thành công
                                showNotification('success', 'Đã xóa vĩnh viễn ' + successCount + ' phòng khoa thành công.');
                            }
                        } else {
                            // Kiểm tra nếu đã xử lý tất cả các ID
                            if (processedCount === totalIds) {
                                // Vẽ lại bảng
                                dataTable.draw(false);
                                
                                // Hiển thị thông báo với số lượng thành công
                                if (successCount > 0) {
                                    showNotification('info', 'Đã xóa vĩnh viễn ' + successCount + ' phòng khoa thành công và ' + (totalIds - successCount) + ' thất bại.');
                                } else {
                                    showNotification('error', 'Không thể xóa vĩnh viễn phòng khoa. Vui lòng thử lại.');
                                }
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        processedCount++;
                        
                        // Kiểm tra nếu đã xử lý tất cả các ID
                        if (processedCount === totalIds) {
                            // Vẽ lại bảng
                            dataTable.draw(false);
                            
                            // Hiển thị thông báo với số lượng thành công
                            if (successCount > 0) {
                                showNotification('info', 'Đã xóa vĩnh viễn ' + successCount + ' phòng khoa thành công và ' + (totalIds - successCount) + ' thất bại.');
                            } else {
                                showNotification('error', 'Không thể xóa vĩnh viễn phòng khoa. Vui lòng thử lại.');
                            }
                        }
                    }
                });
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện khôi phục phòng khoa bằng Ajax
    $(document).on('click', '.restore-phongkhoa', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var row = $(this).closest('tr');
        var phongKhoaName = $(this).attr('title').replace('Khôi phục ', '');
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn khôi phục phòng khoa "' + phongKhoaName + '"?')) {
            var data = {};
            data[csrfName] = csrfHash;
            
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(response) {
                    // Cập nhật CSRF hash nếu có
                    if (response.csrf_hash) {
                        csrfHash = response.csrf_hash;
                    }
                    
                    if (response.success) {
                        // Xóa dòng khỏi DataTable mà không tải lại trang
                        dataTable.row(row).remove().draw(false);
                        
                        // Quay lại trang hiện tại nếu cần
                        if (dataTable.page() !== currentPage && dataTable.page.info().pages > currentPage) {
                            dataTable.page(currentPage).draw(false);
                        }
                        
                        // Lưu thông tin phòng khoa đã khôi phục vào localStorage để cập nhật trang index
                        var phongKhoaData = {
                            id: row.find('input[name="phong_khoa_id[]"]').val(),
                            phong_khoa_id: row.find('input[name="phong_khoa_id[]"]').val(),
                            ma_phong_khoa: row.find('td:eq(2)').text().trim(),
                            ten_phong_khoa: phongKhoaName,
                            ghi_chu: row.find('td:eq(4)').text().trim()
                        };
                        localStorage.setItem('restored_phongkhoa', JSON.stringify(phongKhoaData));
                        
                        // Hiển thị thông báo thành công
                        showNotification('success', 'Khôi phục phòng khoa thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể khôi phục phòng khoa. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện xóa vĩnh viễn một phòng khoa
    $(document).on('click', '.delete-permanent', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var row = $(this).closest('tr');
        var phongKhoaName = $(this).attr('title').replace('Xóa vĩnh viễn ', '');
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn phòng khoa "' + phongKhoaName + '"? Hành động này không thể hoàn tác!')) {
            var data = {};
            data[csrfName] = csrfHash;
            
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(response) {
                    // Cập nhật CSRF hash nếu có
                    if (response.csrf_hash) {
                        csrfHash = response.csrf_hash;
                    }
                    
                    if (response.success) {
                        // Xóa dòng khỏi DataTable mà không tải lại trang
                        dataTable.row(row).remove().draw(false);
                        
                        // Hiển thị thông báo thành công
                        showNotification('success', 'Xóa vĩnh viễn phòng khoa thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể xóa vĩnh viễn phòng khoa. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện khôi phục nhiều phòng khoa
    $('#restore-form').on('submit', function(e) {
        e.preventDefault();
        
        var selectedIds = [];
        
        // Lấy tất cả ID đã chọn
        $('input[name="phong_khoa_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một phòng khoa để khôi phục.');
            return false;
        }
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        if (confirm('Bạn có chắc chắn muốn khôi phục ' + selectedIds.length + ' phòng khoa đã chọn?')) {
            // Cập nhật hidden input với danh sách ID
            $('#selected_ids').val(selectedIds.join(','));
            
            var data = {
                selected_ids: selectedIds.join(',')
            };
            data[csrfName] = csrfHash;
            
            $.ajax({
                url: '<?= site_url('phongkhoa/restoreMultiple') ?>',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function(response) {
                    // Cập nhật CSRF hash nếu có
                    if (response.csrf_hash) {
                        csrfHash = response.csrf_hash;
                    }
                    
                    if (response.success) {
                        // Xóa các dòng đã chọn khỏi DataTable
                        $('input[name="phong_khoa_id[]"]:checked').each(function() {
                            var row = $(this).closest('tr');
                            // Lưu thông tin phòng khoa trước khi xóa dòng
                            var phongKhoaData = {
                                id: $(this).val(),
                                phong_khoa_id: $(this).val(),
                                ma_phong_khoa: row.find('td:eq(2)').text().trim(),
                                ten_phong_khoa: row.find('td:eq(3)').text().trim(),
                                ghi_chu: row.find('td:eq(4)').text().trim()
                            };
                            
                            // Xóa dòng
                            dataTable.row(row).remove();
                            
                            // Lưu thông tin phòng khoa đầu tiên vào localStorage để cập nhật trang index
                            if (selectedIds.indexOf($(this).val()) === 0) {
                                localStorage.setItem('restored_phongkhoa', JSON.stringify(phongKhoaData));
                            }
                        });
                        
                        // Vẽ lại bảng
                        dataTable.draw(false);
                        
                        // Quay lại trang hiện tại nếu cần
                        if (dataTable.page() !== currentPage && dataTable.page.info().pages > currentPage) {
                            dataTable.page(currentPage).draw(false);
                        }
                        
                        // Bỏ chọn checkbox "chọn tất cả"
                        $('#select-all').prop('checked', false);
                        
                        // Hiển thị thông báo thành công
                        showNotification('success', response.message || 'Khôi phục phòng khoa thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể khôi phục phòng khoa. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện chọn tất cả
    $('#select-all').on('change', function() {
        $('input[name="phong_khoa_id[]"]').prop('checked', $(this).prop('checked'));
    });
    
    // Cập nhật trạng thái Select All khi chọn từng checkbox
    $(document).on('change', 'input[name="phong_khoa_id[]"]', function() {
        var allChecked = $('input[name="phong_khoa_id[]"]:checked').length === $('input[name="phong_khoa_id[]"]').length;
        $('#select-all').prop('checked', allChecked);
    });
    
    // Hàm hiển thị thông báo
    function showNotification(type, message) {
        var bgClass = 'bg-success';
        var icon = 'bx bx-check-circle';
        
        if (type === 'error') {
            bgClass = 'bg-danger';
            icon = 'bx bx-error-circle';
        } else if (type === 'warning') {
            bgClass = 'bg-warning';
            icon = 'bx bx-error';
        } else if (type === 'info') {
            bgClass = 'bg-info';
            icon = 'bx bx-info-circle';
        }
        
        var html = '<div class="toast-container position-fixed top-0 end-0 p-3">' +
                   '<div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
                   '<div class="toast-header ' + bgClass + ' text-white">' +
                   '<i class="' + icon + ' me-2"></i>' +
                   '<strong class="me-auto">' + (type === 'success' ? 'Thành công' : 'Thông báo') + '</strong>' +
                   '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' +
                   '</div>' +
                   '<div class="toast-body">' + message + '</div>' +
                   '</div>' +
                   '</div>';
        
        // Thêm thông báo vào body
        $('body').append(html);
        
        // Tự động đóng thông báo sau 3 giây
        setTimeout(function() {
            $('.toast-container').remove();
        }, 3000);
    }
});
</script>
<?= $this->endSection() ?>