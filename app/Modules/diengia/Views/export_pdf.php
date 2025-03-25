<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách khuôn mặt người dùng</title>
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
        .img-thumbnail {
            max-width: 60px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DANH SÁCH KHUÔN MẶT NGƯỜI DÙNG</h1>
        </div>
        
        <div class="date">
            Ngày xuất: <?= isset($date) ? $date : date('d/m/Y H:i:s') ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="10%">ID</th>
                    <th width="25%">Người dùng</th>
                    <th width="15%">Hình ảnh</th>
                    <th width="15%">Ngày cập nhật</th>
                    <th width="10%">Trạng thái</th>
                    <?php if (isset($is_deleted) && $is_deleted): ?>
                    <th width="15%">Ngày xóa</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php $i = 1; foreach ($items as $item): ?>
                        <tr>
                            <td class="text-center"><?= $i++ ?></td>
                            <td class="text-center"><?= $item->face_nguoi_dung_id ?></td>
                            <td>
                                <?php if (isset($item->nguoi_dung) && !empty($item->nguoi_dung)): ?>
                                    <strong><?= esc($item->nguoi_dung->ho_ten) ?></strong>
                                    <?php if (!empty($item->nguoi_dung->email)): ?>
                                        <br><span style="font-size: 10px;"><?= esc($item->nguoi_dung->email) ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span>Không có thông tin</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($item->duong_dan_anh)): ?>
                                    <span>[Đã có ảnh]</span>
                                <?php else: ?>
                                    <span>Không có ảnh</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($item->ngay_cap_nhat)): ?>
                                    <?= date('d/m/Y H:i', strtotime($item->ngay_cap_nhat)) ?>
                                <?php else: ?>
                                    <span>Chưa cập nhật</span>
                                <?php endif; ?>
                            </td>
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
                        <td colspan="<?= isset($is_deleted) && $is_deleted ? 7 : 6 ?>" class="text-center">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            Tài liệu này được xuất tự động từ hệ thống quản lý khuôn mặt người dùng - <?= date('Y') ?>
        </div>
    </div>
</body>
</html> 