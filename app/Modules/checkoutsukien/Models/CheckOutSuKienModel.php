<?php

namespace App\Modules\checkoutsukien\Models;

use App\Models\BaseModel;
use App\Modules\checkoutsukien\Entities\CheckOutSuKien;
use App\Modules\checkoutsukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

class CheckOutSuKienModel extends BaseModel
{
    protected $table = 'checkout_sukien';
    protected $primaryKey = 'checkout_sukien_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'sukien_id',
        'email',
        'ho_ten',
        'dangky_sukien_id',
        'checkin_sukien_id',
        'thoi_gian_check_out',
        'checkout_type',
        'face_image_path',
        'face_match_score',
        'face_verified',
        'ma_xac_nhan',
        'status',
        'location_data',
        'device_info',
        'attendance_duration_minutes',
        'hinh_thuc_tham_gia',
        'ip_address',
        'thong_tin_bo_sung',
        'ghi_chu',
        'feedback',
        'danh_gia',
        'noi_dung_danh_gia',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = CheckOutSuKien::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'email',
        'ho_ten',
        'thoi_gian_check_out',
        'checkout_type',
        'ma_xac_nhan',
        'attendance_duration_minutes',
        'hinh_thuc_tham_gia',
        'ghi_chu',
        'feedback',
        'noi_dung_danh_gia'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'sukien_id',
        'dangky_sukien_id',
        'checkin_sukien_id',
        'checkout_type',
        'status',
        'hinh_thuc_tham_gia',
        'danh_gia'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // CheckOutSuKien pager
    public $pager = null;
    
    // Định nghĩa mối quan hệ
    protected $relations = [
        'sukien' => [
            'type' => '1-1',
            'table' => 'su_kien',
            'foreignKey' => 'su_kien_id',
            'localKey' => 'sukien_id',
            'entity' => 'App\Modules\sukien\Entities\SuKien',
            'useSoftDeletes' => true
        ],
        'dangkysukien' => [
            'type' => '1-1',
            'table' => 'dangky_sukien',
            'foreignKey' => 'dangky_sukien_id',
            'localKey' => 'dangky_sukien_id',
            'entity' => 'App\Modules\dangkysukien\Entities\DangKySuKien',
            'useSoftDeletes' => true
        ],
        'checkinsukien' => [
            'type' => '1-1',
            'table' => 'checkin_sukien',
            'foreignKey' => 'checkin_sukien_id',
            'localKey' => 'checkin_sukien_id',
            'entity' => 'App\Modules\checkinsukien\Entities\CheckInSuKien',
            'useSoftDeletes' => true
        ]
    ];
    
