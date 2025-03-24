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
        
        <table>
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="10%">Mã camera</th>
                    <th width="25%">Tên camera</th>
                    <th width="15%">Địa chỉ IP</th>
                    <th width="10%">Port</th>
                    <th width="15%">Tài khoản</th>
                    <th width="10%">Trạng thái</th>
                    <?php if (isset($is_deleted) && $is_deleted): ?>
                    <th width="10%">Ngày xóa</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($camera)): ?>
                    <?php $i = 1; foreach ($camera as $item): ?>
                        <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td><?= esc($item->ma_camera) ?></td>
                            <td><?= esc($item->ten_camera) ?></td>
                            <td><?= esc($item->ip_camera) ?></td>
                            <td class="text-center"><?= esc($item->port) ?></td>
                            <td><?= esc($item->username) ?></td>
                            <td class="text-center">
                                <?php if ($item->status == 1): ?>
                                    <span class="status-active">Hoạt động</span>
                                <?php else: ?>
                                    <span class="status-inactive">Không hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <?php if (isset($is_deleted) && $is_deleted): ?>
                            <td class="text-center">
                                <?php if (!empty($item->deleted_at)): ?>
                                    <?= date('d/m/Y H:i', strtotime($item->deleted_at)) ?>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= isset($is_deleted) && $is_deleted ? 8 : 7 ?>" class="text-center">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            Tài liệu này được xuất tự động từ hệ thống quản lý camera - <?= date('Y') ?>
        </div>
    </div>
</body>
</html> 