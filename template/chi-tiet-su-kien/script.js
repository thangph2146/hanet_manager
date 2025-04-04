document.addEventListener("DOMContentLoaded", function() {
    console.log("Đã tải trang chi tiết sự kiện");
    
    // Xử lý nút back to top
    const backToTop = document.querySelector(".back-to-top");
    
    window.addEventListener("scroll", function() {
        if (window.scrollY > 300) {
            backToTop.classList.add("active");
        } else {
            backToTop.classList.remove("active");
        }
    });
    
    if (backToTop) {
        backToTop.addEventListener("click", function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }
    
    // Lấy thông tin sự kiện từ URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const slug = urlParams.get("slug") || "hoi-thao-tai-chinh-ngan-hang-ky-nguyen-so"; // Mặc định nếu không có
    
    console.log("Đang tìm sự kiện với slug:", slug);
    
    // Tải dữ liệu sự kiện từ file JSON
    loadEventData(slug);
    
    // Thiết lập form đăng ký
    setupRegistrationForm();
    
    // Thiết lập chia sẻ QR Code
    setupQRCodeShare();
});

// Tải dữ liệu sự kiện từ file JSON
async function loadEventData(slug) {
    try {
        // Thử nhiều đường dẫn khác nhau để tìm file JSON
        const basePaths = [
            "http://127.0.0.1:5500/template/data/su-kien.json",
        ];
        
        console.log("Đang thử tải dữ liệu từ các đường dẫn có thể");
        console.log("URL hiện tại:", window.location.href);
        
        // Hiển thị trạng thái đang tải
        updateLoadingState(true);
        
        // Lưu lỗi để hiển thị nếu không tìm thấy file
        let lastError = null;
        let data = null;
        let successPath = null;
        
        // Thử tất cả các đường dẫn có thể
        for (const path of basePaths) {
            try {
                console.log(`Đang thử tải từ: ${path}`);
                const response = await fetch(path);
                
                if (response.ok) {
                    const jsonData = await response.json();
                    console.log(`Đã tải thành công từ: ${path}`);
                    console.log("Dữ liệu đã nhận:", jsonData);
                    data = jsonData;
                    successPath = path;
                    break;
                } else {
                    console.log(`Không thể tải từ ${path}: ${response.status} ${response.statusText}`);
                }
            } catch (error) {
                console.log(`Lỗi khi tải từ ${path}:`, error.message);
                lastError = error;
            }
        }
        
        // Nếu không tìm thấy file, tạo dữ liệu mẫu để test
        if (!data) {
            console.warn("Không thể tải dữ liệu - sử dụng dữ liệu mẫu để kiểm tra");
            
            // Dữ liệu mẫu để kiểm tra
            data = [
                {
                    "su_kien_id": 1,
                    "ten_su_kien": "[Dữ liệu mẫu] Hội thảo khoa học Tài chính và Ngân hàng",
                    "mo_ta": "Đây là dữ liệu mẫu vì không thể tải được dữ liệu thực từ file JSON.",
                    "dia_diem": "CS Tôn Thất Đạm",
                    "dia_chi_cu_the": "36 Tôn Thất Đạm, Quận 1, TP.HCM",
                    "thoi_gian_bat_dau": "2025-06-15 08:00:00",
                    "thoi_gian_ket_thuc": "2025-06-15 17:00:00",
                    "hinh_thuc": "offline",
                    "tong_dang_ky": 132,
                    "so_luot_xem": 1326,
                    "slug": "hoi-thao-tai-chinh-ngan-hang-ky-nguyen-so",
                    "lich_trinh": [
                        {
                            "tieu_de": "Đăng ký và khai mạc",
                            "mo_ta": "Mô tả chi tiết cho phiên 1",
                            "thoi_gian_bat_dau": "2025-06-15 08:00:00",
                            "thoi_gian_ket_thuc": "2025-06-15 09:00:00",
                            "nguoi_phu_trach": "Ban tổ chức"
                        },
                        {
                            "tieu_de": "Phiên thảo luận chính",
                            "mo_ta": "Mô tả chi tiết cho phiên 2",
                            "thoi_gian_bat_dau": "2025-06-15 09:00:00",
                            "thoi_gian_ket_thuc": "2025-06-15 11:00:00",
                            "nguoi_phu_trach": "Diễn giả 2"
                        }
                    ]
                },
                {
                    "su_kien_id": 2,
                    "ten_su_kien": "[Dữ liệu mẫu] Ngày hội việc làm HUB lần thứ 13",
                    "mo_ta": "Đây là dữ liệu mẫu vì không thể tải được dữ liệu thực từ file JSON.",
                    "dia_diem": "CS Hoàng Diệu",
                    "dia_chi_cu_the": "56 Hoàng Diệu 2, Quận Thủ Đức, TP.HCM",
                    "thoi_gian_bat_dau": "2025-06-22 08:30:00",
                    "thoi_gian_ket_thuc": "2025-06-22 16:30:00",
                    "hinh_thuc": "offline",
                    "tong_dang_ky": 168,
                    "so_luot_xem": 980,
                    "slug": "ngay-hoi-viec-lam-hub-lan-13-2023"
                }
            ];
            
            console.log("Đã tạo dữ liệu mẫu:", data);
        }
        
        // Kiểm tra dữ liệu
        if (!Array.isArray(data)) {
            throw new Error("Dữ liệu JSON không hợp lệ, mong đợi một mảng các sự kiện");
        }
        
        console.log(`Đã tải ${data.length} sự kiện${successPath ? ' từ file JSON tại: ' + successPath : ' (sử dụng dữ liệu mẫu)'}`);
        
        // Tìm sự kiện theo slug
        const event = data.find(item => item.slug === slug);
        
        if (!event) {
            console.error("Không tìm thấy sự kiện với slug:", slug);
            
            // Nếu không tìm thấy theo slug, lấy sự kiện đầu tiên
            const defaultEvent = data[0];
            console.log("Sử dụng sự kiện mặc định:", defaultEvent.ten_su_kien);
            
            if (defaultEvent) {
                updateEventDetails(defaultEvent);
                updateSchedule(defaultEvent.lich_trinh || []);
                
                if (new Date(defaultEvent.thoi_gian_bat_dau) > new Date()) {
                    countdownTimer(defaultEvent.thoi_gian_bat_dau);
                }
                
                updateRelatedEvents(data, defaultEvent.su_kien_id);
                updateLoadingState(false);
                return;
            } else {
                throw new Error('Không tìm thấy sự kiện nào trong dữ liệu');
            }
        }
        
        console.log("Đã tìm thấy sự kiện:", event.ten_su_kien);
        
        // Cập nhật thông tin sự kiện
        updateEventDetails(event);
        
        // Cập nhật lịch trình nếu có
        if (event.lich_trinh && event.lich_trinh.length > 0) {
            console.log("Cập nhật lịch trình:", event.lich_trinh.length, "phiên");
            updateSchedule(event.lich_trinh);
        } else {
            console.log("Không có lịch trình, sử dụng lịch trình mặc định");
            updateSchedule([
                {
                    "tieu_de": "Đăng ký và khai mạc",
                    "mo_ta": "Mô tả chi tiết cho phiên 1",
                    "thoi_gian_bat_dau": event.thoi_gian_bat_dau,
                    "thoi_gian_ket_thuc": event.thoi_gian_bat_dau,
                    "nguoi_phu_trach": "Ban tổ chức"
                },
                {
                    "tieu_de": "Phiên thảo luận chính",
                    "mo_ta": "Mô tả chi tiết cho phiên 2",
                    "thoi_gian_bat_dau": event.thoi_gian_bat_dau,
                    "thoi_gian_ket_thuc": event.thoi_gian_bat_dau,
                    "nguoi_phu_trach": "Diễn giả"
                }
            ]);
        }
        
        // Đếm ngược thời gian đến sự kiện
        if (new Date(event.thoi_gian_bat_dau) > new Date()) {
            countdownTimer(event.thoi_gian_bat_dau);
        }
        
        // Cập nhật sự kiện liên quan
        updateRelatedEvents(data, event.su_kien_id);
        
        // Tắt trạng thái đang tải
        updateLoadingState(false);
        
    } catch (error) {
        console.error("Lỗi khi tải dữ liệu:", error);
        
        // Hiển thị thông báo lỗi trong giao diện
        updateErrorState(error.message);
        
        // Tắt trạng thái đang tải
        updateLoadingState(false);
    }
}

// Cập nhật trạng thái đang tải
function updateLoadingState(isLoading) {
    const loading = document.createElement('div');
    loading.id = 'loading-indicator';
    loading.innerHTML = `
        <div class="loading-spinner"></div>
        <p>Đang tải dữ liệu sự kiện...</p>
    `;
    loading.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;
    
    // Thêm CSS cho spinner
    const style = document.createElement('style');
    style.textContent = `
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    
    if (isLoading) {
        // Nếu đã có loading indicator thì không cần thêm nữa
        if (!document.getElementById('loading-indicator')) {
            document.head.appendChild(style);
            document.body.appendChild(loading);
        }
    } else {
        // Xóa loading indicator nếu có
        const existingLoading = document.getElementById('loading-indicator');
        if (existingLoading) {
            document.body.removeChild(existingLoading);
        }
    }
}

// Hiển thị thông báo lỗi
function updateErrorState(errorMessage) {
    // Hiển thị lỗi trong các phần quan trọng của trang
    const elements = [
        { selector: '.event-title', message: 'Không thể tải thông tin sự kiện' },
        { selector: '.event-intro p', message: errorMessage },
        { selector: '.schedule-list', message: `<div class="alert alert-danger">Không thể tải lịch trình sự kiện: ${errorMessage}</div>` }
    ];
    
    elements.forEach(el => {
        const element = document.querySelector(el.selector);
        if (element) {
            element.innerHTML = el.message;
        }
    });
    
    // Hiển thị thông báo lỗi chính
    const errorAlert = document.createElement('div');
    errorAlert.className = 'alert alert-danger';
    errorAlert.style.cssText = `
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        padding: 15px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    `;
    errorAlert.innerHTML = `
        <strong>Lỗi:</strong> ${errorMessage}
        <button type="button" class="btn-close" aria-label="Close" style="float: right; cursor: pointer;"></button>
    `;
    
    document.body.appendChild(errorAlert);
    
    // Xử lý đóng thông báo
    const closeButton = errorAlert.querySelector('.btn-close');
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            document.body.removeChild(errorAlert);
        });
        
        // Tự động đóng sau 10 giây
        setTimeout(() => {
            if (document.body.contains(errorAlert)) {
                document.body.removeChild(errorAlert);
            }
        }, 10000);
    }
}

// Cập nhật thông tin chi tiết sự kiện
function updateEventDetails(event) {
    console.log("Đang cập nhật thông tin chi tiết sự kiện");
    
    // Cập nhật tiêu đề trang
    document.title = event.ten_su_kien;
    
    // Cập nhật tiêu đề sự kiện
    const eventTitle = document.querySelector('.event-title');
    if (eventTitle) {
        eventTitle.textContent = event.ten_su_kien;
        console.log("Đã cập nhật tiêu đề:", event.ten_su_kien);
    } else {
        console.error("Không tìm thấy phần tử .event-title");
    }
    
    // Cập nhật breadcrumb
    const breadcrumbTitle = document.querySelector('.breadcrumb-item.active');
    if (breadcrumbTitle) {
        breadcrumbTitle.textContent = event.ten_su_kien;
    } else {
        console.error("Không tìm thấy phần tử .breadcrumb-item.active");
    }
    
    // Cập nhật meta og tags
    updateMetaTags(event);
    
    // Cập nhật thông tin meta
    const metaViews = document.querySelector('.event-meta-item:nth-child(1)');
    if (metaViews) {
        metaViews.innerHTML = `<i class="fas fa-eye"></i> ${event.so_luot_xem || 0} lượt xem`;
    }
    
    const metaRegistrations = document.querySelector('.event-meta-item:nth-child(2)');
    if (metaRegistrations) {
        metaRegistrations.innerHTML = `<i class="fas fa-users"></i> ${event.tong_dang_ky || 0} người đăng ký`;
    }
    
    const metaDate = document.querySelector('.event-meta-item:nth-child(3)');
    if (metaDate) {
        const eventDate = new Date(event.thoi_gian_bat_dau);
        metaDate.innerHTML = `<i class="far fa-calendar-alt"></i> ${formatDate(eventDate)}`;
    }
    
    // Cập nhật chi tiết sự kiện
    const eventDate = document.querySelector('.event-detail-item:nth-child(1) p');
    if (eventDate) {
        const date = new Date(event.thoi_gian_bat_dau);
        eventDate.textContent = formatDate(date);
    }
    
    const eventTime = document.querySelector('.event-detail-item:nth-child(2) p');
    if (eventTime) {
        const startTime = formatTime(new Date(event.thoi_gian_bat_dau));
        const endTime = formatTime(new Date(event.thoi_gian_ket_thuc));
        eventTime.textContent = `${startTime} - ${endTime}`;
    }
    
    const eventLocation = document.querySelector('.event-detail-item:nth-child(3) p:first-of-type');
    if (eventLocation) {
        eventLocation.textContent = event.dia_diem;
    }
    
    const eventVenueDetail = document.querySelector('.event-detail-item:nth-child(3) .venue-detail');
    if (eventVenueDetail) {
        eventVenueDetail.textContent = event.dia_chi_cu_the;
    }
    
    const eventCount = document.querySelector('.event-detail-item:nth-child(4) p');
    if (eventCount) {
        eventCount.textContent = `${event.tong_dang_ky || 0} người`;
    }
    
    const eventFormat = document.querySelector('.event-detail-item:nth-child(5) p');
    if (eventFormat) {
        eventFormat.textContent = event.hinh_thuc;
    }
    
    // Cập nhật intro
    const eventIntro = document.querySelector('.event-intro p');
    if (eventIntro) {
        eventIntro.textContent = event.mo_ta;
    }
    
    // Cập nhật thông tin thống kê
    const statViews = document.querySelector('.stat-item:nth-child(1) .stat-value');
    if (statViews) {
        statViews.textContent = event.so_luot_xem || 0;
    }
    
    const statRegistrations = document.querySelector('.stat-item:nth-child(2) .stat-value');
    if (statRegistrations) {
        statRegistrations.textContent = event.tong_dang_ky || 0;
    }
    
    // Cập nhật mã QR
    const qrCodeImg = document.querySelector('.qr-code-img');
    if (qrCodeImg) {
        const currentUrl = window.location.href;
        qrCodeImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(currentUrl)}`;
    }
    
    // Cập nhật hình ảnh poster nếu có
    if (event.su_kien_poster && event.su_kien_poster.url) {
        // Thêm hình ảnh vào phần giới thiệu
        const introSection = document.querySelector('.event-intro');
        if (introSection) {
            const posterImage = document.createElement('img');
            posterImage.src = event.su_kien_poster.url;
            posterImage.alt = event.su_kien_poster.alt || event.ten_su_kien;
            posterImage.className = 'event-poster';
            posterImage.style.cssText = 'max-width: 100%; height: auto; margin-top: 15px; border-radius: 8px;';
            
            // Chèn hình ảnh vào sau đoạn văn bản giới thiệu
            introSection.appendChild(posterImage);
        }
    }
    
    console.log("Đã cập nhật xong thông tin chi tiết sự kiện");
}

