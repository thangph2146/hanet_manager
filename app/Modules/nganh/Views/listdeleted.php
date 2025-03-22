<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= nganh_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÙNG RÁC - NGÀNH<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thùng rác - Ngành',
	'dashboard_url' => site_url('nganh/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Ngành', 'url' => site_url('nganh')],
		['title' => 'Thùng rác', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/nganh'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách ngành đã xóa</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
                <i class='bx bx-refresh'></i> Làm mới
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 bg-light border-bottom">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <?= form_open("nganh/restoreMultiple", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="restore-selected" class="btn btn-success btn-sm me-2" disabled>
                        <i class='bx bx-reset'></i> Khôi phục mục đã chọn
                    </button>
                    <?= form_close() ?>
                    
                    <?= form_open("nganh/permanentDeleteMultiple", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="permanent-delete-selected" class="btn btn-danger btn-sm" disabled>
                        <i class='bx bx-x-circle'></i> Xóa vĩnh viễn
                    </button>
                    <?= form_close() ?>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="table-search" placeholder="Tìm kiếm...">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="search-btn">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <div class="table-container">
                <table id="dataTable" class="table table-striped table-bordered table-hover m-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center align-middle">
                                <div class="form-check">
                                    <input type="checkbox" id="select-all" class="form-check-input cursor-pointer">
                                </div>
                            </th>
                            <th width="20%" class="align-middle">Mã ngành</th>
                            <th width="30%" class="align-middle">Tên ngành</th>
                            <th width="10%" class="align-middle">Phòng/Khoa</th>
                            <th width="10%" class="align-middle">Trạng thái</th>
                            <th width="10%" class="align-middle">Ngày xóa</th>
                            <th width="15%" class="text-center align-middle">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($nganh)): ?>
                            <?php foreach ($nganh as $item): ?>
                                <tr>
                                    <td class="text-center py-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="selected_ids[]" value="<?= $item->nganh_id ?>" class="form-check-input checkbox-item cursor-pointer">
                                        </div>
                                    </td>
                                    <td class="py-2"><?= esc($item->ma_nganh) ?></td>
                                    <td class="py-2"><?= esc($item->ten_nganh) ?></td>
                                    <td class="py-2">
                                        <?= $item->getPhongKhoaInfo() ?>
                                    </td>
                                    <td class="py-2">
                                        <?= $item->getStatusLabel() ?>
                                    </td>
                                    <td class="py-2">
                                        <?= $item->getDeletedAtFormatted() ?>
                                    </td>
                                    <td class="text-center py-2">
                                        <div class="d-flex justify-content-center gap-1">
                                            <form action="<?= site_url('nganh/restore/' . $item->nganh_id) ?>" method="post" style="display:inline;">
                                                <button type="submit" class="btn btn-success btn-sm" title="Khôi phục" data-bs-toggle="tooltip">
                                                    <i class="bx bx-reset"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm btn-permanent-delete" 
                                            data-id="<?= $item->nganh_id ?>" data-name="<?= $item->ten_nganh ?>" title="Xóa vĩnh viễn" data-bs-toggle="tooltip">
                                                <i class="bx bx-x-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    <div class="d-flex flex-column align-items-center py-3">
                                        <i class='bx bx-trash text-secondary mb-2' style="font-size: 2rem;"></i>
                                        <p class="mb-0">Thùng rác trống</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (!empty($nganh)): ?>
            <div class="card-footer d-flex justify-content-between align-items-center py-2">
                <div class="text-muted small">Hiển thị <span id="total-records"><?= count($nganh) ?></span> bản ghi</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Xác nhận khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class='bx bx-help-circle text-success' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục ngành:</p>
                <p class="text-center fw-bold" id="restore-item-name"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="restore-form" method="post" style="display: inline;">
                    <button type="submit" id="btn-confirm-restore" class="btn btn-success">Khôi phục</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class='bx bx-error-circle text-danger' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn ngành:</p>
                <p class="text-center fw-bold" id="permanent-delete-item-name"></p>
                <div class="alert alert-warning mt-3">
                    <i class='bx bx-info-circle'></i> Lưu ý: Hành động này không thể hoàn tác.
                </div>
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

<!-- Modal Xác nhận khôi phục nhiều -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class='bx bx-help-circle text-success' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục <span id="restore-count" class="fw-bold"></span> ngành đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-restore-multiple" class="btn btn-success">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn nhiều -->
<div class="modal fade" id="permanentDeleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class='bx bx-error-circle text-danger' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn <span id="permanent-delete-count" class="fw-bold"></span> ngành đã chọn?</p>
                <div class="alert alert-warning mt-3">
                    <i class='bx bx-info-circle'></i> Lưu ý: Hành động này không thể hoàn tác.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-permanent-delete-multiple" class="btn btn-danger">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Xử lý chọn tất cả checkbox
    $('#select-all').on('change', function() {
        $('.checkbox-item').prop('checked', $(this).prop('checked'));
        updateBulkActionButtons();
    });
    
    // Cập nhật trạng thái các nút hành động hàng loạt
    $('.checkbox-item').on('change', function() {
        updateBulkActionButtons();
    });
    
    function updateBulkActionButtons() {
        var count = $('.checkbox-item:checked').length;
        if (count > 0) {
            $('#restore-selected, #permanent-delete-selected').removeAttr('disabled');
        } else {
            $('#restore-selected, #permanent-delete-selected').attr('disabled', 'disabled');
        }
    }
    
    // Xử lý nút xóa vĩnh viễn
    $('.btn-permanent-delete').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#permanent-delete-item-name').text(name);
        $('#permanent-delete-form').attr('action', base_url + '/nganh/permanentDelete/' + id);
        $('#permanentDeleteModal').modal('show');
    });
    
    // Xử lý nút khôi phục nhiều
    $('#restore-selected').on('click', function() {
        var count = $('.checkbox-item:checked').length;
        $('#restore-count').text(count);
        $('#restoreMultipleModal').modal('show');
    });
    
    // Xử lý xác nhận khôi phục nhiều
    $('#confirm-restore-multiple').on('click', function() {
        var selectedIds = [];
        $('.checkbox-item:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length > 0) {
            // Thêm các ID đã chọn vào form
            $('#form-restore-multiple').html('');
            selectedIds.forEach(function(id) {
                $('#form-restore-multiple').append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
            });
            
            // Submit form
            $('#form-restore-multiple').submit();
        }
    });
    
    // Xử lý nút xóa vĩnh viễn nhiều
    $('#permanent-delete-selected').on('click', function() {
        var count = $('.checkbox-item:checked').length;
        $('#permanent-delete-count').text(count);
        $('#permanentDeleteMultipleModal').modal('show');
    });
    
    // Xử lý xác nhận xóa vĩnh viễn nhiều
    $('#confirm-permanent-delete-multiple').on('click', function() {
        var selectedIds = [];
        $('.checkbox-item:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length > 0) {
            // Thêm các ID đã chọn vào form
            $('#form-permanent-delete-multiple').html('');
            selectedIds.forEach(function(id) {
                $('#form-permanent-delete-multiple').append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
            });
            
            // Submit form
            $('#form-permanent-delete-multiple').submit();
        }
    });
    
    // Tìm kiếm
    $('#search-btn').on('click', function() {
        performSearch();
    });
    
    $('#table-search').on('keypress', function(e) {
        if (e.which === 13) {
            performSearch();
        }
    });
    
    function performSearch() {
        var searchTerm = $('#table-search').val().trim().toLowerCase();
        
        if (searchTerm === '') {
            // Hiển thị tất cả nếu không có từ khóa
            $('table tbody tr').show();
        } else {
            // Ẩn các dòng không khớp
            $('table tbody tr').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(searchTerm) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        }
        
        // Cập nhật số lượng bản ghi hiển thị
        $('#total-records').text($('table tbody tr:visible').length);
    }
    
    // Nút làm mới bảng
    $('#refresh-table').on('click', function() {
        location.reload();
    });
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection() ?> 