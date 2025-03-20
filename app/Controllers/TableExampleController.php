<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\TableBuilder;

class TableExampleController extends BaseController
{
    /**
     * Hiển thị ví dụ bảng cơ bản
     */
    public function basicExample()
    {
        // Dữ liệu mẫu
        $data = [
            ['Tên', 'Email', 'Điện thoại'],
            ['Nguyễn Văn A', 'nguyenvana@example.com', '0987654321'],
            ['Trần Thị B', 'tranthib@example.com', '0123456789'],
            ['Lê Văn C', 'levanc@example.com', '0369852147']
        ];
        
        // Tạo bảng
        $tableBuilder = new TableBuilder();
        $tableHtml = $tableBuilder->generate($data);
        
        return view('table_examples/layout_example', [
            'title' => 'Bảng cơ bản',
            'table' => $tableHtml
        ]);
    }
    
    /**
     * Hiển thị ví dụ bảng với tiêu đề và footer
     */
    public function headingFootingExample()
    {
        // Dữ liệu mẫu (không bao gồm tiêu đề)
        $data = [
            ['Nguyễn Văn A', 'nguyenvana@example.com', '0987654321'],
            ['Trần Thị B', 'tranthib@example.com', '0123456789'],
            ['Lê Văn C', 'levanc@example.com', '0369852147']
        ];
        
        // Tạo bảng với tiêu đề và footer
        $tableBuilder = new TableBuilder();
        $tableBuilder->setHeading('Tên', 'Email', 'Điện thoại');
        $tableBuilder->setFooting('Tổng', '3 người dùng', '');
        $tableBuilder->setCaption('Danh sách người dùng');
        
        $tableHtml = $tableBuilder->generate($data);
        
        return view('table_examples/layout_example', [
            'title' => 'Bảng với tiêu đề và footer',
            'table' => $tableHtml
        ]);
    }
    
    /**
     * Hiển thị ví dụ bảng với template tùy chỉnh
     */
    public function customTemplateExample()
    {
        // Dữ liệu mẫu
        $data = [
            ['Nguyễn Văn A', 'nguyenvana@example.com', '0987654321'],
            ['Trần Thị B', 'tranthib@example.com', '0123456789'],
            ['Lê Văn C', 'levanc@example.com', '0369852147']
        ];
        
        // Template tùy chỉnh
        $template = [
            'table_open' => '<table class="table table-bordered table-hover">',
            'thead_open' => '<thead class="table-dark">',
            'tfoot_open' => '<tfoot class="table-light">',
        ];
        
        // Tạo bảng với template tùy chỉnh
        $tableBuilder = new TableBuilder();
        $tableBuilder->setTemplate($template);
        $tableBuilder->setHeading('Tên', 'Email', 'Điện thoại');
        
        $tableHtml = $tableBuilder->generate($data);
        
        return view('table_examples/layout_example', [
            'title' => 'Bảng với template tùy chỉnh',
            'table' => $tableHtml
        ]);
    }
    
