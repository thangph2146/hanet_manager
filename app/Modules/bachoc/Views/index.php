<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>QUẢN LÝ BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= bachoc_css('table') ?>
<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Quản lý Bậc học',
    'dashboard_url' => site_url('bachoc/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc học', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/bachoc/new'), 'title' => 'Tạo Bậc học mới'],
        ['url' => site_url('/bachoc/listdeleted'), 'title' => 'Danh sách Bậc học đã xóa']
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
                
                <?= form_open("bachoc/statusMultiple", ['id' => 'form-status-multiple', 'class' => 'd-inline']) ?>
                <button type="button" id="status-selected" class="btn btn-warning">Đổi trạng thái mục đã chọn</button>
                <?= form_close() ?>
            </div>
            
            <table id="dataTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%"><input type="checkbox" id="select-all" /></th>
                        <th width="20%">Tên bậc học</th>
                        <th width="20%">Mã bậc học</th>
                        <th width="15%">Trạng thái</th>
                        <th width="15%">Ngày tạo</th>
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
                                <td><?= (new DateTime($bac['created_at']))->format('d/m/Y') ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?= site_url('bachoc/edit/' . $bac['id']) ?>" class="btn btn-primary btn-sm me-1" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="<?= site_url('bachoc/status/' . $bac['id']) ?>" method="post" style="display:inline;">
                                            <button type="submit" class="btn btn-warning btn-sm me-1" title="Đổi trạng thái">
                                                <i class="bx bx-refresh"></i>
                                            </button>
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-delete" 
                                           data-id="<?= $bac['id'] ?>" data-name="<?= $bac['ten_bac_hoc'] ?>" title="Xóa">
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
                Bạn có chắc chắn muốn xóa bậc học "<span id="delete-item-name"></span>"?
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
<?= bachoc_js('table') ?>

<script>
    $(document).ready(function() {
        // Handle delete multiple button
        $('#delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn xóa các bậc học đã chọn?')) {
                    var tempForm = $('#form-delete-multiple');
                    tempForm.empty();
                    
                    $('.checkbox-item:checked').each(function() {
                        var input = $('<input>').attr({
                            type: 'hidden',
                            name: 'selected_ids[]',
                            value: $(this).val()
                        });
                        tempForm.append(input);
                    });
                    
                    tempForm.attr('action', '<?= site_url('bachoc/deleteMultiple') ?>');
                    tempForm.submit();
                }
            } else {
                alert('Vui lòng chọn ít nhất một bậc học để xóa');
            }
        });
        
        // Handle status multiple button
        $('#status-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                if (confirm('Bạn có chắc chắn muốn đổi trạng thái các bậc học đã chọn?')) {
                    var tempForm = $('#form-status-multiple');
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
                alert('Vui lòng chọn ít nhất một bậc học để đổi trạng thái');
            }
        });
        
        // Handle delete modal
        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#delete-form').attr('action', '<?= site_url('bachoc/delete/') ?>' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?>