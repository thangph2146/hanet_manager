<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= namhoc_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>QUẢN LÝ NĂM HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Năm Học',
	'dashboard_url' => site_url('namhoc/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Năm Học', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/namhoc/new'), 'title' => 'Tạo Năm Học Mới'],
		['url' => site_url('/namhoc/listdeleted'), 'title' => 'Danh sách Năm Học đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<!-- Debug thông tin -->
<?php if (ENVIRONMENT === 'development'): ?>
<div class="alert alert-info">
    <p><strong>Debug:</strong> Số lượng năm học: <?= count($nam_hoc ?? []) ?></p>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div class="col-12 mb-3">
                <form action="<?= site_url('namhoc/bulkDelete') ?>" method="post" id="form-delete-multiple" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="button" id="delete-selected" class="btn btn-danger me-2">Xóa mục đã chọn</button>
                </form>
                
                <?= form_open("namhoc/statusMultiple", ['id' => 'form-status-multiple', 'class' => 'd-inline']) ?>
                <?= csrf_field() ?>
                <button type="button" id="status-selected" class="btn btn-warning">Đổi trạng thái mục đã chọn</button>
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
                        <th width="10%">Ngày tạo</th>
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
                                <td><?= is_object($item['created_at']) ? $item['created_at']->humanize() : $item['created_at'] ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?= site_url('namhoc/edit/' . $item['id']) ?>" class="btn btn-primary btn-sm me-1" title="Sửa">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="<?= site_url('namhoc/status/' . $item['id']) ?>" method="post" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-warning btn-sm me-1" title="Đổi trạng thái">
                                                <i class="bx bx-refresh"></i>
                                            </button>
                                        </form>
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-delete" 
                                           data-id="<?= $item['id'] ?>" data-name="<?= $item['ten_nam_hoc'] ?>" title="Xóa">
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

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa năm học "<span id="delete-item-name"></span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="delete-form" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" id="btn-confirm-delete" class="btn btn-danger">Xóa</button>
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
        // Xử lý nút xóa mục đã chọn
        $('#delete-selected').on('click', function() {
            // Lấy các checkbox đã chọn
            var checkboxes = $('.checkbox-item:checked');
            var count = checkboxes.length;
            
            console.log('Số checkbox đã chọn:', count);
            
            // Nếu không có mục nào được chọn
            if (count === 0) {
                alert('Vui lòng chọn ít nhất một năm học để xóa');
                return;
            }
            
            // Hiển thị thông báo xác nhận
            if (confirm('Bạn có chắc chắn muốn xóa ' + count + ' năm học đã chọn?')) {
                // Tạo form mới để submit
                var form = $('<form></form>');
                form.attr('method', 'POST');
                form.attr('action', '<?= site_url('namhoc/bulkDelete') ?>');
                
                // Thêm CSRF token
                form.append($('<input>').attr({
                    type: 'hidden',
                    name: '<?= csrf_token() ?>',
                    value: '<?= csrf_hash() ?>'
                }));
                
                // Thêm các ID đã chọn vào form
                checkboxes.each(function() {
                    var id = $(this).val();
                    console.log('ID đã chọn:', id);
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: 'selected_ids[]',
                        value: id
                    }));
                });
                
                // Thêm form vào body và submit
                $('body').append(form);
                form.submit();
            }
        });
        
        // Xử lý button đổi trạng thái nhiều
        $('#status-selected').on('click', function() {
            // Lấy các checkbox đã chọn
            var checkboxes = $('.checkbox-item:checked');
            var count = checkboxes.length;
            
            console.log('Số checkbox đã chọn:', count);
            
            // Nếu không có mục nào được chọn
            if (count === 0) {
                alert('Vui lòng chọn ít nhất một năm học để đổi trạng thái');
                return;
            }
            
            // Hiển thị thông báo xác nhận
            if (confirm('Bạn có chắc chắn muốn đổi trạng thái ' + count + ' năm học đã chọn?')) {
                // Tạo form mới để submit
                var form = $('<form></form>');
                form.attr('method', 'POST');
                form.attr('action', '<?= site_url('namhoc/statusMultiple') ?>');
                
                // Thêm CSRF token
                form.append($('<input>').attr({
                    type: 'hidden',
                    name: '<?= csrf_token() ?>',
                    value: '<?= csrf_hash() ?>'
                }));
                
                // Thêm các ID đã chọn vào form
                checkboxes.each(function() {
                    var id = $(this).val();
                    console.log('ID đã chọn cho đổi trạng thái:', id);
                    form.append($('<input>').attr({
                        type: 'hidden',
                        name: 'selected_ids[]',
                        value: id
                    }));
                });
                
                // Thêm form vào body và submit
                $('body').append(form);
                form.submit();
            }
        });
        
        // Xử lý modal xóa
        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#delete-form').attr('action', '<?= site_url('namhoc/delete/') ?>' + id);
            $('#deleteModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?> 