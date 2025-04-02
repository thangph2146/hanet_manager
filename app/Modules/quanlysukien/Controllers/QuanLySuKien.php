<?php

namespace App\Modules\quanlysukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlysukien\Models\SuKienModel;
use App\Modules\quanlysukien\Models\LoaiSuKienModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Alert;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\I18n\Time;
use App\Modules\quanlysukien\Traits\ExportTrait;
use App\Modules\quanlysukien\Traits\RelationTrait;

class QuanLySuKien extends BaseController
{
    use ResponseTrait;
    use ExportTrait;
    use RelationTrait;
    
    protected $model;
    protected $loaiSuKienModel;
    protected $breadcrumb;
    protected $alert;
    protected $moduleUrl;
    protected $title;
    protected $title_home;
    protected $module_name = 'quanlysukien';
    protected $controller_name = 'QuanLySuKien';
    protected $primary_key = 'su_kien_id';
    // Search
    protected $field_sort = 'thoi_gian_bat_dau';
    protected $field_order = 'DESC';

    // Export
    protected $export_excel = 'danh_sach_su_kien_excel';
    protected $export_excel_title = 'DANH SÁCH SỰ KIỆN (Excel)';

    protected $export_pdf = 'danh_sach_su_kien_pdf';
    protected $export_pdf_title = 'DANH SÁCH SỰ KIỆN (PDF)';

    protected $export_excel_deleted = 'danh_sach_su_kien_da_xoa_excel';
    protected $export_excel_deleted_title = 'DANH SÁCH SỰ KIỆN ĐÃ XÓA (Excel)';

    protected $export_pdf_deleted = 'danh_sach_su_kien_da_xoa_pdf';
    protected $export_pdf_deleted_title = 'DANH SÁCH SỰ KIỆN ĐÃ XÓA (PDF)';

    protected $pager_only = [
        'keyword', 
        'perPage', 
        'sort', 
        'order', 
        'loai_su_kien_id',
        'status',
        'hinh_thuc'
    ];

    protected $sukien;
    protected $validation;
    protected $masterScript;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');
        
        // Khởi tạo các thành phần cần thiết
        $this->model = new \App\Modules\quanlysukien\Models\SuKienModel();
        $this->breadcrumb = new \App\Libraries\Breadcrumb();
        $this->alert = new \App\Libraries\Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
        $this->title = 'Sự kiện';
        $this->title_home = 'Danh sách sự kiện';
        
        // Khởi tạo thư viện MasterScript với module_name
        $masterScriptClass = "\App\Modules\\" . $this->module_name . '\Libraries\MasterScript';
        $this->masterScript = new $masterScriptClass($this->module_name);
        
        // Khởi tạo các model quan hệ
        $this->initializeRelationTrait();
        
        // Khởi tạo model loại sự kiện nếu cần
        if (class_exists('App\Modules\loaisukien\Models\LoaiSuKienModel')) {
            $this->loaiSuKienModel = new \App\Modules\loaisukien\Models\LoaiSuKienModel();
        }
        
        // Khởi tạo validation
        $this->validation = \Config\Services::validation();
        
        // Đăng ký các quy tắc validation tùy chỉnh
        $this->setCustomValidationRules();
        
        // Các thuộc tính khác
        $this->pageTitle = 'Quản lý sự kiện';
        $this->viewPrefix = 'App\Modules\quanlysukien\Views';
        $this->routePrefix = 'quanlysukien';
        
        // Thiết lập tên file xuất dữ liệu
        $this->export_excel = 'danh_sach_su_kien_' . date('dmY_His') . '.xlsx';
        $this->export_excel_deleted = 'danh_sach_su_kien_da_xoa_' . date('dmY_His') . '.xlsx';
        $this->export_pdf = 'danh_sach_su_kien_' . date('dmY_His') . '.pdf';
        $this->export_pdf_deleted = 'danh_sach_su_kien_da_xoa_' . date('dmY_His') . '.pdf';
        
