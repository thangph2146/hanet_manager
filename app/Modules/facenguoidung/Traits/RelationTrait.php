<?php

namespace App\Modules\facenguoidung\Traits;

use CodeIgniter\I18n\Time;
use App\Modules\nguoidung\Models\NguoiDungModel;

trait RelationTrait
{
    protected $nguoiDungModel;

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        $this->nguoiDungModel = new NguoiDungModel();
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
        $nguoiDungList = $this->nguoiDungModel->getAllActive(100, 0, 'ten_nguoi_dung', 'ASC');
        
        // Lấy thông tin người dùng và sự kiện cho các tham số
        $nguoiDungInfo = null;
        $suKienInfo = null;
        
        // Lấy thông tin phòng khoa nếu có tham số
        $nguoiDungInfo = null;
        if (!empty($params['nguoi_dung_id'])) {
            $nguoiDungInfo = $this->nguoiDungModel->find($params['nguoi_dung_id']);
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
            'nguoi_dung_id' => $params['nguoi_dung_id'] ?? null,
            'nguoi_dung_info' => $nguoiDungInfo,
            'nguoiDungList' => $nguoiDungList,
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

        foreach ($data as &$item) {
            // Xử lý thông tin người dùng
            if (isset($item->nguoi_dung_id)) {
                $nguoiDung = $this->nguoiDungModel->find($item->nguoi_dung_id);
                if ($nguoiDung && !$nguoiDung->isDeleted()) {
                    $item->ten_nguoi_dung = $nguoiDung->getFullName();
                    $item->email = $nguoiDung->getEmail();
                    $item->so_dien_thoai = $nguoiDung->getMobilePhone();
                } else {
                    $item->ten_nguoi_dung = 'Không xác định';
                    $item->email = '';
                    $item->so_dien_thoai = '';
                }
            }

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
            'nguoi_dung_id' => $request->getGet('nguoi_dung_id'),
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
        
        if (!empty($params['nguoi_dung_id'])) {
            $criteria['nguoi_dung_id'] = $params['nguoi_dung_id'];
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
        
        // Lấy danh sách người dùng cho form select box
        $builder = $this->nguoiDungModel->builder();
        $builder->select('*');
        $builder->where('status', 1);
        $builder->where('deleted_at IS NULL');
        $builder->orderBy('FullName', 'ASC');
        $nguoiDungList = $builder->get()->getResult($this->nguoiDungModel->returnType);
        
        return [
            'data' => $data,
            'nguoiDungList' => $nguoiDungList,
            'module_name' => $module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
        ];
    }
} 