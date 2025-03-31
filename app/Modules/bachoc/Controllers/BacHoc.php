<?php

namespace App\Modules\bachoc\Controllers;

use App\Controllers\BaseController;
use App\Modules\bachoc\Models\BacHocModel;
use App\Modules\bachoc\Entities\BacHoc as BacHocEntity;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class BacHoc extends BaseController
{
    protected $model;
    protected $entity;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->route_url = 'admin/' . $this->moduleName;
        
        $this->model = new BacHocModel(); 
        $this->entity = new BacHocEntity();

        $this->searchFields = ['ten_bac_hoc', 'ma_bac_hoc']; 
        $this->filterFields = ['status']; 
        $this->sortFields = ['ten_bac_hoc', 'ma_bac_hoc', 'status', 'created_at']; 
        $this->defaultSort = 'bac_hoc_id DESC'; 
        $this->perPage = config('Pager')->perPage ?? 10; 
        
        $this->model->prepareValidationRules('insert'); 
        $this->validationRules = $this->model->getValidationRules();
        $this->validationMessages = $this->model->getValidationMessages();
    }

    /**
     * Hiển thị danh sách bậc học (Index page).
     */
    public function index()
    {
        $params = $this->getSearchParams();
        
        // Lấy dữ liệu từ model sử dụng phương thức getByParams của BaseModel
        // BaseModel đã xử lý phân trang và trả về pager
        $items = $this->model->getByParams($params, [
            'limit' => $this->perPage,
            'page' => $params['page'] // page đã được lấy trong getSearchParams
        ]);
        
        // Lấy đối tượng pager từ model sau khi gọi getByParams
        $pager = $this->model->pager ?? service('pager'); // Lấy pager từ model hoặc tạo mới nếu chưa có
        
        $data = [
            'title' => 'Quản lý Bậc học', // Tiêu đề trang
            'items' => $items,
            'pager' => $pager->links(), // Tạo link phân trang
            'search' => $params['search'], // Giữ lại giá trị tìm kiếm
            'filters' => $params['filters'], // Giữ lại giá trị lọc
            'sort' => $params['sort'], // Giữ lại giá trị sắp xếp
            'searchFields' => $this->searchFields, // Truyền cấu hình tìm kiếm
            'filterFields' => $this->filterFields, // Truyền cấu hình lọc
            'sortFields' => $this->sortFields, // Truyền cấu hình sắp xếp
            'moduleName' => $this->moduleName, // Tên module để tạo URL
            'viewPath' => $this->viewPath, // Đường dẫn view
            'route_url' => $this->route_url, // Đường dẫn route
        ];
        
        // Sử dụng namespace để gọi view
        return view($this->viewPath . 'index', $data); 
    }

    /**
     * Hiển thị form thêm mới bậc học.
     */
    public function create()
    {
        $this->model->prepareValidationRules('insert');
        $this->validationRules = $this->model->getValidationRules();
        $this->validationMessages = $this->model->getValidationMessages();
        
        return parent::create(); 
    }

    /**
     * Hiển thị form chỉnh sửa bậc học.
     * Kế thừa phương thức edit() từ BaseController.
     */
    public function edit($id = null)
    {
        // Lấy dữ liệu item
        $item = $this->model->find($id);
        if (!$item) {
            return redirect()->to('/'. $this->moduleName .'')->with('error', 'Không tìm thấy dữ liệu.');
        }
        
        // Chuẩn bị validation rules cho 'update' scenario
        $postData = $this->request->getPost() ?: (array)$item;
        $postData[$this->model->primaryKey] = $id;
        $this->model->prepareValidationRules('update', $postData);
        $this->validationRules = $this->model->getValidationRules();
        $this->validationMessages = $this->model->getValidationMessages();
        
        if ($this->request->getMethod() === 'post') {
            return $this->update($id);
        }

        $data = [
            'title' => 'Chỉnh sửa ' . ucfirst($this->moduleName),
            'item' => $item,
            'validation' => \Config\Services::validation(),
            'route_url' => $this->route_url,
            'module_name' => $this->moduleName
        ];

        return view($this->viewPath . 'edit', $data);
    }

    /**
     * Xử lý cập nhật dữ liệu bậc học
     * Kế thừa phương thức update() từ BaseController
     */
    public function update($id)
    {
        
        if ($this->validate($this->validationRules, $this->validationMessages)) {
            $model = new $this->modelName();
            $postData = $this->request->getPost();
        
            if ($model->safeUpdate($id, $postData)) {
                return redirect()->to("/{$this->route_url}")->with('success', 'Cập nhật thành công');
            }
        }

        // Nếu có lỗi validation, quay lại form với dữ liệu cũ
        return redirect()->back()
            ->withInput()
            ->with('error', 'Vui lòng kiểm tra lại thông tin');
    }

    /**
     * Xóa bậc học (Soft delete).
     */
    public function delete($id = null)
    {
        // Lấy URL hiện tại trước khi xóa
        $currentUrl = previous_url() ?: "/{$this->route_url}";

        if (empty($id)) {
            return redirect()->to($currentUrl)->with('error', 'ID không hợp lệ');
        }

        $model = new $this->modelName();
        if (!$model->find($id)) {
            return redirect()->to($currentUrl)->with('error', 'Bản ghi không tồn tại');
        }

        if ($model->delete($id)) {
            return redirect()->to($currentUrl)->with('success', 'Xóa thành công');
        }

        return redirect()->to($currentUrl)->with('error', 'Có lỗi xảy ra khi xóa');
    }

    /**
     * Xem chi tiết bậc học.
     */
    public function show($id = null)
    {
        return parent::show($id);
    }
    
    /**
     * Lấy các tham số tìm kiếm, lọc, sắp xếp từ request.
     */
    protected function getSearchParams()
    {
        $search = $this->request->getGet('search') ?? '';
        $filters = $this->request->getGet('filters') ?? [];
        $sort = $this->request->getGet('sort') ?? $this->defaultSort; 
        $page = (int)($this->request->getGet('page') ?? 1);
        
        // Lọc ra các filter hợp lệ
        $validFilters = [];
        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $key => $value) {
                if (in_array($key, $this->filterFields) && $value !== '') {
                     $validFilters[$key] = $value;
                }
            }
        }
        
        $sortParts = explode(' ', $sort);
        $sortField = $sortParts[0] ?? 'bac_hoc_id';
        $sortOrder = strtoupper($sortParts[1] ?? 'DESC');
        if (!in_array($sortField, $this->sortFields) || !in_array($sortOrder, ['ASC', 'DESC'])) {
             $sort = $this->defaultSort; 
        }

        return [
            'search' => trim($search),
            'filters' => $validFilters,
            'sort' => $sort,
            'page' => max(1, $page) // Đảm bảo page luôn >= 1
        ];
    }
    
    // Implement export methods
    public function exportPdf()
    {
        $params = $this->getSearchParams();
        $items = $this->model->getAllByParams($params);

         return parent::exportPdf(); 
    }
    
    public function exportExcel()
    {
        $params = $this->getSearchParams();
        $items = $this->model->getAllByParams($params);

        return parent::exportExcel();
    }
    
    public function exportDeletedPdf()
    {
         $params = $this->getSearchParams();
         $items = $this->model->onlyDeleted()->getAllByParams($params);
        
         return parent::exportDeletedPdf(); 
    }
    
    public function exportDeletedExcel()
    {
         $params = $this->getSearchParams();
         $items = $this->model->onlyDeleted()->getAllByParams($params);
        
         return parent::exportDeletedExcel();
    }

    //=========================================================================
    // EXPORT HELPER METHODS (Override from BaseController)
    //=========================================================================

    /**
     * Trả về mảng chứa tên các cột header cho file Excel.
     * 
     * @override
     * @return array
     */
    protected function getExportHeaders(): array
    {
        // Định nghĩa các cột bạn muốn xuất cho module Bậc học
        return [
            'Mã Bậc Học', 
            'Tên Bậc Học', 
            'Trạng Thái',
            'Ngày Tạo'
        ]; 
    }

    /**
     * Trả về mảng chứa dữ liệu cho một hàng trong file Excel.
     *
     * @override
     * @param object|array $item Dữ liệu của một bậc học (Entity hoặc array)
     * @return array
     */
    protected function getExportRowData($item): array
    {
        // Lấy dữ liệu tương ứng với headers
        $statusLabel = '';
        if (is_object($item) && method_exists($item, 'getStatusLabel')) {
            // Lấy text từ HTML label, ví dụ: 'Hoạt động' từ '<span...>Hoạt động</span>'
            $statusLabel = strip_tags($item->getStatusLabel()); 
        } elseif (is_array($item)) {
            $statusLabel = ($item['status'] ?? 0) == 1 ? 'Hoạt động' : 'Không hoạt động';
        }
        
        $createdAt = '';
         if (is_object($item) && method_exists($item, 'getCreatedAtFormatted')) {
            $createdAt = $item->getCreatedAtFormatted('d/m/Y H:i:s');
        } elseif (is_array($item) && isset($item['created_at'])) {
             $createdAt = \CodeIgniter\I18n\Time::parse($item['created_at'])->toLocalizedString('dd/MM/yyyy HH:mm:ss');
        }

        return [
            is_object($item) ? $item->ma_bac_hoc : ($item['ma_bac_hoc'] ?? ''),
            is_object($item) ? $item->ten_bac_hoc : ($item['ten_bac_hoc'] ?? ''),
            $statusLabel,
            $createdAt
        ];
    }

    /**
     * Trả về mảng chứa tên các cột header cho file Excel (dữ liệu đã xóa).
     *
     * @override
     * @return array
     */
    protected function getDeletedExportHeaders(): array
    {
        // Thêm cột 'Ngày xóa' vào header
        $headers = $this->getExportHeaders(); 
        $headers[] = 'Ngày Xóa'; 
        return $headers;
    }

    /**
     * Trả về mảng chứa dữ liệu cho một hàng trong file Excel (dữ liệu đã xóa).
     *
     * @override
     * @param object|array $item Dữ liệu của một bậc học đã xóa
     * @return array
     */
    protected function getDeletedExportRowData($item): array
    {
        $rowData = $this->getExportRowData($item); // Lấy dữ liệu cơ bản

        // Thêm dữ liệu ngày xóa
        $deletedAt = '';
        if (is_object($item) && method_exists($item, 'getDeletedAtFormatted')) {
            $deletedAt = $item->getDeletedAtFormatted('d/m/Y H:i:s');
        } elseif (is_array($item) && isset($item['deleted_at'])) {
            $deletedAt = \CodeIgniter\I18n\Time::parse($item['deleted_at'])->toLocalizedString('dd/MM/yyyy HH:mm:ss');
        }
        $rowData[] = $deletedAt;

        return $rowData;
    }
}
