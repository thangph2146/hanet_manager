<?php
/**
 * View hiển thị màn hình check-in cho sự kiện
 * 
 * Các biến được truyền vào view:
 * @var string $title Tiền tố hoặc học vị người tham gia
 * @var string $personName Tên người tham gia
 * @var string $avatar Đường dẫn ảnh đại diện
 * @var string $date Ngày tổ chức sự kiện
 * @var string $placeID ID địa điểm
 * @var string $place Địa điểm tổ chức
 * @var int $checkinTime Thời gian check-in (timestamp)
 * @var string $text1 Dòng text chào mừng 1
 * @var string $text2 Dòng text chào mừng 2
 * @var string $bgType Loại background (1-8)
 * @var string $eventId ID của sự kiện
 */
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Sự Kiện</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/checkin_styles.css') ?>">
    <style>
        /* Thêm hiệu ứng khi có người check-in mới */
        @keyframes newCheckin {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); box-shadow: 0 0 30px rgba(255,255,255,0.8); }
            100% { transform: scale(1); }
        }
        
        .new-checkin {
            animation: newCheckin 1.5s ease-out;
        }
        
        /* Logo HANET */
        .hanet-logo {
            position: absolute;
            bottom: 20px;
            right: 20px;
            max-width: 120px;
            opacity: 0.7;
            z-index: 3;
        }
        
        /* Thêm các background mới */
        .bg-type-5 {
            background: linear-gradient(135deg, #1d2671, #c33764);
        }
        
        .bg-type-6 {
            background: linear-gradient(135deg, #000046, #1CB5E0);
        }
        
        .bg-type-7 {
            background: linear-gradient(135deg, #4b6cb7, #182848);
        }
        
        .bg-type-8 {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        }
        
        /* Thông báo trạng thái kết nối */
        .connection-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            z-index: 10;
            display: none;
        }
        
        .connection-status.connected {
            background-color: rgba(40, 167, 69, 0.7);
            color: white;
        }
        
        .connection-status.disconnected {
            background-color: rgba(220, 53, 69, 0.7);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="connection-status">Kết nối WebSocket</div>
        
        <div class="checkin-container bg-type-<?= $bgType ?>">
            <div class="overlay"></div>
            <div class="content">
                <div class="date-display animate">
                    <i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($date)) ?>
                </div>
                
                <div class="avatar-container animate animate-delay-1">
                    <img src="<?= base_url('uploads/avatars/' . $avatar) ?>" alt="<?= esc($personName) ?>" class="avatar-img" onerror="this.src='<?= base_url('assets/images/default-avatar.jpg') ?>'">
                </div>
                
                <div class="person-info animate animate-delay-1">
                    <div class="title"><?= esc($title) ?></div>
                    <h1><?= esc($personName) ?></h1>
                </div>
                
                <div class="checkin-time animate animate-delay-2">
                    <i class="far fa-clock"></i> <?= date('H:i:s', $checkinTime/1000) ?>
                </div>
                
                <div class="venue-info animate animate-delay-2">
                    <i class="fas fa-map-marker-alt"></i> <?= esc($place) ?>
                </div>
                
                <div class="welcome-text animate animate-delay-3">
                    <?= esc($text1) ?>
                </div>
                
                <div class="welcome-text-en animate animate-delay-3">
                    <?= esc($text2) ?>
                </div>
            </div>
            
            <!-- Logo HANET -->
            <div class="hanet-logo">
                <img src="<?= base_url('assets/images/hanet-logo.png') ?>" alt="HANET" onerror="this.src='<?= base_url('assets/images/default-logo.png') ?>'">
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/checkin_scripts.js') ?>"></script>
    <script>
        // Thiết lập WebSocket cho realtime check-in
        const socketUrl = 'ws://<?= $_SERVER['HTTP_HOST'] ?>/ws-checkin';
        let socket;
        let reconnectTimeout;
        const eventId = '<?= $eventId ?? '0' ?>';
        
        // Kết nối WebSocket
        function connectWebSocket() {
            try {
                socket = new WebSocket(socketUrl);
                
                socket.onopen = function() {
                    console.log('WebSocket Connected');
                    $('.connection-status').text('Đã kết nối').addClass('connected').removeClass('disconnected').fadeIn().delay(2000).fadeOut();
                    
                    // Đăng ký sự kiện cần theo dõi
                    if (eventId) {
                        socket.send(JSON.stringify({
                            type: 'register',
                            eventId: eventId
                        }));
                    }
                    
                    // Xóa timeout nếu đã kết nối thành công
                    if (reconnectTimeout) {
                        clearTimeout(reconnectTimeout);
                    }
                };
                
                socket.onmessage = function(event) {
                    // Nhận dữ liệu check-in mới từ server
                    const data = JSON.parse(event.data);
                    console.log('Received data:', data);
                    
                    if (data.type === 'checkin') {
                        // Cập nhật thông tin người check-in
                        updateCheckinDisplay(data);
                    }
                };
                
                socket.onclose = function() {
                    console.log('WebSocket Disconnected');
                    $('.connection-status').text('Mất kết nối').addClass('disconnected').removeClass('connected').fadeIn();
                    
                    // Thử kết nối lại sau 5 giây
                    reconnectTimeout = setTimeout(function() {
                        connectWebSocket();
                    }, 5000);
                };
                
                socket.onerror = function(error) {
                    console.error('WebSocket Error:', error);
                };
            } catch (e) {
                console.error('WebSocket connection error:', e);
                $('.connection-status').text('Lỗi kết nối').addClass('disconnected').removeClass('connected').fadeIn();
                
                // Thử kết nối lại sau 5 giây
                reconnectTimeout = setTimeout(function() {
                    connectWebSocket();
                }, 5000);
            }
        }
        
        // Cập nhật hiển thị khi có người check-in mới
        function updateCheckinDisplay(data) {
            // Thêm hiệu ứng mới khi có người check-in
            $('.content').addClass('new-checkin');
            setTimeout(function() {
                $('.content').removeClass('new-checkin');
            }, 2000);
            
            // Cập nhật thông tin người dùng
            $('.title').text(data.title || 'PGS.TS');
            $('.person-info h1').text(data.personName);
            
            // Cập nhật avatar
            let avatarUrl = '<?= base_url('uploads/avatars/') ?>' + data.avatar;
            $('.avatar-img').attr('src', avatarUrl);
            
            // Cập nhật thời gian
            const checkinDate = new Date(data.checkinTime);
            const hours = String(checkinDate.getHours()).padStart(2, '0');
            const minutes = String(checkinDate.getMinutes()).padStart(2, '0');
            const seconds = String(checkinDate.getSeconds()).padStart(2, '0');
            $('.checkin-time').html(`<i class="far fa-clock"></i> ${hours}:${minutes}:${seconds}`);
            
            // Cập nhật địa điểm
            $('.venue-info').html(`<i class="fas fa-map-marker-alt"></i> ${data.place}`);
            
            // Cập nhật text chào mừng
            $('.welcome-text').text(data.text1 || 'Chao mung den voi su kien');
            $('.welcome-text-en').text(data.text2 || 'Welcome');
            
            // Khởi tạo lại animation
            $('.animate').each(function() {
                $(this).removeClass('animate').addClass('temp-class');
                setTimeout(() => {
                    $(this).removeClass('temp-class').addClass('animate');
                }, 10);
            });
        }
        
        // Kết nối WebSocket khi trang được tải
        $(document).ready(function() {
            connectWebSocket();
            
            // Thay đổi background theo khoảng thời gian
            let bgIndex = <?= $bgType ?? 1 ?>;
            
            // Chỉ thay đổi background nếu không có hoạt động check-in sau 30 giây
            setInterval(function() {
                bgIndex = bgIndex < 8 ? bgIndex + 1 : 1;
                $('.checkin-container').removeClass(function (index, className) {
                    return (className.match(/(^|\s)bg-type-\S+/g) || []).join(' ');
                }).addClass('bg-type-' + bgIndex);
            }, 30000);
        });
    </script>
</body>
</html> 