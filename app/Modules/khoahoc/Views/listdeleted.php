<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= khoahoc_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH KHÓA HỌC ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách Khóa Học đã xóa',
	'dashboard_url' => site_url('khoahoc/dashboard'),
	'breadcrumbs' => [
		['url' => site_url('khoahoc'), 'title' => 'Quản lý Khóa Học'],
		['title' => 'Danh sách đã xóa', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/khoahoc'), 'title' => 'Quay lại danh sách Khóa Học']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <?= form_open("khoahoc/restoreMultiple", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="restore-selected" class="btn btn-success me-2">Khôi phục mục đã chọn</button>
                <?= form_close() ?>
                
                <?= form_open("khoahoc/permanentDeleteMultiple", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="permanent-delete-selected" class="btn btn-danger">Xóa vĩnh viễn mục đã chọn</button>
                <?= form_close() ?>
            </div>
            
            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="select-all" /></th>
                        <th width="30%">Tên khóa học</th>
                        <th width="25%">Thời gian</th>
                        <th width="20%">Phòng/Khoa</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($khoa_hoc)): ?>
                        <?php foreach ($khoa_hoc as $khoa): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $khoa['id'] ?>" class="checkbox-item" />
                                </td>
                                <td><?= $khoa['ten_khoa_hoc'] ?></td>
                                <td><?= $khoa['nam_hoc'] ?></td>
                                <td>
                                    <?php if (isset($khoa['phong_khoa_id']) && !empty($khoa['phong_khoa_id'])): ?>
                                        <?php 
                                        // Nếu có thông tin chi tiết về phòng khoa, hiển thị tên thay vì chỉ hiển thị ID
                                        // Hiện tại chỉ hiển thị ID và có thể cải thiện sau
                                        echo 'Phòng/Khoa ID: ' . $khoa['phong_khoa_id'];
                                        ?>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa xác định</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $khoa['status'] ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-success btn-sm restore-item me-1" data-id="<?= $khoa['id'] ?>" title="Khôi phục">
                                            <i class="bx bx-reset"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm permanent-delete-item" data-id="<?= $khoa['id'] ?>" title="Xóa vĩnh viễn">
                                            <i class="bx bx-trash"></i>
                                        </button>
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

<!-- Modal Xác nhận khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn khôi phục khóa học này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="confirmRestore" class="btn btn-success">Khôi phục</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-labelledby="permanentDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permanentDeleteModalLabel">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa vĩnh viễn khóa học này không? Hành động này không thể hoàn tác.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="confirmPermanentDelete" class="btn btn-danger">Xóa vĩnh viễn</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận khôi phục nhiều mục -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-labelledby="restoreMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreMultipleModalLabel">Xác nhận khôi phục nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn khôi phục các khóa học đã chọn không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirmRestoreMultiple" class="btn btn-success">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn nhiều mục -->
<div class="modal fade" id="permanentDeleteMultipleModal" tabindex="-1" aria-labelledby="permanentDeleteMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permanentDeleteMultipleModalLabel">Xác nhận xóa vĩnh viễn nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa vĩnh viễn các khóa học đã chọn không? Hành động này không thể hoàn tác.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirmPermanentDeleteMultiple" class="btn btn-danger">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= khoahoc_js('table') ?>

<script>
    $(document).ready(function() {
        // Chọn tất cả
        $('#select-all').on('change', function() {
            $('.checkbox-item').prop('checked', $(this).is(':checked'));
        });
        
        // Cập nhật chọn tất cả khi các checkbox thay đổi
        $('.checkbox-item').on('change', function() {
            var allChecked = $('.checkbox-item:checked').length === $('.checkbox-item').length;
            $('#select-all').prop('checked', allChecked);
        });
        
        // Xử lý sự kiện khôi phục hàng loạt
        $('#restore-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#restoreMultipleModal').modal('show');
            } else {
                alert('Vui lòng chọn ít nhất một khóa học để khôi phục');
            }
        });
        
        $('#confirmRestoreMultiple').on('click', function() {
            var tempForm = $('#form-restore-multiple');
            $('.checkbox-item:checked').each(function() {
                tempForm.append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
            });
            tempForm.submit();
        });
        
        // Xử lý sự kiện xóa vĩnh viễn hàng loạt
        $('#permanent-delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#permanentDeleteMultipleModal').modal('show');
            } else {
                alert('Vui lòng chọn ít nhất một khóa học để xóa vĩnh viễn');
            }
        });
        
        $('#confirmPermanentDeleteMultiple').on('click', function() {
            var tempForm = $('#form-permanent-delete-multiple');
            $('.checkbox-item:checked').each(function() {
                tempForm.append('<input type="hidden" name="ids[]" value="' + $(this).val() + '">');
            });
            tempForm.submit();
        });
        
        // Xử lý modal khôi phục
        $('.restore-item').on('click', function() {
            var id = $(this).data('id');
            $('#confirmRestore').attr('href', '<?= site_url('khoahoc/restore/') ?>' + id);
            $('#restoreModal').modal('show');
        });
        
        // Xử lý modal xóa vĩnh viễn
        $('.permanent-delete-item').on('click', function() {
            var id = $(this).data('id');
            $('#confirmPermanentDelete').attr('href', '<?= site_url('khoahoc/permanentDelete/') ?>' + id);
            $('#permanentDeleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?> 