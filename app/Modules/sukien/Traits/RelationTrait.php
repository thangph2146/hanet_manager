<?php

namespace App\Modules\sukien\Traits;

use CodeIgniter\I18n\Time;
use App\Modules\phongkhoa\Models\PhongKhoaModel;
use App\Modules\loaisukien\Models\LoaiSuKienModel;
use App\Modules\nguoidung\Models\NguoiDungModel;
use App\Modules\diengia\Models\DienGiaModel;
use App\Modules\dangky\Models\DangKyModel;
use App\Modules\checkin\Models\CheckInModel;
use App\Modules\checkout\Models\CheckOutModel;

trait RelationTrait
{
    protected $phongKhoaModel;
    protected $loaiSuKienModel;
    protected $nguoiDungModel;
    protected $dienGiaModel;
    protected $dangKyModel;
    protected $checkInModel;
    protected $checkOutModel;

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        $this->phongKhoaModel = new PhongKhoaModel();
        $this->loaiSuKienModel = new LoaiSuKienModel();
        $this->nguoiDungModel = new NguoiDungModel();
        $this->dienGiaModel = new DienGiaModel();
        $this->dangKyModel = new DangKyModel();
        $this->checkInModel = new CheckInModel();
        $this->checkOutModel = new CheckOutModel();
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
        
        // Lấy danh sách loại sự kiện cho form select box
        $loaiSuKienList = $this->loaiSuKienModel->getAllActive(100, 0, 'ten_loai_su_kien', 'ASC');
        
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
            'loaiSuKienList' => $loaiSuKienList,
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
        $loaiSuKienIds = [];
        $nguoiTaoIds = [];
        $dienGiaIds = [];
        
        foreach ($data as $item) {
            if (!empty($item->phong_khoa_id)) {
                $phongKhoaIds[] = $item->phong_khoa_id;
            }
            if (!empty($item->loai_su_kien_id)) {
                $loaiSuKienIds[] = $item->loai_su_kien_id;
            }
            if (!empty($item->nguoi_tao_id)) {
                $nguoiTaoIds[] = $item->nguoi_tao_id;
            }
            if (!empty($item->dien_gia_id)) {
                $dienGiaIds[] = $item->dien_gia_id;
            }
        }
      
        // Lấy dữ liệu relation một lần duy nhất
        $phongKhoas = [];
        $loaiSuKiens = [];
        $nguoiTao = [];
        $dienGia = [];
        
        if (!empty($phongKhoaIds)) {
            $phongKhoaIds = array_unique($phongKhoaIds);
            $phongKhoas = $this->phongKhoaModel->find($phongKhoaIds);
        }
        
        if (!empty($loaiSuKienIds)) {
            $loaiSuKienIds = array_unique($loaiSuKienIds);
            $loaiSuKiens = $this->loaiSuKienModel->find($loaiSuKienIds);
        }
        
        if (!empty($nguoiTaoIds)) {
            $nguoiTaoIds = array_unique($nguoiTaoIds);
            $nguoiTao = $this->nguoiDungModel->find($nguoiTaoIds);
        }
        
        if (!empty($dienGiaIds)) {
            $dienGiaIds = array_unique($dienGiaIds);
            $dienGia = $this->dienGiaModel->find($dienGiaIds);
        }

        // Chuyển đổi thành mảng key-value để dễ truy cập
        $phongKhoaMap = !empty($phongKhoas) ? array_column($phongKhoas, null, 'phong_khoa_id') : [];
        $loaiSuKienMap = !empty($loaiSuKiens) ? array_column($loaiSuKiens, null, 'loai_su_kien_id') : [];
        $nguoiTaoMap = !empty($nguoiTao) ? array_column($nguoiTao, null, 'nguoi_dung_id') : [];
        $dienGiaMap = !empty($dienGia) ? array_column($dienGia, null, 'dien_gia_id') : [];

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

            // Thêm thông tin loại sự kiện
            if (!empty($item->loai_su_kien_id) && isset($loaiSuKienMap[$item->loai_su_kien_id])) {
                $item->loai_su_kien = $loaiSuKienMap[$item->loai_su_kien_id];
                $item->ten_loai_su_kien = $loaiSuKienMap[$item->loai_su_kien_id]->getTenLoaiSuKien();
                $item->ma_loai_su_kien = $loaiSuKienMap[$item->loai_su_kien_id]->getMaLoaiSuKien();
            } else {
                $item->loai_su_kien = null;
                $item->ten_loai_su_kien = 'Chưa xác định';
                $item->ma_loai_su_kien = '';
            }