// Cập nhật meta tags
function updateMetaTags(event) {
    // Cập nhật các thẻ meta
    const metaTags = {
        'description': event.mo_ta,
        'og:title': `Chi tiết sự kiện: ${event.ten_su_kien}`,
        'og:description': event.mo_ta,
        'og:url': window.location.href
    };
    
    // Cập nhật hình ảnh og:image nếu có
    if (event.su_kien_poster && event.su_kien_poster.url) {
        metaTags['og:image'] = event.su_kien_poster.url;
    }
    
    // Cập nhật các thẻ meta
    for (const [name, content] of Object.entries(metaTags)) {
        let metaTag;
        
        if (name.startsWith('og:')) {
            metaTag = document.querySelector(`meta[property="${name}"]`);
        } else {
            metaTag = document.querySelector(`meta[name="${name}"]`);
        }
        
        if (metaTag) {
            metaTag.setAttribute('content', content);
        }
    }
}

// Format date function
function formatDate(date) {
    return date.toLocaleDateString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Format time function
function formatTime(date) {
    return date.toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Cập nhật lịch trình
function updateSchedule(scheduleData) {
    console.log("Đang cập nhật lịch trình");
    
    const scheduleList = document.querySelector('.schedule-list');
    if (!scheduleList) {
        console.error("Không tìm thấy phần tử .schedule-list");
        return;
    }
    
    // Xóa nội dung hiện tại
    scheduleList.innerHTML = '';
    
    if (!scheduleData || scheduleData.length === 0) {
        const noScheduleItem = document.createElement('div');
        noScheduleItem.className = 'schedule-item';
        noScheduleItem.innerHTML = `
            <div class="schedule-number">1</div>
            <div class="schedule-content">
                <h4>Chưa có lịch trình chi tiết cho sự kiện này</h4>
            </div>
        `;
        scheduleList.appendChild(noScheduleItem);
        return;
    }
    
    // Thêm các mục lịch trình mới
    scheduleData.forEach((item, index) => {
        const scheduleItem = document.createElement('div');
        scheduleItem.className = 'schedule-item';
        
        // Xử lý trường hợp thời gian không hợp lệ
        let startTime, endTime;
        try {
            startTime = formatTime(new Date(item.thoi_gian_bat_dau));
            endTime = formatTime(new Date(item.thoi_gian_ket_thuc));
        } catch (error) {
            console.error("Lỗi khi chuyển đổi thời gian:", error);
            startTime = "--:--";
            endTime = "--:--";
        }
        
        scheduleItem.innerHTML = `
            <div class="schedule-number">${index + 1}</div>
            <div class="schedule-content">
                <h4>${item.tieu_de || 'Chưa có tiêu đề'}</h4>
                <p>${item.mo_ta || 'Chưa có mô tả'}</p>
                <div class="schedule-time">
                    <i class="far fa-clock"></i> ${startTime} - ${endTime}
                </div>
                <div class="schedule-host">
                    <i class="fas fa-user"></i> ${item.nguoi_phu_trach || 'Chưa xác định'}
                </div>
            </div>
        `;
        
        scheduleList.appendChild(scheduleItem);
    });
    
    console.log("Đã cập nhật xong lịch trình");
}

// Cập nhật sự kiện liên quan
function updateRelatedEvents(allEvents, currentEventId) {
    console.log("Đang cập nhật sự kiện liên quan");
    
    const relatedEventsContainer = document.querySelector('.related-events');
    if (!relatedEventsContainer) {
        console.error("Không tìm thấy phần tử .related-events");
        return;
    }
    
    // Xóa nội dung hiện tại
    relatedEventsContainer.innerHTML = '';
    
    // Lọc sự kiện liên quan (không bao gồm sự kiện hiện tại và giới hạn 3 sự kiện)
    const relatedEvents = allEvents
        .filter(event => event.su_kien_id !== currentEventId)
        .sort((a, b) => new Date(a.thoi_gian_bat_dau) - new Date(b.thoi_gian_bat_dau))
        .slice(0, 3);
    
    // Hiển thị sự kiện liên quan
    relatedEvents.forEach(event => {
        const date = new Date(event.thoi_gian_bat_dau);
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        const time = formatTime(date);
        
        const relatedEventItem = document.createElement('a');
        relatedEventItem.href = `?slug=${event.slug}`;
        relatedEventItem.className = 'related-event-item';
        
        relatedEventItem.innerHTML = `
            <div class="related-event-date">
                <span class="date-number">${day}</span>
                <span class="date-month">${month < 10 ? '0' + month : month}/${year}</span>
            </div>
            <div class="related-event-info">
                <h4>${event.ten_su_kien}</h4>
                <div class="related-event-meta">
                    <i class="far fa-clock"></i> ${time}
                </div>
            </div>
        `;
        
        relatedEventsContainer.appendChild(relatedEventItem);
    });
    
    // Nếu không có sự kiện liên quan
    if (relatedEvents.length === 0) {
        relatedEventsContainer.innerHTML = '<p>Không có sự kiện liên quan.</p>';
    }
    
    console.log("Đã cập nhật xong sự kiện liên quan");
}

// Đếm ngược thời gian
function countdownTimer(eventDate) {
    console.log("Khởi tạo đếm ngược đến:", eventDate);
    // Ngày sự kiện
    const eventDateTime = new Date(eventDate).getTime();
    
    function updateCountdown() {
        const now = new Date().getTime();
        const distance = eventDateTime - now;
        
        // Nếu thời gian đã qua
        if (distance < 0) {
            clearInterval(timer);
            document.getElementById('countdown-days').innerText = '0';
            document.getElementById('countdown-hours').innerText = '0';
            document.getElementById('countdown-minutes').innerText = '0';
            document.getElementById('countdown-seconds').innerText = '0';
            return;
        }
        
        // Tính toán ngày, giờ, phút, giây
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Cập nhật giao diện
        document.getElementById('countdown-days').innerText = days;
        document.getElementById('countdown-hours').innerText = hours;
        document.getElementById('countdown-minutes').innerText = minutes;
        document.getElementById('countdown-seconds').innerText = seconds;
    }
    
    // Cập nhật đếm ngược ngay lập tức
    updateCountdown();
    
    // Cập nhật đếm ngược mỗi giây
    const timer = setInterval(updateCountdown, 1000);
}

// Thiết lập form đăng ký
function setupRegistrationForm() {
    const form = document.getElementById('event-registration-form');
    if (!form) {
        console.error("Không tìm thấy form đăng ký #event-registration-form");
        return;
    }
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Lấy dữ liệu form
        const formData = {
            fullname: document.getElementById('fullname').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            organization: document.getElementById('organization').value,
            note: document.getElementById('note').value
        };
        
        // Giả lập gửi dữ liệu đăng ký
        console.log('Dữ liệu đăng ký:', formData);
        
        // Hiển thị thông báo
        alert('Đăng ký tham gia sự kiện thành công!');
        
        // Reset form
        form.reset();
    });
    
    console.log("Đã thiết lập form đăng ký");
}

