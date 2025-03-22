<?= $this->extend('layouts/default') ?>

<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= bachoc_css('table') ?>
<?= $this->endSection() ?>

<?= $this->section('title') ?>DANH SÁCH BẬC HỌC ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Danh sách Bậc học đã xóa',
    'dashboard_url' => site_url('bachoc/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc học', 'url' => site_url('bachoc')],
        ['title' => 'Danh sách đã xóa', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/bachoc'), 'title' => 'Quay lại danh sách']
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
                        <th width="20%">Tên bậc học</th>
                        <th width="20%">Mã bậc học</th>
                        <th width="15%">Trạng thái</th>
                        <th width="15%">Ngày xóa</th>
                        <th width="15%">Hành động</th>
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
                                <td><?= (new DateTime($bac['deleted_at']))->format('d/m/Y') ?></td>
                                <td>
                                    <div class="d-flex">
                                        <form action="<?= site_url('bachoc/restore/' . $bac['id']) ?>" method="post" style="display:inline;">
                                            <button type="submit" class="btn btn-success btn-sm me-1" title="Khôi phục">
                                                <i class="bx bx-recycle"></i>
                                            </button>
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-permanent-delete" 
                                           data-id="<?= $bac['id'] ?>" data-name="<?= $bac['ten_bac_hoc'] ?>" title="Xóa vĩnh viễn">
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
                Bạn có chắc chắn muốn xóa vĩnh viễn bậc học "<span id="permanent-delete-item-name"></span>"?<br>
                <strong class="text-danger">Lưu ý: Hành động này không thể khôi phục!</strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="permanent-delete-form" method="post" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= bachoc_js('table') ?>

<script>
    $(document).ready(function() {
        // Handle restore multiple button
        $('#restore-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn khôi phục các bậc học đã chọn?')) {
                    var tempForm = $('#form-restore-multiple');
                    tempForm.empty();
                    
                    $('.checkbox-item:checked').each(function() {
                        var input = $('<input>').attr({
                            type: 'hidden',
                            name: 'selected_ids[]',
                            value: $(this).val()
                        });
                        tempForm.append(input);
                    });
                    
                    tempForm.submit();
                }
            } else {
                alert('Vui lòng chọn ít nhất một bậc học để khôi phục');
            }
        });
        
        // Handle permanent delete multiple button
        $('#permanent-delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn các bậc học đã chọn?\nLưu ý: Hành động này không thể khôi phục!')) {
                    var tempForm = $('#form-permanent-delete-multiple');
                    tempForm.empty();
                    
                    $('.checkbox-item:checked').each(function() {
                        var input = $('<input>').attr({
                            type: 'hidden',
                            name: 'selected_ids[]',
                            value: $(this).val()
                        });
                        tempForm.append(input);
                    });
                    
                    tempForm.submit();
                }
            } else {
                alert('Vui lòng chọn ít nhất một bậc học để xóa vĩnh viễn');
            }
        });
        
        // Handle permanent delete modal
        $('.btn-permanent-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#permanent-delete-item-name').text(name);
            $('#permanent-delete-form').attr('action', '<?= site_url('bachoc/permanentDelete/') ?>' + id);
            $('#permanentDeleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?>