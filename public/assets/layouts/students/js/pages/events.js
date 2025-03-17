/**
 * Events Page JS
 * Xử lý các chức năng tương tác trên trang sự kiện
 */
document.addEventListener('DOMContentLoaded', function() {
    // Các phần tử trên trang
    const filterForm = document.getElementById('event-filter-form');
    const resetFilterBtn = document.querySelector('.btn-reset');
    const qrBtns = document.querySelectorAll('.registered-qr-btn');
    const qrModal = document.querySelector('.qr-modal');
    const qrModalClose = document.querySelector('.qr-modal-close');
    const qrDownloadBtn = document.querySelector('.qr-download-btn');
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    // Xử lý tabs
    if (tabButtons.length && tabContents.length) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Bỏ active ở tất cả các tab
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Thêm active cho tab được chọn
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
                
                // Lưu trạng thái tab vào localStorage
                localStorage.setItem('activeEventTab', tabId);
            });
        });
        
        // Khôi phục tab từ localStorage
        const activeTab = localStorage.getItem('activeEventTab');
        if (activeTab) {
            document.querySelector(`[data-tab="${activeTab}"]`)?.click();
        } else {
            // Mặc định hiển thị tab đầu tiên
            tabButtons[0]?.click();
        }
    }
    
    // Xử lý form lọc
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Có thể thực hiện AJAX hoặc submit form bình thường
            // e.preventDefault();
            // const formData = new FormData(this);
            // ajaxSubmit(formData);
        });
    }
    
    // Xử lý nút reset lọc
    if (resetFilterBtn) {
        resetFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Reset tất cả các trường form
            const inputs = filterForm.querySelectorAll('input:not([type="submit"]), select');
            inputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            
            // Submit form sau khi reset
            // filterForm.submit();
        });
    }
    
    // Xử lý hiển thị QR Code
    if (qrBtns.length && qrModal) {
        qrBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Lấy thông tin sự kiện từ data attributes
                const eventId = this.getAttribute('data-event-id');
                const eventTitle = this.getAttribute('data-event-title');
                const eventDate = this.getAttribute('data-event-date');
                const eventLocation = this.getAttribute('data-event-location');
                
                // Cập nhật thông tin hiển thị trong modal
                if (qrModal.querySelector('.qr-event-title')) {
                    qrModal.querySelector('.qr-event-title').textContent = eventTitle;
                }
                
                if (qrModal.querySelector('.qr-event-date')) {
                    qrModal.querySelector('.qr-event-date').textContent = eventDate;
                }
                
                if (qrModal.querySelector('.qr-event-location')) {
                    qrModal.querySelector('.qr-event-location').textContent = eventLocation;
                }
                
                // Tạo QR code hoặc tải QR code từ server
                generateQRCode(eventId);
                
                // Hiển thị modal
                qrModal.classList.add('active');
                
                // Ngăn cuộn trang khi modal hiển thị
                document.body.style.overflow = 'hidden';
            });
        });
    }
    
    // Xử lý đóng QR modal
    if (qrModalClose && qrModal) {
        qrModalClose.addEventListener('click', function() {
            qrModal.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Đóng modal khi click bên ngoài content
        qrModal.addEventListener('click', function(e) {
            if (e.target === qrModal) {
                qrModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Đóng modal khi ấn ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && qrModal.classList.contains('active')) {
                qrModal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
    
    // Xử lý tải xuống QR code
    if (qrDownloadBtn) {
        qrDownloadBtn.addEventListener('click', function() {
            const qrImage = qrModal.querySelector('.qr-code img');
            if (qrImage && qrImage.src) {
                // Tạo link tải xuống
                const link = document.createElement('a');
                link.href = qrImage.src;
                link.download = 'event-qr-code.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
    }
    
    // Hàm tạo QR code
    function generateQRCode(eventId) {
        const qrContainer = qrModal.querySelector('.qr-code');
        if (!qrContainer) return;
        
        // Xóa QR code cũ nếu có
        qrContainer.innerHTML = '';
        
        // Tạo QR code mới
        // Trong thực tế, bạn có thể sử dụng thư viện qrcode.js hoặc tải QR từ server
        // Ví dụ với QR code tĩnh
        const qrImg = document.createElement('img');
        qrImg.src = `/students/events/qrcode/${eventId}`; // URL để tải QR code từ server
        qrImg.alt = 'Event QR Code';
        qrContainer.appendChild(qrImg);
        
        // Hoặc sử dụng thư viện tạo QR code phía client
        // Nếu bạn đã tích hợp thư viện như qrcode.js
        /*
        new QRCode(qrContainer, {
            text: eventId,
            width: 180,
            height: 180,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        */
    }
    
    // Xử lý hiệu ứng hover cho event cards
    const eventCards = document.querySelectorAll('.event-card');
    eventCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.event-image img').style.transform = 'scale(1.05)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.event-image img').style.transform = '';
        });
    });
    
    // Xử lý đăng ký sự kiện với AJAX
    const registerBtns = document.querySelectorAll('.event-register-btn');
    registerBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const eventId = this.getAttribute('data-event-id');
            const button = this;
            
            // Thay đổi trạng thái nút để tránh click nhiều lần
            button.disabled = true;
            button.innerText = 'Đang xử lý...';
            
            // Gửi yêu cầu AJAX để đăng ký sự kiện
            fetch('/students/events/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ event_id: eventId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công
                    showNotification('success', data.message || 'Đăng ký sự kiện thành công!');
                    
                    // Chuyển sang tab đã đăng ký
                    const registeredTabBtn = document.querySelector('[data-tab="registered-tab"]');
                    if (registeredTabBtn) {
                        registeredTabBtn.click();
                    }
                    
                    // Tải lại danh sách sự kiện đã đăng ký
                    loadRegisteredEvents();
                } else {
                    // Hiển thị thông báo lỗi
                    showNotification('error', data.message || 'Đăng ký sự kiện thất bại!');
                    
                    // Khôi phục trạng thái nút
                    button.disabled = false;
                    button.innerText = 'Đăng ký';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra, vui lòng thử lại sau!');
                
                // Khôi phục trạng thái nút
                button.disabled = false;
                button.innerText = 'Đăng ký';
            });
        });
    });
    
    // Xử lý hủy đăng ký sự kiện
    const cancelBtns = document.querySelectorAll('.registered-cancel-btn');
    cancelBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (!confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?')) {
                return;
            }
            
            const eventId = this.getAttribute('data-event-id');
            const registrationId = this.getAttribute('data-registration-id');
            const button = this;
            const cardElement = button.closest('.registered-card');
            
            // Thay đổi trạng thái nút để tránh click nhiều lần
            button.disabled = true;
            button.innerText = 'Đang xử lý...';
            
            // Gửi yêu cầu AJAX để hủy đăng ký
            fetch('/students/events/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    event_id: eventId,
                    registration_id: registrationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công
                    showNotification('success', data.message || 'Hủy đăng ký thành công!');
                    
                    // Xóa card khỏi danh sách
                    if (cardElement) {
                        cardElement.style.opacity = '0';
                        setTimeout(() => {
                            cardElement.remove();
                            
                            // Kiểm tra nếu không còn sự kiện nào đã đăng ký
                            const remainingCards = document.querySelectorAll('.registered-card');
                            if (remainingCards.length === 0) {
                                // Hiển thị trạng thái rỗng
                                const emptyState = `
                                    <div class="events-empty">
                                        <div class="events-empty-icon">
                                            <i class="fas fa-calendar-xmark"></i>
                                        </div>
                                        <h3 class="events-empty-title">Chưa có sự kiện đăng ký</h3>
                                        <p class="events-empty-text">Bạn chưa đăng ký sự kiện nào, hãy khám phá và đăng ký các sự kiện để tham gia.</p>
                                        <a href="#events-tab" class="events-empty-btn">Khám phá sự kiện</a>
                                    </div>
                                `;
                                
                                const registeredList = document.querySelector('.registered-list');
                                if (registeredList) {
                                    registeredList.innerHTML = emptyState;
                                }
                            }
                        }, 300);
                    }
                } else {
                    // Hiển thị thông báo lỗi
                    showNotification('error', data.message || 'Hủy đăng ký thất bại!');
                    
                    // Khôi phục trạng thái nút
                    button.disabled = false;
                    button.innerText = 'Hủy đăng ký';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra, vui lòng thử lại sau!');
                
                // Khôi phục trạng thái nút
                button.disabled = false;
                button.innerText = 'Hủy đăng ký';
            });
        });
    });
    
    // Hàm hiển thị thông báo
    function showNotification(type, message) {
        // Tạo phần tử thông báo
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            </div>
            <div class="notification-content">
                <p>${message}</p>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Thêm vào container thông báo
        const container = document.querySelector('.notification-container');
        if (!container) {
            // Tạo container nếu chưa có
            const newContainer = document.createElement('div');
            newContainer.className = 'notification-container';
            document.body.appendChild(newContainer);
            newContainer.appendChild(notification);
        } else {
            container.appendChild(notification);
        }
        
        // Xử lý nút đóng
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', function() {
            notification.remove();
        });
        
        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
    
    // Hàm tải lại danh sách sự kiện đã đăng ký
    function loadRegisteredEvents() {
        fetch('/students/events/registered', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.html) {
                const registeredContent = document.getElementById('registered-tab');
                if (registeredContent) {
                    registeredContent.innerHTML = data.html;
                    
                    // Đăng ký lại các sự kiện cho các nút mới
                    initRegisteredEvents();
                }
            }
        })
        .catch(error => {
            console.error('Error loading registered events:', error);
        });
    }
    
    // Khởi tạo lại các sự kiện cho các phần tử đã đăng ký sau khi cập nhật
    function initRegisteredEvents() {
        // Khởi tạo lại các sự kiện cho nút QR code
        const newQrBtns = document.querySelectorAll('.registered-qr-btn');
        newQrBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const eventId = this.getAttribute('data-event-id');
                const eventTitle = this.getAttribute('data-event-title');
                const eventDate = this.getAttribute('data-event-date');
                const eventLocation = this.getAttribute('data-event-location');
                
                if (qrModal.querySelector('.qr-event-title')) {
                    qrModal.querySelector('.qr-event-title').textContent = eventTitle;
                }
                
                if (qrModal.querySelector('.qr-event-date')) {
                    qrModal.querySelector('.qr-event-date').textContent = eventDate;
                }
                
                if (qrModal.querySelector('.qr-event-location')) {
                    qrModal.querySelector('.qr-event-location').textContent = eventLocation;
                }
                
                generateQRCode(eventId);
                qrModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });
        
        // Khởi tạo lại các sự kiện cho nút hủy đăng ký
        const newCancelBtns = document.querySelectorAll('.registered-cancel-btn');
        newCancelBtns.forEach(btn => {
            // Thêm sự kiện click tương tự như ở trên
        });
    }
}); 