<?php

namespace App\Modules\loaisukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\loaisukien\Models\LoaisukienModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Alert;
use CodeIgniter\Database\Exceptions\DataException;

class Loaisukien extends BaseController
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
        
        $this->model = new LoaisukienModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('loaisukien');
        $this->moduleName = 'Loại sự kiện';
        
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
            'loaisukien' => $data,
            'pager' => $pager,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName,
            'alert' => $this->alert->get()
        ];
        
        return view('App\Modules\loaisukien\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Danh sách', $this->moduleUrl)
                         ->add('Thêm mới', current_url());
        
        return view('App\Modules\loaisukien\Views\new', [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm mới ' . $this->moduleName,
            'method' => 'POST',
            'action' => $this->moduleUrl . '/create',
            'data' => null,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName,
        ]);
    }
    
    /**
     * Xử lý dữ liệu gửi từ form tạo mới
     */
    public function create()
    {
        // Validate dữ liệu đầu vào
        $input = $this->request->getPost();
        
        // Kiểm tra tên đã tồn tại chưa
        if ($this->model->isNameExists($input['ten_loai_su_kien'])) {
            $this->alert->set('error', 'Tên loại sự kiện đã tồn tại.');
            return redirect()->to($this->moduleUrl . '/new')->withInput();
        }
        
        // Kiểm tra mã đã tồn tại chưa (nếu có)
        if (!empty($input['ma_loai_su_kien']) && $this->model->isCodeExists($input['ma_loai_su_kien'])) {
            $this->alert->set('error', 'Mã loại sự kiện đã tồn tại.');
            return redirect()->to($this->moduleUrl . '/new')->withInput();
        }
        
        try {
            if ($this->model->insert($input)) {
                $this->alert->set('success', 'Thêm mới thành công.');
                return redirect()->to($this->moduleUrl);
            } else {
                $this->alert->set('error', 'Thêm mới thất bại. Vui lòng thử lại.');
                return redirect()->to($this->moduleUrl . '/new')->withInput();
            }
        } catch (DataException $e) {
            $this->alert->set('error', 'Lỗi dữ liệu: ' . $e->getMessage());
            return redirect()->to($this->moduleUrl . '/new')->withInput();
        }
    }
    
    /**
     * Hiển thị chi tiết
     */
    public function view($id = null)
    {
        if ($id === null) {
            $this->alert->set('error', 'ID không hợp lệ.');
            return redirect()->to($this->moduleUrl);
        }
        
        $data = $this->model->find($id);
        
        if ($data === null) {
            $this->alert->set('error', 'Không tìm thấy ' . $this->moduleName . '.');
            return redirect()->to($this->moduleUrl);
        }
        
        $this->breadcrumb->add('Danh sách', $this->moduleUrl)
                         ->add('Chi tiết', current_url());
        
        return view('App\Modules\loaisukien\Views\view', [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'loaisukien' => $data,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName,
        ]);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if ($id === null) {
            $this->alert->set('error', 'ID không hợp lệ.');
            return redirect()->to($this->moduleUrl);
        }
        
        $data = $this->model->find($id);
        
        if ($data === null) {
            $this->alert->set('error', 'Không tìm thấy dữ liệu.');
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Danh sách', $this->moduleUrl)
                         ->add('Chỉnh sửa', current_url());
        
        return view('App\Modules\loaisukien\Views\edit', [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'method' => 'PUT',
            'action' => $this->moduleUrl . '/update/' . $id,
            'data' => $data,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName,
        ]);
    }
    
    /**
     * Xử lý dữ liệu gửi từ form chỉnh sửa
     */
    public function update($id = null)
    {
        if ($id === null) {
            $this->alert->set('error', 'ID không hợp lệ.');
            return redirect()->to($this->moduleUrl);
        }
        
        $existingData = $this->model->find($id);
        
        if ($existingData === null) {
            $this->alert->set('error', 'Không tìm thấy dữ liệu.');
            return redirect()->to($this->moduleUrl);
        }
        
        // Validate dữ liệu đầu vào
        $input = $this->request->getPost();
        
        // Kiểm tra tên đã tồn tại chưa
        if ($this->model->isNameExists($input['ten_loai_su_kien'], $id)) {
            $this->alert->set('error', 'Tên loại sự kiện đã tồn tại.');
            return redirect()->to($this->moduleUrl . '/edit/' . $id)->withInput();
        }
        
        // Kiểm tra mã đã tồn tại chưa (nếu có)
        if (!empty($input['ma_loai_su_kien']) && $this->model->isCodeExists($input['ma_loai_su_kien'], $id)) {
            $this->alert->set('error', 'Mã loại sự kiện đã tồn tại.');
            return redirect()->to($this->moduleUrl . '/edit/' . $id)->withInput();
        }
        
        try {
            if ($this->model->update($id, $input)) {
                $this->alert->set('success', 'Cập nhật thành công.');
                return redirect()->to($this->moduleUrl);
            } else {
                $this->alert->set('error', 'Cập nhật thất bại. Vui lòng thử lại.');
                return redirect()->to($this->moduleUrl . '/edit/' . $id)->withInput();
            }
        } catch (DataException $e) {
            $this->alert->set('error', 'Lỗi dữ liệu: ' . $e->getMessage());
            return redirect()->to($this->moduleUrl . '/edit/' . $id)->withInput();
        }
    }
    
    /**
     * Xóa (chuyển vào thùng rác)
     */
    public function delete($id = null)
    {
        if ($id === null) {
            $this->alert->set('error', 'ID không hợp lệ.');
            return redirect()->to($this->moduleUrl);
        }
        
        // Thay vì xóa, chúng ta cập nhật trạng thái bin
        if ($this->model->moveToRecycleBin($id)) {
            $this->alert->set('success', 'Đã chuyển vào thùng rác.');
        } else {
            $this->alert->set('error', 'Không thể xóa. Vui lòng thử lại.');
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Hiển thị danh sách các mục đã xóa (thùng rác)
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thùng rác', current_url());
        
        $data = $this->model->getAllInRecycleBin();
        
        return view('App\Modules\loaisukien\Views\listdeleted', [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thùng rác',
            'loaisukien' => $data,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName,
        ]);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if ($id === null) {
            $this->alert->set('error', 'ID không hợp lệ.');
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Đã khôi phục thành công.');
        } else {
            $this->alert->set('error', 'Không thể khôi phục. Vui lòng thử lại.');
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Khôi phục nhiều mục từ thùng rác
     */
    public function restoreMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            $this->alert->set('error', 'Không có mục nào được chọn.');
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        $success = 0;
        foreach ($selectedIds as $id) {
            if ($this->model->restoreFromRecycleBin($id)) {
                $success++;
            }
        }
        
        if ($success > 0) {
            $this->alert->set('success', "Đã khôi phục $success mục.");
        } else {
            $this->alert->set('error', 'Không thể khôi phục các mục đã chọn.');
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn nhiều mục
     */
    public function permanentDeleteMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            $this->alert->set('error', 'Không có mục nào được chọn.');
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        $success = 0;
        foreach ($selectedIds as $id) {
            if ($this->model->delete($id, true)) {
                $success++;
            }
        }
        
        if ($success > 0) {
            $this->alert->set('success', "Đã xóa vĩnh viễn $success mục.");
        } else {
            $this->alert->set('error', 'Không thể xóa vĩnh viễn các mục đã chọn.');
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một mục
     */
    public function permanentDelete($id = null)
    {
        if ($id === null) {
            $this->alert->set('error', 'ID không hợp lệ.');
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        if ($this->model->delete($id, true)) {
            $this->alert->set('success', 'Đã xóa vĩnh viễn.');
        } else {
            $this->alert->set('error', 'Không thể xóa vĩnh viễn. Vui lòng thử lại.');
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Tìm kiếm
     */
    public function search()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Tìm kiếm', current_url());
        
        // Lấy thông tin tìm kiếm
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? '';
        
        // Thiết lập tiêu chí tìm kiếm
        $criteria = ['filters' => ['bin' => 0]];
        
        if (!empty($search)) {
            $criteria['search'] = $search;
        }
        
        if ($status !== '') {
            $criteria['filters']['status'] = (int)$status;
        }
        
        // Thiết lập tùy chọn
        $options = [
            'sort' => 'updated_at',
            'sort_direction' => 'desc',
            'paginate' => true,
            'page' => (int)($this->request->getGet('page') ?? 1),
            'per_page' => (int)($this->request->getGet('per_page') ?? 20),
        ];
        
        // Thực hiện tìm kiếm
        $result = $this->model->search($criteria, $options);
        
        return view('App\Modules\loaisukien\Views\index', [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Tìm kiếm',
            'items' => $result['data'],
            'pager' => $result['pager'],
            'search' => $search,
            'status' => $status,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName,
        ]);
    }
    
    /**
     * Xóa nhiều mục
     */
    public function deleteMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            $this->alert->set('error', 'Không có mục nào được chọn.');
            return redirect()->to($this->moduleUrl);
        }
        
        $success = 0;
        foreach ($selectedIds as $id) {
            if ($this->model->moveToRecycleBin($id)) {
                $success++;
            }
        }
        
        if ($success > 0) {
            $this->alert->set('success', "Đã chuyển $success mục vào thùng rác.");
        } else {
            $this->alert->set('error', 'Không thể xóa các mục đã chọn.');
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Cập nhật trạng thái cho nhiều mục
     */
    public function statusMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        $newStatus = (int)$this->request->getPost('status');
        
        if (empty($selectedIds)) {
            $this->alert->set('error', 'Không có mục nào được chọn.');
            return redirect()->to($this->moduleUrl);
        }
        
        if (!in_array($newStatus, [0, 1])) {
            $this->alert->set('error', 'Trạng thái không hợp lệ.');
            return redirect()->to($this->moduleUrl);
        }
        
        $success = 0;
        foreach ($selectedIds as $id) {
            // Lấy dữ liệu hiện tại
            $item = $this->model->find($id);
            if ($item) {
                // Cập nhật trạng thái
                $item->setStatus($newStatus);
                if ($this->model->save($item)) {
                    $success++;
                }
            }
        }
        
        if ($success > 0) {
            $this->alert->set('success', "Đã cập nhật trạng thái cho $success mục.");
        } else {
            $this->alert->set('error', 'Không thể cập nhật trạng thái cho các mục đã chọn.');
        }
        
        return redirect()->to($this->moduleUrl);
    }
} 