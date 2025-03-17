/**
 * JavaScript cho alerts component
 */

document.addEventListener('DOMContentLoaded', function() {
  initAlerts();
});

/**
 * Khởi tạo các thông báo
 */
function initAlerts() {
  const alerts = document.querySelectorAll('.alert-dismissible');
  
  alerts.forEach(function(alert) {
    // Tự động ẩn thông báo sau 5 giây
    setTimeout(function() {
      fadeOutAlert(alert);
    }, 5000);
    
    // Xử lý nút đóng
    const closeButton = alert.querySelector('.btn-close');
    if (closeButton) {
      closeButton.addEventListener('click', function() {
        fadeOutAlert(alert);
      });
    }
  });
}

/**
 * Hiệu ứng fade out cho alert
 */
function fadeOutAlert(alert) {
  // Thêm lớp fade-out
  alert.classList.add('fade-out');
  
  // Xóa alert khỏi DOM sau khi hiệu ứng hoàn thành
  setTimeout(function() {
    if (alert.parentNode) {
      alert.parentNode.removeChild(alert);
    }
  }, 500);
}

/**
 * Hiển thị thông báo từ JavaScript
 * @param {string} message - Nội dung thông báo
 * @param {string} type - Loại thông báo (success, info, warning, danger)
 * @param {boolean} dismissible - Có thể đóng hay không
 * @param {number} timeout - Thời gian hiển thị (ms), 0 để không tự động ẩn
 */
function showAlert(message, type = 'info', dismissible = true, timeout = 5000) {
  // Tạo alert mới
  const alert = document.createElement('div');
  alert.className = `alert alert-${type}${dismissible ? ' alert-dismissible fade show' : ''}`;
  alert.role = 'alert';
  
  // Nội dung thông báo
  alert.innerHTML = message;
  
  // Thêm nút đóng nếu dismissible = true
  if (dismissible) {
    alert.innerHTML += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
  }
  
  // Thêm alert vào container
  const alertContainer = document.querySelector('.page-content-wrapper');
  if (alertContainer) {
    alertContainer.insertBefore(alert, alertContainer.firstChild);
    
    // Thiết lập sự kiện click cho nút đóng
    const closeButton = alert.querySelector('.btn-close');
    if (closeButton) {
      closeButton.addEventListener('click', function() {
        fadeOutAlert(alert);
      });
    }
    
    // Tự động ẩn nếu timeout > 0
    if (timeout > 0) {
      setTimeout(function() {
        fadeOutAlert(alert);
      }, timeout);
    }
  }
  
  return alert;
} 