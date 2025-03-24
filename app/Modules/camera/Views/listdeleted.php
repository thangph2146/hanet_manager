<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= camera_css('table') ?>
<?= camera_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÙNG RÁC - CAMERA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thùng rác - Camera',
	'dashboard_url' => site_url('camera/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Camera', 'url' => site_url('camera')],
		['title' => 'Thùng rác', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/camera'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách camera đã xóa</h5>
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
                    <?= form_open("camera/restoreMultiple", ['id' => 'form-restore-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="restore-selected" class="btn btn-success btn-sm me-2" disabled>
                        <i class='bx bx-revision'></i> Khôi phục mục đã chọn
                    </button>
                    <?= form_close() ?>
                    
                    <?= form_open("camera/permanentDeleteMultiple", ['id' => 'form-permanent-delete-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="permanent-delete-selected" class="btn btn-danger btn-sm" disabled>
                        <i class='bx bx-trash-alt'></i> Xóa vĩnh viễn
                    </button>
                    <?= form_close() ?>
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= site_url('camera/listdeleted') ?>" method="get" id="search-form">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="perPage" value="<?= $perPage ?>">
                        <div class="input-group search-box">
                            <input type="text" class="form-control form-control-sm" id="table-search" name="keyword" placeholder="Tìm kiếm..." value="<?= $keyword ?? '' ?>">
                            <select name="status" class="form-select form-select-sm" style="max-width: 140px;">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" <?= (isset($status) && $status == '1') ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="0" <?= (isset($status) && $status == '0') ? 'selected' : '' ?>>Không hoạt động</option>
                            </select>
                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                <i class='bx bx-search'></i>
                            </button>
                            <?php if (!empty($keyword) || (isset($status) && $status !== '')): ?>
                            <a href="<?= site_url('camera/listdeleted') ?>" class="btn btn-outline-danger btn-sm">
                                <i class='bx bx-x'></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
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
        
        <?php if (!empty($keyword) || (isset($status) && $status !== '')): ?>
            <div class="alert alert-info m-3">
                <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
                <div class="small">
                    <?php if (!empty($keyword)): ?>
                        <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
                    <?php endif; ?>
                    <?php if (isset($status) && $status !== ''): ?>
                        <span class="badge bg-secondary me-2">Trạng thái: <?= $status == 1 ? 'Hoạt động' : 'Không hoạt động' ?></span>
                    <?php endif; ?>
                    <a href="<?= site_url('camera/listdeleted') ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
                </div>
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
                            <th width="10%" class="align-middle">Mã camera</th>
                            <th width="20%" class="align-middle">Tên camera</th>
                            <th width="20%" class="align-middle">Địa chỉ IP</th>
                            <th width="10%" class="align-middle">Port</th>
                            <th width="10%" class="align-middle">Trạng thái</th>
                            <th width="10%" class="align-middle">Ngày xóa</th>
                            <th width="15%" class="text-center align-middle">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($cameras)): ?>
                            <?php foreach ($cameras as $item): ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="checkbox" name="selected_ids[]" value="<?= $item->camera_id ?>" class="form-check-input checkbox-item cursor-pointer">
                                        </div>
                                    </td>
                                    <td><?= esc($item->ma_camera) ?></td>
                                    <td><?= esc($item->ten_camera) ?></td>
                                    <td><?= esc($item->ip_camera) ?></td>
                                    <td><?= esc($item->port) ?></td>
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
                                            <form action="<?= site_url('camera/restore/' . $item->camera_id) ?>" method="post" style="display:inline;">
                                                <button type="submit" class="btn btn-success btn-sm" title="Khôi phục" data-bs-toggle="tooltip">
                                                    <i class="bx bx-revision mr-0"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm btn-permanent-delete" 
                                            data-id="<?= $item->camera_id ?>" data-name="<?= $item->ten_camera ?>" title="Xóa vĩnh viễn" data-bs-toggle="tooltip">
                                                <i class="bx bx-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-3">
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
        <?php if (!empty($cameras)): ?>
            <div class="card-footer d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Hiển thị từ <?= (($pager->getCurrentPage() - 1) * $perPage + 1) ?> đến <?= min(($pager->getCurrentPage() - 1) * $perPage + $perPage, $total) ?> trong số <?= $total ?> bản ghi
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="me-2">
                            <select id="perPage" class="form-select form-select-sm" aria-label="Items per page">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>
                        <div>
                            <?php if (isset($pager) && $pager instanceof \App\Modules\camera\Libraries\CameraPager): ?>
                                <?= $pager->render() ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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
                <p class="text-center">Bạn có chắc chắn muốn khôi phục camera:</p>
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
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn camera:</p>
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
                <p class="text-center">Bạn có chắc chắn muốn khôi phục <span id="restore-count" class="fw-bold"></span> camera đã chọn?</p>
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
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn <span id="permanent-delete-count" class="fw-bold"></span> camera đã chọn?</p>
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
<?= camera_js('table') ?>
<?= camera_section_js('table') ?>

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
                    { orderable: false, targets: [0, 7] },
                    { className: 'align-middle', targets: '_all' }
                ],
                searching: false, // Tắt tìm kiếm của DataTable vì đã có form tìm kiếm
                paging: false, // Tắt phân trang của DataTable vì đã có phân trang CodeIgniter
                info: false // Tắt thông tin của DataTable
            });
            
            // Xử lý form tìm kiếm
            $('#search-form').on('submit', function() {
                // Form sẽ gửi yêu cầu GET nên không cần xử lý gì thêm
                return true;
            });
            
            // Xử lý nhấn Enter trong ô tìm kiếm
            $('#table-search').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    $('#search-form').submit();
                }
            });
        } else {
            // Nếu bảng đã được khởi tạo, lấy instance hiện tại
            const dataTable = $('#dataTable').DataTable();
            
            // Cập nhật lại dữ liệu
            dataTable.draw();
        }
        
        // Làm mới bảng
        $('#refresh-table').on('click', function() {
            $('#loading-indicator').css('display', 'flex').fadeIn(100);
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
            $('#permanent-delete-form').attr('action', base_url + '/camera/permanentDelete/' + id);
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
            window.location.href = '<?= site_url("camera/exportDeletedExcel") ?>';
        });
        
        $('#export-pdf').on('click', function(e) {
            e.preventDefault();
            window.location.href = '<?= site_url("camera/exportDeletedPdf") ?>';
        });

        // Xử lý khi thay đổi số lượng bản ghi trên mỗi trang
        document.getElementById('perPage').addEventListener('change', function() {
            const perPage = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            
            // Giữ lại các tham số cần thiết
            urlParams.set('perPage', perPage);
            urlParams.set('page', 1); // Reset về trang 1 khi thay đổi số bản ghi/trang
            
            // Chuyển hướng đến URL mới
            window.location.href = window.location.pathname + '?' + urlParams.toString();
        });
    });
</script>
<?= $this->endSection() ?> 