    /**
     * Hiển thị ví dụ bảng với DataTable
     */
    public function dataTableExample()
    {
        // Dữ liệu mẫu cho bảng
        $data = [
            ['1', 'Nguyễn Văn A', 'nguyenvana@example.com', '0987654321', 'Hoạt động'],
            ['2', 'Trần Thị B', 'tranthib@example.com', '0123456789', 'Không hoạt động'],
            ['3', 'Lê Văn C', 'levanc@example.com', '0369852147', 'Hoạt động'],
            ['4', 'Phạm Thị D', 'phamthid@example.com', '0765432198', 'Chờ xử lý'],
            ['5', 'Võ Văn E', 'vovane@example.com', '0912345678', 'Hoạt động'],
            ['6', 'Đặng Văn F', 'dangvanf@example.com', '0865432198', 'Không hoạt động'],
            ['7', 'Hoàng Thị G', 'hoangthig@example.com', '0978563412', 'Hoạt động'],
            ['8', 'Trương Văn H', 'truongvanh@example.com', '0956874123', 'Chờ xử lý'],
            ['9', 'Bùi Thị I', 'buithii@example.com', '0932165478', 'Hoạt động'],
            ['10', 'Lý Văn K', 'lyvank@example.com', '0918273645', 'Không hoạt động']
        ];
        
        // Thiết lập cấu hình bảng
        $tableBuilder = new TableBuilder([
            'id' => 'users-table'
        ]);
        
        // Thiết lập tiêu đề
        $tableBuilder->setHeading('ID', 'Họ tên', 'Email', 'Số điện thoại', 'Trạng thái');
        
        // Bật DataTable
        $tableBuilder->useDataTable(true);
        
        // Tùy chọn cho DataTable
        $tableBuilder->setDataTableOptions([
            'pageLength' => 5,
            'searching' => true,
            'ordering' => true,
            'responsive' => true
        ]);
        
        // Format cột trạng thái
        $tableBuilder->formatColumn(4, function($value) {
            $badge = '';
            switch ($value) {
                case 'Hoạt động':
                    $badge = '<span class="badge bg-success">Hoạt động</span>';
                    break;
                case 'Không hoạt động':
                    $badge = '<span class="badge bg-danger">Không hoạt động</span>';
                    break;
                case 'Chờ xử lý':
                    $badge = '<span class="badge bg-warning">Chờ xử lý</span>';
                    break;
                default:
                    $badge = '<span class="badge bg-secondary">' . $value . '</span>';
            }
            return $badge;
        });
        
        // Tạo HTML bảng
        $tableHtml = $tableBuilder->generate($data);
        
        return view('table_examples/layout_example', [
            'title' => 'Bảng với DataTable',
            'table' => $tableHtml
        ]);
    }
    
    /**
     * Hiển thị ví dụ bảng với chức năng xuất dữ liệu
     */
    public function exportExample()
    {
        // Dữ liệu mẫu cho bảng
        $data = [
            ['1', 'Nguyễn Văn A', 'nguyenvana@example.com', '0987654321', 'Hoạt động'],
            ['2', 'Trần Thị B', 'tranthib@example.com', '0123456789', 'Không hoạt động'],
            ['3', 'Lê Văn C', 'levanc@example.com', '0369852147', 'Hoạt động'],
            ['4', 'Phạm Thị D', 'phamthid@example.com', '0765432198', 'Chờ xử lý'],
            ['5', 'Võ Văn E', 'vovane@example.com', '0912345678', 'Hoạt động'],
            ['6', 'Đặng Văn F', 'dangvanf@example.com', '0865432198', 'Không hoạt động'],
            ['7', 'Hoàng Thị G', 'hoangthig@example.com', '0978563412', 'Hoạt động'],
            ['8', 'Trương Văn H', 'truongvanh@example.com', '0956874123', 'Chờ xử lý'],
            ['9', 'Bùi Thị I', 'buithii@example.com', '0932165478', 'Hoạt động'],
            ['10', 'Lý Văn K', 'lyvank@example.com', '0918273645', 'Không hoạt động']
        ];
        
        // Thiết lập cấu hình bảng
        $tableBuilder = new TableBuilder([
            'id' => 'export-table'
        ]);
        
        // Thiết lập tiêu đề
        $tableBuilder->setHeading('ID', 'Họ tên', 'Email', 'Số điện thoại', 'Trạng thái', 'Hành động');
        
        // Bật DataTable và tính năng xuất dữ liệu
        $tableBuilder->useDataTable(true);
        
        // Cấu hình nút xuất dữ liệu - thêm tất cả các loại
        $tableBuilder->setExportOptions([
            'enable' => true,
            'copy' => true, 
            'excel' => true, 
            'pdf' => true, 
            'print' => true
        ]);
        
        // Tùy chọn cho DataTable
        $tableBuilder->setDataTableOptions([
            'pageLength' => 5,
            'searching' => true,
            'ordering' => true,
            'responsive' => true
        ]);
        
        // Format cột trạng thái
        $tableBuilder->formatColumn(4, function($value) {
            $badge = '';
            switch ($value) {
                case 'Hoạt động':
                    $badge = '<span class="badge bg-success">Hoạt động</span>';
                    break;
                case 'Không hoạt động':
                    $badge = '<span class="badge bg-danger">Không hoạt động</span>';
                    break;
                case 'Chờ xử lý':
                    $badge = '<span class="badge bg-warning">Chờ xử lý</span>';
                    break;
                default:
                    $badge = '<span class="badge bg-secondary">' . $value . '</span>';
            }
            return $badge;
        });
        
        // Thêm cột hành động - sẽ bị loại bỏ khi xuất
        foreach ($data as $i => $row) {
            $data[$i][] = '<div class="btn-group">
                <a href="javascript:void(0)" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Sửa</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</a>
            </div>';
        }
        
        // Tạo HTML bảng
        $tableHtml = $tableBuilder->generate($data);
        
        return view('table_examples/layout_example', [
            'title' => 'Bảng với chức năng xuất dữ liệu',
            'table' => $tableHtml
        ]);
    }
    