    /**
     * Lấy tất cả bản ghi check-out sự kiện
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'thoi_gian_check_out', $order = 'DESC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select($this->table . '.*, su_kien.ten_su_kien');
        $this->builder->join('su_kien', 'su_kien.su_kien_id = ' . $this->table . '.sukien_id', 'left');
        
        // Chỉ lấy bản ghi chưa xóa và đang hoạt động
        $this->builder->where($this->table . '.deleted_at IS NULL');
        $this->builder->where($this->table . '.status', 1);
        
        if ($sort && $order) {
            $this->builder->orderBy($this->table . '.' . $sort, $order);
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
        
        $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số bản ghi check-out sự kiện
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm bản ghi chưa xóa và đang hoạt động
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->where($this->table . '.status', 1);
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm check-out sự kiện dựa vào các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $builder = $this->builder();
        $builder->select($this->table . '.*, su_kien.ten_su_kien');
        $builder->join('su_kien', 'su_kien.su_kien_id = ' . $this->table . '.sukien_id', 'left');
        
        // Xử lý withDeleted nếu cần
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where($this->table . '.deleted_at IS NOT NULL');
        } else {
            // Mặc định chỉ lấy dữ liệu chưa xóa
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo sự kiện
        if (isset($criteria['sukien_id']) && $criteria['sukien_id'] > 0) {
            $builder->where($this->table . '.sukien_id', $criteria['sukien_id']);
        }
        
        // Lọc theo đăng ký sự kiện
        if (isset($criteria['dangky_sukien_id']) && $criteria['dangky_sukien_id'] > 0) {
            $builder->where($this->table . '.dangky_sukien_id', $criteria['dangky_sukien_id']);
        }
        
        // Lọc theo check-in sự kiện
        if (isset($criteria['checkin_sukien_id']) && $criteria['checkin_sukien_id'] > 0) {
            $builder->where($this->table . '.checkin_sukien_id', $criteria['checkin_sukien_id']);
        }
        
        // Lọc theo loại check-out
        if (isset($criteria['checkout_type']) && !empty($criteria['checkout_type'])) {
            $builder->where($this->table . '.checkout_type', $criteria['checkout_type']);
        }
        
        // Lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && !empty($criteria['hinh_thuc_tham_gia'])) {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Lọc theo đánh giá
        if (isset($criteria['danh_gia']) && $criteria['danh_gia'] > 0) {
            $builder->where($this->table . '.danh_gia', $criteria['danh_gia']);
        }
        
        // Lọc theo thời gian check-out từ ngày
        if (isset($criteria['tu_ngay']) && !empty($criteria['tu_ngay'])) {
            $tuNgay = $criteria['tu_ngay'] . ' 00:00:00';
            $builder->where($this->table . '.thoi_gian_check_out >=', $tuNgay);
        }
        
        // Lọc theo thời gian check-out đến ngày
        if (isset($criteria['den_ngay']) && !empty($criteria['den_ngay'])) {
            $denNgay = $criteria['den_ngay'] . ' 23:59:59';
            $builder->where($this->table . '.thoi_gian_check_out <=', $denNgay);
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
        $sort = $options['sort'] ?? 'thoi_gian_check_out';
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
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo sự kiện
        if (isset($criteria['sukien_id']) && $criteria['sukien_id'] > 0) {
            $builder->where($this->table . '.sukien_id', $criteria['sukien_id']);
        }
        
        // Lọc theo đăng ký sự kiện
        if (isset($criteria['dangky_sukien_id']) && $criteria['dangky_sukien_id'] > 0) {
            $builder->where($this->table . '.dangky_sukien_id', $criteria['dangky_sukien_id']);
        }
        
        // Lọc theo check-in sự kiện
        if (isset($criteria['checkin_sukien_id']) && $criteria['checkin_sukien_id'] > 0) {
            $builder->where($this->table . '.checkin_sukien_id', $criteria['checkin_sukien_id']);
        }
        
        // Lọc theo loại check-out
        if (isset($criteria['checkout_type']) && !empty($criteria['checkout_type'])) {
            $builder->where($this->table . '.checkout_type', $criteria['checkout_type']);
        }
        
        // Lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && !empty($criteria['hinh_thuc_tham_gia'])) {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Lọc theo đánh giá
        if (isset($criteria['danh_gia']) && $criteria['danh_gia'] > 0) {
            $builder->where($this->table . '.danh_gia', $criteria['danh_gia']);
        }
        
        // Lọc theo thời gian check-out từ ngày
        if (isset($criteria['tu_ngay']) && !empty($criteria['tu_ngay'])) {
            $tuNgay = $criteria['tu_ngay'] . ' 00:00:00';
            $builder->where($this->table . '.thoi_gian_check_out >=', $tuNgay);
        }
        
        // Lọc theo thời gian check-out đến ngày
        if (isset($criteria['den_ngay']) && !empty($criteria['den_ngay'])) {
            $denNgay = $criteria['den_ngay'] . ' 23:59:59';
            $builder->where($this->table . '.thoi_gian_check_out <=', $denNgay);
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
     * Lấy thông tin check-out theo sự kiện
     *
     * @param int $suKienId ID sự kiện
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function getBySuKien(int $suKienId, array $options = [])
    {
        $criteria = [
            'sukien_id' => $suKienId
        ];
        
        return $this->search($criteria, $options);
    }
    
    /**
     * Lấy thông tin check-out theo check-in
     *
     * @param int $checkInId ID check-in
     * @return CheckOutSuKien|null
     */
    public function getByCheckIn(int $checkInId)
    {
        return $this->where('checkin_sukien_id', $checkInId)
                     ->where('deleted_at IS NULL')
                     ->first();
    }
    
