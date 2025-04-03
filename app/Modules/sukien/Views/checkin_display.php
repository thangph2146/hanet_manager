<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Display - <?= isset($text2) ? esc($text2) : 'Sự kiện' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a;
            color: white;
        }
        
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            z-index: -1;
            transition: background-image 1s ease-in-out;
        }
        
        .check-in-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        .info-box {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            width: 80%;
            max-width: 800px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            animation: fade-in 1s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .profile {
            position: relative;
            margin-bottom: 20px;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 75px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .title {
            background-color: rgba(0, 150, 136, 0.8);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 16px;
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }
        
        .person-name {
            font-size: 48px;
            margin-top: 20px;
            margin-bottom: 20px;
            font-weight: bold;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }
        
        .welcome-text {
            font-size: 24px;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .event-name {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #00BCD4;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }
        
        .time-location {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .time, .location {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .success-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            width: 60px;
            height: 60px;
            border-radius: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: pulse 2s infinite;
        }
        
        .success-icon i {
            font-size: 30px;
            color: white;
        }
        
        @keyframes fade-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .bg-1 { background-image: url('/assets/images/checkin-bg-1.jpg'); }
        .bg-2 { background-image: url('/assets/images/checkin-bg-2.jpg'); }
        .bg-3 { background-image: url('/assets/images/checkin-bg-3.jpg'); }
        .bg-4 { background-image: url('/assets/images/checkin-bg-4.jpg'); }
        
        #websocket-status {
            position: fixed;
            top: 10px;
            left: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
        }
        
        .waiting-message {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .debug-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #00ff00;
            font-family: monospace;
            font-size: 12px;
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        
        .debug-panel.active {
            display: block;
        }
    </style>
</head>
<body>
    <div id="websocket-status">WebSocket: Connecting...</div>
    <div class="background bg-<?= isset($bgType) ? esc($bgType) : '1' ?>"></div>
    
    <div class="check-in-container">
        <div class="info-box">
            <div class="success-icon">
                <i class="bx bx-check"></i>
            </div>
            
            <div class="profile">
                <?php if(isset($avatar) && !empty($avatar)): ?>
                <img src="<?= esc($avatar) ?>" class="profile-image" alt="Profile Image" onerror="this.src='/assets/images/default-avatar.jpg'">
                <?php else: ?>
                <img src="/assets/images/default-avatar.jpg" class="profile-image" alt="Default Profile">
                <?php endif; ?>
                
                <?php if (isset($title) && !empty($title)): ?>
                <div class="title"><?= esc($title) ?></div>
                <?php endif; ?>
            </div>
            
            <?php if(isset($personName) && !empty($personName)): ?>
            <div class="person-name"><?= esc($personName) ?></div>
            <?php else: ?>
            <div class="person-name waiting-for-checkin">Đang chờ check-in...</div>
            <?php endif; ?>
            
            <div class="welcome-text"><?= isset($text1) ? esc($text1) : 'Chào mừng đến với sự kiện' ?></div>
            <div class="event-name"><?= isset($text2) ? esc($text2) : 'Welcome to the event' ?></div>
            
            <div class="time-location">
                <div class="time">
                    <i class="bx bx-time"></i>
                    <span id="current-time"><?= isset($checkinTime) ? date('H:i:s', intval($checkinTime/1000)) : date('H:i:s') ?></span>
                </div>
                <div class="location">
                    <i class="bx bx-map"></i>
                    <span><?= isset($place) ? esc($place) : 'Chưa xác định' ?></span>
                </div>
            </div>
            
            <?php if(!isset($personName) || empty($personName)): ?>
            <div class="waiting-message mt-4">
                <p>Vui lòng nhìn vào camera để check-in</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div id="debug-panel" class="debug-panel"></div>
    
    <script>
        // Hiển thị thời gian hiện tại
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }
        
        setInterval(updateTime, 1000);
        updateTime();
        
        // Debug functions
        const debugMode = <?= isset($_GET['debug']) && $_GET['debug'] === '1' ? 'true' : 'false' ?>;
        const debugPanel = document.getElementById('debug-panel');
        
        function debugLog(message) {
            if (!debugMode) return;
            
            console.log(message);
            const time = new Date().toLocaleTimeString();
            const logMessage = document.createElement('div');
            logMessage.innerHTML = `<span style="color:#aaa;">[${time}]</span> ${message}`;
            debugPanel.appendChild(logMessage);
            debugPanel.scrollTop = debugPanel.scrollHeight;
            
            if (!debugPanel.classList.contains('active')) {
                debugPanel.classList.add('active');
            }
        }
        
        // Activate debug mode if URL param is set
        if (debugMode) {
            debugPanel.classList.add('active');
            debugLog('Debug mode activated');
        }
        
        // WebSocket để nhận dữ liệu check-in trực tiếp
        const eventId = '<?= isset($eventId) ? esc($eventId) : '' ?>';
        const wsUrl = '<?= isset($websocketUrl) ? esc($websocketUrl) : 'ws://localhost:8080' ?>';
        let ws;
        let reconnectAttempts = 0;
        const maxReconnectAttempts = 10;
        
        function connectWebSocket() {
            if (!eventId) {
                debugLog('Missing eventId parameter');
                document.getElementById('websocket-status').textContent = 'WebSocket: Error - Missing eventId';
                document.getElementById('websocket-status').style.backgroundColor = 'rgba(255, 0, 0, 0.7)';
                return;
            }
            
            debugLog(`Connecting to WebSocket: ${wsUrl}/checkin`);
            
            try {
                // Thay đổi địa chỉ WebSocket theo cấu hình thực tế
                ws = new WebSocket(`${wsUrl}/checkin`);
                
                ws.onopen = function() {
                    debugLog('Connected to WebSocket server');
                    reconnectAttempts = 0;
                    document.getElementById('websocket-status').textContent = 'WebSocket: Connected';
                    document.getElementById('websocket-status').style.backgroundColor = 'rgba(0, 150, 0, 0.5)';
                    
                    // Đăng ký nhận thông báo cho sự kiện này
                    const subscribeMsg = JSON.stringify({
                        type: 'subscribe',
                        eventId: eventId
                    });
                    
                    debugLog(`Subscribing to event: ${eventId}`);
                    ws.send(subscribeMsg);
                };
                
                ws.onmessage = function(event) {
                    try {
                        debugLog(`Received WebSocket message: ${event.data}`);
                        const data = JSON.parse(event.data);
                        
                        // Kiểm tra nếu là thông báo check-in và cùng sự kiện
                        if (data.type === 'checkin_notification' && data.data && data.data.eventId === eventId) {
                            debugLog('Received valid check-in notification, updating display');
                            updateCheckinDisplay(data.data);
                        } else {
                            debugLog(`Ignoring message: type=${data.type}, received eventId=${data.data?.eventId}, expected=${eventId}`);
                        }
                    } catch (error) {
                        debugLog(`Error parsing WebSocket message: ${error.message}`);
                    }
                };
                
                ws.onclose = function(event) {
                    debugLog(`WebSocket disconnected. Code: ${event.code}, Reason: ${event.reason}`);
                    document.getElementById('websocket-status').textContent = 'WebSocket: Disconnected';
                    document.getElementById('websocket-status').style.backgroundColor = 'rgba(150, 0, 0, 0.5)';
                    
                    // Thử kết nối lại nếu chưa vượt quá số lần thử
                    if (reconnectAttempts < maxReconnectAttempts) {
                        reconnectAttempts++;
                        const delay = Math.min(30000, Math.pow(1.5, reconnectAttempts) * 1000);
                        debugLog(`Reconnecting in ${delay/1000} seconds (Attempt ${reconnectAttempts}/${maxReconnectAttempts})`);
                        
                        setTimeout(connectWebSocket, delay);
                    } else {
                        debugLog('Max reconnect attempts reached, giving up');
                        document.getElementById('websocket-status').textContent = 'WebSocket: Failed to connect';
                    }
                };
                
                ws.onerror = function(error) {
                    debugLog(`WebSocket error: ${error.message}`);
                    document.getElementById('websocket-status').textContent = 'WebSocket: Error';
                    document.getElementById('websocket-status').style.backgroundColor = 'rgba(150, 0, 0, 0.5)';
                };
            } catch (error) {
                debugLog(`Exception creating WebSocket: ${error.message}`);
                document.getElementById('websocket-status').textContent = 'WebSocket: Connection failed';
                document.getElementById('websocket-status').style.backgroundColor = 'rgba(150, 0, 0, 0.5)';
            }
        }
        
        // Cập nhật thông tin hiển thị khi có người check-in mới
        function updateCheckinDisplay(data) {
            debugLog('Updating check-in display with data: ' + JSON.stringify(data));
            
            // Cập nhật thông tin người check-in
            document.querySelector('.profile-image').src = data.avatar || '/assets/images/default-avatar.jpg';
            
            // Cập nhật title nếu có
            const titleElement = document.querySelector('.title');
            if (titleElement) {
                if (data.title) {
                    titleElement.textContent = data.title;
                    titleElement.style.display = 'block';
                } else {
                    titleElement.style.display = 'none';
                }
            }
            
            // Cập nhật tên người check-in
            const personNameElement = document.querySelector('.person-name');
            if (personNameElement) {
                personNameElement.textContent = data.personName || 'Guest';
                personNameElement.classList.remove('waiting-for-checkin');
            }
            
            // Cập nhật text1 và text2
            document.querySelector('.welcome-text').textContent = data.text1 || 'Chào mừng đến với sự kiện';
            document.querySelector('.event-name').textContent = data.text2 || 'Welcome to the event';
            
            // Cập nhật địa điểm
            const locationSpan = document.querySelector('.location span');
            if (locationSpan) {
                locationSpan.textContent = data.place || 'Chưa xác định';
            }
            
            // Cập nhật thời gian check-in
            if (data.checkinTime) {
                const checkinDate = new Date(parseInt(data.checkinTime));
                const hours = String(checkinDate.getHours()).padStart(2, '0');
                const minutes = String(checkinDate.getMinutes()).padStart(2, '0');
                const seconds = String(checkinDate.getSeconds()).padStart(2, '0');
                document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
            }
            
            // Ẩn thông báo chờ nếu đang hiển thị
            const waitingMessage = document.querySelector('.waiting-message');
            if (waitingMessage) {
                waitingMessage.style.display = 'none';
            }
            
            // Hiệu ứng fade-in khi có người check-in mới
            const infoBox = document.querySelector('.info-box');
            infoBox.style.opacity = '0';
            
            setTimeout(function() {
                infoBox.style.opacity = '1';
                
                // Hiệu ứng với icon thành công
                const successIcon = document.querySelector('.success-icon');
                successIcon.style.animation = 'none';
                setTimeout(() => {
                    successIcon.style.animation = 'pulse 2s infinite';
                }, 10);
            }, 500);
            
            // Thay đổi background nếu có chỉ định
            if (data.bgType) {
                document.querySelector('.background').className = `background bg-${data.bgType}`;
            }
            
            // Phát âm thanh thông báo (nếu có)
            playNotificationSound();
        }
        
        // Phát âm thanh khi có người check-in
        function playNotificationSound() {
            try {
                const audio = new Audio('/assets/sounds/checkin-sound.mp3');
                audio.play().catch(e => {
                    debugLog(`Failed to play sound: ${e.message}`);
                });
            } catch (e) {
                debugLog(`Error with audio: ${e.message}`);
            }
        }
        
        // Test với dữ liệu mẫu
        function testWithSampleData() {
            const sampleData = {
                type: 'checkin_notification',
                data: {
                    eventId: eventId,
                    personId: 'test123',
                    title: 'Khách mời',
                    personName: 'Nguyễn Văn Test',
                    avatar: '/assets/images/default-avatar.jpg',
                    date: '<?= date('Y-m-d') ?>',
                    placeID: 'TEST',
                    place: 'Demo Location',
                    checkinTime: Date.now(),
                    text1: 'Chào mừng đến với sự kiện',
                    text2: 'Test Checkin System',
                    bgType: '2'
                }
            };
            
            debugLog('Testing with sample data');
            updateCheckinDisplay(sampleData.data);
        }
        
        // Đăng ký các phím tắt để debug
        document.addEventListener('keydown', function(event) {
            // Bấm T để test
            if (event.key === 't' || event.key === 'T') {
                testWithSampleData();
            }
            
            // Bấm D để bật/tắt debug panel
            if (event.key === 'd' || event.key === 'D') {
                debugPanel.classList.toggle('active');
            }
            
            // Bấm R để kết nối lại WebSocket
            if (event.key === 'r' || event.key === 'R') {
                if (ws) {
                    ws.close();
                }
                connectWebSocket();
            }
        });
        
        // Kết nối WebSocket khi trang tải xong
        window.addEventListener('load', function() {
            debugLog('Page loaded, connecting to WebSocket');
            connectWebSocket();
            
            // Thêm hành vi tự động làm mới trang sau một khoảng thời gian
            // (để đảm bảo kết nối WebSocket vẫn hoạt động)
            setTimeout(function() {
                debugLog('Auto-refresh triggered');
                location.reload();
            }, 3600000); // Làm mới sau 1 giờ
        });
    </script>
</body>
</html>