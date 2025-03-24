<?php

namespace App\Modules\manhinh\Controllers;

use App\Controllers\BaseController;
use App\Modules\manhinh\Models\ManhinhModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Alert;
use CodeIgniter\Database\Exceptions\DataException;

class Manhinh extends BaseController
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
        
        $this->model = new ManhinhModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('manhinh');
        $this->moduleName = 'Màn hình';
        
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
        
        // Sử dụng phương thức getAll từ model
        $data = $this->model->getAll();
        
        // Lấy đối tượng phân trang
        $pager = $this->model->pager;
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Danh sách ' . $this->moduleName,
            'manhinh' => $data,
            'pager' => $pager,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\manhinh\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Lấy danh sách camera và template cho dropdowns
        $cameras = $this->model->getAllCameras();
        $templates = $this->model->getAllTemplates();
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $manhinh = new \App\Modules\manhinh\Entities\Manhinh([
            'status' => 1,
            'bin' => 0
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'validation' => $this->validator,
            'cameras' => $cameras,
            'templates' => $templates,
            'moduleUrl' => $this->moduleUrl,
            'manhinh' => $manhinh,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\manhinh\Views\new', $viewData);
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

        $tenManHinh = $request->getPost('ten_man_hinh');
        $maManHinh = $request->getPost('ma_man_hinh');

        // Kiểm tra tên màn hình đã tồn tại chưa
        if (!empty($tenManHinh) && $this->model->isNameExists($tenManHinh)) {
            return redirect()->back()->withInput()->with('error', 'Tên màn hình đã tồn tại');
        }

        // Lưu ý: Không cần kiểm tra tính duy nhất của mã màn hình nữa vì nó không còn là unique
        $data = [
            'ten_man_hinh' => $tenManHinh,
            'ma_man_hinh' => $maManHinh,
            'camera_id' => $request->getPost('camera_id'),
            'temlate_id' => $request->getPost('temlate_id'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/manhinh')->with('success', 'Thêm màn hình thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }
    
    /**
     * Hiển thị thông tin chi tiết của một màn hình
     */
    public function view($id = null)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID không hợp lệ');
        }
        
        // Tìm màn hình với ID tương ứng và load quan hệ
        $manhinh = $this->model->findWithRelations($id);
        
        if (empty($manhinh)) {
            return redirect()->to('/manhinh')->with('error', 'Không tìm thấy màn hình');
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'manhinh' => $manhinh,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\manhinh\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithRelations từ model
        $manhinh = $this->model->findWithRelations($id);
        
        if (empty($manhinh)) {
            $this->alert->set('danger', 'Không tìm thấy màn hình', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy danh sách camera và template cho dropdowns
        $cameras = $this->model->getAllCameras();
        $templates = $this->model->getAllTemplates();
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'manhinh' => $manhinh,
            'cameras' => $cameras,
            'templates' => $templates,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\manhinh\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin màn hình với relationship
        $existingManhinh = $this->model->findWithRelations($id);
        
        if (empty($existingManhinh)) {
            $this->alert->set('danger', 'Không tìm thấy màn hình', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Chuẩn bị quy tắc validation cho cập nhật
        $this->model->prepareValidationRules('update', $id);
        
        // Xử lý validation với quy tắc đã được điều chỉnh
        if (!$this->validate($this->model->getValidationRules())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Kiểm tra xem tên màn hình đã tồn tại chưa (trừ chính nó)
        if (!empty($data['ten_man_hinh']) && $this->model->isNameExists($data['ten_man_hinh'], $id)) {
            $this->alert->set('danger', 'Tên màn hình đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Chuẩn bị dữ liệu cập nhật
        $updateData = [
            'ten_man_hinh' => $data['ten_man_hinh'],
            'ma_man_hinh' => $data['ma_man_hinh'],
            'camera_id' => $data['camera_id'] ?? null,
            'temlate_id' => $data['temlate_id'] ?? null,
            'status' => $data['status'] ?? 1,
        ];
        
        // Cập nhật dữ liệu
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật màn hình thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật màn hình', true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa màn hình (chuyển vào thùng rác)
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Kiểm tra màn hình tồn tại không
        $manhinh = $this->model->find($id);
        
        if (empty($manhinh)) {
            $this->alert->set('danger', 'Không tìm thấy màn hình', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Chuyển vào thùng rác thay vì xóa
        if ($this->model->moveToRecycleBin($id)) {
            $this->alert->set('success', 'Đã chuyển màn hình vào thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa màn hình', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Hiển thị danh sách các màn hình đã bị xóa
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thùng rác', current_url());
        
        // Lấy tất cả màn hình trong thùng rác
        $data = $this->model->getAllInRecycleBin();
        
        // Lấy đối tượng phân trang
        $pager = $this->model->pager;
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thùng rác ' . $this->moduleName,
            'manhinh' => $data,
            'pager' => $pager,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\manhinh\Views\listdeleted', $viewData);
    }
    
    /**
     * Khôi phục màn hình từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Khôi phục màn hình thành công', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục màn hình', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Khôi phục nhiều màn hình từ thùng rác
     */
    public function restoreMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn màn hình nào để khôi phục', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        foreach ($idArray as $id) {
            if ($this->model->restoreFromRecycleBin($id)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã khôi phục $successCount màn hình", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục màn hình', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn nhiều màn hình
     */
    public function permanentDeleteMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn màn hình nào để xóa vĩnh viễn', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        foreach ($idArray as $id) {
            if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã xóa vĩnh viễn $successCount màn hình", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa vĩnh viễn màn hình', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một màn hình
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn màn hình', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa màn hình', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Tìm kiếm màn hình
     */
    public function search()
    {
        // Lấy dữ liệu từ request
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $cameraId = $this->request->getGet('camera_id');
        $temlateId = $this->request->getGet('temlate_id');
        
        // Chuẩn bị tiêu chí tìm kiếm
        $criteria = [
            'keyword' => $keyword
        ];
        
        // Thêm bộ lọc nếu có
        $filters = [];
        if ($status !== null && $status !== '') {
            $filters['status'] = (int)$status;
        }
        if ($cameraId !== null && $cameraId !== '') {
            $filters['camera_id'] = (int)$cameraId;
        }
        if ($temlateId !== null && $temlateId !== '') {
            $filters['temlate_id'] = (int)$temlateId;
        }
        
        // Chỉ thêm vào criteria nếu có bộ lọc
        if (!empty($filters)) {
            $criteria['filters'] = $filters;
        }
        
        // Thiết lập tùy chọn
        $options = [
            'sort_field' => $this->request->getGet('sort') ?? 'updated_at',
            'sort_direction' => $this->request->getGet('order') ?? 'DESC',
            'limit' => (int)($this->request->getGet('length') ?? 10),
            'offset' => (int)($this->request->getGet('start') ?? 0)
        ];
        
        // Thực hiện tìm kiếm
        $results = $this->model->search($criteria, $options);
        
        // Tổng số kết quả
        $totalRecords = $this->model->countSearchResults($criteria);
        
        // Nếu yêu cầu là AJAX (từ DataTables)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'draw' => $this->request->getGet('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results
            ]);
        }
        
        // Nếu không phải AJAX, hiển thị trang tìm kiếm
        $viewData = [
            'breadcrumb' => $this->breadcrumb->add('Tìm kiếm', current_url())->render(),
            'title' => 'Tìm kiếm ' . $this->moduleName,
            'manhinh' => $results,
            'pager' => $this->model->pager,
            'keyword' => $keyword,
            'filters' => $filters,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\manhinh\Views\search', $viewData);
    }
    
    /**
     * Lấy màn hình theo camera
     */
    public function getByCamera()
    {
        $cameraId = $this->request->getGet('camera_id');
        
        if (empty($cameraId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID camera không hợp lệ'
            ]);
        }
        
        $data = $this->model->getByCameraId((int)$cameraId, true);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }
    
    /**
     * Xóa nhiều màn hình (chuyển vào thùng rác)
     */
    public function deleteMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn màn hình nào để xóa', true);
            return redirect()->to($this->moduleUrl);
        }
        
        $successCount = 0;
        
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        foreach ($idArray as $id) {
            if ($this->model->moveToRecycleBin($id)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã chuyển $successCount màn hình vào thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa màn hình', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Thay đổi trạng thái nhiều màn hình
     */
    public function statusMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        $newStatus = $this->request->getPost('status');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn màn hình nào để thay đổi trạng thái', true);
            return redirect()->to($this->moduleUrl);
        }
        
        if ($newStatus === null || !in_array($newStatus, ['0', '1'])) {
            $this->alert->set('warning', 'Trạng thái không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        $successCount = 0;
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        foreach ($idArray as $id) {
            if ($this->model->update($id, ['status' => $newStatus])) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $statusText = $newStatus == '1' ? 'hoạt động' : 'không hoạt động';
            $this->alert->set('success', "Đã chuyển $successCount màn hình sang trạng thái $statusText", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể thay đổi trạng thái', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
} 