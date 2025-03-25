<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('table') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÙNG RÁC - THAM GIA SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thùng rác - Tham gia sự kiện',
	'dashboard_url' => site_url('thamgiasukien/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Tham Gia Sự Kiện', 'url' => site_url('thamgiasukien')],
		['title' => 'Thùng rác', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/thamgiasukien'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách tham gia sự kiện đã xóa</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
                <i class='bx bx-refresh'></i> Làm mới
            </button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-export'></i> Xuất
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= site_url('thamgiasukien/exportDeletedExcel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" id="export-excel">Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="export-pdf">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 bg-light border-bottom">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <form id="form-restore-multiple" action="<?= site_url('thamgiasukien/restoreMultiple') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="button" id="restore-selected" class="btn btn-success btn-sm me-2" disabled>
                            <i class='bx bx-revision'></i> Khôi phục mục đã chọn
                        </button>
                    </form>
                    
                    <form id="form-delete-multiple" action="<?= site_url('thamgiasukien/permanentDeleteMultiple') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="button" id="delete-selected" class="btn btn-danger btn-sm" disabled>
                            <i class='bx bx-trash'></i> Xóa vĩnh viễn
                        </button>
                    </form>
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= site_url('thamgiasukien/listdeleted') ?>" method="get" id="search-form">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="perPage" value="<?= $perPage ?>">
                        <div class="input-group search-box">
                            <input type="text" class="form-control form-control-sm" id="table-search" name="keyword" placeholder="Tìm kiếm..." value="<?= $keyword ?? '' ?>">
                            <select name="phuong_thuc_diem_danh" class="form-select form-select-sm" style="max-width: 150px;">
                                <option value="">-- Phương thức --</option>
                                <option value="qr_code" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'qr_code') ? 'selected' : '' ?>>QR Code</option>
                                <option value="face_id" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'face_id') ? 'selected' : '' ?>>Face ID</option>
                                <option value="manual" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'manual') ? 'selected' : '' ?>>Thủ công</option>
                            </select>
                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                <i class='bx bx-search'></i>
                            </button>
                            <?php if (!empty($keyword) || (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== '')): ?>
                            <a href="<?= site_url('thamgiasukien/listdeleted') ?>" class="btn btn-outline-danger btn-sm">
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
        
        <?php if (!empty($keyword) || (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== '')): ?>
            <div class="alert alert-info m-3">
                <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
                <div class="small">
                    <?php if (!empty($keyword)): ?>
                        <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
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
                    <a href="<?= site_url('thamgiasukien/listdeleted') ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
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
                            <th width="15%" class="text-center align-middle">Ngày xóa</th>
                            <th width="15%" class="text-center align-middle">Thao tác</th>
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
                                        <?= !empty($item->deleted_at) ? date('d/m/Y H:i:s', strtotime($item->deleted_at)) : 'N/A' ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <button type="button" class="btn btn-success btn-sm btn-restore w-100 h-100" 
                                                    data-id="<?= $item->tham_gia_su_kien_id ?>" 
                                                    data-name="ID: <?= esc($item->tham_gia_su_kien_id) ?>"
                                                    data-bs-toggle="tooltip" title="Khôi phục">
                                                <i class="bx bx-revision"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete w-100 h-100" 
                                                    data-id="<?= $item->tham_gia_su_kien_id ?>" 
                                                    data-name="ID: <?= esc($item->tham_gia_su_kien_id) ?>"
                                                    data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
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

