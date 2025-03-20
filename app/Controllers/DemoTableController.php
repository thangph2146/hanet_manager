<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\TableBuilder;

class DemoTableController extends BaseController
{
    public function index()
    {
        // Tạo dữ liệu mẫu sản phẩm lớn hơn
        $products = $this->generateSampleProducts(50);

        // Chuyển đổi dữ liệu sang dạng phù hợp cho bảng
        $tableData = [];
        foreach ($products as $product) {
            // Format giá và trạng thái
            $price = number_format($product['price'], 0, ',', '.') . ' đ';
            
            $status = '';
            switch($product['status']) {
                case 'Còn hàng':
                    $status = '<span class="badge bg-success">Còn hàng</span>';
                    break;
                case 'Hết hàng':
                    $status = '<span class="badge bg-danger">Hết hàng</span>';
                    break;
                case 'Sắp hết':
                    $status = '<span class="badge bg-warning">Sắp hết</span>';
                    break;
                default:
                    $status = '<span class="badge bg-secondary">' . $product['status'] . '</span>';
            }
            
            // Thêm hành động
            $actions = '<div class="btn-group">
                <a href="javascript:void(0)" class="btn btn-sm btn-info"><i class="bi bi-eye" style="display:inline-block;"></i></a>
                <a href="javascript:void(0)" class="btn btn-sm btn-primary"><i class="bi bi-pencil" style="display:inline-block;"></i></a>
                <a href="javascript:void(0)" class="btn btn-sm btn-danger"><i class="bi bi-trash" style="display:inline-block;"></i></a>
            </div>';
            
            $tableData[] = [
                $product['id'],
                $product['name'],
                $product['category'],
                $price,
                $product['quantity'],
                $product['date'],
                $status,
                $actions
            ];
        }

        // Tạo bảng thông thường
        $basicBuilder = new TableBuilder();
        $basicBuilder->setHeading('ID', 'Tên sản phẩm', 'Loại', 'Giá bán', 'Số lượng', 'Ngày tạo', 'Trạng thái', 'Thao tác');
        $basicTable = $basicBuilder->generate($tableData);

        // Tạo bảng với DataTable
        $dataTableBuilder = new TableBuilder();
        $dataTableBuilder->setHeading('ID', 'Tên sản phẩm', 'Loại', 'Giá bán', 'Số lượng', 'Ngày tạo', 'Trạng thái', 'Thao tác');
        $dataTableBuilder->useDataTable(true);
        $dataTableBuilder->setDataTableOptions([
            'pageLength' => 10,
            'lengthMenu' => [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Tất cả']],
            'searching' => true,
            'responsive' => true
        ]);
        $dataTable = $dataTableBuilder->generate($tableData);

        // Tạo bảng với chức năng xuất dữ liệu
        $exportBuilder = new TableBuilder();
        $exportBuilder->setHeading('ID', 'Tên sản phẩm', 'Loại', 'Giá bán', 'Số lượng', 'Ngày tạo', 'Trạng thái', 'Thao tác');
        $exportBuilder->useDataTable(true);
        $exportBuilder->setExportOptions([
            'enable' => true,
            'excel' => true,
            'pdf' => true
        ]);
        $exportTable = $exportBuilder->generate($tableData);

        // Tạo bảng có tính năng lọc
        $filterBuilder = new TableBuilder([
            'id' => 'filter-table',
            'table_open' => '<table id="filter-table" class="table table-striped table-bordered">'
        ]);
        $filterBuilder->setHeading('ID', 'Tên sản phẩm', 'Loại', 'Giá bán', 'Số lượng', 'Ngày tạo', 'Trạng thái', 'Thao tác');
        $filterBuilder->useDataTable(true);
        $filterBuilder->setExportOptions([
            'enable' => true,
            'excel' => true,
            'pdf' => true
        ]);
        
        // Thiết lập các bộ lọc
        $filterBuilder->setFilters([
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
                'name' => 'category',
                'label' => 'Loại sản phẩm',
                'column' => 2,
                'options' => [
                    'Điện thoại' => 'Điện thoại',
                    'Laptop' => 'Laptop',
                    'Máy tính bảng' => 'Máy tính bảng',
                    'Máy ảnh' => 'Máy ảnh',
                    'Tai nghe' => 'Tai nghe',
                    'Đồng hồ thông minh' => 'Đồng hồ thông minh',
                    'Thiết bị nhà thông minh' => 'Thiết bị nhà thông minh',
                    'Âm thanh' => 'Âm thanh'
                ],
                'class' => 'col-md-4'
            ],
            [
                'type' => 'numberrange',
                'name' => 'quantity',
                'label' => 'Số lượng',
                'column' => 4,
                'class' => 'col-md-4'
            ],
            [
                'type' => 'daterange',
                'name' => 'created_date',
                'label' => 'Ngày tạo',
                'column' => 5,
                'class' => 'col-md-4'
            ],
            [
                'type' => 'select',
                'name' => 'status',
                'label' => 'Trạng thái',
                'column' => 6,
                'options' => [
                    'Còn hàng' => 'Còn hàng',
                    'Hết hàng' => 'Hết hàng',
                    'Sắp hết' => 'Sắp hết'
                ],
                'class' => 'col-md-4'
            ]
        ]);

        // Thiết lập tùy chọn cho DataTable
        $filterBuilder->setDataTableOptions([
            'pageLength' => 10,
            'lengthMenu' => [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Tất cả']],
            'searching' => true,
            'ordering' => true,
            'responsive' => true,
            'order' => [[0, 'asc']]
        ]);

        // Tạo HTML bảng
        $filterTable = $filterBuilder->generate($tableData);

        return view('demo/tables', [
            'title' => 'Demo các loại bảng',
            'basicTable' => $basicTable,
            'dataTable' => $dataTable,
            'exportTable' => $exportTable,
            'filterTable' => $filterTable
        ]);
    }

    /**
     * Tạo dữ liệu mẫu sản phẩm
     * 
     * @param int $count Số lượng sản phẩm
     * @return array
     */
    private function generateSampleProducts($count = 50)
    {
        $products = [];
        $categories = ['Điện thoại', 'Laptop', 'Máy tính bảng', 'Máy ảnh', 'Tai nghe', 'Đồng hồ thông minh', 'Thiết bị nhà thông minh', 'Âm thanh'];
        $productNames = [
            'Điện thoại' => ['iPhone 13', 'Samsung Galaxy S21', 'Xiaomi Mi 11', 'Oppo Reno 6', 'Vivo V21', 'Realme GT', 'iPhone 12', 'Samsung Galaxy A52'],
            'Laptop' => ['Dell XPS 13', 'MacBook Air M1', 'HP Spectre x360', 'Lenovo ThinkPad X1', 'Asus ZenBook', 'Acer Swift 5', 'MSI GS66', 'Dell Inspiron 15'],
            'Máy tính bảng' => ['iPad Pro 11', 'Samsung Galaxy Tab S7', 'Xiaomi Mi Pad 5', 'Lenovo Tab P11', 'Huawei MatePad Pro', 'iPad Air', 'Amazon Fire HD 10'],
            'Máy ảnh' => ['Sony A7III', 'Canon EOS R6', 'Nikon Z6', 'Fujifilm X-T4', 'Panasonic GH5', 'Sony ZV-1', 'Canon EOS 90D', 'Nikon D780'],
            'Tai nghe' => ['AirPods Pro', 'Samsung Galaxy Buds Pro', 'Sony WF-1000XM4', 'Bose QuietComfort Earbuds', 'Jabra Elite 85t', 'Sennheiser Momentum'],
            'Đồng hồ thông minh' => ['Apple Watch Series 7', 'Samsung Galaxy Watch 4', 'Garmin Fenix 6', 'Fitbit Sense', 'Huawei Watch GT 3', 'Xiaomi Mi Watch'],
            'Thiết bị nhà thông minh' => ['Google Nest Hub', 'Amazon Echo Dot', 'Xiaomi Robot Vacuum', 'Philips Hue', 'Ring Video Doorbell', 'Ecobee Smart Thermostat'],
            'Âm thanh' => ['Sonos One', 'Bose SoundLink Revolve', 'JBL Flip 6', 'Sony SRS-XB43', 'Ultimate Ears Boom 3', 'Anker Soundcore']
        ];
        $statuses = ['Còn hàng', 'Hết hàng', 'Sắp hết'];

        for ($i = 1; $i <= $count; $i++) {
            $category = $categories[array_rand($categories)];
            $productName = $productNames[$category][array_rand($productNames[$category])];
            
            // Thêm biến thể vào tên sản phẩm
            if (rand(0, 1)) {
                $variants = ['Pro', 'Ultra', 'Max', 'Plus', 'Lite', 'SE', 'Premium', 'Standard', 'Enhanced'];
                $productName .= ' ' . $variants[array_rand($variants)];
            }
            
            // Thêm màu sắc vào tên sản phẩm
            if (rand(0, 1)) {
                $colors = ['Đen', 'Trắng', 'Bạc', 'Vàng', 'Xám', 'Xanh', 'Đỏ', 'Hồng', 'Tím'];
                $productName .= ' ' . $colors[array_rand($colors)];
            }
            
            // Tạo giá với phân phối thực tế hơn
            $basePrice = 0;
            switch ($category) {
                case 'Điện thoại':
                    $basePrice = rand(5, 40) * 1000000;
                    break;
                case 'Laptop':
                    $basePrice = rand(12, 60) * 1000000;
                    break;
                case 'Máy tính bảng':
                    $basePrice = rand(8, 35) * 1000000;
                    break;
                case 'Máy ảnh':
                    $basePrice = rand(15, 80) * 1000000;
                    break;
                case 'Tai nghe':
                    $basePrice = rand(1, 10) * 1000000;
                    break;
                case 'Đồng hồ thông minh':
                    $basePrice = rand(3, 20) * 1000000;
                    break;
                case 'Thiết bị nhà thông minh':
                    $basePrice = rand(1, 15) * 1000000;
                    break;
                case 'Âm thanh':
                    $basePrice = rand(2, 25) * 1000000;
                    break;
            }
            
            // Tạo số lượng
            $quantity = rand(0, 50);
            
            // Xác định trạng thái dựa trên số lượng
            $status = '';
            if ($quantity == 0) {
                $status = 'Hết hàng';
            } elseif ($quantity <= 5) {
                $status = 'Sắp hết';
            } else {
                $status = 'Còn hàng';
            }
            
            // Tạo ngày tạo trong 6 tháng gần đây
            $date = date('Y-m-d', strtotime('-' . rand(1, 180) . ' days'));
            
            $products[] = [
                'id' => $i,
                'name' => $productName,
                'category' => $category,
                'price' => $basePrice,
                'quantity' => $quantity,
                'status' => $status,
                'date' => $date
            ];
        }

        return $products;
    }
} 