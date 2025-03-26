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
                <th>Tài khoản</th>
                <th>Họ và tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Loại người dùng</th>
                <th>Mã loại</th>
                <th>Mô tả</th>
                <th>Mật khẩu</th>
                <th>Năm học</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Bậc học</th>
                <th>Mã bậc</th>
                <th>Hệ đào tạo</th>
                <th>Mã hệ</th>
                <th>Ngành</th>
                <th>Mã ngành</th>
                <th>Phòng khoa</th>
                <th>Mã phòng</th>
                <th>Ghi chú</th>
                <th>Trạng thái</th>
                <th>Đăng nhập cuối</th>
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
                <td class="text-center"><?= $item->nguoi_dung_id ?></td>
                <td><?= $item->AccountId ?></td>
                <td><?= $item->getFullName() ?></td>
                <td><?= $item->getEmail() ?></td>
                <td><?= $item->getMobilePhone() ?></td>
                <td><?= $item->getLoaiNguoiDungDisplay() ?></td>
                <td><?= $item->getLoaiNguoiDungId() ?></td>
                <td><?= $item->getLoaiNguoiDung() ? $item->getLoaiNguoiDung()->getMoTa() : '' ?></td>
                <td><?= $item->getField('mat_khau_local') ?></td>
                <td><?= $item->getNamHocDisplay() ?></td>
                <td><?= $item->getNamHoc() ? $item->getNamHoc()->getNgayBatDau() : '' ?></td>
                <td><?= $item->getNamHoc() ? $item->getNamHoc()->getNgayKetThuc() : '' ?></td>
                <td><?= $item->getBacHocDisplay() ?></td>
                <td><?= $item->getBacHoc() ? $item->getBacHoc()->getMaBacHoc() : '' ?></td>
                <td><?= $item->getHeDaoTaoDisplay() ?></td>
                <td><?= $item->getHeDaoTao() ? $item->getHeDaoTao()->getMaHeDaoTao() : '' ?></td>
                <td><?= $item->getNganhDisplay() ?></td>
                <td><?= $item->getNganh() ? $item->getNganh()->getMaNganh() : '' ?></td>
                <td><?= $item->getPhongKhoaDisplay() ?></td>
                <td><?= $item->getPhongKhoa() ? $item->getPhongKhoa()->getMaPhongKhoa() : '' ?></td>
                <td><?= $item->getPhongKhoa() ? $item->getPhongKhoa()->getGhiChu() : '' ?></td>
                <td class="text-center <?= $item->isActive() ? 'status-active' : 'status-inactive' ?>">
                    <?= $item->isActive() ? 'Hoạt động' : 'Không hoạt động' ?>
                </td>
                <td class="text-center"><?= $item->getLastLoginFormatted() ?></td>
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