<?php

namespace App\Modules\manhinh\Traits;

use CodeIgniter\I18n\Time;
use App\Modules\camera\Models\CameraModel;
use App\Modules\template\Models\TemplateModel;

trait RelationTrait
{
    protected $cameraModel;
    protected $templateModel;

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        $this->cameraModel = new CameraModel();
        $this->templateModel = new TemplateModel();
    }

    /**
     * Chuẩn bị dữ liệu cho view
     */
    protected function prepareViewData($module_name, $data, $pager, $params)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        // Xử lý dữ liệu và thêm relation
        $processedData = $this->processData($data);
        
        // Lấy danh sách camera cho form select box 
        // Đảm bảo không hiển thị các camera đã xóa
        $cameraList = $this->cameraModel->getAllActive(100, 0, 'ten_camera', 'ASC');
        
        // Lấy danh sách template cho form select box
        // Đảm bảo không hiển thị các template đã xóa
        $templateList = $this->templateModel->getAllActive(100, 0, 'ten_template', 'ASC');
        
        // Lấy thông tin người dùng và sự kiện cho các tham số
        $nguoiDungInfo = null;
        $suKienInfo = null;
        
        // Lấy thông tin camera nếu có tham số
        $cameraInfo = null;
        if (!empty($params['camera_id'])) {
            $cameraInfo = $this->cameraModel->find($params['camera_id']);
        }
        
        // Lấy thông tin template nếu có tham số
        $templateInfo = null;
        if (!empty($params['template_id'])) {
            $templateInfo = $this->templateModel->find($params['template_id']);
        }
        
        return [
            'processedData' => $processedData,
            'pager' => $pager,
            'currentPage' => $params['page'],
            'perPage' => $params['perPage'],
            'total' => $params['total'],
            'sort' => $params['sort'],
            'order' => $params['order'],
            'keyword' => $params['keyword'],
            'status' => $params['status'],
            'camera_id' => $params['camera_id'] ?? null,
            'camera_info' => $cameraInfo,
            'cameraList' => $cameraList,
            'template_id' => $params['template_id'] ?? null,
            'template_info' => $templateInfo,
            'templateList' => $templateList,
            'title' => 'Danh sách ' . $this->title,
            'moduleUrl' => $this->moduleUrl,
            'title' => $this->title,
            'module_name' => $module_name
        ];
    }

    /**
     * Xử lý dữ liệu trước khi hiển thị
     */
    protected function processData($data)
    {
        if (empty($data)) {
            return [];
        }

        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();

        // Lấy danh sách ID duy nhất để tối ưu query
        $cameraIds = [];
        $templateIds = [];
        
        foreach ($data as $item) {
            if ($item->getCameraId() !== null) {
                $cameraIds[] = $item->getCameraId();
            }
            if ($item->getTemplateId() !== null) {
                $templateIds[] = $item->getTemplateId();
            }
        }
      
        // Lấy dữ liệu relation một lần duy nhất
        $cameras = [];
        $templates = [];
        
        if (!empty($cameraIds)) {
            $cameraIds = array_unique($cameraIds);
            $cameras = $this->cameraModel->find($cameraIds);
            
            // Đảm bảo chỉ lấy camera chưa bị xóa
            if (!empty($cameras)) {
                $cameras = array_filter($cameras, function($camera) {
                    return !$camera->isDeleted();
                });
            }
        }
        
        if (!empty($templateIds)) {
            $templateIds = array_unique($templateIds);
            $templates = $this->templateModel->find($templateIds);
            
            // Đảm bảo chỉ lấy template chưa bị xóa
            if (!empty($templates)) {
                $templates = array_filter($templates, function($template) {
                    return !$template->isDeleted();
                });
            }
        }

        // Chuyển đổi thành mảng key-value để dễ truy cập
        $cameraMap = !empty($cameras) ? array_column($cameras, null, 'camera_id') : [];
        $templateMap = !empty($templates) ? array_column($templates, null, 'template_id') : [];

        foreach ($data as &$item) {
            // Thêm thông tin camera
            if ($item->getCameraId() !== null && isset($cameraMap[$item->getCameraId()])) {
                $camera = $cameraMap[$item->getCameraId()];
                $item->camera = $camera;
                $item->ten_camera = $camera->getTenCamera();
                $item->ma_camera = $camera->getMaCamera();
            } else {
                $item->camera = null;
                $item->ten_camera = 'Chưa xác định';
                $item->ma_camera = '';
            }
            
            // Thêm thông tin template
            if ($item->getTemplateId() !== null && isset($templateMap[$item->getTemplateId()])) {
                $template = $templateMap[$item->getTemplateId()];
                $item->template = $template;
                $item->ten_template = $template->getTenTemplate();
                $item->ma_template = $template->getMaTemplate();
            } else {
                $item->template = null;
                $item->ten_template = 'Chưa xác định';
                $item->ma_template = '';
            }

            // Xử lý trạng thái sử dụng isActive() thay vì truy cập trực tiếp status
            $item->status_text = $item->isActive() ? 'Hoạt động' : 'Không hoạt động';
            $item->status_class = $item->isActive() ? 'status-active' : 'status-inactive';
        }

        return $data;
    }

    /**
     * Chuẩn bị tham số tìm kiếm
     */
    protected function prepareSearchParams($request)
    {
        return [
            'page' => (int)($request->getGet('page') ?? 1),
            'perPage' => (int)($request->getGet('perPage') ?? 10),
            'sort' => $request->getGet('sort') ?? 'created_at',
            'order' => $request->getGet('order') ?? 'DESC',
            'keyword' => $request->getGet('keyword') ?? '',
            'status' => $request->getGet('status'),
            'camera_id' => $request->getGet('camera_id'),
            'template_id' => $request->getGet('template_id'),
        ];
    }

    /**
     * Xử lý tham số tìm kiếm
     */
    protected function processSearchParams($params)
    {
        // Kiểm tra và điều chỉnh các tham số không hợp lệ
        if ($params['page'] < 1) $params['page'] = 1;
        if ($params['perPage'] < 1) $params['perPage'] = 10;

        // Xử lý status
        if ($params['status'] !== null && $params['status'] !== '') {
            $params['status'] = (int)$params['status'];
        }

        return $params;
    }

    /**
     * Xây dựng tham số tìm kiếm cho model
     */
    protected function buildSearchCriteria($params)
    {
        $criteria = [];
        
        if (!empty($params['keyword'])) {
            $criteria['keyword'] = $params['keyword'];
        }
        
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = $params['status'];
        }
        
        if (!empty($params['camera_id'])) {
            $criteria['camera_id'] = $params['camera_id'];
        }
        
        if (!empty($params['template_id'])) {
            $criteria['template_id'] = $params['template_id'];
        }

        return $criteria;
    }

    /**
     * Xây dựng tùy chọn tìm kiếm cho model
     */
    protected function buildSearchOptions($params)
    {
        return [
            'limit' => $params['perPage'],
            'offset' => ($params['page'] - 1) * $params['perPage'],
            'sort' => $params['sort'],
            'order' => $params['order']
        ];
    }
    
    /**
     * Chuẩn bị dữ liệu cho form
     * 
     * @param string $module_name Tên module
     * @param object $data Dữ liệu màn hình (nếu là cập nhật)
     * @return array Dữ liệu để truyền vào view
     */
    public function prepareFormData($module_name, $data = null)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy danh sách camera cho form select box
        // Đảm bảo không hiển thị các camera đã xóa
        $cameraList = $this->cameraModel->getAllActive(100, 0, 'ten_camera', 'ASC');
        
        // Lấy danh sách template cho form select box
        // Đảm bảo không hiển thị các template đã xóa
        $templateList = $this->templateModel->getAllActive(100, 0, 'ten_template', 'ASC');
        
        return [
            'data' => $data,
            'cameraList' => $cameraList,
            'templateList' => $templateList,
            'module_name' => $module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
        ];
    }
} 