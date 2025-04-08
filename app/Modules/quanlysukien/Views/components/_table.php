<?php
/**
 * Component hiển thị bảng dữ liệu sự kiện
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu sự kiện
 * @var string $module_name Tên module
 */

// Lấy danh sách loại sự kiện để hiển thị tên loại sự kiện
$loaiSuKienModel = model('App\Modules\quanlyloaisukien\Models\LoaiSuKienModel');
$loaiSuKienList = $loaiSuKienModel->getForDropdown(false);

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
    
    <?php if (isset($processedData)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">Table Data</div>
        <div class="debug-data">
            <pre id="table-data-processed"><?php print_r($processedData); ?><button class="copy-btn" onclick="copyDebugData('table-data-processed')">Copy</button></pre>
        </div>
    </div>
    <?php elseif (isset($data)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">Table Data</div>
        <div class="debug-data">
            <pre id="table-data"><?php print_r($data); ?><button class="copy-btn" onclick="copyDebugData('table-data')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($pager)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">Pager</div>
        <div class="debug-data">
            <pre id="pager-data"><?php print_r($pager); ?><button class="copy-btn" onclick="copyDebugData('pager-data')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($filters)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">Filters</div>
        <div class="debug-data">
            <pre id="filters-data"><?php print_r($filters); ?><button class="copy-btn" onclick="copyDebugData('filters-data')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($_GET)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">GET Request</div>
        <div class="debug-data">
            <pre id="get-params"><?php print_r($_GET); ?><button class="copy-btn" onclick="copyDebugData('get-params')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($_POST)): ?>
    <div class="debug-content">
        <div class="debug-subtitle" onclick="toggleDebugSection(this)">POST Request</div>
        <div class="debug-data">
            <pre id="post-data"><?php print_r($_POST); ?><button class="copy-btn" onclick="copyDebugData('post-data')">Copy</button></pre>
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
    
    <?php if (isset($loaiSuKienList)): ?>
    <div class="debug-content">
        <div class="debug-subtitle collapsed" onclick="toggleDebugSection(this)">Loại Sự Kiện List</div>
        <div class="debug-data collapsed">
            <pre id="loai-sukien-list"><?php print_r($loaiSuKienList); ?><button class="copy-btn" onclick="copyDebugData('loai-sukien-list')">Copy</button></pre>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function toggleDebugSection(element) {
    var content = element.nextElementSibling;
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
    text = text.replace('Copy', '').trim();
    
    try {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showCopiedFeedback(id);
            });
        } else {
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

document.addEventListener('DOMContentLoaded', function() {
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

<div class="table-responsive">
    <div class="table-container">
        <table id="dataTable" class="table table-striped table-hover m-0 w-100">
            <thead class="table-light">
                <tr>
                    <th width="3%" class="text-center align-middle">
                        <div class="form-check">
                            <input type="checkbox" id="select-all" class="form-check-input cursor-pointer">
                        </div>
                    </th>
                    <th width="3%" class="align-middle">ID</th>
                    <th width="17%" class="align-middle">Tên sự kiện</th>
                    <th width="15%" class="align-middle">Thời gian</th>
                    <th width="12%" class="align-middle">Địa điểm</th>
                    <th width="10%" class="align-middle">Loại sự kiện</th>
                    <th width="10%" class="align-middle">Hình thức</th>
                    <th width="10%" class="align-middle">Tham gia</th>
                    <th width="5%" class="text-center align-middle">Trạng thái</th>
                    <th width="8%" class="text-center align-middle">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($processedData)) : ?>
                    <?php foreach ($processedData as $item) : ?>
                        <tr>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input checkbox-item cursor-pointer" 
                                           type="checkbox" name="selected_ids[]" 
                                           value="<?= $item->getId() ?>">
                                </div>
                            </td>
                            <td><?= $item->getId() ?></td>
                            <td>
                                <div class="fw-bold"><?= esc($item->getTenSuKien()) ?></div>
                                <?php if ($item->getSlug()): ?>
                                <div class="small text-muted"><?= $item->getSlug() ?></div>
                                <?php endif; ?>
                                <?php if ($item->getDonViToChuc()): ?>
                                <div class="small text-muted">
                                    <i class="bx bx-buildings me-1"></i><?= esc($item->getDonViToChuc()) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div>Từ: <?= $item->getThoiGianBatDauFormatted('d/m/Y H:i') ?></div>
                                <div>Đến: <?= $item->getThoiGianKetThucFormatted('d/m/Y H:i') ?></div>
                                <?php if ($item->getThoiGianCheckinBatDau()): ?>
                                <div class="small text-muted">
                                    <i class="bx bx-log-in me-1"></i>Check-in: 
                                    <?= $item->getThoiGianCheckinBatDauFormatted('d/m/Y H:i') ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?= esc($item->getDiaDiem()) ?></div>
                                <?php if ($item->getDiaChiCuThe()): ?>
                                <div class="small text-muted"><?= esc($item->getDiaChiCuThe()) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($item->getTenLoaiSuKien()) ?></td>
                            <td>
                                <?php $hinhThuc = $item->getHinhThuc(); ?>
                                <?php if ($hinhThuc == 'offline'): ?>
                                    <span class="badge bg-primary">Trực tiếp</span>
                                <?php elseif ($hinhThuc == 'online'): ?>
                                    <span class="badge bg-info">Trực tuyến</span>
                                    <?php if ($item->getLinkOnline()): ?>
                                    <div class="small mt-1">
                                        <a href="<?= esc($item->getLinkOnline()) ?>" target="_blank" class="text-primary">
                                            <i class="bx bx-link-external me-1"></i>Link
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                <?php elseif ($hinhThuc == 'hybrid'): ?>
                                    <span class="badge bg-secondary">Kết hợp</span>
                                <?php else: ?>
                                    <span class="badge bg-dark">Không xác định</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-user me-1"></i>
                                    <span>Giới hạn: <?= $item->getSoLuongThamGia() ?></span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="bx bx-user-check me-1 text-success"></i>
                                    <span class="text-success">Đăng ký: <?= $item->getTongDangKy() ?></span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="bx bx-log-in-circle me-1 text-info"></i>
                                    <span class="text-info">Check-in: <?= $item->getTongCheckIn() ?></span>
                                </div>
                            </td>
                            <td class="text-center text-black">
                                <?= $item->getStatusHtml() ?>
                                <?php if ($item->getSoLuotXem() > 0): ?>
                                <div class="small text-muted mt-1">
                                    <i class="bx bx-show me-1"></i><?= $item->getSoLuotXem() ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <a href="<?= $item->getDetailUrl() ?>" 
                                       class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                        <i class="bx bx-info-circle text-white"></i>
                                    </a>
                                    <a href="<?= $item->getEditUrl() ?>" 
                                       class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="<?= $item->getId() ?>" 
                                            data-name="<?= esc($item->getTenSuKien()) ?>"
                                            data-bs-toggle="tooltip" title="Xóa">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center py-3">
                            <div class="empty-state">
                                <i class="bx bx-folder-open"></i>
                                <p>Không có dữ liệu sự kiện</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 