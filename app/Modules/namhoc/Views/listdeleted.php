<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= namhoc_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH NĂM HỌC ĐÃ XÓA<?= $this->endSection() ?>

<!-- Thêm CSRF token meta tag -->
<?= $this->section('meta') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách Năm Học đã xóa',
	'dashboard_url' => site_url('namhoc/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Năm Học', 'url' => site_url('namhoc')],
		['title' => 'Danh sách Năm Học đã xóa', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/namhoc'), 'title' => 'Quay lại danh sách']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <?= form_open("namhoc/restore", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="restore-selected" class="btn btn-primary me-2">Khôi phục mục đã chọn</button>
                <?= form_close() ?>
                
                <?= form_open("namhoc/permanentDelete", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="permanent-delete-selected" class="btn btn-danger">Xóa vĩnh viễn mục đã chọn</button>
                <?= form_close() ?>
            </div>
            
            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="select-all" /></th>
                        <th width="20%">Tên năm học</th>
                        <th width="15%">Ngày bắt đầu</th>
                        <th width="15%">Ngày kết thúc</th>
                        <th width="15%">Trạng thái</th>
                        <th width="10%">Ngày xóa</th>
                        <th width="10%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($nam_hoc)): ?>
                        <?php foreach ($nam_hoc as $item): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $item['id'] ?>" class="checkbox-item" />
                                </td>
                                <td><?= $item['ten_nam_hoc'] ?></td>
                                <td><?= $item['ngay_bat_dau'] ?></td>
                                <td><?= $item['ngay_ket_thuc'] ?></td>
                                <td><?= $item['status'] ?></td>
                                <td><?= is_object($item['deleted_at']) ? $item['deleted_at']->humanize() : (is_string($item['deleted_at']) ? date('d/m/Y', strtotime($item['deleted_at'])) : $item['deleted_at']) ?></td>
                                <td>
                                    <div class="d-flex">
                                        <form action="<?= site_url('namhoc/restore/' . $item['id']) ?>" method="post" style="display:inline;">
                                            <button type="submit" class="btn btn-primary btn-sm me-1" title="Khôi phục">
                                                <i class="bx bx-revision"></i>
                                            </button>
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-permanent-delete" 
                                           data-id="<?= $item['id'] ?>" data-name="<?= $item['ten_nam_hoc'] ?>" title="Xóa vĩnh viễn">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
                Bạn có chắc chắn muốn xóa vĩnh viễn năm học "<span id="permanent-delete-item-name"></span>"?
                <div class="text-danger mt-2">Hành động này không thể hoàn tác!</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="permanent-delete-form" method="post" style="display: inline;">
                    <button type="submit" id="btn-confirm-permanent-delete" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= namhoc_js('table') ?>

<script>
    $(document).ready(function() {
        // Xử lý button khôi phục nhiều
        $('#restore-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn khôi phục các năm học đã chọn?')) {
                    // Tạo form tạm thời chứa các checkbox đã chọn
                    var tempForm = $('#form-restore-multiple');
                    
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
                alert('Vui lòng chọn ít nhất một năm học để khôi phục');
            }
        });
        
        // Xử lý button xóa vĩnh viễn nhiều
        $('#permanent-delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn các năm học đã chọn? Hành động này không thể hoàn tác!')) {
                    // Tạo form tạm thời chứa các checkbox đã chọn
                    var tempForm = $('#form-permanent-delete-multiple');
                    
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
                alert('Vui lòng chọn ít nhất một năm học để xóa vĩnh viễn');
            }
        });
        
        // Xử lý modal xóa vĩnh viễn
        $('.btn-permanent-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#permanent-delete-item-name').text(name);
            $('#permanent-delete-form').attr('action', '<?= site_url('namhoc/permanentDelete/') ?>' + id);
            $('#permanentDeleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?> 