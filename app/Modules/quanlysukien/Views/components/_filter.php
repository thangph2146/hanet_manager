<?php
/**
 * Component hiển thị form lọc dữ liệu sự kiện
 */
$perPageOptions = [10, 25, 50, 100];
$statusOptions = [
    '' => 'Tất cả trạng thái',
    '1' => 'Hoạt động',
    '0' => 'Vô hiệu'
];

$hinhThucOptions = [
    '' => 'Tất cả hình thức',
    'offline' => 'Trực tiếp',
    'online' => 'Trực tuyến',
    'hybrid' => 'Kết hợp'
];

// Lấy danh sách loại sự kiện
$loaiSuKienModel = model('App\Modules\quanlyloaisukien\Models\LoaiSuKienModel');
$loaiSuKienList = $loaiSuKienModel->getForDropdown(true);

// Debug Information - Chỉ hiển thị trong môi trường development
if (ENVIRONMENT === 'development'):
?>
<style>
.debug-section {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 15px;
    margin: 15px 0;
    font-family: monospace;
}

.debug-section pre {
    margin: 0;
    white-space: pre-wrap;
    max-height: 400px;
    overflow-y: auto;
    background: #f1f1f1;
    padding: 10px;
    border-radius: 4px;
    position: relative;
}

.debug-title {
    color: #dc3545;
    font-weight: bold;
    margin-bottom: 10px;
    font-size: 1.1em;
    display: flex;
    justify-content: space-between;
}

.debug-subtitle {
    color: #0d6efd;
    font-weight: bold;
    margin: 10px 0;
    font-size: 1em;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.debug-subtitle:after {
    content: "▼";
    font-size: 0.8em;
    margin-left: 5px;
}

.debug-subtitle.collapsed:after {
    content: "►";
}

.debug-content {
    margin-bottom: 15px;
}

.debug-data {
    display: block;
}

.debug-data.collapsed {
    display: none;
}

.copy-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #198754;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 2px 8px;
    font-size: 0.8em;
    cursor: pointer;
    z-index: 5;
}

.copy-btn:hover {
    background: #157347;
}

.expand-all-btn {
    background: #0d6efd;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 2px 8px;
    font-size: 0.8em;
    cursor: pointer;
}

.expand-all-btn:hover {
    background: #0a58ca;
}
</style>

<div class="debug-section">
    <div class="debug-title">
        Debug Information
        <button class="expand-all-btn" onclick="toggleAllDebugSections()">Expand All</button>
    </div>
    
    <?php
    // Hiển thị tất cả các biến lọc đang được sử dụng
    $filterVariables = [
        'keyword' => $keyword ?? null,
        'loai_su_kien_id' => $loai_su_kien_id ?? null,
        'status' => $status ?? null,
        'hinh_thuc' => $hinh_thuc ?? null,
        'start_date' => $start_date ?? null,
        'end_date' => $end_date ?? null,
        'don_vi_to_chuc' => $don_vi_to_chuc ?? null,
        'perPage' => $perPage ?? 10,
        'upcoming' => $upcoming ?? null,
        'featured' => $featured ?? null,
        'cho_phep_check_in' => $cho_phep_check_in ?? null,
        'doi_tuong_tham_gia' => $doi_tuong_tham_gia ?? null,
        'sort' => $sort ?? 'thoi_gian_bat_dau',
        'order' => $order ?? 'asc',
        'bat_dau_dang_ky' => $bat_dau_dang_ky ?? null,
        'ket_thuc_dang_ky' => $ket_thuc_dang_ky ?? null
    ];
    ?>
    
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">Applied Filters</div>
        <div class="debug-data">
            <pre id="applied-filters"><?php print_r($filterVariables); ?><button class="copy-btn" onclick="copyDebugData('applied-filters')">Copy</button></pre>
        </div>
    </div>
    
    <?php if (isset($filters)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">Filter Data</div>
        <div class="debug-data">
            <pre id="filter-data"><?php print_r($filters); ?><button class="copy-btn" onclick="copyDebugData('filter-data')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($_GET)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">GET Parameters</div>
        <div class="debug-data">
            <pre id="get-params"><?php print_r($_GET); ?><button class="copy-btn" onclick="copyDebugData('get-params')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($loaiSuKienList)): ?>
    <div class="debug-content">
        <div class="debug-subtitle collapsed" onclick="toggleDebugSection(this)">Loại Sự Kiện List</div>
        <div class="debug-data collapsed">
            <pre id="loai-sukien-list"><?php print_r($loaiSuKienList); ?><button class="copy-btn" onclick="copyDebugData('loai-sukien-list')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (isset($module_name)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">Module Information</div>
        <div class="debug-data">
            <pre id="module-info"><?php
            echo "Module Name: " . $module_name . "\n";
            echo "Current URL: " . current_url() . "\n"; 
            echo "PHP Version: " . phpversion() . "\n";
            echo "Environment: " . ENVIRONMENT . "\n";
            ?><button class="copy-btn" onclick="copyDebugData('module-info')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleDebugSection(element) {
    // Tìm phần tử debug-data kế tiếp
    var content = element.nextElementSibling;
    // Toggle class collapsed
    element.classList.toggle('collapsed');
    content.classList.toggle('collapsed');
}