// Thiết lập chia sẻ QR Code
function setupQRCodeShare() {
    const downloadButton = document.querySelector('.btn-download');
    if (!downloadButton) {
        console.error("Không tìm thấy nút tải xuống QR code");
        return;
    }
    
    downloadButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Lấy URL của hình ảnh QR Code
        const qrCodeUrl = document.querySelector('.qr-code-img').src;
        
        // Tạo liên kết tải xuống
        const link = document.createElement('a');
        link.href = qrCodeUrl;
        link.download = 'qr-code-su-kien.png';
        
        // Thêm liên kết vào trang và kích hoạt sự kiện click
        document.body.appendChild(link);
        link.click();
        
        // Xóa liên kết
        document.body.removeChild(link);
    });
    
    // Thiết lập nút chia sẻ
    const shareButtons = document.querySelectorAll('.share-buttons .btn');
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Lấy URL hiện tại
            const currentUrl = window.location.href;
            const shareType = this.className.includes('facebook') ? 'facebook' : 
                              this.className.includes('twitter') ? 'twitter' : 
                              this.className.includes('linkedin') ? 'linkedin' : 'email';
            
            // Xử lý chia sẻ theo từng loại
            switch(shareType) {
                case 'facebook':
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`, '_blank');
                    break;
                case 'twitter':
                    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(currentUrl)}`, '_blank');
                    break;
                case 'linkedin':
                    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentUrl)}`, '_blank');
                    break;
                case 'email':
                    window.location.href = `mailto:?subject=Thông tin sự kiện&body=${encodeURIComponent('Tôi muốn chia sẻ sự kiện này với bạn: ' + currentUrl)}`;
                    break;
            }
        });
    });
    
    console.log("Đã thiết lập chia sẻ QR code");
} 