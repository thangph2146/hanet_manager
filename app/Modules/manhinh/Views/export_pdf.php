<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            padding: 0;
        }
        .date {
            text-align: right;
            margin-bottom: 20px;
            font-style: italic;
        }
        .filters {
            margin-bottom: 15px;
            font-style: italic;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }
        td {
            padding: 8px;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-style: italic;
            font-size: 10px;
        }
        .status-active {
            color: green;
            font-weight: bold;
        }
        .status-inactive {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= $title ?></h1>
        </div>
        
        <div class="date">
            Ngày xuất: <?= $date ?>
        </div>
        
        <?php if (isset($filters) && !empty($filters)): ?>
        <div class="filters">
            <strong>Bộ lọc:</strong> <?= implode(', ', $filters) ?>
        </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="15%">Mã màn hình</th>
                    <th width="25%">Tên màn hình</th>
                    <th width="20%">Camera</th>
                    <th width="20%">Template</th>
                    <th width="15%">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($manhinhs)): ?>
                    <?php $i = 1; foreach ($manhinhs as $item): ?>
                        <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td><?= esc($item->ma_man_hinh) ?></td>
                            <td><?= esc($item->ten_man_hinh) ?></td>
                            <td><?= esc($item->ten_camera ?? 'Chưa gắn camera') ?></td>
                            <td><?= esc($item->ten_template ?? 'Chưa gắn template') ?></td>
                            <td class="text-center">
                                <?php if ($item->status == 1): ?>
                                    <span class="status-active">Hoạt động</span>
                                <?php else: ?>
                                    <span class="status-inactive">Không hoạt động</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            Tài liệu này được xuất tự động từ hệ thống quản lý màn hình - <?= date('Y') ?>
        </div>
    </div>
</body>
</html> 