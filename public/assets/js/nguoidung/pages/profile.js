/**
 * Profile Page JS
 * Xử lý tương tác của trang hồ sơ người dùng
 */

"use strict";

// Biến toàn cục
let profilePage;

// Class quản lý trang hồ sơ
class ProfilePage {
    constructor() {
        this.init();
    }
    
    init() {
        // Khởi tạo các thành phần
        this.initElements();
        this.initEventListeners();
        this.initTooltips();
        
        // Tải dữ liệu ban đầu
        this.onTabChange();
    }
    
    initElements() {
        // Các elements chính
        this.profileTabs = document.getElementById('profileTab');
        this.editProfileBtn = document.getElementById('edit-profile-btn');
        this.saveProfileBtn = document.getElementById('save-profile-btn');
        this.confirmCancelBtn = document.getElementById('confirm-cancel-btn');
        this.saveFeedbackBtn = document.getElementById('save-feedback-btn');
        this.searchEventsInput = document.getElementById('search-events');
        this.searchEventsBtn = document.getElementById('search-events-btn');
        
        // Containers
        this.registeredContainer = document.getElementById('registered-events-container');
        this.attendedContainer = document.getElementById('attended-events-container');
        this.canceledContainer = document.getElementById('canceled-events-container');
        this.availableContainer = document.getElementById('available-events-container');
        
        // Modals
        this.editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        this.cancelEventModal = new bootstrap.Modal(document.getElementById('cancelEventModal'));
        this.feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
        
        // Forms
        this.editProfileForm = document.getElementById('edit-profile-form');
        this.cancelEventForm = document.getElementById('cancel-event-form');
        this.feedbackForm = document.getElementById('feedback-form');
        
        // Select elements
        this.cancelReason = document.getElementById('cancel-reason');
        this.otherReasonContainer = document.getElementById('other-reason-container');
        this.otherReason = document.getElementById('other-reason');
        
        // Rating elements
        this.ratingStars = document.querySelectorAll('.rating-star');
        this.ratingValue = document.getElementById('rating-value');
    }
    
    initEventListeners() {
        // Sự kiện khi chuyển tab
        this.profileTabs.addEventListener('shown.bs.tab', (e) => this.onTabChange(e));
        
        // Sự kiện khi nhấn nút chỉnh sửa thông tin
        if (this.editProfileBtn) {
            this.editProfileBtn.addEventListener('click', () => this.openEditModal());
        }
        
        // Sự kiện khi lưu thông tin
        if (this.saveProfileBtn) {
            this.saveProfileBtn.addEventListener('click', () => this.saveProfile());
        }
        
        // Sự kiện khi xác nhận hủy đăng ký sự kiện
        if (this.confirmCancelBtn) {
            this.confirmCancelBtn.addEventListener('click', () => this.cancelEvent());
        }
        
        // Sự kiện khi gửi đánh giá
        if (this.saveFeedbackBtn) {
            this.saveFeedbackBtn.addEventListener('click', () => this.saveFeedback());
        }
        
        // Sự kiện khi tìm kiếm sự kiện
        if (this.searchEventsBtn) {
            this.searchEventsBtn.addEventListener('click', () => this.searchEvents());
        }
        
        if (this.searchEventsInput) {
            this.searchEventsInput.addEventListener('keyup', (e) => {
                if (e.key === 'Enter') {
                    this.searchEvents();
                }
            });
        }
        
        // Sự kiện khi chọn lý do hủy
        if (this.cancelReason) {
            this.cancelReason.addEventListener('change', () => this.toggleOtherReason());
        }
        
        // Sự kiện rating
        this.ratingStars.forEach(star => {
            star.addEventListener('click', (e) => this.handleRating(e));
            star.addEventListener('mouseover', (e) => this.handleRatingHover(e));
            star.addEventListener('mouseout', () => this.handleRatingOut());
        });
    }
    
    initTooltips() {
        // Khởi tạo tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });
    }
    
