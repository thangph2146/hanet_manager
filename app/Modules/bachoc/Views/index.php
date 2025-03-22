    <?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= bachoc_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>QUẢN LÝ BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Bậc Học',
	'dashboard_url' => site_url('dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Bậc Học', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/bachoc/new'), 'title' => 'Tạo Bậc Học Mới'],
		['url' => site_url('/bachoc/listdeleted'), 'title' => 'Danh sách Bậc Học đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <?= form_open("bachoc/deleteMultiple", ['id' => 'form-delete-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="delete-selected" class="btn btn-danger me-2">Xóa mục đã chọn</button>
                <?= form_close() ?>
            </div>
            
            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="select-all" /></th>
                        <th width="40%">Tên bậc học</th>
                        <th width="20%">Mã bậc học</th>
                        <th width="15%">Trạng thái</th>
                        <th width="20%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bac_hoc)): ?>
                        <?php foreach ($bac_hoc as $bac): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $bac['id'] ?>" class="checkbox-item" />
                                </td>
                                <td><?= $bac['ten_bac_hoc'] ?></td>
                                <td><?= $bac['ma_bac_hoc'] ?></td>
                                <td><?= $bac['status'] ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?= site_url('bachoc/edit/' . $bac['id']) ?>" class="btn btn-primary btn-sm me-1" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-item me-1" data-id="<?= $bac['id'] ?>" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                        <button type="button" class="btn <?= strpos($bac['status'], 'Hoạt động') !== false ? 'btn-warning' : 'btn-success' ?> btn-sm status-item" 
                                                data-id="<?= $bac['id'] ?>" title="<?= strpos($bac['status'], 'Hoạt động') !== false ? 'Vô hiệu hóa' : 'Kích hoạt' ?>">
                                            <i class="bx <?= strpos($bac['status'], 'Hoạt động') !== false ? 'bx-shield-x' : 'bx-check-shield' ?>"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Không có dữ liệu</td>
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
                Bạn có chắc chắn muốn xóa bậc học này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa nhiều mục -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa các bậc học đã chọn không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-multiple">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận đổi trạng thái -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận thay đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn thay đổi trạng thái của bậc học này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="confirm-status">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= bachoc_js('table') ?>
<script>
    $(document).ready(function() {
        // Xử lý xóa bậc học
        let deleteId = null;
        
        $('.delete-item').on('click', function() {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });
        
        $('#confirm-delete').on('click', function() {
            if (deleteId) {
                window.location.href = '<?= site_url('bachoc/delete/') ?>' + deleteId;
            }
            $('#deleteModal').modal('hide');
        });
        
        // Xử lý xóa nhiều bậc học
        $('#delete-selected').on('click', function() {
            const selectedItems = $('.checkbox-item:checked');
            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất một mục để xóa');
                return;
            }
            
            $('#deleteMultipleModal').modal('show');
        });
        
        $('#confirm-delete-multiple').on('click', function() {
            $('#form-delete-multiple').submit();
            $('#deleteMultipleModal').modal('hide');
        });
        
        // Xử lý thay đổi trạng thái
        let statusId = null;
        
        $('.status-item').on('click', function() {
            statusId = $(this).data('id');
            $('#statusModal').modal('show');
        });
        
        $('#confirm-status').on('click', function() {
            if (statusId) {
                window.location.href = '<?= site_url('bachoc/toggleStatus/') ?>' + statusId;
            }
            $('#statusModal').modal('hide');
        });
    });
</script>
<?= $this->endSection() ?> 