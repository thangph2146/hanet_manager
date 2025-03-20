<?php

namespace App\Controllers;

use App\Libraries\FormBuilder;
use CodeIgniter\Controller;

class FormExampleController extends Controller
{
    protected $formBuilder;
    
    public function __construct()
    {
        helper(['form', 'url']);
        $this->formBuilder = new FormBuilder();
    }
    
    /**
     * Ví dụ form đơn giản
     */
    public function basic()
    {
        // Cấu hình form cơ bản
        $config = [
            'attributes' => [
                'action' => current_url(),
                'method' => 'post',
                'id' => 'basic-form',
                'class' => 'needs-validation',
            ],
            'fields' => [
                'name' => [
                    'type' => 'text',
                    'label' => 'Họ tên',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nhập họ tên',
                    ],
                    'rules' => 'required',
                    'help' => 'Vui lòng nhập đầy đủ họ tên',
                ],
                'email' => [
                    'type' => 'email',
                    'label' => 'Email',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'email@example.com',
                    ],
                    'rules' => 'required|valid_email',
                ],
                'phone' => [
                    'type' => 'tel',
                    'label' => 'Số điện thoại',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => '0912345678',
                    ],
                ],
                'gender' => [
                    'type' => 'radio',
                    'label' => 'Giới tính',
                    'options' => [
                        'male' => 'Nam',
                        'female' => 'Nữ',
                        'other' => 'Khác',
                    ],
                    'value' => 'male',
                ],
                'agree' => [
                    'type' => 'checkbox',
                    'label' => 'Đồng ý với điều khoản',
                    'attributes' => [
                        'class' => 'form-check-input',
                    ],
                    'rules' => 'required',
                    'wrapper_attr' => ['class' => 'form-check mb-3'],
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Gửi form',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                    ],
                ],
            ],
            'validation' => [
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Họ tên không được để trống',
                    ],
                ],
                'email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email không được để trống',
                        'valid_email' => 'Email không hợp lệ',
                    ],
                ],
                'agree' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Bạn phải đồng ý với điều khoản',
                    ],
                ],
            ],
        ];

        // Xử lý submit
        if ($this->request->getMethod() === 'post') {
            // Cấu hình form và validate
            $this->formBuilder->config($config);
            
            if ($this->formBuilder->validate()) {

                // Xử lý dữ liệu thành công
                return redirect()->to(current_url())->with('success', 'Form đã được gửi thành công!');
            }
        }
        
        // Render form
        $data = [
            'title' => 'Form cơ bản',
            'content' => $this->formBuilder->config($config)->render('form/default', [], true),
        ];
        
        return view('examples/form_example', $data);
    }
    
    /**
     * Ví dụ form nâng cao với nhóm trường
     */
    public function advanced()
    {
        // Cấu hình form nâng cao
        $config = [
            'attributes' => [
                'action' => current_url(),
                'method' => 'post',
                'id' => 'advanced-form',
                'class' => 'needs-validation',
            ],
            'layout' => 'horizontal',
            'fieldsets' => [
                'personal-info' => [
                    'legend' => 'Thông tin cá nhân',
                    'fields' => [
                        'fullname' => [
                            'type' => 'text',
                            'label' => 'Họ tên đầy đủ',
                            'attributes' => [
                                'class' => 'form-control',
                            ],
                            'rules' => 'required',
                        ],
                        'email' => [
                            'type' => 'email',
                            'label' => 'Email',
                            'attributes' => [
                                'class' => 'form-control',
                            ],
                            'rules' => 'required|valid_email',
                        ],
                        'dob' => [
                            'type' => 'date',
                            'label' => 'Ngày sinh',
                            'attributes' => [
                                'class' => 'form-control',
                            ],
                        ],
                    ],
                ],
                'address' => [
                    'legend' => 'Địa chỉ',
                    'fields' => [
                        'address_line1' => [
                            'type' => 'text',
                            'label' => 'Địa chỉ',
                            'attributes' => [
                                'class' => 'form-control',
                            ],
                        ],
                        'city' => [
                            'type' => 'text',
                            'label' => 'Thành phố',
                            'attributes' => [
                                'class' => 'form-control',
                            ],
                        ],
                        'province' => [
                            'type' => 'select',
                            'label' => 'Tỉnh/Thành phố',
                            'options' => [
                                '' => '-- Chọn Tỉnh/Thành phố --',
                                'hanoi' => 'Hà Nội',
                                'hcm' => 'TP Hồ Chí Minh',
                                'danang' => 'Đà Nẵng',
                                'cantho' => 'Cần Thơ',
                            ],
                            'attributes' => [
                                'class' => 'form-select',
                            ],
                        ],
                    ],
                ],
            ],
            'fields' => [
                'newsletter' => [
                    'type' => 'checkbox',
                    'label' => 'Đăng ký nhận bản tin',
                    'attributes' => [
                        'class' => 'form-check-input',
                    ],
                    'wrapper_attr' => ['class' => 'form-check mb-3'],
                ],
                'notes' => [
                    'type' => 'textarea',
                    'label' => 'Ghi chú',
                    'attributes' => [
                        'class' => 'form-control',
                        'rows' => 5,
                    ],
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Gửi form',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                    ],
                ],
            ],
        ];

        // Xử lý submit
        if ($this->request->getMethod() === 'post') {
            $this->formBuilder->config($config);
            
            if ($this->formBuilder->validate()) {
                return redirect()->to(current_url())->with('success', 'Form đã được gửi thành công!');
            }
        }
        
        // Render form
        $data = [
            'title' => 'Form nâng cao',
            'content' => $this->formBuilder->config($config)->render(null, [], true),
        ];
        
        return view('examples/form_example', $data);
    }
    
    /**
     * Ví dụ form với dữ liệu từ database
     */
    public function editUser($id = null)
    {
        // Giả lập dữ liệu user
        $userData = [
            'id' => 1,
            'fullname' => 'Nguyễn Văn A',
            'email' => 'nguyenvana@example.com',
            'dob' => '1990-01-01',
            'address' => 'Số 1 Đường ABC',
            'city' => 'Hà Nội',
            'role' => 'admin',
            'active' => true,
        ];
        
        // Cấu hình form
        $config = [
            'attributes' => [
                'action' => current_url(),
                'method' => 'post',
                'id' => 'edit-user-form',
            ],
            'fields' => [
                'id' => [
                    'type' => 'hidden',
                    'value' => $userData['id'],
                ],
                'fullname' => [
                    'type' => 'text',
                    'label' => 'Họ tên',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'rules' => 'required',
                ],
                'email' => [
                    'type' => 'email',
                    'label' => 'Email',
                    'attributes' => [
                        'class' => 'form-control',
                        'readonly' => true,
                    ],
                    'rules' => 'required|valid_email',
                ],
                'dob' => [
                    'type' => 'date',
                    'label' => 'Ngày sinh',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                ],
                'address' => [
                    'type' => 'text',
                    'label' => 'Địa chỉ',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                ],
                'city' => [
                    'type' => 'text',
                    'label' => 'Thành phố',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                ],
                'role' => [
                    'type' => 'select',
                    'label' => 'Vai trò',
                    'options' => [
                        'user' => 'Người dùng',
                        'admin' => 'Quản trị viên',
                        'editor' => 'Biên tập viên',
                    ],
                    'attributes' => [
                        'class' => 'form-select',
                    ],
                ],
                'active' => [
                    'type' => 'checkbox',
                    'label' => 'Kích hoạt tài khoản',
                    'value' => '1',
                    'checked' => $userData['active'],
                    'attributes' => [
                        'class' => 'form-check-input',
                    ],
                    'wrapper_attr' => ['class' => 'form-check mb-3'],
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Cập nhật',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                    ],
                ],
            ],
        ];

        // Xử lý submit
        if ($this->request->getMethod() === 'post') {
            $this->formBuilder->config($config);
            
            if ($this->formBuilder->validate()) {
                // Xử lý cập nhật dữ liệu thành công
                return redirect()->to(current_url())->with('success', 'Thông tin người dùng đã được cập nhật!');
            }
        }
        
        // Đặt dữ liệu cho form
        $this->formBuilder->config($config)->setData($userData);
        
        // Render form
        $data = [
            'title' => 'Cập nhật thông tin người dùng',
            'content' => $this->formBuilder->render(null, [], true),
        ];
        
        return view('examples/form_example', $data);
    }
    
    /**
     * Ví dụ form với trường chọn thời gian
     */
    public function timeExample()
    {
        // Cấu hình form với trường chọn thời gian
        $config = [
            'attributes' => [
                'action' => current_url(),
                'method' => 'post',
                'id' => 'time-form',
                'class' => 'needs-validation',
            ],
            'use_timepicker' => true,
            'fields' => [
                'event_name' => [
                    'type' => 'text',
                    'label' => 'Tên sự kiện',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nhập tên sự kiện',
                    ],
                    'rules' => 'required',
                ],
                'event_date' => [
                    'type' => 'date',
                    'label' => 'Ngày sự kiện',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'datepicker' => true,
                    'date_format' => 'dd/mm/yyyy',
                    'rules' => 'required',
                ],
                'start_time' => [
                    'type' => 'time',
                    'label' => 'Thời gian bắt đầu',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'time_format' => 'default',
                    'rules' => 'required',
                ],
                'end_time' => [
                    'type' => 'time',
                    'label' => 'Thời gian kết thúc',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'time_format' => 'seconds',
                    'rules' => 'required',
                ],
                'meeting_time' => [
                    'type' => 'timepicker',
                    'label' => 'Giờ họp (24h)',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'time_format' => '24hour',
                ],
                'deadline' => [
                    'type' => 'datetime',
                    'label' => 'Hạn chót',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'datetime_format' => 'dd/mm/yyyy HH:mm',
                    'rules' => 'required',
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Mô tả',
                    'attributes' => [
                        'class' => 'form-control',
                        'rows' => 3,
                    ],
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Lưu sự kiện',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                    ],
                ],
            ],
            'validation' => [
                'event_name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tên sự kiện không được để trống',
                    ],
                ],
                'event_date' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Ngày sự kiện không được để trống',
                    ],
                ],
                'start_time' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Thời gian bắt đầu không được để trống',
                    ],
                ],
                'end_time' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Thời gian kết thúc không được để trống',
                    ],
                ],
                'deadline' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Hạn chót không được để trống',
                    ],
                ],
            ],
        ];

        // Xử lý submit
        if ($this->request->getMethod() === 'post') {
            $this->formBuilder->config($config);
            
            if ($this->formBuilder->validate()) {
                // Xử lý dữ liệu thành công
                return redirect()->to(current_url())->with('success', 'Thông tin sự kiện đã được lưu!');
            }
        }
        
        // Render form
        $data = [
            'title' => 'Form chọn thời gian',
            'content' => $this->formBuilder->useTimepicker()->config($config)->render('form/default', [], true),
        ];
        
        return view('examples/form_example', $data);
    }
    
    /**
     * Ví dụ form với bảng sản phẩm
     */
    public function productTableExample()
    {
        // Dữ liệu mẫu cho bảng sản phẩm
        $productData = [
            [
                'id' => 1,
                'code' => 'SP001',
                'name' => 'Điện thoại iPhone 13 Pro Max',
                'category' => 'Điện thoại',
                'price' => 28990000,
                'quantity' => 50,
                'date_added' => '2023-06-15',
                'status' => 'in_stock'
            ],
            [
                'id' => 2,
                'code' => 'SP002',
                'name' => 'Laptop Dell XPS 15',
                'category' => 'Laptop',
                'price' => 42500000,
                'quantity' => 15,
                'date_added' => '2023-05-20',
                'status' => 'in_stock'
            ],
            [
                'id' => 3,
                'code' => 'SP003',
                'name' => 'Tai nghe Apple AirPods Pro',
                'category' => 'Phụ kiện',
                'price' => 4990000,
                'quantity' => 100,
                'date_added' => '2023-06-05',
                'status' => 'in_stock'
            ],
            [
                'id' => 4,
                'code' => 'SP004',
                'name' => 'Samsung Galaxy S23 Ultra',
                'category' => 'Điện thoại',
                'price' => 23990000,
                'quantity' => 30,
                'date_added' => '2023-07-10',
                'status' => 'in_stock'
            ],
            [
                'id' => 5,
                'code' => 'SP005',
                'name' => 'iPad Pro M2 11 inch',
                'category' => 'Máy tính bảng',
                'price' => 20990000,
                'quantity' => 0,
                'date_added' => '2023-04-15',
                'status' => 'out_of_stock'
            ],
            [
                'id' => 6,
                'code' => 'SP006',
                'name' => 'Màn hình Dell UltraSharp 27"',
                'category' => 'Màn hình',
                'price' => 12500000,
                'quantity' => 10,
                'date_added' => '2023-03-25',
                'status' => 'in_stock'
            ],
            [
                'id' => 7,
                'code' => 'SP007',
                'name' => 'Bàn phím cơ Logitech G Pro X',
                'category' => 'Phụ kiện',
                'price' => 3200000,
                'quantity' => 5,
                'date_added' => '2023-05-05',
                'status' => 'low_stock'
            ],
            [
                'id' => 8,
                'code' => 'SP008',
                'name' => 'Chuột không dây Razer Viper Ultimate',
                'category' => 'Phụ kiện',
                'price' => 2900000,
                'quantity' => 0,
                'date_added' => '2023-02-10',
                'status' => 'out_of_stock'
            ],
            [
                'id' => 9,
                'code' => 'SP009',
                'name' => 'Macbook Air M2',
                'category' => 'Laptop',
                'price' => 32500000,
                'quantity' => 20,
                'date_added' => '2023-07-15',
                'status' => 'in_stock'
            ],
            [
                'id' => 10,
                'code' => 'SP010',
                'name' => 'Loa Bluetooth Sony SRS-XB43',
                'category' => 'Âm thanh',
                'price' => 4490000,
                'quantity' => 8,
                'date_added' => '2023-06-20',
                'status' => 'low_stock'
            ],
        ];
        
        // Định nghĩa formatter cho cột giá
        $priceFormatter = function($value, $row, $index) {
            return number_format($value, 0, ',', '.') . ' đ';
        };
        
        // Định nghĩa formatter cho cột trạng thái
        $statusFormatter = function($value, $row, $index) {
            $badge = '';
            switch ($value) {
                case 'in_stock':
                    $badge = '<span class="badge bg-success">Còn hàng</span>';
                    break;
                case 'out_of_stock':
                    $badge = '<span class="badge bg-danger">Hết hàng</span>';
                    break;
                case 'low_stock':
                    $badge = '<span class="badge bg-warning">Sắp hết</span>';
                    break;
                default:
                    $badge = '<span class="badge bg-secondary">' . $value . '</span>';
            }
            return $badge;
        };
        
        // Định nghĩa formatter cho cột hành động
        $actionFormatter = function($value, $row, $index) {
            return '
                <div class="btn-group" role="group">
                    <a href="javascript:void(0)" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                </div>
            ';
        };
        
        // Định nghĩa formatter cho cột số lượng
        $quantityFormatter = function($value, $row, $index) {
            if ($value <= 0) {
                return '<span class="text-danger fw-bold">0</span>';
            } elseif ($value < 10) {
                return '<span class="text-warning fw-bold">' . $value . '</span>';
            } else {
                return '<span class="text-success">' . $value . '</span>';
            }
        };
        
        // Cấu hình form với trường bảng sản phẩm
        $config = [
            'attributes' => [
                'action' => current_url(),
                'method' => 'post',
                'id' => 'product-table-form',
            ],
            'use_datatable' => true,
            'fields' => [
                'heading' => [
                    'type' => 'text',
                    'label' => 'Tiêu đề',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nhập tiêu đề',
                    ],
                    'value' => 'Danh sách sản phẩm'
                ],
                'products_table' => [
                    'type' => 'table',
                    'label' => 'Danh sách sản phẩm',
                    'table_class' => 'table table-striped table-hover',
                    'data' => $productData,
                    'use_datatable' => true,
                    'pagination' => true,
                    'page_length' => 5,
                    'searching' => true,
                    'ordering' => true,
                    'columns' => [
                        [
                            'title' => 'ID',
                            'field' => 'id',
                            'width' => '5%',
                        ],
                        [
                            'title' => 'Mã SP',
                            'field' => 'code',
                            'width' => '8%',
                        ],
                        [
                            'title' => 'Tên sản phẩm',
                            'field' => 'name',
                            'width' => '25%',
                        ],
                        [
                            'title' => 'Danh mục',
                            'field' => 'category',
                            'width' => '10%',
                        ],
                        [
                            'title' => 'Giá bán',
                            'field' => 'price',
                            'width' => '12%',
                            'formatter' => $priceFormatter,
                        ],
                        [
                            'title' => 'Số lượng',
                            'field' => 'quantity',
                            'width' => '8%',
                            'formatter' => $quantityFormatter,
                        ],
                        [
                            'title' => 'Ngày nhập',
                            'field' => 'date_added',
                            'width' => '10%',
                        ],
                        [
                            'title' => 'Trạng thái',
                            'field' => 'status',
                            'width' => '12%',
                            'formatter' => $statusFormatter,
                        ],
                        [
                            'title' => 'Thao tác',
                            'width' => '10%',
                            'formatter' => $actionFormatter,
                        ],
                    ],
                ],
                'notes' => [
                    'type' => 'textarea',
                    'label' => 'Ghi chú',
                    'attributes' => [
                        'class' => 'form-control',
                        'rows' => 3,
                    ],
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Cập nhật',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                    ],
                ],
            ],
        ];

        // Xử lý submit
        if ($this->request->getMethod() === 'post') {
            $this->formBuilder->config($config);
            
            if ($this->formBuilder->validate()) {
                // Xử lý dữ liệu thành công
                return redirect()->to(current_url())->with('success', 'Dữ liệu đã được cập nhật!');
            }
        }
        
        // Render form
        $data = [
            'title' => 'Form với bảng sản phẩm',
            'content' => $this->formBuilder->useDatatable()->config($config)->render('form/default', [], true),
        ];
        
        return view('examples/form_example', $data);
    }

    /**
     * Ví dụ form với timeline sự kiện
     */
    public function timelineExample()
    {
        // Dữ liệu mẫu cho timeline sự kiện
        $timelineData = [
            [
                'id' => 1,
                'title' => 'Khởi động dự án',
                'date' => '2023-03-15',
                'time' => '09:00',
                'description' => 'Cuộc họp khởi động dự án với toàn bộ các bên liên quan.',
                'status' => 'completed',
                'color' => 'success',
                'icon' => 'bi-check-circle-fill'
            ],
            [
                'id' => 2,
                'title' => 'Thiết kế UI/UX',
                'date' => '2023-04-10',
                'time' => '13:30',
                'description' => 'Hoàn thành và trình bày bản thiết kế UI/UX cho khách hàng.',
                'status' => 'completed',
                'color' => 'success',
                'icon' => 'bi-check-circle-fill'
            ],
            [
                'id' => 3,
                'title' => 'Phát triển back-end',
                'date' => '2023-05-20',
                'time' => '10:00',
                'description' => 'Hoàn thành phát triển các module back-end và API.',
                'status' => 'completed',
                'color' => 'success',
                'icon' => 'bi-check-circle-fill'
            ],
            [
                'id' => 4,
                'title' => 'Phát triển front-end',
                'date' => '2023-06-30',
                'time' => '15:00',
                'description' => 'Hoàn thành phát triển giao diện người dùng.',
                'status' => 'in_progress',
                'color' => 'primary',
                'icon' => 'bi-hourglass-split'
            ],
            [
                'id' => 5,
                'title' => 'Kiểm thử hệ thống',
                'date' => '2023-07-25',
                'time' => '09:00',
                'description' => 'Kiểm thử toàn bộ hệ thống trước khi triển khai.',
                'status' => 'pending',
                'color' => 'secondary',
                'icon' => 'bi-clock'
            ],
            [
                'id' => 6,
                'title' => 'Triển khai hệ thống',
                'date' => '2023-08-15',
                'time' => '09:00',
                'description' => 'Triển khai hệ thống lên môi trường sản xuất.',
                'status' => 'pending',
                'color' => 'secondary',
                'icon' => 'bi-clock'
            ],
            [
                'id' => 7,
                'title' => 'Đào tạo người dùng',
                'date' => '2023-08-25',
                'time' => '13:30',
                'description' => 'Tổ chức khóa đào tạo cho người dùng cuối.',
                'status' => 'pending',
                'color' => 'secondary',
                'icon' => 'bi-clock'
            ],
            [
                'id' => 8,
                'title' => 'Bàn giao dự án',
                'date' => '2023-09-10',
                'time' => '10:00',
                'description' => 'Bàn giao dự án chính thức cho khách hàng.',
                'status' => 'pending',
                'color' => 'secondary',
                'icon' => 'bi-clock'
            ]
        ];
        
        // Hiển thị timeline
        $timelineHtml = '<div class="timeline-container p-4">';
        $timelineHtml .= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">';
        $timelineHtml .= '<style>
            .timeline-container {
                position: relative;
                max-width: 100%;
            }
            .timeline {
                position: relative;
                max-width: 100%;
            }
            .timeline::after {
                content: "";
                position: absolute;
                width: 3px;
                background-color: #dee2e6;
                top: 0;
                bottom: 0;
                left: 50%;
                margin-left: -1.5px;
            }
            .timeline-item {
                padding: 10px 40px;
                position: relative;
                width: 50%;
                box-sizing: border-box;
            }
            .timeline-item::after {
                content: "";
                position: absolute;
                width: 25px;
                height: 25px;
                right: -12.5px;
                top: 20px;
                border-radius: 50%;
                z-index: 1;
                box-shadow: 0 0 0 3px #fff;
            }
            .left {
                left: 0;
            }
            .right {
                left: 50%;
            }
            .left::before {
                content: " ";
                position: absolute;
                top: 20px;
                width: 0;
                z-index: 1;
                right: 30px;
                border: medium solid #fff;
                border-width: 10px 0 10px 10px;
                border-color: transparent transparent transparent #fff;
            }
            .right::before {
                content: " ";
                position: absolute;
                top: 20px;
                width: 0;
                z-index: 1;
                left: 30px;
                border: medium solid #fff;
                border-width: 10px 10px 10px 0;
                border-color: transparent #fff transparent transparent;
            }
            .right::after {
                left: -12.5px;
            }
            .timeline-content {
                padding: 20px;
                background-color: white;
                position: relative;
                border-radius: 6px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .timeline-date {
                font-weight: bold;
            }
            .timeline-title {
                font-size: 1.2rem;
                margin-top: 5px;
                margin-bottom: 10px;
            }
            @media screen and (max-width: 768px) {
                .timeline::after {
                    left: 31px;
                }
                .timeline-item {
                    width: 100%;
                    padding-left: 70px;
                    padding-right: 25px;
                }
                .timeline-item::before {
                    left: 60px;
                    border-width: 10px 10px 10px 0;
                    border-color: transparent #fff transparent transparent;
                }
                .left::after, .right::after {
                    left: 18px;
                }
                .right {
                    left: 0%;
                }
            }
        </style>';
        
        // Timeline HTML
        $timelineHtml .= '<div class="timeline">';
        
        foreach ($timelineData as $index => $item) {
            $position = $index % 2 == 0 ? 'left' : 'right';
            $timelineHtml .= '<div class="timeline-item ' . $position . '">';
            $timelineHtml .= '<div class="timeline-content border-' . $item['color'] . '">';
            $timelineHtml .= '<div class="timeline-date text-' . $item['color'] . '">' . date('d/m/Y', strtotime($item['date'])) . ' - ' . $item['time'] . '</div>';
            $timelineHtml .= '<h5 class="timeline-title">' . $item['title'] . '</h5>';
            $timelineHtml .= '<p>' . $item['description'] . '</p>';
            $timelineHtml .= '<span class="badge bg-' . $item['color'] . '">';
            $timelineHtml .= '<i class="bi ' . $item['icon'] . '"></i> ';
            
            switch ($item['status']) {
                case 'completed':
                    $timelineHtml .= 'Hoàn thành';
                    break;
                case 'in_progress':
                    $timelineHtml .= 'Đang thực hiện';
                    break;
                case 'pending':
                    $timelineHtml .= 'Chờ xử lý';
                    break;
                default:
                    $timelineHtml .= $item['status'];
            }
            
            $timelineHtml .= '</span>';
            $timelineHtml .= '</div>';
            $timelineHtml .= '</div>';
        }
        
        $timelineHtml .= '</div></div>';
        
        // Cấu hình form thêm sự kiện vào timeline
        $config = [
            'attributes' => [
                'action' => current_url(),
                'method' => 'post',
                'id' => 'timeline-form',
            ],
            'use_timepicker' => true,
            'fields' => [
                'project_name' => [
                    'type' => 'text',
                    'label' => 'Tên dự án',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nhập tên dự án',
                    ],
                    'value' => 'Dự án phát triển website',
                ],
                'event_title' => [
                    'type' => 'text',
                    'label' => 'Tên sự kiện mới',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nhập tên sự kiện',
                    ],
                    'rules' => 'required',
                ],
                'event_date' => [
                    'type' => 'date',
                    'label' => 'Ngày diễn ra',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'datepicker' => true,
                    'rules' => 'required',
                ],
                'event_time' => [
                    'type' => 'time',
                    'label' => 'Thời gian diễn ra',
                    'attributes' => [
                        'class' => 'form-control',
                    ],
                    'time_format' => 'default',
                    'rules' => 'required',
                ],
                'event_description' => [
                    'type' => 'textarea',
                    'label' => 'Mô tả sự kiện',
                    'attributes' => [
                        'class' => 'form-control',
                        'rows' => 3,
                    ],
                ],
                'event_status' => [
                    'type' => 'select',
                    'label' => 'Trạng thái',
                    'options' => [
                        'pending' => 'Chờ xử lý',
                        'in_progress' => 'Đang thực hiện',
                        'completed' => 'Hoàn thành',
                    ],
                    'attributes' => [
                        'class' => 'form-select',
                    ],
                    'value' => 'pending',
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Thêm sự kiện vào timeline',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                    ],
                ],
            ],
            'validation' => [
                'event_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tên sự kiện không được để trống',
                    ],
                ],
                'event_date' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Ngày diễn ra không được để trống',
                    ],
                ],
                'event_time' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Thời gian diễn ra không được để trống',
                    ],
                ],
            ],
        ];

        // Xử lý submit
        if ($this->request->getMethod() === 'post') {
            $this->formBuilder->config($config);
            
            if ($this->formBuilder->validate()) {
                // Xử lý dữ liệu thành công - trong thực tế sẽ thêm vào timeline
                return redirect()->to(current_url())->with('success', 'Sự kiện mới đã được thêm vào timeline!');
            }
        }
        
        // Render form với FormBuilder
        $formHtml = $this->formBuilder->useTimepicker()->config($config)->render('form/default', [], true);
        
        // Chèn timeline vào form đã tạo (sau trường đầu tiên)
        $formHtml = preg_replace(
            '/<\/div>\s*(<div class="form-group mb-3">)/', 
            '</div>' . 
            '<div class="form-group mb-3">' .
            '<label class="form-label">Timeline dự án</label>' .
            $timelineHtml .
            '</div>' . 
            '$1', 
            $formHtml, 
            1
        );
        
        // Render view
        $data = [
            'title' => 'Form với timeline sự kiện',
            'content' => $formHtml,
        ];
        
        return view('examples/form_example', $data);
    }

    /**
     * Ví dụ form upload file
     */
    public function uploadExample()
    {
        // Cấu hình form upload
        $config = [
            'attributes' => [
                'action' => current_url(),
                'method' => 'post',
                'id' => 'upload-form',
                'enctype' => 'multipart/form-data', // Rất quan trọng cho form upload
            ],
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'label' => 'Tiêu đề hồ sơ',
                    'attributes' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nhập tiêu đề hồ sơ',
                    ],
                    'rules' => 'required',
                ],
                'description' => [
                    'type' => 'textarea',
                    'label' => 'Mô tả',
                    'attributes' => [
                        'class' => 'form-control',
                        'rows' => 3,
                    ],
                ],
                'profile_image' => [
                    'type' => 'file',
                    'label' => 'Ảnh đại diện',
                    'attributes' => [
                        'class' => 'form-control image-preview-input',
                        'accept' => 'image/*',
                        'data-preview' => 'profile-image-preview',
                    ],
                    'help' => 'Chấp nhận các file định dạng: jpg, jpeg, png, gif. Kích thước tối đa: 2MB',
                    'rules' => 'uploaded[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/png,image/gif]|max_size[profile_image,2048]',
                ],
                'documents' => [
                    'type' => 'file',
                    'label' => 'Tài liệu đính kèm',
                    'attributes' => [
                        'class' => 'form-control',
                        'multiple' => 'multiple',
                        'accept' => '.pdf,.doc,.docx,.xls,.xlsx',
                    ],
                    'help' => 'Chấp nhận các file: PDF, Word, Excel. Kích thước tối đa: 5MB mỗi file',
                ],
                'product_images' => [
                    'type' => 'file',
                    'label' => 'Hình ảnh sản phẩm (nhiều)',
                    'attributes' => [
                        'class' => 'form-control multiple-image-preview-input',
                        'multiple' => 'multiple',
                        'accept' => 'image/*',
                        'data-preview' => 'product-images-preview',
                    ],
                    'help' => 'Tối đa 5 ảnh, mỗi ảnh không quá 1MB',
                ],
                'upload_type' => [
                    'type' => 'select',
                    'label' => 'Loại tài liệu',
                    'options' => [
                        'personal' => 'Tài liệu cá nhân',
                        'business' => 'Tài liệu doanh nghiệp',
                        'other' => 'Khác',
                    ],
                    'attributes' => [
                        'class' => 'form-select',
                    ],
                    'value' => 'personal',
                ],
                'submit' => [
                    'type' => 'submit',
                    'value' => 'Tải lên',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                    ],
                ],
            ],
            'validation' => [
                'title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tiêu đề hồ sơ không được để trống',
                    ],
                ],
                'profile_image' => [
                    'rules' => 'uploaded[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/png,image/gif]|max_size[profile_image,2048]',
                    'errors' => [
                        'uploaded' => 'Vui lòng chọn ảnh đại diện',
                        'mime_in' => 'Ảnh đại diện phải có định dạng: jpg, jpeg, png, gif',
                        'max_size' => 'Kích thước ảnh không được vượt quá 2MB',
                    ],
                ],
            ],
        ];

        // Xử lý submit
        if ($this->request->getMethod() === 'post') {
            $this->formBuilder->config($config);
            
            if ($this->formBuilder->validate()) {
                // Lấy thông tin files
                $profileImage = $this->request->getFile('profile_image');
                $documents = $this->request->getFileMultiple('documents');
                $productImages = $this->request->getFileMultiple('product_images');
                
                // Trong thực tế, đây là nơi xử lý upload files
                // Ví dụ:
                if ($profileImage->isValid() && !$profileImage->hasMoved()) {
                    // Tạo tên file ngẫu nhiên
                    $newName = $profileImage->getRandomName();
                    
                    // Thông báo thành công với tên file
                    session()->setFlashdata('profile_image', $newName);
                }
                
                // Xử lý documents (nhiều file)
                $docNames = [];
                if ($documents) {
                    foreach ($documents as $doc) {
                        if ($doc->isValid() && !$doc->hasMoved()) {
                            $docNames[] = $doc->getName();
                            // Trong thực tế: $doc->move(WRITEPATH . 'uploads/documents', $doc->getRandomName());
                        }
                    }
                    
                    if (!empty($docNames)) {
                        session()->setFlashdata('documents', $docNames);
                    }
                }
                
                // Xử lý product images (nhiều file)
                $imageNames = [];
                if ($productImages) {
                    foreach ($productImages as $img) {
                        if ($img->isValid() && !$img->hasMoved()) {
                            $imageNames[] = $img->getName();
                            // Trong thực tế: $img->move(WRITEPATH . 'uploads/products', $img->getRandomName());
                        }
                    }
                    
                    if (!empty($imageNames)) {
                        session()->setFlashdata('product_images', $imageNames);
                    }
                }
                
                return redirect()->to(current_url())->with('success', 'Tải lên file thành công!');
            }
        }
        
        // Render form
        $formHtml = $this->formBuilder->config($config)->render('form/default', [], true);
        
        // Tạo HTML mẫu để hiển thị các file đã upload (nếu có)
        $uploadResultHtml = '';
        if (session()->has('success') && (session()->has('profile_image') || session()->has('documents') || session()->has('product_images'))) {
            $uploadResultHtml = '<div class="card mt-4 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Kết quả tải lên</h5>
                </div>
                <div class="card-body">';
            
            // Hiển thị thông tin ảnh đại diện
            if (session()->has('profile_image')) {
                $uploadResultHtml .= '<div class="mb-3">
                    <h6>Ảnh đại diện:</h6>
                    <p><strong>Tên file:</strong> ' . session('profile_image') . '</p>
                    <div class="alert alert-info">
                        Trong môi trường thực tế, file sẽ được lưu vào thư mục uploads và có thể hiển thị hình ảnh ở đây.
                    </div>
                </div>';
            }
            
            // Hiển thị thông tin tài liệu
            if (session()->has('documents') && !empty(session('documents'))) {
                $uploadResultHtml .= '<div class="mb-3">
                    <h6>Tài liệu đã tải lên:</h6>
                    <ul>';
                
                foreach (session('documents') as $doc) {
                    $uploadResultHtml .= '<li>' . $doc . '</li>';
                }
                
                $uploadResultHtml .= '</ul>
                </div>';
            }
            
            // Hiển thị thông tin ảnh sản phẩm
            if (session()->has('product_images') && !empty(session('product_images'))) {
                $uploadResultHtml .= '<div class="mb-3">
                    <h6>Hình ảnh sản phẩm đã tải lên:</h6>
                    <ul>';
                
                foreach (session('product_images') as $img) {
                    $uploadResultHtml .= '<li>' . $img . '</li>';
                }
                
                $uploadResultHtml .= '</ul>
                </div>';
            }
            
            $uploadResultHtml .= '</div></div>';
        }
        
        // Thêm div hiển thị xem trước hình ảnh
        $previewHtml = '
        <style>
            .image-preview-container {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 20px;
            }
            .image-preview {
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 10px;
                width: 100%;
                min-height: 300px;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f8f9fa;
                position: relative;
            }
            .image-preview img {
                max-width: 100%;
                max-height: 280px;
                object-fit: contain;
            }
            .preview-title {
                margin-bottom: 10px;
                font-weight: bold;
            }
            .slideshow-container {
                width: 100%;
                position: relative;
                margin: auto;
                height: 400px;
                overflow: hidden;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .slide {
                display: none;
                width: 100%;
                height: 100%;
                text-align: center;
                align-items: center;
                justify-content: center;
                position: absolute;
                top: 0;
                left: 0;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.5s ease-in-out;
                padding: 10px;
            }
            .slide.active {
                display: flex;
                opacity: 1;
                transform: translateX(0);
            }
            .slide.prev {
                transform: translateX(-100%);
            }
            .slide.next {
                transform: translateX(100%);
            }
            .slide img {
                max-width: 100%;
                max-height: 100%;
                width: auto;
                height: auto;
                object-fit: contain;
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            .prev, .next {
                cursor: pointer;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 40px;
                height: 40px;
                background-color: rgba(0,0,0,0.5);
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                transition: all 0.3s ease;
                z-index: 2;
            }
            .prev:hover, .next:hover {
                background-color: rgba(0,0,0,0.8);
                transform: translateY(-50%) scale(1.1);
            }
            .prev {
                left: 10px;
            }
            .next {
                right: 10px;
            }
            .slide-number {
                position: absolute;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                background-color: rgba(0,0,0,0.5);
                color: white;
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 12px;
                z-index: 2;
            }
            .dots-container {
                text-align: center;
                padding: 10px 0;
                margin-top: 10px;
            }
            .dot {
                cursor: pointer;
                height: 10px;
                width: 10px;
                margin: 0 4px;
                background-color: #bbb;
                border-radius: 50%;
                display: inline-block;
                transition: all 0.3s ease;
            }
            .dot.active, .dot:hover {
                background-color: #333;
                transform: scale(1.2);
            }
            .preview-container {
                flex: 1;
                min-width: 320px;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .preview-title {
                margin-bottom: 15px;
                font-weight: bold;
                color: #333;
                font-size: 1.1rem;
                padding-bottom: 10px;
                border-bottom: 2px solid #f0f0f0;
            }
            .preview-placeholder {
                color: #6c757d;
                font-style: italic;
                font-size: 0.9rem;
                padding: 20px;
                background-color: #f8f9fa;
                border-radius: 4px;
                border: 1px dashed #ddd;
            }
            .form-container {
                flex: 1;
                min-width: 320px;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .form-row {
                display: flex;
                flex-wrap: wrap;
                gap: 30px;
                margin-bottom: 30px;
            }
            @media (max-width: 768px) {
                .form-row {
                    flex-direction: column;
                }
                .preview-container,
                .form-container {
                    width: 100%;
                }
                .slideshow-container {
                    height: 300px;
                }
            }
        </style>
        
        <div class="form-row">
            <div class="preview-container">
                <div class="preview-title">Xem trước ảnh</div>
                
                <div id="profile-slideshow" class="slideshow-container">
                    <div class="slide active">
                        <span class="preview-placeholder">Chưa có hình ảnh đại diện được chọn</span>
                    </div>
                    <a class="prev" onclick="changeSlide(-1, \'profile\')">&#10094;</a>
                    <a class="next" onclick="changeSlide(1, \'profile\')">&#10095;</a>
                    <div class="slide-number">Ảnh 1/1</div>
                </div>
                <div id="profile-dots" class="dots-container"></div>
                
                <div class="preview-title mt-4">Xem trước ảnh sản phẩm</div>
                <div id="product-slideshow" class="slideshow-container">
                    <div class="slide active">
                        <span class="preview-placeholder">Chưa có hình ảnh sản phẩm được chọn</span>
                    </div>
                    <a class="prev" onclick="changeSlide(-1, \'product\')">&#10094;</a>
                    <a class="next" onclick="changeSlide(1, \'product\')">&#10095;</a>
                    <div class="slide-number">Ảnh 1/1</div>
                </div>
                <div id="product-dots" class="dots-container"></div>
            </div>
            
            <div class="form-container">
                <!-- Đây là nơi để chèn form -->
            </div>
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            let profileSlideIndex = 1;
            let productSlideIndex = 1;
            
            // Xử lý xem trước ảnh đại diện
            const profileImageInput = document.querySelector("input[data-preview=\'profile-image-preview\']");
            const profileSlideshow = document.getElementById("profile-slideshow");
            const profileDots = document.getElementById("profile-dots");
            
            if (profileImageInput && profileSlideshow) {
                profileImageInput.addEventListener("change", function() {
                    if (this.files && this.files.length > 0) {
                        // Xóa slides hiện tại
                        profileSlideshow.innerHTML = "";
                        profileDots.innerHTML = "";
                        
                        // Thêm slide mới
                        const file = this.files[0];
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const slide = document.createElement("div");
                            slide.className = "slide active";
                            
                            const img = document.createElement("img");
                            img.src = e.target.result;
                            slide.appendChild(img);
                            
                            profileSlideshow.appendChild(slide);
                            
                            // Thêm nút điều hướng
                            const prevBtn = document.createElement("a");
                            prevBtn.className = "prev";
                            prevBtn.innerHTML = "&#10094;";
                            prevBtn.setAttribute("onclick", "changeSlide(-1, \'profile\')");
                            
                            const nextBtn = document.createElement("a");
                            nextBtn.className = "next";
                            nextBtn.innerHTML = "&#10095;";
                            nextBtn.setAttribute("onclick", "changeSlide(1, \'profile\')");
                            
                            profileSlideshow.appendChild(prevBtn);
                            profileSlideshow.appendChild(nextBtn);
                            
                            // Thêm số trang
                            const slideNumber = document.createElement("div");
                            slideNumber.className = "slide-number";
                            slideNumber.textContent = "Ảnh 1/1";
                            profileSlideshow.appendChild(slideNumber);
                            
                            // Thêm dot
                            const dot = document.createElement("span");
                            dot.className = "dot active";
                            dot.setAttribute("onclick", "currentSlide(1, \'profile\')");
                            profileDots.appendChild(dot);
                            
                            profileSlideIndex = 1;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Nếu không có file nào được chọn, hiển thị placeholder
                        profileSlideshow.innerHTML = `
                            <div class="slide active">
                                <span class="preview-placeholder">Chưa có hình ảnh đại diện được chọn</span>
                            </div>
                            <a class="prev" onclick="changeSlide(-1, \'profile\')">&#10094;</a>
                            <a class="next" onclick="changeSlide(1, \'profile\')">&#10095;</a>
                            <div class="slide-number">Ảnh 1/1</div>
                        `;
                        profileDots.innerHTML = "";
                    }
                });
            }
            
            // Xử lý xem trước nhiều ảnh sản phẩm
            const productImagesInput = document.querySelector("input[data-preview=\'product-images-preview\']");
            const productSlideshow = document.getElementById("product-slideshow");
            const productDots = document.getElementById("product-dots");
            
            if (productImagesInput && productSlideshow) {
                productImagesInput.addEventListener("change", function() {
                    if (this.files && this.files.length > 0) {
                        // Xóa slides hiện tại
                        productSlideshow.innerHTML = "";
                        productDots.innerHTML = "";
                        
                        const fileCount = this.files.length;
                        
                        // Thêm slides mới
                        Array.from(this.files).forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const slide = document.createElement("div");
                                slide.className = "slide" + (index === 0 ? " active" : "");
                                
                                const img = document.createElement("img");
                                img.src = e.target.result;
                                slide.appendChild(img);
                                
                                productSlideshow.appendChild(slide);
                                
                                // Thêm dot
                                const dot = document.createElement("span");
                                dot.className = "dot" + (index === 0 ? " active" : "");
                                dot.setAttribute("onclick", `currentSlide(${index + 1}, \'product\')`);
                                productDots.appendChild(dot);
                                
                                // Nếu là slide cuối cùng, thêm nút điều hướng và cập nhật số trang
                                if (index === fileCount - 1) {
                                    // Thêm nút điều hướng
                                    const prevBtn = document.createElement("a");
                                    prevBtn.className = "prev";
                                    prevBtn.innerHTML = "&#10094;";
                                    prevBtn.setAttribute("onclick", "changeSlide(-1, \'product\')");
                                    
                                    const nextBtn = document.createElement("a");
                                    nextBtn.className = "next";
                                    nextBtn.innerHTML = "&#10095;";
                                    nextBtn.setAttribute("onclick", "changeSlide(1, \'product\')");
                                    
                                    productSlideshow.appendChild(prevBtn);
                                    productSlideshow.appendChild(nextBtn);
                                    
                                    // Thêm số trang
                                    const slideNumber = document.createElement("div");
                                    slideNumber.className = "slide-number";
                                    slideNumber.textContent = "Ảnh 1/" + fileCount;
                                    productSlideshow.appendChild(slideNumber);
                                    
                                    productSlideIndex = 1;
                                }
                            };
                            reader.readAsDataURL(file);
                        });
                    } else {
                        // Nếu không có file nào được chọn, hiển thị placeholder
                        productSlideshow.innerHTML = `
                            <div class="slide active">
                                <span class="preview-placeholder">Chưa có hình ảnh sản phẩm được chọn</span>
                            </div>
                            <a class="prev" onclick="changeSlide(-1, \'product\')">&#10094;</a>
                            <a class="next" onclick="changeSlide(1, \'product\')">&#10095;</a>
                            <div class="slide-number">Ảnh 1/1</div>
                        `;
                        productDots.innerHTML = "";
                    }
                });
            }
            
            // Thêm hàm điều khiển slideshow vào window để có thể gọi từ HTML
            window.changeSlide = function(n, type) {
                let slideIndex = type === "profile" ? profileSlideIndex : productSlideIndex;
                let slideshow = document.getElementById(type + "-slideshow");
                let dots = document.getElementById(type + "-dots");
                
                let slides = slideshow.getElementsByClassName("slide");
                let dotElements = dots.getElementsByClassName("dot");
                
                if (slides.length <= 1) return;
                
                slideIndex += n;
                if (slideIndex > slides.length) {slideIndex = 1}
                if (slideIndex < 1) {slideIndex = slides.length}
                
                // Ẩn tất cả slides
                for (let i = 0; i < slides.length; i++) {
                    slides[i].classList.remove("active");
                }
                
                // Bỏ active tất cả dots
                for (let i = 0; i < dotElements.length; i++) {
                    dotElements[i].classList.remove("active");
                }
                
                // Hiển thị slide hiện tại
                slides[slideIndex-1].classList.add("active");
                
                // Active dot hiện tại
                if (dotElements.length > 0) {
                    dotElements[slideIndex-1].classList.add("active");
                }
                
                // Cập nhật số trang
                let slideNumber = slideshow.querySelector(".slide-number");
                if (slideNumber) {
                    slideNumber.textContent = "Ảnh " + slideIndex + "/" + slides.length;
                }
                
                // Cập nhật biến slideIndex
                if (type === "profile") {
                    profileSlideIndex = slideIndex;
                } else {
                    productSlideIndex = slideIndex;
                }
            };
            
            window.currentSlide = function(n, type) {
                let slideshow = document.getElementById(type + "-slideshow");
                let dots = document.getElementById(type + "-dots");
                
                let slides = slideshow.getElementsByClassName("slide");
                let dotElements = dots.getElementsByClassName("dot");
                
                if (slides.length <= 1) return;
                
                // Ẩn tất cả slides
                for (let i = 0; i < slides.length; i++) {
                    slides[i].classList.remove("active");
                }
                
                // Bỏ active tất cả dots
                for (let i = 0; i < dotElements.length; i++) {
                    dotElements[i].classList.remove("active");
                }
                
                // Hiển thị slide chỉ định
                slides[n-1].classList.add("active");
                
                // Active dot chỉ định
                dotElements[n-1].classList.add("active");
                
                // Cập nhật số trang
                let slideNumber = slideshow.querySelector(".slide-number");
                if (slideNumber) {
                    slideNumber.textContent = "Ảnh " + n + "/" + slides.length;
                }
                
                // Cập nhật biến slideIndex
                if (type === "profile") {
                    profileSlideIndex = n;
                } else {
                    productSlideIndex = n;
                }
            };
        });
        </script>';
        
        // Chèn các HTML xem trước hình ảnh và kết quả upload vào form 
        if (!empty($uploadResultHtml)) {
            // Chèn form vào div "form-container"
            $formHtml = str_replace('<div class="form-container">', '<div class="form-container">' . $formHtml, $previewHtml);
            $formHtml = $uploadResultHtml . $formHtml;
        } else {
            // Chèn form vào div "form-container"
            $formHtml = str_replace('<div class="form-container">', '<div class="form-container">' . $formHtml, $previewHtml);
        }
        
        $data = [
            'title' => 'Form Upload File',
            'content' => $formHtml,
        ];
        
        return view('examples/form_example', $data);
    }
} 