function toggleAllDebugSections() {
    var btnText = document.querySelector('.expand-all-btn');
    var allSubtitles = document.querySelectorAll('.debug-subtitle');
    var allContents = document.querySelectorAll('.debug-data');
    
    if (btnText.textContent === 'Expand All') {
        btnText.textContent = 'Collapse All';
        allSubtitles.forEach(function(subtitle) {
            subtitle.classList.remove('collapsed');
        });
        allContents.forEach(function(content) {
            content.classList.remove('collapsed');
        });
    } else {
        btnText.textContent = 'Expand All';
        allSubtitles.forEach(function(subtitle) {
            subtitle.classList.add('collapsed');
        });
        allContents.forEach(function(content) {
            content.classList.add('collapsed');
        });
    }
}

function copyDebugData(id) {
    var text = document.getElementById(id).innerText;
    // Loại bỏ nút "Copy" khỏi nội dung copy
    text = text.replace('Copy', '').trim();
    
    // Sử dụng tryắthẹn để tương thích với nhiều trình duyệt
    try {
        // Phương pháp hiện đại
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showCopiedFeedback(id);
            });
        } else {
            // Phương pháp thay thế cho các trình duyệt cũ hơn
            fallbackCopyTextToClipboard(text, id);
        }
    } catch (err) {
        console.error('Không thể copy: ', err);
        alert('Không thể copy dữ liệu! Hãy thử phương pháp thủ công: Chọn văn bản và nhấn Ctrl+C.');
    }
}

function fallbackCopyTextToClipboard(text, id) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Tránh cuộn
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        var successful = document.execCommand('copy');
        if (successful) {
            showCopiedFeedback(id);
        } else {
            console.error('Fallback: Copy command was unsuccessful');
        }
    } catch (err) {
        console.error('Fallback: Không thể thực hiện copy', err);
    }

    document.body.removeChild(textArea);
}

function showCopiedFeedback(id) {
    var btn = document.querySelector('#' + id + ' .copy-btn');
    if (btn) {
        btn.textContent = 'Copied!';
        setTimeout(function() {
            btn.textContent = 'Copy';
        }, 2000);
    }
}

// Tự động mở rộng phần đầu tiên khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    // Mở phần đầu tiên
    var firstDebugContent = document.querySelector('.debug-content');
    if (firstDebugContent) {
        var subtitle = firstDebugContent.querySelector('.debug-subtitle');
        var data = firstDebugContent.querySelector('.debug-data');
        if (subtitle && data) {
            subtitle.classList.remove('collapsed');
            data.classList.remove('collapsed');
        }
    }
});
</script>
<?php 
endif; // End debug section 
?>

