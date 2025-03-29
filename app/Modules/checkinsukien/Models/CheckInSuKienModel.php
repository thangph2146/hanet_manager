<?php

namespace App\Modules\checkinsukien\Models;

use App\Models\BaseModel;
use App\Modules\checkinsukien\Entities\CheckInSuKien;
use App\Modules\checkinsukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

class CheckInSuKienModel extends BaseModel
{
    protected $table = 'checkin_sukien';
    protected $primaryKey = 'checkin_sukien_id';
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
        'thoi_gian_check_in',
        'checkin_type',
        'face_image_path',
        'face_match_score',
        'face_verified',
        'ma_xac_nhan',
        'status',
        'location_data',
        'device_info',
        'hinh_thuc_tham_gia',
        'ip_address',
        'thong_tin_bo_sung',
        'ghi_chu',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = CheckInSuKien::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'email',
        'ho_ten',
        'ma_xac_nhan',
        'ghi_chu',
        'ip_address',
        'location_data',
        'device_info'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'su_kien_id',
        'dangky_sukien_id',
        'checkin_type',
        'face_verified',
        'status',
        'hinh_thuc_tham_gia'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi check-in sự kiện
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'thoi_gian_check_in', $order = 'DESC')
    {
        $builder = $this->builder();
        $builder->select("{$this->table}.*, su_kien.ten_su_kien");
        $builder->join('su_kien', "su_kien.su_kien_id = {$this->table}.su_kien_id", 'left');
        
        // Chỉ lấy bản ghi chưa xóa
        $builder->where("{$this->table}.deleted_at IS NULL");
        
        if ($sort && $order) {
            $builder->orderBy("{$this->table}.{$sort}", $order);
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
     * Đếm tổng số bản ghi check-in
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
     * Tìm kiếm các bản ghi check-in dựa vào các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $builder = $this->builder();
        $builder->select("{$this->table}.*, su_kien.ten_su_kien");
        $builder->join('su_kien', "su_kien.su_kien_id = {$this->table}.su_kien_id", 'left');
        
        // Xử lý withDeleted nếu cần
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where("{$this->table}.deleted_at IS NOT NULL");
        } else {
            // Mặc định chỉ lấy dữ liệu chưa xóa
            $builder->where("{$this->table}.deleted_at IS NULL");
        }
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $builder->where("{$this->table}.su_kien_id", $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo ID đăng ký
        if (isset($criteria['dangky_sukien_id']) && $criteria['dangky_sukien_id'] !== '') {
            $builder->where("{$this->table}.dangky_sukien_id", $criteria['dangky_sukien_id']);
        }
        
        // Xử lý lọc theo loại check-in
        if (isset($criteria['checkin_type']) && $criteria['checkin_type'] !== '') {
            $builder->where("{$this->table}.checkin_type", $criteria['checkin_type']);
        }
        
        // Xử lý lọc theo trạng thái xác minh khuôn mặt
        if (isset($criteria['face_verified']) && $criteria['face_verified'] !== '') {
            $builder->where("{$this->table}.face_verified", $criteria['face_verified']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $builder->where("{$this->table}.status", $criteria['status']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $builder->where("{$this->table}.hinh_thuc_tham_gia", $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý tìm kiếm theo khoảng thời gian
        if (isset($criteria['start_date']) && isset($criteria['end_date'])) {
            $builder->where("{$this->table}.thoi_gian_check_in >=", $criteria['start_date']);
            $builder->where("{$this->table}.thoi_gian_check_in <=", $criteria['end_date']);
        } elseif (isset($criteria['start_date'])) {
            $builder->where("{$this->table}.thoi_gian_check_in >=", $criteria['start_date']);
        } elseif (isset($criteria['end_date'])) {
            $builder->where("{$this->table}.thoi_gian_check_in <=", $criteria['end_date']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like("{$this->table}.{$field}", $keyword);
                } else {
                    $builder->orLike("{$this->table}.{$field}", $keyword);
                }
            }
            // Thêm tìm kiếm theo tên sự kiện
            $builder->orLike("su_kien.ten_su_kien", $keyword);
            $builder->groupEnd();
        }
        
        // Xác định trường sắp xếp và thứ tự sắp xếp
        $sort = $options['sort'] ?? 'thoi_gian_check_in';
        $order = $options['order'] ?? 'DESC';
        
        // Xử lý giới hạn và phân trang
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        // Thực hiện truy vấn với phân trang
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Sắp xếp kết quả
        $builder->orderBy("{$this->table}.{$sort}", $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập pager nếu cần
        if ($limit > 0) {
            $totalRows = $this->countSearchResults($criteria);
            $this->pager = new \App\Modules\checkinsukien\Libraries\Pager(
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
        $builder->join('su_kien', "su_kien.su_kien_id = {$this->table}.su_kien_id", 'left');
        
        // Xử lý withDeleted nếu cần
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where("{$this->table}.deleted_at IS NOT NULL");
        } else {
            // Mặc định chỉ lấy dữ liệu chưa xóa
            $builder->where("{$this->table}.deleted_at IS NULL");
        }
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $builder->where("{$this->table}.su_kien_id", $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo ID đăng ký
        if (isset($criteria['dangky_sukien_id']) && $criteria['dangky_sukien_id'] !== '') {
            $builder->where("{$this->table}.dangky_sukien_id", $criteria['dangky_sukien_id']);
        }
        
        // Xử lý lọc theo loại check-in
        if (isset($criteria['checkin_type']) && $criteria['checkin_type'] !== '') {
            $builder->where("{$this->table}.checkin_type", $criteria['checkin_type']);
        }
        
        // Xử lý lọc theo trạng thái xác minh khuôn mặt
        if (isset($criteria['face_verified']) && $criteria['face_verified'] !== '') {
            $builder->where("{$this->table}.face_verified", $criteria['face_verified']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $builder->where("{$this->table}.status", $criteria['status']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $builder->where("{$this->table}.hinh_thuc_tham_gia", $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý tìm kiếm theo khoảng thời gian
        if (isset($criteria['start_date']) && isset($criteria['end_date'])) {
            $builder->where("{$this->table}.thoi_gian_check_in >=", $criteria['start_date']);
            $builder->where("{$this->table}.thoi_gian_check_in <=", $criteria['end_date']);
        } elseif (isset($criteria['start_date'])) {
            $builder->where("{$this->table}.thoi_gian_check_in >=", $criteria['start_date']);
        } elseif (isset($criteria['end_date'])) {
            $builder->where("{$this->table}.thoi_gian_check_in <=", $criteria['end_date']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like("{$this->table}.{$field}", $keyword);
                } else {
                    $builder->orLike("{$this->table}.{$field}", $keyword);
                }
            }
            // Thêm tìm kiếm theo tên sự kiện
            $builder->orLike("su_kien.ten_su_kien", $keyword);
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
        $entity = new CheckInSuKien();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
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
     * Lấy danh sách check-in theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @param array $options Các tùy chọn bổ sung
     * @return array
     */
    public function getCheckInsByEvent(int $suKienId, array $options = [])
    {
        $builder = $this->builder();
        $builder->where($this->table . '.su_kien_id', $suKienId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Lọc theo loại check-in
        if (isset($options['checkin_type']) && $options['checkin_type'] !== '') {
            $builder->where($this->table . '.checkin_type', $options['checkin_type']);
        }
        
        // Lọc theo hình thức tham gia
        if (isset($options['hinh_thuc_tham_gia']) && $options['hinh_thuc_tham_gia'] !== '') {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $options['hinh_thuc_tham_gia']);
        }
        
        // Lọc theo trạng thái xác minh khuôn mặt
        if (isset($options['face_verified']) && $options['face_verified'] !== '') {
            $builder->where($this->table . '.face_verified', $options['face_verified']);
        }
        
        // Lọc theo ID đăng ký
        if (isset($options['dangky_sukien_id']) && $options['dangky_sukien_id'] !== '') {
            $builder->where($this->table . '.dangky_sukien_id', $options['dangky_sukien_id']);
        }
        
        // Lọc theo khoảng thời gian
        if (isset($options['start_date']) && isset($options['end_date'])) {
            $builder->where($this->table . '.thoi_gian_check_in >=', $options['start_date']);
            $builder->where($this->table . '.thoi_gian_check_in <=', $options['end_date']);
        } elseif (isset($options['start_date'])) {
            $builder->where($this->table . '.thoi_gian_check_in >=', $options['start_date']);
        } elseif (isset($options['end_date'])) {
            $builder->where($this->table . '.thoi_gian_check_in <=', $options['end_date']);
        }
        
        // Sắp xếp
        $sort = $options['sort'] ?? 'thoi_gian_check_in';
        $order = $options['order'] ?? 'DESC';
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Giới hạn và phân trang
        if (isset($options['limit']) && $options['limit'] > 0) {
            $offset = $options['offset'] ?? 0;
            $builder->limit($options['limit'], $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy số lượng check-in theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @param array $options Các tùy chọn lọc
     * @return int
     */
    public function countCheckInsByEvent(int $suKienId, array $options = [])
    {
        $builder = $this->builder();
        $builder->where($this->table . '.su_kien_id', $suKienId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Lọc theo loại check-in
        if (isset($options['checkin_type']) && $options['checkin_type'] !== '') {
            $builder->where($this->table . '.checkin_type', $options['checkin_type']);
        }
        
        // Lọc theo hình thức tham gia
        if (isset($options['hinh_thuc_tham_gia']) && $options['hinh_thuc_tham_gia'] !== '') {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $options['hinh_thuc_tham_gia']);
        }
        
        // Lọc theo trạng thái xác minh khuôn mặt
        if (isset($options['face_verified']) && $options['face_verified'] !== '') {
            $builder->where($this->table . '.face_verified', $options['face_verified']);
        }
        
        // Lọc theo ID đăng ký
        if (isset($options['dangky_sukien_id']) && $options['dangky_sukien_id'] !== '') {
            $builder->where($this->table . '.dangky_sukien_id', $options['dangky_sukien_id']);
        }
        
        // Lọc theo khoảng thời gian
        if (isset($options['start_date']) && isset($options['end_date'])) {
            $builder->where($this->table . '.thoi_gian_check_in >=', $options['start_date']);
            $builder->where($this->table . '.thoi_gian_check_in <=', $options['end_date']);
        } elseif (isset($options['start_date'])) {
            $builder->where($this->table . '.thoi_gian_check_in >=', $options['start_date']);
        } elseif (isset($options['end_date'])) {
            $builder->where($this->table . '.thoi_gian_check_in <=', $options['end_date']);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm check-in sự kiện theo email và ID sự kiện
     *
     * @param string $email Email người check-in
     * @param int $suKienId ID của sự kiện
     * @return CheckInSuKien|null
     */
    public function findByEmailAndEvent(string $email, int $suKienId)
    {
        return $this->where('email', $email)
                   ->where('su_kien_id', $suKienId)
                   ->first();
    }
    
    /**
     * Tìm check-in bằng mã xác nhận
     *
     * @param string $maXacNhan Mã xác nhận
     * @return CheckInSuKien|null
     */
    public function findByConfirmationCode(string $maXacNhan)
    {
        return $this->where('ma_xac_nhan', $maXacNhan)->first();
    }
    
    /**
     * Tạo mã xác nhận ngẫu nhiên
     *
     * @param int $length Độ dài mã xác nhận
     * @return string
     */
    public function generateConfirmationCode(int $length = 8): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Kiểm tra xem mã đã tồn tại chưa
        while ($this->where('ma_xac_nhan', $code)->countAllResults() > 0) {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        }
        
        return $code;
    }
    
    /**
     * Cập nhật trạng thái xác minh khuôn mặt
     *
     * @param int $id ID của check-in
     * @param bool $verified Trạng thái xác minh
     * @param float|null $matchScore Điểm số khớp khuôn mặt
     * @return bool
     */
    public function updateFaceVerification(int $id, bool $verified, ?float $matchScore = null): bool
    {
        $data = [
            'face_verified' => $verified,
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        if ($matchScore !== null) {
            $data['face_match_score'] = $matchScore;
        }
        
        return $this->update($id, $data);
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
        $builder->select("{$this->table}.*, su_kien.ten_su_kien");
        $builder->join('su_kien', "su_kien.su_kien_id = {$this->table}.su_kien_id", 'left');
        
        // Chỉ lấy dữ liệu đã xóa
        $builder->where("{$this->table}.deleted_at IS NOT NULL");
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $builder->where("{$this->table}.su_kien_id", $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo loại check-in
        if (isset($criteria['checkin_type']) && $criteria['checkin_type'] !== '') {
            $builder->where("{$this->table}.checkin_type", $criteria['checkin_type']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $builder->where("{$this->table}.hinh_thuc_tham_gia", $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like("{$this->table}.{$field}", $keyword);
                } else {
                    $builder->orLike("{$this->table}.{$field}", $keyword);
                }
            }
            // Thêm tìm kiếm theo tên sự kiện
            $builder->orLike("su_kien.ten_su_kien", $keyword);
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
        $builder->orderBy("{$this->table}.{$sort}", $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập pager nếu cần
        if ($limit > 0) {
            $totalRows = $this->countDeletedSearchResults($criteria);
            $this->pager = new \App\Modules\checkinsukien\Libraries\Pager(
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
        $builder->join('su_kien', "su_kien.su_kien_id = {$this->table}.su_kien_id", 'left');
        
        // Chỉ đếm bản ghi đã xóa
        $builder->where("{$this->table}.deleted_at IS NOT NULL");
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $builder->where("{$this->table}.su_kien_id", $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo loại check-in
        if (isset($criteria['checkin_type']) && $criteria['checkin_type'] !== '') {
            $builder->where("{$this->table}.checkin_type", $criteria['checkin_type']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $builder->where("{$this->table}.hinh_thuc_tham_gia", $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like("{$this->table}.{$field}", $keyword);
                } else {
                    $builder->orLike("{$this->table}.{$field}", $keyword);
                }
            }
            // Thêm tìm kiếm theo tên sự kiện
            $builder->orLike("su_kien.ten_su_kien", $keyword);
            $builder->groupEnd();
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy thống kê check-in theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @return array
     */
    public function getEventCheckInStats(int $suKienId): array
    {
        $stats = [
            'total' => 0,
            'by_type' => [
                'face_id' => 0,
                'manual' => 0,
                'qr_code' => 0,
                'online' => 0
            ],
            'by_mode' => [
                'offline' => 0,
                'online' => 0
            ],
            'face_verified' => 0,
            'status' => [
                'active' => 0,
                'inactive' => 0,
                'processing' => 0
            ]
        ];
        
        // Tổng số check-in
        $stats['total'] = $this->where('su_kien_id', $suKienId)
                               ->where('deleted_at IS NULL')
                               ->countAllResults();
        
        // Thống kê theo loại check-in
        $checkInTypeStats = $this->select('checkin_type, COUNT(*) as count')
                                ->where('su_kien_id', $suKienId)
                                ->where('deleted_at IS NULL')
                                ->groupBy('checkin_type')
                                ->get()
                                ->getResult();
        
        foreach ($checkInTypeStats as $stat) {
            if (isset($stats['by_type'][$stat->checkin_type])) {
                $stats['by_type'][$stat->checkin_type] = (int)$stat->count;
            }
        }
        
        // Thống kê theo hình thức tham gia
        $participationModeStats = $this->select('hinh_thuc_tham_gia, COUNT(*) as count')
                                      ->where('su_kien_id', $suKienId)
                                      ->where('deleted_at IS NULL')
                                      ->groupBy('hinh_thuc_tham_gia')
                                      ->get()
                                      ->getResult();
        
        foreach ($participationModeStats as $stat) {
            if (isset($stats['by_mode'][$stat->hinh_thuc_tham_gia])) {
                $stats['by_mode'][$stat->hinh_thuc_tham_gia] = (int)$stat->count;
            }
        }
        
        // Thống kê số lượng đã xác thực khuôn mặt
        $stats['face_verified'] = $this->where('su_kien_id', $suKienId)
                                      ->where('face_verified', 1)
                                      ->where('deleted_at IS NULL')
                                      ->countAllResults();
        
        // Thống kê theo trạng thái
        $statusStats = $this->select('status, COUNT(*) as count')
                           ->where('su_kien_id', $suKienId)
                           ->where('deleted_at IS NULL')
                           ->groupBy('status')
                           ->get()
                           ->getResult();
        
        foreach ($statusStats as $stat) {
            switch ((int)$stat->status) {
                case 1:
                    $stats['status']['active'] = (int)$stat->count;
                    break;
                case 0:
                    $stats['status']['inactive'] = (int)$stat->count;
                    break;
                case 2:
                    $stats['status']['processing'] = (int)$stat->count;
                    break;
            }
        }
        
        return $stats;
    }
    
    /**
     * Khôi phục một bản ghi đã bị xóa mềm
     *
     * @param mixed $id ID của bản ghi cần khôi phục
     * @return mixed
     */
    public function restore($id)
    {
        $this->withDeleted();
        $data = [
            'deleted_at' => null,
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        return $this->update($id, $data);
    }
    
    /**
     * Lấy thông tin sự kiện từ bản ghi check-in
     *
     * @param int $checkinId ID của bản ghi check-in
     * @return object|null
     */
    public function getSuKienFromCheckIn(int $checkinId)
    {
        $builder = $this->db->table("{$this->table} c");
        $builder->select("s.*, c.su_kien_id");
        $builder->join('su_kien s', "s.su_kien_id = c.su_kien_id", 'inner');
        $builder->where("c.{$this->primaryKey}", $checkinId);
        
        return $builder->get()->getRow();
    }
    
    /**
     * Lấy thông tin đăng ký sự kiện từ bản ghi check-in
     *
     * @param int $checkinId ID của bản ghi check-in
     * @return object|null
     */
    public function getDangKySuKienFromCheckIn(int $checkinId)
    {
        $builder = $this->db->table("{$this->table} c");
        $builder->select("d.*, c.dangky_sukien_id");
        $builder->join('dangky_sukien d', "d.dangky_sukien_id = c.dangky_sukien_id", 'inner');
        $builder->where("c.{$this->primaryKey}", $checkinId);
        
        return $builder->get()->getRow();
    }
} 