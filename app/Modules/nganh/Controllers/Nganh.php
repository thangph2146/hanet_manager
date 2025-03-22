<?php

namespace App\Modules\nganh\Controllers;

use App\Controllers\BaseController;
use App\Modules\nganh\Models\NganhModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Alert;
use CodeIgniter\Database\Exceptions\DataException;

class Nganh extends BaseController
{
    protected $model;
    protected $breadcrumb;
    protected $alert;
    protected $moduleUrl;
    protected $moduleName;
    protected $session;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');
        
        $this->model = new NganhModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('nganh');
        $this->moduleName = 'Ngành';
        
        // Thêm breadcrumb cơ bản cho tất cả các trang trong controller này
        $this->breadcrumb->add('Trang chủ', base_url())
                        ->add($this->moduleName, $this->moduleUrl);
    }
    
    /**
     * Hiển thị dashboard của module
     */
    public function index()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Danh sách', current_url());
        
        // Lấy dữ liệu từ model với phân trang và sắp xếp
        // Tận dụng withRelations từ BaseModel
        $data = $this->model->where('nganh.bin', 0)
                          ->withRelations(['phong_khoa'])
                          ->orderBy('nganh.updated_at', 'DESC')
                          ->paginate(10);
                          
        // Lấy đối tượng phân trang
        $pager = $this->model->pager;
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Danh sách ' . $this->moduleName,
            'nganhs' => $data,
            'pager' => $pager,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\nganh\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Lấy danh sách phòng/khoa từ relationship đã định nghĩa
        $phongkhoas = $this->model->getAllPhongKhoa();
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $nganh = new \App\Modules\nganh\Entities\Nganh([
            'status' => 1,
            'bin' => 0
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'phongkhoas' => $phongkhoas,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'nganh' => $nganh // Thêm entity trống với giá trị mặc định
        ];
        
        return view('App\Modules\nganh\Views\form', $viewData);
    }
    
    /**
     * Xử lý lưu dữ liệu mới
     */
    public function create()
    {
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Xử lý validation
        if (!$this->validateData($data, $this->model->getValidationRules(), $this->model->getValidationMessages())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return $this->new();
        }
        
        // Kiểm tra xem mã ngành đã tồn tại chưa
        if ($this->model->isCodeExists($data['ma_nganh'])) {
            $this->alert->set('danger', 'Mã ngành đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Kiểm tra xem tên ngành đã tồn tại chưa
        if ($this->model->isNameExists($data['ten_nganh'])) {
            $this->alert->set('danger', 'Tên ngành đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Điền các giá trị mặc định
        $data['status'] = $data['status'] ?? 1;
        $data['bin'] = 0;
        
        try {
            // Lưu dữ liệu vào database sử dụng createWithRelations
            // Chuẩn bị dữ liệu quan hệ nếu có
            $relations = [];
            
            $result = $this->model->createWithRelations($data, $relations);
            
            if ($result) {
                $this->alert->set('success', 'Thêm ngành thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $this->alert->set('danger', 'Thêm ngành thất bại', true);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Lỗi dữ liệu: ' . $e->getMessage(), true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Hiển thị thông tin chi tiết của một ngành
     */
    public function view($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID ngành không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithRelations từ BaseModel
        $nganh = $this->model->findWithRelations($id, ['phong_khoa']);
        
        if (empty($nganh)) {
            $this->alert->set('danger', 'Không tìm thấy ngành', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'nganh' => $nganh,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\nganh\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID ngành không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithPhongKhoa từ NganhModel
        $nganh = $this->model->findWithPhongKhoa($id);
        
        if (empty($nganh)) {
            $this->alert->set('danger', 'Không tìm thấy ngành', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Lấy danh sách phòng/khoa từ NganhModel
        $phongkhoas = $this->model->getAllPhongKhoa();
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'nganh' => $nganh,
            'phongkhoas' => $phongkhoas,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\nganh\Views\form', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID ngành không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin ngành với relationship
        $existingNganh = $this->model->findWithRelations($id, ['phong_khoa']);
        
        if (empty($existingNganh)) {
            $this->alert->set('danger', 'Không tìm thấy ngành', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Xử lý validation
        if (!$this->validateData($data, $this->model->getValidationRules(), $this->model->getValidationMessages())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return $this->edit($id);
        }
        
        // Kiểm tra xem mã ngành đã tồn tại chưa (trừ chính nó)
        if ($this->model->isCodeExists($data['ma_nganh'], $id)) {
            $this->alert->set('danger', 'Mã ngành đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Kiểm tra xem tên ngành đã tồn tại chưa (trừ chính nó)
        if ($this->model->isNameExists($data['ten_nganh'], $id)) {
            $this->alert->set('danger', 'Tên ngành đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        try {
            // Chuẩn bị dữ liệu quan hệ nếu có
            $relations = [];
            
            // Cập nhật dữ liệu vào database sử dụng updateWithRelations
            $result = $this->model->updateWithRelations($id, $data, $relations);
            
            if ($result) {
                $this->alert->set('success', 'Cập nhật ngành thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $this->alert->set('danger', 'Cập nhật ngành thất bại', true);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Lỗi dữ liệu: ' . $e->getMessage(), true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xử lý xóa (chuyển vào thùng rác)
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID ngành không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        try {
            // Sử dụng soft delete
            if ($this->model->delete($id)) {
                $this->alert->set('success', 'Xóa ngành thành công', true);
            } else {
                $this->alert->set('danger', 'Xóa ngành thất bại', true);
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Lỗi: ' . $e->getMessage(), true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Hiển thị danh sách các bản ghi đã xóa
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thùng rác', current_url());
        
        // Lấy dữ liệu đã xóa từ model với quan hệ phòng khoa
        $deletedItems = $this->model->getAllDeleted(true);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thùng rác ' . $this->moduleName,
            'deletedItems' => $deletedItems,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\nganh\Views\listdeleted', $viewData);
    }
    
    /**
     * Khôi phục bản ghi đã xóa
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID ngành không hợp lệ', true);
            return redirect()->to("{$this->moduleUrl}/listdeleted");
        }
        
        try {
            // Lấy thông tin của mục cần khôi phục với relationships
            $nganh = $this->model->withDeleted()->findWithRelations($id, ['phong_khoa']);
            
            if (empty($nganh)) {
                $this->alert->set('danger', 'Không tìm thấy ngành', true);
                return redirect()->to("{$this->moduleUrl}/listdeleted");
            }
            
            // Khôi phục bản ghi
            if ($this->model->update($id, ['deleted_at' => null])) {
                $this->alert->set('success', 'Khôi phục ngành thành công', true);
            } else {
                $this->alert->set('danger', 'Khôi phục ngành thất bại', true);
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Lỗi: ' . $e->getMessage(), true);
        }
        
        return redirect()->to("{$this->moduleUrl}/listdeleted");
    }
    
    /**
     * Xóa vĩnh viễn bản ghi
     */
    public function purge($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID ngành không hợp lệ', true);
            return redirect()->to("{$this->moduleUrl}/listdeleted");
        }
        
        try {
            // Xóa vĩnh viễn
            if ($this->model->delete($id, true)) {
                $this->alert->set('success', 'Xóa vĩnh viễn ngành thành công', true);
            } else {
                $this->alert->set('danger', 'Xóa vĩnh viễn ngành thất bại', true);
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Lỗi: ' . $e->getMessage(), true);
        }
        
        return redirect()->to("{$this->moduleUrl}/listdeleted");
    }
    
    /**
     * Tìm kiếm ngành
     */
    public function search()
    {
        $keyword = $this->request->getGet('keyword');
        
        if (empty($keyword)) {
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Tìm kiếm', current_url());
        
        // Thiết lập tiêu chí tìm kiếm
        $criteria = ['keyword' => $keyword];
        
        // Thiết lập tùy chọn sắp xếp và phân trang
        $options = [
            'sort_field' => $this->request->getGet('sort') ?? 'updated_at',
            'sort_direction' => $this->request->getGet('direction') ?? 'DESC',
            'withPhongKhoa' => true // Chỉ định rõ ràng rằng cần tải quan hệ phòng khoa
        ];
        
        // Sử dụng phương thức search đã được tối ưu hóa
        $results = $this->model->search($criteria, $options);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Kết quả tìm kiếm cho "' . $keyword . '"',
            'nganhs' => $results,
            'keyword' => $keyword,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\nganh\Views\search_results', $viewData);
    }
    
    /**
     * Phương thức AJAX để load danh sách ngành theo phòng/khoa
     */
    public function getByPhongKhoa()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }
        
        $phongKhoaId = $this->request->getPost('phong_khoa_id');
        
        if (empty($phongKhoaId)) {
            return $this->response->setJSON(['error' => 'ID phòng/khoa không hợp lệ']);
        }
        
        // Sử dụng phương thức getByPhongKhoaId với tải quan hệ
        $nganhs = $this->model->getByPhongKhoaId((int)$phongKhoaId, true);
        
        // Chuẩn bị dữ liệu cho dropdown
        $options = [];
        foreach ($nganhs as $nganh) {
            $options[] = [
                'id' => $nganh->nganh_id,
                'text' => $nganh->ten_nganh . ' (' . $nganh->ma_nganh . ')'
            ];
        }
        
        return $this->response->setJSON($options);
    }
} 