            // Thêm thông tin người tạo
            if (!empty($item->nguoi_tao_id) && isset($nguoiTaoMap[$item->nguoi_tao_id])) {
                $item->nguoi_tao = $nguoiTaoMap[$item->nguoi_tao_id];
                $item->ten_nguoi_tao = $nguoiTaoMap[$item->nguoi_tao_id]->getTenNguoiDung();
                $item->ma_nguoi_tao = $nguoiTaoMap[$item->nguoi_tao_id]->getMaNguoiDung();
            } else {
                $item->nguoi_tao = null;
                $item->ten_nguoi_tao = 'Chưa xác định';
                $item->ma_nguoi_tao = '';
            }

            // Thêm thông tin diễn giả
            if (!empty($item->dien_gia_id) && isset($dienGiaMap[$item->dien_gia_id])) {
                $item->dien_gia = $dienGiaMap[$item->dien_gia_id];
                $item->ten_dien_gia = $dienGiaMap[$item->dien_gia_id]->getTenDienGia();
                $item->ma_dien_gia = $dienGiaMap[$item->dien_gia_id]->getMaDienGia();
            } else {
                $item->dien_gia = null;
                $item->ten_dien_gia = 'Chưa xác định';
                $item->ma_dien_gia = '';
            }

            // Xử lý trạng thái
            $item->status_text = $item->status == 1 ? 'Hoạt động' : 'Không hoạt động';
            $item->status_class = $item->status == 1 ? 'status-active' : 'status-inactive';

            // Xử lý JSON fields
            if (!empty($item->su_kien_poster)) {
                $item->su_kien_poster = json_decode($item->su_kien_poster, true);
            }
            if (!empty($item->lich_trinh)) {
                $item->lich_trinh = json_decode($item->lich_trinh, true);
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
            'status' => $request->getGet('status'),
            'phong_khoa_id' => $request->getGet('phong_khoa_id'),
            'loai_su_kien_id' => $request->getGet('loai_su_kien_id'),
            'nguoi_tao_id' => $request->getGet('nguoi_tao_id'),
            'dien_gia_id' => $request->getGet('dien_gia_id'),
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

        // Xử lý status
        if ($params['status'] !== null && $params['status'] !== '') {
            $params['status'] = (int)$params['status'];
        }

        // Xử lý các ID
        if (!empty($params['phong_khoa_id'])) {
            $params['phong_khoa_id'] = (int)$params['phong_khoa_id'];
        }
        if (!empty($params['loai_su_kien_id'])) {
            $params['loai_su_kien_id'] = (int)$params['loai_su_kien_id'];
        }
        if (!empty($params['nguoi_tao_id'])) {
            $params['nguoi_tao_id'] = (int)$params['nguoi_tao_id'];
        }
        if (!empty($params['dien_gia_id'])) {
            $params['dien_gia_id'] = (int)$params['dien_gia_id'];
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

        if (!empty($params['loai_su_kien_id'])) {
            $criteria['loai_su_kien_id'] = $params['loai_su_kien_id'];
        }

        if (!empty($params['nguoi_tao_id'])) {
            $criteria['nguoi_tao_id'] = $params['nguoi_tao_id'];
        }

        if (!empty($params['dien_gia_id'])) {
            $criteria['dien_gia_id'] = $params['dien_gia_id'];
        }

        if (!empty($params['thoi_gian_bat_dau_from'])) {
            $criteria['thoi_gian_bat_dau_from'] = $params['thoi_gian_bat_dau_from'];
        }

        if (!empty($params['thoi_gian_bat_dau_to'])) {
            $criteria['thoi_gian_bat_dau_to'] = $params['thoi_gian_bat_dau_to'];
        }

        if (!empty($params['thoi_gian_ket_thuc_from'])) {
            $criteria['thoi_gian_ket_thuc_from'] = $params['thoi_gian_ket_thuc_from'];
        }

        if (!empty($params['thoi_gian_ket_thuc_to'])) {
            $criteria['thoi_gian_ket_thuc_to'] = $params['thoi_gian_ket_thuc_to'];
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
     * @param object $data Dữ liệu sự kiện (nếu là cập nhật)
     * @return array Dữ liệu để truyền vào view
     */
    public function prepareFormData($module_name, $data = null)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy danh sách phòng khoa cho form select box
        $phongKhoaList = $this->phongKhoaModel->getAllActive(100, 0, 'ten_phong_khoa', 'ASC');
        
        // Lấy danh sách loại sự kiện cho form select box
        $loaiSuKienList = $this->loaiSuKienModel->getAllActive(100, 0, 'ten_loai_su_kien', 'ASC');
        
        // Lấy danh sách diễn giả cho form select box
        $dienGiaList = $this->dienGiaModel->getAllActive(100, 0, 'ten_dien_gia', 'ASC');
        
        return [
            'data' => $data,
            'phongKhoaList' => $phongKhoaList,
            'loaiSuKienList' => $loaiSuKienList,
            'dienGiaList' => $dienGiaList,
            'module_name' => $module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
        ];
    }
} 