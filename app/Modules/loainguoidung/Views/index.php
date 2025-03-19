<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
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
<?= $this->endSection() ?>
<?= $this->section('title') ?>QUẢN LÝ LOẠI NGƯỜI DÙNG<?= $this->endSection() ?>



<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Loại Người Dùng',
	'dashboard_url' => site_url('users/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Loại Người Dùng', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/loainguoidung/new'), 'title' => 'Tạo Loại Người Dùng'],
		['url' => site_url('/loainguoidung/listdeleted'), 'title' => 'Danh sách Loại Người Dùng đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
	<?= form_open("loainguoidung/deleteMultiple", ['class' => 'row g3', 'id' => 'delete-form']) ?>
	<div class="col-12 mb-3">
		<button type="button" id="delete-selected" class="btn btn-danger ms-2">Xóa tất cả đã chọn</button>
	</div>
    <input type="hidden" name="selected_ids" id="selected_ids" value="">
<?= view('components/_table', [
    'caption' => 'Danh Sách Loại Người Dùng',
    'headers' => [
        '<input type="checkbox" id="select-all" />', 
        'ID', 
        'Tên loại', 
        'Mô tả',
        'Status',
        'Action'
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
            'type' => 'status',
            'field' => 'status',
            'active_label' => 'Hoạt động',
            'inactive_label' => 'Đã khóa!!'
        ],
        [
            'type' => 'actions',
            'buttons' => [
                [
                    'url_prefix' => site_url('loainguoidung/edit/'),
                    'id_field' => 'loai_nguoi_dung_id',
                    'title_field' => 'ten_loai',
                    'title' => 'Edit %s',
                    'icon' => 'fadeIn animated bx bx-edit'
                ],
                [
                    'url_prefix' => site_url('loainguoidung/delete/'),
                    'id_field' => 'loai_nguoi_dung_id',
                    'title_field' => 'ten_loai',
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
    
    // Lắng nghe sự kiện từ localStorage để cập nhật bảng khi có loại người dùng được khôi phục
    window.addEventListener('storage', function(e) {
        if (e.key === 'restored_loai') {
            try {
                var loaiData = JSON.parse(e.newValue);
                console.log('Received restored loai data:', loaiData); // Log để debug
                
                if (loaiData && loaiData.id) {
                    // Kiểm tra xem loại người dùng đã tồn tại trong bảng chưa
                    var existingRow = null;
                    dataTable.rows().every(function() {
                        var rowData = this.data();
                        var rowId = $(rowData[0]).val();
                        if (rowId == loaiData.loai_nguoi_dung_id) {
                            existingRow = this;
                            return false; // Break the loop
                        }
                    });
                    
                    if (existingRow) {
                        console.log('Loại người dùng already exists in table, updating...');
                        // Cập nhật dòng hiện tại
                        existingRow.draw(false);
                        $(existingRow.node()).addClass('highlight-row');
                        setTimeout(function() {
                            $(existingRow.node()).removeClass('highlight-row');
                        }, 3000);
                    } else {
                        console.log('Adding new loai to table...');
                        // Thêm loại người dùng đã khôi phục vào bảng
                        var newRow = dataTable.row.add([
                            '<input type="checkbox" class="check-select-p" name="loai_nguoi_dung_id[]" value="' + loaiData.loai_nguoi_dung_id + '" />',
                            loaiData.loai_nguoi_dung_id,
                            loaiData.ten_loai,
                            loaiData.mo_ta,
                            '<span class="badge rounded-pill bg-success">Hoạt động</span>',
                            '<a href="<?= site_url('loainguoidung/edit') ?>/' + loaiData.loai_nguoi_dung_id + '" title="Edit ' + loaiData.ten_loai + '" class="btn btn-sm btn-primary"><i class="fadeIn animated bx bx-edit"></i></a> ' +
                            '<a href="<?= site_url('loainguoidung/delete') ?>/' + loaiData.loai_nguoi_dung_id + '" title="Delete ' + loaiData.ten_loai + '" class="btn btn-sm btn-danger"><i class="lni lni-trash"></i></a>'
                        ]).draw(false).node();
                        
                        // Làm nổi bật dòng mới thêm
                        $(newRow).addClass('highlight-row');
                        setTimeout(function() {
                            $(newRow).removeClass('highlight-row');
                        }, 3000);
                    }
                    
                    // Hiển thị thông báo
                    showNotification('success', 'Loại người dùng "' + loaiData.ten_loai + '" đã được khôi phục.');
                    
                    // Xóa dữ liệu từ localStorage
                    localStorage.removeItem('restored_loai');
                }
            } catch (error) {
                console.error('Error parsing restored loai data:', error);
            }
        }
    });
    
    // Xử lý sự kiện xóa loại người dùng bằng Ajax
    $(document).on('click', '.lni-trash', function(e) {
        e.preventDefault();
        
        var url = $(this).parent().attr('href');
        var row = $(this).closest('tr');
        var loaiName = $(this).parent().attr('title').replace('Delete ', '');
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn thật sự muốn xóa Loại Người Dùng này?')) {
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
                        showNotification('success', response.message || 'Xóa loại người dùng thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể xóa loại người dùng. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện xóa tất cả loại người dùng đã chọn
    $('#delete-selected').on('click', function() {
        var selectedIds = [];
        
        // Lấy tất cả ID đã chọn
        $('.check-select-p:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một loại người dùng để xóa.');
            return;
        }
        
        if (confirm('Bạn thật sự muốn xóa ' + selectedIds.length + ' loại người dùng đã chọn?')) {
            // Cập nhật hidden input với danh sách ID
            $('#selected_ids').val(selectedIds.join(','));
            
            // Gửi form để xóa
            $('#delete-form').submit();
        }
    });
    
    // Chọn tất cả
    $('#select-all').on('click', function() {
        $('.check-select-p').prop('checked', this.checked);
    });
    
    // Cập nhật trạng thái Select All khi chọn từng checkbox
    $(document).on('change', '.check-select-p', function() {
        var allChecked = $('.check-select-p:checked').length === $('.check-select-p').length;
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