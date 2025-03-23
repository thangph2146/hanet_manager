<?php
// Kiểm tra xem có dữ liệu khuôn mặt không
if (empty($data)) {
    exit('Không có dữ liệu khuôn mặt người dùng.');
}

// Cài đặt font
$pdf->SetFont('dejavusans', '', 10);

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khuôn mặt người dùng</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 13px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            vertical-align: top;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .badge-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        .img-placeholder {
            width: 60px;
            height: 60px;
            background-color: #efefef;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            line-height: 60px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Danh sách khuôn mặt người dùng</h1>
        <p>Xuất ngày: <?= date('d/m/Y H:i:s') ?></p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Người dùng</th>
                <th width="25%">Đường dẫn ảnh</th>
                <th width="15%">Ngày cập nhật</th>
                <th width="10%">Trạng thái</th>
                <th width="15%">Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)) : ?>
                <tr>
                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                </tr>
            <?php else : ?>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td class="text-center"><?= $item->face_nguoi_dung_id ?></td>
                        <td>
                            <?php if (isset($item->nguoi_dung) && !empty($item->nguoi_dung)) : ?>
                                <?= esc($item->nguoi_dung->ho_ten) ?>
                                <?php if (!empty($item->nguoi_dung->email)) : ?>
                                    <br><span style="font-size: 10px; color: #666;"><?= esc($item->nguoi_dung->email) ?></span>
                                <?php endif; ?>
                            <?php else : ?>
                                Không có thông tin
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($item->duong_dan_anh)) : ?>
                                <?= esc($item->duong_dan_anh) ?>
                            <?php else : ?>
                                Không có
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($item->ngay_cap_nhat)) : ?>
                                <?= date('d/m/Y H:i:s', strtotime($item->ngay_cap_nhat)) ?>
                            <?php else : ?>
                                Chưa cập nhật
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($item->status == 1) : ?>
                                <span class="badge badge-success">Hoạt động</span>
                            <?php else : ?>
                                <span class="badge badge-danger">Không hoạt động</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($item->created_at)) : ?>
                                <?= date('d/m/Y H:i:s', strtotime($item->created_at)) ?>
                            <?php else : ?>
                                Không có
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Tổng số bản ghi: <?= count($items) ?></p>
        <p>Người xuất: <?= session()->get('user_name') ?? 'Hệ thống' ?></p>
    </div>
</body>
</html> 