<div class="card-header p-0 border-0">
    <form action="<?= site_url($module_name) ?>" method="get" class="form-horizontal" id="filterForm">
        <div class="p-0">
            <div class="row mx-0 py-3">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Tìm kiếm theo tên, mô tả, ID..." value="<?= $keyword ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="loai_su_kien_id" id="loai_su_kien_id">
                                    <option value="">-- Loại sự kiện --</option>
                                    <?php foreach ($loaiSuKienList as $id => $name): ?>
                                    <option value="<?= $id ?>" <?= isset($loai_su_kien_id) && $loai_su_kien_id == $id ? 'selected' : '' ?>>
                                        <?= esc($name) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="status" id="status">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="1" <?= isset($status) && $status == '1' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= isset($status) && $status == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="hinh_thuc" id="hinh_thuc">
                                    <option value="">-- Hình thức --</option>
                                    <option value="offline" <?= isset($hinh_thuc) && $hinh_thuc == 'offline' ? 'selected' : '' ?>>Offline</option>
                                    <option value="online" <?= isset($hinh_thuc) && $hinh_thuc == 'online' ? 'selected' : '' ?>>Online</option>
                                    <option value="hybrid" <?= isset($hinh_thuc) && $hinh_thuc == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="start_date" id="start_date" placeholder="Ngày bắt đầu" value="<?= $start_date ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="end_date" id="end_date" placeholder="Ngày kết thúc" value="<?= $end_date ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" name="don_vi_to_chuc" id="don_vi_to_chuc" placeholder="Đơn vị tổ chức" value="<?= $don_vi_to_chuc ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="perPage" id="perPage">
                                    <?php foreach ($perPageOptions as $option): ?>
                                    <option value="<?= $option ?>" <?= ($perPage ?? 10) == $option ? 'selected' : '' ?>><?= $option ?> bản ghi</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="upcoming" id="upcoming">
                                    <option value="">-- Thời gian diễn ra --</option>
                                    <option value="1" <?= isset($upcoming) && $upcoming == '1' ? 'selected' : '' ?>>Sắp diễn ra</option>
                                    <option value="0" <?= isset($upcoming) && $upcoming == '0' ? 'selected' : '' ?>>Đã diễn ra</option>
                                    <option value="ongoing" <?= isset($upcoming) && $upcoming == 'ongoing' ? 'selected' : '' ?>>Đang diễn ra</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="featured" id="featured">
                                    <option value="">-- Sự kiện nổi bật --</option>
                                    <option value="1" <?= isset($featured) && $featured == '1' ? 'selected' : '' ?>>Nổi bật</option>
                                    <option value="0" <?= isset($featured) && $featured == '0' ? 'selected' : '' ?>>Không nổi bật</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="cho_phep_check_in" id="cho_phep_check_in">
                                    <option value="">-- Trạng thái check-in --</option>
                                    <option value="1" <?= isset($cho_phep_check_in) && $cho_phep_check_in == '1' ? 'selected' : '' ?>>Cho phép check-in</option>
                                    <option value="0" <?= isset($cho_phep_check_in) && $cho_phep_check_in == '0' ? 'selected' : '' ?>>Không cho phép check-in</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" name="doi_tuong_tham_gia" id="doi_tuong_tham_gia" placeholder="Đối tượng tham gia" value="<?= $doi_tuong_tham_gia ?? '' ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="sort" id="sort">
                                    <option value="thoi_gian_bat_dau" <?= ($sort ?? 'thoi_gian_bat_dau') == 'thoi_gian_bat_dau' ? 'selected' : '' ?>>Sắp xếp theo thời gian bắt đầu</option>
                                    <option value="thoi_gian_ket_thuc" <?= ($sort ?? '') == 'thoi_gian_ket_thuc' ? 'selected' : '' ?>>Sắp xếp theo thời gian kết thúc</option>
                                    <option value="ten_su_kien" <?= ($sort ?? '') == 'ten_su_kien' ? 'selected' : '' ?>>Sắp xếp theo tên sự kiện</option>
                                    <option value="created_at" <?= ($sort ?? '') == 'created_at' ? 'selected' : '' ?>>Sắp xếp theo ngày tạo</option>
                                    <option value="tong_dang_ky" <?= ($sort ?? '') == 'tong_dang_ky' ? 'selected' : '' ?>>Sắp xếp theo số lượng đăng ký</option>
                                    <option value="tong_check_in" <?= ($sort ?? '') == 'tong_check_in' ? 'selected' : '' ?>>Sắp xếp theo số lượng check-in</option>
                                    <option value="so_luong_tham_gia" <?= ($sort ?? '') == 'so_luong_tham_gia' ? 'selected' : '' ?>>Sắp xếp theo số lượng tham gia</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="order" id="order">
                                    <option value="DESC" <?= ($order ?? 'DESC') == 'DESC' ? 'selected' : '' ?>>Giảm dần</option>
                                    <option value="ASC" <?= ($order ?? '') == 'ASC' ? 'selected' : '' ?>>Tăng dần</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="bat_dau_dang_ky" id="bat_dau_dang_ky" placeholder="Bắt đầu đăng ký từ" value="<?= $bat_dau_dang_ky ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="ket_thuc_dang_ky" id="ket_thuc_dang_ky" placeholder="Kết thúc đăng ký đến" value="<?= $ket_thuc_dang_ky ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="<?= site_url($module_name) ?>" class="btn btn-secondary btn-block">
                        <i class="fas fa-sync"></i> Làm mới
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($keyword) || (isset($status) && $status !== '') || 
          (isset($loai_su_kien_id) && $loai_su_kien_id !== '') ||
          (isset($hinh_thuc) && $hinh_thuc !== '') ||
          (isset($start_date) && $start_date !== '') ||
          (isset($end_date) && $end_date !== '') ||
          (isset($don_vi_to_chuc) && $don_vi_to_chuc !== '') ||
          (isset($upcoming) && $upcoming !== '') ||
          (isset($featured) && $featured !== '')): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (isset($loai_su_kien_id) && $loai_su_kien_id !== ''): ?>
                <span class="badge bg-secondary me-2">Loại sự kiện: <?= esc($loaiSuKienList[$loai_su_kien_id] ?? '') ?></span>
            <?php endif; ?>
            
            <?php if (isset($hinh_thuc) && $hinh_thuc !== ''): ?>
                <span class="badge bg-success me-2">Hình thức: <?= $hinhThucOptions[$hinh_thuc] ?></span>
            <?php endif; ?>
            
            <?php if (isset($status) && $status !== ''): ?>
                <span class="badge bg-warning text-dark me-2">Trạng thái: <?= $statusOptions[$status] ?></span>
            <?php endif; ?>
            
            <?php if (isset($start_date) && $start_date !== ''): ?>
                <span class="badge bg-info me-2">Ngày bắt đầu: <?= esc($start_date) ?></span>
            <?php endif; ?>
            
            <?php if (isset($end_date) && $end_date !== ''): ?>
                <span class="badge bg-info me-2">Ngày kết thúc: <?= esc($end_date) ?></span>
            <?php endif; ?>
            
            <?php if (isset($don_vi_to_chuc) && $don_vi_to_chuc !== ''): ?>
                <span class="badge bg-dark me-2">Đơn vị tổ chức: <?= esc($don_vi_to_chuc) ?></span>
            <?php endif; ?>
            
            <?php if (isset($upcoming) && $upcoming !== ''): ?>
                <span class="badge bg-dark me-2">
                    <?= $upcoming == '1' ? 'Sắp diễn ra' : ($upcoming == '0' ? 'Đã diễn ra' : 'Đang diễn ra') ?>
                </span>
            <?php endif; ?>
            
            <?php if (isset($featured) && $featured !== ''): ?>
                <span class="badge bg-dark me-2">
                    <?= $featured == '1' ? 'Sự kiện nổi bật' : 'Không nổi bật' ?>
                </span>
            <?php endif; ?>
            
            <a href="<?= site_url($module_name) ?>" class="text-decoration-none">
                <i class="bx bx-x"></i> Xóa bộ lọc
            </a>
        </div>
    </div>
<?php endif; ?>