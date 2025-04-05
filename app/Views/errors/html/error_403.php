<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Truy cập bị từ chối</title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .error-container {
            max-width: 800px;
            margin: 100px auto;
            text-align: center;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #f44336;
            margin-bottom: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 30px;
            margin-bottom: 20px;
            color: #333;
        }
        .error-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        .error-icon {
            font-size: 100px;
            color: #f44336;
            margin-bottom: 20px;
        }
        .btn-return {
            padding: 10px 30px;
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div>
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Truy cập bị từ chối</h2>
        <p class="error-message">
            Rất tiếc, bạn không có quyền truy cập vào trang này. 
            Hệ thống đã ghi nhận thông tin này.
        </p>
        <div class="error-actions">
            <p>Vui lòng thử một trong các tùy chọn sau:</p>
            <ul class="list-unstyled">
                <li>• Quay lại trang trước đó</li>
                <li>• Kiểm tra quyền truy cập của bạn</li>
                <li>• Liên hệ với quản trị viên hệ thống nếu bạn cho rằng đây là sự cố</li>
            </ul>
            <a href="<?= site_url('dashboard') ?>" class="btn btn-primary btn-return">
                <i class="fas fa-home me-2"></i> Quay lại trang chủ
            </a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html> 