/**
 * Notification Component JS
 * Hệ thống thông báo cho Student App
 */
class StudentNotification {
    constructor() {
        this.containerClass = 'notification-container';
        this.notificationClass = 'notification';
        this.showClass = 'show';
        this.iconClass = 'notification-icon';
        this.contentClass = 'notification-content';
        this.titleClass = 'notification-title';
        this.messageClass = 'notification-message';
        this.closeClass = 'notification-close';
        this.progressClass = 'notification-progress';
        this.progressBarClass = 'notification-progress-bar';
        this.duration = 5000; // Thời gian hiển thị mặc định (5 giây)
        
        this.init();
    }
    
    init() {
        // Tạo container nếu chưa tồn tại
        if (!document.querySelector(`.${this.containerClass}`)) {
            const container = document.createElement('div');
            container.className = this.containerClass;
            document.body.appendChild(container);
        }
    }
    
    /**
     * Hiển thị thông báo
     * @param {Object} options - Các tùy chọn
     * @param {string} options.type - Loại thông báo (success, error, warning, info)
     * @param {string} options.title - Tiêu đề thông báo
     * @param {string} options.message - Nội dung thông báo
     * @param {number} options.duration - Thời gian hiển thị (ms)
     * @param {boolean} options.showProgress - Hiển thị thanh tiến trình
     * @param {boolean} options.closable - Cho phép đóng bằng nút
     */
    show(options = {}) {
        const type = options.type || 'info';
        const title = options.title || '';
        const message = options.message || '';
        const duration = options.duration || this.duration;
        const showProgress = options.showProgress !== false;
        const closable = options.closable !== false;
        
        // Lấy container
        const container = document.querySelector(`.${this.containerClass}`);
        if (!container) return;
        
        // Tạo thông báo
        const notification = document.createElement('div');
        notification.className = `${this.notificationClass} ${type}`;
        
        // Xác định icon theo loại
        let icon = 'fa-info-circle';
        if (type === 'success') icon = 'fa-check-circle';
        if (type === 'error') icon = 'fa-exclamation-circle';
        if (type === 'warning') icon = 'fa-exclamation-triangle';
        
        // Tạo HTML nội bộ
        notification.innerHTML = `
            <div class="${this.iconClass}">
                <i class="fas ${icon}"></i>
            </div>
            <div class="${this.contentClass}">
                ${title ? `<div class="${this.titleClass}">${title}</div>` : ''}
                ${message ? `<div class="${this.messageClass}">${message}</div>` : ''}
            </div>
            ${closable ? `
                <button class="${this.closeClass}">
                    <i class="fas fa-times"></i>
                </button>
            ` : ''}
            ${showProgress ? `
                <div class="${this.progressClass}">
                    <div class="${this.progressBarClass}"></div>
                </div>
            ` : ''}
        `;
        
        // Thêm vào container
        container.appendChild(notification);
        
        // Hiển thị với hiệu ứng
        setTimeout(() => {
            notification.classList.add(this.showClass);
        }, 10);
        
        // Xử lý nút đóng
        const closeButton = notification.querySelector(`.${this.closeClass}`);
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                this.close(notification);
            });
        }
        
        // Thiết lập thời gian tự động đóng
        if (duration !== 0) {
            // Thiết lập animation duration cho thanh tiến trình
            if (showProgress) {
                const progressBar = notification.querySelector(`.${this.progressBarClass}`);
                if (progressBar) {
                    progressBar.style.animationDuration = `${duration}ms`;
                }
            }
            
            // Tự động đóng sau thời gian
            setTimeout(() => {
                this.close(notification);
            }, duration);
        }
        
        // Trả về thông báo để có thể sử dụng sau này
        return notification;
    }
    
    /**
     * Đóng thông báo
     * @param {HTMLElement} notification - Element thông báo cần đóng
     */
    close(notification) {
        if (!notification) return;
        
        // Thêm hiệu ứng đóng
        notification.classList.remove(this.showClass);
        
        // Xóa sau khi animation kết thúc
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
    
    /**
     * Đóng tất cả thông báo
     */
    closeAll() {
        const notifications = document.querySelectorAll(`.${this.notificationClass}`);
        notifications.forEach(notification => {
            this.close(notification);
        });
    }
    
    /**
     * Hiển thị thông báo thành công
     * @param {string} message - Nội dung thông báo
     * @param {string} title - Tiêu đề thông báo
     * @param {number} duration - Thời gian hiển thị
     */
    success(message, title = 'Thành công', duration = this.duration) {
        return this.show({
            type: 'success',
            title,
            message,
            duration
        });
    }
    
    /**
     * Hiển thị thông báo lỗi
     * @param {string} message - Nội dung thông báo
     * @param {string} title - Tiêu đề thông báo
     * @param {number} duration - Thời gian hiển thị
     */
    error(message, title = 'Lỗi', duration = this.duration) {
        return this.show({
            type: 'error',
            title,
            message,
            duration
        });
    }
    
    /**
     * Hiển thị thông báo cảnh báo
     * @param {string} message - Nội dung thông báo
     * @param {string} title - Tiêu đề thông báo
     * @param {number} duration - Thời gian hiển thị
     */
    warning(message, title = 'Cảnh báo', duration = this.duration) {
        return this.show({
            type: 'warning',
            title,
            message,
            duration
        });
    }
    
    /**
     * Hiển thị thông báo thông tin
     * @param {string} message - Nội dung thông báo
     * @param {string} title - Tiêu đề thông báo
     * @param {number} duration - Thời gian hiển thị
     */
    info(message, title = 'Thông báo', duration = this.duration) {
        return this.show({
            type: 'info',
            title,
            message,
            duration
        });
    }
}

// Khởi tạo hệ thống thông báo
const studentNotification = new StudentNotification();

// Thêm vào đối tượng window để có thể sử dụng ở mọi nơi
window.studentNotification = studentNotification; 