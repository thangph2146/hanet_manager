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
	'dashboard_url' => site_url('loainguoidung/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Loại Người Dùng', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/loainguoidung/new'), 'title' => 'Tạo Loại Người Dùng Mới'],
		['url' => site_url('/loainguoidung/listdeleted'), 'title' => 'Danh sách Loại Người Dùng đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <?= form_open("loainguoidung/delete", ['id' => 'form-delete-multiple', 'class' => 'row g-3']) ?>
            <div class="col-12 mb-3">
                <button type="button" id="delete-selected" class="btn btn-danger me-2">Xóa mục đã chọn</button>
                <?= form_close() ?>
                
                <?= form_open("loainguoidung/statusMultiple", ['id' => 'form-status-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="status-selected" class="btn btn-warning">Đổi trạng thái mục đã chọn</button>
                <?= form_close() ?>
            </div>
            
            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="select-all" /></th>
                        <th width="20%">Tên loại</th>
                        <th width="40%">Mô tả</th>
                        <th width="15%">Trạng thái</th>
                        <th width="10%">Ngày tạo</th>
                        <th width="10%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($loai_nguoi_dung)): ?>
                        <?php foreach ($loai_nguoi_dung as $loai): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $loai['id'] ?>" class="checkbox-item" />
                                </td>
                                <td><?= $loai['ten_loai_nguoi_dung'] ?></td>
                                <td><?= $loai['mo_ta'] ?></td>
                                <td><?= $loai['status'] ?></td>
                                <td><?= (new DateTime($loai['created_at']))->format('d/m/Y') ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?= site_url('loainguoidung/edit/' . $loai['id']) ?>" class="btn btn-primary btn-sm me-1" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="<?= site_url('loainguoidung/status/' . $loai['id']) ?>" method="post" style="display:inline;">
                                            <button type="submit" class="btn btn-warning btn-sm me-1" title="Đổi trạng thái">
                                                <i class="bx bx-refresh"></i>
                                            </button>
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-delete" 
                                           data-id="<?= $loai['id'] ?>" data-name="<?= $loai['ten_loai_nguoi_dung'] ?>" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa loại người dùng "<span id="delete-item-name"></span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="delete-form" method="post" style="display: inline;">
                    <button type="submit" id="btn-confirm-delete" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- DataTables JS -->
<script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        // DataTable
        $('#dataTable').DataTable({
            language: {
                url: '<?= base_url('assets/plugins/datatable/locale/vi.json') ?>'
            }
        });
        
        // Xử lý checkbox select all
        $('#select-all').on('click', function() {
            $('.checkbox-item').prop('checked', $(this).prop('checked'));
        });
        
        // Xử lý button xóa nhiều
        $('#delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn xóa các loại người dùng đã chọn?')) {
                    $('#form-delete-multiple').attr('action', '<?= site_url('loainguoidung/deleteMultiple') ?>');
                    $('#form-delete-multiple').submit();
                }
            } else {
                alert('Vui lòng chọn ít nhất một loại người dùng để xóa');
            }
        });
        
        // Xử lý button đổi trạng thái nhiều
        $('#status-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn đổi trạng thái các loại người dùng đã chọn?')) {
                    // Copy các checkbox đã chọn sang form đổi trạng thái
                    $('.checkbox-item:checked').clone().appendTo('#form-status-multiple');
                    $('#form-status-multiple').submit();
                }
            } else {
                alert('Vui lòng chọn ít nhất một loại người dùng để đổi trạng thái');
            }
        });
        
        // Xử lý modal xóa
        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#delete-form').attr('action', '<?= site_url('loainguoidung/delete/') ?>' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?> 