    onTabChange(e) {
        // Xác định tab nào được hiển thị
        const activeTabId = e ? e.target.id : document.querySelector('#profileTab .nav-link.active').id;
        
        switch (activeTabId) {
            case 'registered-tab':
                this.loadRegisteredEvents();
                break;
            case 'attended-tab':
                this.loadAttendedEvents();
                break;
            case 'canceled-tab':
                this.loadCanceledEvents();
                break;
            case 'available-tab':
                this.loadAvailableEvents();
                break;
            // Trường hợp tab thông tin cá nhân không cần tải dữ liệu
        }
    }
    
    openEditModal() {
        this.editProfileModal.show();
    }
    
    saveProfile() {
        // Kiểm tra form
        if (!this.editProfileForm.checkValidity()) {
            this.editProfileForm.reportValidity();
            return;
        }
        
        // Xử lý submit form
        document.getElementById('save-profile-btn').disabled = true;
        document.getElementById('save-profile-btn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
        
        // Submit form
        this.editProfileForm.submit();
    }
    
    loadRegisteredEvents() {
        if (!this.registeredContainer) return;
        
        // Hiển thị loading
        this.registeredContainer.innerHTML = `
            <div class="col-12 text-center py-5 loading-container">
                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                <p>Đang tải dữ liệu...</p>
            </div>
        `;
        
        // Gọi API lấy sự kiện đã đăng ký
        fetch(API_CONFIG.registeredEvents, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.renderEvents(this.registeredContainer, data.events, 'registered');
            } else {
                this.showEmptyState(this.registeredContainer, 'Không tìm thấy sự kiện đã đăng ký');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showEmptyState(this.registeredContainer, 'Đã xảy ra lỗi khi tải dữ liệu');
        });
    }
    
