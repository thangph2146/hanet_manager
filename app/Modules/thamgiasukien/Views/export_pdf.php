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
                <th>Người dùng ID</th>
                <th>Sự kiện ID</th>
                <th>Thời gian điểm danh</th>
                <th>Phương thức điểm danh</th>
                <th>Ghi chú</th>
                <th>Trạng thái</th>
                <?php if ($includeDeletedAt): ?>
                <th>Ngày xóa</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $item): ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td class="text-center"><?= $item->tham_gia_su_kien_id ?></td>
                <td class="text-center"><?= $item->nguoi_dung_id ?></td>
                <td class="text-center"><?= $item->su_kien_id ?></td>
                <td><?= !empty($item->thoi_gian_diem_danh) ? 
                    ($item->thoi_gian_diem_danh instanceof \CodeIgniter\I18n\Time ? 
                        $item->thoi_gian_diem_danh->format('d/m/Y H:i:s') : 
                        date('d/m/Y H:i:s', strtotime($item->thoi_gian_diem_danh))) : 
                    'Chưa điểm danh' ?></td>
                <td class="text-center">
                    <?php
                    switch ($item->phuong_thuc_diem_danh) {
                        case 'qr_code':
                            echo 'QR Code';
                            break;
                        case 'face_id':
                            echo 'Face ID';
                            break;
                        default:
                            echo 'Thủ công';
                    }
                    ?>
                </td>
                <td><?= $item->ghi_chu ?? '' ?></td>
                <td class="text-center <?= $item->status == 1 ? 'status-active' : 'status-inactive' ?>">
                    <?= $item->status == 1 ? 'Hoạt động' : 'Không hoạt động' ?>
                </td>
                <?php if ($includeDeletedAt): ?>
                <td class="text-center deleted">
                    <?= !empty($item->deleted_at) ? 
                        ($item->deleted_at instanceof \CodeIgniter\I18n\Time ? 
                            $item->deleted_at->format('d/m/Y H:i:s') : 
                            date('d/m/Y H:i:s', strtotime($item->deleted_at))) : 
                        '' ?>
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