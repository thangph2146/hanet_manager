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
<?= $this->section('title') ?>QUẢN LÝ PHÒNG KHOA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Phòng Khoa',
	'dashboard_url' => site_url('users/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Phòng Khoa', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/phongkhoa/new'), 'title' => 'Tạo Phòng Khoa'],
		['url' => site_url('/phongkhoa/listdeleted'), 'title' => 'Danh sách Phòng Khoa đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
	<?= form_open("phongkhoa/deleteMultiple", ['class' => 'row g3', 'id' => 'delete-form']) ?>
	<div class="col-12 mb-3">
		<button type="button" id="delete-selected" class="btn btn-danger ms-2">Xóa tất cả đã chọn</button>
	</div>
    <input type="hidden" name="selected_ids" id="selected_ids" value="">
<?= view('components/_table', [
    'caption' => 'Danh Sách Phòng Khoa',
    'headers' => [
        '<input type="checkbox" id="select-all" />', 
        'ID', 
        'Mã phòng khoa',
        'Tên phòng khoa', 
        'Ghi chú',
        'Status',
        'Action'
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
            'type' => 'status',
            'field' => 'status',
            'active_label' => 'Hoạt động',
            'inactive_label' => 'Đã khóa!!'
        ],
        [
            'type' => 'actions',
            'buttons' => [
                [
                    'url_prefix' => site_url('phongkhoa/edit/'),
                    'id_field' => 'phong_khoa_id',
                    'title_field' => 'ten_phong_khoa',
                    'title' => 'Edit %s',
                    'icon' => 'fadeIn animated bx bx-edit'
                ],
                [
                    'url_prefix' => site_url('phongkhoa/delete/'),
                    'id_field' => 'phong_khoa_id',
                    'title_field' => 'ten_phong_khoa',
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
    
    // Lắng nghe sự kiện từ localStorage để cập nhật bảng khi có phòng khoa được khôi phục
    window.addEventListener('storage', function(e) {
        if (e.key === 'restored_phongkhoa') {
            try {
                var phongKhoaData = JSON.parse(e.newValue);
                console.log('Received restored phongkhoa data:', phongKhoaData); // Log để debug
                
                if (phongKhoaData && phongKhoaData.id) {
                    // Kiểm tra xem phòng khoa đã tồn tại trong bảng chưa
                    var existingRow = null;
                    dataTable.rows().every(function() {
                        var rowData = this.data();
                        var rowId = $(rowData[0]).val();
                        if (rowId == phongKhoaData.phong_khoa_id) {
                            existingRow = this;
                            return false; // Break the loop
                        }
                    });
                    
                    if (existingRow) {
                        console.log('Phòng khoa already exists in table, updating...');
                        // Cập nhật dòng hiện tại
                        existingRow.draw(false);
                        $(existingRow.node()).addClass('highlight-row');
                        setTimeout(function() {
                            $(existingRow.node()).removeClass('highlight-row');
                        }, 3000);
                    } else {
                        console.log('Adding new phongkhoa to table...');
                        // Thêm phòng khoa đã khôi phục vào bảng
                        var newRow = dataTable.row.add([
                            '<input type="checkbox" class="check-select-p" name="phong_khoa_id[]" value="' + phongKhoaData.phong_khoa_id + '" />',
                            phongKhoaData.phong_khoa_id,
                            phongKhoaData.ma_phong_khoa,
                            phongKhoaData.ten_phong_khoa,
                            phongKhoaData.ghi_chu,
                            '<span class="badge rounded-pill bg-success">Hoạt động</span>',
                            '<a href="<?= site_url('phongkhoa/edit') ?>/' + phongKhoaData.phong_khoa_id + '" title="Edit ' + phongKhoaData.ten_phong_khoa + '" class="btn btn-sm btn-primary"><i class="fadeIn animated bx bx-edit"></i></a> ' +
                            '<a href="<?= site_url('phongkhoa/delete') ?>/' + phongKhoaData.phong_khoa_id + '" title="Delete ' + phongKhoaData.ten_phong_khoa + '" class="btn btn-sm btn-danger"><i class="lni lni-trash"></i></a>'
                        ]).draw(false).node();
                        
                        // Làm nổi bật dòng mới thêm
                        $(newRow).addClass('highlight-row');
                        setTimeout(function() {
                            $(newRow).removeClass('highlight-row');
                        }, 3000);
                    }
                    
                    // Hiển thị thông báo
                    showNotification('success', 'Phòng khoa "' + phongKhoaData.ten_phong_khoa + '" đã được khôi phục.');
                    
                    // Xóa dữ liệu từ localStorage
                    localStorage.removeItem('restored_phongkhoa');
                }
            } catch (error) {
                console.error('Error parsing restored phongkhoa data:', error);
            }
        }
    });
    
    // Xử lý sự kiện xóa phòng khoa bằng Ajax
    $(document).on('click', '.lni-trash', function(e) {
        e.preventDefault();
        
        var url = $(this).parent().attr('href');
        var row = $(this).closest('tr');
        var phongKhoaName = $(this).parent().attr('title').replace('Delete ', '');
        
        // Hiển thị hộp thoại xác nhận
        if (confirm('Bạn thật sự muốn xóa Phòng Khoa này?')) {
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
                        showNotification('success', response.message || 'Xóa phòng khoa thành công.');
                    } else {
                        // Hiển thị thông báo lỗi
                        showNotification('error', response.message || 'Không thể xóa phòng khoa. Vui lòng thử lại.');
                    }
                },
                error: function(xhr, status, error) {
                    showNotification('error', 'Đã xảy ra lỗi: ' + error);
                }
            });
        }
        
        return false;
    });
    
    // Xử lý sự kiện xóa tất cả phòng khoa đã chọn
    $('#delete-selected').on('click', function() {
        var selectedIds = [];
        
        // Lấy tất cả ID đã chọn
        $('.check-select-p:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một phòng khoa để xóa.');
            return;
        }
        
        if (confirm('Bạn thật sự muốn xóa ' + selectedIds.length + ' phòng khoa đã chọn?')) {
            // Cập nhật hidden input với danh sách ID
            $('#selected_ids').val(selectedIds.join(','));
            
            // Gửi form để xóa
            $('#delete-form').submit();
        }
    });
    
    // Chọn tất cả
    $('#select-all').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.check-select-p').prop('checked', isChecked);
    });
    
    // Cập nhật trạng thái "chọn tất cả" khi các checkbox riêng lẻ thay đổi
    $(document).on('change', '.check-select-p', function() {
        var allChecked = $('.check-select-p:checked').length === $('.check-select-p').length;
        $('#select-all').prop('checked', allChecked);
    });
    
    // Xử lý sự kiện để hiển thị thông báo
    function showNotification(type, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            alert(message);
        }
    }
});
</script>
<?= $this->endSection() ?>