    loadAttendedEvents() {
        if (!this.attendedContainer) return;
        
        // Hiển thị loading
        this.attendedContainer.innerHTML = `
            <div class="col-12 text-center py-5 loading-container">
                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                <p>Đang tải dữ liệu...</p>
            </div>
        `;
        
        // Gọi API lấy sự kiện đã tham gia
        fetch(API_CONFIG.attendedEvents, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.renderEvents(this.attendedContainer, data.events, 'attended');
            } else {
                this.showEmptyState(this.attendedContainer, 'Bạn chưa tham gia sự kiện nào');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showEmptyState(this.attendedContainer, 'Đã xảy ra lỗi khi tải dữ liệu');
        });
    }
    
    loadCanceledEvents() {
        if (!this.canceledContainer) return;
        
        // Hiển thị loading
        this.canceledContainer.innerHTML = `
            <div class="col-12 text-center py-5 loading-container">
                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                <p>Đang tải dữ liệu...</p>
            </div>
        `;
        
        // Gọi API lấy sự kiện đã hủy
        fetch(API_CONFIG.canceledEvents, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.renderEvents(this.canceledContainer, data.events, 'canceled');
            } else {
                this.showEmptyState(this.canceledContainer, 'Không có sự kiện nào đã hủy');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showEmptyState(this.canceledContainer, 'Đã xảy ra lỗi khi tải dữ liệu');
        });
    }
    
    loadAvailableEvents() {
        if (!this.availableContainer) return;
        
        // Hiển thị loading
        this.availableContainer.innerHTML = `
            <div class="col-12 text-center py-5 loading-container">
                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                <p>Đang tải dữ liệu...</p>
            </div>
        `;
        
        // Gọi API lấy sự kiện có thể đăng ký
        const searchTerm = this.searchEventsInput ? this.searchEventsInput.value : '';
        
        fetch(`${API_CONFIG.availableEvents}?search=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.events && data.events.length > 0) {
                this.renderEvents(this.availableContainer, data.events, 'available');
            } else {
                this.showEmptyState(this.availableContainer, 'Không có sự kiện nào có thể đăng ký');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showEmptyState(this.availableContainer, 'Đã xảy ra lỗi khi tải dữ liệu');
        });
    }
    
    searchEvents() {
        this.loadAvailableEvents();
    }
    
    renderEvents(container, events, type) {
        if (!container) return;
        
        // Nếu không có sự kiện
        if (!events || events.length === 0) {
            this.showEmptyState(container, 'Không có sự kiện nào');
            return;
        }
        
        let html = '';
        
        events.forEach(event => {
            const eventDateObj = new Date(event.ngay_su_kien);
            const formattedDate = eventDateObj.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            const startTimeObj = new Date(`2000-01-01T${event.gio_bat_dau}`);
            const endTimeObj = new Date(`2000-01-01T${event.gio_ket_thuc}`);
            const formattedTime = `${startTimeObj.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })} - ${endTimeObj.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}`;
            
            html += `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="event-card">
                        <div class="event-card-image-container">
                            <img src="${event.hinh_anh ? `${window.location.origin}/uploads/events/${event.hinh_anh}` : `${window.location.origin}/assets/images/events/default.jpg`}" 
                                class="event-card-image" alt="${event.ten_su_kien}">
                            <div class="event-card-date">
                                <span class="event-day">${eventDateObj.getDate()}</span>
                                <span class="event-month">${eventDateObj.toLocaleString('vi-VN', { month: 'short' })}</span>
                            </div>
                        </div>
                        <div class="event-card-body">
                            <h5 class="event-card-title">${event.ten_su_kien}</h5>
                            <div class="event-card-info">
                                <p><i class="fas fa-map-marker-alt"></i> ${event.dia_diem}</p>
                                <p><i class="far fa-clock"></i> ${formattedTime}</p>
                                <p><i class="far fa-calendar-alt"></i> ${formattedDate}</p>
                            </div>
                            <div class="event-card-actions">
            `;
            
            // Các nút action tùy theo loại sự kiện
            if (type === 'registered') {
                html += `
                    <a href="${window.location.origin}/nguoi-dung/events/details/${event.su_kien_id}" class="btn btn-sm btn-primary">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </a>
                    <button class="btn btn-sm btn-danger cancel-event-btn" data-event-id="${event.su_kien_id}" data-event-title="${event.ten_su_kien}">
                        <i class="fas fa-times-circle"></i> Hủy đăng ký
                    </button>
                `;
            } else if (type === 'attended') {
                html += `
                    <a href="${window.location.origin}/nguoi-dung/events/details/${event.su_kien_id}" class="btn btn-sm btn-primary">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </a>
                    ${!event.has_feedback ? `
                    <button class="btn btn-sm btn-success feedback-btn" data-event-id="${event.su_kien_id}" data-event-title="${event.ten_su_kien}">
                        <i class="fas fa-star"></i> Đánh giá
                    </button>
                    ` : `
                    <button class="btn btn-sm btn-outline-success" disabled>
                        <i class="fas fa-check"></i> Đã đánh giá
                    </button>
                    `}
                `;
            } else if (type === 'canceled') {
                html += `
                    <a href="${window.location.origin}/nguoi-dung/events/details/${event.su_kien_id}" class="btn btn-sm btn-primary">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </a>
                `;
            } else if (type === 'available') {
                html += `
                    <a href="${window.location.origin}/nguoi-dung/events/details/${event.su_kien_id}" class="btn btn-sm btn-primary">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </a>
                    <button class="btn btn-sm btn-success register-event-btn" data-event-id="${event.su_kien_id}">
                        <i class="fas fa-calendar-check"></i> Đăng ký
                    </button>
                `;
            }
            
            html += `
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
        
        // Gắn các event listener cho các nút
        this.initEventCardButtons(container, type);
    }
    
    showEmptyState(container, message) {
        if (!container) return;
        
        container.innerHTML = `
            <div class="col-12 text-center py-5 empty-state">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <p class="text-muted">${message}</p>
            </div>
        `;
    }
    
    initEventCardButtons(container, type) {
        // Gắn sự kiện cho các nút trong thẻ sự kiện
        if (type === 'registered') {
            const cancelButtons = container.querySelectorAll('.cancel-event-btn');
            cancelButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const eventId = e.currentTarget.dataset.eventId;
                    const eventTitle = e.currentTarget.dataset.eventTitle;
                    this.openCancelModal(eventId, eventTitle);
                });
            });
        } else if (type === 'attended') {
            const feedbackButtons = container.querySelectorAll('.feedback-btn');
            feedbackButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const eventId = e.currentTarget.dataset.eventId;
                    const eventTitle = e.currentTarget.dataset.eventTitle;
                    this.openFeedbackModal(eventId, eventTitle);
                });
            });
        } else if (type === 'available') {
            const registerButtons = container.querySelectorAll('.register-event-btn');
            registerButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const eventId = e.currentTarget.dataset.eventId;
                    this.registerEvent(eventId);
                });
            });
        }
    }
    
    openCancelModal(eventId, eventTitle) {
        // Thiết lập modal hủy đăng ký
        document.getElementById('cancel-event-id').value = eventId;
        document.getElementById('cancelEventModalLabel').textContent = `Xác nhận hủy đăng ký: ${eventTitle}`;
        
        // Reset form
        this.cancelEventForm.reset();
        this.otherReasonContainer.classList.add('d-none');
        
        // Hiển thị modal
        this.cancelEventModal.show();
    }
    
    openFeedbackModal(eventId, eventTitle) {
        // Thiết lập modal đánh giá
        document.getElementById('feedback-event-id').value = eventId;
        document.getElementById('feedback-event-title').textContent = eventTitle;
        
        // Reset form
        this.feedbackForm.reset();
        this.resetRating();
        
        // Hiển thị modal
        this.feedbackModal.show();
    }
    
    cancelEvent() {
        // Kiểm tra form
        if (!this.cancelEventForm.checkValidity()) {
            this.cancelEventForm.reportValidity();
            return;
        }
        
        // Kiểm tra lý do nếu chọn 'Khác'
        if (this.cancelReason.value === 'other' && !this.otherReason.value.trim()) {
            this.otherReason.setCustomValidity('Vui lòng nhập lý do');
            this.otherReason.reportValidity();
            return;
        } else {
            this.otherReason.setCustomValidity('');
        }
        
        // Lấy dữ liệu form
        const eventId = document.getElementById('cancel-event-id').value;
        const reason = this.cancelReason.value;
        const otherReasonText = this.otherReason.value;
        
        // Disable buttons
        this.confirmCancelBtn.disabled = true;
        this.confirmCancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        // Gọi API hủy đăng ký
        fetch(API_CONFIG.cancelEvent, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken
            },
            body: JSON.stringify({
                event_id: eventId,
                reason: reason,
                other_reason: reason === 'other' ? otherReasonText : ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hiển thị thông báo thành công
                if (window.StudentUI) {
                    window.StudentUI.showToast(data.message || 'Hủy đăng ký thành công!', 'success');
                } else {
                    alert(data.message || 'Hủy đăng ký thành công!');
                }
                
                // Đóng modal
                this.cancelEventModal.hide();
                
                // Tải lại dữ liệu
                this.loadRegisteredEvents();
                setTimeout(() => this.loadCanceledEvents(), 500);
            } else {
                // Hiển thị thông báo lỗi
                if (window.StudentUI) {
                    window.StudentUI.showToast(data.message || 'Đã xảy ra lỗi!', 'danger');
                } else {
                    alert(data.message || 'Đã xảy ra lỗi!');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Hiển thị thông báo lỗi
            if (window.StudentUI) {
                window.StudentUI.showToast('Đã xảy ra lỗi khi gửi yêu cầu!', 'danger');
            } else {
                alert('Đã xảy ra lỗi khi gửi yêu cầu!');
            }
        })
        .finally(() => {
            // Enable buttons
            this.confirmCancelBtn.disabled = false;
            this.confirmCancelBtn.innerHTML = 'Xác nhận hủy';
        });
    }
    
    registerEvent(eventId) {
        if (!eventId) return;
        
        // Gọi API đăng ký sự kiện
        fetch(API_CONFIG.registerEvent, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken
            },
            body: JSON.stringify({
                event_id: eventId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hiển thị thông báo thành công
                if (window.StudentUI) {
                    window.StudentUI.showToast(data.message || 'Đăng ký thành công!', 'success');
                } else {
                    alert(data.message || 'Đăng ký thành công!');
                }
                
                // Tải lại dữ liệu
                this.loadAvailableEvents();
                setTimeout(() => this.loadRegisteredEvents(), 500);
            } else {
                // Hiển thị thông báo lỗi
                if (window.StudentUI) {
                    window.StudentUI.showToast(data.message || 'Đã xảy ra lỗi!', 'danger');
                } else {
                    alert(data.message || 'Đã xảy ra lỗi!');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Hiển thị thông báo lỗi
            if (window.StudentUI) {
                window.StudentUI.showToast('Đã xảy ra lỗi khi gửi yêu cầu!', 'danger');
            } else {
                alert('Đã xảy ra lỗi khi gửi yêu cầu!');
            }
        });
    }
    
    saveFeedback() {
        // Kiểm tra rating
        if (parseInt(this.ratingValue.value) === 0) {
            if (window.StudentUI) {
                window.StudentUI.showToast('Vui lòng chọn số sao đánh giá!', 'warning');
            } else {
                alert('Vui lòng chọn số sao đánh giá!');
            }
            return;
        }
        
        // Lấy dữ liệu form
        const eventId = document.getElementById('feedback-event-id').value;
        const rating = parseInt(this.ratingValue.value);
        const comments = document.getElementById('feedback-comments').value;
        
        // Disable buttons
        this.saveFeedbackBtn.disabled = true;
        this.saveFeedbackBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        // Gọi API gửi đánh giá
        fetch(API_CONFIG.feedback, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken
            },
            body: JSON.stringify({
                event_id: eventId,
                rating: rating,
                comments: comments
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hiển thị thông báo thành công
                if (window.StudentUI) {
                    window.StudentUI.showToast(data.message || 'Đánh giá thành công!', 'success');
                } else {
                    alert(data.message || 'Đánh giá thành công!');
                }
                
                // Đóng modal
                this.feedbackModal.hide();
                
                // Tải lại dữ liệu
                this.loadAttendedEvents();
            } else {
                // Hiển thị thông báo lỗi
                if (window.StudentUI) {
                    window.StudentUI.showToast(data.message || 'Đã xảy ra lỗi!', 'danger');
                } else {
                    alert(data.message || 'Đã xảy ra lỗi!');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Hiển thị thông báo lỗi
            if (window.StudentUI) {
                window.StudentUI.showToast('Đã xảy ra lỗi khi gửi yêu cầu!', 'danger');
            } else {
                alert('Đã xảy ra lỗi khi gửi yêu cầu!');
            }
        })
        .finally(() => {
            // Enable buttons
            this.saveFeedbackBtn.disabled = false;
            this.saveFeedbackBtn.innerHTML = 'Gửi đánh giá';
        });
    }
    
    toggleOtherReason() {
        const reasonValue = this.cancelReason.value;
        
        if (reasonValue === 'other') {
            this.otherReasonContainer.classList.remove('d-none');
            this.otherReason.setAttribute('required', 'required');
        } else {
            this.otherReasonContainer.classList.add('d-none');
            this.otherReason.removeAttribute('required');
        }
    }
    
    handleRating(e) {
        const value = parseInt(e.currentTarget.dataset.value);
        this.updateRating(value);
        this.ratingValue.value = value;
    }
    
    handleRatingHover(e) {
        const value = parseInt(e.currentTarget.dataset.value);
        this.updateRatingVisual(value);
    }
    
    handleRatingOut() {
        const value = parseInt(this.ratingValue.value) || 0;
        this.updateRatingVisual(value);
    }
    
    updateRating(value) {
        this.ratingValue.value = value;
        this.updateRatingVisual(value);
    }
    
    updateRatingVisual(value) {
        this.ratingStars.forEach(star => {
            const starValue = parseInt(star.dataset.value);
            if (starValue <= value) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }
    
    resetRating() {
        this.ratingValue.value = 0;
        this.updateRatingVisual(0);
    }
}

// Khởi tạo trang khi DOM đã tải xong
document.addEventListener('DOMContentLoaded', () => {
    profilePage = new ProfilePage();
});