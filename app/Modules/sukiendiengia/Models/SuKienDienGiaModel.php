<?php

namespace App\Modules\sukiendiengia\Models;

use App\Models\BaseModel;
use App\Modules\sukiendiengia\Entities\SuKienDienGia;
use App\Modules\sukiendiengia\Libraries\Pager;
use CodeIgniter\I18n\Time;

class SuKienDienGiaModel extends BaseModel
{
    protected $table = 'su_kien_dien_gia';
    protected $primaryKey = 'su_kien_dien_gia_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'su_kien_id',
        'dien_gia_id',
        'thu_tu',
        'vai_tro',
        'mo_ta',
        'thoi_gian_trinh_bay',
        'thoi_gian_ket_thuc',
        'thoi_luong_phut',
        'tieu_de_trinh_bay',
        'tai_lieu_dinh_kem',
        'trang_thai_tham_gia',
        'hien_thi_cong_khai',
        'ghi_chu',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = SuKienDienGia::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'vai_tro',
        'mo_ta',
        'tieu_de_trinh_bay',
        'ghi_chu'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'su_kien_id',
        'dien_gia_id',
        'trang_thai_tham_gia',
        'hien_thi_cong_khai'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // SuKienDienGia pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi mối quan hệ giữa sự kiện và diễn giả
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'created_at', $order = 'DESC')
    {
        $builder = $this->builder();
        
        // Chỉ lấy bản ghi chưa xóa
        $builder->where($this->table . '.deleted_at IS NULL');
        
        if ($sort && $order) {
            $builder->orderBy($this->table . '.' . $sort, $order);
        }
        
        $total = $this->countAll();
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        if ($this->pager === null) {
            $this->pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->pager->setTotal($total)
                        ->setPerPage($limit)
                        ->setCurrentPage($currentPage);
        }
        
        $result = $builder->limit($limit, $offset)->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số bản ghi mối quan hệ
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm bản ghi chưa xóa
        $builder->where($this->table . '.deleted_at IS NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm mối quan hệ dựa vào các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $builder = $this->builder();
        
        // Xử lý withDeleted nếu cần
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where($this->table . '.deleted_at IS NOT NULL');
        } else {
            // Mặc định chỉ lấy dữ liệu chưa xóa
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo diễn giả
        if (isset($criteria['dien_gia_id'])) {
            $builder->where($this->table . '.dien_gia_id', $criteria['dien_gia_id']);
        }
        
        // Xử lý lọc theo trạng thái tham gia
        if (isset($criteria['trang_thai_tham_gia'])) {
            $builder->where($this->table . '.trang_thai_tham_gia', $criteria['trang_thai_tham_gia']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        // Xác định trường sắp xếp và thứ tự sắp xếp
        $sort = $options['sort'] ?? 'thu_tu';
        $order = $options['order'] ?? 'ASC';
        
        // Xử lý giới hạn và phân trang
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        // Thực hiện truy vấn với phân trang
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Sắp xếp kết quả
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập pager nếu cần
        if ($limit > 0) {
            $totalRows = $this->countSearchResults($criteria);
            $this->pager = new Pager(
                $totalRows,
                $limit,
                floor($offset / $limit) + 1
            );
            $this->pager->setSurroundCount($this->surroundCount ?? 2);
        }
        
        return $result;
    }
    
    /**
     * Đếm tổng số kết quả tìm kiếm
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countSearchResults(array $criteria = [])
    {
        $builder = $this->builder();
        
        // Xử lý withDeleted nếu cần
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where($this->table . '.deleted_at IS NOT NULL');
        } else {
            // Mặc định chỉ lấy dữ liệu chưa xóa
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo diễn giả
        if (isset($criteria['dien_gia_id'])) {
            $builder->where($this->table . '.dien_gia_id', $criteria['dien_gia_id']);
        }
        
        // Xử lý lọc theo trạng thái tham gia
        if (isset($criteria['trang_thai_tham_gia'])) {
            $builder->where($this->table . '.trang_thai_tham_gia', $criteria['trang_thai_tham_gia']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên tình huống
     * 
     * @param string $scenario Tình huống xác thực ('insert' hoặc 'update')
     * @param array $data Dữ liệu cần xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $entity = new SuKienDienGia();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        // Loại trừ các trường thời gian vì sẽ được xử lý thủ công trong controller
        unset($this->validationRules['thoi_gian_trinh_bay']);
        unset($this->validationRules['thoi_gian_ket_thuc']);
    }
    
    /**
     * Chuyển đổi định dạng thời gian từ HTML (Y-m-d\TH:i) sang định dạng cơ sở dữ liệu (Y-m-d H:i:s)
     *
     * @param string|null $datetimeString Chuỗi thời gian theo định dạng HTML
     * @return string|null Chuỗi thời gian theo định dạng cơ sở dữ liệu
     */
    public function formatDateTime($datetimeString)
    {
        if (empty($datetimeString)) {
            return null;
        }
        
        // Chuyển đổi từ '2025-03-28T12:12' sang '2025-03-28 12:12:00'
        try {
            $datetime = \DateTime::createFromFormat('Y-m-d\TH:i', $datetimeString);
            if ($datetime) {
                return $datetime->format('Y-m-d H:i:s');
            }
            
            // Thử với định dạng khác nếu không phù hợp
            $datetime = \DateTime::createFromFormat('Y-m-d H:i', $datetimeString);
            if ($datetime) {
                return $datetime->format('Y-m-d H:i:s');
            }
            
            // Nếu là timestamp đầy đủ
            $datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $datetimeString);
            if ($datetime) {
                return $datetimeString;
            }
        } catch (\Exception $e) {
            // Ghi log lỗi nếu cần
            log_message('error', 'Lỗi chuyển đổi thời gian: ' . $e->getMessage());
        }
        
        return $datetimeString;
    }

    /**
     * Thiết lập số lượng liên kết trang hiển thị xung quanh trang hiện tại
     * 
     * @param int $count Số lượng liên kết trang hiển thị (mỗi bên)
     * @return $this
     */
    public function setSurroundCount(int $count)
    {
        if ($this->pager !== null) {
            $this->pager->setSurroundCount($count);
        }
        
        return $this;
    }
    
    /**
     * Lấy đối tượng phân trang 
     * 
     * @return Pager|null
     */
    public function getPager()
    {
        return $this->pager;
    }
    
    /**
     * Lấy danh sách diễn giả theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @param array $options Các tùy chọn bổ sung
     * @return array
     */
    public function getDienGiaBySuKien(int $suKienId, array $options = [])
    {
        $builder = $this->builder();
        $builder->select($this->table . '.*, dg.ten_dien_gia, dg.chuc_danh, dg.to_chuc, dg.avatar');
        $builder->join('dien_gia dg', 'dg.dien_gia_id = ' . $this->table . '.dien_gia_id', 'left');
        $builder->where($this->table . '.su_kien_id', $suKienId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Lọc theo trạng thái tham gia
        if (isset($options['trang_thai_tham_gia'])) {
            $builder->where($this->table . '.trang_thai_tham_gia', $options['trang_thai_tham_gia']);
        }
        
        // Lọc theo hiển thị công khai
        if (isset($options['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $options['hien_thi_cong_khai']);
        }
        
        // Sắp xếp
        $sort = $options['sort'] ?? 'thu_tu';
        $order = $options['order'] ?? 'ASC';
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Giới hạn và phân trang
        if (isset($options['limit']) && $options['limit'] > 0) {
            $offset = $options['offset'] ?? 0;
            $builder->limit($options['limit'], $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện theo diễn giả
     *
     * @param int $dienGiaId ID của diễn giả
     * @param array $options Các tùy chọn bổ sung
     * @return array
     */
    public function getSuKienByDienGia(int $dienGiaId, array $options = [])
    {
        $builder = $this->builder();
        $builder->select($this->table . '.*, sk.ten_su_kien, sk.thoi_gian_bat_dau, sk.thoi_gian_ket_thuc as sk_thoi_gian_ket_thuc, sk.dia_diem, sk.hinh_anh');
        $builder->join('su_kien sk', 'sk.su_kien_id = ' . $this->table . '.su_kien_id', 'left');
        $builder->where($this->table . '.dien_gia_id', $dienGiaId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Lọc theo trạng thái tham gia
        if (isset($options['trang_thai_tham_gia'])) {
            $builder->where($this->table . '.trang_thai_tham_gia', $options['trang_thai_tham_gia']);
        }
        
        // Sắp xếp
        $sort = $options['sort'] ?? 'sk.thoi_gian_bat_dau';
        $order = $options['order'] ?? 'DESC';
        $builder->orderBy($sort, $order);
        
        // Giới hạn và phân trang
        if (isset($options['limit']) && $options['limit'] > 0) {
            $offset = $options['offset'] ?? 0;
            $builder->limit($options['limit'], $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Kiểm tra xem mối quan hệ giữa sự kiện và diễn giả đã tồn tại chưa
     *
     * @param int $suKienId ID của sự kiện
     * @param int $dienGiaId ID của diễn giả
     * @param int|null $excludeId ID của bản ghi cần loại trừ khi kiểm tra (cho update)
     * @return bool
     */
    public function isRelationExists(int $suKienId, int $dienGiaId, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('su_kien_id', $suKienId);
        $builder->where('dien_gia_id', $dienGiaId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Cập nhật trạng thái tham gia của diễn giả
     *
     * @param int $id ID của mối quan hệ
     * @param string $trangThai Trạng thái tham gia mới
     * @return bool
     */
    public function updateTrangThaiThamGia(int $id, string $trangThai): bool
    {
        if (!in_array($trangThai, ['xac_nhan', 'cho_xac_nhan', 'tu_choi', 'khong_lien_he_duoc'])) {
            return false;
        }
        
        return $this->update($id, [
            'trang_thai_tham_gia' => $trangThai,
            'updated_at' => Time::now()->toDateTimeString()
        ]);
    }
    
    /**
     * Tìm kiếm các bản ghi đã xóa
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        // Đảm bảo withDeleted được thiết lập để tìm kiếm cả các bản ghi đã xóa
        $this->withDeleted();
        
        $builder = $this->builder();
        
        // Chỉ lấy dữ liệu đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo diễn giả
        if (isset($criteria['dien_gia_id'])) {
            $builder->where($this->table . '.dien_gia_id', $criteria['dien_gia_id']);
        }
        
        // Xử lý lọc theo trạng thái tham gia
        if (isset($criteria['trang_thai_tham_gia'])) {
            $builder->where($this->table . '.trang_thai_tham_gia', $criteria['trang_thai_tham_gia']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        // Xác định trường sắp xếp và thứ tự sắp xếp
        $sort = $options['sort'] ?? 'deleted_at';
        $order = $options['order'] ?? 'DESC';
        
        // Xử lý giới hạn và phân trang
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        // Thực hiện truy vấn với phân trang
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Sắp xếp kết quả
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập pager nếu cần
        if ($limit > 0) {
            $totalRows = $this->countDeletedSearchResults($criteria);
            $this->pager = new Pager(
                $totalRows,
                $limit,
                floor($offset / $limit) + 1
            );
            $this->pager->setSurroundCount($this->surroundCount ?? 2);
        }
        
        return $result;
    }
    
    /**
     * Đếm tổng số kết quả tìm kiếm đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedSearchResults(array $criteria = [])
    {
        // Đảm bảo withDeleted được thiết lập để tìm kiếm cả các bản ghi đã xóa
        $this->withDeleted();
        
        $builder = $this->builder();
        
        // Chỉ đếm bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo diễn giả
        if (isset($criteria['dien_gia_id'])) {
            $builder->where($this->table . '.dien_gia_id', $criteria['dien_gia_id']);
        }
        
        // Xử lý lọc theo trạng thái tham gia
        if (isset($criteria['trang_thai_tham_gia'])) {
            $builder->where($this->table . '.trang_thai_tham_gia', $criteria['trang_thai_tham_gia']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        return $builder->countAllResults();
    }
} 