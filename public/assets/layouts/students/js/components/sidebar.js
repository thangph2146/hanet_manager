/**
 * Sidebar Component JS
 * Xử lý việc mở/đóng sidebar trên các kích thước màn hình khác nhau
 */
document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const menuToggle = document.querySelector('.menu-toggle');
    
    // Kiểm tra sự tồn tại của các phần tử trước khi thực hiện
    if (!sidebar || !sidebarOverlay || !menuToggle) {
        console.warn('Không tìm thấy các phần tử cần thiết để xử lý sidebar!');
        return;
    }
    
    // Hàm toggle sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        
        // Thêm hoặc xóa class để hiển thị đúng trên desktop
        if (window.innerWidth >= 768) {
            body.classList.toggle('sidebar-closed');
        }
    }
    
    // Thêm sự kiện click cho nút toggle menu
    menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        toggleSidebar();
    });
    
    // Thêm sự kiện click cho overlay để đóng sidebar
    sidebarOverlay.addEventListener('click', function() {
        toggleSidebar();
    });
    
    // Xử lý khi resize cửa sổ trình duyệt
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Nếu đang ở chế độ mobile (< 768px) và sidebar đang mở
            if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
                // Không làm gì, giữ sidebar mở
            } 
            // Nếu chuyển từ mobile sang desktop (≥ 768px)
            else if (window.innerWidth >= 768) {
                // Hiển thị sidebar mặc định
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                
                // Nếu người dùng đã đóng sidebar trước đó trên desktop
                if (body.classList.contains('sidebar-closed')) {
                    sidebar.classList.add('active');
                }
            }
        }, 250);
    });
    
    // Xử lý đóng sidebar khi click vào liên kết trên mobile
    const menuLinks = sidebar.querySelectorAll('a');
    menuLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });
    });
    
    // Xác định trạng thái ban đầu của sidebar dựa vào kích thước màn hình
    if (window.innerWidth >= 768) {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    } else {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
    }
    
    // Lưu trạng thái sidebar vào localStorage
    function saveSidebarState() {
        if (window.innerWidth >= 768) {
            localStorage.setItem('sidebarClosed', body.classList.contains('sidebar-closed'));
        }
    }
    
    // Khôi phục trạng thái sidebar từ localStorage khi tải trang
    function restoreSidebarState() {
        if (window.innerWidth >= 768) {
            const sidebarClosed = localStorage.getItem('sidebarClosed') === 'true';
            if (sidebarClosed) {
                body.classList.add('sidebar-closed');
                sidebar.classList.add('active');
            } else {
                body.classList.remove('sidebar-closed');
                sidebar.classList.remove('active');
            }
        }
    }
    
    // Thêm sự kiện để lưu trạng thái khi đóng/mở sidebar
    menuToggle.addEventListener('click', saveSidebarState);
    
    // Khôi phục trạng thái khi tải trang
    restoreSidebarState();
}); 