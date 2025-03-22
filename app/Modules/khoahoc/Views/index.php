<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= khoahoc_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>QUẢN LÝ KHÓA HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Khóa Học',
	'dashboard_url' => site_url('khoahoc/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Khóa Học', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/khoahoc/new'), 'title' => 'Tạo Khóa Học Mới'],
		['url' => site_url('/khoahoc/listdeleted'), 'title' => 'Danh sách Khóa Học đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <?= form_open("khoahoc/delete", ['id' => 'form-delete-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="delete-selected" class="btn btn-danger me-2">Xóa mục đã chọn</button>
                <?= form_close() ?>
                
                <?= form_open("khoahoc/statusMultiple", ['id' => 'form-status-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="status-selected" class="btn btn-warning">Đổi trạng thái mục đã chọn</button>
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
                                        <a href="<?= site_url('khoahoc/edit/' . $khoa['id']) ?>" class="btn btn-primary btn-sm me-1" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-item me-1" data-id="<?= $khoa['id'] ?>" title="Xóa">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                        <button type="button" class="btn <?= $khoa['status'] ? 'btn-warning' : 'btn-success' ?> btn-sm status-item" 
                                                data-id="<?= $khoa['id'] ?>" title="<?= $khoa['status'] ? 'Vô hiệu hóa' : 'Kích hoạt' ?>">
                                            <i class="bx <?= $khoa['status'] ? 'bx-shield-x' : 'bx-check-shield' ?>"></i>
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

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa khóa học này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận đổi trạng thái -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Xác nhận đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn thay đổi trạng thái của khóa học này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="confirmStatus" class="btn btn-primary">Đổi trạng thái</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa nhiều mục -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-labelledby="deleteMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMultipleModalLabel">Xác nhận xóa nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa các khóa học đã chọn không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirmDeleteMultiple" class="btn btn-danger">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận đổi trạng thái nhiều mục -->
<div class="modal fade" id="statusMultipleModal" tabindex="-1" aria-labelledby="statusMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusMultipleModalLabel">Xác nhận đổi trạng thái nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn đổi trạng thái các khóa học đã chọn không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirmStatusMultiple" class="btn btn-primary">Đổi trạng thái</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= khoahoc_js('table') ?>

<script>
    $(document).ready(function() {
        // Xử lý button xóa nhiều
        $('#delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn xóa các khóa học đã chọn?')) {
                    // Tạo form tạm thời chứa các checkbox đã chọn
                    var tempForm = $('#form-delete-multiple');
                    
                    // Xóa form cũ và tạo form mới
                    tempForm.empty();
                    
                    // Thêm các checkbox đã chọn vào form
                    $('.checkbox-item:checked').each(function() {
                        var input = $('<input>').attr({
                            type: 'hidden',
                            name: 'selected_ids[]',
                            value: $(this).val()
                        });
                        tempForm.append(input);
                    });
                    
                    // Cập nhật action và submit form
                    tempForm.attr('action', '<?= site_url('khoahoc/deleteMultiple') ?>');
                    tempForm.submit();
                }
            } else {
                alert('Vui lòng chọn ít nhất một khóa học để xóa');
            }
        });
        
        // Xử lý button đổi trạng thái nhiều
        $('#status-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn đổi trạng thái các khóa học đã chọn?')) {
                    // Tạo form tạm thời chứa các checkbox đã chọn
                    var tempForm = $('#form-status-multiple');
                    
                    // Xóa form cũ và tạo form mới
                    tempForm.empty();
                    
                    // Thêm các checkbox đã chọn vào form
                    $('.checkbox-item:checked').each(function() {
                        var input = $('<input>').attr({
                            type: 'hidden',
                            name: 'selected_ids[]',
                            value: $(this).val()
                        });
                        tempForm.append(input);
                    });
                    
                    // Submit form
                    tempForm.submit();
                }
            } else {
                alert('Vui lòng chọn ít nhất một khóa học để đổi trạng thái');
            }
        });
        
        // Xử lý modal xóa
        $('.delete-item').on('click', function() {
            var id = $(this).data('id');
            $('#confirmDelete').attr('href', '<?= site_url('khoahoc/delete/') ?>' + id);
            $('#deleteModal').modal('show');
        });

        // Xử lý modal đổi trạng thái
        $('.status-item').on('click', function() {
            var id = $(this).data('id');
            $('#confirmStatus').attr('href', '<?= site_url('khoahoc/status/') ?>' + id);
            $('#statusModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?> 