<?= $this->extend('layouts/default'); ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <!-- Breadcrumb -->
    <?= view('components/_breakcrump', [
        'title' => $title,
        'dashboard_url' => site_url('admin'),
        'breadcrumbs' => [
            ['title' => 'Loại người dùng', 'active' => true]
        ],
        'actions' => [
            ['url' => site_url('loainguoidung/new'), 'title' => 'Thêm mới', 'icon' => 'fas fa-plus'],
            ['url' => site_url('loainguoidung/deleted'), 'title' => 'Thùng rác', 'icon' => 'fas fa-trash']
        ]
    ]) ?>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Thông báo -->
        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Thành công!</h5>
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Lỗi!</h5>
                <?= session('error') ?>
            </div>
        <?php endif; ?>
        
        <!-- Form xử lý xóa nhiều -->
        <?= form_open('loainguoidung/deleteMultiple', ['id' => 'form-delete-multiple', 'style' => 'display:none;']) ?>
            <input type="hidden" name="selected_ids" id="selected_ids" value="">
        <?= form_close() ?>
        
        <!-- Danh sách loại người dùng -->
        <?= $this->include('App\Modules\loainguoidung\Views\_list', ['loai_nguoi_dungs' => $loai_nguoi_dungs]) ?>
    </div>
</section>
<!-- /.content -->

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<script>
$(document).ready(function() {
    // Xử lý nút xóa đơn
    $(document).on('click', '.btn-danger[data-id]', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const tenLoai = $(this).closest('a').attr('title').replace('Xóa ', '');
        
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: `Bạn có chắc chắn muốn xóa loại người dùng "${tenLoai}" không?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi AJAX request để xóa
                $.ajax({
                    url: '<?= site_url('loainguoidung/delete') ?>/' + id,
                    type: 'POST',
                    data: {
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Đã xóa!',
                                response.message,
                                'success'
                            ).then(() => {
                                // Tải lại trang sau khi xóa thành công
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Lỗi!',
                            'Có lỗi xảy ra khi xóa loại người dùng.',
                            'error'
                        );
                    }
                });
            }
        });
    });
    
    // Xử lý nút xóa nhiều
    $('#btn-delete-multiple').on('click', function(e) {
        e.preventDefault();
        
        // Lấy danh sách ID đã chọn
        const selectedIds = [];
        $('.check-select-p:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            Swal.fire(
                'Chưa chọn mục nào!',
                'Vui lòng chọn ít nhất một mục để xóa.',
                'warning'
            );
            return;
        }
        
        Swal.fire({
            title: 'Xác nhận xóa nhiều?',
            text: `Bạn có chắc chắn muốn xóa ${selectedIds.length} loại người dùng đã chọn không?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa tất cả',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Đặt giá trị cho input hidden
                $('#selected_ids').val(selectedIds.join(','));
                
                // Submit form
                $('#form-delete-multiple').submit();
            }
        });
    });
});
</script>

<?= $this->include('App\Modules\loainguoidung\Views\_scripts') ?>
<?= $this->endSection() ?> 