/**
 * Quản lý trang hồ sơ cá nhân
 * Xử lý tất cả tương tác và tính năng cho trang cá nhân
 */
class ProfileManager {
    constructor() {
        // DOM Elements 
        this.profileTabs = document.getElementById('profileTab');
        this.profileTabContent = document.getElementById('profileTabContent');
        this.editProfileBtn = document.getElementById('edit-profile-btn');
        this.cancelEventButtons = document.querySelectorAll('.cancel-event-btn');
        this.registerEventButtons = document.querySelectorAll('.register-event-btn');
        this.searchEventsInput = document.getElementById('search-events');
        this.viewFeedbackButtons = document.querySelectorAll('.view-feedback-btn');
        
        // Data state
        this.activeTab = 'personal-info';
        this.isEditing = false;
        
        // Khởi tạo
        this.init();
    }
    
    init() {
        // Khởi tạo tooltips
        this.initTooltips();
        
        // Thiết lập các event listeners
        this.setupEventListeners();
        
        // Thực hiện các tác vụ khi tải trang
        this.onPageLoad();
    }
    
    initTooltips() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
    
    setupEventListeners() {
        // Xử lý sự kiện thay đổi tab
        if (this.profileTabs) {
            const tabButtons = this.profileTabs.querySelectorAll('.nav-link');
            tabButtons.forEach(tabButton => {
                tabButton.addEventListener('click', (e) => {
                    this.activeTab = tabButton.getAttribute('id').replace('-tab', '');
                    this.updateUrlWithTab(this.activeTab);
                });
            });
        }
        
        // Xử lý sự kiện chỉnh sửa thông tin cá nhân
        if (this.editProfileBtn) {
            this.editProfileBtn.addEventListener('click', () => this.toggleEditMode());
        }
        
        // Xử lý sự kiện hủy đăng ký
        this.cancelEventButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleCancelEvent(e));
        });
        
        // Xử lý sự kiện đăng ký sự kiện
        this.registerEventButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleRegisterEvent(e));
        });
        
        // Xử lý sự kiện tìm kiếm sự kiện
        if (this.searchEventsInput) {
            this.searchEventsInput.addEventListener('input', this.debounce((e) => {
                this.filterEvents(e.target.value.toLowerCase());
            }, 300));
        }
        
        // Xử lý sự kiện xem đánh giá
        this.viewFeedbackButtons.forEach(button => {
            button.addEventListener('click', (e) => this.showFeedbackModal(e));
        });
    }
    
    onPageLoad() {
        // Kiểm tra URL params để mở tab tương ứng
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        
        if (tabParam) {
            this.activateTab(tabParam);
        }
    }
    
    activateTab(tabId) {
        const tabToActivate = document.getElementById(`${tabId}-tab`);
        if (tabToActivate) {
            // Sử dụng Bootstrap 5 API để kích hoạt tab
            const tab = new bootstrap.Tab(tabToActivate);
            tab.show();
            this.activeTab = tabId;
        }
    }
    
    updateUrlWithTab(tabId) {
        // Cập nhật URL mà không load lại trang
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.replaceState({}, '', url);
    }
    
    toggleEditMode() {
        // Toggle giữa chế độ xem và chỉnh sửa thông tin cá nhân
        this.isEditing = !this.isEditing;
        
        const personalInfoTab = document.getElementById('personal-info');
        if (!personalInfoTab) return;
        
        if (this.isEditing) {
            // Chuyển sang chế độ chỉnh sửa
            this.editProfileBtn.innerHTML = '<i class="fas fa-save me-1"></i> Lưu thông tin';
            this.editProfileBtn.classList.remove('btn-outline-primary');
            this.editProfileBtn.classList.add('btn-primary');
            
            // Chuyển các trường hiển thị thành form inputs
            const readOnlyFields = personalInfoTab.querySelectorAll('.form-control');
            readOnlyFields.forEach(field => {
                const value = field.textContent;
                const fieldId = field.previousElementSibling?.textContent.trim().toLowerCase().replace(/\s+/g, '-');
                field.outerHTML = `<input type="text" class="form-control" id="${fieldId}" value="${value}" />`;
            });
            
            // Thêm nút Hủy
            const cancelBtn = document.createElement('button');
            cancelBtn.className = 'btn btn-outline-secondary btn-sm ms-2';
            cancelBtn.id = 'cancel-edit-btn';
            cancelBtn.innerHTML = '<i class="fas fa-times me-1"></i> Hủy';
            cancelBtn.addEventListener('click', () => this.cancelEdit());
            
            this.editProfileBtn.parentNode.appendChild(cancelBtn);
        } else {
            // Lưu thông tin và chuyển về chế độ xem
            this.saveProfileInfo();
        }
    }
    
    cancelEdit() {
        // Hủy chế độ chỉnh sửa và trở về trạng thái ban đầu
        this.isEditing = false;
        
        // Tải lại trang để reset form
        window.location.reload();
    }
    
    saveProfileInfo() {
        // Hiển thị loading state
        this.editProfileBtn.disabled = true;
        this.editProfileBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Đang lưu...';
        
        // Thu thập dữ liệu từ form
        const formData = {
            name: document.getElementById('họ-và-tên')?.value,
            email: document.getElementById('email')?.value,
            phone: document.getElementById('số-điện-thoại')?.value,
            birthday: document.getElementById('ngày-sinh')?.value,
            class: document.getElementById('lớp')?.value,
            faculty: document.getElementById('khoa')?.value
        };
        
        // Mô phỏng gửi dữ liệu
        setTimeout(() => {
            // Cập nhật UI
            this.editProfileBtn.innerHTML = '<i class="fas fa-edit me-1"></i> Sửa thông tin';
            this.editProfileBtn.classList.add('btn-outline-primary');
            this.editProfileBtn.classList.remove('btn-primary');
            this.editProfileBtn.disabled = false;
            
            // Xóa nút hủy
            const cancelBtn = document.getElementById('cancel-edit-btn');
            if (cancelBtn) cancelBtn.remove();
            
            // Hiển thị thông báo thành công
            this.showToast('Cập nhật thông tin cá nhân thành công!', 'success');
            
            // Tải lại trang sau 1 giây
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }, 1500);
    }
    
    handleCancelEvent(e) {
        e.preventDefault();
        
        const button = e.currentTarget;
        const eventCard = button.closest('.event-card');
        if (!eventCard) return;
        
        const eventTitle = eventCard.querySelector('.card-title')?.textContent;
        
        // Hiển thị hội thoại xác nhận
        if (confirm(`Bạn có chắc chắn muốn hủy đăng ký sự kiện "${eventTitle.trim()}"?`)) {
            this.showCancelReasonModal(eventCard, button);
        }
    }
    
    showCancelReasonModal(eventCard, targetButton) {
        // Kiểm tra và tạo modal nếu chưa tồn tại
        let modalElement = document.getElementById('cancelReasonModal');
        
        if (!modalElement) {
            modalElement = document.createElement('div');
            modalElement.className = 'modal fade';
            modalElement.id = 'cancelReasonModal';
            modalElement.setAttribute('tabindex', '-1');
            modalElement.setAttribute('aria-labelledby', 'cancelReasonModalLabel');
            modalElement.setAttribute('aria-hidden', 'true');
            
            modalElement.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelReasonModalLabel">Lý do hủy đăng ký</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3">
                                    <label for="reason-select" class="form-label">Vui lòng chọn lý do hủy đăng ký:</label>
                                    <select class="form-select" id="reason-select">
                                        <option value="">-- Chọn lý do --</option>
                                        <option value="time_conflict">Trùng lịch</option>
                                        <option value="not_interested">Không còn quan tâm</option>
                                        <option value="cant_attend">Không thể tham gia</option>
                                        <option value="other">Lý do khác</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="other-reason-group" style="display: none;">
                                    <label for="other-reason" class="form-label">Lý do khác:</label>
                                    <textarea class="form-control" id="other-reason" rows="3"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-danger" id="confirm-cancel">Xác nhận hủy</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modalElement);
            
            // Hiển thị/ẩn trường lý do khác khi chọn
            const reasonSelect = document.getElementById('reason-select');
            const otherReasonGroup = document.getElementById('other-reason-group');
            
            reasonSelect.addEventListener('change', () => {
                otherReasonGroup.style.display = reasonSelect.value === 'other' ? 'block' : 'none';
            });
        }
        
        // Khởi tạo Bootstrap modal
        const modal = new bootstrap.Modal(modalElement);
        
        // Xử lý sự kiện khi nhấn nút xác nhận hủy
        const confirmBtn = document.getElementById('confirm-cancel');
        confirmBtn.addEventListener('click', () => {
            const reasonSelect = document.getElementById('reason-select');
            const otherReason = document.getElementById('other-reason');
            
            // Kiểm tra xem đã chọn lý do chưa
            if (!reasonSelect.value) {
                this.showToast('Vui lòng chọn lý do hủy đăng ký!', 'warning');
                return;
            }
            
            // Kiểm tra xem đã nhập lý do khác chưa nếu chọn "Lý do khác"
            if (reasonSelect.value === 'other' && !otherReason.value.trim()) {
                this.showToast('Vui lòng nhập lý do khác!', 'warning');
                return;
            }
            
            // Ẩn modal
            modal.hide();
            
            // Xử lý hủy đăng ký
            this.processCancelEvent(eventCard, targetButton, reasonSelect.value, otherReason.value);
        });
        
        // Hiển thị modal
        modal.show();
    }
    
    processCancelEvent(eventCard, button, reason, otherReason) {
        // Hiển thị trạng thái loading
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Đang hủy...';
        
        // Mô phỏng gửi yêu cầu hủy đăng ký
        setTimeout(() => {
            // Di chuyển card này sang tab "Đã hủy"
            const canceledTab = document.getElementById('canceled-events');
            if (canceledTab) {
                const eventRow = canceledTab.querySelector('.row');
                if (eventRow) {
                    // Thay đổi trạng thái hiển thị của card
                    const badge = eventCard.querySelector('.event-badge');
                    if (badge) {
                        badge.className = 'event-badge canceled';
                        badge.textContent = 'Đã hủy';
                    }
                    
                    // Thay thế nút hủy bằng nút đăng ký lại
                    const footer = eventCard.querySelector('.event-footer');
                    if (footer) {
                        const registerAgainBtn = document.createElement('a');
                        registerAgainBtn.href = '#';
                        registerAgainBtn.className = 'btn btn-success btn-sm register-event-btn';
                        registerAgainBtn.setAttribute('data-bs-toggle', 'tooltip');
                        registerAgainBtn.setAttribute('title', 'Đăng ký lại sự kiện này');
                        registerAgainBtn.textContent = 'Đăng ký lại';
                        registerAgainBtn.addEventListener('click', (e) => this.handleRegisterEvent(e));
                        
                        // Thay thế nút cũ
                        button.replaceWith(registerAgainBtn);
                        this.initTooltips();
                    }
                    
                    // Di chuyển card sang tab mới
                    const colDiv = eventCard.closest('.col-md-4');
                    if (colDiv) {
                        const clone = colDiv.cloneNode(true);
                        colDiv.remove();
                        eventRow.appendChild(clone);
                        
                        // Cập nhật event listeners
                        const registerBtn = clone.querySelector('.register-event-btn');
                        if (registerBtn) {
                            registerBtn.addEventListener('click', (e) => this.handleRegisterEvent(e));
                        }
                    }
                }
            }
            
            // Giảm số lượng trên badge của tab "Đã đăng ký"
            this.updateTabCounter('registered-tab', -1);
            
            // Tăng số lượng trên badge của tab "Đã hủy"
            this.updateTabCounter('canceled-tab', 1);
            
            // Hiển thị thông báo thành công
            this.showToast('Đã hủy đăng ký sự kiện thành công!', 'success');
        }, 1000);
    }
    
    updateTabCounter(tabId, change) {
        const tab = document.getElementById(tabId);
        if (!tab) return;
        
        const badge = tab.querySelector('.badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent);
            if (!isNaN(currentCount)) {
                badge.textContent = currentCount + change;
            }
        }
    }
    
    handleRegisterEvent(e) {
        e.preventDefault();
        
        const button = e.currentTarget;
        const eventCard = button.closest('.event-card');
        if (!eventCard) return;
        
        const eventTitle = eventCard.querySelector('.card-title')?.textContent;
        
        // Hiển thị hội thoại xác nhận
        if (confirm(`Bạn có chắc chắn muốn đăng ký sự kiện "${eventTitle.trim()}"?`)) {
            // Hiển thị trạng thái loading
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Đang đăng ký...';
            
            // Mô phỏng gửi yêu cầu đăng ký
            setTimeout(() => {
                // Di chuyển card này sang tab "Đã đăng ký"
                const registeredTab = document.getElementById('registered-events');
                if (registeredTab) {
                    const eventRow = registeredTab.querySelector('.row');
                    if (eventRow) {
                        // Thay đổi trạng thái hiển thị của card
                        const badge = eventCard.querySelector('.event-badge');
                        if (badge) {
                            badge.className = 'event-badge registered';
                            badge.textContent = 'Đã đăng ký';
                        }
                        
                        // Thay thế nút đăng ký bằng nút hủy
                        const footer = eventCard.querySelector('.event-footer');
                        if (footer) {
                            const cancelBtn = document.createElement('a');
                            cancelBtn.href = '#';
                            cancelBtn.className = 'btn btn-danger btn-sm cancel-event-btn';
                            cancelBtn.setAttribute('data-bs-toggle', 'tooltip');
                            cancelBtn.setAttribute('title', 'Hủy đăng ký sự kiện này');
                            cancelBtn.textContent = 'Hủy đăng ký';
                            cancelBtn.addEventListener('click', (e) => this.handleCancelEvent(e));
                            
                            // Thay thế nút cũ
                            button.replaceWith(cancelBtn);
                            this.initTooltips();
                        }
                        
                        // Di chuyển card sang tab mới
                        const colDiv = eventCard.closest('.col-md-4');
                        if (colDiv) {
                            const clone = colDiv.cloneNode(true);
                            colDiv.remove();
                            eventRow.appendChild(clone);
                            
                            // Cập nhật event listeners
                            const cancelBtn = clone.querySelector('.cancel-event-btn');
                            if (cancelBtn) {
                                cancelBtn.addEventListener('click', (e) => this.handleCancelEvent(e));
                            }
                        }
                    }
                }
                
                // Tăng số lượng trên badge của tab "Đã đăng ký"
                this.updateTabCounter('registered-tab', 1);
                
                // Nếu đang ở tab "Đã hủy", giảm số lượng
                if (this.activeTab === 'canceled-events') {
                    this.updateTabCounter('canceled-tab', -1);
                }
                
                // Hiển thị thông báo thành công
                this.showToast('Đã đăng ký sự kiện thành công!', 'success');
            }, 1000);
        }
    }
    
    filterEvents(searchTerm) {
        // Lọc các sự kiện dựa trên chuỗi tìm kiếm
        const eventsContainer = document.getElementById('available-events');
        if (!eventsContainer) return;
        
        const eventCards = eventsContainer.querySelectorAll('.event-card');
        let hasVisibleCards = false;
        
        eventCards.forEach(card => {
            const title = card.querySelector('.card-title')?.textContent?.toLowerCase() || '';
            const description = card.querySelector('.card-text')?.textContent?.toLowerCase() || '';
            const category = card.querySelector('.event-category')?.textContent?.toLowerCase() || '';
            const date = card.querySelector('.event-date')?.textContent?.toLowerCase() || '';
            const location = card.querySelector('.event-location')?.textContent?.toLowerCase() || '';
            
            const content = `${title} ${description} ${category} ${date} ${location}`;
            
            if (content.includes(searchTerm)) {
                card.closest('.col-md-4').style.display = '';
                hasVisibleCards = true;
            } else {
                card.closest('.col-md-4').style.display = 'none';
            }
        });
        
        // Hiển thị thông báo nếu không có kết quả
        this.toggleEmptyState(eventsContainer, !hasVisibleCards);
    }
    
    toggleEmptyState(container, isEmpty) {
        // Xóa empty state cũ nếu có
        const existingEmptyState = container.querySelector('.empty-search-result');
        if (existingEmptyState) {
            existingEmptyState.remove();
        }
        
        if (isEmpty) {
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-search-result text-center my-4';
            emptyState.innerHTML = `
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="mb-1">Không tìm thấy sự kiện nào phù hợp.</p>
                <button class="btn btn-outline-primary btn-sm mt-2 clear-search">
                    <i class="fas fa-times me-1"></i> Xóa tìm kiếm
                </button>
            `;
            
            container.appendChild(emptyState);
            
            // Thêm event listener cho nút xóa tìm kiếm
            emptyState.querySelector('.clear-search').addEventListener('click', () => {
                if (this.searchEventsInput) {
                    this.searchEventsInput.value = '';
                    this.filterEvents('');
                }
            });
        }
    }
    
    showFeedbackModal(e) {
        e.preventDefault();
        
        const button = e.currentTarget;
        const eventCard = button.closest('.event-card');
        if (!eventCard) return;
        
        const eventTitle = eventCard.querySelector('.card-title')?.textContent;
        
        // Tạo modal hiển thị đánh giá
        let modalElement = document.getElementById('feedbackModal');
        
        if (!modalElement) {
            modalElement = document.createElement('div');
            modalElement.className = 'modal fade';
            modalElement.id = 'feedbackModal';
            modalElement.setAttribute('tabindex', '-1');
            modalElement.setAttribute('aria-labelledby', 'feedbackModalLabel');
            modalElement.setAttribute('aria-hidden', 'true');
            
            modalElement.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="feedbackModalLabel">Đánh giá sự kiện</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6 class="event-title-text"></h6>
                            <div class="my-3">
                                <label class="form-label">Đánh giá của bạn:</label>
                                <div class="rating-display">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <span class="ms-2 fw-bold">4/5</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nhận xét của bạn:</label>
                                <p class="feedback-comment border p-2 rounded">
                                    Sự kiện rất bổ ích, tôi đã học được nhiều kiến thức mới và mở rộng được mạng lưới quan hệ. Tuy nhiên, thời gian hơi dài và phần thảo luận cuối cùng chưa thực sự hiệu quả.
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thời gian đánh giá:</label>
                                <p>20/03/2024 15:30</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" id="edit-feedback-btn">Sửa đánh giá</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modalElement);
            
            // Xử lý nút sửa đánh giá
            document.getElementById('edit-feedback-btn').addEventListener('click', () => {
                this.showEditFeedbackModal(eventTitle);
                
                // Đóng modal hiện tại
                bootstrap.Modal.getInstance(modalElement).hide();
            });
        }
        
        // Cập nhật tiêu đề sự kiện
        modalElement.querySelector('.event-title-text').textContent = eventTitle;
        
        // Hiển thị modal
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
    
    showEditFeedbackModal(eventTitle) {
        // Tạo modal chỉnh sửa đánh giá
        let modalElement = document.getElementById('editFeedbackModal');
        
        if (!modalElement) {
            modalElement = document.createElement('div');
            modalElement.className = 'modal fade';
            modalElement.id = 'editFeedbackModal';
            modalElement.setAttribute('tabindex', '-1');
            modalElement.setAttribute('aria-labelledby', 'editFeedbackModalLabel');
            modalElement.setAttribute('aria-hidden', 'true');
            
            modalElement.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editFeedbackModalLabel">Chỉnh sửa đánh giá</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6 class="event-title-text"></h6>
                            <div class="my-3">
                                <label class="form-label">Đánh giá của bạn:</label>
                                <div class="rating-input">
                                    <i class="star fas fa-star text-warning" data-value="1"></i>
                                    <i class="star fas fa-star text-warning" data-value="2"></i>
                                    <i class="star fas fa-star text-warning" data-value="3"></i>
                                    <i class="star fas fa-star text-warning" data-value="4"></i>
                                    <i class="star far fa-star text-warning" data-value="5"></i>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="feedback-comment" class="form-label">Nhận xét của bạn:</label>
                                <textarea class="form-control" id="feedback-comment" rows="3">Sự kiện rất bổ ích, tôi đã học được nhiều kiến thức mới và mở rộng được mạng lưới quan hệ. Tuy nhiên, thời gian hơi dài và phần thảo luận cuối cùng chưa thực sự hiệu quả.</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" id="save-feedback-btn">Lưu đánh giá</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modalElement);
            
            // Thêm sự kiện cho các ngôi sao đánh giá
            const stars = modalElement.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const value = parseInt(star.getAttribute('data-value'));
                    this.updateRatingStars(stars, value);
                });
                
                star.addEventListener('mouseover', () => {
                    const value = parseInt(star.getAttribute('data-value'));
                    this.previewRatingStars(stars, value);
                });
                
                star.addEventListener('mouseout', () => {
                    this.resetRatingStars(stars);
                });
            });
            
            // Xử lý nút lưu đánh giá
            document.getElementById('save-feedback-btn').addEventListener('click', () => {
                const rating = modalElement.querySelectorAll('.fas.fa-star.text-warning').length;
                const comment = document.getElementById('feedback-comment').value;
                
                if (comment.trim() === '') {
                    this.showToast('Vui lòng nhập nhận xét của bạn!', 'warning');
                    return;
                }
                
                // Đóng modal
                bootstrap.Modal.getInstance(modalElement).hide();
                
                // Hiển thị thông báo thành công
                this.showToast('Đã cập nhật đánh giá thành công!', 'success');
            });
        }
        
        // Cập nhật tiêu đề sự kiện
        modalElement.querySelector('.event-title-text').textContent = eventTitle;
        
        // Hiển thị modal
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
    
    updateRatingStars(stars, value) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            if (starValue <= value) {
                star.className = 'star fas fa-star text-warning';
            } else {
                star.className = 'star far fa-star text-warning';
            }
        });
    }
    
    previewRatingStars(stars, value) {
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            if (starValue <= value) {
                star.className = 'star fas fa-star text-warning';
            } else {
                star.className = 'star far fa-star text-warning';
            }
        });
    }
    
    resetRatingStars(stars) {
        const activeValue = this.getActiveRating(stars);
        this.updateRatingStars(stars, activeValue);
    }
    
    getActiveRating(stars) {
        let lastActive = 0;
        stars.forEach(star => {
            if (star.classList.contains('fas')) {
                lastActive = parseInt(star.getAttribute('data-value'));
            }
        });
        return lastActive;
    }
    
    showToast(message, type = 'success') {
        if (window.userInterface) {
            window.userInterface.showNotification(message, type);
        } else {
            alert(message);
        }
    }
    
    debounce(func, delay) {
        let debounceTimer;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        };
    }
}

// Khởi tạo quản lý hồ sơ khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', () => {
    window.profileManager = new ProfileManager();
});