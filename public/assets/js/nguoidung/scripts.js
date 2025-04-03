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

// Đối tượng Utility chứa các hàm tiện ích
const StudentUtils = {
    // Hiển thị toast message
    showToast: function(message, type = 'success') {
        if (window.StudentUI) {
            window.StudentUI.showToast(message, type);
            return;
        }
        
        // Fallback nếu không có StudentUI
        const toastContainer = document.querySelector('.toast-container') || this.createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast show bg-${type}`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">${type === 'success' ? 'Thành công' : 'Thông báo'}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-white">
                ${message}
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Tự động ẩn sau 5s
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    },
    
    createToastContainer: function() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
        return container;
    },
    
    // Format date
    formatDate: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    },
    
    // Format time
    formatTime: function(timeString) {
        const date = new Date(`2000-01-01T${timeString}`);
        return date.toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    // Format datetime
    formatDateTime: function(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toLocaleDateString('vi-VN', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    // Debounce function để tối ưu hiệu suất
    debounce: function(func, delay) {
        let timeoutId;
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(this, args);
            }, delay);
        };
    },
    
    // Hàm ajax helper
    ajax: function(url, method = 'GET', data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                method: method,
                data: data,
                dataType: 'json',
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }
};

// Các hàm Ajax Calendar
function initAjaxCalendar(containerId, options = {}) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const defaults = {
        apiUrl: API_URL + '/events',
        allowRegistration: true,
        onDayClick: null
    };
    
    const settings = { ...defaults, ...options };
    
    // Trạng thái nội bộ của calendar
    let state = {
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        events: [],
        selectedDay: null
    };
    
    // Khởi tạo calendar
    generateCalendar();
    loadEvents();
    
    // Gắn event listeners
    container.addEventListener('click', (e) => {
        // Xử lý khi click vào nút điều hướng tháng
        if (e.target.classList.contains('calendar-prev') || e.target.closest('.calendar-prev')) {
            changeMonth(-1);
        } else if (e.target.classList.contains('calendar-next') || e.target.closest('.calendar-next')) {
            changeMonth(1);
        }
        
        // Xử lý khi click vào ngày
        const dayEl = e.target.closest('.calendar-day');
        if (dayEl && !dayEl.classList.contains('disabled')) {
            // Bỏ chọn ngày cũ
            const currentSelected = container.querySelector('.calendar-day.selected');
            if (currentSelected) {
                currentSelected.classList.remove('selected');
            }
            
            // Chọn ngày mới
            dayEl.classList.add('selected');
            state.selectedDay = parseInt(dayEl.dataset.day);
            
            // Hiển thị sự kiện
            showEventDetails(dayEl);
            
            // Gọi callback
            if (typeof settings.onDayClick === 'function') {
                const date = new Date(state.currentYear, state.currentMonth, state.selectedDay);
                settings.onDayClick(date, dayEl.dataset.events ? JSON.parse(dayEl.dataset.events) : []);
            }
        }
    });
    
    // Hàm thay đổi tháng
    function changeMonth(delta) {
        state.currentMonth += delta;
        
        if (state.currentMonth > 11) {
            state.currentMonth = 0;
            state.currentYear++;
        } else if (state.currentMonth < 0) {
            state.currentMonth = 11;
            state.currentYear--;
        }
        
        generateCalendar();
        loadEvents();
    }
    
    // Tải sự kiện từ API
    function loadEvents() {
        const startDate = new Date(state.currentYear, state.currentMonth, 1);
        const endDate = new Date(state.currentYear, state.currentMonth + 1, 0);
        
        const formattedStart = startDate.toISOString().split('T')[0];
        const formattedEnd = endDate.toISOString().split('T')[0];
        
        // Sử dụng utils.ajax function từ Student.utils
        StudentUtils.ajax(`${settings.apiUrl}?start=${formattedStart}&end=${formattedEnd}`)
            .then(data => {
                state.events = data.events || [];
                updateCalendarWithEvents();
            })
            .catch(error => {
                console.error('Lỗi khi tải sự kiện:', error);
            });
    }
    
    function generateCalendar() {
        // Lấy thông tin ngày tháng
        const firstDay = new Date(state.currentYear, state.currentMonth, 1);
        const lastDay = new Date(state.currentYear, state.currentMonth + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startDayOfWeek = firstDay.getDay(); // 0 = Chủ nhật
        
        // Tạo danh sách các ngày trong tháng
        const days = [];
        
        // Thêm các ngày của tháng trước
        const prevMonthLastDay = new Date(state.currentYear, state.currentMonth, 0).getDate();
        for (let i = startDayOfWeek - 1; i >= 0; i--) {
            days.push({
                day: prevMonthLastDay - i,
                currentMonth: false,
                otherMonth: true
            });
        }
        
        // Thêm các ngày của tháng hiện tại
        for (let i = 1; i <= daysInMonth; i++) {
            days.push({
                day: i,
                currentMonth: true,
                today: new Date().getDate() === i && 
                       new Date().getMonth() === state.currentMonth && 
                       new Date().getFullYear() === state.currentYear
            });
        }
        
        // Thêm các ngày của tháng sau
        const remainingDays = 42 - days.length; // 6 hàng x 7 ngày
        for (let i = 1; i <= remainingDays; i++) {
            days.push({
                day: i,
                currentMonth: false,
                otherMonth: true
            });
        }
        
        // Tên tháng và năm
        const monthNames = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        
        // Tạo HTML cho calendar
        let html = `
            <div class="calendar-header">
                <button class="calendar-nav-btn calendar-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h4 class="calendar-title">${monthNames[state.currentMonth]} ${state.currentYear}</h4>
                <button class="calendar-nav-btn calendar-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="calendar-weekdays">
                <div>CN</div><div>T2</div><div>T3</div><div>T4</div><div>T5</div><div>T6</div><div>T7</div>
            </div>
            <div class="calendar-days">
        `;
        
        // Tạo HTML cho các ngày
        days.forEach(day => {
            const classes = [
                'calendar-day',
                day.currentMonth ? '' : 'other-month',
                day.today ? 'today' : '',
                day.day === state.selectedDay && day.currentMonth ? 'selected' : ''
            ].filter(Boolean).join(' ');
            
            html += createDayElement(day.day, classes);
        });
        
        html += `
            </div>
        `;
        
        // Cập nhật HTML
        container.innerHTML = html;
    }
    
    function createDayElement(day, classes, events = []) {
        const hasEvents = events.length > 0;
        const dayClasses = hasEvents ? `${classes} has-event` : classes;
        
        let html = `<div class="${dayClasses}" data-day="${day}"`;
        
        if (hasEvents) {
            html += ` data-events='${JSON.stringify(events)}'`;
        }
        
        html += `>${day}</div>`;
        return html;
    }
    
    function updateCalendarWithEvents() {
        // Cập nhật calendar với sự kiện
        state.events.forEach(event => {
            const eventDate = new Date(event.ngay_su_kien);
            const day = eventDate.getDate();
            
            if (eventDate.getMonth() === state.currentMonth && eventDate.getFullYear() === state.currentYear) {
                const dayElement = container.querySelector(`.calendar-day:not(.other-month)[data-day="${day}"]`);
                
                if (dayElement) {
                    dayElement.classList.add('has-event');
                    
                    const existingEvents = dayElement.dataset.events ? JSON.parse(dayElement.dataset.events) : [];
                    existingEvents.push(event);
                    dayElement.dataset.events = JSON.stringify(existingEvents);
                }
            }
        });
    }
    
    function showEventDetails(dayEl) {
        // Hiển thị chi tiết sự kiện
        const events = dayEl.dataset.events ? JSON.parse(dayEl.dataset.events) : [];
        const eventsContainer = container.querySelector('.calendar-events-container') || createEventsContainer();
        
        if (events.length === 0) {
            eventsContainer.innerHTML = `
                <div class="no-events">
                    <i class="far fa-calendar-times"></i>
                    <p>Không có sự kiện nào vào ngày này</p>
                </div>
            `;
            return;
        }
        
        let html = `<div class="events-list">`;
        
        events.forEach(event => {
            html += `
                <div class="event-item">
                    <div class="event-time">
                        <i class="far fa-clock"></i> ${StudentUtils.formatTime(event.gio_bat_dau)} - ${StudentUtils.formatTime(event.gio_ket_thuc)}
                    </div>
                    <div class="event-title">${event.ten_su_kien}</div>
                    <div class="event-location">
                        <i class="fas fa-map-marker-alt"></i> ${event.dia_diem}
                    </div>
                    ${settings.allowRegistration ? `
                    <div class="event-actions">
                        <a href="${base_url}nguoi-dung/events/details/${event.id}" class="btn btn-sm btn-primary">
                            <i class="fas fa-info-circle"></i> Chi tiết
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
        });
        
        html += `</div>`;
        eventsContainer.innerHTML = html;
    }
    
    function createEventsContainer() {
        const eventsContainer = document.createElement('div');
        eventsContainer.className = 'calendar-events-container mt-3';
        container.appendChild(eventsContainer);
        return eventsContainer;
    }
    
    // API public cho component
    return {
        refresh: loadEvents,
        goToMonth: (month, year) => {
            state.currentMonth = month;
            state.currentYear = year;
            generateCalendar();
            loadEvents();
        },
        getSelectedDate: () => {
            if (!state.selectedDay) return null;
            return new Date(state.currentYear, state.currentMonth, state.selectedDay);
        }
    };
}

// Khởi tạo các components khi DOM đã tải xong
document.addEventListener('DOMContentLoaded', () => {
    // Xử lý forms với AJAX
    const ajaxForms = document.querySelectorAll('form[data-ajax="true"]');
    
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    StudentUtils.showToast(data.message || 'Thao tác thành công!', 'success');
                    
                    // Redirect nếu có
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    }
                    
                    // Reset form nếu cần
                    if (form.dataset.reset === "true") {
                        form.reset();
                    }
                    
                    // Callback
                    if (form.dataset.callback) {
                        try {
                            window[form.dataset.callback](data);
                        } catch (error) {
                            console.error('Callback error:', error);
                        }
                    }
                } else {
                    StudentUtils.showToast(data.message || 'Đã xảy ra lỗi!', 'danger');
                    
                    // Hiển thị lỗi validation
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                
                                let feedbackEl = input.nextElementSibling;
                                if (!feedbackEl || !feedbackEl.classList.contains('invalid-feedback')) {
                                    feedbackEl = document.createElement('div');
                                    feedbackEl.className = 'invalid-feedback';
                                    input.parentNode.insertBefore(feedbackEl, input.nextSibling);
                                }
                                
                                feedbackEl.textContent = data.errors[field];
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                StudentUtils.showToast('Đã xảy ra lỗi khi gửi yêu cầu!', 'danger');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });
}); 