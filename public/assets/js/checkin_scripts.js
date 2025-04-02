/**
 * JavaScript cho màn hình check-in sự kiện
 */

// Biến toàn cục
let socket;
let reconnectTimer;
let eventId = '';

document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo đồng hồ thời gian thực
    updateClock();
    // Cập nhật đồng hồ mỗi giây
    setInterval(updateClock, 1000);
    
    // Thêm animation khi tải trang
    animateElements();
    
    // Khởi tạo WebSocket
    initWebSocket();
});

/**
 * Cập nhật đồng hồ thời gian thực
 */
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const clockElement = document.querySelector('.checkin-time');
    if (clockElement) {
        clockElement.innerHTML = `<i class="far fa-clock"></i> ${hours}:${minutes}:${seconds}`;
    }
}

/**
 * Thêm animation cho các phần tử
 */
function animateElements() {
    const elements = document.querySelectorAll('.animate');
    elements.forEach((el, index) => {
        // Thêm độ trễ tăng dần cho mỗi phần tử
        setTimeout(() => {
            el.classList.add('show');
        }, 200 * index);
    });
}

/**
 * Thay đổi loại background
 * @param {number} type Loại background (1-4)
 */
function changeBackground(type) {
    const container = document.querySelector('.checkin-container');
    if (container) {
        // Xóa tất cả các lớp background hiện tại
        container.classList.remove('bg-type-1', 'bg-type-2', 'bg-type-3', 'bg-type-4');
        // Thêm lớp background mới
        container.classList.add(`bg-type-${type}`);
    }
}

// Khởi tạo WebSocket
function initWebSocket() {
    // Lấy eventId từ URL nếu có
    const urlParams = new URLSearchParams(window.location.search);
    eventId = urlParams.get('eventId') || '0';
    
    // Kết nối WebSocket
    connectWebSocket();
    
    // Thêm sự kiện khi đóng trang
    window.addEventListener('beforeunload', function() {
        if (socket && socket.readyState === WebSocket.OPEN) {
            socket.close();
        }
    });
}

// Kết nối đến WebSocket Server
function connectWebSocket() {
    // WebSocket URL
    const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = `${wsProtocol}//${window.location.host}/ws-checkin`;
    
    try {
        socket = new WebSocket('ws://127.0.0.1:8080');
        
        socket.onopen = function() {
            console.log('Kết nối WebSocket thành công');
            
            // Đăng ký lắng nghe sự kiện
            if (eventId) {
                const registerMsg = {
                    type: 'register',
                    eventId: eventId
                };
                socket.send(JSON.stringify(registerMsg));
            }
            
            // Hiển thị trạng thái kết nối
            const statusEl = document.querySelector('.connection-status');
            if (statusEl) {
                statusEl.textContent = 'Đã kết nối';
                statusEl.classList.add('connected');
                statusEl.classList.remove('disconnected');
                statusEl.style.display = 'block';
                
                // Ẩn sau 3 giây
                setTimeout(() => {
                    statusEl.style.display = 'none';
                }, 3000);
            }
            
            // Xóa timer reconnect nếu có
            if (reconnectTimer) {
                clearTimeout(reconnectTimer);
                reconnectTimer = null;
            }
        };
        
        socket.onmessage = function(event) {
            try {
                const data = JSON.parse(event.data);
                console.log('Nhận dữ liệu từ server:', data);
                
                // Xử lý dữ liệu check-in
                if (data.type === 'checkin') {
                    updateCheckinDisplay(data);
                }
            } catch (error) {
                console.error('Lỗi khi phân tích dữ liệu:', error);
            }
        };
        
        socket.onclose = function() {
            console.log('Kết nối WebSocket đã đóng');
            
            // Hiển thị trạng thái mất kết nối
            const statusEl = document.querySelector('.connection-status');
            if (statusEl) {
                statusEl.textContent = 'Mất kết nối';
                statusEl.classList.add('disconnected');
                statusEl.classList.remove('connected');
                statusEl.style.display = 'block';
            }
            
            // Thử kết nối lại sau 5 giây
            reconnectTimer = setTimeout(connectWebSocket, 5000);
        };
        
        socket.onerror = function(error) {
            console.error('Lỗi WebSocket:', error);
        };
    } catch (error) {
        console.error('Lỗi khi khởi tạo WebSocket:', error);
        
        // Thử kết nối lại sau 5 giây
        reconnectTimer = setTimeout(connectWebSocket, 5000);
    }
}

// Cập nhật hiển thị thông tin người check-in
function updateCheckinDisplay(data) {
    // Thêm hiệu ứng khi có người check-in mới
    const contentEl = document.querySelector('.content');
    if (contentEl) {
        contentEl.classList.add('new-checkin');
        setTimeout(() => {
            contentEl.classList.remove('new-checkin');
        }, 2000);
    }
    
    // Cập nhật thông tin cá nhân
    updateElement('.title', data.title || 'PGS.TS');
    updateElement('.person-info h1', data.personName);
    
    // Cập nhật avatar
    const avatarImg = document.querySelector('.avatar-img');
    if (avatarImg) {
        if (data.avatar && data.avatar.startsWith('http')) {
            // Nếu là URL đầy đủ
            avatarImg.src = data.avatar;
        } else {
            // Nếu là tên file
            avatarImg.src = `/uploads/avatars/${data.avatar}`;
        }
        avatarImg.onerror = function() {
            this.src = '/assets/images/default-avatar.jpg';
        };
    }
    
    // Cập nhật thời gian check-in
    const checkinTime = new Date(parseInt(data.checkinTime));
    const timeString = `${String(checkinTime.getHours()).padStart(2, '0')}:${String(checkinTime.getMinutes()).padStart(2, '0')}:${String(checkinTime.getSeconds()).padStart(2, '0')}`;
    updateElement('.checkin-time', `<i class="far fa-clock"></i> ${timeString}`);
    
    // Cập nhật địa điểm và text chào mừng
    updateElement('.venue-info', `<i class="fas fa-map-marker-alt"></i> ${data.place}`);
    updateElement('.welcome-text', data.text1 || 'Chao mung den voi su kien');
    updateElement('.welcome-text-en', data.text2 || 'Welcome');
    
    // Khởi động lại animation cho các phần tử
    restartAnimations();
}

// Cập nhật nội dung của phần tử
function updateElement(selector, content) {
    const element = document.querySelector(selector);
    if (element) {
        if (content.includes('<')) {
            element.innerHTML = content;
        } else {
            element.textContent = content;
        }
    }
}

// Khởi động lại animation cho các phần tử
function restartAnimations() {
    const animatedElements = document.querySelectorAll('.animate');
    animatedElements.forEach((el, index) => {
        el.classList.remove('animate');
        void el.offsetWidth; // Trigger reflow
        el.classList.add('animate');
    });
} 