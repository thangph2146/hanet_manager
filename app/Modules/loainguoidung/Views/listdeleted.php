<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH LOẠI NGƯỜI DÙNG ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Danh sách Loại Người Dùng đã xóa',
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Loại Người Dùng', 'url' => site_url('/loainguoidung')],
        ['title' => 'Danh sách Loại Người Dùng đã xóa', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/loainguoidung/new'), 'title' => 'Tạo Loại Người Dùng'],
		['url' => site_url('/loainguoidung'), 'title' => 'Danh sách Loại Người Dùng']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
    <?= form_open("loainguoidung/restoreMultiple", ['class' => 'row g3', 'id' => 'restore-form']) ?>
    <div class="col-12 mb-3">
        <button type="submit" class="btn btn-success">Khôi phục loại người dùng đã chọn</button>
        <button type="button" id="force-delete-btn" class="btn btn-danger">Xóa vĩnh viễn</button>
    </div>
    <input type="hidden" name="selected_ids" id="selected_ids" value="">
    <?= view('components/_table', [
        'caption' => 'Danh Sách Loại Người Dùng đã xóa',
        'headers' => [
            '<input type="checkbox" id="select-all" />', 
            'ID', 
            'Tên loại', 
            'Mô tả',
            'Ngày xóa',
            'Thao tác'
        ],    
        'data' => $loai_nguoi_dungs,
        'columns' => [
            [
                'type' => 'checkbox',
                'id_field' => 'loai_nguoi_dung_id',
                'name' => 'loai_nguoi_dung_id[]'
            ],
            [
                'field' => 'loai_nguoi_dung_id'
            ],
            [
                'field' => 'ten_loai'
            ],
            [
                'field' => 'mo_ta'
            ],
            [
                'field' => 'deleted_at',
                'format' => 'date'
            ],
            [
                'type' => 'actions',
                'buttons' => [
                    [
                        'url_prefix' => site_url('loainguoidung/permanentDelete/'),
                        'id_field' => 'loai_nguoi_dung_id',
                        'title_field' => 'ten_loai',
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
        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một loại người dùng để xóa vĩnh viễn.');
            return false;
        }
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn những loại người dùng này? Hành động này không thể hoàn tác!')) {
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
                    url: '<?= site_url('loainguoidung/permanentDelete') ?>/' + id,
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
                            var row = $('input[name="loai_nguoi_dung_id[]"][value="' + id + '"]').closest('tr');
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
                                showNotification('success', 'Đã xóa vĩnh viễn ' + successCount + ' loại người dùng thành công.');
                            }
                        } else {
                            // Kiểm tra nếu đã xử lý tất cả các ID
                            if (processedCount === totalIds) {
                                // Vẽ lại bảng
                                dataTable.draw(false);
                                
                                // Hiển thị thông báo với số lượng thành công
                                if (successCount > 0) {
                                    showNotification('info', 'Đã xóa vĩnh viễn ' + successCount + ' loại người dùng thành công và ' + (totalIds - successCount) + ' thất bại.');
                                } else {
                                    showNotification('error', 'Không thể xóa vĩnh viễn loại người dùng. Vui lòng thử lại.');
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
                                showNotification('info', 'Đã xóa vĩnh viễn ' + successCount + ' loại người dùng thành công và ' + (totalIds - successCount) + ' thất bại.');
                            } else {
                                showNotification('error', 'Không thể xóa vĩnh viễn loại người dùng. Vui lòng thử lại.');
                            }
                        }
                    }
                });
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện khôi phục loại người dùng bằng Ajax
    $(document).on('click', '.restore-loai', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var row = $(this).closest('tr');
        var loaiName = $(this).attr('title').replace('Khôi phục ', '');
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn khôi phục loại người dùng "' + loaiName + '"?')) {
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
                        
                        // Lưu thông tin loại người dùng đã khôi phục vào localStorage để cập nhật trang index
                        var loaiData = {
                            id: row.find('input[name="loai_nguoi_dung_id[]"]').val(),
                            loai_nguoi_dung_id: row.find('input[name="loai_nguoi_dung_id[]"]').val(),
                            ten_loai: loaiName,
                            mo_ta: row.find('td:eq(3)').text().trim()
                        };
                        localStorage.setItem('restored_loai', JSON.stringify(loaiData));
                        
                        // Hiển thị thông báo thành công
                        showNotification('success', 'Khôi phục loại người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể khôi phục loại người dùng. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện xóa vĩnh viễn một loại người dùng
    $(document).on('click', '.delete-permanent', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var row = $(this).closest('tr');
        var loaiName = $(this).attr('title').replace('Xóa vĩnh viễn ', '');
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn loại người dùng "' + loaiName + '"? Hành động này không thể hoàn tác!')) {
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
                        showNotification('success', 'Xóa vĩnh viễn loại người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể xóa vĩnh viễn loại người dùng. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện khôi phục nhiều loại người dùng
    $('#restore-form').on('submit', function(e) {
        e.preventDefault();
        
        var selectedIds = [];
        
        // Lấy tất cả ID đã chọn
        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một loại người dùng để khôi phục.');
            return false;
        }
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        if (confirm('Bạn có chắc chắn muốn khôi phục ' + selectedIds.length + ' loại người dùng đã chọn?')) {
            // Cập nhật hidden input với danh sách ID
            $('#selected_ids').val(selectedIds.join(','));
            
            var data = {
                selected_ids: selectedIds.join(',')
            };
            data[csrfName] = csrfHash;
            
            $.ajax({
                url: '<?= site_url('loainguoidung/restoreMultiple') ?>',
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
                        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
                            var row = $(this).closest('tr');
                            // Lưu thông tin loại người dùng trước khi xóa dòng
                            var loaiData = {
                                id: $(this).val(),
                                loai_nguoi_dung_id: $(this).val(),
                                ten_loai: row.find('td:eq(2)').text().trim(),
                                mo_ta: row.find('td:eq(3)').text().trim()
                            };
                            
                            // Xóa dòng
                            dataTable.row(row).remove();
                            
                            // Lưu thông tin loại người dùng đầu tiên vào localStorage để cập nhật trang index
                            if (selectedIds.indexOf($(this).val()) === 0) {
                                localStorage.setItem('restored_loai', JSON.stringify(loaiData));
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
                        showNotification('success', response.message || 'Khôi phục loại người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể khôi phục loại người dùng. Vui lòng thử lại.');
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
        $('input[name="loai_nguoi_dung_id[]"]').prop('checked', $(this).prop('checked'));
    });
    
    // Cập nhật trạng thái Select All khi chọn từng checkbox
    $(document).on('change', 'input[name="loai_nguoi_dung_id[]"]', function() {
        var allChecked = $('input[name="loai_nguoi_dung_id[]"]:checked').length === $('input[name="loai_nguoi_dung_id[]"]').length;
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