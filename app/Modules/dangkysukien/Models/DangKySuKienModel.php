<?php

namespace App\Modules\dangkysukien\Models;

use App\Models\BaseModel;
use App\Modules\dangkysukien\Entities\DangKySuKien;
use App\Modules\dangkysukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

class DangKySuKienModel extends BaseModel
{
    protected $table = 'dangky_sukien';
    protected $primaryKey = 'dangky_sukien_id';
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
        'dien_thoai',
        'loai_nguoi_dang_ky',
        'ngay_dang_ky',
        'ma_xac_nhan',
        'status',
        'noi_dung_gop_y',
        'nguon_gioi_thieu',
        'don_vi_to_chuc',
        'face_image_path',
        'face_verified',
        'da_check_in',
        'da_check_out',
        'checkin_sukien_id',
        'checkout_sukien_id',
        'thoi_gian_duyet',
        'thoi_gian_huy',
        'ly_do_huy',
        'hinh_thuc_tham_gia',
        'attendance_status',
        'attendance_minutes',
        'diem_danh_bang',
        'thong_tin_dang_ky',
        'ly_do_tham_du',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = DangKySuKien::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'email',
        'ho_ten',
        'dien_thoai',
        'don_vi_to_chuc',
        'noi_dung_gop_y',
        'ly_do_tham_du'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'sukien_id',
        'loai_nguoi_dang_ky',
        'status',
        'da_check_in',
        'da_check_out',
        'hinh_thuc_tham_gia',
        'attendance_status',
        'diem_danh_bang'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi đăng ký sự kiện
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
     * Đếm tổng số bản ghi đăng ký
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
     * Tìm kiếm đăng ký dựa vào các tiêu chí
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
        if (isset($criteria['sukien_id'])) {
            $builder->where($this->table . '.sukien_id', $criteria['sukien_id']);
        }
        
