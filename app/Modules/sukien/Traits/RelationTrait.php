<?php

namespace App\Modules\sukien\Traits;

use CodeIgniter\I18n\Time;

trait RelationTrait
{
    protected $fields = [
        'ten_su_kien' => 'getTenSuKien',
        'mo_ta' => 'getMoTa',
        'mo_ta_su_kien' => 'getMoTaSuKien',
        'chi_tiet_su_kien' => 'getChiTietSuKien',
        'thoi_gian_bat_dau_formatted' => 'getThoiGianBatDauFormatted',
        'thoi_gian_ket_thuc_formatted' => 'getThoiGianKetThucFormatted',
        'dia_diem' => 'getDiaDiem',
        'dia_chi_cu_the' => 'getDiaChiCuThe',
        'toa_do_gps' => 'getToaDoGps',
        'loai_su_kien_id' => 'getLoaiSuKienId',
        'ma_qr_code' => 'getMaQrCode',
        'tong_dang_ky' => 'getTongDangKy',
        'tong_check_in' => 'getTongCheckIn',
        'tong_check_out' => 'getTongCheckOut',
        'hinh_thuc_text' => 'getHinhThucText',
        'status_text' => 'getStatusText',
        'status' => 'getStatus',
        'so_luot_xem' => 'getSoLuotXem',
        'created_at_formatted' => 'getCreatedAtFormatted',
        'updated_at_formatted' => 'getUpdatedAtFormatted',
        'deleted_at_formatted' => 'getDeletedAtFormatted',
        'is_deleted' => 'isDeleted',
    ];

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        // Khởi tạo model LoaiSuKien nếu cần
        if (!isset($this->loaiSuKienModel) && class_exists('App\Modules\loaisukien\Models\LoaiSuKienModel')) {
            $this->loaiSuKienModel = new \App\Modules\loaisukien\Models\LoaiSuKienModel();
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
        
        return [
            'processedData' => $processedData,
            'pager' => $pager,
            'currentPage' => $params['page'] ?? 1,
            'perPage' => $params['perPage'] ?? 10,
            'total' => $params['total'] ?? 0,
            'sort' => $params['sort'] ?? $this->field_sort,
            'order' => $params['order'] ?? $this->field_order,
            'keyword' => $params['keyword'] ?? '',
            'loai_su_kien_id' => $params['loai_su_kien_id'] ?? '',
            'hinh_thuc' => $params['hinh_thuc'] ?? '',
            'status' => isset($params['status']) && $params['status'] !== '' ? $params['status'] : null,
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
            // Xử lý thời gian
            $dateTimeFields = ['created_at', 'updated_at', 'deleted_at', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 
                'bat_dau_dang_ky', 'ket_thuc_dang_ky', 'han_huy_dang_ky', 'gio_bat_dau', 'gio_ket_thuc'];
            
            foreach ($dateTimeFields as $field) {
                if (!empty($item->$field)) {
                    try {
                        $item->$field = $item->$field instanceof Time ? 
                            $item->$field : 
                            Time::parse($item->$field);
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi xử lý thời gian ' . $field . ': ' . $e->getMessage());
                        $item->$field = null;
                    }
                }
            }

            // Xử lý các trường JSON
            $jsonFields = ['su_kien_poster', 'lich_trinh'];
            foreach ($jsonFields as $field) {
                if (!empty($item->$field) && is_string($item->$field)) {
                    try {
                        $item->$field = json_decode($item->$field);
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi xử lý trường JSON ' . $field . ': ' . $e->getMessage());
                    }
                }
            }

            // Thêm các thuộc tính đã định dạng vào các thuộc tính mới
            foreach ($this->fields as $key => $method) {
                // Kiểm tra phương thức tồn tại trước khi gọi
                if (method_exists($item, $method)) {
                    // Thêm trực tiếp như một thuộc tính của đối tượng
                    $item->$key = $item->$method();
                }
            }
            
            // Thêm thông tin loại sự kiện nếu model loại sự kiện tồn tại
            if (isset($this->loaiSuKienModel) && !empty($item->loai_su_kien_id)) {
                $item->loaiSuKien = $this->loaiSuKienModel->find($item->loai_su_kien_id);
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
            'sort' => $request->getGet('sort') ?? $this->field_sort,
            'order' => $request->getGet('order') ?? $this->field_order,
            'keyword' => $request->getGet('keyword') ?? '',
            'loai_su_kien_id' => $request->getGet('loai_su_kien_id'),
            'status' => $request->getGet('status'),
            'hinh_thuc' => $request->getGet('hinh_thuc'),
            'thoi_gian_bat_dau_from' => $request->getGet('thoi_gian_bat_dau_from'),
            'thoi_gian_bat_dau_to' => $request->getGet('thoi_gian_bat_dau_to'),
            'thoi_gian_ket_thuc_from' => $request->getGet('thoi_gian_ket_thuc_from'),
            'thoi_gian_ket_thuc_to' => $request->getGet('thoi_gian_ket_thuc_to')
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

        // Giới hạn perPage để tránh quá tải
        $params['perPage'] = min($params['perPage'], 100);

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

        if (!empty($params['loai_su_kien_id'])) {
            $criteria['loai_su_kien_id'] = (int)$params['loai_su_kien_id'];
        }

        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = (int)$params['status'];
        }

        if (!empty($params['hinh_thuc'])) {
            $criteria['hinh_thuc'] = $params['hinh_thuc'];
        }

        // Xử lý lọc theo thời gian
        $timeFields = [
            'thoi_gian_bat_dau_from' => 'thoi_gian_bat_dau_from',
            'thoi_gian_bat_dau_to' => 'thoi_gian_bat_dau_to',
            'thoi_gian_ket_thuc_from' => 'thoi_gian_ket_thuc_from',
            'thoi_gian_ket_thuc_to' => 'thoi_gian_ket_thuc_to'
        ];

        foreach ($timeFields as $paramKey => $criteriaKey) {
            if (!empty($params[$paramKey])) {
                $criteria[$criteriaKey] = $params[$paramKey];
            }
        }

        return $criteria;
    }

    /**
     * Xây dựng tùy chọn tìm kiếm cho model
     */
    protected function buildSearchOptions($params)
    {
        // Tính toán offset cho phân trang
        $offset = ($params['page'] - 1) * $params['perPage'];
        
        return [
            'offset' => $offset,
            'limit' => $params['perPage'],
            'sort' => $params['sort'],
            'order' => $params['order']
        ];
    }

    /**
     * Chuẩn bị dữ liệu cho form
     */
    public function prepareFormData($module_name, $data = null)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        $viewData = [
            'title' => 'Form ' . $this->title,
            'data' => $data,
            'moduleUrl' => $this->moduleUrl,
            'module_name' => $module_name
        ];
        
        // Thêm danh sách loại sự kiện nếu model tồn tại
        if (isset($this->loaiSuKienModel)) {
            $viewData['loaiSuKienList'] = $this->loaiSuKienModel->findAll();
        }
        
        return $viewData;
    }
    
    /**
     * Định dạng các giá trị của trường cho hiển thị
     */
    protected function formatFieldValue($field, $value)
    {
        if (empty($value)) {
            return '';
        }
        
        // Xử lý các loại trường khác nhau
        switch ($field) {
            case 'status':
                return $value ? 'Hoạt động' : 'Không hoạt động';
                
            case 'hinh_thuc':
                switch ($value) {
                    case 'offline':
                        return 'Trực tiếp';
                    case 'online':
                        return 'Trực tuyến';
                    case 'hybrid':
                        return 'Kết hợp';
                    default:
                        return $value;
                }
                
            case 'cho_phep_check_in':
            case 'cho_phep_check_out':
            case 'yeu_cau_face_id':
            case 'cho_phep_checkin_thu_cong':
                return $value ? 'Có' : 'Không';
                
            default:
                // Nếu là đối tượng Time, định dạng thành chuỗi
                if ($value instanceof Time) {
                    return $value->format('d/m/Y H:i:s');
                }
                return $value;
        }
    }
} 