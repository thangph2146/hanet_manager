<?php

namespace App\Modules\diengia\Traits;

use CodeIgniter\I18n\Time;

trait RelationTrait
{

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
            'sort' => $request->getGet('sort') ?? 'thu_tu',
            'order' => $request->getGet('order') ?? 'ASC',
            'keyword' => $request->getGet('keyword') ?? '',
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