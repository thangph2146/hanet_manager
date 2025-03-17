/**
 * Student Dashboard Scripts
 * Bao gồm các hàm xử lý Ajax và nâng cao trải nghiệm người dùng
 */

"use strict";

// Thiết lập CSRF token cho tất cả các ajax request
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Các biến toàn cục
const API_URL = document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') || '';

// Đối tượng chính xử lý giao diện người dùng
const StudentApp = {
    // Khởi tạo ứng dụng
    init: function() {
        this.setupSidebar();
        this.setupNotifications();
        this.setupAjaxHandlers();
        this.setupEventHandlers();
        this.setupToasts();
    },

    // Thiết lập sidebar responsive
    setupSidebar: function() {
        // Toggle sidebar trên thiết bị di động
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebarContainer = document.querySelector('.sidebar-container');
        const contentWrapper = document.querySelector('.content-wrapper');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebarContainer.classList.toggle('show');
                
                // Thêm overlay khi sidebar hiển thị trên mobile
                if (sidebarContainer.classList.contains('show')) {
                    const overlay = document.createElement('div');
                    overlay.classList.add('sidebar-overlay');
                    document.body.appendChild(overlay);
                    
                    overlay.addEventListener('click', function() {
                        sidebarContainer.classList.remove('show');
                        overlay.remove();
                    });
                } else {
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) overlay.remove();
                }
            });
        }

        // Đóng sidebar khi click vào link trên mobile
        const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebarContainer.classList.remove('show');
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) overlay.remove();
                }
            });
        });
    },

    // Thiết lập thông báo
    setupNotifications: function() {
        // Tải thông báo từ server mỗi 30 giây
        this.loadNotifications();
        setInterval(() => this.loadNotifications(), 30000);

        // Đánh dấu thông báo đã đọc khi click
        document.addEventListener('click', (e) => {
            const notificationItem = e.target.closest('.notification-item');
            if (notificationItem) {
                const notificationId = notificationItem.dataset.id;
                if (notificationId) {
                    this.markNotificationAsRead(notificationId);
                }
            }
        });
    },

    // Thiết lập xử lý AJAX chung
    setupAjaxHandlers: function() {
        // Hiển thị loading indicator khi bắt đầu ajax request
        $(document).on('ajaxStart', function() {
            $('#ajax-loading-indicator').show();
        });

        // Ẩn loading indicator khi ajax request hoàn thành
        $(document).on('ajaxStop', function() {
            $('#ajax-loading-indicator').hide();
        });

        // Xử lý lỗi ajax
        $(document).on('ajaxError', function(event, jqXHR, ajaxSettings, thrownError) {
            if (jqXHR.status === 401) {
                // Người dùng chưa đăng nhập, chuyển hướng tới trang đăng nhập
                window.location.href = '/login';
            } else if (jqXHR.status === 403) {
                // Người dùng không có quyền truy cập
                StudentApp.showToast('Lỗi', 'Bạn không có quyền thực hiện hành động này', 'error');
            } else {
                // Lỗi khác
                StudentApp.showToast('Lỗi', 'Đã xảy ra lỗi khi xử lý yêu cầu', 'error');
                console.error('Ajax Error:', thrownError);
            }
        });
    },

    // Thiết lập các sự kiện
    setupEventHandlers: function() {
        // Xử lý các form Ajax
        document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAjaxForm(form);
            });
        });

        // Xử lý các nút Ajax
        document.querySelectorAll('[data-ajax-url]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const url = button.dataset.ajaxUrl;
                const method = button.dataset.ajaxMethod || 'GET';
                const confirm = button.dataset.ajaxConfirm;
                
                if (confirm && !window.confirm(confirm)) {
                    return;
                }
                
                this.ajaxRequest(url, method);
            });
        });
    },

    // Thiết lập hiển thị thông báo toast
    setupToasts: function() {
        // Tạo container cho toast messages nếu chưa tồn tại
        if (!document.querySelector('.toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.classList.add('toast-container');
            document.body.appendChild(toastContainer);
        }
    },

    // Hiển thị thông báo toast
    showToast: function(title, message, type = 'info', duration = 5000) {
        const toastContainer = document.querySelector('.toast-container');
        const icon = this.getToastIcon(type);
        
        const toastEl = document.createElement('div');
        toastEl.className = `toast toast-${type}`;
        toastEl.innerHTML = `
            <div class="toast-header">
                ${icon}
                <strong class="me-auto">${title}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">${message}</div>
        `;
        
        toastContainer.appendChild(toastEl);
        
        // Xử lý đóng toast
        const closeBtn = toastEl.querySelector('.btn-close');
        closeBtn.addEventListener('click', () => {
            toastEl.remove();
        });
        
        // Tự động đóng toast sau thời gian
        setTimeout(() => {
            toastEl.classList.add('fade-out');
            setTimeout(() => toastEl.remove(), 300);
        }, duration);
    },

    // Lấy icon cho toast
    getToastIcon: function(type) {
        switch (type) {
            case 'success':
                return '<i class="fas fa-check-circle text-success me-2"></i>';
            case 'error':
                return '<i class="fas fa-exclamation-circle text-danger me-2"></i>';
            case 'warning':
                return '<i class="fas fa-exclamation-triangle text-warning me-2"></i>';
            default:
                return '<i class="fas fa-info-circle text-info me-2"></i>';
        }
    },

    // Tải thông báo từ server
    loadNotifications: function() {
        this.ajaxRequest('/students/notifications/ajax', 'GET', null, (data) => {
            if (data.success && data.notifications) {
                this.updateNotificationBadge(data.unread_count);
                this.updateNotificationDropdown(data.notifications);
            }
        });
    },

    // Cập nhật badge thông báo
    updateNotificationBadge: function(count) {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }
    },

    // Cập nhật dropdown thông báo
    updateNotificationDropdown: function(notifications) {
        const dropdown = document.querySelector('#notificationsDropdown + .dropdown-menu');
        if (!dropdown) return;
        
        // Xóa các thông báo cũ
        const items = dropdown.querySelectorAll('.dropdown-item:not(.text-center)');
        items.forEach(item => item.remove());
        
        // Xóa divider nếu có
        const divider = dropdown.querySelector('.dropdown-divider');
        if (divider) divider.remove();
        
        // Thêm header
        const header = dropdown.querySelector('.dropdown-header');
        if (header) {
            header.textContent = 'Thông báo mới';
        } else {
            const newHeader = document.createElement('span');
            newHeader.className = 'dropdown-header';
            newHeader.textContent = 'Thông báo mới';
            dropdown.prepend(newHeader);
        }
        
        // Thêm divider
        const newDivider = document.createElement('div');
        newDivider.className = 'dropdown-divider';
        header.after(newDivider);
        
        // Thêm các thông báo mới
        if (notifications.length === 0) {
            const emptyItem = document.createElement('a');
            emptyItem.className = 'dropdown-item';
            emptyItem.textContent = 'Không có thông báo mới';
            newDivider.after(emptyItem);
        } else {
            notifications.forEach(notification => {
                const item = document.createElement('a');
                item.className = 'dropdown-item notification-item d-flex align-items-center py-2';
                item.href = notification.url || '#';
                item.dataset.id = notification.id;
                
                // Thêm class đã đọc hoặc chưa đọc
                if (!notification.read_at) {
                    item.classList.add('unread');
                }
                
                // Tạo nội dung thông báo
                item.innerHTML = `
                    <div class="notification-icon me-2">
                        <i class="fas ${notification.icon || 'fa-bell'} text-${notification.type || 'primary'}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-text">${notification.content}</div>
                        <div class="notification-time small text-muted">${notification.time_ago}</div>
                    </div>
                `;
                
                newDivider.after(item);
            });
        }
        
        // Thêm link xem tất cả
        let viewAllLink = dropdown.querySelector('.dropdown-item.text-center');
        if (!viewAllLink) {
            viewAllLink = document.createElement('a');
            viewAllLink.className = 'dropdown-item text-center';
            viewAllLink.href = '/students/notifications';
            viewAllLink.textContent = 'Xem tất cả';
            dropdown.appendChild(viewAllLink);
        }
    },

    // Đánh dấu thông báo đã đọc
    markNotificationAsRead: function(id) {
        this.ajaxRequest('/students/notifications/mark-as-read', 'POST', { id });
    },

    // Xử lý form Ajax
    handleAjaxForm: function(form) {
        const url = form.action;
        const method = form.method.toUpperCase() || 'POST';
        const formData = new FormData(form);
        
        // Hiển thị loading trên nút submit
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span> Đang xử lý...';
        }
        
        // Thực hiện request Ajax
        this.ajaxRequest(url, method, formData, (data) => {
            // Khôi phục nút submit
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
            
            // Xử lý kết quả
            if (data.success) {
                // Nếu có thông báo thành công
                if (data.message) {
                    this.showToast('Thành công', data.message, 'success');
                }
                
                // Nếu cần redirect
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                
                // Nếu có callback
                const callback = form.dataset.ajaxCallback;
                if (callback && typeof window[callback] === 'function') {
                    window[callback](data);
                }
                
                // Nếu cần reset form
                if (form.dataset.ajaxReset !== 'false') {
                    form.reset();
                }
                
                // Nếu cần reload
                if (form.dataset.ajaxReload === 'true') {
                    window.location.reload();
                }
            } else {
                // Nếu có lỗi
                if (data.message) {
                    this.showToast('Lỗi', data.message, 'error');
                }
                
                // Hiển thị các lỗi validation
                if (data.errors) {
                    this.showFormErrors(form, data.errors);
                }
            }
        });
    },

    // Hiển thị lỗi validation trên form
    showFormErrors: function(form, errors) {
        // Xóa thông báo lỗi cũ
        form.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        form.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.remove();
        });
        
        // Thêm thông báo lỗi mới
        for (const field in errors) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = errors[field];
                
                input.after(feedback);
            }
        }
    },

    // Hàm thực hiện Ajax request
    ajaxRequest: function(url, method = 'GET', data = null, callback = null) {
        if (!url) return;
        
        const options = {
            url: url,
            type: method,
            dataType: 'json',
            processData: !(data instanceof FormData),
            contentType: !(data instanceof FormData) ? 'application/x-www-form-urlencoded; charset=UTF-8' : false,
        };
        
        if (data) {
            options.data = data;
        }
        
        $.ajax(options)
            .done(function(response) {
                if (callback && typeof callback === 'function') {
                    callback(response);
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Ajax request failed:', textStatus, errorThrown);
            });
    },

    // Tải dữ liệu sử dụng Ajax cho các element có data-ajax-load
    loadAjaxContent: function() {
        document.querySelectorAll('[data-ajax-load]').forEach(element => {
            const url = element.dataset.ajaxLoad;
            if (!url) return;
            
            // Hiển thị loading
            element.innerHTML = '<div class="text-center p-3"><span class="loading-spinner"></span> Đang tải...</div>';
            
            // Thực hiện request
            this.ajaxRequest(url, 'GET', null, (data) => {
                if (data.html) {
                    element.innerHTML = data.html;
                } else {
                    element.innerHTML = '<div class="alert alert-warning">Không thể tải nội dung</div>';
                }
            });
        });
    },

    // Tải dữ liệu sự kiện cho lịch
    loadCalendarEvents: function(year, month, callback) {
        this.ajaxRequest(`/students/events/calendar/${year}/${month}`, 'GET', null, (data) => {
            if (callback && typeof callback === 'function') {
                callback(data.events || []);
            }
        });
    }
};

