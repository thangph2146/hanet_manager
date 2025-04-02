<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
        }
        h1 {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 10px;
        }
        .export-date {
            text-align: right;
            font-style: italic;
            margin-bottom: 10px;
        }
        .filters {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .filters-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .filter-item {
            margin-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 9pt;
        }
        th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            padding: 10px 0;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <h1><?= $title ?></h1>
    
    <div class="export-date">
        Ngày xuất: <?= $export_date ?>
    </div>
    
    <?php if (!empty($filters)): ?>
    <div class="filters">
        <div class="filters-title">Thông tin bộ lọc:</div>
        <?php foreach ($filters as $key => $value): ?>
        <div class="filter-item">
            <strong><?= $key ?>:</strong> <?= $value ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Sự kiện</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Thời gian check-in</th>
                <th>Loại check-in</th>
                <th>Hình thức</th>
                <th>Trạng thái</th>
                <th>Xác minh KM</th>
                <th>Điểm số KM</th>
                <?php if (isset($deleted) && $deleted): ?>
                <th>Ngày xóa</th>
                <?php endif; ?>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $item): ?>
            <tr>
                <td class="text-center"><?= $item->getId() ?></td>
                <td><?= $item->getTenSuKien() ?></td>
                <td><?= $item->getHoTen() ?></td>
                <td><?= $item->getEmail() ?></td>
                <td class="text-center"><?= $item->getThoiGianCheckInFormatted() ?></td>
                <td><?= $item->getCheckinTypeLabel() ?></td>
                <td><?= $item->getHinhThucThamGiaLabel() ?></td>
                <td><?= $item->getStatusLabel() ?></td>
                <td class="text-center"><?= $item->isFaceVerified() ? 'Đã xác minh' : 'Chưa xác minh' ?></td>
                <td class="text-center"><?= $item->getFaceMatchScore() ? number_format($item->getFaceMatchScore() * 100, 2) . '%' : '' ?></td>
                <?php if (isset($deleted) && $deleted): ?>
                <td class="text-center"><?= $item->getDeletedAtFormatted() ?></td>
                <?php endif; ?>
                <td><?= $item->getGhiChu() ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="text-right">
        <strong>Tổng số bản ghi: <?= count($data) ?></strong>
    </div>
    
    <div class="footer">
        Trang {PAGENO}/{nbpg}
    </div>
</body>
</html> 