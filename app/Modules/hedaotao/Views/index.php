<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= hedaotao_css('table') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>QUẢN LÝ HỆ ĐÀO TẠO<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Hệ Đào Tạo',
	'dashboard_url' => site_url('hedaotao/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Hệ Đào Tạo', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/hedaotao/new'), 'title' => 'Tạo Hệ Đào Tạo Mới', 'icon' => 'bx bx-plus'],
		['url' => site_url('/hedaotao/listdeleted'), 'title' => 'Danh sách đã xóa', 'icon' => 'bx bx-trash']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách hệ đào tạo</h5>
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
                    <?= form_open("hedaotao/deleteMultiple", ['id' => 'form-delete-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="delete-selected" class="btn btn-danger btn-sm me-2" disabled>
                        <i class='bx bx-trash'></i> Xóa mục đã chọn
                    </button>
                    <?= form_close() ?>
                    
                    <?= form_open("hedaotao/statusMultiple", ['id' => 'form-status-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="status-selected" class="btn btn-warning btn-sm" disabled>
                        <i class='bx bx-refresh'></i> Đổi trạng thái
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
                            <th width="30%" class="align-middle">Tên hệ đào tạo</th>
                            <th width="20%" class="align-middle">Mã hệ đào tạo</th>
                            <th width="15%" class="align-middle">Trạng thái</th>
                            <th width="15%" class="align-middle">Ngày tạo</th>
                            <th width="15%" class="text-center align-middle">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($he_dao_tao)): ?>
                            <?php foreach ($he_dao_tao as $hdt): ?>
                                <tr>
                                    <td class="text-center py-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="selected_ids[]" value="<?= $hdt['id'] ?>" class="form-check-input checkbox-item cursor-pointer">
                                        </div>
                                    </td>
                                    <td class="py-2"><?= $hdt['ten_he_dao_tao'] ?></td>
                                    <td class="py-2"><?= $hdt['ma_he_dao_tao'] ?: '<span class="text-muted fst-italic">Chưa có</span>' ?></td>
                                    <td class="py-2"><?= $hdt['status'] ?></td>
                                    <td class="py-2"><?= (new DateTime($hdt['created_at']))->format('d/m/Y H:i') ?></td>
                                    <td class="text-center py-2">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= site_url('hedaotao/edit/' . $hdt['id']) ?>" class="btn btn-primary btn-sm" title="Sửa" data-bs-toggle="tooltip">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <form action="<?= site_url('hedaotao/status/' . $hdt['id']) ?>" method="post" style="display:inline;">
                                                <button type="submit" class="btn btn-warning btn-sm" title="Đổi trạng thái" data-bs-toggle="tooltip">
                                                    <i class="bx bx-refresh"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="<?= $hdt['id'] ?>" data-name="<?= $hdt['ten_he_dao_tao'] ?>" title="Xóa" data-bs-toggle="tooltip">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <div class="d-flex flex-column align-items-center py-3">
                                        <i class='bx bx-info-circle text-secondary mb-2' style="font-size: 2rem;"></i>
                                        <p class="mb-0">Không có dữ liệu</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (!empty($he_dao_tao)): ?>
            <div class="card-footer d-flex justify-content-between align-items-center py-2">
                <div class="text-muted small">Hiển thị <span id="total-records"><?= count($he_dao_tao) ?></span> bản ghi</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class='bx bx-error-circle text-danger' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa hệ đào tạo:</p>
                <p class="text-center fw-bold" id="delete-item-name"></p>
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

<!-- Modal Xác nhận xóa nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class='bx bx-error-circle text-danger' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa <span id="selected-count" class="fw-bold"></span> hệ đào tạo đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger">Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận đổi trạng thái nhiều -->
<div class="modal fade" id="statusMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class='bx bx-question-mark text-warning' style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn đổi trạng thái <span id="status-count" class="fw-bold"></span> hệ đào tạo đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-status-multiple" class="btn btn-warning">Đổi trạng thái</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= hedaotao_js('table') ?>

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
            $('#delete-form').attr('action', '<?= site_url('hedaotao/delete/') ?>' + id);
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
            
            // Cập nhật action và submit form
            tempForm.attr('action', '<?= site_url('hedaotao/deleteMultiple') ?>');
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