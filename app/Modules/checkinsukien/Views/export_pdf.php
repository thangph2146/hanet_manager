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
        .status-processing {
            color: #ffc107;
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
                <th>Sự kiện</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Thời gian check-in</th>
                <th>Loại check-in</th>
                <th>Hình thức tham gia</th>
                <th>Mã xác nhận</th>
                <th>Ghi chú</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <?php if ($includeDeletedAt): ?>
                <th>Ngày xóa</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $item): ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td class="text-center"><?= $item->getId() ?></td>
                <td>
                    <?php 
                    $suKien = $item->getSuKien();
                    echo $suKien ? esc($suKien->getTenSuKien()) : 'Không có thông tin';
                    ?>
                </td>
                <td><?= esc($item->getEmail()) ?></td>
                <td><?= esc($item->getHoTen()) ?></td>
                <td class="text-center"><?= $item->getThoiGianCheckInFormatted() ?></td>
                <td class="text-center"><?= $item->getCheckinTypeText() ?></td>
                <td class="text-center"><?= $item->getHinhThucThamGiaText() ?></td>
                <td class="text-center"><?= esc($item->getMaXacNhan()) ?></td>
                <td><?= esc($item->getGhiChu()) ?></td>
                <td class="text-center">
                    <?php 
                    $status = $item->getStatus();
                    if ($status == 1): ?>
                        <span class="status-active"><?= $item->getStatusText() ?></span>
                    <?php elseif ($status == 2): ?>
                        <span class="status-processing"><?= $item->getStatusText() ?></span>
                    <?php else: ?>
                        <span class="status-inactive"><?= $item->getStatusText() ?></span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?php 
                    $createdAt = $item->getCreatedAt();
                    echo $createdAt ? $createdAt->format('d/m/Y H:i') : '';
                    ?>
                </td>
                <td class="text-center">
                    <?php 
                    $updatedAt = $item->getUpdatedAt();
                    echo $updatedAt ? $updatedAt->format('d/m/Y H:i') : '';
                    ?>
                </td>
                <?php if ($includeDeletedAt): ?>
                <td class="text-center deleted">
                    <?php 
                    $deletedAt = $item->getDeletedAt();
                    echo $deletedAt ? $deletedAt->format('d/m/Y H:i') : '';
                    ?>
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