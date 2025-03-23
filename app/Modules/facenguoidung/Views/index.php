<?php $this->extend('layouts/default') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>

<?php $this->section('styles') ?>
<?= nganh_css('table') ?>
<?= nganh_section_css('modal') ?>
<?php $this->endSection() ?>
<?php $this->section('title') ?>QUẢN LÝ KHUÔN MẶT NGƯỜI DÙNG<?php $this->endSection() ?>

<?php $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý khuôn mặt người dùng',
	'dashboard_url' => site_url('facenguoidung'),
	'breadcrumbs' => [
		['title' => 'Quản lý khuôn mặt người dùng', 'active' => true]
	],
	'actions' => [
		['url' => site_url('facenguoidung/new'), 'title' => 'Thêm mới', 'icon' => 'bx bx-plus'],
		['url' => site_url('facenguoidung/listdeleted'), 'title' => 'Thùng rác', 'icon' => 'bx bx-trash']
	]
]) ?>
<?php $this->endSection() ?>

<?php $this->section('content') ?>
<!-- Thông báo -->
<?php if (session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-2"></i>
        <?= session('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-2"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Card chính -->
<div class="card shadow-sm">
    <div class="card-header py-3">
        <div class="row g-3 align-items-center">
            <div class="col-md-12 col-lg-4">
                <h5 class="card-title mb-0">Danh sách khuôn mặt người dùng</h5>
            </div>
            
            <div class="col-md-12 col-lg-8">
                <form id="searchForm" action="<?= site_url('facenguoidung') ?>" method="GET" class="row g-2 justify-content-md-end">
                    <div class="col-md-3">
                        <select name="nguoi_dung_id" id="nguoi_dung_id" class="form-select select2" data-placeholder="-- Tất cả người dùng --">
                            <option value="">-- Tất cả người dùng --</option>
                            <?php if (!empty($nguoidungs)): ?>
                                <?php foreach ($nguoidungs as $nguoidung): ?>
                                    <option value="<?= $nguoidung->id ?>" <?= isset($_GET['nguoi_dung_id']) && $_GET['nguoi_dung_id'] == $nguoidung->id ? 'selected' : '' ?>>
                                        <?= esc($nguoidung->ho_ten) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="1" <?= isset($_GET['status']) && $_GET['status'] === '1' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= isset($_GET['status']) && $_GET['status'] === '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" id="search" placeholder="Tìm kiếm..." value="<?= isset($_GET['search']) ? esc($_GET['search']) : '' ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-search"></i>
                            </button>
                            <?php if (!empty($_GET)): ?>
                                <a href="<?= site_url('facenguoidung') ?>" class="btn btn-danger">
                                    <i class="bx bx-x"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Action Tools -->
        <div class="mb-3">
            <form id="bulkActionForm" action="<?= site_url('facenguoidung/deleteMultiple') ?>" method="POST" class="d-inline">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-trash me-1"></i> Xóa
                    </button>
                    <ul class="dropdown-menu">
                        <li><button type="button" class="dropdown-item" id="btnDeleteSelected">Xóa đã chọn</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button type="button" class="dropdown-item" id="btnSelectAll">Chọn tất cả</button></li>
                        <li><button type="button" class="dropdown-item" id="btnDeselectAll">Bỏ chọn tất cả</button></li>
                    </ul>
                </div>
                
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-toggle-left me-1"></i> Trạng thái
                    </button>
                    <ul class="dropdown-menu">
                        <li><button type="button" class="dropdown-item" id="btnSetActive">Đặt hoạt động</button></li>
                        <li><button type="button" class="dropdown-item" id="btnSetInactive">Đặt không hoạt động</button></li>
                    </ul>
                </div>
                
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-export me-1"></i> Xuất
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= site_url('facenguoidung/exportPdf') . (!empty($_GET) ? '?' . http_build_query($_GET) : '') ?>">Xuất PDF</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('facenguoidung/exportExcel') . (!empty($_GET) ? '?' . http_build_query($_GET) : '') ?>">Xuất Excel</a></li>
                    </ul>
                </div>
            </form>
        </div>
        
        <!-- Bảng dữ liệu -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">#</th>
                        <th width="60" class="text-center">ID</th>
                        <th>Người dùng</th>
                        <th width="150" class="text-center">Ảnh</th>
                        <th width="180">Ngày cập nhật</th>
                        <th width="120" class="text-center">Trạng thái</th>
                        <th width="130" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">Không có dữ liệu</div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $key => $item): ?>
                        <tr>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ids[]" value="<?= $item->face_nguoi_dung_id ?>" form="bulkActionForm" id="check<?= $item->face_nguoi_dung_id ?>">
                                    <label class="form-check-label" for="check<?= $item->face_nguoi_dung_id ?>"></label>
                                </div>
                            </td>
                            <td class="text-center"><?= $item->face_nguoi_dung_id ?></td>
                            <td>
                                <?php if (isset($item->nguoi_dung) && !empty($item->nguoi_dung)): ?>
                                    <div class="fw-bold"><?= esc($item->nguoi_dung->ho_ten) ?></div>
                                    <?php if (!empty($item->nguoi_dung->email)): ?>
                                        <div class="small text-muted"><?= esc($item->nguoi_dung->email) ?></div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Không có thông tin</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($item->duong_dan_anh)): ?>
                                    <img src="<?= base_url($item->duong_dan_anh) ?>" class="img-thumbnail" width="100" alt="Ảnh khuôn mặt">
                                <?php else: ?>
                                    <span class="text-muted"><i class="bx bx-camera-off"></i> Không có ảnh</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($item->ngay_cap_nhat)): ?>
                                    <?= date('d/m/Y H:i:s', strtotime($item->ngay_cap_nhat)) ?>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <form action="<?= site_url('facenguoidung/status/' . $item->face_nguoi_dung_id) ?>" method="post" class="status-form">
                                    <button type="submit" class="btn btn-sm <?= $item->status ? 'btn-success' : 'btn-secondary' ?>">
                                        <?php if ($item->status): ?>
                                            <i class="bx bx-check-circle me-1"></i> Hoạt động
                                        <?php else: ?>
                                            <i class="bx bx-x-circle me-1"></i> Không hoạt động
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= site_url('facenguoidung/view/' . $item->face_nguoi_dung_id) ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Xem chi tiết">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="<?= site_url('facenguoidung/edit/' . $item->face_nguoi_dung_id) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="<?= $item->face_nguoi_dung_id ?>" data-name="<?= isset($item->nguoi_dung) ? esc($item->nguoi_dung->ho_ten) : 'Khuôn mặt #' . $item->face_nguoi_dung_id ?>" data-bs-toggle="tooltip" title="Xóa">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Phân trang -->
        <?php if ($pager): ?>
            <?= $pager->links() ?>
        <?php endif; ?>
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
                Bạn có chắc chắn muốn xóa khuôn mặt của <strong id="deleteName"></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="deleteButton" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?>