// Khởi chạy khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo ứng dụng
    StudentApp.init();
    
    // Tải nội dung Ajax
    StudentApp.loadAjaxContent();
});

// Hàm tạo Calendar nâng cao với Ajax
function initAjaxCalendar(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Các thiết lập mặc định
    const defaultOptions = {
        showEventDetails: true,
        enableEventCreation: false,
        onDayClick: null,
        onEventClick: null
    };
    
    // Kết hợp options
    const settings = {...defaultOptions, ...options};
    
    // Tháng và năm hiện tại
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    
    // Các ngày có sự kiện
    let eventDays = [];
    
    // Khởi tạo calendar
    const calendarHeader = document.createElement('div');
    calendarHeader.className = 'calendar-header';
    calendarHeader.innerHTML = `
        <div class="calendar-title">Tháng ${currentMonth + 1}/${currentYear}</div>
        <div class="calendar-nav">
            <button class="calendar-nav-btn prev-month">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="calendar-nav-btn next-month">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    `;
    
    const calendarGrid = document.createElement('div');
    calendarGrid.className = 'calendar-grid';
    calendarGrid.innerHTML = `
        <div class="calendar-weekdays">
            <div class="calendar-weekday">CN</div>
            <div class="calendar-weekday">T2</div>
            <div class="calendar-weekday">T3</div>
            <div class="calendar-weekday">T4</div>
            <div class="calendar-weekday">T5</div>
            <div class="calendar-weekday">T6</div>
            <div class="calendar-weekday">T7</div>
        </div>
        <div class="calendar-days"></div>
    `;
    
    const eventDetails = document.createElement('div');
    eventDetails.className = 'calendar-event-details';
    eventDetails.style.display = 'none';
    
    // Thêm các phần tử vào container
    container.innerHTML = '';
    container.appendChild(calendarHeader);
    container.appendChild(calendarGrid);
    
    if (settings.showEventDetails) {
        container.appendChild(eventDetails);
    }
    
    // Các phần tử DOM
    const calendarTitle = container.querySelector('.calendar-title');
    const calendarDays = container.querySelector('.calendar-days');
    const prevMonthBtn = container.querySelector('.prev-month');
    const nextMonthBtn = container.querySelector('.next-month');
    
    // Xử lý điều hướng
    prevMonthBtn.addEventListener('click', () => changeMonth(-1));
    nextMonthBtn.addEventListener('click', () => changeMonth(1));
    
    // Thay đổi tháng
    function changeMonth(delta) {
        currentMonth += delta;
        
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        
        calendarTitle.textContent = `Tháng ${currentMonth + 1}/${currentYear}`;
        loadEvents();
    }
    
    // Tải danh sách sự kiện
    function loadEvents() {
        // Hiển thị loading
        calendarDays.innerHTML = '<div class="text-center py-5"><span class="loading-spinner"></span> Đang tải...</div>';
        
        // Gọi API để lấy danh sách sự kiện
        StudentApp.loadCalendarEvents(currentYear, currentMonth + 1, (events) => {
            eventDays = events;
            generateCalendar();
        });
    }
    
    // Tạo lịch
    function generateCalendar() {
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        
        // Xóa các ngày cũ
        calendarDays.innerHTML = '';
        
        // Thêm ngày từ tháng trước
        const daysFromPrevMonth = firstDay.getDay();
        const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
        
        for (let i = 0; i < daysFromPrevMonth; i++) {
            const dayNumber = prevMonthLastDay - daysFromPrevMonth + i + 1;
            const dayEl = createDayElement(dayNumber, 'other-month');
            calendarDays.appendChild(dayEl);
        }
        
        // Thêm ngày trong tháng hiện tại
        const today = new Date();
        
        for (let i = 1; i <= lastDay.getDate(); i++) {
            let classes = [];
            
            // Kiểm tra nếu là ngày hôm nay
            if (today.getDate() === i && today.getMonth() === currentMonth && today.getFullYear() === currentYear) {
                classes.push('today');
            }
            
            // Tìm danh sách sự kiện cho ngày này
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            const dayEvents = eventDays.filter(event => event.date === dateStr);
            
            if (dayEvents.length > 0) {
                classes.push('has-event');
                classes.push(`event-count-${Math.min(dayEvents.length, 3)}`);
            }
            
            const dayEl = createDayElement(i, classes.join(' '), dayEvents);
            calendarDays.appendChild(dayEl);
        }
        
        // Thêm ngày từ tháng sau
        const daysInGrid = daysFromPrevMonth + lastDay.getDate();
        const remainingDays = 42 - daysInGrid; // 6 rows x 7 days
        
        for (let i = 1; i <= remainingDays; i++) {
            const dayEl = createDayElement(i, 'other-month');
            calendarDays.appendChild(dayEl);
        }
    }
    
    // Tạo phần tử ngày
    function createDayElement(day, classes, events = []) {
        const dayEl = document.createElement('div');
        dayEl.className = `calendar-day ${classes}`;
        dayEl.textContent = day;
        
        // Thêm data events
        if (events.length > 0) {
            dayEl.dataset.events = JSON.stringify(events);
        }
        
        // Xử lý click vào ngày
        dayEl.addEventListener('click', function() {
            // Xóa lớp selected từ tất cả các ngày
            document.querySelectorAll('.calendar-day').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Thêm lớp selected vào ngày được click
            this.classList.add('selected');
            
            // Hiển thị chi tiết sự kiện nếu có
            if (settings.showEventDetails && this.classList.contains('has-event')) {
                showEventDetails(this);
            }
            
            // Callback khi click vào ngày
            if (settings.onDayClick && typeof settings.onDayClick === 'function') {
                const date = new Date(currentYear, currentMonth, parseInt(this.textContent));
                const dateEvents = JSON.parse(this.dataset.events || '[]');
                settings.onDayClick(date, dateEvents);
            }
        });
        
        return dayEl;
    }
    
    // Hiển thị chi tiết sự kiện
    function showEventDetails(dayEl) {
        const events = JSON.parse(dayEl.dataset.events || '[]');
        
        if (events.length === 0) {
            eventDetails.style.display = 'none';
            return;
        }
        
        let html = `<div class="event-list">`;
        
        events.forEach(event => {
            html += `
                <div class="event-list-item" data-event-id="${event.id}">
                    <div class="event-time">
                        <i class="far fa-clock"></i> ${event.time}
                    </div>
                    <div class="event-title">${event.title}</div>
                    <div class="event-location">
                        <i class="fas fa-map-marker-alt"></i> ${event.location}
                    </div>
                    <a href="${event.url}" class="event-link">Chi tiết</a>
                </div>
            `;
        });
        
        html += `</div>`;
        
        eventDetails.innerHTML = html;
        eventDetails.style.display = 'block';
        
        // Xử lý click vào sự kiện
        const eventItems = eventDetails.querySelectorAll('.event-list-item');
        eventItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Không xử lý nếu click vào link
                if (e.target.tagName === 'A') return;
                
                const eventId = this.dataset.eventId;
                const event = events.find(e => e.id == eventId);
                
                if (settings.onEventClick && typeof settings.onEventClick === 'function') {
                    settings.onEventClick(event);
                }
            });
        });
    }
    
    // Tải dữ liệu ban đầu
    loadEvents();
    
    // Public API
    return {
        refresh: loadEvents,
        setMonth: function(year, month) {
            currentYear = year;
            currentMonth = month - 1; // Chuyển từ 1-12 sang 0-11
            calendarTitle.textContent = `Tháng ${currentMonth + 1}/${currentYear}`;
            loadEvents();
        },
        getCurrentMonth: function() {
            return {
                year: currentYear,
                month: currentMonth + 1
            };
        }
    };
}

// Tạo hàm AJAX helper để các view khác có thể sử dụng
const Ajax = {
    get: function(url, data = null, successCallback = null, errorCallback = null) {
        return this.request('GET', url, data, successCallback, errorCallback);
    },
    
    post: function(url, data = null, successCallback = null, errorCallback = null) {
        return this.request('POST', url, data, successCallback, errorCallback);
    },
    
    request: function(method, url, data = null, successCallback = null, errorCallback = null) {
        return $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'json',
            success: function(response) {
                if (successCallback && typeof successCallback === 'function') {
                    successCallback(response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Ajax request failed:', textStatus, errorThrown);
                
                if (errorCallback && typeof errorCallback === 'function') {
                    errorCallback(jqXHR, textStatus, errorThrown);
                }
            }
        });
    }
}; 