<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= loaisukien_css('table') ?>
<?= loaisukien_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÙNG RÁC - LOẠI SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thùng rác - Loại Sự Kiện',
	'dashboard_url' => site_url('loaisukien/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Loại Sự Kiện', 'url' => site_url('loaisukien')],
		['title' => 'Thùng rác', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/loaisukien'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách loại sự kiện đã xóa</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
                <i class='bx bx-refresh'></i> Làm mới
            </button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-export'></i> Xuất
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" id="export-excel">Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="export-pdf">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 bg-light border-bottom">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <?= form_open("loaisukien/restoreMultiple", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="restore-selected" class="btn btn-success btn-sm me-2" disabled>
                        <i class='bx bx-revision'></i> Khôi phục mục đã chọn
                    </button>
                    <?= form_close() ?>
                    
                    <?= form_open("loaisukien/permanentDeleteMultiple", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="permanent-delete-selected" class="btn btn-danger btn-sm" disabled>
                        <i class='bx bx-trash-alt'></i> Xóa vĩnh viễn
                    </button>
                    <?= form_close() ?>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group search-box">
                        <input type="text" class="form-control form-control-sm" id="table-search" placeholder="Tìm kiếm...">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="search-btn">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <div class="table-container">
                <table id="dataTable" class="table table-striped table-hover m-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center align-middle">
                                <div class="form-check">
                                    <input type="checkbox" id="select-all" class="form-check-input cursor-pointer">
                                </div>
                            </th>
                            <th width="15%" class="align-middle">Mã loại</th>
                            <th width="40%" class="align-middle">Tên loại sự kiện</th>
                            <th width="15%" class="align-middle">Trạng thái</th>
                            <th width="10%" class="align-middle">Ngày xóa</th>
                            <th width="15%" class="text-center align-middle">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($loaisukien)): ?>
                            <?php foreach ($loaisukien as $item): ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="checkbox" name="selected_ids[]" value="<?= $item->loai_su_kien_id ?>" class="form-check-input checkbox-item cursor-pointer">
                                        </div>
                                    </td>
                                    <td><?= esc($item->ma_loai_su_kien) ?></td>
                                    <td><?= esc($item->ten_loai_su_kien) ?></td>
                                    <td>
                                        <?php if (method_exists($item, 'getStatusLabel')): ?>
                                            <?= $item->getStatusLabel() ?>
                                        <?php else: ?>
                                            <?= $item->status == 1 ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-danger">Không hoạt động</span>' ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (method_exists($item, 'getDeletedAtFormatted')): ?>
                                            <?= $item->getDeletedAtFormatted() ?>
                                        <?php else: ?>
                                            <?= !empty($item->deleted_at) ? date('d/m/Y H:i', strtotime($item->deleted_at)) : '' ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <form action="<?= site_url('loaisukien/restore/' . $item->loai_su_kien_id) ?>" method="post" style="display:inline;">
                                                <button type="submit" class="btn btn-success btn-sm" title="Khôi phục" data-bs-toggle="tooltip">
                                                    <i class="bx bx-revision mr-0"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm btn-permanent-delete" 
                                            data-id="<?= $item->loai_su_kien_id ?>" data-name="<?= $item->ten_loai_su_kien ?>" title="Xóa vĩnh viễn" data-bs-toggle="tooltip">
                                                <i class="bx bx-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <div class="empty-state">
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
        <?php if (!empty($loaisukien)): ?>
            <div class="card-footer d-flex justify-content-between align-items-center py-2">
                <div class="text-muted small">Hiển thị <span id="total-records"><?= count($loaisukien) ?></span> bản ghi</div>
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
                <div class="text-center icon-wrapper mb-3">
                    <i class='bx bx-help-circle text-success' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục loại sự kiện:</p>
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
                <div class="text-center icon-wrapper mb-3">
                    <i class='bx bx-error-circle text-danger' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn loại sự kiện:</p>
                <p class="text-center fw-bold" id="permanent-delete-item-name"></p>
                <div class="alert alert-warning mt-3">
                    <i class='bx bx-warning me-1'></i> Lưu ý: Hành động này không thể hoàn tác!
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
                <div class="text-center icon-wrapper mb-3">
                    <i class='bx bx-help-circle text-success' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục <span id="restore-count" class="fw-bold"></span> loại sự kiện đã chọn?</p>
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
                <div class="text-center icon-wrapper mb-3">
                    <i class='bx bx-error-circle text-danger' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn <span id="permanent-delete-count" class="fw-bold"></span> loại sự kiện đã chọn?</p>
                <div class="alert alert-warning mt-3">
                    <i class='bx bx-warning me-1'></i> Lưu ý: Hành động này không thể hoàn tác!
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
    var base_url = '<?= site_url() ?>';
</script>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= loaisukien_js('table') ?>
<?= loaisukien_section_js('table') ?>

<script>
    $(document).ready(function() {
        // Kiểm tra xem bảng đã được khởi tạo thành DataTable chưa
        if (!$.fn.DataTable.isDataTable('#dataTable')) {
            // Khởi tạo tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltips].map(t => new bootstrap.Tooltip(t));
            
            // Khởi tạo DataTable với cấu hình tiếng Việt
            const dataTable = $('#dataTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json',
                },
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                dom: '<"row mx-0"<"col-sm-12 px-0"tr>><"row mx-0 mt-2"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"p>>',
                ordering: true,
                responsive: false,
                scrollX: false,
                columnDefs: [
                    { orderable: false, targets: [0, 5] },
                    { className: 'align-middle', targets: '_all' }
                ]
            });
            
            // Tìm kiếm
            $('#search-btn').on('click', function() {
                dataTable.search($('#table-search').val()).draw();
            });
            
            $('#table-search').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    dataTable.search($(this).val()).draw();
                }
            });

            // Cập nhật tổng số bản ghi
            dataTable.on('draw', function() {
                $('#total-records').text(dataTable.page.info().recordsTotal);
            });
        } else {
            // Nếu bảng đã được khởi tạo, lấy instance hiện tại
            const dataTable = $('#dataTable').DataTable();
            
            // Cập nhật lại dữ liệu
            dataTable.draw();
        }
        
        // Làm mới bảng
        $('#refresh-table').on('click', function() {
            location.reload();
        });
        
        // Chọn tất cả
        $('#select-all').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.checkbox-item').prop('checked', isChecked);
            updateActionButtons();
        });
        
        // Cập nhật trạng thái nút hành động khi checkbox thay đổi
        $(document).on('change', '.checkbox-item', function() {
            updateActionButtons();
            
            // Nếu bỏ chọn một item, bỏ chọn select-all
            if (!$(this).prop('checked')) {
                $('#select-all').prop('checked', false);
            }
            
            // Nếu chọn tất cả items, chọn select-all
            if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
                $('#select-all').prop('checked', true);
            }
        });
        
        // Function cập nhật trạng thái của các nút hành động
        function updateActionButtons() {
            const selectedCount = $('.checkbox-item:checked').length;
            if (selectedCount > 0) {
                $('#restore-selected, #permanent-delete-selected').prop('disabled', false);
            } else {
                $('#restore-selected, #permanent-delete-selected').prop('disabled', true);
            }
        }
        
        // Xử lý xóa vĩnh viễn
        $('.btn-permanent-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#permanent-delete-item-name').text(name);
            $('#permanent-delete-form').attr('action', base_url + '/loaisukien/permanentDelete/' + id);
            $('#permanentDeleteModal').modal('show');
        });
        
        // Xử lý nút khôi phục nhiều
        $('#restore-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#restore-count').text($('.checkbox-item:checked').length);
                $('#restoreMultipleModal').modal('show');
            }
        });
        
        // Xử lý xác nhận khôi phục nhiều
        $('#confirm-restore-multiple').on('click', function() {
            // Tạo form tạm thời chứa các checkbox đã chọn
            const tempForm = $('#form-restore-multiple');
            
            // Xóa form cũ và tạo form mới
            tempForm.empty();
            
            // Thêm các checkbox đã chọn vào form
            $('.checkbox-item:checked').each(function() {
                const input = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_ids[]',
                    value: $(this).val()
                });
                tempForm.append(input);
            });
            
            // Submit form
            tempForm.submit();
            
            // Đóng modal
            $('#restoreMultipleModal').modal('hide');
        });
        
        // Xử lý nút xóa vĩnh viễn nhiều
        $('#permanent-delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#permanent-delete-count').text($('.checkbox-item:checked').length);
                $('#permanentDeleteMultipleModal').modal('show');
            }
        });
        
        // Xử lý xác nhận xóa vĩnh viễn nhiều
        $('#confirm-permanent-delete-multiple').on('click', function() {
            // Tạo form tạm thời chứa các checkbox đã chọn
            const tempForm = $('#form-permanent-delete-multiple');
            
            // Xóa form cũ và tạo form mới
            tempForm.empty();
            
            // Thêm các checkbox đã chọn vào form
            $('.checkbox-item:checked').each(function() {
                const input = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_ids[]',
                    value: $(this).val()
                });
                tempForm.append(input);
            });
            
            // Submit form
            tempForm.submit();
            
            // Đóng modal
            $('#permanentDeleteMultipleModal').modal('hide');
        });
        
        // Xuất dữ liệu
        $('#export-excel').on('click', function(e) {
            e.preventDefault();
            window.location.href = '<?= site_url("loaisukien/exportDeletedExcel") ?>';
        });
        
        $('#export-pdf').on('click', function(e) {
            e.preventDefault();
            window.location.href = '<?= site_url("loaisukien/exportDeletedPdf") ?>';
        });
    });
</script>
<?= $this->endSection() ?> 