    /**
     * Lấy thông tin check-out theo đăng ký
     *
     * @param int $dangKyId ID đăng ký
     * @param int $suKienId ID sự kiện (tùy chọn)
     * @return CheckOutSuKien|null
     */
    public function getByDangKy(int $dangKyId, ?int $suKienId = null)
    {
        $query = $this->where('dangky_sukien_id', $dangKyId)
                      ->where('deleted_at IS NULL');
        
        if ($suKienId !== null) {
            $query->where('sukien_id', $suKienId);
        }
        
        return $query->first();
    }
    
    /**
     * Lấy thông tin check-out theo email và sự kiện
     *
     * @param string $email Email
     * @param int $suKienId ID sự kiện
     * @return CheckOutSuKien|null
     */
    public function getByEmailAndSuKien(string $email, int $suKienId)
    {
        return $this->where('email', $email)
                     ->where('sukien_id', $suKienId)
                     ->where('deleted_at IS NULL')
                     ->first();
    }
    
    /**
     * Đếm số lượng check-out theo sự kiện
     * 
     * @param int $suKienId ID sự kiện
     * @return int
     */
    public function countBySuKien(int $suKienId): int
    {
        return $this->where('sukien_id', $suKienId)
                    ->where('deleted_at IS NULL')
                    ->countAllResults();
    }
    
    /**
     * Đếm số lượng đánh giá theo sự kiện và số sao
     * 
     * @param int $suKienId ID sự kiện
     * @param int|null $danhGia Số sao đánh giá (1-5), null để đếm tất cả
     * @return int
     */
    public function countDanhGiaBySuKien(int $suKienId, ?int $danhGia = null): int
    {
        $query = $this->where('sukien_id', $suKienId)
                      ->where('deleted_at IS NULL')
                      ->where('danh_gia IS NOT NULL');
        
        if ($danhGia !== null) {
            $query->where('danh_gia', $danhGia);
        }
        
        return $query->countAllResults();
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên tình huống
     * 
     * @param string $scenario Tình huống xác thực ('insert' hoặc 'update')
     * @param array $data Dữ liệu cần xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $entity = new CheckOutSuKien();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Tùy chỉnh thêm quy tắc xác thực dựa vào tình huống
        if ($scenario === 'update' && isset($data[$this->primaryKey])) {
            // Bỏ qua xác thực không bắt buộc khi cập nhật
            if (isset($this->validationRules['sukien_id'])) {
                $this->validationRules['sukien_id']['rules'] = 'permit_empty|integer|is_not_unique[su_kien.su_kien_id]';
            }
            
            if (isset($this->validationRules['email'])) {
                $this->validationRules['email']['rules'] = 'permit_empty|valid_email';
            }
            
            if (isset($this->validationRules['ho_ten'])) {
                $this->validationRules['ho_ten']['rules'] = 'permit_empty|min_length[3]|max_length[255]';
            }
            
            if (isset($this->validationRules['thoi_gian_check_out'])) {
                $this->validationRules['thoi_gian_check_out']['rules'] = 'permit_empty|valid_date';
            }
            
            if (isset($this->validationRules['checkout_type'])) {
                $this->validationRules['checkout_type']['rules'] = 'permit_empty|in_list[face_id,manual,qr_code,auto,online]';
            }
            
            if (isset($this->validationRules['hinh_thuc_tham_gia'])) {
                $this->validationRules['hinh_thuc_tham_gia']['rules'] = 'permit_empty|in_list[offline,online]';
            }
        }
    }
    
    /**
     * Thiết lập số lượng liên kết trang hiển thị xung quanh trang hiện tại
     *
     * @param int $count Số lượng liên kết
     * @return $this
     */
    public function setSurroundCount(int $count)
    {
        $this->surroundCount = $count;
        
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
} 