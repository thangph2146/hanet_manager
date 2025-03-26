<?php

namespace App\Modules\nganh\Traits;

use CodeIgniter\I18n\Time;
use App\Modules\phongkhoa\Models\PhongKhoaModel;

trait RelationTrait
{
    protected $phongKhoaModel;

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        $this->phongKhoaModel = new PhongKhoaModel();
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
        
        // Lấy danh sách phòng khoa cho form select box
        $phongKhoaList = $this->phongKhoaModel->getAllActive(100, 0, 'ten_phong_khoa', 'ASC');
        
        // Lấy thông tin người dùng và sự kiện cho các tham số
        $nguoiDungInfo = null;
        $suKienInfo = null;
        
        // Lấy thông tin phòng khoa nếu có tham số
        $phongKhoaInfo = null;
        if (!empty($params['phong_khoa_id'])) {
            $phongKhoaInfo = $this->phongKhoaModel->find($params['phong_khoa_id']);
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
            'phong_khoa_id' => $params['phong_khoa_id'] ?? null,
            'phong_khoa_info' => $phongKhoaInfo,
            'phongKhoaList' => $phongKhoaList,
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
        $phongKhoaIds = [];
        
        foreach ($data as $item) {
            if (!empty($item->phong_khoa_id)) {
                $phongKhoaIds[] = $item->phong_khoa_id;
            }
        }
      
        // Lấy dữ liệu relation một lần duy nhất
        $phongKhoas = [];
        
        if (!empty($phongKhoaIds)) {
            $phongKhoaIds = array_unique($phongKhoaIds);
            $phongKhoas = $this->phongKhoaModel->find($phongKhoaIds);
        }

        // Chuyển đổi thành mảng key-value để dễ truy cập
        $phongKhoaMap = !empty($phongKhoas) ? array_column($phongKhoas, null, 'phong_khoa_id') : [];

        foreach ($data as &$item) {
          
            // Xử lý thời gian tạo
            if (!empty($item->created_at)) {
                try {
                    $item->created_at = $item->created_at instanceof Time ? 
                        $item->created_at : 
                        Time::parse($item->created_at);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi xử lý thời gian tạo: ' . $e->getMessage());
                    $item->created_at = null;
                }
            }

            // Xử lý thời gian cập nhật
            if (!empty($item->updated_at)) {
                try {
                    $item->updated_at = $item->updated_at instanceof Time ? 
                        $item->updated_at : 
                        Time::parse($item->updated_at);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi xử lý thời gian cập nhật: ' . $e->getMessage());
                    $item->updated_at = null;
                }
            }

            // Xử lý thời gian xóa
            if (!empty($item->deleted_at)) {
                try {
                    $item->deleted_at = $item->deleted_at instanceof Time ? 
                        $item->deleted_at : 
                        Time::parse($item->deleted_at);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi xử lý thời gian xóa: ' . $e->getMessage());
                    $item->deleted_at = null;
                }
            }

            // Thêm thông tin phòng khoa
            if (!empty($item->phong_khoa_id) && isset($phongKhoaMap[$item->phong_khoa_id])) {
                $item->phong_khoa = $phongKhoaMap[$item->phong_khoa_id];
                $item->ten_phong_khoa = $phongKhoaMap[$item->phong_khoa_id]->getTenPhongKhoa();
                $item->ma_phong_khoa = $phongKhoaMap[$item->phong_khoa_id]->getMaPhongKhoa();
            } else {
                $item->phong_khoa = null;
                $item->ten_phong_khoa = 'Chưa xác định';
                $item->ma_phong_khoa = '';
            }

            // Xử lý trạng thái
            $item->status_text = $item->status == 1 ? 'Hoạt động' : 'Không hoạt động';
            $item->status_class = $item->status == 1 ? 'status-active' : 'status-inactive';
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
            'phong_khoa_id' => $request->getGet('phong_khoa_id'),
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
        
        if (!empty($params['phong_khoa_id'])) {
            $criteria['phong_khoa_id'] = $params['phong_khoa_id'];
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
     * @param object $data Dữ liệu khóa học (nếu là cập nhật)
     * @return array Dữ liệu để truyền vào view
     */
    public function prepareFormData($module_name, $data = null)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy danh sách phòng khoa cho form select box
        $phongKhoaList = $this->phongKhoaModel->getAllActive(100, 0, 'ten_phong_khoa', 'ASC');
        
        return [
            'data' => $data,
            'phongKhoaList' => $phongKhoaList,
            'module_name' => $module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
        ];
    }
} 