<!-- Modal xác nhận khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-revision text-success" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục bản ghi tham gia sự kiện:</p>
                <p class="text-center fw-bold" id="restore-item-name"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('', ['id' => 'restore-form']) ?>
                    <button type="submit" class="btn btn-success">Khôi phục</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn bản ghi tham gia sự kiện:</p>
                <p class="text-center fw-bold" id="delete-item-name"></p>
                <div class="alert alert-danger mt-3">
                    <i class="bx bx-info-circle me-1"></i> Cảnh báo: Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('', ['id' => 'delete-form']) ?>
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận khôi phục nhiều -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục nhiều</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-revision text-success" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục <span id="restore-count" class="fw-bold"></span> bản ghi đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-restore-multiple" class="btn btn-success">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa vĩnh viễn nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn <span id="delete-count" class="fw-bold"></span> bản ghi đã chọn?</p>
                <div class="alert alert-danger mt-3">
                    <i class="bx bx-info-circle me-1"></i> Cảnh báo: Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger">Xóa vĩnh viễn</button>
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
        
        // Xử lý nút khôi phục
        $('.btn-restore').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#restore-item-name').text(name);
            
            // Lấy đường dẫn tương đối (path + query string)
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Tạo URL khôi phục với tham số truy vấn return_url
            const restoreUrl = '<?= site_url('thamgiasukien/restore/') ?>' + id + '?return_url=' + encodeURIComponent(pathAndQuery);
            $('#restore-form').attr('action', restoreUrl);
            
            console.log('URL khôi phục:', restoreUrl);
            
            $('#restoreModal').modal('show');
        });
        
        // Xử lý nút xóa vĩnh viễn
        $('.btn-delete').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#delete-item-name').text(name);
            
            // Lấy đường dẫn tương đối (path + query string)
            const pathAndQuery = window.location.pathname + window.location.search;
            
            // Tạo URL xóa với tham số truy vấn return_url
            const deleteUrl = '<?= site_url('thamgiasukien/permanentDelete/') ?>' + id + '?return_url=' + encodeURIComponent(pathAndQuery);
            $('#delete-form').attr('action', deleteUrl);
            
            console.log('URL xóa vĩnh viễn:', deleteUrl);
            
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
                $('#restore-selected, #delete-selected').prop('disabled', false);
            } else {
                $('#restore-selected, #delete-selected').prop('disabled', true);
            }
        }
        
        // Xử lý nút khôi phục nhiều
        $('#restore-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#restore-count').text($('.checkbox-item:checked').length);
                $('#restoreMultipleModal').modal('show');
            }
        });
        
        // Xử lý xác nhận khôi phục nhiều
        $('#confirm-restore-multiple').on('click', function() {
            // Lấy form
            const form = $('#form-restore-multiple');
            
            // Lấy danh sách ID đã chọn
            const selectedItems = [];
            $('.checkbox-item:checked').each(function() {
                selectedItems.push($(this).val());
            });
            
            // Nếu không có item nào được chọn, hiển thị thông báo
            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất một bản ghi để khôi phục');
                $('#restoreMultipleModal').modal('hide');
                return;
            }
            
            // Xóa tất cả input hiện có (trừ CSRF token)
            form.find('input:not([name="<?= csrf_token() ?>"])').remove();
            
            // Thêm các checkbox đã chọn vào form dưới dạng input hidden
            selectedItems.forEach(function(id) {
                form.append($('<input>').attr({
                    type: 'hidden',
                    name: 'selected_items[]',
                    value: id
                }));
            });
            
            // Thêm URL hiện tại làm return_url nếu chưa có
            if (form.find('input[name="return_url"]').length === 0) {
                const pathAndQuery = window.location.pathname + window.location.search;
                form.append($('<input>').attr({
                    type: 'hidden',
                    name: 'return_url',
                    value: pathAndQuery
                }));
            }
            
            // Submit form
            form.submit();
            
            // Đóng modal
            $('#restoreMultipleModal').modal('hide');
        });
        
        // Xử lý nút xóa vĩnh viễn nhiều
        $('#delete-selected').on('click', function() {
            if ($('.checkbox-item:checked').length > 0) {
                $('#delete-count').text($('.checkbox-item:checked').length);
                $('#deleteMultipleModal').modal('show');
            }
        });
        
        // Xử lý xác nhận xóa vĩnh viễn nhiều
        $('#confirm-delete-multiple').on('click', function() {
            // Lấy form
            const form = $('#form-delete-multiple');
            
            // Lấy danh sách ID đã chọn
            const selectedItems = [];
            $('.checkbox-item:checked').each(function() {
                selectedItems.push($(this).val());
            });
            
            // Nếu không có item nào được chọn, hiển thị thông báo
            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất một bản ghi để xóa vĩnh viễn');
                $('#deleteMultipleModal').modal('hide');
                return;
            }
            
            // Xóa tất cả input hiện có (trừ CSRF token)
            form.find('input:not([name="<?= csrf_token() ?>"])').remove();
            
            // Thêm các checkbox đã chọn vào form dưới dạng input hidden
            selectedItems.forEach(function(id) {
                form.append($('<input>').attr({
                    type: 'hidden',
                    name: 'selected_items[]',
                    value: id
                }));
            });
            
            // Thêm URL hiện tại làm return_url nếu chưa có
            if (form.find('input[name="return_url"]').length === 0) {
                const pathAndQuery = window.location.pathname + window.location.search;
                form.append($('<input>').attr({
                    type: 'hidden',
                    name: 'return_url',
                    value: pathAndQuery
                }));
            }
            
            // Submit form
            form.submit();
            
            // Đóng modal
            $('#deleteMultipleModal').modal('hide');
        });
        
        // Xuất dữ liệu Excel
        $('#export-excel').on('click', function(e) {
            e.preventDefault();
            
            // Lấy URL hiện tại và các tham số query string
            const currentUrl = new URL(window.location.href);
            const queryParams = currentUrl.searchParams;
            
            // Tạo URL xuất Excel với các tham số cần thiết
            let exportUrl = '<?= site_url("thamgiasukien/exportDeletedExcel") ?>';
            const params = [];
            
            // Thêm các tham số cần thiết
            if (queryParams.has('keyword')) {
                params.push('keyword=' + encodeURIComponent(queryParams.get('keyword')));
            }
            if (queryParams.has('phuong_thuc_diem_danh')) {
                params.push('phuong_thuc_diem_danh=' + encodeURIComponent(queryParams.get('phuong_thuc_diem_danh')));
            }
            
            // Thêm các tham số vào URL
            if (params.length > 0) {
                exportUrl += '?' + params.join('&');
            }
            
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
            let exportUrl = '<?= site_url("thamgiasukien/exportDeletedPdf") ?>';
            const params = [];
            
            // Thêm các tham số cần thiết
            if (queryParams.has('keyword')) {
                params.push('keyword=' + encodeURIComponent(queryParams.get('keyword')));
            }
            if (queryParams.has('phuong_thuc_diem_danh')) {
                params.push('phuong_thuc_diem_danh=' + encodeURIComponent(queryParams.get('phuong_thuc_diem_danh')));
            }
            
            // Thêm các tham số vào URL
            if (params.length > 0) {
                exportUrl += '?' + params.join('&');
            }
            
            // Chuyển hướng đến URL xuất PDF
            window.location.href = exportUrl;
        });
        
        // Xử lý khi thay đổi số lượng bản ghi trên mỗi trang
        document.getElementById('perPageSelect').addEventListener('change', function() {
            const perPage = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            
            // Giữ lại tất cả các tham số cần thiết
            const paramsToKeep = ['keyword', 'phuong_thuc_diem_danh'];
            
            // Tạo URL mới với tham số perPage và reset về trang 1
            const newParams = new URLSearchParams();
            newParams.set('perPage', perPage);
            newParams.set('page', 1); // Reset về trang 1 khi thay đổi số bản ghi/trang
            
            // Giữ lại các tham số quan trọng
            paramsToKeep.forEach(param => {
                if (urlParams.has(param) && urlParams.get(param)) {
                    newParams.set(param, urlParams.get(param));
                }
            });
            
            // Chuyển hướng đến URL mới
            window.location.href = window.location.pathname + '?' + newParams.toString();
        });
    });
</script>
<?= $this->endSection() ?> 