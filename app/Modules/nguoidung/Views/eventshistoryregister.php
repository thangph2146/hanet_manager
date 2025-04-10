<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/eventshistoryregister.css') ?>">
<style>
/* CSS cho responsive layout dạng danh sách */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Debug Log Styles */
.debug-section {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    margin-bottom: 20px;
    padding: 15px;
    font-family: monospace;
    overflow-x: auto;
}

.debug-section h4 {
    color: #343a40;
    font-size: 1.2rem;
    margin-bottom: 10px;
    border-bottom: 1px solid #ced4da;
    padding-bottom: 8px;
}

.debug-section pre {
    background-color: #e9ecef;
    padding: 10px;
    border-radius: 4px;
    font-size: 0.9rem;
    color: #212529;
    margin: 0;
    max-height: 300px;
    overflow-y: auto;
}

.debug-section .btn-toggle {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    margin-bottom: 10px;
    font-size: 0.85rem;
    line-height: 1.5;
    border-radius: 0.2rem;
    color: #fff;
    background-color: #6c757d;
    border: 1px solid #6c757d;
    cursor: pointer;
}

.debug-section .copy-btn {
    font-size: 0.8rem;
    padding: 0.2rem 0.4rem;
    margin-bottom: 5px;
    background-color: #17a2b8;
    border: 1px solid #17a2b8;
}

.debug-section .copy-btn:hover {
    background-color: #138496;
    border-color: #117a8b;
}

.debug-section .copy-all-btn {
    background-color: #007bff;
    border: 1px solid #007bff;
}

.debug-section .copy-all-btn:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.copy-tooltip {
    animation: fadeInOut 2s ease-in-out;
}

@keyframes fadeInOut {
    0% { opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; }
}

.debug-section .collapsible {
    display: none;
}

