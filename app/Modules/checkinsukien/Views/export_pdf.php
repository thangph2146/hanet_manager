<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16pt;
            margin: 0;
            padding: 10px 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .filters p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4472C4;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        .deleted {
            color: #dc3545;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        .text-center {
            text-align: center;
        }
        .export-info {
            text-align: right;
            margin-bottom: 10px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $title ?></h1>
        <p>Ngày xuất: <?= $date ?></p>
    </div>

    <?php if (!empty($filters)): ?>
    <div class="filters">
        <p><strong>Thông tin bộ lọc:</strong></p>
        <p><?= $filters ?></p>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th class="text-center">STT</th>
                <th class="text-center">ID</th>
                <th>Tên sự kiện</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th class="text-center">Thời gian check-in</th>
                <th class="text-center">Loại check-in</th>
                <th class="text-center">Hình thức tham gia</th>
                <th class="text-center">Điểm khớp khuôn mặt</th>
                <th class="text-center">Xác thực khuôn mặt</th>
                <th class="text-center">Trạng thái</th>
                <th>Địa chỉ IP</th>
                <th>Thông tin thiết bị</th>
                <th class="text-center">Ngày tạo</th>
                <th class="text-center">Ngày cập nhật</th>
                <?php if ($includeDeletedAt): ?>
                <th class="text-center">Ngày xóa</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $item): ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td class="text-center"><?= $item->getId() ?></td>
                <td><?= $item->getTenSuKien() ?? '' ?></td>
                <td><?= $item->getHoTen() ?></td>
                <td><?= $item->getEmail() ?></td>
                <td class="text-center"><?= $item->getThoiGianCheckInFormatted() ?></td>
                <td class="text-center"><?= $item->getCheckinTypeText() ?></td>
                <td class="text-center"><?= $item->getHinhThucThamGiaText() ?></td>
                <td class="text-center"><?= $item->getFaceMatchScorePercent() ?></td>
                <td class="text-center">
                    <?php if ($item->isFaceVerified()): ?>
                        <span class="status-active">Có</span>
                    <?php else: ?>
                        <span class="status-inactive">Không</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php if ($item->getStatus() == 1): ?>
                        <span class="status-active"><?= $item->getStatusText() ?></span>
                    <?php else: ?>
                        <span class="status-inactive"><?= $item->getStatusText() ?></span>
                    <?php endif; ?>
                </td>
                <td><?= $item->getIpAddress() ?></td>
                <td><?= $item->getFormattedDeviceInfo() ?></td>
                <td class="text-center"><?= $item->getCreatedAtFormatted() ?></td>
                <td class="text-center"><?= $item->getUpdatedAtFormatted() ?></td>
                <?php if ($includeDeletedAt): ?>
                <td class="text-center deleted">
                    <?= $item->getDeletedAtFormatted() ?>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Tổng số bản ghi: <?= $total_records ?></p>
    </div>
</body>
</html> 