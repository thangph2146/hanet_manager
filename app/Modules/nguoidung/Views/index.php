<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('title') ?>QUẢN LÝ NGƯỜI DÙNG<?= $this->endSection() ?>



<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Người Dùng',
	'dashboard_url' => site_url('users/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Người Dùng', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/nguoidung/new'), 'title' => 'Tạo Người Dùng'],
		['url' => site_url('/nguoidung/listdeleted'), 'title' => 'Danh sách Người Dùng đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
	<?= form_open("nguoidung/resetpassword", ['class' => 'row g3']) ?>
	<div class="col-12 mb-3">
		<button type="submit" class="btn btn-primary">ResetPassWord</button>
		<button type="button" id="delete-selected" class="btn btn-danger ms-2">Xóa tất cả đã chọn</button>
	</div>
<?= view('components/_table', [
    'caption' => 'Danh Sách Người Dùng',
    'headers' => [
        '<input type="checkbox" id="select-all" />', 
        'AccountId', 
        'FullName', 
        'Status',
        'Action'
    ],	
    'data' => $data,
    'columns' => [
        [
            'type' => 'checkbox',
            'id_field' => 'id',
            'name' => 'id[]'
        ],
        [
            'field' => 'AccountId'
        ],
        [
            'field' => 'FullName'
        ],
        [
            'type' => 'status',
            'field' => 'status',
            'active_label' => 'Hoạt động',
            'inactive_label' => 'Đã khóa!!'
        ],
        [
            'type' => 'actions',
            'buttons' => [
                [
                    'url_prefix' => '/nguoidung/edit/',
                    'id_field' => 'id',
                    'title_field' => 'FullName',
                    'title' => 'Edit %s',
                    'icon' => 'fadeIn animated bx bx-edit'
                ],
                [
                    'url_prefix' => '/nguoidung/deleteusers/',
                    'id_field' => 'id',
                    'title_field' => 'FullName',
                    'title' => 'Delete %s',
                    'icon' => 'lni lni-trash'
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
    
    // Xử lý sự kiện xóa người dùng bằng Ajax
    $(document).on('click', '.lni-trash', function(e) {
        e.preventDefault();
        
        var url = $(this).parent().attr('href');
        var row = $(this).closest('tr');
        var userName = $(this).parent().attr('title').replace('Delete ', '');
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn thật sự muốn xóa Người Dùng này?')) {
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
                        showNotification('success', response.message || 'Xóa người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể xóa người dùng. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện xóa tất cả người dùng đã chọn
    $('#delete-selected').on('click', function() {
        var selectedIds = [];
        
        // Lấy tất cả ID đã chọn
        $('.check-select-p:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một người dùng để xóa.');
            return;
        }
        
        if (confirm('Bạn thật sự muốn xóa ' + selectedIds.length + ' người dùng đã chọn?')) {
            var data = {
                ids: selectedIds
            };
            data[csrfName] = csrfHash;
            
            $.ajax({
                url: '<?= site_url('/nguoidung/deleteusers') ?>',
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
                        $('.check-select-p:checked').each(function() {
                            var row = $(this).closest('tr');
                            dataTable.row(row).remove();
                        });
                        
                        // Vẽ lại bảng
                        dataTable.draw(false);
                        
                        // Bỏ chọn checkbox "chọn tất cả"
                        $('#select-all').prop('checked', false);
                        
                        // Hiển thị thông báo thành công
                        showNotification('success', response.message || 'Xóa người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể xóa người dùng. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
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