.statistics-card {
    transition: all 0.3s ease;
    margin-bottom: 20px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.event-card-container {
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.event-card-container .card {
    background-color: #fff;
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.event-card-container .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.page-header {
    padding: 20px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.page-header h2 {
    font-weight: 700;
    margin-bottom: 0;
}

.event-date-badge {
    min-width: 60px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 8px;
}

.event-timeline {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    height: 100%;
}

.event-card-container .btn {
    border-radius: 6px;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
    transition: all 0.2s;
}

.event-card-container .btn:hover {
    transform: translateY(-2px);
}

/* Breakpoints for responsive design */
/* Large desktop */
@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
    
    .event-card-container .card-body {
        padding: 1.5rem;
    }
}

/* Desktop */
@media (min-width: 992px) and (max-width: 1199px) {
    .container {
        max-width: 960px;
    }
    
    .event-card-container .card-body {
        padding: 1.25rem;
    }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 991px) {
    .container {
        max-width: 720px;
    }
    
    .page-header h2 {
        font-size: 1.75rem;
    }
    
    .event-card-container .card-body {
        padding: 1.1rem;
    }
}

/* Mobile landscape */
@media (min-width: 576px) and (max-width: 767.98px) {
    .container {
        max-width: 540px;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .event-card-container .card-body {
        padding: 1rem;
    }
    
    .event-card-container h5.card-title {
        font-size: 1rem;
    }
    
    .event-timeline {
        margin-top: 10px;
    }
}

/* Mobile portrait */
@media (max-width: 575.98px) {
    .page-header h2 {
        font-size: 1.25rem;
    }
    
    .event-card-container .card-body {
        padding: 0.8rem;
    }
    
    .event-card-container h5.card-title {
        font-size: 0.9rem;
    }
    
    .event-card-container .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .event-timeline {
        padding: 6px;
        margin-top: 8px;
    }
    
    .statistics-card .card-title {
        font-size: 0.9rem;
    }
    
    .statistics-card .h1 {
        font-size: 1.5rem;
    }
}

/* Thêm hiệu ứng khi hover vào thẻ */
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* CSS cho filter buttons */
.filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.filter-btn {
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 0.85rem;
    transition: all 0.2s;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
}

.filter-btn:hover {
    background-color: #e9ecef;
}

.filter-btn.active {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

/* CSS cho empty state */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    margin: 20px 0;
}

.empty-icon {
    font-size: 4rem;
    color: #adb5bd;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #495057;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 20px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 767.98px) {
    .empty-state {
        padding: 30px 15px;
    }
    
    .empty-icon {
        font-size: 3rem;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
    }
    
    .empty-state p {
        font-size: 0.9rem;
    }
}

/* CSS cho các thẻ thống kê sự kiện */
.donut-chart-container {
    position: relative;
    width: 180px;
    height: 180px;
    margin: 0 auto;
}

.donut-chart-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.status-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* CSS cho trang trống */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}

.empty-filter-results {
    background-color: #f8f9fa;
    border-radius: 10px;
    margin-top: 20px;
}

.debug-section .export-btn {
    background-color: #28a745;
    border: 1px solid #28a745;
}

.debug-section .export-btn:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.debug-section .copy-status-alert {
    margin: 5px 0 15px 0;
    padding: 5px 10px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    opacity: 0;
}

.debug-section .copy-status-alert.show {
    opacity: 1;
}

.debug-section pre.debug-pre {
    position: relative;
    counter-reset: line;
    padding: 10px;
    border-radius: 4px;
    font-size: 0.9rem;
    color: #212529;
    margin: 0;
    max-height: 300px;
    overflow-y: auto;
}

.debug-section pre.debug-pre.with-line-numbers {
    padding-left: 50px;
}

.debug-section pre.debug-pre.with-line-numbers span.line {
    counter-increment: line;
    display: inline-block;
    width: calc(100% - 5px);
    position: relative;
}

.debug-section pre.debug-pre.with-line-numbers span.line:before {
    content: counter(line);
    position: absolute;
    left: -45px;
    color: #6c757d;
    text-align: right;
    width: 35px;
    font-size: 0.8rem;
    border-right: 1px solid #dee2e6;
    padding-right: 5px;
    user-select: none;
}

.debug-section pre.debug-pre.with-line-numbers span.line:hover {
    background-color: #f8f9fa;
}

.debug-section pre.debug-pre.with-line-numbers span.line:hover:after {
    content: "Copy";
    position: absolute;
    right: 5px;
    font-size: 0.7rem;
    background: #17a2b8;
    color: white;
    padding: 2px 5px;
    border-radius: 3px;
    cursor: pointer;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="py-4">
    <?php if (ENVIRONMENT === 'development'): ?>
    <!-- DEBUG LOG SECTION - CHỈ HIỂN THỊ TRONG MÔI TRƯỜNG DEVELOPMENT -->
    <div class="debug-section">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <button class="btn-toggle" onclick="toggleDebug()">Hiển thị/Ẩn Debug Log</button>
            <div>
                <button class="btn-toggle export-btn me-2" onclick="exportDebugLog()">
                    <i class="fas fa-file-download me-1"></i> Xuất file
                </button>
                <button class="btn-toggle copy-all-btn" onclick="copyAllDebugInfo()">
                    <i class="fas fa-copy me-1"></i> Copy tất cả
                </button>
            </div>
        </div>
        
        <!-- Hiển thị trạng thái copy -->
        <div id="copyStatus" class="copy-status-alert alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <span id="copyStatusText">Đã copy thành công!</span>
        </div>
        
        <div class="collapsible" id="debugContent">
            <h4>Debug Information <span class="badge bg-warning">Development Only</span></h4>

            <div class="d-flex justify-content-between align-items-center">
                <h5>Registered Events Data:</h5>
                <div>
                    <button class="btn-toggle copy-btn me-1" onclick="copyDebugSection('registeredEventsData')">
                        <i class="fas fa-copy me-1"></i> Copy tất cả
                    </button>
                    <button class="btn-toggle copy-btn" onclick="toggleLineNumbers('registeredEventsData')">
                        <i class="fas fa-list-ol me-1"></i> Dòng
                    </button>
                </div>
            </div>
            <pre id="registeredEventsData" class="debug-pre"><?php 
                // Hiển thị dữ liệu từ biến registeredEvents
                if (isset($registeredEvents)) {
                    echo "Số lượng sự kiện: " . count($registeredEvents) . "\n\n";
                    
                    if (!empty($registeredEvents)) {
                        echo "Thông tin sự kiện đầu tiên:\n";
                        echo "-------------------------\n";
                        $firstEvent = reset($registeredEvents);
                        foreach ($firstEvent as $key => $value) {
                            if (is_object($value) || is_array($value)) {
                                echo "$key: " . json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
                            } else {
                                echo "$key: $value\n";
                            }
                        }
                    }
                } 
                // Hiển thị dữ liệu từ biến firstEvent (mới cung cấp)
                else {
                    // Dữ liệu mẫu khi không có dữ liệu thực
                    echo "Số lượng sự kiện: 3\n\n";
                    
                    echo "Thông tin sự kiện đầu tiên:\n";
                    echo "-------------------------\n";
                    echo "dangky_sukien_id: 103\n";
                    echo "nguoi_dung_id: 6\n";
                    echo "su_kien_id: 3\n";
                    echo "email: nguyenvana@example.com\n";
                    echo "ho_ten: Nguyễn Văn Tiến Anh\n";
                    echo "nguoi_dung_id: ACC001\n";
                    echo "dien_thoai: \n";
                    echo "so_dien_thoai: 0123456789\n";
                    echo "loai_nguoi_dang_ky: khach\n";
                    echo "ngay_dang_ky: 2025-04-06 15:00:47\n";
                    echo "ma_xac_nhan: \n";
                    echo "status: 1\n";
                    echo "noi_dung_gop_y: \n";
                    echo "nguon_gioi_thieu: \n";
                    echo "don_vi_to_chuc: Trường Đại học Ngân hàng TP.HCM\n";
                    echo "face_image_path: uploads/avatars/6_1743914737.jpg\n";
                    echo "face_verified: 0\n";
                    echo "da_check_in: 0\n";
                    echo "da_check_out: 0\n";
                    echo "checkin_sukien_id: \n";
                    echo "checkout_sukien_id: \n";
                    echo "thoi_gian_duyet: \n";
                    echo "thoi_gian_huy: \n";
                    echo "ly_do_huy: \n";
                    echo "hinh_thuc_tham_gia: offline\n";
                    echo "attendance_status: not_attended\n";
                    echo "attendance_minutes: 0\n";
                    echo "diem_danh_bang: none\n";
                    echo "thong_tin_dang_ky: \n";
                    echo "ly_do_tham_du: \n";
                    echo "created_at: 2023-05-10 09:45:00\n";
                    echo "updated_at: 2025-04-07 13:49:03\n";
                    echo "deleted_at: \n";
                    echo "ten_su_kien: Workshop \"Kỹ năng phân tích dữ liệu trong lĩnh vực tài chính\"\n";
                    echo "su_kien_poster: {\"original\":\"assets/images/event-3.jpg\",\"thumbnail\":\"assets/images/event-3_thumb.jpg\",\"alt_text\":\"Poster Workshop \\\"Kỹ năng phân tích dữ liệu trong lĩnh vực tài chính\\\"\"}\n";
                    echo "mo_ta: Học hỏi các kỹ năng phân tích dữ liệu cơ bản và nâng cao, ứng dụng thực tế trong ngành tài chính.\n";
                    echo "mo_ta_su_kien: Học hỏi các kỹ năng phân tích dữ liệu cơ bản và nâng cao, ứng dụng thực tế trong ngành tài chính.\n";
                    echo "chi_tiet_su_kien: Workshop \"Kỹ năng phân tích dữ liệu trong lĩnh vực tài chính\" được tổ chức nhằm giúp sinh viên và những người làm việc trong ngành tài chính nắm bắt được các kỹ năng phân tích dữ liệu cơ bản và nâng cao, từ đó có thể áp dụng vào công việc thực tế.\n";
                    echo "thoi_gian_bat_dau: 2025-06-30 13:30:00\n";
                    echo "thoi_gian_ket_thuc: 2025-06-30 17:00:00\n";
                    echo "thoi_gian_checkin_bat_dau: 2025-04-03 14:44:14\n";
                    echo "thoi_gian_checkin_ket_thuc: 2025-04-03 14:44:14\n";
                    echo "don_vi_phoi_hop: Trường Đại học Ngân hàng TP.HCM\n";
                    echo "doi_tuong_tham_gia: Tất cả\n";
                    echo "dia_diem: CS Hàm Nghi\n";
                    echo "dia_chi_cu_the: Địa chỉ cụ thể 3\n";
                    echo "toa_do_gps: 11.373523,107.727844\n";
                    echo "loai_su_kien_id: 10\n";
                    echo "ma_qr_code: QR_EVENT_3_35763\n";
                    echo "tong_dang_ky: 47\n";
                    echo "tong_check_in: 78\n";
                    echo "tong_check_out: 97\n";
                    echo "cho_phep_check_in: 1\n";
                    echo "cho_phep_check_out: 1\n";
                    echo "yeu_cau_face_id: 0\n";
                    echo "cho_phep_checkin_thu_cong: 1\n";
                    echo "bat_dau_dang_ky: 2025-06-01 00:00:00\n";
                    echo "ket_thuc_dang_ky: 2025-06-29 23:59:59\n";
                    echo "han_huy_dang_ky: \n";
                    echo "gio_bat_dau: 2025-06-30 13:30:00\n";
                    echo "gio_ket_thuc: 2025-06-30 17:00:00\n";
                    echo "so_luong_tham_gia: 100\n";
                    echo "so_luong_dien_gia: 2\n";
                    echo "gioi_han_loai_nguoi_dung: Sinh viên, Giảng viên, Cựu sinh viên, Đơn vị ngoài\n";
                    echo "tu_khoa_su_kien: workshop, kỹ năng, phân tích dữ liệu, tài chính\n";
                    echo "hashtag: #DataAnalytics #FinancialData #SkillDevelopment\n";
                    echo "slug: workshop-ky-nang-phan-tich-du-lieu-trong-linh-vuc-tai-chinh-3.html\n";
                    echo "so_luot_xem: 859\n";
                    echo "lich_trinh: [{\"tieu_de\":\"Đăng ký và khai mạc\",\"mo_ta\":\"Mô tả chi tiết cho phiên 1\",\"thoi_gian_bat_dau\":\"2025-04-03 14:44:14\",\"thoi_gian_ket_thuc\":\"2025-04-03 14:44:14\",\"nguoi_phu_trach\":\"Ban tổ chức\"},{\"tieu_de\":\"Phiên thảo luận chính\",\"mo_ta\":\"Mô tả chi tiết cho phiên 2\",\"thoi_gian_bat_dau\":\"2025-04-03 14:44:14\",\"thoi_gian_ket_thuc\":\"2025-04-03 14:44:14\",\"nguoi_phu_trach\":\"Diễn giả 2\"},{\"tieu_de\":\"Giải lao\",\"mo_ta\":\"Mô tả chi tiết cho phiên 3\",\"thoi_gian_bat_dau\":\"2025-04-03 14:44:14\",\"thoi_gian_ket_thuc\":\"2025-04-03 14:44:14\",\"nguoi_phu_trach\":\"Diễn giả 3\"},{\"tieu_de\":\"Phát biểu của khách mời\",\"mo_ta\":\"Mô tả chi tiết cho phiên 4\",\"thoi_gian_bat_dau\":\"2025-04-03 14:44:14\",\"thoi_gian_ket_thuc\":\"2025-04-03 14:44:14\",\"nguoi_phu_trach\":\"Ban tổ chức\"},{\"tieu_de\":\"Workshop thực hành\",\"mo_ta\":\"Mô tả chi tiết cho phiên 5\",\"thoi_gian_bat_dau\":\"2025-04-03 14:44:14\",\"thoi_gian_ket_thuc\":\"2025-04-03 14:44:14\",\"nguoi_phu_trach\":\"Diễn giả 5\"}]\n";
                    echo "hinh_thuc: hybrid\n";
                    echo "link_online: \n";
                    echo "mat_khau_online: \n";
                    echo "version: 1\n";
                    echo "ngay_to_chuc: 2025-06-30 13:30:00\n";
                }
            ?></pre>
            
            <div class="d-flex justify-content-between align-items-center">
                <h5>Thông tin người dùng:</h5>
                <div>
                    <button class="btn-toggle copy-btn me-1" onclick="copyDebugSection('profileData')">
                        <i class="fas fa-copy me-1"></i> Copy tất cả
                    </button>
                    <button class="btn-toggle copy-btn" onclick="toggleLineNumbers('profileData')">
                        <i class="fas fa-list-ol me-1"></i> Dòng
                    </button>
                </div>
            </div>
            <pre id="profileData" class="debug-pre"><?php 
                if (isset($profile)) {
                    foreach ($profile as $key => $value) {
                        if (is_object($value) || is_array($value)) {
                            echo "$key: " . json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
                        } else {
                            echo "$key: $value\n";
                        }
                    }
                } else {
                    echo "Không có dữ liệu profile";
                }
            ?></pre>
            
            <div class="d-flex justify-content-between align-items-center">
                <h5>Current Filter Data:</h5>
                <div>
                    <button class="btn-toggle copy-btn me-1" onclick="copyDebugSection('currentFilterData')">
                        <i class="fas fa-copy me-1"></i> Copy tất cả
                    </button>
                    <button class="btn-toggle copy-btn" onclick="toggleLineNumbers('currentFilterData')">
                        <i class="fas fa-list-ol me-1"></i> Dòng
                    </button>
                </div>
            </div>
            <pre id="currentFilterData" class="debug-pre"><?php 
                if (isset($current_filter)) {
                    echo json_encode($current_filter, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                } else {
                    echo "Không có dữ liệu current_filter";
                }
            ?></pre>
            
            <div class="d-flex justify-content-between align-items-center">
                <h5>All View Data:</h5>
                <div>
                    <button class="btn-toggle copy-btn me-1" onclick="copyDebugSection('allViewData')">
                        <i class="fas fa-copy me-1"></i> Copy tất cả
                    </button>
                    <button class="btn-toggle copy-btn" onclick="toggleLineNumbers('allViewData')">
                        <i class="fas fa-list-ol me-1"></i> Dòng
                    </button>
                </div>
            </div>
            <pre id="allViewData" class="debug-pre"><?php 
                $data = get_defined_vars();
                unset($data['registeredEvents']); // Đã hiển thị ở trên
                echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            ?></pre>
            
            <div class="mt-3">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Lưu ý:</strong> Thông tin debug này chỉ hiển thị ở môi trường phát triển. Vui lòng không hiển thị trong môi trường sản phẩm.
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleDebug() {
            var content = document.getElementById('debugContent');
            if (content.style.display === 'block') {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
        }
        
        // Ẩn debug log và thông báo trạng thái khi load trang
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('debugContent').style.display = 'none';
            document.getElementById('copyStatus').style.opacity = '0';
        });
        
        function copyDebugSection(elementId) {
            var contentElement = document.getElementById(elementId);
            var content = contentElement.textContent;
            
            navigator.clipboard.writeText(content)
                .then(() => {
                    // Hiển thị thông báo đã copy
                    showCopyStatus("Đã copy " + getSectionName(elementId) + " thành công!");
                })
                .catch(err => {
                    console.error('Không thể copy: ', err);
                    // Phương pháp dự phòng
                    fallbackCopyTextToClipboard(content, elementId);
                });
        }
        
        function getSectionName(elementId) {
            switch(elementId) {
                case 'registeredEventsData':
                    return 'dữ liệu sự kiện';
                case 'currentFilterData':
                    return 'dữ liệu bộ lọc';
                case 'allViewData':
                    return 'tất cả dữ liệu view';
                default:
                    return 'dữ liệu';
            }
        }
        
        function fallbackCopyTextToClipboard(text, elementId) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            
            // Làm cho textarea không hiển thị
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    showCopyStatus("Đã copy " + getSectionName(elementId) + " thành công!");
                }
            } catch (err) {
                console.error('Fallback: Không thể copy', err);
                showCopyStatus("Không thể copy: " + err, "danger");
            }
            
            document.body.removeChild(textArea);
        }
        
        function showCopyStatus(message, type = "success") {
            var statusElement = document.getElementById('copyStatus');
            var statusTextElement = document.getElementById('copyStatusText');
            
            // Cập nhật nội dung và loại thông báo
            statusTextElement.textContent = message;
            statusElement.className = 'copy-status-alert alert alert-' + type;
            
            // Hiển thị thông báo với hiệu ứng fade in
            statusElement.style.opacity = '1';
            
            // Tự động ẩn sau 3 giây
            setTimeout(function() {
                statusElement.style.opacity = '0';
            }, 3000);
        }
        
        function showCopyTooltip(elementId) {
            // Tạo tooltip
            var tooltip = document.createElement('div');
            tooltip.textContent = 'Đã copy!';
            tooltip.className = 'copy-tooltip';
            tooltip.style.position = 'fixed';
            tooltip.style.padding = '5px 10px';
            tooltip.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
            tooltip.style.color = 'white';
            tooltip.style.borderRadius = '3px';
            tooltip.style.zIndex = '1000';
            
            // Lấy vị trí nút được nhấp
            var button = event.target.closest('button');
            var rect = button.getBoundingClientRect();
            
            // Đặt tooltip phía trên nút
            tooltip.style.left = (rect.left + rect.width/2 - 30) + 'px';
            tooltip.style.top = (rect.top - 30) + 'px';
            
            document.body.appendChild(tooltip);
            
            // Xóa tooltip sau 2 giây
            setTimeout(function() {
                document.body.removeChild(tooltip);
            }, 2000);
        }
        
        function copyAllDebugInfo() {
            var allContent = 
                document.getElementById('registeredEventsData').textContent + '\n\n' +
                document.getElementById('currentFilterData').textContent + '\n\n' +
                document.getElementById('allViewData').textContent;
            
            navigator.clipboard.writeText(allContent)
                .then(() => {
                    showCopyStatus("Đã copy tất cả thông tin debug thành công!");
                    showCopyTooltip('copy-all-btn');
                })
                .catch(err => {
                    fallbackCopyTextToClipboard(allContent, 'copy-all-btn');
                });
        }
        
        function toggleLineNumbers(elementId) {
            var preElement = document.getElementById(elementId);
            
            if (preElement.classList.contains('with-line-numbers')) {
                // Đã hiển thị số dòng, ẩn đi
                preElement.classList.remove('with-line-numbers');
                preElement.innerHTML = preElement.getAttribute('data-original-content');
                showCopyStatus("Đã ẩn số dòng", "info");
            } else {
                // Chưa hiển thị số dòng, hiển thị
                if (!preElement.hasAttribute('data-original-content')) {
                    preElement.setAttribute('data-original-content', preElement.innerHTML);
                }
                
                var content = preElement.innerHTML;
                var lines = content.split('\n');
                var newContent = '';
                
                for (var i = 0; i < lines.length; i++) {
                    if (lines[i].trim() !== '') {
                        newContent += '<span class="line" onclick="copyLine(this)">' + lines[i] + '</span>\n';
                    } else {
                        newContent += '<span class="line">&nbsp;</span>\n';
                    }
                }
                
                preElement.innerHTML = newContent;
                preElement.classList.add('with-line-numbers');
                showCopyStatus("Đã hiển thị số dòng. Nhấp vào dòng bất kỳ để copy nội dung dòng đó.", "info");
            }
        }
        
        function copyLine(lineElement) {
            var content = lineElement.textContent;
            
            navigator.clipboard.writeText(content)
                .then(() => {
                    showCopyStatus("Đã copy dòng: \"" + (content.length > 30 ? content.substring(0, 30) + '...' : content) + "\"");
                })
                .catch(err => {
                    console.error('Không thể copy dòng: ', err);
                    // Phương pháp dự phòng
                    var textArea = document.createElement("textarea");
                    textArea.value = content;
                    
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    try {
                        var successful = document.execCommand('copy');
                        if (successful) {
                            showCopyStatus("Đã copy dòng: \"" + (content.length > 30 ? content.substring(0, 30) + '...' : content) + "\"");
                        }
                    } catch (err) {
                        showCopyStatus("Không thể copy dòng", "danger");
                    }
                    
                    document.body.removeChild(textArea);
                });
        }

        function exportDebugLog() {
            // Lấy tất cả nội dung debug
            var allContent = 
                "====== THÔNG TIN DEBUG EVENTSHISTORYREGISTER ======\n" +
                "Thời gian xuất: " + new Date().toLocaleString() + "\n\n" +
                "====== REGISTERED EVENTS DATA ======\n" +
                document.getElementById('registeredEventsData').textContent + '\n\n' +
                "====== CURRENT FILTER DATA ======\n" +
                document.getElementById('currentFilterData').textContent + '\n\n' +
                "====== ALL VIEW DATA ======\n" +
                document.getElementById('allViewData').textContent;
            
            // Tạo file text để download
            var blob = new Blob([allContent], { type: 'text/plain;charset=utf-8' });
            var url = URL.createObjectURL(blob);
            
            // Tạo link download và click
            var downloadLink = document.createElement('a');
            var date = new Date();
            var fileName = 'debug_log_' + 
                date.getFullYear() + '-' + 
                ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
                ('0' + date.getDate()).slice(-2) + '_' + 
                ('0' + date.getHours()).slice(-2) + '-' + 
                ('0' + date.getMinutes()).slice(-2) + '.txt';
                
            downloadLink.href = url;
            downloadLink.download = fileName;
            
            // Thêm link vào document, click và xóa
            document.body.appendChild(downloadLink);
            downloadLink.click();
            
            // Cleanup
            setTimeout(function() {
                document.body.removeChild(downloadLink);
                URL.revokeObjectURL(url);
                
                // Hiển thị thông báo thành công
                showCopyStatus("Đã xuất file debug thành công!");
            }, 100);
        }
    </script>
    <!-- END DEBUG LOG SECTION -->
    <?php else: ?>
    <!-- Debug log chỉ hiển thị trong môi trường development -->
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header bg-primary bg-gradient text-white rounded-3 mb-4 p-4">
                <h2 class="page-title fw-bold"><i class="far fa-calendar-alt me-2"></i><?= $title ?? 'Lịch sử đăng ký sự kiện' ?></h2>
                <p class="page-description mb-0">Theo dõi tất cả các sự kiện bạn đã đăng ký tham gia</p>
            </div>
        </div>
    </div>
    
    <!-- Bộ lọc -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="statusFilter" class="form-label">Trạng thái</label>
                            <select class="form-select" id="statusFilter">
                                <option value="all">Tất cả</option>
                                <option value="3">Đã tham gia</option>
                                <option value="1">Đang chờ</option>
                                <option value="2">Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="timeFilter" class="form-label">Thời gian</label>
                            <select class="form-select" id="timeFilter">
                                <option value="all">Tất cả</option>
                                <option value="upcoming">Sắp diễn ra</option>
                                <option value="past">Đã diễn ra</option>
                                <option value="month">Tháng này</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="searchEvents" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="searchEvents" placeholder="Nhập tên sự kiện...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="applyFilter">
                                <i class="fas fa-filter me-1"></i> Lọc
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Đếm tổng số lượng sự kiện và phân loại theo trạng thái
    $totalEvents = count($registeredEvents ?? []);
    $attendedEvents = 0;
    $pendingEvents = 0;
    $cancelledEvents = 0;

    foreach ($registeredEvents ?? [] as $event) {
        if (isset($event->trang_thai_dang_ky)) {
            if ($event->trang_thai_dang_ky == 3) {
                $attendedEvents++;
            } else if ($event->trang_thai_dang_ky == 2) {
                $cancelledEvents++;
            } else {
                $pendingEvents++;
            }
        } else {
            // Mặc định là đang chờ nếu không có trạng thái
            $pendingEvents++;
        }
    }

    // Tính phần trăm
    $attendedPercent = $totalEvents > 0 ? round(($attendedEvents / $totalEvents) * 100) : 0;
    $pendingPercent = $totalEvents > 0 ? round(($pendingEvents / $totalEvents) * 100) : 0;
    $cancelledPercent = $totalEvents > 0 ? round(($cancelledEvents / $totalEvents) * 100) : 0;
    ?>

    <div class="row mb-4">
        <div class="col-lg-12">
            <!-- Thẻ thống kê -->
            <div class="card statistics-card shadow-sm border-0">
                <div class="card-header bg-white p-3 border-bottom">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie text-primary me-2"></i>Thống kê sự kiện</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Tổng số sự kiện</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?= isset($totalEvents) ? $totalEvents : ($attendedEvents + $pendingEvents + $cancelledEvents) ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Đã tham gia</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?= isset($attendedEvents) ? $attendedEvents : 0 ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card pending-card">
                                <div class="card-body">
                                    <h5 class="card-title">Đang chờ</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-hourglass-split"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?= isset($pendingEvents) ? $pendingEvents : 0 ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card cancelled-card">
                                <div class="card-body">
                                    <h5 class="card-title">Đã hủy</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-x-circle"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?= isset($cancelledEvents) ? $cancelledEvents : 0 ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list text-primary me-2"></i>Danh sách sự kiện
                        </h5>
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" class="form-control" placeholder="Tìm kiếm sự kiện..." id="searchEvent">
                            <button class="btn btn-outline-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($registeredEvents)): ?>
                    <!-- Hiển thị trạng thái trống -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3>Chưa có sự kiện nào được đăng ký</h3>
                        <p>Bạn chưa đăng ký tham gia sự kiện nào. Hãy khám phá các sự kiện hiện có để đăng ký tham gia!</p>
                        <a href="<?= site_url('sukien') ?>" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Khám phá sự kiện
                        </a>
                    </div>
                    <?php else: ?>
                    <!-- Danh sách thẻ sự kiện -->
                    <div class="event-cards">
                        <?php 
                        // Kiểm tra nếu $registeredEvents là một mảng và không rỗng
                        if (is_array($registeredEvents) && count($registeredEvents) > 0):
                        foreach ($registeredEvents as $event): 
                            // Xác định trạng thái đăng ký
                            $status = isset($event->trang_thai_dang_ky) ? $event->trang_thai_dang_ky : 1;
                            
                            // Định dạng màu sắc và văn bản trạng thái
                            $statusClass = 'warning';
                            $statusText = 'Đang chờ';
                            $statusIcon = 'clock';
                            
                            if ($status == 2) {
                                $statusClass = 'danger';
                                $statusText = 'Đã hủy';
                                $statusIcon = 'times';
                            } else if ($status == 3) {
                                $statusClass = 'success';
                                $statusText = 'Đã tham gia';
                                $statusIcon = 'check';
                            }
                            
                            // Định dạng ngày giờ sự kiện
                            $eventDate = isset($event->ngay_bat_dau) ? date('Y-m-d', strtotime($event->ngay_bat_dau)) : '';
                            $eventTime = isset($event->ngay_bat_dau) ? date('H:i', strtotime($event->ngay_bat_dau)) : '';
                            $eventDay = isset($event->ngay_bat_dau) ? date('d', strtotime($event->ngay_bat_dau)) : '';
                            $eventMonth = isset($event->ngay_bat_dau) ? date('m', strtotime($event->ngay_bat_dau)) : '';
                            $eventYear = isset($event->ngay_bat_dau) ? date('Y', strtotime($event->ngay_bat_dau)) : '';
                            
                            // Định dạng ngày đăng ký
                            $registrationDate = isset($event->ngay_dang_ky) ? date('d/m/Y H:i', strtotime($event->ngay_dang_ky)) : '';
                            
                            // Định dạng check-in, check-out
                            $checkinDate = isset($event->ngay_checkin) ? date('d/m/Y H:i', strtotime($event->ngay_checkin)) : '';
                            $checkoutDate = isset($event->ngay_checkout) ? date('d/m/Y H:i', strtotime($event->ngay_checkout)) : '';
                        ?>
                        <div class="event-card-container mb-3" 
                             data-event-status="<?= $status ?>"
                             data-event-date="<?= $eventDate ?>">
                            <div class="card hover-shadow border-start border-<?= $statusClass ?> border-4">
                                <div class="row g-0">
                                    <!-- Hình ảnh sự kiện -->
                                    <div class="col-md-3 col-lg-2">
                                        <div class="position-relative h-100">
                                            <img src="<?= base_url('uploads/sukien/' . ($event->hinh_anh ?? 'default-event.jpg')) ?>" 
                                                 class="img-fluid rounded-start h-100" 
                                                 alt="<?= esc($event->ten_su_kien ?? 'Sự kiện') ?>"
                                                 style="object-fit: cover; width: 100%; min-height: 120px;">
                                            
                                            <!-- Badge trạng thái -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-<?= $statusClass ?> p-2">
                                                    <i class="fas fa-<?= $statusIcon ?> me-1"></i><?= $statusText ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Thông tin sự kiện -->
                                    <div class="col-md-9 col-lg-10">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <!-- Tiêu đề sự kiện -->
                                                <h5 class="card-title fw-bold mb-1">
                                                    <?= esc($event->ten_su_kien ?? 'Sự kiện không xác định') ?>
                                                </h5>
                                                
                                                <!-- Ngày diễn ra -->
                                                <div class="event-date-badge text-center bg-light rounded p-2 ms-3 d-none d-md-block">
                                                    <div class="event-day fw-bold"><?= $eventDay ?></div>
                                                    <div class="event-year small text-muted"><?= $eventYear ?></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Thông tin cơ bản -->
                                            <div class="row mb-3">
                                                <div class="col-md-8">
                                                    <?php if (isset($event->dia_diem)): ?>
                                                    <div class="d-flex align-items-center text-muted mb-1">
                                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                        <span><?= esc($event->dia_diem) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="d-flex align-items-center text-muted mb-1">
                                                        <i class="far fa-calendar-alt text-primary me-2"></i>
                                                        <span>
                                                            <?= isset($event->ngay_bat_dau) ? date('d/m/Y', strtotime($event->ngay_bat_dau)) : 'Chưa xác định' ?>
                                                            <?php if (!empty($eventTime)): ?>
                                                                <i class="fas fa-clock ms-2 me-1"></i><?= $eventTime ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    
                                                    <?php if (isset($event->to_chuc) || isset($event->ban_to_chuc)): ?>
                                                    <div class="d-flex align-items-center text-muted mb-1">
                                                        <i class="fas fa-user-tie text-info me-2"></i>
                                                        <span><?= esc($event->to_chuc ?? $event->ban_to_chuc ?? '') ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="event-timeline small">
                                                        <div class="mb-1 d-flex align-items-center">
                                                            <p style="font-size: 15px;"><?= $registrationDate ?: 'N/A' ?></p>
                                                        </div>
                                                        
                                                        <?php if ($status == 3 && !empty($checkinDate)): ?>
                                                        <div class="mb-1 d-flex align-items-center">
                                                            <span class="badge bg-success me-2">Check-in</span>
                                                            <span class="text-muted"><?= $checkinDate ?></span>
                                                        </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($status == 3 && !empty($checkoutDate)): ?>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-info me-2">Check-out</span>
                                                            <span class="text-muted"><?= $checkoutDate ?></span>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Card footer với các nút tương tác -->
                                            <div class="d-flex justify-content-end mt-2">
                                                <?php if (isset($event->chung_chi) && !empty($event->chung_chi) && isset($event->da_check_in) && $event->da_check_in == 1): ?>
                                                <a href="<?= base_url('uploads/chungchi/' . $event->chung_chi) ?>" 
                                                   class="btn btn-outline-success me-2" 
                                                   target="_blank"
                                                   data-toggle="tooltip" 
                                                   title="Xem chứng chỉ tham gia">
                                                    <i class="fas fa-award me-1"></i>Chứng chỉ
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if ($status == 1): ?>
                                                <a href="<?= site_url('nguoi-dung/huy-dang-ky-su-kien/' . $event->su_kien_id) ?>" 
                                                   class="btn btn-outline-danger me-2" 
                                                   data-toggle="tooltip" 
                                                   title="Hủy đăng ký tham gia"
                                                   onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?');">
                                                    <i class="fas fa-times me-1"></i>Hủy đăng ký
                                                </a>
                                                <?php endif; ?>
                                                
                                                <a href="<?= site_url('su-kien/detail/' . ($event->slug ?? '')) ?>" 
                                                   class="btn btn-primary" 
                                                   data-toggle="tooltip" 
                                                   title="Xem chi tiết sự kiện">
                                                    <i class="fas fa-info-circle me-1"></i>Chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; 
                        else: ?>
                        <!-- Không có dữ liệu sự kiện -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Không tìm thấy sự kiện nào. Có thể do lỗi dữ liệu hoặc sự kiện đã bị xóa.
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Khởi tạo tooltip Bootstrap
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Khởi tạo biểu đồ thống kê sự kiện
    var ctx = document.getElementById('eventStatusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Đã tham gia', 'Đang chờ', 'Đã hủy'],
                datasets: [{
                    data: [
                        <?= isset($attendedEvents) ? $attendedEvents : 0 ?>, 
                        <?= isset($pendingEvents) ? $pendingEvents : 0 ?>, 
                        <?= isset($cancelledEvents) ? $cancelledEvents : 0 ?>
                    ],
                    backgroundColor: ['#2eca6a', '#ff9b44', '#f34e4e'],
                    borderWidth: 0,
                    cutout: '65%'
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: false
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Xử lý khi click vào lọc theo thời gian
    $('.date-filter').click(function() {
        // ... existing code ...
    });
    
    // Xử lý lọc sự kiện
    $('#applyFilter').on('click', function() {
        filterEvents();
    });
    
    $('#searchEvents').on('keyup', function(e) {
        if (e.key === 'Enter') {
            filterEvents();
        }
    });
    
    function filterEvents() {
        const statusFilter = $('#statusFilter').val();
        const timeFilter = $('#timeFilter').val();
        const searchText = $('#searchEvents').val().toLowerCase();
        
        $('.event-card-container').each(function() {
            let showCard = true;
            
            // Lọc theo trạng thái
            if (statusFilter !== 'all') {
                const cardStatus = $(this).data('event-status');
                if (cardStatus != statusFilter) {
                    showCard = false;
                }
            }
            
            // Lọc theo thời gian
            if (timeFilter !== 'all' && showCard) {
                const eventDate = new Date($(this).data('event-date'));
                const today = new Date();
                
                if (timeFilter === 'upcoming' && eventDate < today) {
                    showCard = false;
                } else if (timeFilter === 'past' && eventDate > today) {
                    showCard = false;
                } else if (timeFilter === 'month') {
                    const currentMonth = today.getMonth();
                    const currentYear = today.getFullYear();
                    if (eventDate.getMonth() !== currentMonth || eventDate.getFullYear() !== currentYear) {
                        showCard = false;
                    }
                }
            }
            
            // Lọc theo tìm kiếm
            if (searchText && showCard) {
                const eventTitle = $(this).find('.card-title').text().toLowerCase();
                if (!eventTitle.includes(searchText)) {
                    showCard = false;
                }
            }
            
            // Hiển thị hoặc ẩn thẻ
            if (showCard) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Hiển thị thông báo nếu không có kết quả
        checkEmptyResults();
    }
    
    function checkEmptyResults() {
        const visibleCards = $('.event-card-container:visible').length;
        
        if (visibleCards === 0) {
            // Nếu không có thẻ nào hiển thị, hiển thị thông báo "không có kết quả"
            if ($('.empty-results-message').length === 0) {
                $('.event-cards').append(`
                    <div class="empty-results-message alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Không tìm thấy sự kiện nào phù hợp với bộ lọc. Vui lòng thử lại với các tiêu chí khác.
                    </div>
                `);
            }
        } else {
            // Nếu có thẻ hiển thị, ẩn thông báo
            $('.empty-results-message').remove();
        }
    }
});
</script>
<?= $this->endSection() ?>

