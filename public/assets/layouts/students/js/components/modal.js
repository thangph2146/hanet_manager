/**
 * Modal Component JS
 * Hệ thống modal cho Student App
 */
class StudentModal {
    constructor() {
        this.overlayClass = 'st-modal-overlay';
        this.modalClass = 'st-modal';
        this.headerClass = 'st-modal-header';
        this.titleClass = 'st-modal-title';
        this.bodyClass = 'st-modal-body';
        this.footerClass = 'st-modal-footer';
        this.closeClass = 'st-modal-close';
        this.activeClass = 'active';
        
        this.escapeListener = this.escapeListener.bind(this);
        this.init();
    }
    
    init() {
        // Khởi tạo sự kiện ESC để đóng tất cả modal đang mở
        document.addEventListener('keydown', this.escapeListener);
    }
    
    escapeListener(event) {
        if (event.key === 'Escape') {
            const openModals = document.querySelectorAll(`.${this.overlayClass}.${this.activeClass}`);
            if (openModals.length > 0) {
                this.close(openModals[openModals.length - 1]); // Đóng modal gần nhất
            }
        }
    }
    
    /**
     * Tạo modal mới
     * @param {Object} options - Các tùy chọn
     * @param {string} options.id - ID của modal
     * @param {string} options.title - Tiêu đề modal
     * @param {string} options.content - Nội dung HTML của modal
     * @param {string} options.size - Kích thước modal (sm, md, lg, xl, fullscreen)
     * @param {Array} options.buttons - Các nút trong footer
     * @param {boolean} options.closable - Cho phép đóng bằng nút X
     * @param {string} options.animation - Hiệu ứng (fade, slide-top, slide-bottom)
     * @returns {HTMLElement} - Element overlay chứa modal
     */
    create(options = {}) {
        const id = options.id || `modal-${Date.now()}`;
        const title = options.title || 'Modal Title';
        const content = options.content || '';
        const size = options.size || 'md';
        const buttons = options.buttons || [];
        const closable = options.closable !== false;
        const animation = options.animation || '';
        
        // Tạo overlay
        const overlay = document.createElement('div');
        overlay.className = `${this.overlayClass}${animation ? ` st-modal-${animation}` : ''}`;
        overlay.id = id;
        
        // Tạo modal
        const modal = document.createElement('div');
        modal.className = `${this.modalClass}${size ? ` st-modal-${size}` : ''}`;
        
        // Tạo header
        const header = document.createElement('div');
        header.className = this.headerClass;
        
        const titleEl = document.createElement('h3');
        titleEl.className = this.titleClass;
        titleEl.innerHTML = title;
        header.appendChild(titleEl);
        
        if (closable) {
            const closeBtn = document.createElement('button');
            closeBtn.className = this.closeClass;
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            closeBtn.addEventListener('click', () => {
                this.close(overlay);
            });
            header.appendChild(closeBtn);
        }
        
        // Tạo body
        const body = document.createElement('div');
        body.className = this.bodyClass;
        body.innerHTML = content;
        
        // Tạo footer nếu có buttons
        let footer = null;
        if (buttons.length > 0) {
            footer = document.createElement('div');
            footer.className = this.footerClass;
            
            buttons.forEach(btn => {
                const button = document.createElement('button');
                button.className = `st-modal-btn ${btn.class || ''}`;
                button.innerHTML = btn.text || 'Button';
                
                if (btn.id) {
                    button.id = btn.id;
                }
                
                if (btn.attributes) {
                    Object.keys(btn.attributes).forEach(key => {
                        button.setAttribute(key, btn.attributes[key]);
                    });
                }
                
                if (btn.onClick) {
                    button.addEventListener('click', (e) => {
                        btn.onClick(e, overlay);
                    });
                }
                
                if (btn.close) {
                    button.addEventListener('click', () => {
                        this.close(overlay);
                    });
                }
                
                footer.appendChild(button);
            });
        }
        
        // Ghép các phần
        modal.appendChild(header);
        modal.appendChild(body);
        if (footer) {
            modal.appendChild(footer);
        }
        
        overlay.appendChild(modal);
        
        // Thêm sự kiện click bên ngoài để đóng modal
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay && closable) {
                this.close(overlay);
            }
        });
        
        // Thêm vào body
        document.body.appendChild(overlay);
        
        return overlay;
    }
    
    /**
     * Mở modal
     * @param {string|HTMLElement} modal - ID hoặc element của modal cần mở
     */
    open(modal) {
        const modalEl = typeof modal === 'string' ? document.getElementById(modal) : modal;
        if (!modalEl) return;
        
        // Ngăn scroll body
        document.body.style.overflow = 'hidden';
        
        // Hiển thị modal
        modalEl.classList.add(this.activeClass);
        
        // Gọi sự kiện mở modal
        const event = new CustomEvent('modal.open', { detail: { modal: modalEl } });
        document.dispatchEvent(event);
    }
    
    /**
     * Đóng modal
     * @param {string|HTMLElement} modal - ID hoặc element của modal cần đóng
     */
    close(modal) {
        const modalEl = typeof modal === 'string' ? document.getElementById(modal) : modal;
        if (!modalEl) return;
        
        // Ẩn modal
        modalEl.classList.remove(this.activeClass);
        
        // Kiểm tra xem còn modal nào đang mở không
        const openModals = document.querySelectorAll(`.${this.overlayClass}.${this.activeClass}`);
        if (openModals.length === 0) {
            // Khôi phục scroll body nếu không còn modal nào đang mở
            document.body.style.overflow = '';
        }
        
        // Gọi sự kiện đóng modal
        const event = new CustomEvent('modal.close', { detail: { modal: modalEl } });
        document.dispatchEvent(event);
    }
    
    /**
     * Xóa modal
     * @param {string|HTMLElement} modal - ID hoặc element của modal cần xóa
     */
    destroy(modal) {
        const modalEl = typeof modal === 'string' ? document.getElementById(modal) : modal;
        if (!modalEl) return;
        
        this.close(modalEl);
        
        // Xóa sau khi animation kết thúc
        setTimeout(() => {
            modalEl.remove();
        }, 300);
    }
    
    /**
     * Tạo alert modal đơn giản
     * @param {Object} options - Các tùy chọn
     * @param {string} options.title - Tiêu đề alert
     * @param {string} options.message - Nội dung alert
     * @param {function} options.onOK - Callback khi nhấn OK
     * @param {string} options.okText - Text của nút OK
     * @param {string} options.okClass - Class của nút OK
     */
    alert(options = {}) {
        const title = options.title || 'Thông báo';
        const message = options.message || '';
        const onOK = options.onOK || null;
        const okText = options.okText || 'OK';
        const okClass = options.okClass || 'st-modal-btn-primary';
        
        const modal = this.create({
            title,
            content: `<p>${message}</p>`,
            size: 'sm',
            animation: 'fade',
            buttons: [
                {
                    text: okText,
                    class: okClass,
                    close: true,
                    onClick: onOK
                }
            ]
        });
        
        this.open(modal);
        return modal;
    }
    
    /**
     * Tạo confirm modal với 2 nút
     * @param {Object} options - Các tùy chọn
     * @param {string} options.title - Tiêu đề confirm
     * @param {string} options.message - Nội dung confirm
     * @param {function} options.onConfirm - Callback khi nhấn confirm
     * @param {function} options.onCancel - Callback khi nhấn cancel
     * @param {string} options.confirmText - Text của nút confirm
     * @param {string} options.cancelText - Text của nút cancel
     * @param {string} options.confirmClass - Class của nút confirm
     * @param {string} options.cancelClass - Class của nút cancel
     */
    confirm(options = {}) {
        const title = options.title || 'Xác nhận';
        const message = options.message || '';
        const onConfirm = options.onConfirm || null;
        const onCancel = options.onCancel || null;
        const confirmText = options.confirmText || 'Xác nhận';
        const cancelText = options.cancelText || 'Hủy';
        const confirmClass = options.confirmClass || 'st-modal-btn-primary';
        const cancelClass = options.cancelClass || 'st-modal-btn-secondary';
        
        const modal = this.create({
            title,
            content: `<p>${message}</p>`,
            size: 'sm',
            animation: 'fade',
            buttons: [
                {
                    text: cancelText,
                    class: cancelClass,
                    close: true,
                    onClick: onCancel
                },
                {
                    text: confirmText,
                    class: confirmClass,
                    close: true,
                    onClick: onConfirm
                }
            ]
        });
        
        this.open(modal);
        return modal;
    }
    
    /**
     * Tạo prompt modal với input
     * @param {Object} options - Các tùy chọn
     * @param {string} options.title - Tiêu đề prompt
     * @param {string} options.message - Nội dung prompt
     * @param {string} options.placeholder - Placeholder cho input
     * @param {string} options.defaultValue - Giá trị mặc định
     * @param {function} options.onSubmit - Callback khi nhấn submit
     * @param {function} options.onCancel - Callback khi nhấn cancel
     * @param {string} options.submitText - Text của nút submit
     * @param {string} options.cancelText - Text của nút cancel
     */
    prompt(options = {}) {
        const title = options.title || 'Nhập dữ liệu';
        const message = options.message || '';
        const placeholder = options.placeholder || '';
        const defaultValue = options.defaultValue || '';
        const onSubmit = options.onSubmit || null;
        const onCancel = options.onCancel || null;
        const submitText = options.submitText || 'Xác nhận';
        const cancelText = options.cancelText || 'Hủy';
        
        const inputId = `prompt-input-${Date.now()}`;
        const content = `
            ${message ? `<p>${message}</p>` : ''}
            <div class="st-modal-form-group">
                <input type="text" id="${inputId}" class="st-modal-input" placeholder="${placeholder}" value="${defaultValue}">
            </div>
        `;
        
        const modal = this.create({
            title,
            content,
            size: 'sm',
            animation: 'fade',
            buttons: [
                {
                    text: cancelText,
                    class: 'st-modal-btn-secondary',
                    close: true,
                    onClick: onCancel
                },
                {
                    text: submitText,
                    class: 'st-modal-btn-primary',
                    close: true,
                    onClick: (e, modalEl) => {
                        const input = document.getElementById(inputId);
                        if (onSubmit && input) {
                            onSubmit(input.value, modalEl);
                        }
                    }
                }
            ]
        });
        
        this.open(modal);
        
        // Focus vào input
        setTimeout(() => {
            const input = document.getElementById(inputId);
            if (input) {
                input.focus();
                input.select();
            }
        }, 100);
        
        return modal;
    }
}

// Khởi tạo hệ thống modal
const studentModal = new StudentModal();

// Thêm vào đối tượng window để có thể sử dụng ở mọi nơi
window.studentModal = studentModal; 