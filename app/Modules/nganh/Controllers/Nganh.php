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
        
        // Thiết lập tiêu chí tìm kiếm mặc định
        $criteria = ['filters' => ['bin' => 0]];
        
        // Thiết lập tùy chọn
        $options = [
            'sort' => 'updated_at',
            'sort_direction' => 'DESC',
            'page' => 1,
            'limit' => 10,
            'withRelations' => true
        ];
        
        // Sử dụng phương thức search từ BaseModel thông qua NganhModel
        $data = $this->model->getAll();
        
        // Lấy đối tượng phân trang
        $pager = $this->model->pager;
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Danh sách ' . $this->moduleName,
            'nganh' => $data,
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
            'moduleUrl' => $this->moduleUrl,
            'nganh' => $nganh
        ];
        
        return view('App\Modules\nganh\Views\new', $viewData);
    }
    
    /**
     * Xử lý lưu dữ liệu mới
     */
    public function create()
    {
        $request = $this->request;

        // Validate dữ liệu đầu vào
        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tenNganh = $request->getPost('ten_nganh');
        $maNganh = $request->getPost('ma_nganh');

        // Kiểm tra tên ngành đã tồn tại chưa
        if ($this->model->isNameExists($tenNganh)) {
            return redirect()->back()->withInput()->with('error', 'Tên ngành đã tồn tại');
        }

        // Kiểm tra mã ngành đã tồn tại chưa
        if ($this->model->isCodeExists($maNganh)) {
            return redirect()->back()->withInput()->with('error', 'Mã ngành đã tồn tại');
        }

        $data = [
            'ten_nganh' => $tenNganh,
            'ma_nganh' => $maNganh,
            'phong_khoa_id' => $request->getPost('phong_khoa_id'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/nganh')->with('success', 'Thêm ngành thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }
    
    /**
     * Hiển thị thông tin chi tiết của một ngành
     */
    public function view($id = null)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID không hợp lệ');
        }
        
        // Tìm ngành với ID tương ứng và load quan hệ phòng khoa
        $nganh = $this->model->findWithRelations($id);
        
        if (empty($nganh)) {
            return redirect()->to('/nganh')->with('error', 'Không tìm thấy ngành');
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
        
        // Sử dụng phương thức findWithRelations từ BaseModel, không validate
        $nganh = $this->model->findWithRelations($id);
        
        if (empty($nganh)) {
            $this->alert->set('danger', 'Không tìm thấy ngành', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy danh sách phòng khoa cho dropdown
        $phongKhoaList = [];
        try {
            $phongKhoaList = $this->model->getAllPhongKhoa();
        } catch (\Exception $e) {
            // Lỗi không cần hiển thị
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'nganh' => $nganh,
            'phong_khoa_list' => $phongKhoaList,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? $this->validator->getErrors(),
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
        
        // Lấy thông tin ngành với relationship, không validate
        $existingNganh = $this->model->findWithRelations($id);
        
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
            return redirect()->back()->with('error', 'ID không hợp lệ');
        }
        
        try {
            // Sử dụng bin=1 và thêm deleted_at
            if ($this->model->update($id, [
                'bin' => 1, 
                'deleted_at' => date('Y-m-d H:i:s')
            ])) {
                return redirect()->to('/nganh')->with('success', 'Đã xóa ngành thành công');
            } else {
                return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa ngành');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Hiển thị danh sách các bản ghi đã xóa
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thùng rác', current_url());
        
        // Lấy dữ liệu đã xóa từ model với quan hệ phòng khoa
        $deletedItems = $this->model->getAllInRecycleBin();
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thùng rác ' . $this->moduleName,
            'nganh' => $deletedItems,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\nganh\Views\listdeleted', $viewData);
    }
    
    /**
     * Khôi phục một bản ghi đã xóa
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID không hợp lệ');
        }
        
        // Khôi phục bản ghi bằng cách đặt bin = 0 và xóa deleted_at
        if ($this->model->update($id, ['bin' => 0, 'deleted_at' => null])) {
            return redirect()->to('/nganh/listdeleted')->with('success', 'Đã khôi phục ngành thành công');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi khôi phục ngành');
        }
    }
    
    /**
     * Khôi phục nhiều bản ghi đã xóa
     */
    public function restoreMultiple()
    {
        $request = $this->request;
        $selectedIds = $request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Không có mục nào được chọn để khôi phục');
        }
        
        $countSuccess = 0;
        
        foreach ($selectedIds as $id) {
            // Khôi phục bản ghi bằng cách đặt bin = 0 và xóa deleted_at
            if ($this->model->update($id, ['bin' => 0, 'deleted_at' => null])) {
                $countSuccess++;
            }
        }
        
        if ($countSuccess > 0) {
            return redirect()->to('/nganh/listdeleted')->with('success', "Đã khôi phục {$countSuccess} ngành thành công");
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi khôi phục các mục đã chọn');
        }
    }
    
    /**
     * Xóa vĩnh viễn nhiều bản ghi
     */
    public function permanentDeleteMultiple()
    {
        $request = $this->request;
        $selectedIds = $request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Không có mục nào được chọn để xóa vĩnh viễn');
        }
        
        $countSuccess = 0;
        
        foreach ($selectedIds as $id) {
            // Xóa vĩnh viễn bản ghi
            if ($this->model->where('nganh_id', $id)->delete(null, true)) {
                $countSuccess++;
            }
        }
        
        if ($countSuccess > 0) {
            return redirect()->to('/nganh/listdeleted')->with('success', "Đã xóa vĩnh viễn {$countSuccess} ngành thành công");
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn các mục đã chọn');
        }
    }
    
    /**
     * Xóa vĩnh viễn một bản ghi
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID không hợp lệ');
        }
        
        // Xóa vĩnh viễn bản ghi
        if ($this->model->where('nganh_id', $id)->delete(null, true)) {
            return redirect()->to('/nganh/listdeleted')->with('success', 'Đã xóa vĩnh viễn ngành thành công');
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn ngành');
        }
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
        $criteria = [
            'search' => $keyword,
            'filters' => ['bin' => 0]
        ];
        
        // Thiết lập tùy chọn sắp xếp và phân trang
        $options = [
            'sort' => $this->request->getGet('sort') ?? 'updated_at',
            'sort_direction' => $this->request->getGet('direction') ?? 'DESC',
            'withRelations' => true
        ];
        
        // Sử dụng phương thức search từ BaseModel
        $results = $this->model->like('ten_nganh', $keyword)->orLike('ma_nganh', $keyword)->where('bin', 0)->findAll();
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Kết quả tìm kiếm cho "' . $keyword . '"',
            'nganh' => $results,
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
    
    /**
     * Xử lý xóa nhiều bản ghi cùng lúc
     */
    public function deleteMultiple()
    {
        $request = $this->request;
        $selectedIds = $request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Không có mục nào được chọn để xóa');
        }
        
        $countSuccess = 0;
        
        foreach ($selectedIds as $id) {
            // Chuyển bản ghi vào thùng rác thay vì xóa hoàn toàn
            if ($this->model->update($id, ['bin' => 1, 'deleted_at' => date('Y-m-d H:i:s')])) {
                $countSuccess++;
            }
        }
        
        if ($countSuccess > 0) {
            return redirect()->to('/nganh')->with('success', "Đã xóa {$countSuccess} ngành thành công");
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa các mục đã chọn');
        }
    }
    
    /**
     * Xử lý thay đổi trạng thái nhiều bản ghi cùng lúc
     */
    public function statusMultiple()
    {
        $request = $this->request;
        $selectedIds = $request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Không có mục nào được chọn để thay đổi trạng thái');
        }
        
        $countSuccess = 0;
        
        foreach ($selectedIds as $id) {
            // Lấy bản ghi hiện tại
            $nganh = $this->model->find($id);
            
            if ($nganh) {
                // Đảo ngược trạng thái
                $newStatus = $nganh->status == 1 ? 0 : 1;
                
                // Cập nhật trạng thái mới
                if ($this->model->update($id, ['status' => $newStatus])) {
                    $countSuccess++;
                }
            }
        }
        
        if ($countSuccess > 0) {
            return redirect()->to('/nganh')->with('success', "Đã thay đổi trạng thái {$countSuccess} ngành thành công");
        } else {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thay đổi trạng thái các mục đã chọn');
        }
    }
} 