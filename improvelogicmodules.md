---
description: 
globs: 
alwaysApply: false
---
---
description: 
globs: 
alwaysApply: false
---
---
description: Hướng dẫn tạo module mới trong CodeIgniter 4.6
globs: 
alwaysApply: true
---
# Hướng Dẫn Tạo Module Mới Trong CodeIgniter 4.6

## 1. Cấu trúc thư mục Module

Mỗi module tuân theo cấu trúc thư mục chuẩn sau:

```
app/Modules/tên_module/
├── Config/
│   └── Routes.php
├── Controllers/
│   └── TênController.php
├── Database/
│   ├── Migrations/
│   │   └── YYYY-MM-DD-XXXXXX_CreateTableName.php
│   └── Seeds/
│       └── TableNameSeeder.php
├── Entities/
│   └── TênEntity.php
├── Models/
│   └── TênModel.php
└── Views/
    ├── index.php
    ├── new.php
    ├── edit.php
    ├── form.php
    ├── listdeleted.php
    └── master_scripts.php
```

## 2. Các bước tạo module mới

### Bước 1: Tạo cấu trúc thư mục module

Tạo các thư mục với cấu trúc như trên. Thay `tên_module` bằng tên module của bạn, viết thường, không dấu, không khoảng trắng.

### Bước 2: Tạo file Config/Routes.php

```php
<?php

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('tên_module', ['namespace' => 'App\Modules\tên_module\Controllers'], function ($routes) {
    $routes->get('/', 'TênController::index');    
    $routes->get('new', 'TênController::new');
    $routes->post('create', 'TênController::create');
    $routes->get('edit/(:num)', 'TênController::edit/$1');
    $routes->post('update/(:num)', 'TênController::update/$1');
    $routes->post('delete/(:num)', 'TênController::delete/$1');
    $routes->post('status/(:num)', 'TênController::status/$1');
    $routes->post('bulkDelete', 'TênController::bulkDelete');
    $routes->post('deleteMultiple', 'TênController::bulkDelete');
    $routes->post('statusMultiple', 'TênController::statusMultiple');
    $routes->get('listdeleted', 'TênController::listdeleted');
    $routes->post('restore/(:num)', 'TênController::restore/$1');
    $routes->post('bulkRestore', 'TênController::bulkRestore');
    $routes->post('restore', 'TênController::bulkRestore');
    $routes->post('permanentDelete/(:num)', 'TênController::permanentDelete/$1');
    $routes->post('bulkPermanentDelete', 'TênController::bulkPermanentDelete');
    $routes->post('permanentDelete', 'TênController::bulkPermanentDelete');
    $routes->get('debug', 'TênController::debug');
}); 
```

### Bước 3: Tạo file Database/Migrations

Tạo file migration với định dạng `YYYY-MM-DD-XXXXXX_CreateTableName.php`:

```php
<?php

namespace App\Modules\tên_module\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableName extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_field' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'field_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            // Thêm các trường khác theo cần thiết
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'bin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null,
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null,
                'on update'  => 'CURRENT_TIMESTAMP',
            ],
            'deleted_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null,
            ],
        ]);
        
        $this->forge->addKey('id_field', true);
        $this->forge->createTable('table_name');
    }

    public function down()
    {
        $this->forge->dropTable('table_name');
    }
}
```

### Bước 4: Tạo file Database/Seeds

```php
<?php

namespace App\Modules\tên_module\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TableNameSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'field_name'  => 'Giá trị mẫu 1',
                'status'      => 1,
                'bin'         => 0,
                'created_at'  => Time::now()
            ],
            [
                'field_name'  => 'Giá trị mẫu 2',
                'status'      => 1,
                'bin'         => 0,
                'created_at'  => Time::now()
            ],
            // Thêm dữ liệu mẫu khác nếu cần
        ];

        $this->db->table('table_name')->insertBatch($data);
    }
}
```

### Bước 5: Tạo file Entity

```php
<?php

namespace App\Modules\tên_module\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class TênEntity extends BaseEntity
{
    protected $tableName = 'table_name';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
        // Thêm các trường date khác nếu cần
    ];
    
    protected $casts = [
        'id_field' => 'int',
        'status' => 'int',
        'bin' => 'int',
        // Thêm cast cho các trường khác nếu cần
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [
        'field_name' => 'required|min_length[3]|max_length[50]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'field_name' => [
            'required' => 'Trường này là bắt buộc',
            'min_length' => 'Trường này phải có ít nhất {param} ký tự',
            'max_length' => 'Trường này không được vượt quá {param} ký tự',
        ],
    ];
}
```

### Bước 6: Tạo file Model

