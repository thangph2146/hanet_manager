<?php

namespace App\Modules\thamgiasukien\Traits;

use CodeIgniter\I18n\Time;
use App\Modules\sukien\Models\SuKienModel;
use App\Modules\nguoidung\Models\NguoiDungModel;

trait RelationTrait
{
    protected $suKienModel;
    protected $nguoiDungModel;

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        if (!$this->suKienModel) {
            $this->suKienModel = new SuKienModel();
        }
        if (!$this->nguoiDungModel) {
            $this->nguoiDungModel = new NguoiDungModel();
        }
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
        
        // Lấy thông tin người dùng và sự kiện cho các tham số
        $nguoiDungInfo = null;
        $suKienInfo = null;
        
        if (!empty($params['nguoi_dung_id'])) {
            $nguoiDungInfo = $this->nguoiDungModel->find($params['nguoi_dung_id']);
        }
        
        if (!empty($params['su_kien_id'])) {
            $suKienInfo = $this->suKienModel->find($params['su_kien_id']);
        }
        
        return [
            'thamGiaSuKiens' => $processedData,
            'pager' => $pager,
            'currentPage' => $params['page'],
            'perPage' => $params['perPage'],
            'total' => $params['total'],
            'sort' => $params['sort'],
            'order' => $params['order'],
            'keyword' => $params['keyword'],
            'status' => $params['status'],
            'nguoi_dung_id' => $params['nguoi_dung_id'],
            'nguoi_dung_info' => $nguoiDungInfo,
            'su_kien_id' => $params['su_kien_id'],
            'su_kien_info' => $suKienInfo,
            'phuong_thuc_diem_danh' => $params['phuong_thuc_diem_danh'],
            'breadcrumb' => $this->breadcrumb->render(),
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
        $nguoiDungIds = [];
        foreach ($data as $item) {
            if (!empty($item->su_kien_id)) {
                $suKienIds[] = $item->su_kien_id;
            }
            if (!empty($item->nguoi_dung_id)) {
                $nguoiDungIds[] = $item->nguoi_dung_id;
            }
        }

        // Lấy dữ liệu relation một lần duy nhất
        $suKiens = [];
        $nguoiDungs = [];
        
        if (!empty($suKienIds)) {
            $suKienIds = array_unique($suKienIds);
            $suKiens = $this->suKienModel->find($suKienIds);
        }
        
        if (!empty($nguoiDungIds)) {
            $nguoiDungIds = array_unique($nguoiDungIds);
            $nguoiDungs = $this->nguoiDungModel->find($nguoiDungIds);
        }

        // Chuyển đổi thành mảng key-value để dễ truy cập
        $suKienMap = array_column($suKiens, null, 'su_kien_id');
        $nguoiDungMap = array_column($nguoiDungs, null, 'nguoi_dung_id');

        foreach ($data as &$item) {
            // Xử lý thời gian điểm danh
            if (!empty($item->thoi_gian_diem_danh)) {
                try {
                    $item->thoi_gian_diem_danh = $item->thoi_gian_diem_danh instanceof Time ? 
                        $item->thoi_gian_diem_danh : 
                        Time::parse($item->thoi_gian_diem_danh);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi xử lý thời gian điểm danh: ' . $e->getMessage());
                    $item->thoi_gian_diem_danh = null;
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

            // Thêm thông tin sự kiện
            if (!empty($item->su_kien_id) && isset($suKienMap[$item->su_kien_id])) {
                $item->su_kien = $suKienMap[$item->su_kien_id];
            } else {
                $item->su_kien = null;
            }

            // Thêm thông tin người dùng
            if (!empty($item->nguoi_dung_id) && isset($nguoiDungMap[$item->nguoi_dung_id])) {
                $item->nguoi_dung = $nguoiDungMap[$item->nguoi_dung_id];
            } else {
                $item->nguoi_dung = null;
            }

            // Xử lý phương thức điểm danh
            $item->phuong_thuc_diem_danh_text = $this->getPhuongThucDiemDanhTextRelation($item->phuong_thuc_diem_danh);

            // Xử lý trạng thái
            $item->status_text = $item->status == 1 ? 'Hoạt động' : 'Không hoạt động';
            $item->status_class = $item->status == 1 ? 'status-active' : 'status-inactive';
        }

        return $data;
    }

    /**
     * Lấy text cho phương thức điểm danh (phiên bản cho RelationTrait)
     */
    protected function getPhuongThucDiemDanhTextRelation($phuongThuc)
    {
        switch ($phuongThuc) {
            case 'qr_code':
                return 'QR Code';
            case 'face_id':
                return 'Face ID';
            default:
                return 'Thủ công';
        }
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
            'su_kien_id' => $request->getGet('su_kien_id'),
            'phuong_thuc_diem_danh' => $request->getGet('phuong_thuc_diem_danh')
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
        
        if (!empty($params['su_kien_id'])) {
            $criteria['su_kien_id'] = $params['su_kien_id'];
        }
        
        if (!empty($params['phuong_thuc_diem_danh'])) {
            $criteria['phuong_thuc_diem_danh'] = $params['phuong_thuc_diem_danh'];
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