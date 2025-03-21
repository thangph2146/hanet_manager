<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= loainguoidung_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH LOẠI NGƯỜI DÙNG ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Danh sách Loại Người Dùng đã xóa',
    'dashboard_url' => site_url('loainguoidung/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Loại Người Dùng', 'url' => site_url('loainguoidung')],
        ['title' => 'Danh sách đã xóa', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/loainguoidung'), 'title' => 'Quay lại danh sách']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <?= form_open("loainguoidung/restoreMultiple", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="restore-selected" class="btn btn-success me-2">Khôi phục mục đã chọn</button>
                <?= form_close() ?>
                
                <?= form_open("loainguoidung/permanentDeleteMultiple", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="permanent-delete-selected" class="btn btn-danger">Xóa vĩnh viễn mục đã chọn</button>
                <?= form_close() ?>
            </div>
            
            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="select-all" /></th>
                        <th width="20%">Tên loại</th>
                        <th width="30%">Mô tả</th>
                        <th width="15%">Trạng thái</th>
                        <th width="15%">Ngày xóa</th>
                        <th width="15%">Hành động</th>
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
                                <td><?= (new DateTime($loai['deleted_at']))->format('d/m/Y') ?></td>
                                <td>
                                    <div class="d-flex">
                                        <form action="<?= site_url('loainguoidung/restore/' . $loai['id']) ?>" method="post" style="display:inline;">
                                            <button type="submit" class="btn btn-success btn-sm me-1" title="Khôi phục">
                                                <i class="bx bx-recycle"></i>
                                            </button>
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-permanent-delete" 
                                           data-id="<?= $loai['id'] ?>" data-name="<?= $loai['ten_loai_nguoi_dung'] ?>" title="Xóa vĩnh viễn">
                                            <i class="bx bx-trash-alt"></i>
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

<!-- Modal Xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa vĩnh viễn loại người dùng "<span id="delete-item-name"></span>"? Hành động này không thể hoàn tác.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="btn-confirm-permanent-delete" class="btn btn-danger">Xóa vĩnh viễn</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= loainguoidung_js('table') ?>

<script>
    $(document).ready(function() {        
        // Xử lý button khôi phục nhiều
        $('#restore-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn khôi phục các loại người dùng đã chọn?')) {
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
                alert('Vui lòng chọn ít nhất một loại người dùng để khôi phục');
            }
        });
        
        // Xử lý button xóa vĩnh viễn nhiều
        $('#permanent-delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn các loại người dùng đã chọn? Hành động này không thể hoàn tác.')) {
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
                alert('Vui lòng chọn ít nhất một loại người dùng để xóa vĩnh viễn');
            }
        });
        
        // Xử lý modal xóa vĩnh viễn
        $('.btn-permanent-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#btn-confirm-permanent-delete').attr('href', '<?= site_url('loainguoidung/permanentDelete/') ?>' + id);
            $('#permanentDeleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?> 