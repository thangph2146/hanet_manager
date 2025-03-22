<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= nganh_css('table') ?>
<?= nganh_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH NGÀNH<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách ngành',
	'dashboard_url' => site_url('nganh/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Ngành', 'url' => site_url('nganh')],
		['title' => 'Danh sách', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/nganh/new'), 'title' => 'Thêm mới', 'icon' => 'bx bx-plus-circle']
	]
]) ?>
<?= $this->endSection() ?>  

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách ngành</h5>
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
                    <?= form_open("nganh/deleteMultiple", ['id' => 'form-delete-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="delete-selected" class="btn btn-danger btn-sm me-2" disabled>
                        <i class='bx bx-trash'></i> Xóa mục đã chọn
                    </button>
                    <?= form_close() ?>
                    
                    <?= form_open("nganh/statusMultiple", ['id' => 'form-status-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="status-selected" class="btn btn-warning btn-sm" disabled>
                        <i class='bx bx-refresh'></i> Đổi trạng thái
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
                            <th width="10%" class="align-middle">Mã</th>
                            <th width="35%" class="align-middle">Tên ngành</th>
                            <th width="25%" class="align-middle">Phòng/Khoa</th>
                            <th width="10%" class="text-center align-middle">Trạng thái</th>
                            <th width="15%" class="text-center align-middle">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($nganh)) : ?>
                            <?php foreach ($nganh as $item) : ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input checkbox-item cursor-pointer" type="checkbox" name="selected_ids[]" value="<?= $item->nganh_id ?>">
                                        </div>
                                    </td>
                                    <td><?= esc($item->ma_nganh) ?></td>
                                    <td><?= esc($item->ten_nganh) ?></td>
                                    <td>
                                        <?php 
                                        if (method_exists($item, 'getPhongKhoaInfo')):
                                            echo $item->getPhongKhoaInfo();
                                        elseif (isset($item->phong_khoa) && !empty($item->phong_khoa)):
                                            echo esc($item->phong_khoa->ten_phong_khoa);
                                        else:
                                            echo '<span class="text-muted">Không có</span>';
                                        endif;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (method_exists($item, 'getStatusLabel')): ?>
                                            <?= $item->getStatusLabel() ?>
                                        <?php else: ?>
                                            <?= $item->status == 1 ? '<span class="badge bg-success">Hoạt động</span>' : '<span class="badge bg-danger">Không hoạt động</span>' ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <a href="<?= site_url("nganh/view/{$item->nganh_id}") ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="bx bx-info-circle"></i>
                                            </a>
                                            <a href="<?= site_url("nganh/edit/{$item->nganh_id}") ?>" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                                    data-id="<?= $item->nganh_id ?>" 
                                                    data-name="<?= esc($item->ten_nganh) ?>"
                                                    data-bs-toggle="tooltip" title="Xóa">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <div class="empty-state">
                                        <i class="bx bx-folder-open"></i>
                                        <p>Không có dữ liệu</p>
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
                <a href="<?= site_url('nganh/listdeleted') ?>" class="btn btn-sm btn-secondary">
                    <i class="bx bx-trash-alt me-1"></i> Thùng rác
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa ngành:</p>
                <p class="text-center fw-bold" id="delete-item-name"></p>
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-info-circle me-1"></i> Dữ liệu sẽ được chuyển vào thùng rác và có thể khôi phục.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('', ['id' => 'delete-form']) ?>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa nhiều</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa <span id="selected-count" class="fw-bold"></span> ngành đã chọn?</p>
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-info-circle me-1"></i> Dữ liệu sẽ được chuyển vào thùng rác và có thể khôi phục.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận đổi trạng thái nhiều -->
<div class="modal fade" id="statusMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-toggle-right text-warning" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn thay đổi trạng thái của <span id="status-count" class="fw-bold"></span> ngành đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-status-multiple" class="btn btn-warning">Đổi trạng thái</button>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = '<?= site_url() ?>';
</script>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
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
                $('#delete-selected, #status-selected').prop('disabled', false);
            } else {
                $('#delete-selected, #status-selected').prop('disabled', true);
            }
        }
        
        // Xử lý xóa một mục
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#delete-item-name').text(name);
            $('#delete-form').attr('action', '<?= site_url('nganh/delete/') ?>' + id);
            $('#deleteModal').modal('show');
        });
        
        // Xử lý xóa nhiều mục
        $('#delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#selected-count').text($('.checkbox-item:checked').length);
                $('#deleteMultipleModal').modal('show');
            }
        });
        
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
        
        // Xử lý đổi trạng thái nhiều mục
        $('#status-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#status-count').text($('.checkbox-item:checked').length);
                $('#statusMultipleModal').modal('show');
            }
        });
        
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
        
        // Xuất dữ liệu
        $('#export-excel').on('click', function(e) {
            e.preventDefault();
            alert('Chức năng xuất Excel đang được phát triển');
        });
        
        $('#export-pdf').on('click', function(e) {
            e.preventDefault();
            alert('Chức năng xuất PDF đang được phát triển');
        });
    });
</script>
<?= $this->endSection() ?> 