```php
<?php

namespace App\Modules\tên_module\Models;

use App\Models\BaseModel;

class TênModel extends BaseModel
{
    protected $table = 'table_name';
    protected $primaryKey = 'id_field';
    protected $returnType = 'App\Modules\tên_module\Entities\TênEntity';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'field_name',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Các trường liên quan đến timestamp
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Các trường có thể tìm kiếm
    protected $searchableFields = [
        'field_name'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Các trường cần loại bỏ khoảng trắng thừa
    protected $beforeSpaceRemoval = [
        'field_name'
    ];
    
    // Định nghĩa các mối quan hệ
    protected $relations = [];
    
    /**
     * Validation rules
     */
    protected $validationRules = [
        'field_name' => 'required|min_length[3]|max_length[50]'
    ];

    /**
     * Validation messages
     */
    protected $validationMessages = [
        'field_name' => [
            'required' => 'Trường này không được để trống',
            'min_length' => 'Trường này phải có ít nhất 3 ký tự',
            'max_length' => 'Trường này không được vượt quá 50 ký tự'
        ]
    ];
    
    /**
     * Lấy tất cả bản ghi đang hoạt động
     */
    public function getAllActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->findAll();
    }
    
    /**
     * Lấy tất cả bản ghi đã xóa tạm thời
     */
    public function getAllDeleted()
    {
        return $this->where('bin', 1)
                    ->findAll();
    }
}
```

### Bước 7: Tạo Controller

```php
<?php

namespace App\Modules\tên_module\Controllers;

use App\Controllers\BaseController;
use App\Modules\tên_module\Models\TênModel;
use CodeIgniter\HTTP\ResponseInterface;

class TênController extends BaseController
{
    protected $model;
    protected $validation;

    public function __construct()
    {
        $this->model = new TênModel();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Hiển thị danh sách bản ghi
     */
    public function index()
    {
        $items = $this->model->getAllActive();
        
        return view('App\Modules\tên_module\Views\index', [
            'items' => $items,
            'pager' => $this->model->pager
        ]);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        return view('App\Modules\tên_module\Views\new');
    }
    
    /**
     * Xử lý tạo mới bản ghi
     */
    public function create()
    {
        $data = $this->request->getPost();
        
        if (!$this->validate($this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->model->insert($data);
        
        return redirect()->to('tên_module')->with('message', 'Thêm mới thành công');
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id)
    {
        $item = $this->model->find($id);
        
        if (!$item) {
            return redirect()->to('tên_module')->with('error', 'Không tìm thấy bản ghi');
        }
        
        return view('App\Modules\tên_module\Views\edit', [
            'item' => $item
        ]);
    }
    
    /**
     * Xử lý cập nhật bản ghi
     */
    public function update($id)
    {
        $data = $this->request->getPost();
        
        if (!$this->validate($this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->model->update($id, $data);
        
        return redirect()->to('tên_module')->with('message', 'Cập nhật thành công');
    }
    
    /**
     * Xóa bản ghi (đánh dấu là đã xóa)
     */
    public function delete($id)
    {
        $this->model->update($id, ['bin' => 1]);
        
        return redirect()->to('tên_module')->with('message', 'Xóa thành công');
    }
    
    /**
     * Hiển thị danh sách bản ghi đã xóa
     */
    public function listdeleted()
    {
        $items = $this->model->getAllDeleted();
        
        return view('App\Modules\tên_module\Views\listdeleted', [
            'items' => $items,
            'pager' => $this->model->pager
        ]);
    }
    
    /**
     * Khôi phục bản ghi đã xóa
     */
    public function restore($id)
    {
        $this->model->update($id, ['bin' => 0]);
        
        return redirect()->to('tên_module/listdeleted')->with('message', 'Khôi phục thành công');
    }
    
    /**
     * Xóa vĩnh viễn bản ghi
     */
    public function permanentDelete($id)
    {
        $this->model->delete($id, true);
        
        return redirect()->to('tên_module/listdeleted')->with('message', 'Đã xóa vĩnh viễn');
    }
    
    /**
     * Cập nhật trạng thái bản ghi
     */
    public function status($id)
    {
        $item = $this->model->find($id);
        
        if (!$item) {
            return redirect()->to('tên_module')->with('error', 'Không tìm thấy bản ghi');
        }
        
        $newStatus = $item->status == 1 ? 0 : 1;
        $this->model->update($id, ['status' => $newStatus]);
        
        return redirect()->to('tên_module')->with('message', 'Cập nhật trạng thái thành công');
    }
    
    /**
     * Xóa nhiều bản ghi cùng lúc
     */
    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids)) {
            return redirect()->to('tên_module')->with('error', 'Không có bản ghi nào được chọn');
        }
        
        foreach ($ids as $id) {
            $this->model->update($id, ['bin' => 1]);
        }
        
        return redirect()->to('tên_module')->with('message', 'Xóa thành công ' . count($ids) . ' bản ghi');
    }
    
    /**
     * Khôi phục nhiều bản ghi cùng lúc
     */
    public function bulkRestore()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids)) {
            return redirect()->to('tên_module/listdeleted')->with('error', 'Không có bản ghi nào được chọn');
        }
        
        foreach ($ids as $id) {
            $this->model->update($id, ['bin' => 0]);
        }
        
        return redirect()->to('tên_module/listdeleted')->with('message', 'Khôi phục thành công ' . count($ids) . ' bản ghi');
    }
    
    /**
     * Xóa vĩnh viễn nhiều bản ghi cùng lúc
     */
    public function bulkPermanentDelete()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids)) {
            return redirect()->to('tên_module/listdeleted')->with('error', 'Không có bản ghi nào được chọn');
        }
        
        foreach ($ids as $id) {
            $this->model->delete($id, true);
        }
        
        return redirect()->to('tên_module/listdeleted')->with('message', 'Đã xóa vĩnh viễn ' . count($ids) . ' bản ghi');
    }
    
    /**
     * Cập nhật trạng thái nhiều bản ghi cùng lúc
     */
    public function statusMultiple()
    {
        $ids = $this->request->getPost('ids');
        $status = $this->request->getPost('status');
        
        if (empty($ids)) {
            return redirect()->to('tên_module')->with('error', 'Không có bản ghi nào được chọn');
        }
        
        foreach ($ids as $id) {
            $this->model->update($id, ['status' => $status]);
        }
        
        return redirect()->to('tên_module')->with('message', 'Cập nhật trạng thái thành công ' . count($ids) . ' bản ghi');
    }
    
    /**
     * Phương thức debug để kiểm tra kết nối database
     */
    public function debug()
    {
        $db = \Config\Database::connect();
        echo "<h2>Database Debug</h2>";
        
        // Kiểm tra kết nối
        echo "<p>Kết nối Database: " . ($db->connected ? "Thành công" : "Thất bại") . "</p>";
        
        // Kiểm tra bảng
        try {
            $tables = $db->listTables();
            echo "<p>Danh sách bảng: " . implode(', ', $tables) . "</p>";
            
            echo "<p>Kiểm tra bảng table_name: " . (in_array('table_name', $tables) ? "Tồn tại" : "Không tồn tại") . "</p>";
            
            if (in_array('table_name', $tables)) {
                // Lấy cấu trúc bảng
                $fields = $db->getFieldData('table_name');
                echo "<h3>Cấu trúc bảng table_name:</h3>";
                echo "<ul>";
                foreach ($fields as $field) {
                    echo "<li>{$field->name} - {$field->type} " . ($field->primary_key ? "(Primary Key)" : "") . "</li>";
                }
                echo "</ul>";
                
                // Đếm số bản ghi
                $query = $db->query("SELECT COUNT(*) as count FROM table_name");
                $row = $query->getRow();
                echo "<p>Tổng số bản ghi: {$row->count}</p>";
            }
        } catch (\Exception $e) {
            echo "<p>Lỗi: " . $e->getMessage() . "</p>";
        }
        
        exit;
    }
}
```