    /**
     * Hiển thị ví dụ bảng từ cơ sở dữ liệu
     */
    public function databaseExample()
    {
        // Dữ liệu mẫu (thay vì lấy từ CSDL)
        $users = [
            ['id' => 1, 'name' => 'Nguyễn Văn A', 'email' => 'nguyenvana@example.com', 'created_at' => '2023-01-05 10:15:22'],
            ['id' => 2, 'name' => 'Trần Thị B', 'email' => 'tranthib@example.com', 'created_at' => '2023-01-10 14:20:35'],
            ['id' => 3, 'name' => 'Lê Văn C', 'email' => 'levanc@example.com', 'created_at' => '2023-02-12 09:30:15'],
            ['id' => 4, 'name' => 'Phạm Thị D', 'email' => 'phamthid@example.com', 'created_at' => '2023-02-20 16:45:10'],
            ['id' => 5, 'name' => 'Hoàng Văn E', 'email' => 'hoangvane@example.com', 'created_at' => '2023-03-07 11:25:40']
        ];
        
        // Thiết lập cấu hình bảng
        $tableBuilder = new TableBuilder([
            'id' => 'db-table'
        ]);
        
        // Thiết lập tiêu đề
        $tableBuilder->setHeading('ID', 'Tên', 'Email', 'Ngày tạo', 'Hành động');
        
        // Bật DataTable
        $tableBuilder->useDataTable(true);
        
        // Thay đổi key trong mảng dữ liệu để phù hợp với bảng
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['created_at'],
                '<div class="btn-group">
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Sửa</a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Xóa</a>
                </div>'
            ];
        }
        
        // Tạo HTML bảng
        $tableHtml = $tableBuilder->generate($data);
        
        return view('table_examples/layout_example', [
            'title' => 'Bảng từ cơ sở dữ liệu (mô phỏng)',
            'table' => $tableHtml
        ]);
    }
    
    /**
     * Hiển thị ví dụ về báo cáo với bộ lọc dữ liệu
     */
    public function reportExample()
    {
        // Tạo dữ liệu mẫu
        $data = [];
        
        // Tạo dữ liệu mẫu - 30 bản ghi
        for ($i = 1; $i <= 30; $i++) {
            $status = rand(1, 3);
            $statusText = '';
            
            switch ($status) {
                case 1:
                    $statusText = '<span class="badge bg-success">Hoạt động</span>';
                    break;
                case 2:
                    $statusText = '<span class="badge bg-warning">Tạm dừng</span>';
                    break;
                case 3:
                    $statusText = '<span class="badge bg-danger">Khóa</span>';
                    break;
            }
            
            // Tạo dữ liệu mẫu cho các cột
            $dept = rand(1, 5);
            $deptName = '';
            switch ($dept) {
                case 1: $deptName = 'Phòng Kế toán'; break;
                case 2: $deptName = 'Phòng Kinh doanh'; break;
                case 3: $deptName = 'Phòng IT'; break;
                case 4: $deptName = 'Phòng Nhân sự'; break;
                case 5: $deptName = 'Ban Giám đốc'; break;
            }
            
            $warehouse = rand(1, 4);
            $warehouseName = '';
            switch ($warehouse) {
                case 1: $warehouseName = 'Kho Hà Nội'; break;
                case 2: $warehouseName = 'Kho Hồ Chí Minh'; break;
                case 3: $warehouseName = 'Kho Đà Nẵng'; break;
                case 4: $warehouseName = 'Kho Cần Thơ'; break;
            }
            
            $userType = rand(1, 3);
            $userTypeName = '';
            switch ($userType) {
                case 1: $userTypeName = 'Quản trị viên'; break;
                case 2: $userTypeName = 'Nhân viên'; break;
                case 3: $userTypeName = 'Khách hàng'; break;
            }
            
            // Tạo ngày trong 2 năm gần đây
            $date = date('Y-m-d', strtotime('-' . rand(1, 730) . ' days'));
            
            // Tạo số lượng và đơn giá
            $quantity = rand(1, 100);
            $price = rand(10, 1000) * 1000;
            $total = $quantity * $price;
            
            $data[] = [
                $i,
                'Sản phẩm ' . $i,
                $deptName,
                $warehouseName,
                $userTypeName,
                $date,
                number_format($quantity),
                number_format($price) . ' đ',
                number_format($total) . ' đ',
                $statusText
            ];
        }
        
        // Khởi tạo TableBuilder
        $tableBuilder = new TableBuilder([
            'id' => 'report-table',
            'class' => 'table table-striped table-hover'
        ]);
        
        // Thiết lập tiêu đề cho các cột
        $tableBuilder->setHeading(
            'ID', 
            'Tên sản phẩm', 
            'Phòng ban', 
            'Kho', 
            'Loại người dùng', 
            'Ngày tạo', 
            'Số lượng', 
            'Đơn giá', 
            'Thành tiền', 
            'Trạng thái'
        );
        
        // Bật DataTable và tính năng xuất dữ liệu
        $tableBuilder->useDataTable(true);
        $tableBuilder->setExportOptions([
            'enable' => true,
            'copy' => true, 
            'excel' => true, 
            'pdf' => true, 
            'print' => true
        ]);
        
        // Thiết lập các bộ lọc báo cáo
        $tableBuilder->setFilters([
            [
                'type' => 'text',
                'name' => 'product_name',
                'label' => 'Tên sản phẩm',
                'column' => 1,
                'placeholder' => 'Nhập tên sản phẩm...',
                'class' => 'col-md-4'
            ],
            [
                'type' => 'select',
                'name' => 'department',
                'label' => 'Phòng ban',
                'column' => 2,
                'options' => [
                    'Phòng Kế toán' => 'Phòng Kế toán',
                    'Phòng Kinh doanh' => 'Phòng Kinh doanh',
                    'Phòng IT' => 'Phòng IT',
                    'Phòng Nhân sự' => 'Phòng Nhân sự',
                    'Ban Giám đốc' => 'Ban Giám đốc'
                ],
                'class' => 'col-md-4'
            ],
            [
                'type' => 'select',
                'name' => 'warehouse',
                'label' => 'Kho',
                'column' => 3,
                'options' => [
                    'Kho Hà Nội' => 'Kho Hà Nội',
                    'Kho Hồ Chí Minh' => 'Kho Hồ Chí Minh',
                    'Kho Đà Nẵng' => 'Kho Đà Nẵng',
                    'Kho Cần Thơ' => 'Kho Cần Thơ'
                ],
                'class' => 'col-md-4'
            ],
            [
                'type' => 'select',
                'name' => 'user_type',
                'label' => 'Loại người dùng',
                'column' => 4,
                'options' => [
                    'Quản trị viên' => 'Quản trị viên',
                    'Nhân viên' => 'Nhân viên',
                    'Khách hàng' => 'Khách hàng'
                ],
                'class' => 'col-md-4'
            ],
            [
                'type' => 'daterange',
                'name' => 'created_date',
                'label' => 'Thời gian tạo',
                'column' => 5,
                'class' => 'col-md-4'
            ],
            [
                'type' => 'numberrange',
                'name' => 'quantity',
                'label' => 'Số lượng',
                'column' => 6,
                'class' => 'col-md-4'
            ]
        ]);
        
        // Tùy chọn cho DataTable
        $tableBuilder->setDataTableOptions([
            'pageLength' => 10,
            'lengthMenu' => [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Tất cả']],
            'order' => [[0, 'asc']]
        ]);
        
        // Tạo HTML bảng
        $tableHtml = $tableBuilder->generate($data);
        
        return view('table_examples/layout_example', [
            'title' => 'Báo cáo với bộ lọc dữ liệu',
            'table' => $tableHtml
        ]);
    }
} 