        // Thiết lập tiêu đề xuất dữ liệu
        $this->export_excel_title = 'DANH SÁCH SỰ KIỆN';
        $this->export_excel_deleted_title = 'DANH SÁCH SỰ KIỆN ĐÃ XÓA';
        $this->export_pdf_title = 'DANH SÁCH SỰ KIỆN';
        $this->export_pdf_deleted_title = 'DANH SÁCH SỰ KIỆN ĐÃ XÓA';
        
        // Đảm bảo sukien model được khởi tạo 
        $this->sukien = $this->model;
    }
    
    /**
     * Thiết lập các quy tắc validate tùy chỉnh
     */
    protected function setCustomValidationRules()
    {
        // Lấy validation service từ container
        $validation = \Config\Services::validation();
        
        // Không cần reset tại đây vì các rule đã được thiết lập trong Config/Validation.php
        
        // Đảm bảo các validation rule đã được đăng ký đúng cách trong app/Config/Validation.php
        // Bao gồm datetime_greater_than và required_if
    }

    /**
     * Phương thức validate thời gian kết thúc phải lớn hơn thời gian bắt đầu
     *
     * @param string $str Giá trị cần kiểm tra
     * @param string $field Tên trường tham chiếu
     * @param array $data Dữ liệu từ form
     * @return bool
     */
    public function validateDatetimeGreaterThan(string $str, string $field, array $data): bool
    {
        // Lấy tên trường cần so sánh từ tham số trong rule
        // datetime_greater_than[thoi_gian_bat_dau] -> thoi_gian_bat_dau
        $compareField = preg_replace('/.*\[(.*)\]/', '$1', $field);
        
        // Nếu trường so sánh không tồn tại trong dữ liệu, return false
        if (!isset($data[$compareField])) {
            return false;
        }
        
        // Nếu giá trị của trường hiện tại hoặc trường so sánh trống, return true
        if (empty($str) || empty($data[$compareField])) {
            return true;
        }
        
        // Chuyển đổi thành đối tượng datetime để so sánh
        try {
            $dateEnd = new \DateTime($str);
            $dateStart = new \DateTime($data[$compareField]);
            
            // So sánh các giá trị datetime
            return $dateEnd > $dateStart;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Hiển thị danh sách sự kiện
     */
    public function index()
    {
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Lấy dữ liệu sự kiện và thông tin phân trang thông qua model
        $pageData = $this->model->search($criteria, $options);
        
        // Lấy tổng số kết quả thông qua model
        $pager = $this->model->getPager();
        $total = $pager ? $pager->getTotal() : $this->model->countSearchResults($criteria);
        
        // Nếu trang hiện tại lớn hơn tổng số trang, điều hướng về trang cuối cùng
        $pageCount = ceil($total / $params['perPage']);
        if ($total > 0 && $params['page'] > $pageCount) {
            // Tạo URL mới với trang cuối cùng
            $redirectParams = $_GET;
            $redirectParams['page'] = $pageCount;
            $redirectUrl = site_url($this->module_name) . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath($this->module_name);
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly($this->pager_only);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        
        // Thêm danh sách loại sự kiện nếu cần
        if (isset($this->loaiSuKienModel)) {
            $viewData['loaiSuKienList'] = $this->loaiSuKienModel->findAll();
        }
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form thêm mới
     */
    public function new()
    {
        // Sử dụng prepareFormData để chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name);
        
        // Thêm danh sách loại sự kiện
        if (isset($this->loaiSuKienModel)) {
            $viewData['loaiSuKienList'] = $this->loaiSuKienModel->findAll();
        }
        
        // Thêm dữ liệu cho view
        $viewData['title'] = 'Thêm mới ' . $this->title;
        $viewData['validation'] = \Config\Services::validation();
        $viewData['errors'] = session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []);
        $viewData['action'] = site_url($this->module_name . '/create');
        $viewData['method'] = 'POST';
        
        return view('App\Modules\\' . $this->module_name . '\Views\new', $viewData);
    }
    
    /**
     * Xử lý thêm mới dữ liệu
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Xử lý các trường thời gian
        $dateTimeFields = [
            'thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 'bat_dau_dang_ky',
            'ket_thuc_dang_ky', 'han_huy_dang_ky', 'gio_bat_dau', 'gio_ket_thuc'
        ];
        
        foreach ($dateTimeFields as $field) {
            if (!empty($data[$field])) {
                $data[$field] = $this->model->formatDateTime($data[$field]);
            } else {
                $data[$field] = null;
            }
        }
        
        // Xử lý upload file poster nếu có
        $poster = $this->request->getFile('su_kien_poster');
        if ($poster && $poster->isValid() && !$poster->hasMoved()) {
            try {
                // Tạo thư mục nếu chưa tồn tại
                $uploadPath = ROOTPATH . 'public/uploads/sukien';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $newName = $poster->getRandomName();
                $poster->move($uploadPath, $newName);
                
                // Lưu thông tin vào dữ liệu JSON
                $data['su_kien_poster'] = json_encode([
                    'path' => 'uploads/sukien/' . $newName,
                    'name' => $poster->getClientName(),
                    'type' => $poster->getClientMimeType(),
                    'size' => $poster->getSize()
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Lỗi upload poster: ' . $e->getMessage());
            }
        }

        // Xử lý slug nếu chưa có
        if (empty($data['slug']) && !empty($data['ten_su_kien'])) {
            $data['slug'] = $this->model->createSlug($data['ten_su_kien']);
        }
        
        // Xử lý lịch trình nếu có
        if (isset($data['lich_trinh']) && is_array($data['lich_trinh'])) {
            // Chuyển đổi dữ liệu dạng cột thành mảng dạng hàng
            $lichTrinh = [];
            
            // Kiểm tra cấu trúc dữ liệu để xử lý phù hợp
            if (isset($data['lich_trinh']['thoi_gian']) && is_array($data['lich_trinh']['thoi_gian'])) {
                $count = count($data['lich_trinh']['thoi_gian']);
                
                for ($i = 0; $i < $count; $i++) {
                    // Bỏ qua các dòng trống
                    if (empty($data['lich_trinh']['thoi_gian'][$i]) && 
                        empty($data['lich_trinh']['noi_dung'][$i]) && 
                        empty($data['lich_trinh']['ghi_chu'][$i])) {
                        continue;
                    }
                    
                    $lichTrinh[] = [
                        'thoi_gian' => $data['lich_trinh']['thoi_gian'][$i] ?? '',
                        'noi_dung' => $data['lich_trinh']['noi_dung'][$i] ?? '',
                        'ghi_chu' => $data['lich_trinh']['ghi_chu'][$i] ?? ''
                    ];
                }
            }
            
            // Chuyển đổi thành JSON
            $data['lich_trinh'] = json_encode($lichTrinh);
        }
        
        // Xử lý thông tin diễn giả nếu có
        if (isset($data['dien_gia_info']) && !empty($data['dien_gia_info'])) {
            // Kiểm tra nếu chuỗi đã là JSON
            if (!is_array($data['dien_gia_info']) && is_string($data['dien_gia_info'])) {
                $decoded = json_decode($data['dien_gia_info'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Nếu không phải là JSON, coi như danh sách các dòng
                    $lines = array_filter(array_map('trim', explode("\n", $data['dien_gia_info'])));
                    $data['dien_gia_info'] = json_encode($lines);
                }
            } elseif (is_array($data['dien_gia_info'])) {
                // Nếu đã là mảng, chuyển thành JSON
                $data['dien_gia_info'] = json_encode($data['dien_gia_info']);
            }
        }
        
        // Xử lý các trường checkbox (chuyển đổi thành boolean)
        $checkboxFields = [
            'cho_phep_check_in', 'cho_phep_check_out', 
            'yeu_cau_face_id', 'cho_phep_checkin_thu_cong'
        ];
        
        foreach ($checkboxFields as $field) {
            $data[$field] = isset($data[$field]) ? 1 : 0;
        }
        
        // Xử lý các trường số
        $numericFields = [
            'loai_su_kien_id', 'so_luong_tham_gia', 'so_luong_dien_gia',
            'status', 'version', 'so_luot_xem', 'tong_dang_ky', 
            'tong_check_in', 'tong_check_out'
        ];
        
        foreach ($numericFields as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                $data[$field] = (int)$data[$field];
            }
        }
        
        // Chuẩn bị các quy tắc validation
        $this->model->prepareValidationRules('insert', $data);
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            // Lưu dữ liệu thông qua model
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm mới ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                throw new \RuntimeException('Không thể thêm mới ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::create] ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm mới ' . $this->title . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Hiển thị chi tiết
     */
    public function view($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin dữ liệu cơ bản thông qua model
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Tăng số lượt xem
        $this->model->increaseViewCount($id);
        
        // Lấy loại sự kiện
        if (isset($this->loaiSuKienModel)) {
            $data->loaiSuKien = $this->loaiSuKienModel->find($data->getLoaiSuKienId());
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'title' => 'Chi tiết ' . $this->title,
            'data' => $data,
            'moduleUrl' => $this->moduleUrl,
            'module_name' => $this->module_name,
            'loaiSuKienList' => isset($this->loaiSuKienModel) ? $this->loaiSuKienModel->findAll() : []
        ];
        
        return view('App\Modules\\' . $this->module_name . '\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin từ model
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng prepareFormData để chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name, $data);
        
        // Thêm danh sách loại sự kiện
        if (isset($this->loaiSuKienModel)) {
            $viewData['loaiSuKienList'] = $this->loaiSuKienModel->findAll();
        }
        
        // Thêm dữ liệu cho view
        $viewData['title'] = 'Chỉnh sửa ' . $this->title;
        $viewData['validation'] = \Config\Services::validation();
        $viewData['errors'] = session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []);
        $viewData['action'] = site_url($this->module_name . '/update/' . $id);
        $viewData['method'] = 'POST';
        
        return view('App\Modules\\' . $this->module_name . '\Views\edit', $viewData);
    }
    
    /**
     * Cập nhật sự kiện
     * 
     * @param int|null $id ID của sự kiện cần cập nhật
     * @return ResponseInterface|string
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Nếu là phương thức GET, chuyển đến trang edit
        if ($this->request->getMethod() === 'get') {
            return $this->edit($id);
        }
        
        // Kiểm tra xem bản ghi tồn tại không
        $existingData = $this->model->find($id);
        
        if (empty($existingData)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thiết lập các quy tắc validation trước
        $this->setCustomValidationRules();
        
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Đảm bảo có ID của bản ghi đang cập nhật
        $data[$this->primary_key] = $id;
        
        // Xử lý các trường thời gian
        $dateTimeFields = [
            'thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 'bat_dau_dang_ky',
            'ket_thuc_dang_ky', 'han_huy_dang_ky', 'gio_bat_dau', 'gio_ket_thuc'
        ];
        
        foreach ($dateTimeFields as $field) {
            if (!empty($data[$field])) {
                $data[$field] = $this->model->formatDateTime($data[$field]);
            } else {
                $data[$field] = null;
            }
        }
        
        // Chuẩn bị quy tắc validation cho cập nhật
        $rules = $this->model->prepareValidationRules('update', $data);
        
        // Kiểm tra dữ liệu
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Xử lý upload file poster nếu có
        $poster = $this->request->getFile('su_kien_poster');
        if ($poster && $poster->isValid() && !$poster->hasMoved()) {
            try {
                // Tạo thư mục nếu chưa tồn tại
                $uploadPath = ROOTPATH . 'public/uploads/sukien';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $newName = $poster->getRandomName();
                $poster->move($uploadPath, $newName);
                
                // Lưu thông tin vào dữ liệu JSON
                $data['su_kien_poster'] = json_encode([
                    'path' => 'uploads/sukien/' . $newName,
                    'name' => $poster->getClientName(),
                    'type' => $poster->getClientMimeType(),
                    'size' => $poster->getSize()
                ]);
                
                // Xóa file ảnh cũ nếu có
                if (!empty($existingData->su_kien_poster)) {
                    try {
                        $oldPosterData = json_decode($existingData->getSuKienPoster());
                        if (isset($oldPosterData->path)) {
                            $oldFilePath = ROOTPATH . 'public/' . $oldPosterData->path;
                            if (file_exists($oldFilePath)) {
                                @unlink($oldFilePath);
                            }
                        }
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi xóa poster cũ: ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Lỗi upload poster: ' . $e->getMessage());
            }
        } else {
            // Giữ lại dữ liệu poster cũ nếu không upload mới
            unset($data['su_kien_poster']);
        }

        // Xử lý slug nếu chưa có
        if (empty($data['slug']) && !empty($data['ten_su_kien'])) {
            $data['slug'] = $this->model->createSlug($data['ten_su_kien'], $id);
        }
        
        // Xử lý lịch trình nếu có
        if (isset($data['lich_trinh']) && is_array($data['lich_trinh'])) {
            // Chuyển đổi dữ liệu dạng cột thành mảng dạng hàng
            $lichTrinh = [];
            
            // Kiểm tra cấu trúc dữ liệu để xử lý phù hợp
            if (isset($data['lich_trinh']['thoi_gian']) && is_array($data['lich_trinh']['thoi_gian'])) {
                $count = count($data['lich_trinh']['thoi_gian']);
                
                for ($i = 0; $i < $count; $i++) {
                    // Bỏ qua các dòng trống
                    if (empty($data['lich_trinh']['thoi_gian'][$i]) && 
                        empty($data['lich_trinh']['noi_dung'][$i]) && 
                        empty($data['lich_trinh']['ghi_chu'][$i])) {
                        continue;
                    }
                    
                    $lichTrinh[] = [
                        'thoi_gian' => $data['lich_trinh']['thoi_gian'][$i] ?? '',
                        'noi_dung' => $data['lich_trinh']['noi_dung'][$i] ?? '',
                        'ghi_chu' => $data['lich_trinh']['ghi_chu'][$i] ?? ''
                    ];
                }
            }
            
            // Chuyển đổi thành JSON
            $data['lich_trinh'] = json_encode($lichTrinh);
        }
        
        // Xử lý thông tin diễn giả nếu có
        if (isset($data['dien_gia_info']) && !empty($data['dien_gia_info'])) {
            // Kiểm tra nếu chuỗi đã là JSON
            if (!is_array($data['dien_gia_info']) && is_string($data['dien_gia_info'])) {
                $decoded = json_decode($data['dien_gia_info'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Nếu không phải là JSON, coi như danh sách các dòng
                    $lines = array_filter(array_map('trim', explode("\n", $data['dien_gia_info'])));
                    $data['dien_gia_info'] = json_encode($lines);
                }
            } elseif (is_array($data['dien_gia_info'])) {
                // Nếu đã là mảng, chuyển thành JSON
                $data['dien_gia_info'] = json_encode($data['dien_gia_info']);
            }
        }
        
        // Xử lý các trường checkbox (chuyển đổi thành boolean)
        $checkboxFields = [
            'cho_phep_check_in', 'cho_phep_check_out', 
            'yeu_cau_face_id', 'cho_phep_checkin_thu_cong', 'cho_phep_dang_ky'
        ];
        
        foreach ($checkboxFields as $field) {
            $data[$field] = isset($data[$field]) ? 1 : 0;
        }
        
        // Xử lý các trường số
        $numericFields = [
            'loai_su_kien_id', 'so_luong_tham_gia', 'so_luong_dien_gia',
            'status', 'version', 'so_luot_xem', 'tong_dang_ky', 
            'tong_check_in', 'tong_check_out'
        ];
        
        foreach ($numericFields as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                $data[$field] = (int)$data[$field];
            }
        }
        
        try {
            // Lưu dữ liệu thông qua model
            if ($this->model->update($id, $data)) {
                $this->alert->set('success', 'Cập nhật ' . $this->title . ' thành công', true);
                
                // Xử lý URL trả về
                $returnUrl = $this->request->getPost('return_url');
                $redirectUrl = $this->processReturnUrl($returnUrl);
                
                return redirect()->to($redirectUrl);
            } else {
                throw new \RuntimeException('Không thể cập nhật ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::update] ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật ' . $this->title . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Định dạng chuỗi thời gian thành định dạng chuẩn
     * 
     * @param string $dateTime Chuỗi thời gian đầu vào
     * @return string|null Chuỗi thời gian đã định dạng hoặc null nếu không hợp lệ
     */
    protected function formatDateTime($dateTime)
    {
        if (empty($dateTime)) {
            return null;
        }
        
        try {
            // Thử chuyển đổi chuỗi sang đối tượng Time
            $time = new Time($dateTime);
            return $time->toDateTimeString();
        } catch (\Exception $e) {
            // Thử các định dạng khác nếu không chuyển đổi được
            $formats = [
                'd/m/Y H:i:s', 'd/m/Y H:i', 'd/m/Y',
                'Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d'
            ];
            
            foreach ($formats as $format) {
                $parsedDate = \DateTime::createFromFormat($format, $dateTime);
                if ($parsedDate !== false) {
                    return $parsedDate->format('Y-m-d H:i:s');
                }
            }
        }
        
        // Trả về null nếu không thể chuyển đổi
        return null;
    }
    
    /**
     * Xóa mềm (chuyển vào thùng rác)
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm thông qua model
        if ($this->model->delete($id)) {
            $this->alert->set('success', 'Đã xóa dữ liệu thành công', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getGet('return_url') ?? $backToUrl;
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Xóa nhiều sự kiện (chuyển vào thùng rác)
     */
    public function deleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl);
        }

        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        // Lấy các tham số hiện tại
        $currentParams = $this->prepareSearchParams($this->request);
        
        // Thực hiện xóa nhiều và lấy kết quả
        $result = $this->model->deleteMultiple($idArray, $currentParams);
        
        if ($result['success']) {
            $this->alert->set('success', "Đã chuyển {$result['success']} dữ liệu vào thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa dữ liệu', true);
        }
        
        // Xử lý URL trả về với trang hiện tại
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Hiển thị danh sách đã xóa
     */
    public function listdeleted()
    {
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Thêm điều kiện để chỉ lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Lấy dữ liệu đã xóa thông qua model
        $pageData = $this->model->searchDeleted($criteria, $options);
        
        // Lấy tổng số kết quả
        $total = $this->model->countDeletedSearchResults($criteria);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath($this->module_name . '/listdeleted');
            $pager->setOnly($this->pager_only);
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        
        // Thêm danh sách loại sự kiện để hiển thị tên thay vì ID
        if (isset($this->loaiSuKienModel)) {
            $viewData['loaiSuKienList'] = $this->loaiSuKienModel->findAll();
        }
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\listdeleted', $viewData);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form hoặc từ HTTP_REFERER
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Khôi phục bản ghi thông qua model
        try {
            $data = $this->model->onlyDeleted()->find($id);
            
            if ($data) {
                $this->model->update($id, ['deleted_at' => null]);
                $this->alert->set('success', 'Đã khôi phục dữ liệu từ thùng rác', true);
            } else {
                $this->alert->set('danger', 'Không tìm thấy dữ liệu đã xóa', true);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::restore] ' . $e->getMessage());
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Khôi phục nhiều bản ghi
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để khôi phục', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl);
        }

        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        // Lấy các tham số hiện tại
        $currentParams = $this->prepareSearchParams($this->request);
        
        // Thực hiện khôi phục nhiều và lấy kết quả
        $result = $this->model->restoreMultiple($idArray, $currentParams);
        
        if ($result['success']) {
            $this->alert->set('success', "Đã khôi phục {$result['success']} dữ liệu từ thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục dữ liệu', true);
        }
        
        // Xử lý URL trả về với trang hiện tại
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Xóa vĩnh viễn một bản ghi
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form hoặc từ HTTP_REFERER
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Xóa vĩnh viễn thông qua model
        try {
            if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
                $this->alert->set('success', 'Đã xóa vĩnh viễn dữ liệu', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::permanentDelete] ' . $e->getMessage());
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn nhiều bản ghi
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa vĩnh viễn', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl);
        }

        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($idArray as $id) {
            try {
                if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
                    $successCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                log_message('error', '[' . $this->controller_name . '::permanentDeleteMultiple] ' . $e->getMessage() . ' - ID: ' . $id);
                $errorCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã xóa vĩnh viễn {$successCount} dữ liệu thành công", true);
        }
        
        if ($errorCount > 0) {
            $this->alert->set('warning', "Có {$errorCount} dữ liệu không thể xóa", true);
        }
        
        // Xử lý URL trả về với trang hiện tại
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Xuất danh sách ra file Excel
     */
    public function exportExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;
        $loaiSuKienId = $this->request->getGet('loai_su_kien_id');
        $hinhThuc = $this->request->getGet('hinh_thuc');
        $status = $this->request->getGet('status');

        // Xây dựng tiêu chí tìm kiếm
        $criteria = [
            'keyword' => $keyword
        ];
        
        if (!empty($loaiSuKienId)) {
            $criteria['loai_su_kien_id'] = $loaiSuKienId;
        }
        
        if (isset($status) && $status !== '') {
            $criteria['status'] = $status;
        }
        
        if (!empty($hinhThuc)) {
            $criteria['hinh_thuc'] = $hinhThuc;
        }
        
        // Tùy chọn tìm kiếm
        $options = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả dữ liệu
        ];

        // Lấy dữ liệu từ model
        $data = $this->model->search($criteria, $options);

        // Xử lý dữ liệu và xuất Excel
        $this->exportData($data, 'excel', $criteria);
    }

    /**
     * Xuất danh sách ra file PDF
     */
    public function exportPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;
        $loaiSuKienId = $this->request->getGet('loai_su_kien_id');
        $hinhThuc = $this->request->getGet('hinh_thuc');
        $status = $this->request->getGet('status');

        // Xây dựng tiêu chí tìm kiếm
        $criteria = [
            'keyword' => $keyword
        ];
        
        if (!empty($loaiSuKienId)) {
            $criteria['loai_su_kien_id'] = $loaiSuKienId;
        }
        
        if (isset($status) && $status !== '') {
            $criteria['status'] = $status;
        }
        
        if (!empty($hinhThuc)) {
            $criteria['hinh_thuc'] = $hinhThuc;
        }
        
        // Tùy chọn tìm kiếm
        $options = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả dữ liệu
        ];

        // Lấy dữ liệu từ model
        $data = $this->model->search($criteria, $options);

        // Xử lý dữ liệu và xuất PDF
        $this->exportData($data, 'pdf', $criteria);
    }

    /**
     * Xuất danh sách đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        $loaiSuKienId = $this->request->getGet('loai_su_kien_id');
        $hinhThuc = $this->request->getGet('hinh_thuc');
        $status = $this->request->getGet('status');

        // Xây dựng tiêu chí tìm kiếm
        $criteria = [
            'keyword' => $keyword,
            'deleted' => true
        ];
        
        if (!empty($loaiSuKienId)) {
            $criteria['loai_su_kien_id'] = $loaiSuKienId;
        }
        
        if (isset($status) && $status !== '') {
            $criteria['status'] = $status;
        }
        
        if (!empty($hinhThuc)) {
            $criteria['hinh_thuc'] = $hinhThuc;
        }
        
        // Tùy chọn tìm kiếm
        $options = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả dữ liệu
        ];

        // Lấy dữ liệu từ model
        $data = $this->model->searchDeleted($criteria, $options);

        // Xử lý dữ liệu và xuất Excel
        $this->exportData($data, 'excel', $criteria, true);
    }

    /**
     * Xuất danh sách đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        $loaiSuKienId = $this->request->getGet('loai_su_kien_id');
        $hinhThuc = $this->request->getGet('hinh_thuc');
        $status = $this->request->getGet('status');

        // Xây dựng tiêu chí tìm kiếm
        $criteria = [
            'keyword' => $keyword,
            'deleted' => true
        ];
        
        if (!empty($loaiSuKienId)) {
            $criteria['loai_su_kien_id'] = $loaiSuKienId;
        }
        
        if (isset($status) && $status !== '') {
            $criteria['status'] = $status;
        }
        
        if (!empty($hinhThuc)) {
            $criteria['hinh_thuc'] = $hinhThuc;
        }
        
        // Tùy chọn tìm kiếm
        $options = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả dữ liệu
        ];

        // Lấy dữ liệu từ model
        $data = $this->model->searchDeleted($criteria, $options);

        // Xử lý dữ liệu và xuất PDF
        $this->exportData($data, 'pdf', $criteria, true);
    }
    
    /**
     * Xử lý URL trả về, loại bỏ domain nếu cần
     * 
     * @param string|null $returnUrl URL trả về
     * @return string URL đích đã được xử lý
     */
    private function processReturnUrl($returnUrl)
    {
        // Mặc định là URL module
        $redirectUrl = $this->moduleUrl;
        
        if (!empty($returnUrl)) {
            // Giải mã URL
            $decodedUrl = urldecode($returnUrl);
            
            // Kiểm tra nếu URL chứa domain, chỉ lấy phần path và query
            if (strpos($decodedUrl, 'http') === 0) {
                $urlParts = parse_url($decodedUrl);
                $path = $urlParts['path'] ?? '';
                $query = isset($urlParts['query']) ? '?' . $urlParts['query'] : '';
                $decodedUrl = $path . $query;
            }
            
            // Xử lý đường dẫn tương đối
            if (strpos($decodedUrl, '/') === 0) {
                $decodedUrl = substr($decodedUrl, 1);
            }
            
            // Cập nhật URL đích
            $redirectUrl = $decodedUrl;
        }
        
        return $redirectUrl;
    }
    
    /**
     * Phương thức hỗ trợ xuất dữ liệu
     */
    private function exportData($data, $type, $criteria, $isDeleted = false)
    {
        // Lấy danh sách loại sự kiện từ model
        $loaiSuKienList = [];
        if (isset($this->loaiSuKienModel)) {
            $loaiSuKienList = $this->loaiSuKienModel->findAll();
        }
        
        // Chuẩn bị dữ liệu đã được format
        $formattedData = [];
        foreach ($data as $item) {
            // Tìm tên loại sự kiện từ ID
            $loaiSuKienName = '';
            
            foreach ($loaiSuKienList as $loaiSuKien) {
                if ($loaiSuKien->getId() == $item->getLoaiSuKienId()) {
                    $loaiSuKienName = $loaiSuKien->getTenLoaiSuKien();
                    break;
                }
            }
            
            // Định dạng trạng thái
            $statusText = $item->getStatus() ? 'Hoạt động' : 'Không hoạt động';
            
            // Định dạng hình thức
            $hinhThucText = '';
            switch ($item->getHinhThuc()) {
                case 'offline':
                    $hinhThucText = 'Trực tiếp';
                    break;
                case 'online':
                    $hinhThucText = 'Trực tuyến';
                    break;
                case 'hybrid':
                    $hinhThucText = 'Kết hợp';
                    break;
                default:
                    $hinhThucText = $item->getHinhThuc();
            }
            
            // Định dạng thời gian
            $thoiGianBatDau = $item->getThoiGianBatDau() ? $item->getThoiGianBatDau()->format('d/m/Y H:i') : '';
            $thoiGianKetThuc = $item->getThoiGianKetThuc() ? $item->getThoiGianKetThuc()->format('d/m/Y H:i') : '';
            
            // Thêm vào dữ liệu đã format
            $formattedData[] = [
                'ID' => $item->getId(),
                'Tên sự kiện' => $item->getTenSuKien(),
                'Loại sự kiện' => $loaiSuKienName,
                'Thời gian bắt đầu' => $thoiGianBatDau,
                'Thời gian kết thúc' => $thoiGianKetThuc,
                'Địa điểm' => $item->getDiaDiem(),
                'Trạng thái' => $statusText,
                'Hình thức' => $hinhThucText,
                'Tổng đăng ký' => $item->getTongDangKy(),
                'Tổng check-in' => $item->getTongCheckIn(),
                'Tổng check-out' => $item->getTongCheckOut()
            ];
        }
        
        // Tiêu đề file
        $title = $isDeleted ? 
            ($type === 'pdf' ? $this->export_pdf_deleted_title : $this->export_excel_deleted_title) : 
            ($type === 'pdf' ? $this->export_pdf_title : $this->export_excel_title);
        
        // Tên file
        $filename = $isDeleted ? 
            ($type === 'pdf' ? $this->export_pdf_deleted : $this->export_excel_deleted) : 
            ($type === 'pdf' ? $this->export_pdf : $this->export_excel);
        
        // Xuất dữ liệu
        if ($type === 'pdf') {
            $this->exportToPdf($formattedData, $title, $filename);
        } else {
            $this->exportToExcel($formattedData, $title, $filename);
        }
    }
} 