<?php

namespace App\Modules\sukiendiengia\Traits; 

use CodeIgniter\I18n\Time;
use App\Modules\sukiendiengia\Models\SuKienDienGiaModel;
use App\Modules\sukien\Models\SuKienModel;
use App\Modules\diengia\Models\DienGiaModel;

trait RelationTrait
{
    protected $suKienDienGiaModel;
    protected $suKienModel;
    protected $dienGiaModel;
    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        $this->suKienDienGiaModel = new SuKienDienGiaModel();
        $this->suKienModel = new SuKienModel();
        $this->dienGiaModel = new DienGiaModel();
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
        
        // Lấy danh sách liên kết sự kiện và diễn giả cho form select box
        $suKienList = $this->suKienModel->getAllActive(100, 0, 'ten_su_kien', 'ASC');
        $dienGiaList = $this->dienGiaModel->getAllActive(100, 0, 'ten_dien_gia', 'ASC');
        
        // Lấy thông tin sự kiện và diễn giả cho các tham số
        $suKienInfo = null;
        $dienGiaInfo = null;
        
        // Lấy thông tin liên kết sự kiện và diễn giả nếu có tham số
        $suKienDienGiaInfo = null;
        if (!empty($params['su_kien_dien_gia_id'])) {
            $suKienDienGiaInfo = $this->suKienDienGiaModel->find($params['su_kien_dien_gia_id']);
        }
        
        // Lấy thông tin sự kiện nếu có tham số
        if (!empty($params['su_kien_id'])) {
            $suKienInfo = $this->suKienModel->find($params['su_kien_id']);
        }
        
        // Lấy thông tin diễn giả nếu có tham số
        if (!empty($params['dien_gia_id'])) {
            $dienGiaInfo = $this->dienGiaModel->find($params['dien_gia_id']);
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
            'status' => $params['status'] ?? null,
            'su_kien_id' => $params['su_kien_id'] ?? null,
            'dien_gia_id' => $params['dien_gia_id'] ?? null,
            'su_kien_info' => $suKienInfo,
            'dien_gia_info' => $dienGiaInfo,
            'sukienList' => $suKienList,
            'dienGiaList' => $dienGiaList,
            'su_kien_dien_gia_info' => $suKienDienGiaInfo,
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
        $suKienIds = [];
        $dienGiaIds = [];
        
        foreach ($data as $item) {
            if (!empty($item->su_kien_id)) {
                $suKienIds[] = $item->su_kien_id;
            }
            if (!empty($item->dien_gia_id)) {
                $dienGiaIds[] = $item->dien_gia_id;
            }
        }
      
        // Lấy dữ liệu relation một lần duy nhất
        $suKiens = [];
        $dienGias = [];
        
        if (!empty($suKienIds)) {
            $suKienIds = array_unique($suKienIds);
            $suKiens = $this->suKienModel->find($suKienIds);
            
            // Đảm bảo chỉ lấy sự kiện chưa bị xóa
            if (!empty($suKiens)) {
                $suKiens = array_filter($suKiens, function($suKien) {
                    return !$suKien->isDeleted();
                });
            }
        }
        
        if (!empty($dienGiaIds)) {
            $dienGiaIds = array_unique($dienGiaIds);
            $dienGias = $this->dienGiaModel->find($dienGiaIds);
            
            // Đảm bảo chỉ lấy diễn giả chưa bị xóa
            if (!empty($dienGias)) {
                $dienGias = array_filter($dienGias, function($dienGia) {
                    return !$dienGia->isDeleted();
                });
            }
        }

        // Chuyển đổi thành mảng key-value để dễ truy cập
        $suKienMap = !empty($suKiens) ? array_column($suKiens, null, 'su_kien_id') : [];
        $dienGiaMap = !empty($dienGias) ? array_column($dienGias, null, 'dien_gia_id') : [];

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

            // Thêm thông tin sự kiện
            if (!empty($item->su_kien_id) && isset($suKienMap[$item->su_kien_id])) {
                $item->su_kien = $suKienMap[$item->su_kien_id];
                $item->ten_su_kien = $suKienMap[$item->su_kien_id]->ten_su_kien ?? 'Chưa xác định';
            } else {
                $item->su_kien = null;
                $item->ten_su_kien = 'Chưa xác định';
            }
            
            // Thêm thông tin diễn giả
            if (!empty($item->dien_gia_id) && isset($dienGiaMap[$item->dien_gia_id])) {
                $item->dien_gia = $dienGiaMap[$item->dien_gia_id];
                $item->ten_dien_gia = $dienGiaMap[$item->dien_gia_id]->ten_dien_gia ?? 'Chưa xác định';
                $item->chuc_danh = $dienGiaMap[$item->dien_gia_id]->chuc_danh ?? '';
                $item->to_chuc = $dienGiaMap[$item->dien_gia_id]->to_chuc ?? '';
            } else {
                $item->dien_gia = null;
                $item->ten_dien_gia = 'Chưa xác định';
                $item->chuc_danh = '';
                $item->to_chuc = '';
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
            'su_kien_id' => $request->getGet('su_kien_id'),
            'dien_gia_id' => $request->getGet('dien_gia_id'),
            'status' => $request->getGet('status') ?? null,
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
        
        if (!empty($params['su_kien_id'])) {
            $criteria['su_kien_id'] = $params['su_kien_id'];
        }
        
        if (!empty($params['dien_gia_id'])) {
            $criteria['dien_gia_id'] = $params['dien_gia_id'];
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
     * @param object $data Dữ liệu liên kết (nếu là cập nhật)
     * @return array Dữ liệu để truyền vào view
     */
    public function prepareFormData($module_name, $data = null)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy danh sách sự kiện và diễn giả cho form select box
        $suKienList = $this->suKienModel->getAllActive(100, 0, 'ten_su_kien', 'ASC');
        $dienGiaList = $this->dienGiaModel->getAllActive(100, 0, 'ten_dien_gia', 'ASC');
        
        return [
            'data' => $data,
            'sukienList' => $suKienList,
            'dienGiaList' => $dienGiaList,
            'module_name' => $module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
        ];
    }
} 