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
                <th>ID</th>
                <th>Tên diễn giả</th>
                <th>Chức danh</th>
                <th>Tổ chức</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Website</th>
                <th>Chuyên môn</th>
                <th>Thành tựu</th>
                <th>Mạng xã hội</th>
                <th>Số sự kiện</th>
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
                <td><?= $item->getTenDienGia() ?></td>
                <td><?= $item->getChucDanh() ?></td>
                <td><?= $item->getToChuc() ?></td>
                <td><?= $item->getEmail() ?></td>
                <td><?= $item->getDienThoai() ?></td>
                <td><?= $item->getWebsite() ?></td>
                <td><?= $item->getChuyenMon() ?></td>
                <td><?= $item->getThanhTuu() ?></td>
                <td>
                    <?php if (is_array($item->getMangXaHoi()) && !empty($item->getMangXaHoi())): ?>
                        <?php foreach ($item->getMangXaHoi() as $platform => $url): ?>
                            <div><strong><?= ucfirst($platform) ?>:</strong> <?= $url ?></div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?= is_array($item->getMangXaHoi()) ? '' : $item->getMangXaHoi() ?>
                    <?php endif; ?>
                </td>
                <td class="text-center"><?= $item->getSoSuKienThamGia() ?></td>
                <td class="text-center">
                    <?php if ($item->getStatus()): ?>
                        <span class="status-active">Hoạt động</span>
                    <?php else: ?>
                        <span class="status-inactive">Không hoạt động</span>
                    <?php endif; ?>
                </td>
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