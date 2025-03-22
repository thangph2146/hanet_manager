<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= bachoc_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH BẬC HỌC ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách Bậc Học đã xóa',
	'dashboard_url' => site_url('dashboard'),
	'breadcrumbs' => [
		['url' => site_url('bachoc'), 'title' => 'Quản lý Bậc Học'],
		['title' => 'Danh sách đã xóa', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/bachoc'), 'title' => 'Quay lại danh sách Bậc Học']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <?= form_open("bachoc/restoreMultiple", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="restore-selected" class="btn btn-success me-2">Khôi phục mục đã chọn</button>
                <?= form_close() ?>
                
                <?= form_open("bachoc/permanentDeleteMultiple", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="permanent-delete-selected" class="btn btn-danger">Xóa vĩnh viễn mục đã chọn</button>
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
                                        <button type="button" class="btn btn-success btn-sm restore-item me-1" data-id="<?= $bac['id'] ?>" title="Khôi phục">
                                            <i class="bx bx-reset"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm permanent-delete-item" data-id="<?= $bac['id'] ?>" title="Xóa vĩnh viễn">
                                            <i class="bx bx-trash"></i>
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

<!-- Modal Xác nhận khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn khôi phục bậc học này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="confirm-restore">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận khôi phục nhiều mục -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn khôi phục các bậc học đã chọn không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="confirm-restore-multiple">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger fw-bold">CẢNH BÁO: Hành động này không thể hoàn tác!</p>
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn bậc học này không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-permanent-delete">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn nhiều mục -->
<div class="modal fade" id="permanentDeleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger fw-bold">CẢNH BÁO: Hành động này không thể hoàn tác!</p>
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn các bậc học đã chọn không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-permanent-delete-multiple">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= bachoc_js('table') ?>
<script>
    $(document).ready(function() {
        // Xử lý khôi phục bậc học
        let restoreId = null;
        
        $('.restore-item').on('click', function() {
            restoreId = $(this).data('id');
            $('#restoreModal').modal('show');
        });
        
        $('#confirm-restore').on('click', function() {
            if (restoreId) {
                window.location.href = '<?= site_url('bachoc/restore/') ?>' + restoreId;
            }
            $('#restoreModal').modal('hide');
        });
        
        // Xử lý khôi phục nhiều bậc học
        $('#restore-selected').on('click', function() {
            const selectedItems = $('.checkbox-item:checked');
            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất một mục để khôi phục');
                return;
            }
            
            $('#restoreMultipleModal').modal('show');
        });
        
        $('#confirm-restore-multiple').on('click', function() {
            $('#form-restore-multiple').submit();
            $('#restoreMultipleModal').modal('hide');
        });
        
        // Xử lý xóa vĩnh viễn bậc học
        let permanentDeleteId = null;
        
        $('.permanent-delete-item').on('click', function() {
            permanentDeleteId = $(this).data('id');
            $('#permanentDeleteModal').modal('show');
        });
        
        $('#confirm-permanent-delete').on('click', function() {
            if (permanentDeleteId) {
                window.location.href = '<?= site_url('bachoc/permanentDelete/') ?>' + permanentDeleteId;
            }
            $('#permanentDeleteModal').modal('hide');
        });
        
        // Xử lý xóa vĩnh viễn nhiều bậc học
        $('#permanent-delete-selected').on('click', function() {
            const selectedItems = $('.checkbox-item:checked');
            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất một mục để xóa vĩnh viễn');
                return;
            }
            
            $('#permanentDeleteMultipleModal').modal('show');
        });
        
        $('#confirm-permanent-delete-multiple').on('click', function() {
            $('#form-permanent-delete-multiple').submit();
            $('#permanentDeleteMultipleModal').modal('hide');
        });
    });
</script>
<?= $this->endSection() ?> 