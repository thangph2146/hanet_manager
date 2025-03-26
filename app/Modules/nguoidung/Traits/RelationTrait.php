<?php

namespace App\Modules\nguoidung\Traits;

use CodeIgniter\I18n\Time;
use App\Modules\phongkhoa\Models\PhongKhoaModel;
use App\Modules\loainguoidung\Models\LoaiNguoiDungModel;
use App\Modules\namhoc\Models\NamHocModel;
use App\Modules\bachoc\Models\BacHocModel;
use App\Modules\hedaotao\Models\HeDaoTaoModel;
use App\Modules\nganh\Models\NganhModel;
use App\Modules\khoahoc\Models\KhoaHocModel;
use App\Modules\loaisukien\Models\LoaiSuKienModel;

trait RelationTrait
{
    protected $phongKhoaModel;
    protected $loaiNguoiDungModel;
    protected $namHocModel;
    protected $bacHocModel;
    protected $heDaoTaoModel;
    protected $nganhModel;
    protected $khoaHocModel;
    protected $loaiSuKienModel;

    /**
     * Khởi tạo các model cần thiết
     */
    protected function initializeRelationTrait()
    {
        $this->phongKhoaModel = new PhongKhoaModel();
        $this->loaiNguoiDungModel = new LoaiNguoiDungModel();
        $this->namHocModel = new NamHocModel();
        $this->bacHocModel = new BacHocModel();
        $this->heDaoTaoModel = new HeDaoTaoModel();
        $this->nganhModel = new NganhModel();
        $this->khoaHocModel = new KhoaHocModel();
        $this->loaiSuKienModel = new LoaiSuKienModel();
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
        
        // Lấy danh sách cho các select box
        $phongKhoaList = $this->phongKhoaModel->getAllActive(100, 0, 'ten_phong_khoa', 'ASC');
        $loaiNguoiDungList = $this->loaiNguoiDungModel->getAllActive(100, 0, 'ten_loai', 'ASC');
        $namHocList = $this->namHocModel->getAllActive(100, 0, 'ten_nam_hoc', 'ASC');
        $bacHocList = $this->bacHocModel->getAllActive(100, 0, 'ten_bac_hoc', 'ASC');
        $heDaoTaoList = $this->heDaoTaoModel->getAllActive(100, 0, 'ten_he_dao_tao', 'ASC');
        $nganhList = $this->nganhModel->getAllActive(100, 0, 'ten_nganh', 'ASC');
        $khoaHocList = $this->khoaHocModel->getAllActive(100, 0, 'ten_khoa_hoc', 'ASC');
        $loaiSuKienList = $this->loaiSuKienModel->getAllActive(100, 0, 'ten_loai_su_kien', 'ASC');
        
        // Lấy thông tin chi tiết cho các tham số
        $phongKhoaInfo = null;
        $loaiNguoiDungInfo = null;
        $namHocInfo = null;
        $bacHocInfo = null;
        $heDaoTaoInfo = null;
        $nganhInfo = null;
        $khoaHocInfo = null;
        $loaiSuKienInfo = null;
        
        if (!empty($params['phong_khoa_id'])) {
            $phongKhoaInfo = $this->phongKhoaModel->find($params['phong_khoa_id']);
        }
        if (!empty($params['loai_nguoi_dung_id'])) {
            $loaiNguoiDungInfo = $this->loaiNguoiDungModel->find($params['loai_nguoi_dung_id']);
        }
        if (!empty($params['nam_hoc_id'])) {
            $namHocInfo = $this->namHocModel->find($params['nam_hoc_id']);
        }
        if (!empty($params['bac_hoc_id'])) {
            $bacHocInfo = $this->bacHocModel->find($params['bac_hoc_id']);
        }
        if (!empty($params['he_dao_tao_id'])) {
            $heDaoTaoInfo = $this->heDaoTaoModel->find($params['he_dao_tao_id']);
        }
        if (!empty($params['nganh_id'])) {
            $nganhInfo = $this->nganhModel->find($params['nganh_id']);
        }
        if (!empty($params['khoa_hoc_id'])) {
            $khoaHocInfo = $this->khoaHocModel->find($params['khoa_hoc_id']);
        }
        if (!empty($params['loai_su_kien_id'])) {
            $loaiSuKienInfo = $this->loaiSuKienModel->find($params['loai_su_kien_id']);
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
            'loai_nguoi_dung_id' => $params['loai_nguoi_dung_id'] ?? null,
            'nam_hoc_id' => $params['nam_hoc_id'] ?? null,
            'bac_hoc_id' => $params['bac_hoc_id'] ?? null,
            'he_dao_tao_id' => $params['he_dao_tao_id'] ?? null,
            'nganh_id' => $params['nganh_id'] ?? null,
            'khoa_hoc_id' => $params['khoa_hoc_id'] ?? null,
            'loai_su_kien_id' => $params['loai_su_kien_id'] ?? null,
            'phong_khoa_info' => $phongKhoaInfo,
            'loai_nguoi_dung_info' => $loaiNguoiDungInfo,
            'nam_hoc_info' => $namHocInfo,
            'bac_hoc_info' => $bacHocInfo,
            'he_dao_tao_info' => $heDaoTaoInfo,
            'nganh_info' => $nganhInfo,
            'khoa_hoc_info' => $khoaHocInfo,
            'loai_su_kien_info' => $loaiSuKienInfo,
            'phongKhoaList' => $phongKhoaList,
            'loaiNguoiDungList' => $loaiNguoiDungList,
            'namHocList' => $namHocList,
            'bacHocList' => $bacHocList,
            'heDaoTaoList' => $heDaoTaoList,
            'nganhList' => $nganhList,
            'khoaHocList' => $khoaHocList,
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
        $loaiNguoiDungIds = [];
        $namHocIds = [];
        $bacHocIds = [];
        $heDaoTaoIds = [];
        $nganhIds = [];
        $khoaHocIds = [];
        $loaiSuKienIds = [];
        
        foreach ($data as $item) {
            if (!empty($item->phong_khoa_id)) {
                $phongKhoaIds[] = $item->phong_khoa_id;
            }
            if (!empty($item->loai_nguoi_dung_id)) {
                $loaiNguoiDungIds[] = $item->loai_nguoi_dung_id;
            }
            if (!empty($item->nam_hoc_id)) {
                $namHocIds[] = $item->nam_hoc_id;
            }
            if (!empty($item->bac_hoc_id)) {
                $bacHocIds[] = $item->bac_hoc_id;
            }
            if (!empty($item->he_dao_tao_id)) {
                $heDaoTaoIds[] = $item->he_dao_tao_id;
            }
            if (!empty($item->nganh_id)) {
                $nganhIds[] = $item->nganh_id;
            }
            if (!empty($item->khoa_hoc_id)) {
                $khoaHocIds[] = $item->khoa_hoc_id;
            }
            if (!empty($item->loai_su_kien_id)) {
                $loaiSuKienIds[] = $item->loai_su_kien_id;
            }
        }
      
        // Lấy dữ liệu relation một lần duy nhất
        $phongKhoas = [];
        $loaiNguoiDungs = [];
        $namHocs = [];
        $bacHocs = [];
        $heDaoTaos = [];
        $nganhs = [];
        $khoaHocs = [];
        $loaiSuKiens = [];
        
        if (!empty($phongKhoaIds)) {
            $phongKhoaIds = array_unique($phongKhoaIds);
            $phongKhoas = $this->phongKhoaModel->find($phongKhoaIds);
        }
        if (!empty($loaiNguoiDungIds)) {
            $loaiNguoiDungIds = array_unique($loaiNguoiDungIds);
            $loaiNguoiDungs = $this->loaiNguoiDungModel->find($loaiNguoiDungIds);
        }
        if (!empty($namHocIds)) {
            $namHocIds = array_unique($namHocIds);
            $namHocs = $this->namHocModel->find($namHocIds);
        }
        if (!empty($bacHocIds)) {
            $bacHocIds = array_unique($bacHocIds);
            $bacHocs = $this->bacHocModel->find($bacHocIds);
        }
        if (!empty($heDaoTaoIds)) {
            $heDaoTaoIds = array_unique($heDaoTaoIds);
            $heDaoTaos = $this->heDaoTaoModel->find($heDaoTaoIds);
        }
        if (!empty($nganhIds)) {
            $nganhIds = array_unique($nganhIds);
            $nganhs = $this->nganhModel->find($nganhIds);
        }
        if (!empty($khoaHocIds)) {
            $khoaHocIds = array_unique($khoaHocIds);
            $khoaHocs = $this->khoaHocModel->find($khoaHocIds);
        }
        if (!empty($loaiSuKienIds)) {
            $loaiSuKienIds = array_unique($loaiSuKienIds);
            $loaiSuKiens = $this->loaiSuKienModel->find($loaiSuKienIds);
        }

        // Chuyển đổi thành mảng key-value để dễ truy cập
        $phongKhoaMap = !empty($phongKhoas) ? array_column($phongKhoas, null, 'phong_khoa_id') : [];
        $loaiNguoiDungMap = !empty($loaiNguoiDungs) ? array_column($loaiNguoiDungs, null, 'loai_nguoi_dung_id') : [];
        $namHocMap = !empty($namHocs) ? array_column($namHocs, null, 'nam_hoc_id') : [];
        $bacHocMap = !empty($bacHocs) ? array_column($bacHocs, null, 'bac_hoc_id') : [];
        $heDaoTaoMap = !empty($heDaoTaos) ? array_column($heDaoTaos, null, 'he_dao_tao_id') : [];
        $nganhMap = !empty($nganhs) ? array_column($nganhs, null, 'nganh_id') : [];
        $khoaHocMap = !empty($khoaHocs) ? array_column($khoaHocs, null, 'khoa_hoc_id') : [];
        $loaiSuKienMap = !empty($loaiSuKiens) ? array_column($loaiSuKiens, null, 'loai_su_kien_id') : [];

        foreach ($data as &$item) {
            // Xử lý thời gian
            $this->processTimestamps($item);
            
            // Thêm thông tin phòng khoa
            if (!empty($item->phong_khoa_id) && isset($phongKhoaMap[$item->phong_khoa_id])) {
                $item->phong_khoa = $phongKhoaMap[$item->phong_khoa_id];
                $item->ten_phong_khoa = $phongKhoaMap[$item->phong_khoa_id]->getTenPhongKhoa();
                $item->ma_phong_khoa = $phongKhoaMap[$item->phong_khoa_id]->getMaPhongKhoa();
                $item->ghi_chu = $phongKhoaMap[$item->phong_khoa_id]->getGhiChu();
            } else {
                $item->phong_khoa = null;
                $item->ten_phong_khoa = 'Chưa xác định';
                $item->ma_phong_khoa = '';
                $item->ghi_chu = '';
            }

            // Thêm thông tin loại người dùng
            if (!empty($item->loai_nguoi_dung_id) && isset($loaiNguoiDungMap[$item->loai_nguoi_dung_id])) {
                $item->loai_nguoi_dung = $loaiNguoiDungMap[$item->loai_nguoi_dung_id];
                $item->ten_loai = $loaiNguoiDungMap[$item->loai_nguoi_dung_id]->getTenLoai();
                $item->mo_ta = $loaiNguoiDungMap[$item->loai_nguoi_dung_id]->getMoTa();
            } else {
                $item->loai_nguoi_dung = null;
                $item->ten_loai = 'Chưa xác định';
                $item->mo_ta = '';
            }

            // Thêm thông tin năm học
            if (!empty($item->nam_hoc_id) && isset($namHocMap[$item->nam_hoc_id])) {
                $item->nam_hoc = $namHocMap[$item->nam_hoc_id];
                $item->ten_nam_hoc = $namHocMap[$item->nam_hoc_id]->getTenNamHoc();
                $item->ngay_bat_dau = $namHocMap[$item->nam_hoc_id]->getNgayBatDau();
                $item->ngay_ket_thuc = $namHocMap[$item->nam_hoc_id]->getNgayKetThuc();
            } else {
                $item->nam_hoc = null;
                $item->ten_nam_hoc = 'Chưa xác định';
                $item->ngay_bat_dau = '';
                $item->ngay_ket_thuc = '';
            }

            // Thêm thông tin bậc học
            if (!empty($item->bac_hoc_id) && isset($bacHocMap[$item->bac_hoc_id])) {
                $item->bac_hoc = $bacHocMap[$item->bac_hoc_id];
                $item->ten_bac_hoc = $bacHocMap[$item->bac_hoc_id]->getTenBacHoc();
                $item->ma_bac_hoc = $bacHocMap[$item->bac_hoc_id]->getMaBacHoc();
            } else {
                $item->bac_hoc = null;
                $item->ten_bac_hoc = 'Chưa xác định';
                $item->ma_bac_hoc = '';
            }

            // Thêm thông tin hệ đào tạo
            if (!empty($item->he_dao_tao_id) && isset($heDaoTaoMap[$item->he_dao_tao_id])) {
                $item->he_dao_tao = $heDaoTaoMap[$item->he_dao_tao_id];
                $item->ten_he_dao_tao = $heDaoTaoMap[$item->he_dao_tao_id]->getTenHeDaoTao();
                $item->ma_he_dao_tao = $heDaoTaoMap[$item->he_dao_tao_id]->getMaHeDaoTao();
            } else {
                $item->he_dao_tao = null;
                $item->ten_he_dao_tao = 'Chưa xác định';
                $item->ma_he_dao_tao = '';
            }

            // Thêm thông tin ngành
            if (!empty($item->nganh_id) && isset($nganhMap[$item->nganh_id])) {
                $item->nganh = $nganhMap[$item->nganh_id];
                $item->ten_nganh = $nganhMap[$item->nganh_id]->getTenNganh();
                $item->ma_nganh = $nganhMap[$item->nganh_id]->getMaNganh();
            } else {
                $item->nganh = null;
                $item->ten_nganh = 'Chưa xác định';
                $item->ma_nganh = '';
            }

            // Thêm thông tin khóa học
            if (!empty($item->khoa_hoc_id) && isset($khoaHocMap[$item->khoa_hoc_id])) {
                $item->khoa_hoc = $khoaHocMap[$item->khoa_hoc_id];
                $item->ten_khoa_hoc = $khoaHocMap[$item->khoa_hoc_id]->getTenKhoaHoc();
                $item->nam_bat_dau = $khoaHocMap[$item->khoa_hoc_id]->getNamBatDau();
                $item->nam_ket_thuc = $khoaHocMap[$item->khoa_hoc_id]->getNamKetThuc();
            } else {
                $item->khoa_hoc = null;
                $item->ten_khoa_hoc = 'Chưa xác định';
                $item->nam_bat_dau = '';
                $item->nam_ket_thuc = '';
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

            // Xử lý trạng thái
            $item->status_text = $item->status == 1 ? 'Hoạt động' : 'Không hoạt động';
            $item->status_class = $item->status == 1 ? 'status-active' : 'status-inactive';
        }

        return $data;
    }

    /**
     * Xử lý các trường timestamp
     */
    protected function processTimestamps(&$item)
    {
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

        // Xử lý thời gian đăng nhập cuối
        if (!empty($item->last_login)) {
            try {
                if ($item->last_login instanceof Time) {
                    $item->last_login = $item->last_login;
                } else {
                    // Thử parse với các định dạng phổ biến
                    $formats = ['Y-m-d', 'Y-m-d H:i:s', 'd-m-Y', 'd/m/Y'];
                    $parsed = false;
                    
                    foreach ($formats as $format) {
                        try {
                            $item->last_login = Time::createFromFormat($format, $item->last_login);
                            $parsed = true;
                            break;
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                    
                    if (!$parsed) {
                        // Nếu không parse được, giữ nguyên giá trị
                        $item->last_login = $item->last_login;
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Lỗi xử lý thời gian đăng nhập cuối: ' . $e->getMessage());
                // Giữ nguyên giá trị nếu có lỗi
                $item->last_login = $item->last_login;
            }
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
            'phong_khoa_id' => $request->getGet('phong_khoa_id'),
            'loai_nguoi_dung_id' => $request->getGet('loai_nguoi_dung_id'),
            'nam_hoc_id' => $request->getGet('nam_hoc_id'),
            'bac_hoc_id' => $request->getGet('bac_hoc_id'),
            'he_dao_tao_id' => $request->getGet('he_dao_tao_id'),
            'nganh_id' => $request->getGet('nganh_id'),
            'khoa_hoc_id' => $request->getGet('khoa_hoc_id'),
            'loai_su_kien_id' => $request->getGet('loai_su_kien_id'),
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

        if (!empty($params['loai_nguoi_dung_id'])) {
            $criteria['loai_nguoi_dung_id'] = $params['loai_nguoi_dung_id'];
        }

        if (!empty($params['nam_hoc_id'])) {
            $criteria['nam_hoc_id'] = $params['nam_hoc_id'];
        }

        if (!empty($params['bac_hoc_id'])) {
            $criteria['bac_hoc_id'] = $params['bac_hoc_id'];
        }

        if (!empty($params['he_dao_tao_id'])) {
            $criteria['he_dao_tao_id'] = $params['he_dao_tao_id'];
        }

        if (!empty($params['nganh_id'])) {
            $criteria['nganh_id'] = $params['nganh_id'];
        }

        if (!empty($params['khoa_hoc_id'])) {
            $criteria['khoa_hoc_id'] = $params['khoa_hoc_id'];
        }

        if (!empty($params['loai_su_kien_id'])) {
            $criteria['loai_su_kien_id'] = $params['loai_su_kien_id'];
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
     * @param object $data Dữ liệu người dùng (nếu là cập nhật)
     * @return array Dữ liệu để truyền vào view
     */
    public function prepareFormData($module_name, $data = null)
    {
        // Khởi tạo các model nếu chưa được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy danh sách cho các select box
        $phongKhoaList = $this->phongKhoaModel->getAllActive(100, 0, 'ten_phong_khoa', 'ASC');
        $loaiNguoiDungList = $this->loaiNguoiDungModel->getAllActive(100, 0, 'ten_loai', 'ASC');
        $namHocList = $this->namHocModel->getAllActive(100, 0, 'ten_nam_hoc', 'ASC');
        $bacHocList = $this->bacHocModel->getAllActive(100, 0, 'ten_bac_hoc', 'ASC');
        $heDaoTaoList = $this->heDaoTaoModel->getAllActive(100, 0, 'ten_he_dao_tao', 'ASC');
        $nganhList = $this->nganhModel->getAllActive(100, 0, 'ten_nganh', 'ASC');
        $khoaHocList = $this->khoaHocModel->getAllActive(100, 0, 'ten_khoa_hoc', 'ASC');
        $loaiSuKienList = $this->loaiSuKienModel->getAllActive(100, 0, 'ten_loai_su_kien', 'ASC');
        
        return [
            'data' => $data,
            'phongKhoaList' => $phongKhoaList,
            'loaiNguoiDungList' => $loaiNguoiDungList,
            'namHocList' => $namHocList,
            'bacHocList' => $bacHocList,
            'heDaoTaoList' => $heDaoTaoList,
            'nganhList' => $nganhList,
            'khoaHocList' => $khoaHocList,
            'loaiSuKienList' => $loaiSuKienList,
            'module_name' => $module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
        ];
    }
} 