<?php

namespace App\Modules\dangkysukien\Traits;

use CodeIgniter\I18n\Time;

trait RelationTrait
{
    protected $fields = [
        'ten_form' => 'getTenForm',
        'mo_ta' => 'getMoTa',
        'ten_su_kien' => 'getTenSuKien',
        'count_fields' => 'countFields',
        'count_required_fields' => 'countRequiredFields',
        'bat_buoc_dien' => 'getBatBuocDien',
        'hien_thi_cong_khai' => 'getHienThiCongKhai',
        'so_lan_su_dung' => 'getSoLanSuDung',
        'status' => 'getStatus',
        'status_text' => 'getStatusText',
        'created_at_formatted' => 'getCreatedAt',
        'updated_at_formatted' => 'getUpdatedAt',
        'deleted_at_formatted' => 'getDeletedAt',
        'is_deleted' => 'isDeleted',
    ];

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
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
        
        
        return [
            'processedData' => $processedData,
            'pager' => $pager,
            'currentPage' => $params['page'],
            'perPage' => $params['perPage'],
            'total' => $params['total'],
            'sort' => $params['sort'],
            'order' => $params['order'],
            'keyword' => $params['keyword'],
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

            // Thêm các thuộc tính đã định dạng vào các thuộc tính mới
            foreach ($this->fields as $key => $value) {
                // Kiểm tra phương thức tồn tại trước khi gọi
                if (method_exists($item, $value)) {
                    // Thêm trực tiếp như một thuộc tính của đối tượng
                    // thay vì cố gắng sửa đổi mảng attributes được bảo vệ
                    $item->$key = $item->$value();
                }
            }
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
            'sukien_id' => $request->getGet('sukien_id'),
            'loai_nguoi_dang_ky' => $request->getGet('loai_nguoi_dang_ky'),
            'status' => $request->getGet('status'),
            'hinh_thuc_tham_gia' => $request->getGet('hinh_thuc_tham_gia'),
            'attendance_status' => $request->getGet('attendance_status'),
            'diem_danh_bang' => $request->getGet('diem_danh_bang'),
            'da_check_in' => $request->getGet('da_check_in'),
            'da_check_out' => $request->getGet('da_check_out')
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

        if (!empty($params['sukien_id'])) {
            $criteria['sukien_id'] = (int)$params['sukien_id'];
        }

        if (!empty($params['loai_nguoi_dang_ky'])) {
            $criteria['loai_nguoi_dang_ky'] = $params['loai_nguoi_dang_ky'];
        }

        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = (int)$params['status'];
        }

        if (!empty($params['hinh_thuc_tham_gia'])) {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'];
        }

        if (!empty($params['attendance_status'])) {
            $criteria['attendance_status'] = $params['attendance_status'];
        }

        if (!empty($params['diem_danh_bang'])) {
            $criteria['diem_danh_bang'] = $params['diem_danh_bang'];
        }

        if (isset($params['da_check_in']) && $params['da_check_in'] !== '') {
            $criteria['da_check_in'] = (bool)$params['da_check_in'];
        }

        if (isset($params['da_check_out']) && $params['da_check_out'] !== '') {
            $criteria['da_check_out'] = (bool)$params['da_check_out'];
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
     * @param object $data Dữ liệu diễn giả (nếu là cập nhật)
     * @return array Dữ liệu để truyền vào view
     */
    public function prepareFormData($module_name, $data = null)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        return [
            'data' => $data,
            'module_name' => $module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
        ];
    }
} 