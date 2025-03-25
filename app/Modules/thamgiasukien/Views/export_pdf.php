<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tham gia sự kiện</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        .export-info {
            font-size: 10px;
            margin-bottom: 20px;
            font-style: italic;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: normal;
            text-align: center;
        }
        .badge-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .badge-danger {
            background-color: #f2dede;
            color: #a94442;
        }
        .badge-info {
            background-color: #d9edf7;
            color: #31708f;
        }
        .badge-primary {
            background-color: #d9eaf7;
            color: #0275d8;
        }
        .badge-secondary {
            background-color: #e6e6e6;
            color: #555;
        }
        .text-center {
            text-align: center;
        }
        .page-number {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title"><?= $title ?? 'Danh sách tham gia sự kiện' ?></div>
            <div class="subtitle">Hệ thống quản lý sự kiện</div>
        </div>
        
        <div class="export-info">
            Xuất dữ liệu vào lúc: <?= $date ?? date('d/m/Y H:i:s') ?>
            <?php if (!empty($filters)): ?>
                <br>Bộ lọc: <?= $filters ?>
            <?php endif; ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">STT</th>
                    <th style="width: 60px;">ID</th>
                    <th>Người dùng</th>
                    <th>Sự kiện</th>
                    <th>Thời gian điểm danh</th>
                    <th>Phương thức</th>
                    <th>Ghi chú</th>
                    <th style="width: 60px;">Trạng thái</th>
                    <?php if (isset($deleted) && $deleted): ?>
                    <th>Ngày xóa</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($thamGiaSuKiens) && count($thamGiaSuKiens) > 0): ?>
                    <?php foreach ($thamGiaSuKiens as $index => $item): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td class="text-center"><?= $item->tham_gia_su_kien_id ?></td>
                            <td><?= $item->nguoi_dung_id ?></td>
                            <td><?= $item->su_kien_id ?></td>
                            <td><?= !empty($item->thoi_gian_diem_danh) ? date('d/m/Y H:i:s', strtotime($item->thoi_gian_diem_danh)) : 'Chưa điểm danh' ?></td>
                            <td class="text-center">
                                <?php if ($item->phuong_thuc_diem_danh == 'qr_code'): ?>
                                    <span class="badge badge-info">QR Code</span>
                                <?php elseif ($item->phuong_thuc_diem_danh == 'face_id'): ?>
                                    <span class="badge badge-primary">Face ID</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Thủ công</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item->ghi_chu ?? '-' ?></td>
                            <td class="text-center">
                                <?php if ($item->status == 1): ?>
                                    <span class="badge badge-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Không hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <?php if (isset($deleted) && $deleted): ?>
                            <td><?= !empty($item->deleted_at) ? date('d/m/Y H:i:s', strtotime($item->deleted_at)) : '-' ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= (isset($deleted) && $deleted) ? 9 : 8 ?>" class="text-center">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            Tài liệu được tạo tự động từ hệ thống quản lý tham gia sự kiện
        </div>
        
        <div class="page-number">
            Trang <span class="pagenum"></span>
        </div>
    </div>
</body>
</html> 