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
        'su_kien_id',
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
        'su_kien_id',
        'dangky_sukien_id',
        'checkin_sukien_id',
        'checkout_type',
        'status',
        'hinh_thuc_tham_gia',
        'face_verified',
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
            'localKey' => 'su_kien_id',
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
        $this->builder->join('su_kien', 'su_kien.su_kien_id = ' . $this->table . '.su_kien_id', 'left');
        
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
        $builder->join('su_kien', 'su_kien.su_kien_id = ' . $this->table . '.su_kien_id', 'left');
        
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
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] > 0) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
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
        // Kiểm tra trường sắp xếp tồn tại trong bảng
        $validSortFields = ['thoi_gian_check_out', 'created_at', 'updated_at', 'deleted_at', 'su_kien_id', 'email', 'ho_ten', 'status'];
        $sort = in_array($sort, $validSortFields) ? $sort : 'deleted_at';
        
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
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] > 0) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
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
            'su_kien_id' => $suKienId
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
            $query->where('su_kien_id', $suKienId);
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
                     ->where('su_kien_id', $suKienId)
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
        return $this->where('su_kien_id', $suKienId)
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
        $query = $this->where('su_kien_id', $suKienId)
                      ->where('deleted_at IS NULL')
                      ->where('danh_gia IS NOT NULL');
        
        if ($danhGia !== null) {
            $query->where('danh_gia', $danhGia);
        }
        
        return $query->countAllResults();
    }
    
    /**
     * Tính điểm đánh giá trung bình của sự kiện
     * 
     * @param int $suKienId ID sự kiện
     * @return float|null Điểm trung bình hoặc null nếu không có đánh giá
     */
    public function getAverageDanhGiaBySuKien(int $suKienId): ?float
    {
        $builder = $this->builder();
        $builder->selectAvg('danh_gia', 'average_rating');
        $builder->where('su_kien_id', $suKienId);
        $builder->where('deleted_at IS NULL');
        $builder->where('danh_gia IS NOT NULL');
        
        $result = $builder->get()->getRow();
        
        if (empty($result) || $result->average_rating === null) {
            return null;
        }
        
        return round((float)$result->average_rating, 1);
    }
    
    /**
     * Lấy danh sách các phản hồi đánh giá đã gắn sao cho sự kiện
     * 
     * @param int $suKienId ID sự kiện
     * @param int $limit Số lượng kết quả tối đa
     * @param int $offset Vị trí bắt đầu
     * @return array
     */
    public function getDanhGiaFeedbackBySuKien(int $suKienId, int $limit = 10, int $offset = 0): array
    {
        return $this->select('checkout_sukien_id, ho_ten, email, danh_gia, noi_dung_danh_gia, feedback, created_at')
            ->where('su_kien_id', $suKienId)
            ->where('deleted_at IS NULL')
            ->where('danh_gia IS NOT NULL')
            ->where('(noi_dung_danh_gia IS NOT NULL OR feedback IS NOT NULL)')
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->find();
    }
    
    /**
     * Lấy thống kê số lượng người check-out theo loại
     * 
     * @param int $suKienId ID sự kiện
     * @return array
     */
    public function getCheckoutTypeStatsBySuKien(int $suKienId): array
    {
        $builder = $this->builder();
        $builder->select('checkout_type, COUNT(*) as count');
        $builder->where('su_kien_id', $suKienId);
        $builder->where('deleted_at IS NULL');
        $builder->groupBy('checkout_type');
        
        $result = $builder->get()->getResult();
        
        $stats = [];
        foreach ($result as $row) {
            $stats[$row->checkout_type] = (int)$row->count;
        }
        
        return $stats;
    }
    
    /**
     * Lấy thống kê số lượng người check-out theo hình thức tham gia
     * 
     * @param int $suKienId ID sự kiện
     * @return array
     */
    public function getHinhThucThamGiaStatsBySuKien(int $suKienId): array
    {
        $builder = $this->builder();
        $builder->select('hinh_thuc_tham_gia, COUNT(*) as count');
        $builder->where('su_kien_id', $suKienId);
        $builder->where('deleted_at IS NULL');
        $builder->groupBy('hinh_thuc_tham_gia');
        
        $result = $builder->get()->getResult();
        
        $stats = [
            'offline' => 0,
            'online' => 0
        ];
        
        foreach ($result as $row) {
            $stats[$row->hinh_thuc_tham_gia] = (int)$row->count;
        }
        
        return $stats;
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên kịch bản
     * 
     * @param string $scenario Kịch bản (insert hoặc update)
     * @param array $data Dữ liệu cần xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        // Khởi tạo đối tượng thực thể và lấy quy tắc xác thực
        $entity = new \App\Modules\checkoutsukien\Entities\CheckOutSuKien();
        $rules = $entity->getValidationRules();
        $messages = $entity->getValidationMessages();
        
        // Các trường cần bỏ qua khi cập nhật
        $skipFieldsOnUpdate = [
            'created_at', 'updated_at', 'deleted_at'
        ];
        
        // Các trường cho phép null
        $nullableFields = [
            'dangky_sukien_id', 'checkin_sukien_id', 'face_match_score', 
            'ma_xac_nhan', 'danh_gia', 'ghi_chu', 'noi_dung_danh_gia',
            'feedback', 'attendance_duration_minutes', 'face_image_path', 
            'location_data', 'device_info', 'thong_tin_bo_sung'
        ];
        
        // Trong trường hợp cập nhật, chỉ áp dụng quy tắc cho các trường có trong dữ liệu
        if ($scenario === 'update') {
            // Tạo bản sao của quy tắc ban đầu
            $updatedRules = [];
            
            // Chỉ giữ lại các quy tắc cho những trường có trong dữ liệu và không nằm trong danh sách bỏ qua
            foreach ($rules as $field => $rule) {
                if (array_key_exists($field, $data) && !in_array($field, $skipFieldsOnUpdate)) {
                    $updatedRules[$field] = $rule;
                }
            }
            
            // Đảm bảo trường ID và su_kien_id luôn được kiểm tra trong cập nhật
            if (isset($data[$this->primaryKey])) {
                $updatedRules[$this->primaryKey] = $rules[$this->primaryKey] ?? [
                    'rules' => 'required|integer|is_not_unique[' . $this->table . '.' . $this->primaryKey . ']',
                    'label' => 'ID'
                ];
            }
            
            if (isset($data['su_kien_id'])) {
                // Đổi thành kiểm tra chỉ trường su_kien_id mà không quan tâm đến deleted_at
                $updatedRules['su_kien_id'] = [
                    'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
                    'label' => 'Sự kiện'
                ];
            }
            
            // Cập nhật lại rules
            $rules = $updatedRules;
        } else {
            // Đối với insert, đảm bảo sự kiện tồn tại
            $rules['su_kien_id'] = [
                'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
                'label' => 'Sự kiện'
            ];
        }
        
        // Điều chỉnh quy tắc cho các trường có thể null
        foreach ($nullableFields as $field) {
            if (isset($rules[$field])) {
                if (is_array($rules[$field]) && isset($rules[$field]['rules'])) {
                    // Thêm permit_empty vào quy tắc
                    if (strpos($rules[$field]['rules'], 'permit_empty') === false) {
                        $rules[$field]['rules'] = 'permit_empty|' . preg_replace('/^required\|/', '', $rules[$field]['rules']);
                    }
                } else {
                    // Thêm permit_empty vào quy tắc
                    if (strpos($rules[$field], 'permit_empty') === false) {
                        $rules[$field] = 'permit_empty|' . preg_replace('/^required\|/', '', $rules[$field]);
                    }
                }
            }
        }
        
        // Quy tắc đặc biệt cho face_match_score
        if (isset($rules['face_match_score'])) {
            if (is_array($rules['face_match_score']) && isset($rules['face_match_score']['rules'])) {
                $rules['face_match_score']['rules'] = 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[1]';
            } else {
                $rules['face_match_score'] = 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[1]';
            }
        }
        
        // Quy tắc đặc biệt cho danh_gia
        if (isset($rules['danh_gia'])) {
            if (is_array($rules['danh_gia']) && isset($rules['danh_gia']['rules'])) {
                $rules['danh_gia']['rules'] = 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[5]';
            } else {
                $rules['danh_gia'] = 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[5]';
            }
        }
        
        // Quy tắc đặc biệt cho đường dẫn ảnh
        if (isset($rules['face_image_path'])) {
            if (is_array($rules['face_image_path']) && isset($rules['face_image_path']['rules'])) {
                $rules['face_image_path']['rules'] = 'permit_empty|string|max_length[255]';
            } else {
                $rules['face_image_path'] = 'permit_empty|string|max_length[255]';
            }
        }
        
        // Luôn đảm bảo email và ho_ten là bắt buộc
        if (isset($rules['email'])) {
            if (is_array($rules['email']) && isset($rules['email']['rules'])) {
                if (strpos($rules['email']['rules'], 'required') === false) {
                    $rules['email']['rules'] = 'required|' . $rules['email']['rules'];
                }
            } else if (strpos($rules['email'], 'required') === false) {
                $rules['email'] = 'required|' . $rules['email'];
            }
        }
        
        if (isset($rules['ho_ten'])) {
            if (is_array($rules['ho_ten']) && isset($rules['ho_ten']['rules'])) {
                if (strpos($rules['ho_ten']['rules'], 'required') === false) {
                    $rules['ho_ten']['rules'] = 'required|' . $rules['ho_ten']['rules'];
                }
            } else if (strpos($rules['ho_ten'], 'required') === false) {
                $rules['ho_ten'] = 'required|' . $rules['ho_ten'];
            }
        }
        
        // Log các quy tắc cuối cùng để debug
        log_message('debug', 'Validation rules for ' . $scenario . ': ' . json_encode($rules));
        
        // Thiết lập quy tắc xác thực và thông báo
        $this->setValidationRules($rules);
        $this->setValidationMessages($messages);
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
    
    /**
     * Cập nhật trạng thái tham gia
     *
     * @param int $id ID của bản ghi cần cập nhật
     * @param int $status Trạng thái mới (0: Vô hiệu, 1: Hoạt động, 2: Đang xử lý)
     * @return bool Kết quả cập nhật
     */
    public function updateTrangThaiThamGia(int $id, int $status): bool
    {
        // Kiểm tra ID hợp lệ
        if ($id <= 0) {
            log_message('error', "updateTrangThaiThamGia: ID không hợp lệ: {$id}");
            return false;
        }
        
        // Kiểm tra trạng thái hợp lệ
        if (!in_array($status, [0, 1, 2])) {
            log_message('error', "updateTrangThaiThamGia: Trạng thái không hợp lệ: {$status}");
            return false;
        }
        
        // Kiểm tra sự tồn tại của bản ghi
        $existingRecord = $this->find($id);
        if (!$existingRecord) {
            log_message('error', "updateTrangThaiThamGia: Không tìm thấy bản ghi với ID: {$id}");
            return false;
        }
        
        // Chuẩn bị dữ liệu cập nhật
        $data = [
            'status' => $status,
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        try {
            // Sử dụng builder để thực hiện cập nhật trực tiếp
            $builder = $this->builder();
            $result = $builder->where($this->primaryKey, $id)
                             ->update($data);
            
            // Log thông tin kết quả cập nhật
            if ($result) {
                log_message('info', "updateTrangThaiThamGia: Cập nhật thành công ID: {$id}, status: {$status}");
                return true;
            } else {
                log_message('error', "updateTrangThaiThamGia: Cập nhật không thành công ID: {$id}, status: {$status}");
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', "updateTrangThaiThamGia: Lỗi khi cập nhật ID: {$id}, status: {$status}, lỗi: " . $e->getMessage());
            return false;
        }
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
        $builder = $this->builder();
        $builder->select($this->table . '.*, su_kien.ten_su_kien');
        $builder->join('su_kien', 'su_kien.su_kien_id = ' . $this->table . '.su_kien_id', 'left');
        
        // Chỉ lấy dữ liệu đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] > 0) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
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
        
        // Lọc theo xác minh khuôn mặt
        if (isset($criteria['face_verified'])) {
            $builder->where($this->table . '.face_verified', $criteria['face_verified']);
        }
        
        // Lọc theo đánh giá
        if (isset($criteria['danh_gia']) && $criteria['danh_gia'] > 0) {
            $builder->where($this->table . '.danh_gia', $criteria['danh_gia']);
        }
        
        // Lọc theo thời gian check-out từ ngày
        if (isset($criteria['start_date']) && !empty($criteria['start_date'])) {
            $tuNgay = $criteria['start_date'] . ' 00:00:00';
            $builder->where($this->table . '.thoi_gian_check_out >=', $tuNgay);
        }
        
        // Lọc theo thời gian check-out đến ngày
        if (isset($criteria['end_date']) && !empty($criteria['end_date'])) {
            $denNgay = $criteria['end_date'] . ' 23:59:59';
            $builder->where($this->table . '.thoi_gian_check_out <=', $denNgay);
        }
        
        // Tìm kiếm theo từ khóa
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
        // Kiểm tra trường sắp xếp tồn tại trong bảng
        $validSortFields = ['thoi_gian_check_out', 'created_at', 'updated_at', 'deleted_at', 'su_kien_id', 'email', 'ho_ten', 'status'];
        $sort = in_array($sort, $validSortFields) ? $sort : 'deleted_at';
        
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
        $builder = $this->builder();
        
        // Chỉ đếm bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] > 0) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
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
        
        // Lọc theo xác minh khuôn mặt
        if (isset($criteria['face_verified'])) {
            $builder->where($this->table . '.face_verified', $criteria['face_verified']);
        }
        
        // Lọc theo đánh giá
        if (isset($criteria['danh_gia']) && $criteria['danh_gia'] > 0) {
            $builder->where($this->table . '.danh_gia', $criteria['danh_gia']);
        }
        
        // Lọc theo thời gian check-out từ ngày
        if (isset($criteria['start_date']) && !empty($criteria['start_date'])) {
            $tuNgay = $criteria['start_date'] . ' 00:00:00';
            $builder->where($this->table . '.thoi_gian_check_out >=', $tuNgay);
        }
        
        // Lọc theo thời gian check-out đến ngày
        if (isset($criteria['end_date']) && !empty($criteria['end_date'])) {
            $denNgay = $criteria['end_date'] . ' 23:59:59';
            $builder->where($this->table . '.thoi_gian_check_out <=', $denNgay);
        }
        
        // Tìm kiếm theo từ khóa
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
     * Ghi đè phương thức errors để thêm thông tin debug
     *
     * @param bool $forceDB Buộc lấy lỗi từ database
     * @return array
     */
    public function errors(bool $forceDB = false)
    {
        $errors = parent::errors($forceDB);
        log_message('debug', 'Validation errors: ' . json_encode($errors));
        return $errors;
    }
} 