<?php $this->section('script') ?>
<?= nganh_js('table') ?>
<?= nganh_section_js('table') ?>

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
                responsive: true,
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
        }
        
        // Làm mới bảng
        $('#refresh-table').on('click', function() {
            location.reload();
        });
        
        // Xử lý nút xóa
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#delete-form').attr('action', '<?= site_url('nganh/delete/') ?>' + id);
            $('#deleteModal').modal('show');
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
                $('#delete-selected, #status-selected').prop('disabled', false);
            } else {
                $('#delete-selected, #status-selected').prop('disabled', true);
            }
        }
        
        // Xử lý nút xóa nhiều
        $('#delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#selected-count').text($('.checkbox-item:checked').length);
                $('#deleteMultipleModal').modal('show');
            }
        });
        
        // Xử lý xác nhận xóa nhiều
        $('#confirm-delete-multiple').on('click', function() {
            // Tạo form tạm thời chứa các checkbox đã chọn
            const tempForm = $('#form-delete-multiple');
            
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
            $('#deleteMultipleModal').modal('hide');
        });
        
        // Xử lý nút đổi trạng thái nhiều
        $('#status-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#status-count').text($('.checkbox-item:checked').length);
                $('#statusMultipleModal').modal('show');
            }
        });
        
        // Xử lý xác nhận đổi trạng thái nhiều
        $('#confirm-status-multiple').on('click', function() {
            // Tạo form tạm thời chứa các checkbox đã chọn
            const tempForm = $('#form-status-multiple');
            
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
            $('#statusMultipleModal').modal('hide');
        });
        
        // Xuất Excel
        $('#export-excel').on('click', function(e) {
            e.preventDefault();
            window.location.href = '<?= site_url("nganh/exportExcel") ?>';
        });
        
        // Xuất PDF
        $('#export-pdf').on('click', function(e) {
            e.preventDefault();
            window.location.href = '<?= site_url("nganh/exportPdf") ?>';
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete confirmation
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            const deleteNameElement = document.getElementById('deleteName');
            const deleteButtonLink = document.getElementById('deleteButton');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    
                    deleteNameElement.textContent = name;
                    deleteButtonLink.setAttribute('href', `<?= site_url('facenguoidung/delete/') ?>${id}`);
                    
                    const modal = new bootstrap.Modal(deleteModal);
                    modal.show();
                });
            });
        }
        
        // Handle bulk actions
        const bulkActionForm = document.getElementById('bulkActionForm');
        
        document.getElementById('btnDeleteSelected').addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input[name="ids[]"]:checked');
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn ít nhất một khuôn mặt để xóa!');
                return;
            }
            
            if (confirm(`Bạn có chắc chắn muốn xóa ${checkedBoxes.length} khuôn mặt đã chọn không?`)) {
                bulkActionForm.action = '<?= site_url('facenguoidung/deleteMultiple') ?>';
                bulkActionForm.submit();
            }
        });
        
        document.getElementById('btnSetActive').addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input[name="ids[]"]:checked');
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn ít nhất một khuôn mặt để cập nhật trạng thái!');
                return;
            }
            
            bulkActionForm.action = '<?= site_url('facenguoidung/statusMultiple') ?>';
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = '1';
            bulkActionForm.appendChild(statusInput);
            bulkActionForm.submit();
        });
        
        document.getElementById('btnSetInactive').addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input[name="ids[]"]:checked');
            if (checkedBoxes.length === 0) {
                alert('Vui lòng chọn ít nhất một khuôn mặt để cập nhật trạng thái!');
                return;
            }
            
            bulkActionForm.action = '<?= site_url('facenguoidung/statusMultiple') ?>';
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = '0';
            bulkActionForm.appendChild(statusInput);
            bulkActionForm.submit();
        });
        
        document.getElementById('btnSelectAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        });
        
        document.getElementById('btnDeselectAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        });
        
        // Initialize Select2 for better dropdown UI
        if ($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
        
        // Auto submit form on filter change
        const filterInputs = document.querySelectorAll('#nguoi_dung_id, #status');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        });
    });
</script>
<?php $this->endSection() ?> 