        // Xử lý lọc theo loại người đăng ký
        if (isset($criteria['loai_nguoi_dang_ky'])) {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $criteria['loai_nguoi_dang_ky']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Xử lý lọc theo trạng thái check-in
        if (isset($criteria['da_check_in'])) {
            $builder->where($this->table . '.da_check_in', $criteria['da_check_in']);
        }
        
        // Xử lý lọc theo trạng thái check-out
        if (isset($criteria['da_check_out'])) {
            $builder->where($this->table . '.da_check_out', $criteria['da_check_out']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia'])) {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý lọc theo trạng thái tham dự
        if (isset($criteria['attendance_status'])) {
            $builder->where($this->table . '.attendance_status', $criteria['attendance_status']);
        }
        
        // Xử lý lọc theo phương thức điểm danh
        if (isset($criteria['diem_danh_bang'])) {
            $builder->where($this->table . '.diem_danh_bang', $criteria['diem_danh_bang']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
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
        $sort = $options['sort'] ?? 'created_at';
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
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['sukien_id'])) {
            $builder->where($this->table . '.sukien_id', $criteria['sukien_id']);
        }
        
        // Xử lý lọc theo loại người đăng ký
        if (isset($criteria['loai_nguoi_dang_ky'])) {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $criteria['loai_nguoi_dang_ky']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Xử lý lọc theo trạng thái check-in
        if (isset($criteria['da_check_in'])) {
            $builder->where($this->table . '.da_check_in', $criteria['da_check_in']);
        }
        
        // Xử lý lọc theo trạng thái check-out
        if (isset($criteria['da_check_out'])) {
            $builder->where($this->table . '.da_check_out', $criteria['da_check_out']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia'])) {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý lọc theo trạng thái tham dự
        if (isset($criteria['attendance_status'])) {
            $builder->where($this->table . '.attendance_status', $criteria['attendance_status']);
        }
        
        // Xử lý lọc theo phương thức điểm danh
        if (isset($criteria['diem_danh_bang'])) {
            $builder->where($this->table . '.diem_danh_bang', $criteria['diem_danh_bang']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
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
        $entity = new DangKySuKien();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        // Nếu là cập nhật, thêm kiểm tra xem email đã tồn tại chưa (trừ bản ghi hiện tại)
        if ($scenario === 'update' && isset($data['dangky_sukien_id'])) {
            $this->validationRules['email']['rules'] = sprintf(
                'required|valid_email|is_unique[%s.email,sukien_id,%s,dangky_sukien_id,%s]',
                $this->table,
                $data['sukien_id'] ?? 0,
                $data['dangky_sukien_id']
            );
        } else {
            // Khi thêm mới, kiểm tra email không trùng cho cùng sự kiện
            $this->validationRules['email']['rules'] = sprintf(
                'required|valid_email|is_unique[%s.email,sukien_id,%s]',
                $this->table,
                $data['sukien_id'] ?? 0
            );
        }
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
     * Lấy danh sách đăng ký theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @param array $options Các tùy chọn bổ sung
     * @return array
     */
    public function getRegistrationsByEvent(int $suKienId, array $options = [])
    {
        $builder = $this->builder();
        $builder->where($this->table . '.sukien_id', $suKienId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Lọc theo trạng thái
        if (isset($options['status'])) {
            $builder->where($this->table . '.status', $options['status']);
        }
        
        // Lọc theo loại người đăng ký
        if (isset($options['loai_nguoi_dang_ky'])) {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $options['loai_nguoi_dang_ky']);
        }
        
        // Lọc theo trạng thái check-in
        if (isset($options['da_check_in'])) {
            $builder->where($this->table . '.da_check_in', $options['da_check_in']);
        }
        
        // Lọc theo hình thức tham gia
        if (isset($options['hinh_thuc_tham_gia'])) {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $options['hinh_thuc_tham_gia']);
        }
        
        // Sắp xếp
        $sort = $options['sort'] ?? 'created_at';
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
     * Lấy số lượng đăng ký theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @param array $options Các tùy chọn lọc
     * @return int
     */
    public function countRegistrationsByEvent(int $suKienId, array $options = [])
    {
        $builder = $this->builder();
        $builder->where($this->table . '.sukien_id', $suKienId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Lọc theo trạng thái
        if (isset($options['status'])) {
            $builder->where($this->table . '.status', $options['status']);
        }
        
        // Lọc theo loại người đăng ký
        if (isset($options['loai_nguoi_dang_ky'])) {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $options['loai_nguoi_dang_ky']);
        }
        
        // Lọc theo trạng thái check-in
        if (isset($options['da_check_in'])) {
            $builder->where($this->table . '.da_check_in', $options['da_check_in']);
        }
        
        // Lọc theo hình thức tham gia
        if (isset($options['hinh_thuc_tham_gia'])) {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $options['hinh_thuc_tham_gia']);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm đăng ký sự kiện theo email và ID sự kiện
     *
     * @param string $email Email người đăng ký
     * @param int $suKienId ID của sự kiện
     * @return DangKySuKien|null
     */
    public function findByEmailAndEvent(string $email, int $suKienId)
    {
        return $this->where('email', $email)
                   ->where('sukien_id', $suKienId)
                   ->first();
    }
    
    /**
     * Cập nhật trạng thái đăng ký
     *
     * @param int $id ID của đăng ký
     * @param int $status Trạng thái mới (1: xác nhận, 0: chờ xác nhận, -1: hủy)
     * @param string|null $lyDo Lý do hủy (nếu status = -1)
     * @return bool
     */
    public function updateStatus(int $id, int $status, ?string $lyDo = null): bool
    {
        $data = ['status' => $status];
        
        if ($status == 1) {
            $data['thoi_gian_duyet'] = Time::now()->toDateTimeString();
        } elseif ($status == -1) {
            $data['thoi_gian_huy'] = Time::now()->toDateTimeString();
            if ($lyDo !== null) {
                $data['ly_do_huy'] = $lyDo;
            }
        }
        
        $data['updated_at'] = Time::now()->toDateTimeString();
        
        return $this->update($id, $data);
    }
    
    /**
     * Cập nhật thông tin check-in
     *
     * @param int $id ID của đăng ký
     * @param bool $value Giá trị check-in
     * @param int|null $checkinSuKienId ID của bản ghi check-in (nếu có)
     * @param string $diemDanhBang Phương thức điểm danh
     * @return bool
     */
    public function updateCheckIn(int $id, bool $value, ?int $checkinSuKienId = null, string $diemDanhBang = 'manual'): bool
    {
        $data = [
            'da_check_in' => $value,
            'diem_danh_bang' => $diemDanhBang,
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        if ($checkinSuKienId !== null) {
            $data['checkin_sukien_id'] = $checkinSuKienId;
        }
        
        // Nếu là check-in mà trạng thái attendance_status là not_attended
        // thì cập nhật thành partial
        if ($value) {
            $registration = $this->find($id);
            if ($registration && $registration->getAttendanceStatus() === 'not_attended') {
                $data['attendance_status'] = 'partial';
            }
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Cập nhật thông tin check-out
     *
     * @param int $id ID của đăng ký
     * @param bool $value Giá trị check-out
     * @param int|null $checkoutSuKienId ID của bản ghi check-out (nếu có)
     * @param int $attendanceMinutes Số phút tham dự (nếu check-out)
     * @return bool
     */
    public function updateCheckOut(int $id, bool $value, ?int $checkoutSuKienId = null, int $attendanceMinutes = 0): bool
    {
        $data = [
            'da_check_out' => $value,
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        if ($checkoutSuKienId !== null) {
            $data['checkout_sukien_id'] = $checkoutSuKienId;
        }
        
        if ($value && $attendanceMinutes > 0) {
            $data['attendance_minutes'] = $attendanceMinutes;
            
            // Xác định trạng thái tham dự dựa vào thời gian
            $data['attendance_status'] = ($attendanceMinutes >= 90) ? 'full' : 'partial';
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Tìm đăng ký bằng mã xác nhận
     *
     * @param string $maXacNhan Mã xác nhận
     * @return DangKySuKien|null
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
        if (isset($criteria['sukien_id'])) {
            $builder->where($this->table . '.sukien_id', $criteria['sukien_id']);
        }
        
        // Xử lý lọc theo loại người đăng ký
        if (isset($criteria['loai_nguoi_dang_ky'])) {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $criteria['loai_nguoi_dang_ky']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
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
        if (isset($criteria['sukien_id'])) {
            $builder->where($this->table . '.sukien_id', $criteria['sukien_id']);
        }
        
        // Xử lý lọc theo loại người đăng ký
        if (isset($criteria['loai_nguoi_dang_ky'])) {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $criteria['loai_nguoi_dang_ky']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
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
     * Xóa nhiều bản ghi và tính toán lại vị trí trang
     *
     * @param array $ids Mảng ID cần xóa
     * @param array $currentParams Các tham số hiện tại (page, perPage, filters)
     * @return array Kết quả xóa và thông tin trang mới
     */
    public function deleteMultiple(array $ids, array $currentParams = []): array
    {
        $successCount = 0;
        
        // Xóa các bản ghi
        foreach ($ids as $id) {
            if ($this->delete($id)) {
                $successCount++;
            }
        }
        
        // Tính toán lại vị trí trang
        $currentPage = $currentParams['page'] ?? 1;
        $perPage = $currentParams['perPage'] ?? 10;
        
        // Lấy tổng số bản ghi còn lại với các điều kiện lọc
        $criteria = $this->buildSearchCriteria($currentParams);
        $totalItems = $this->countSearchResults($criteria);
        
        // Tính tổng số trang mới
        $totalPages = ceil($totalItems / $perPage);
        
        // Nếu trang hiện tại lớn hơn tổng số trang mới và có trang
        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
        }
        
        return [
            'success' => $successCount > 0,
            'success_count' => $successCount,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage
        ];
    }
    
    /**
     * Khôi phục nhiều bản ghi và tính toán lại vị trí trang
     *
     * @param array $ids Mảng ID cần khôi phục
     * @param array $currentParams Các tham số hiện tại (page, perPage, filters)
     * @return array Kết quả khôi phục và thông tin trang mới
     */
    public function restoreMultiple(array $ids, array $currentParams = []): array
    {
        $successCount = 0;
        
        // Khôi phục các bản ghi
        foreach ($ids as $id) {
            if ($this->restore($id)) {
                $successCount++;
            }
        }
        
        // Tính toán lại vị trí trang
        $currentPage = $currentParams['page'] ?? 1;
        $perPage = $currentParams['perPage'] ?? 10;
        
        // Lấy tổng số bản ghi còn lại với các điều kiện lọc
        $criteria = $this->buildSearchCriteria($currentParams);
        $totalItems = $this->countSearchResults($criteria);
        
        // Tính tổng số trang mới
        $totalPages = ceil($totalItems / $perPage);
        
        // Nếu trang hiện tại lớn hơn tổng số trang mới và có trang
        if ($currentPage > $totalPages && $totalPages > 0) {
            $currentPage = $totalPages;
        }
        
        return [
            'success' => $successCount > 0,
            'success_count' => $successCount,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage
        ];
    }
    
    /**
     * Xây dựng tiêu chí tìm kiếm từ các tham số
     *
     * @param array $params Các tham số tìm kiếm
     * @return array Tiêu chí tìm kiếm
     */
    protected function buildSearchCriteria(array $params): array
    {
        $criteria = [];
        
        // Xử lý từ khóa tìm kiếm
        if (!empty($params['keyword'])) {
            $criteria['keyword'] = $params['keyword'];
        }
        
        // Xử lý lọc theo sự kiện
        if (isset($params['sukien_id']) && $params['sukien_id'] !== '') {
            $criteria['sukien_id'] = (int)$params['sukien_id'];
        }
        
        // Xử lý lọc theo loại người đăng ký
        if (isset($params['loai_nguoi_dang_ky']) && $params['loai_nguoi_dang_ky'] !== '') {
            $criteria['loai_nguoi_dang_ky'] = $params['loai_nguoi_dang_ky'];
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = $params['status'];
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($params['hinh_thuc_tham_gia']) && $params['hinh_thuc_tham_gia'] !== '') {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'];
        }
        
        // Xử lý lọc theo trạng thái điểm danh
        if (isset($params['attendance_status']) && $params['attendance_status'] !== '') {
            $criteria['attendance_status'] = $params['attendance_status'];
        }
        
        // Xử lý lọc theo phương thức điểm danh
        if (isset($params['diem_danh_bang']) && $params['diem_danh_bang'] !== '') {
            $criteria['diem_danh_bang'] = $params['diem_danh_bang'];
        }
        
        // Xử lý lọc theo trạng thái check-in
        if (isset($params['da_check_in']) && $params['da_check_in'] !== '') {
            $criteria['da_check_in'] = $params['da_check_in'];
        }
        
        // Xử lý lọc theo trạng thái check-out
        if (isset($params['da_check_out']) && $params['da_check_out'] !== '') {
            $criteria['da_check_out'] = $params['da_check_out'];
        }
        
        return $criteria;
    }
} 