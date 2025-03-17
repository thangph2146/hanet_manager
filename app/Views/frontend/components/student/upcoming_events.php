<!-- Component hiển thị sự kiện sắp tới cho sinh viên -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Sự kiện sắp tới</h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                <div class="dropdown-header">Tùy chọn:</div>
                <a class="dropdown-item" href="#">Xem tất cả</a>
                <a class="dropdown-item" href="#">Thêm vào lịch</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Cài đặt thông báo</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="upcoming-events">
            <div class="event d-flex align-items-start mb-3 pb-3 border-bottom">
                <div class="event-date text-center rounded p-2 bg-primary text-white mr-3">
                    <div class="event-day font-weight-bold">15</div>
                    <div class="event-month small">Th4</div>
                </div>
                <div class="event-details">
                    <h6 class="event-title mb-1">Kiểm tra giữa kỳ - Cơ sở dữ liệu</h6>
                    <div class="event-time small text-muted mb-1"><i class="far fa-clock"></i> 08:00 - 09:30</div>
                    <div class="event-location small text-muted"><i class="fas fa-map-marker-alt"></i> Phòng A209</div>
                </div>
            </div>
            
            <div class="event d-flex align-items-start mb-3 pb-3 border-bottom">
                <div class="event-date text-center rounded p-2 bg-success text-white mr-3">
                    <div class="event-day font-weight-bold">17</div>
                    <div class="event-month small">Th4</div>
                </div>
                <div class="event-details">
                    <h6 class="event-title mb-1">Hạn nộp bài tập lớn - Lập trình web</h6>
                    <div class="event-time small text-muted mb-1"><i class="far fa-clock"></i> 23:59</div>
                    <div class="event-location small text-muted"><i class="fas fa-globe"></i> Nộp trực tuyến</div>
                </div>
            </div>
            
            <div class="event d-flex align-items-start mb-3">
                <div class="event-date text-center rounded p-2 bg-info text-white mr-3">
                    <div class="event-day font-weight-bold">20</div>
                    <div class="event-month small">Th4</div>
                </div>
                <div class="event-details">
                    <h6 class="event-title mb-1">Hội thảo Khoa học Công nghệ</h6>
                    <div class="event-time small text-muted mb-1"><i class="far fa-clock"></i> 14:00 - 17:00</div>
                    <div class="event-location small text-muted"><i class="fas fa-map-marker-alt"></i> Hội trường B</div>
                </div>
            </div>
        </div>
    </div>
</div> 