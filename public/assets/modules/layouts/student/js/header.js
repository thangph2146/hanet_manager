/**
 * JavaScript cho header component
 */

document.addEventListener('DOMContentLoaded', function() {
  initSearchHeader();
  initNotifications();
  initUserDropdown();
});

/**
 * Khởi tạo chức năng tìm kiếm trên header
 */
function initSearchHeader() {
  const searchBar = document.querySelector('.searchbar');
  const searchInput = document.querySelector('.searchbar .form-control');
  const searchIcon = document.querySelector('.search-icon');
  const searchCloseIcon = document.querySelector('.search-close-icon');
  
  if (searchBar && searchInput && searchIcon && searchCloseIcon) {
    // Hiển thị icon close khi có nội dung
    searchInput.addEventListener('input', function() {
      if (this.value.length > 0) {
        searchCloseIcon.style.display = 'block';
      } else {
        searchCloseIcon.style.display = 'none';
      }
    });
    
    // Xóa nội dung khi nhấn icon close
    searchCloseIcon.addEventListener('click', function() {
      searchInput.value = '';
      searchCloseIcon.style.display = 'none';
      searchInput.focus();
    });
    
    // Focus vào input khi nhấn vào icon tìm kiếm
    searchIcon.addEventListener('click', function() {
      searchInput.focus();
    });
    
    // Xử lý khi nhấn Enter
    searchInput.addEventListener('keyup', function(e) {
      if (e.key === 'Enter') {
        if (this.value.trim() !== '') {
          // Chuyển hướng đến trang tìm kiếm với tham số search
          window.location.href = 'students/events?search=' + encodeURIComponent(this.value.trim());
        }
      }
    });
  }
}

/**
 * Khởi tạo chức năng thông báo
 */
function initNotifications() {
  const notificationToggle = document.querySelector('.notifications');
  const notificationBadge = document.querySelector('.notify-badge');
  const notificationList = document.querySelector('.notification-list');
  
  if (notificationToggle && notificationBadge) {
    // Lấy số lượng thông báo từ server
    fetchNotificationCount();
    
    // Đánh dấu thông báo đã đọc khi nhấn vào một thông báo
    if (notificationList) {
      const notificationItems = notificationList.querySelectorAll('.dropdown-item');
      
      notificationItems.forEach(function(item) {
        item.addEventListener('click', function() {
          markNotificationAsRead(this.dataset.id);
        });
      });
    }
    
    // Đánh dấu tất cả thông báo đã đọc
    const markAllAsReadBtn = document.querySelector('.mark-all-read');
    if (markAllAsReadBtn) {
      markAllAsReadBtn.addEventListener('click', function(e) {
        e.preventDefault();
        markAllNotificationsAsRead();
      });
    }
  }
}

/**
 * Lấy số lượng thông báo từ server
 */
function fetchNotificationCount() {
  fetch('students/notifications/count')
    .then(response => response.json())
    .then(data => {
      const badge = document.querySelector('.notify-badge');
      if (badge) {
        if (data.count > 0) {
          badge.textContent = data.count;
          badge.style.display = 'flex';
        } else {
          badge.style.display = 'none';
        }
      }
    })
    .catch(error => console.error('Error fetching notification count:', error));
}

/**
 * Đánh dấu một thông báo đã đọc
 */
function markNotificationAsRead(id) {
  fetch('students/notifications/mark-as-read/' + id, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Cập nhật lại số lượng thông báo
        fetchNotificationCount();
      }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

/**
 * Đánh dấu tất cả thông báo đã đọc
 */
function markAllNotificationsAsRead() {
  fetch('students/notifications/mark-all-as-read', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Cập nhật lại số lượng thông báo
        const badge = document.querySelector('.notify-badge');
        if (badge) {
          badge.style.display = 'none';
        }
        
        // Cập nhật style của các thông báo trong list
        const notificationItems = document.querySelectorAll('.notification-list .dropdown-item');
        notificationItems.forEach(function(item) {
          item.classList.add('read');
        });
      }
    })
    .catch(error => console.error('Error marking all notifications as read:', error));
}

/**
 * Khởi tạo user dropdown
 */
function initUserDropdown() {
  const userDropdown = document.querySelector('.user-box .dropdown-toggle');
  const dropdownMenu = document.querySelector('.user-box .dropdown-menu');
  
  if (userDropdown && dropdownMenu) {
    userDropdown.addEventListener('click', function(e) {
      e.preventDefault();
      dropdownMenu.classList.toggle('show');
    });
    
    // Đóng dropdown khi click ngoài
    document.addEventListener('click', function(e) {
      if (!userDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove('show');
      }
    });
  }
}
