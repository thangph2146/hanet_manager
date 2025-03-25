<?php

namespace App\Modules\sukiendiengia\Traits;

use CodeIgniter\I18n\Time;
use App\Modules\sukien\Models\SuKienModel;
use App\Modules\diengia\Models\DienGiaModel;

trait RelationTrait
{
    protected $suKienModel;
    protected $dienGiaModel;

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        if (!$this->suKienModel) {
            $this->suKienModel = new SuKienModel();
        }
        if (!$this->dienGiaModel) {
            $this->dienGiaModel = new DienGiaModel();
        }
    }

    /**
     * Chuẩn bị dữ liệu cho view
     */
    protected function prepareViewData($data, $pager, $params)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        // Xử lý dữ liệu và thêm relation
        $processedData = $this->processData($data);
        
        // Lấy thông tin diễn giả và sự kiện cho các tham số
        $dienGiaInfo = null;
        $suKienInfo = null;
        
        if (!empty($params['dien_gia_id'])) {
            $dienGiaInfo = $this->dienGiaModel->find($params['dien_gia_id']);
        }
        
        if (!empty($params['su_kien_id'])) {
            $suKienInfo = $this->suKienModel->find($params['su_kien_id']);
        }
        
        return [
            'suKienDienGias' => $processedData,
            'pager' => $pager,
            'currentPage' => $params['page'],
            'perPage' => $params['perPage'],
            'total' => $params['total'],
            'sort' => $params['sort'],
            'order' => $params['order'],
            'keyword' => $params['keyword'],
            'deleted' => $params['deleted'],
            'dien_gia_id' => $params['dien_gia_id'],
            'dien_gia_info' => $dienGiaInfo,
            'su_kien_id' => $params['su_kien_id'],
            'su_kien_info' => $suKienInfo,
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Danh sách ' . $this->moduleName,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName
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
        }
        
        if (!empty($dienGiaIds)) {
            $dienGiaIds = array_unique($dienGiaIds);
            $dienGias = $this->dienGiaModel->find($dienGiaIds);
        }

        // Chuyển đổi thành mảng key-value để dễ truy cập
        $suKienMap = array_column($suKiens, null, 'su_kien_id');
        $dienGiaMap = array_column($dienGias, null, 'dien_gia_id');

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
            } else {
                $item->su_kien = null;
            }

            // Thêm thông tin diễn giả
            if (!empty($item->dien_gia_id) && isset($dienGiaMap[$item->dien_gia_id])) {
                $item->dien_gia = $dienGiaMap[$item->dien_gia_id];
            } else {
                $item->dien_gia = null;
            }

            // Xử lý trạng thái xóa
            $item->deleted_text = !empty($item->deleted_at) ? 'Đã xóa' : 'Đang sử dụng';
            $item->deleted_class = !empty($item->deleted_at) ? 'deleted-true' : 'deleted-false';
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
            'deleted' => $request->getGet('deleted') ?? 0,
            'dien_gia_id' => $request->getGet('dien_gia_id'),
            'su_kien_id' => $request->getGet('su_kien_id')
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

        // Xử lý deleted
        if ($params['deleted'] !== null && $params['deleted'] !== '') {
            $params['deleted'] = (int)$params['deleted'];
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
        
        if (isset($params['deleted']) && $params['deleted'] == 1) {
            $criteria['deleted'] = true;
        }
        
        if (!empty($params['dien_gia_id'])) {
            $criteria['dien_gia_id'] = $params['dien_gia_id'];
        }
        
        if (!empty($params['su_kien_id'])) {
            $criteria['su_kien_id'] = $params['su_kien_id'];
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
} 