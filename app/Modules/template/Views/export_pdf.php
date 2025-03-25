<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .date {
            text-align: right;
            margin-bottom: 20px;
        }
        .filters {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            text-align: center;
            font-style: italic;
            margin-top: 20px;
        }
        .status-active {
            color: green;
        }
        .status-inactive {
            color: red;
        }
        .text-center {
            text-align: center;
        }
        .text-muted {
            color: #6c757d;
        }
        .fst-italic {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?= $title ?></h2>
        </div>
        
        <div class="date">
            Ngày xuất: <?= $date ?>
        </div>
        
        <?php if (!empty($filters)): ?>
        <div class="filters">
            <strong>Bộ lọc:</strong> <?= $filters ?>
        </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="5%">STT</th>
                    <th width="15%">Mã template</th>
                    <th width="40%">Tên template</th>
                    <th width="15%">Trạng thái</th>
                    <th width="15%">Ngày tạo</th>
                    <?php if (isset($templates[0]) && !empty($templates[0]->deleted_at)): ?>
                    <th width="15%">Ngày xóa</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($templates)): ?>
                    <?php foreach ($templates as $index => $template): ?>
                    <tr>
                        <td class="text-center"><?= $index + 1 ?></td>
                        <td><?= $template->ma_template ?: '<span class="text-muted fst-italic">Chưa cập nhật</span>' ?></td>
                        <td><?= $template->ten_template ?></td>
                        <td class="text-center <?= $template->status == 1 ? 'status-active' : 'status-inactive' ?>">
                            <?= $template->status == 1 ? 'Hoạt động' : 'Không hoạt động' ?>
                        </td>
                        <td class="text-center"><?= $template->getCreatedAtFormatted() ?></td>
                        <?php if (!empty($template->deleted_at)): ?>
                        <td class="text-center"><?= $template->getDeletedAtFormatted() ?></td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= isset($templates[0]) && !empty($templates[0]->deleted_at) ? 6 : 5 ?>" class="text-center">
                            Không có dữ liệu
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="footer">
            Tài liệu được tạo tự động từ hệ thống quản lý template
        </div>
    </div>
</body>
</html> 