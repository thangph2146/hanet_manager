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
            ['title' => 'Loại người dùng', 'url' => site_url('loainguoidung')],
            ['title' => 'Thùng rác', 'active' => true]
        ],
        'actions' => [
            ['url' => site_url('loainguoidung'), 'title' => 'Quay lại danh sách', 'icon' => 'fas fa-arrow-left']
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
        
        <!-- Form xử lý khôi phục nhiều -->
        <?= form_open('loainguoidung/restoreMultiple', ['id' => 'form-restore-multiple', 'style' => 'display:none;']) ?>
            <input type="hidden" name="selected_ids" id="selected_ids" value="">
        <?= form_close() ?>
        
        <!-- Danh sách loại người dùng đã xóa -->
        <div class="card shadow-sm">
            <div class="card-header border-bottom bg-white">
                <h5 class="card-title text-primary mb-0">
                    <i class="fas fa-trash-restore mr-2"></i> <?= $title ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($loai_nguoi_dungs)) : ?>
                    <!-- Bulk action buttons -->
                    <div class="bg-light p-3 border-bottom">
                        <div class="btn-group">
                            <button type="button" id="btn-restore-multiple" class="btn btn-success btn-sm">
                                <i class="fas fa-trash-restore"></i> Khôi phục đã chọn
                            </button>
                        </div>
                    </div>
                    
                    <!-- Table -->
                    <div class="table-responsive">
                        <table id="deleted-table" class="table table-hover table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th width="5%"><input type="checkbox" id="select-all"></th>
                                    <th width="5%">STT</th>
                                    <th>Tên loại</th>
                                    <th>Mô tả</th>
                                    <th>Ngày xóa</th>
                                    <th width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($loai_nguoi_dungs as $index => $item) : ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="check-select-p" value="<?= $item->loai_nguoi_dung_id ?>">
                                        </td>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= esc($item->ten_loai) ?></td>
                                        <td><?= esc($item->mo_ta) ?></td>
                                        <td><?= $item->deleted_at ? date('d/m/Y H:i', strtotime($item->deleted_at)) : '' ?></td>
                                        <td>
                                            <a href="<?= site_url('loainguoidung/restore/' . $item->loai_nguoi_dung_id) ?>" 
                                               class="btn btn-sm btn-success rounded-circle action-btn btn-restore"
                                               title="Khôi phục <?= esc($item->ten_loai) ?>"
                                               data-id="<?= $item->loai_nguoi_dung_id ?>">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            <a href="<?= site_url('loainguoidung/permanentDelete/' . $item->loai_nguoi_dung_id) ?>" 
                                               class="btn btn-sm btn-danger rounded-circle action-btn btn-permanent-delete"
                                               title="Xóa vĩnh viễn <?= esc($item->ten_loai) ?>"
                                               data-id="<?= $item->loai_nguoi_dung_id ?>">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="p-4 text-center">
                        <p class="mb-0">Không có loại người dùng nào trong thùng rác.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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
    // Initialize DataTable
    $('#deleted-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "<?= base_url('assets/plugins/datatables/Vietnamese.json') ?>"
        }
    });
    
    // Xử lý nút khôi phục đơn
    $(document).on('click', '.btn-restore', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const tenLoai = $(this).attr('title').replace('Khôi phục ', '');
        
        Swal.fire({
            title: 'Xác nhận khôi phục?',
            text: `Bạn có chắc chắn muốn khôi phục loại người dùng "${tenLoai}" không?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Khôi phục',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
    
    // Xử lý nút xóa vĩnh viễn
    $(document).on('click', '.btn-permanent-delete', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const tenLoai = $(this).attr('title').replace('Xóa vĩnh viễn ', '');
        
        Swal.fire({
            title: 'Xác nhận xóa vĩnh viễn?',
            text: `Bạn có chắc chắn muốn xóa vĩnh viễn loại người dùng "${tenLoai}"? Hành động này không thể hoàn tác.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa vĩnh viễn',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
    
    // Xử lý nút chọn tất cả
    $('#select-all').on('change', function() {
        $('.check-select-p').prop('checked', $(this).prop('checked'));
    });
    
    // Cập nhật nút chọn tất cả khi check từng item
    $(document).on('change', '.check-select-p', function() {
        const totalCheckboxes = $('.check-select-p').length;
        const checkedCheckboxes = $('.check-select-p:checked').length;
        
        $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
    
    // Xử lý nút khôi phục nhiều
    $('#btn-restore-multiple').on('click', function(e) {
        e.preventDefault();
        
        // Lấy danh sách ID đã chọn
        const selectedIds = [];
        $('.check-select-p:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            Swal.fire(
                'Chưa chọn mục nào!',
                'Vui lòng chọn ít nhất một mục để khôi phục.',
                'warning'
            );
            return;
        }
        
        Swal.fire({
            title: 'Xác nhận khôi phục nhiều?',
            text: `Bạn có chắc chắn muốn khôi phục ${selectedIds.length} loại người dùng đã chọn không?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Khôi phục tất cả',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Đặt giá trị cho input hidden
                $('#selected_ids').val(selectedIds.join(','));
                
                // Submit form
                $('#form-restore-multiple').submit();
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 