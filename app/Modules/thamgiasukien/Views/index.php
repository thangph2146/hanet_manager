<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('table') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH THAM GIA SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách tham gia sự kiện',
	'dashboard_url' => site_url('thamgiasukien/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Tham Gia Sự Kiện', 'url' => site_url('thamgiasukien')],
		['title' => 'Danh sách', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/thamgiasukien/new'), 'title' => 'Thêm mới', 'icon' => 'bx bx-plus-circle']
	]
]) ?>
<?= $this->endSection() ?>  

<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách tham gia sự kiện</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
                <i class='bx bx-refresh'></i> Làm mới
            </button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-export'></i> Xuất
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= site_url('thamgiasukien/exportExcel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" id="export-excel">Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="export-pdf">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 bg-light border-bottom">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <?= form_open("thamgiasukien/deleteMultiple", ['id' => 'form-delete-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="delete-selected" class="btn btn-danger btn-sm me-2" disabled>
                        <i class='bx bx-trash'></i> Xóa mục đã chọn
                    </button>
                    <?= form_close() ?>
                    
                    <?= form_open("thamgiasukien/statusMultiple", ['id' => 'form-status-multiple', 'class' => 'd-inline']) ?>
                    <button type="button" id="status-selected" class="btn btn-warning btn-sm" disabled>
                        <i class='bx bx-refresh'></i> Đổi trạng thái
                    </button>
                    <?= form_close() ?>
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= site_url('thamgiasukien') ?>" method="get" id="search-form">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="perPage" value="<?= $perPage ?>">
                        <div class="input-group search-box">
                            <input type="text" class="form-control form-control-sm" id="table-search" name="keyword" placeholder="Tìm kiếm..." value="<?= $keyword ?? '' ?>">
                            <select name="status" class="form-select form-select-sm" style="max-width: 140px;">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" <?= (isset($status) && $status == '1') ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="0" <?= (isset($status) && $status == '0') ? 'selected' : '' ?>>Không hoạt động</option>
                            </select>
                            <select name="phuong_thuc_diem_danh" class="form-select form-select-sm" style="max-width: 150px;">
                                <option value="">-- Phương thức --</option>
                                <option value="qr_code" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'qr_code') ? 'selected' : '' ?>>QR Code</option>
                                <option value="face_id" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'face_id') ? 'selected' : '' ?>>Face ID</option>
                                <option value="manual" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'manual') ? 'selected' : '' ?>>Thủ công</option>
                            </select>
                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                <i class='bx bx-search'></i>
                            </button>
                            <?php if (!empty($keyword) || (isset($status) && $status !== '') || (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== '')): ?>
                            <a href="<?= site_url('thamgiasukien') ?>" class="btn btn-outline-danger btn-sm">
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
        
        <?php if (!empty($keyword) || (isset($status) && $status !== '') || (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== '')): ?>
            <div class="alert alert-info m-3">
                <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
                <div class="small">
                    <?php if (!empty($keyword)): ?>
                        <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
                    <?php endif; ?>
                    <?php if (isset($status) && $status !== ''): ?>
                        <span class="badge bg-secondary me-2">Trạng thái: <?= $status == 1 ? 'Hoạt động' : 'Không hoạt động' ?></span>
                    <?php endif; ?>
                    <?php if (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== ''): ?>
                        <span class="badge bg-info me-2">Phương thức: 
                            <?php 
                                if ($phuong_thuc_diem_danh == 'qr_code') echo 'QR Code';
                                elseif ($phuong_thuc_diem_danh == 'face_id') echo 'Face ID';
                                else echo 'Thủ công';
                            ?>
                        </span>
                    <?php endif; ?>
                    <a href="<?= site_url('thamgiasukien') ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
                </div>
            </div>
        <?php endif; ?>
        
    <!-- Phần debug info (chỉ hiển thị trong môi trường development) -->
    <?php if (ENVIRONMENT === 'development'): ?>
    <div class="card mt-3 mx-3">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">Debug Info</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>URL Parameters:</h6>
                    <pre><?= json_encode($_GET, JSON_PRETTY_PRINT) ?></pre>
                </div>
                <div class="col-md-6">
                    <h6>Pagination Info:</h6>
                    <pre><?= json_encode([
                        'current_page' => $currentPage,
                        'per_page' => $perPage,
                        'total_records' => $total,
                        'total_pages' => $pager ? $pager->getPageCount() : 0,
                        'status' => $status,
                        'keyword' => $keyword,
                        'phuong_thuc_diem_danh' => $phuong_thuc_diem_danh ?? null,
                        'thamgiasukien_count' => isset($thamGiaSuKiens) && (is_array($thamGiaSuKiens) || $thamGiaSuKiens instanceof Countable) ? count($thamGiaSuKiens) : 0
                    ], JSON_PRETTY_PRINT) ?></pre>
                </div>
            </div>
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
                            <th width="10%" class="align-middle">ID</th>
                            <th width="15%" class="align-middle">Người dùng</th>
                            <th width="15%" class="align-middle">Sự kiện</th>
                            <th width="15%" class="align-middle">Thời gian điểm danh</th>
                            <th width="10%" class="text-center align-middle">Phương thức</th>
                            <th width="10%" class="text-center align-middle">Trạng thái</th>
                            <th width="20%" class="text-center align-middle">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($thamGiaSuKiens)) : ?>
                            <?php foreach ($thamGiaSuKiens as $item) : ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input checkbox-item cursor-pointer" type="checkbox" name="selected_ids[]" value="<?= $item->tham_gia_su_kien_id ?>">
                                        </div>
                                    </td>
                                    <td><?= esc($item->tham_gia_su_kien_id) ?></td>
                                    <td><?= esc($item->nguoi_dung_id) ?></td>
                                    <td><?= esc($item->su_kien_id) ?></td>
                                    <td><?= !empty($item->thoi_gian_diem_danh) ? date('d/m/Y H:i:s', strtotime($item->thoi_gian_diem_danh)) : 'Chưa điểm danh' ?></td>
                                    <td class="text-center">
                                        <?php if ($item->phuong_thuc_diem_danh == 'qr_code'): ?>
                                            <span class="badge bg-info">QR Code</span>
                                        <?php elseif ($item->phuong_thuc_diem_danh == 'face_id'): ?>
                                            <span class="badge bg-primary">Face ID</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Thủ công</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($item->status == 1): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Không hoạt động</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <a href="<?= site_url("thamgiasukien/view/{$item->tham_gia_su_kien_id}") ?>" class="btn btn-info btn-sm w-100 h-100" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="bx bx-info-circle text-white"></i>
                                            </a>
                                            <a href="<?= site_url("thamgiasukien/edit/{$item->tham_gia_su_kien_id}") ?>" class="btn btn-primary btn-sm w-100 h-100" data-bs-toggle="tooltip" title="Sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete w-100 h-100" 
                                                    data-id="<?= $item->tham_gia_su_kien_id ?>" 
                                                    data-name="ID: <?= esc($item->tham_gia_su_kien_id) ?>"
                                                    data-bs-toggle="tooltip" title="Xóa">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center py-3">
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
        <?php if (!empty($thamGiaSuKiens)): ?>
            <div class="card-footer d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Hiển thị từ <?= (($pager->getCurrentPage() - 1) * $perPage + 1) ?> đến <?= min(($pager->getCurrentPage() - 1) * $perPage + $perPage, $total) ?> trong số <?= $total ?> bản ghi
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="me-2">
                            <select id="perPageSelect" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                <option value="15" <?= $perPage == 15 ? 'selected' : '' ?>>15</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                            </select>
                            <span class="ms-1">bản ghi/trang</span>
                        </div>
                        <div>
                            <?php if (isset($pager) && $pager instanceof \App\Modules\thamgiasukien\Libraries\Pager): ?>
                                <?= $pager->render() ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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
                <p class="text-center">Bạn có chắc chắn muốn xóa bản ghi tham gia sự kiện:</p>
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
                <p class="text-center">Bạn có chắc chắn muốn xóa <span id="selected-count" class="fw-bold"></span> bản ghi đã chọn?</p>
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
                <p class="text-center">Bạn có chắc chắn muốn thay đổi trạng thái của <span id="status-count" class="fw-bold"></span> bản ghi đã chọn?</p>
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
<?= page_js('table') ?>
<?= page_section_js('table') ?>

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
        }
        
        // Làm mới bảng
        $('#refresh-table').on('click', function() {
            $('#loading-indicator').css('display', 'flex').fadeIn(100);
            location.reload();
        });
        
        // Xử lý nút xóa
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#delete-item-name').text(name);
            
            // Lấy đường dẫn tương đối (path + query string)
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Tạo URL xóa với tham số truy vấn return_url
            const deleteUrl = '<?= site_url('thamgiasukien/delete/') ?>' + id + '?return_url=' + encodeURIComponent(pathAndQuery);
            $('#delete-form').attr('action', deleteUrl);
            
            console.log('URL xóa:', deleteUrl);
            
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
            
            // Xóa các input cũ
            tempForm.empty();
            
            // Lấy đường dẫn tương đối (path + query string) thay vì URL đầy đủ
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Thêm URL hiện tại làm return_url
            tempForm.append($('<input>').attr({
                type: 'hidden',
                name: 'return_url',
                value: pathAndQuery
            }));
            
            // Thêm các checkbox đã chọn vào form
            $('.checkbox-item:checked').each(function() {
                const input = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_ids[]',
                    value: $(this).val()
                });
                tempForm.append(input);
            });
            
            console.log('Deleting multiple items with return URL path:', pathAndQuery);
            console.log('Form data:', {
                return_url: pathAndQuery,
                selected_ids: $('.checkbox-item:checked').map(function() { return $(this).val(); }).get()
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
        
        // Xuất dữ liệu
        $('#export-excel').on('click', function(e) {
            e.preventDefault();
            
            // Lấy URL hiện tại và các tham số query string
            const currentUrl = new URL(window.location.href);
            const queryParams = currentUrl.searchParams;
            
            // Tạo URL xuất Excel với các tham số cần thiết
            let exportUrl = '<?= site_url("thamgiasukien/exportExcel") ?>';
            const params = [];
            
            // Thêm các tham số cần thiết
            if (queryParams.has('keyword')) {
                params.push('keyword=' + encodeURIComponent(queryParams.get('keyword')));
            }
            if (queryParams.has('status')) {
                params.push('status=' + encodeURIComponent(queryParams.get('status')));
            }
            if (queryParams.has('phuong_thuc_diem_danh')) {
                params.push('phuong_thuc_diem_danh=' + encodeURIComponent(queryParams.get('phuong_thuc_diem_danh')));
            }
            if (queryParams.has('sort')) {
                params.push('sort=' + encodeURIComponent(queryParams.get('sort')));
            }
            if (queryParams.has('order')) {
                params.push('order=' + encodeURIComponent(queryParams.get('order')));
            }
            
            // Thêm các tham số vào URL
            if (params.length > 0) {
                exportUrl += '?' + params.join('&');
            }
            
            console.log('Exporting data to Excel with URL:', exportUrl);
            
            // Chuyển hướng đến URL xuất Excel
            window.location.href = exportUrl;
        });
        
        // Xuất PDF
        $('#export-pdf').on('click', function(e) {
            e.preventDefault();
            
            // Lấy URL hiện tại và các tham số query string
            const currentUrl = new URL(window.location.href);
            const queryParams = currentUrl.searchParams;
            
            // Tạo URL xuất PDF với các tham số cần thiết
            let exportUrl = '<?= site_url("thamgiasukien/exportPdf") ?>';
            const params = [];
            
            // Thêm các tham số cần thiết
            if (queryParams.has('keyword')) {
                params.push('keyword=' + encodeURIComponent(queryParams.get('keyword')));
            }
            if (queryParams.has('status')) {
                params.push('status=' + encodeURIComponent(queryParams.get('status')));
            }
            if (queryParams.has('phuong_thuc_diem_danh')) {
                params.push('phuong_thuc_diem_danh=' + encodeURIComponent(queryParams.get('phuong_thuc_diem_danh')));
            }
            if (queryParams.has('sort')) {
                params.push('sort=' + encodeURIComponent(queryParams.get('sort')));
            }
            if (queryParams.has('order')) {
                params.push('order=' + encodeURIComponent(queryParams.get('order')));
            }
            
            // Thêm các tham số vào URL
            if (params.length > 0) {
                exportUrl += '?' + params.join('&');
            }
            
            console.log('Exporting data to PDF with URL:', exportUrl);
            
            // Chuyển hướng đến URL xuất PDF
            window.location.href = exportUrl;
        });

        // Xử lý khi thay đổi số lượng bản ghi trên mỗi trang
        document.getElementById('perPageSelect').addEventListener('change', function() {
            const perPage = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            
            // Giữ lại tất cả các tham số cần thiết
            const paramsToKeep = ['keyword', 'status', 'phuong_thuc_diem_danh', 'sort', 'order'];
            
            // Tạo URL mới với tham số perPage và reset về trang 1
            const newParams = new URLSearchParams();
            newParams.set('perPage', perPage);
            newParams.set('page', 1); // Reset về trang 1 khi thay đổi số bản ghi/trang
            
            // Giữ lại các tham số quan trọng
            paramsToKeep.forEach(param => {
                if (urlParams.has(param)) {
                    // Đặc biệt xử lý status=0
                    if (param === 'status' && urlParams.get(param) === '0') {
                        newParams.set(param, '0');
                    } 
                    // Chỉ giữ lại tham số có giá trị
                    else if (urlParams.get(param)) {
                        newParams.set(param, urlParams.get(param));
                    }
                }
            });
            
            // Chuyển hướng đến URL mới
            window.location.href = window.location.pathname + '?' + newParams.toString();
        });
    });
</script>
<?= $this->endSection() ?> 