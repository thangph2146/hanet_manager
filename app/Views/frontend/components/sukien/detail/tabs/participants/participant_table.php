<!-- CSS styles -->
<style>
    /* Responsive tables */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
        margin-bottom: 1rem;
    }
    .table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }
    
    /* Mobile tabs */
    .mobile-tabs {
        display: flex;
        white-space: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .mobile-tabs .nav-link {
        flex-shrink: 0;
        min-width: 100px;
        text-align: center;
    }
    
    /* Card styles */
    .participant-card {
        transition: transform 0.2s;
    }
    .participant-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Media queries */
    @media (max-width: 767.98px) {
        .table-responsive-container {
            padding: 0;
        }
    }
    @media (min-width: 768px) {
        .table th,
        .table td {
            padding: 0.75rem;
            white-space: nowrap;
        }
    }
</style>

<!-- Filter section -->
<div class="mb-4">
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" id="search-participant" placeholder="Tìm kiếm...">
                <button class="btn btn-outline-secondary" type="button"><i class="lni lni-search-alt"></i></button>
            </div>
        </div>
        <div class="col-md-6">
            <select class="form-select" id="filter-status">
                <option value="all">Tất cả trạng thái</option>
                <option value="1">Đã điểm danh</option>
                <option value="0">Chưa điểm danh</option>
            </select>
        </div>
    </div>
</div>

<!-- Participants table/cards container -->
<div class="table-responsive-container">
    <!-- Mobile navigation tabs -->
    <div class="d-md-none mb-3 overflow-auto">
        <div class="nav nav-tabs mobile-tabs" role="tablist">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-content" type="button" role="tab" aria-selected="true">Thông tin</button>
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-content" type="button" role="tab" aria-selected="false">Liên hệ</button>
            <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status-content" type="button" role="tab" aria-selected="false">Trạng thái</button>
        </div>
    </div>

    <!-- Mobile cards view -->
    <div class="d-md-none tab-content">
        <!-- Info tab -->
        <div class="tab-pane fade show active" id="info-content" role="tabpanel">
            <?php if (isset($registrations) && !empty($registrations)): ?>
                <?php foreach ($registrations as $index => $participant): ?>
                    <div class="card mb-3 participant-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= $participant['ho_ten'] ?></h6>
                            <?php if ($participant['da_tham_gia'] == 1): ?>
                                <span class="badge bg-success">Đã điểm danh</span>
                            <?php elseif ($participant['trang_thai'] == 0): ?>
                                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Đã xác nhận</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Mã SV/GV:</small>
                                <div><?= $participant['ma_sv'] ?? 'N/A' ?></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Khoa/Lớp:</small>
                                <div><?= $participant['khoa'] . ($participant['lop'] ? ' / ' . $participant['lop'] : '') ?></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Ngày đăng ký:</small>
                                <div><?= date('d/m/Y H:i', strtotime($participant['ngay_dang_ky'])) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    Chưa có người đăng ký tham gia sự kiện này
                </div>
            <?php endif; ?>
        </div>

        <!-- Contact tab -->
        <div class="tab-pane fade" id="contact-content" role="tabpanel">
            <?php if (isset($registrations) && !empty($registrations)): ?>
                <?php foreach ($registrations as $index => $participant): ?>
                    <div class="card mb-3 participant-card">
                        <div class="card-header">
                            <h6 class="mb-0"><?= $participant['ho_ten'] ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Email:</small>
                                <div><a href="mailto:<?= $participant['email'] ?>"><?= $participant['email'] ?></a></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Số điện thoại:</small>
                                <div><a href="tel:<?= $participant['so_dien_thoai'] ?>"><?= $participant['so_dien_thoai'] ?></a></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    Chưa có người đăng ký tham gia sự kiện này
                </div>
            <?php endif; ?>
        </div>

        <!-- Status tab -->
        <div class="tab-pane fade" id="status-content" role="tabpanel">
            <?php if (isset($registrations) && !empty($registrations)): ?>
                <?php foreach ($registrations as $index => $participant): ?>
                    <div class="card mb-3 participant-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= $participant['ho_ten'] ?></h6>
                            <?php if ($participant['da_tham_gia'] == 1): ?>
                                <span class="badge bg-success">Đã điểm danh</span>
                            <?php elseif ($participant['trang_thai'] == 0): ?>
                                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Đã xác nhận</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Ngày đăng ký:</small>
                                <div><?= date('d/m/Y H:i', strtotime($participant['ngay_dang_ky'])) ?></div>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">Trạng thái:</small>
                                <div>
                                    <?php if ($participant['trang_thai'] == 1): ?>
                                        Đã xác nhận
                                        <?php if ($participant['da_tham_gia'] == 1): ?>
                                            | Đã điểm danh
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Chờ xác nhận
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    Chưa có người đăng ký tham gia sự kiện này
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Desktop table view -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Họ và tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Số điện thoại</th>
                    <th scope="col">Mã SV/GV</th>
                    <th scope="col">Khoa/Lớp</th>
                    <th scope="col">Ngày đăng ký</th>
                    <th scope="col">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($registrations) && !empty($registrations)): ?>
                    <?php foreach ($registrations as $index => $participant): ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $participant['ho_ten'] ?></td>
                        <td><?= $participant['email'] ?></td>
                        <td><?= $participant['so_dien_thoai'] ?></td>
                        <td><?= $participant['ma_sv'] ?? 'N/A' ?></td>
                        <td><?= $participant['khoa'] . ($participant['lop'] ? ' / ' . $participant['lop'] : '') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($participant['ngay_dang_ky'])) ?></td>
                        <td>
                            <?php if ($participant['da_tham_gia'] == 1): ?>
                                <span class="badge bg-success">Đã điểm danh</span>
                            <?php elseif ($participant['trang_thai'] == 0): ?>
                                <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Đã xác nhận</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Chưa có người đăng ký tham gia sự kiện này</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript cho tìm kiếm và lọc -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-participant');
    const statusFilter = document.getElementById('filter-status');
    const tableRows = document.querySelectorAll('.table tbody tr');
    const mobileCards = document.querySelectorAll('.participant-card');
    
    // Hàm tìm kiếm và lọc
    function filterParticipants() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusValue = statusFilter.value;
        
        // Lọc bảng trên desktop
        tableRows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const phone = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const maSV = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            const khoa = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            
            const statusCell = row.querySelector('td:last-child');
            const isDiemDanh = statusCell.textContent.includes('Đã điểm danh');
            const isChoXacNhan = statusCell.textContent.includes('Chờ xác nhận');
            
            // Kiểm tra điều kiện tìm kiếm
            const matchesSearch = name.includes(searchTerm) || 
                                  email.includes(searchTerm) || 
                                  phone.includes(searchTerm) || 
                                  maSV.includes(searchTerm) || 
                                  khoa.includes(searchTerm);
            
            // Kiểm tra điều kiện trạng thái
            let matchesStatus = true;
            if (statusValue === '1') {
                matchesStatus = isDiemDanh;
            } else if (statusValue === '0') {
                matchesStatus = !isDiemDanh;
            }
            
            // Hiển thị nếu thỏa mãn cả hai điều kiện
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
        
        // Lọc thẻ trên mobile
        mobileCards.forEach(card => {
            const name = card.querySelector('.card-header h6').textContent.toLowerCase();
            let content = card.textContent.toLowerCase();
            
            const hasBadgeSuccess = card.querySelector('.badge.bg-success') !== null;
            const hasBadgeWarning = card.querySelector('.badge.bg-warning') !== null;
            
            // Kiểm tra điều kiện tìm kiếm
            const matchesSearch = content.includes(searchTerm);
            
            // Kiểm tra điều kiện trạng thái
            let matchesStatus = true;
            if (statusValue === '1') {
                matchesStatus = hasBadgeSuccess;
            } else if (statusValue === '0') {
                matchesStatus = !hasBadgeSuccess;
            }
            
            // Hiển thị nếu thỏa mãn cả hai điều kiện
            card.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
        
        // Kiểm tra và hiển thị thông báo "Không tìm thấy"
        checkEmptyResults();
    }
    
    // Kiểm tra kết quả trống cho cả desktop và mobile
    function checkEmptyResults() {
        // Desktop
        const tableBody = document.querySelector('.table tbody');
        const visibleRows = [...tableRows].filter(row => row.style.display !== 'none');
        const noResultsRow = document.querySelector('.no-results-row');
        
        if (visibleRows.length === 0 && !noResultsRow) {
            // Tạo hàng "Không tìm thấy kết quả"
            const emptyRow = document.createElement('tr');
            emptyRow.classList.add('no-results-row');
            const emptyCell = document.createElement('td');
            emptyCell.setAttribute('colspan', '8');
            emptyCell.textContent = 'Không tìm thấy kết quả phù hợp';
            emptyCell.className = 'text-center text-muted py-3';
            emptyRow.appendChild(emptyCell);
            tableBody.appendChild(emptyRow);
        } else if (visibleRows.length > 0 && noResultsRow) {
            // Xóa hàng "Không tìm thấy kết quả" nếu có kết quả
            noResultsRow.remove();
        }
        
        // Mobile - kiểm tra từng tab
        const tabs = ['info-content', 'contact-content', 'status-content'];
        tabs.forEach(tabId => {
            const tabPane = document.getElementById(tabId);
            const visibleCards = [...tabPane.querySelectorAll('.participant-card')].filter(card => card.style.display !== 'none');
            const noResultsAlert = tabPane.querySelector('.no-results-alert');
            
            if (visibleCards.length === 0 && !noResultsAlert) {
                // Tạo thông báo "Không tìm thấy kết quả"
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-info no-results-alert';
                alertDiv.textContent = 'Không tìm thấy kết quả phù hợp';
                
                // Thêm thông báo vào đầu tab (sau phần alert gốc nếu có)
                const originalAlert = tabPane.querySelector('.alert');
                if (originalAlert) {
                    originalAlert.style.display = 'none';
                    tabPane.insertBefore(alertDiv, originalAlert.nextSibling);
                } else {
                    tabPane.prepend(alertDiv);
                }
            } else if (visibleCards.length > 0 && noResultsAlert) {
                // Xóa thông báo "Không tìm thấy kết quả" nếu có kết quả
                noResultsAlert.remove();
                const originalAlert = tabPane.querySelector('.alert');
                if (originalAlert) {
                    originalAlert.style.display = '';
                }
            }
        });
    }
    
    // Đăng ký sự kiện
    searchInput.addEventListener('input', filterParticipants);
    statusFilter.addEventListener('change', filterParticipants);
    
    // Nút xóa tìm kiếm
    const searchButton = searchInput.nextElementSibling;
    searchButton.addEventListener('click', function() {
        if (searchInput.value) {
            searchInput.value = '';
            filterParticipants();
        } else {
            filterParticipants();
        }
    });
});
</script>