### Bước 8: Tạo Views

#### 8.1. File index.php:
```php
<?= $this->extend('App\Modules\layouts\Views\main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Danh sách bản ghi</h4>
                <div class="card-tools">
                    <a href="<?= base_url('tên_module/new') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                    <a href="<?= base_url('tên_module/listdeleted') ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Thùng rác
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%"><input type="checkbox" class="check-all"></th>
                            <th>Tên</th>
                            <th width="10%">Trạng thái</th>
                            <th width="15%">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><input type="checkbox" class="check-item" value="<?= $item->id_field ?>"></td>
                                    <td><?= esc($item->field_name) ?></td>
                                    <td>
                                        <form action="<?= base_url('tên_module/status/' . $item->id_field) ?>" method="post" class="d-inline">
                                            <button type="submit" class="btn btn-sm <?= $item->status ? 'btn-success' : 'btn-secondary' ?>">
                                                <?= $item->status ? 'Hoạt động' : 'Không hoạt động' ?>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('tên_module/edit/' . $item->id_field) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= base_url('tên_module/delete/' . $item->id_field) ?>" method="post" class="d-inline delete-form">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <?php if (!empty($items)): ?>
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger btn-sm bulk-delete">Xóa đã chọn</button>
                        <button type="button" class="btn btn-success btn-sm bulk-status" data-status="1">Kích hoạt đã chọn</button>
                        <button type="button" class="btn btn-secondary btn-sm bulk-status" data-status="0">Vô hiệu đã chọn</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->include('App\Modules\tên_module\Views\master_scripts') ?>

<?= $this->endSection() ?>
```

Tôi sẽ tiếp tục thêm các file Views khác vào hướng dẫn này. Đây là cấu trúc cơ bản để tạo một module mới trong CodeIgniter 4.6.

## 3. Chạy Migration và Seeder

Sau khi đã tạo đầy đủ các file cần thiết, bạn cần chạy migration và seeder để tạo bảng và dữ liệu mẫu:

```
php spark migrate -n "App\Modules\tên_module\Database\Migrations"
php spark db:seed "App\Modules\tên_module\Database\Seeds\TableNameSeeder"
```

## 4. Kiểm tra Module

Truy cập đường dẫn `http://your-site/tên_module` để kiểm tra module mới đã hoạt động chưa.

## 5. Chú ý quan trọng

- Thay thế `tên_module` bằng tên thực tế của module bạn muốn tạo (viết thường, không dấu).
- Thay thế `table_name` bằng tên bảng trong database.
- Thay thế `TênController`, `TênModel`, `TênEntity` bằng tên thích hợp (viết hoa chữ cái đầu).
- Thay thế `id_field` bằng tên trường khóa chính thích hợp.
- Thay thế `field_name` và thêm các trường khác theo nhu cầu của bạn.