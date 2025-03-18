<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH NGƯỜI DÙNG ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Danh sách Người Dùng đã xóa',
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Người Dùng', 'url' => site_url('/nguoidung')],
        ['title' => 'Danh sách Người Dùng đã xóa', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/nguoidung/new'), 'title' => 'Tạo Người Dùng'],
		['url' => site_url('/nguoidung'), 'title' => 'Danh sách Người Dùng']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
    <?= form_open("nguoidung/restoreusers", ['class' => 'row g3']) ?>
    <div class="col-12 mb-3">
        <button type="submit" class="btn btn-success">Khôi phục người dùng đã chọn</button>
        <button type="submit" id="force-delete-btn" class="btn btn-danger" formaction="<?= site_url('nguoidung/forcedelete') ?>">Xóa vĩnh viễn</button>
    </div>
    <?= view('components/_table', [
        'caption' => 'Danh Sách Người Dùng đã xóa',
        'headers' => [
            '<input type="checkbox" id="select-all" />', 
            'AccountId', 
            'FullName', 
            'Ngày xóa'
        ],    
        'data' => $data,
        'columns' => [
            [
                'type' => 'checkbox',
                'id_field' => 'nguoi_dung_id',
                'name' => 'nguoi_dung_id[]'
            ],
            [
                'field' => 'AccountId'
            ],
            [
                'field' => 'FullName'
            ],
            [
                'field' => 'deleted_at',
                'format' => 'date'
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
        $('input[name="nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một người dùng để xóa vĩnh viễn.');
            return false;
        }
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn những người dùng này? Hành động này không thể hoàn tác!')) {
            var data = {
                nguoi_dung_ids: selectedIds
            };
            data[csrfName] = csrfHash;
            
            $.ajax({
                url: '<?= site_url('nguoidung/forcedelete') ?>',
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
                        $('input[name="nguoi_dung_id[]"]:checked').each(function() {
                            var row = $(this).closest('tr');
                            dataTable.row(row).remove();
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
                        showNotification('success', response.message || 'Xóa vĩnh viễn người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể xóa vĩnh viễn người dùng. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', xhr.responseText);
                    showNotification('error', 'Đã xảy ra lỗi: ' + (xhr.status === 404 ? 'Không tìm thấy đường dẫn' : error));
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện khôi phục người dùng bằng Ajax
    $(document).on('click', '.restore-user', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var row = $(this).closest('tr');
        var userName = $(this).attr('title').replace('Khôi phục ', '');
        var userId = $(this).closest('tr').find('input[name="nguoi_dung_id[]"]').val();
        
        // Đảm bảo URL chính xác
        if (!url || url === '#' || url === 'javascript:void(0)') {
            url = '<?= site_url('nguoidung/restoreusers') ?>/' + userId;
        }
        
        console.log('Restore URL:', url); // Log URL để debug
        
        // Lưu thông tin trang hiện tại
        var currentPage = dataTable.page();
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn có chắc chắn muốn khôi phục người dùng "' + userName + '"?')) {
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
                        
                        // Lưu thông tin người dùng đã khôi phục vào localStorage để cập nhật trang index
                        if (response.user) {
                            localStorage.setItem('restored_user', JSON.stringify(response.user));
                        }
                        
                        // Hiển thị thông báo thành công
                        showNotification('success', response.message || 'Khôi phục người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể khôi phục người dùng. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Ajax error:', xhr.responseText);
                    showNotification('error', 'Đã xảy ra lỗi: ' + (xhr.status === 404 ? 'Không tìm thấy đường dẫn' : error));
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện khôi phục nhiều người dùng
    $('form').on('submit', function(e) {
        // Chỉ xử lý form khôi phục, không xử lý form xóa vĩnh viễn
        if ($(this).attr('action') === '<?= site_url('nguoidung/restoreusers') ?>') {
            e.preventDefault();
            
            var selectedIds = [];
            
            // Lấy tất cả ID đã chọn
            $('input[name="nguoi_dung_id[]"]:checked').each(function() {
                selectedIds.push($(this).val());
            });
            
            if (selectedIds.length === 0) {
                showNotification('warning', 'Vui lòng chọn ít nhất một người dùng để khôi phục.');
                return false;
            }
            
            // Lưu thông tin trang hiện tại
            var currentPage = dataTable.page();
            
            // Đảm bảo URL chính xác
            var url = '<?= site_url('nguoidung/restoreusers') ?>';
            console.log('Restore multiple URL:', url); // Log URL để debug
            
            if (confirm('Bạn có chắc chắn muốn khôi phục ' + selectedIds.length + ' người dùng đã chọn?')) {
                var data = {
                    nguoi_dung_ids: selectedIds
                };
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
                            // Xóa các dòng đã chọn khỏi DataTable
                            $('input[name="nguoi_dung_id[]"]:checked').each(function() {
                                var row = $(this).closest('tr');
                                dataTable.row(row).remove();
                            });
                            
                            // Vẽ lại bảng
                            dataTable.draw(false);
                            
                            // Quay lại trang hiện tại nếu cần
                            if (dataTable.page() !== currentPage && dataTable.page.info().pages > currentPage) {
                                dataTable.page(currentPage).draw(false);
                            }
                            
                            // Bỏ chọn checkbox "chọn tất cả"
                            $('#select-all').prop('checked', false);
                            
                            // Lưu thông tin người dùng đã khôi phục vào localStorage để cập nhật trang index
                            if (response.users && response.users.length > 0) {
                                // Chỉ lưu người dùng đầu tiên để thông báo
                                localStorage.setItem('restored_user', JSON.stringify(response.users[0]));
                            }
                            
                            // Hiển thị thông báo thành công
                            showNotification('success', response.message || 'Khôi phục người dùng thành công.');
                        } else {
                            // Hiển thị thông báo lỗi
                            showNotification('error', response.message || 'Không thể khôi phục người dùng. Vui lòng thử lại.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax error:', xhr.responseText);
                        showNotification('error', 'Đã xảy ra lỗi: ' + (xhr.status === 404 ? 'Không tìm thấy đường dẫn' : error));
                    }
                });
            }
            
            return false;
        }
    });
    
    // Xử lý sự kiện chọn tất cả
    $('#select-all').on('change', function() {
        $('input[name="nguoi_dung_id[]"]').prop('checked', $(this).prop('checked'));
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