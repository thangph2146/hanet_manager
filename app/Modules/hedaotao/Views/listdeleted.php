<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= hedaotao_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>HỆ ĐÀO TẠO ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Hệ Đào Tạo đã xóa',
	'dashboard_url' => site_url('hedaotao/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Hệ Đào Tạo', 'url' => site_url('hedaotao')],
		['title' => 'Hệ Đào Tạo đã xóa', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/hedaotao'), 'title' => 'Quay lại danh sách']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <?= form_open("hedaotao/restoreMultiple", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="restore-selected" class="btn btn-info me-2">Khôi phục mục đã chọn</button>
                <?= form_close() ?>
                
                <?= form_open("hedaotao/permanentDeleteMultiple", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="permanent-delete-selected" class="btn btn-danger">Xóa vĩnh viễn mục đã chọn</button>
                <?= form_close() ?>
            </div>
            
            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="select-all" /></th>
                        <th width="20%">Tên hệ đào tạo</th>
                        <th width="15%">Mã hệ đào tạo</th>
                        <th width="15%">Trạng thái</th>
                        <th width="15%">Ngày xóa</th>
                        <th width="10%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($he_dao_tao)): ?>
                        <?php foreach ($he_dao_tao as $hdt): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $hdt->getId() ?>" class="checkbox-item" />
                                </td>
                                <td><?= esc($hdt->getTenHeDaoTao()) ?></td>
                                <td><?= esc($hdt->getMaHeDaoTao()) ?></td>
                                <td>
                                    <?= $hdt->isActive() 
                                        ? '<span class="badge bg-success">Hoạt động</span>' 
                                        : '<span class="badge bg-warning">Không hoạt động</span>' 
                                    ?>
                                </td>
                                <td><?= $hdt->deleted_at ? (new DateTime($hdt->deleted_at))->format('d/m/Y H:i:s') : '' ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?= site_url('hedaotao/restore/' . $hdt->getId()) ?>" class="btn btn-info btn-sm me-1" title="Khôi phục">
                                            <i class="bx bx-revision"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-permanent-delete" 
                                           data-id="<?= $hdt->getId() ?>" data-name="<?= esc($hdt->getTenHeDaoTao()) ?>" title="Xóa vĩnh viễn">
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
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa vĩnh viễn hệ đào tạo "<span id="delete-item-name"></span>"?<br>
                <strong class="text-danger">Lưu ý: Dữ liệu đã xóa vĩnh viễn không thể khôi phục!</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="delete-form" method="post" style="display: inline;">
                    <button type="submit" id="btn-confirm-delete" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= hedaotao_js('table') ?>

<script>
    $(document).ready(function() {
        // Xử lý button khôi phục nhiều
        $('#restore-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn khôi phục các hệ đào tạo đã chọn?')) {
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
                alert('Vui lòng chọn ít nhất một hệ đào tạo để khôi phục');
            }
        });
        
        // Xử lý button xóa vĩnh viễn nhiều
        $('#permanent-delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn các hệ đào tạo đã chọn? Dữ liệu đã xóa vĩnh viễn không thể khôi phục!')) {
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
                alert('Vui lòng chọn ít nhất một hệ đào tạo để xóa vĩnh viễn');
            }
        });
        
        // Xử lý modal xóa vĩnh viễn
        $('.btn-permanent-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#delete-form').attr('action', '<?= site_url('hedaotao/permanentDelete/') ?>' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?> 