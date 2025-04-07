<?php

namespace App\Modules\dangkysukien\Models;

namespace App\Modules\quanlydangkysukien\Models;

use App\Models\BaseModel;
use App\Modules\quanlydangkysukien\Entities\DangKySuKien;
use App\Modules\quanlydangkysukien\Libraries\Pager;
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
        'su_kien_id',
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
        'nguoi_dung_id',
        'ma_sinh_vien',
        'so_dien_thoai',
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
        'ly_do_tham_du',
        'nguon_gioi_thieu',
        'ma_xac_nhan'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'su_kien_id',
        'loai_nguoi_dang_ky',
        'status',
        'hinh_thuc_tham_gia',
        'attendance_status',
        'diem_danh_bang',
        'face_verified',
        'da_check_in',
        'da_check_out'
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
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo loại người đăng ký
        if (isset($criteria['loai_nguoi_dang_ky']) && $criteria['loai_nguoi_dang_ky'] !== '') {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $criteria['loai_nguoi_dang_ky']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý lọc theo trạng thái điểm danh
        if (isset($criteria['attendance_status']) && $criteria['attendance_status'] !== '') {
            $builder->where($this->table . '.attendance_status', $criteria['attendance_status']);
        }
        
        // Xử lý lọc theo phương thức điểm danh
        if (isset($criteria['diem_danh_bang']) && $criteria['diem_danh_bang'] !== '') {
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

        // Xử lý lọc theo thời gian
        if (!empty($criteria['start_date'])) {
            $builder->where($this->table . '.created_at >=', $criteria['start_date']);
        }
        
        if (!empty($criteria['end_date'])) {
            $builder->where($this->table . '.created_at <=', $criteria['end_date']);
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
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo loại người đăng ký
        if (isset($criteria['loai_nguoi_dang_ky']) && $criteria['loai_nguoi_dang_ky'] !== '') {
            $builder->where($this->table . '.loai_nguoi_dang_ky', $criteria['loai_nguoi_dang_ky']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý lọc theo trạng thái điểm danh
        if (isset($criteria['attendance_status']) && $criteria['attendance_status'] !== '') {
            $builder->where($this->table . '.attendance_status', $criteria['attendance_status']);
        }
        
        // Xử lý lọc theo phương thức điểm danh
        if (isset($criteria['diem_danh_bang']) && $criteria['diem_danh_bang'] !== '') {
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
        
        // Xử lý lọc theo thời gian
        if (!empty($criteria['start_date'])) {
            $builder->where($this->table . '.created_at >=', $criteria['start_date']);
        }
        
        if (!empty($criteria['end_date'])) {
            $builder->where($this->table . '.created_at <=', $criteria['end_date']);
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
        $this->validationRules = [
            'su_kien_id' => [
                'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
                'label' => 'ID sự kiện'
            ],
            'email' => [
                'rules' => 'required|valid_email|max_length[100]',
                'label' => 'Email'
            ],
            'ho_ten' => [
                'rules' => 'required|min_length[2]|max_length[100]',
                'label' => 'Họ tên'
            ],
            'dien_thoai' => [
                'rules' => 'permit_empty|min_length[10]|max_length[20]',
                'label' => 'Điện thoại'
            ],
            'loai_nguoi_dang_ky' => [
                'rules' => 'required|in_list[khach,sinh_vien,giang_vien]',
                'label' => 'Loại người đăng ký'
            ],
            'status' => [
                'rules' => 'required|in_list[-1,0,1]',
                'label' => 'Trạng thái'
            ],
            'hinh_thuc_tham_gia' => [
                'rules' => 'required|in_list[offline,online,hybrid]',
                'label' => 'Hình thức tham gia'
            ],
            'attendance_status' => [
                'rules' => 'permit_empty|in_list[not_attended,partial,full]',
                'label' => 'Trạng thái tham dự'
            ],
            'attendance_minutes' => [
                'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
                'label' => 'Số phút tham dự'
            ],
            'diem_danh_bang' => [
                'rules' => 'permit_empty|in_list[none,qr_code,face_id,manual]',
                'label' => 'Phương thức điểm danh'
            ],
            'don_vi_to_chuc' => [
                'rules' => 'permit_empty|max_length[100]',
                'label' => 'Đơn vị tổ chức'
            ],
            'nguon_gioi_thieu' => [
                'rules' => 'permit_empty|max_length[100]',
                'label' => 'Nguồn giới thiệu'
            ],
            'ly_do_tham_du' => [
                'rules' => 'permit_empty|max_length[500]',
                'label' => 'Lý do tham dự'
            ],
            'noi_dung_gop_y' => [
                'rules' => 'permit_empty|max_length[1000]',
                'label' => 'Nội dung góp ý'
            ],
            'face_verified' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'label' => 'Xác thực khuôn mặt'
            ],
            'da_check_in' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'label' => 'Trạng thái check-in'
            ],
            'da_check_out' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'label' => 'Trạng thái check-out'
            ]
        ];

        $this->validationMessages = [
            'su_kien_id' => [
                'required' => 'Vui lòng chọn sự kiện',
                'integer' => 'ID sự kiện không hợp lệ',
                'is_not_unique' => 'Sự kiện không tồn tại'
            ],
            'email' => [
                'required' => 'Vui lòng nhập email',
                'valid_email' => 'Email không hợp lệ',
                'max_length' => 'Email không được vượt quá 100 ký tự'
            ],
            'ho_ten' => [
                'required' => 'Vui lòng nhập họ tên',
                'min_length' => 'Họ tên phải có ít nhất 2 ký tự',
                'max_length' => 'Họ tên không được vượt quá 100 ký tự'
            ],
            'dien_thoai' => [
                'min_length' => 'Số điện thoại phải có ít nhất 10 ký tự',
                'max_length' => 'Số điện thoại không được vượt quá 20 ký tự'
            ],
            'loai_nguoi_dang_ky' => [
                'required' => 'Vui lòng chọn loại người đăng ký',
                'in_list' => 'Loại người đăng ký không hợp lệ'
            ],
            'status' => [
                'required' => 'Vui lòng chọn trạng thái',
                'in_list' => 'Trạng thái không hợp lệ'
            ],
            'hinh_thuc_tham_gia' => [
                'required' => 'Vui lòng chọn hình thức tham gia',
                'in_list' => 'Hình thức tham gia không hợp lệ'
            ],
            'attendance_status' => [
                'in_list' => 'Trạng thái tham dự không hợp lệ'
            ],
            'attendance_minutes' => [
                'integer' => 'Số phút tham dự phải là số nguyên',
                'greater_than_equal_to' => 'Số phút tham dự không được âm'
            ],
            'diem_danh_bang' => [
                'in_list' => 'Phương thức điểm danh không hợp lệ'
            ],
            'don_vi_to_chuc' => [
                'max_length' => 'Đơn vị tổ chức không được vượt quá 100 ký tự'
            ],
            'nguon_gioi_thieu' => [
                'max_length' => 'Nguồn giới thiệu không được vượt quá 100 ký tự'
            ],
            'ly_do_tham_du' => [
                'max_length' => 'Lý do tham dự không được vượt quá 500 ký tự'
            ],
            'noi_dung_gop_y' => [
                'max_length' => 'Nội dung góp ý không được vượt quá 1000 ký tự'
            ],
            'face_verified' => [
                'in_list' => 'Trạng thái xác thực khuôn mặt không hợp lệ'
            ],
            'da_check_in' => [
                'in_list' => 'Trạng thái check-in không hợp lệ'
            ],
            'da_check_out' => [
                'in_list' => 'Trạng thái check-out không hợp lệ'
            ]
        ];
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        // Nếu là cập nhật, thêm kiểm tra xem email đã tồn tại chưa (trừ bản ghi hiện tại)
        if ($scenario === 'update' && isset($data[$this->primaryKey])) {
            // Khi cập nhật, cần loại trừ chính bản ghi đang cập nhật khỏi kiểm tra độc nhất
            // Format: is_unique[table.field,ignore_field,ignore_value]
            $this->validationRules['email']['rules'] = sprintf(
                'required|valid_email|is_unique[%s.email,%s,%s,su_kien_id,%s]',
                $this->table,
                $this->primaryKey,
                $data[$this->primaryKey],
                $data['su_kien_id'] ?? 0
            );
        } else {
            // Khi thêm mới, kiểm tra email không trùng cho cùng sự kiện
            $this->validationRules['email']['rules'] = sprintf(
                'required|valid_email|is_unique[%s.email,su_kien_id,%s]',
                $this->table,
                $data['su_kien_id'] ?? 0
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
        $builder->where($this->table . '.su_kien_id', $suKienId);
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
        $builder->where($this->table . '.su_kien_id', $suKienId);
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
                   ->where('su_kien_id', $suKienId)
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
        
        // Lấy thông tin đăng ký hiện tại để biết sự kiện ID
        $registration = $this->find($id);
        if (!$registration) {
            return false;
        }
        $suKienId = $registration->su_kien_id;
        
        if ($status == 1) {
            $data['thoi_gian_duyet'] = Time::now()->toDateTimeString();
        } elseif ($status == -1) {
            $data['thoi_gian_huy'] = Time::now()->toDateTimeString();
            if ($lyDo !== null) {
                $data['ly_do_huy'] = $lyDo;
            }
        }
        
        $data['updated_at'] = Time::now()->toDateTimeString();
        
        $result = $this->update($id, $data);
        
        // Cập nhật thống kê sự kiện
        if ($result) {
            $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
            $suKienModel->updateEventStats($suKienId);
        }
        
        return $result;
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
        // Lấy thông tin đăng ký hiện tại
        $registration = $this->find($id);
        if (!$registration) {
            return false;
        }
        
        $suKienId = $registration->su_kien_id;
        
        $data = [
            'da_check_in' => $value,
            'diem_danh_bang' => $diemDanhBang,
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        if ($checkinSuKienId !== null) {
            $data['checkin_sukien_id'] = $checkinSuKienId;
        }
        
        // Nếu người dùng check-in
        if ($value) {
            // Lưu thời gian check-in
            $data['checkin_time'] = Time::now()->toDateTimeString();
            
            // Cập nhật trạng thái tham dự nếu chưa tham dự
            if ($registration->getAttendanceStatus() === 'not_attended') {
                $data['attendance_status'] = 'partial';
            }
            
            // Cập nhật status nếu đang trong trạng thái chờ xác nhận
            if ($registration->getStatus() === 0) {
                $data['status'] = 1; // Đã xác nhận
            }
        } else {
            // Nếu bỏ check-in thì cũng bỏ check-out 
            if ($registration->isDaCheckOut()) {
                $data['da_check_out'] = false;
            }
            
            // Nếu bỏ check-in và attendance_status đang là partial, trả về not_attended
            if ($registration->getAttendanceStatus() === 'partial' && !$registration->isDaCheckOut()) {
                $data['attendance_status'] = 'not_attended';
            }
        }
        
        $result = $this->update($id, $data);
        
        // Cập nhật thống kê sự kiện
        if ($result) {
            $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
            $suKienModel->updateEventStats($suKienId);
        }
        
        return $result;
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
        // Lấy thông tin đăng ký hiện tại
        $registration = $this->find($id);
        if (!$registration) {
            return false;
        }
        
        $suKienId = $registration->su_kien_id;
        
        // Không cho phép check-out nếu chưa check-in
        if ($value && !$registration->isDaCheckIn()) {
            return false;
        }
        
        $data = [
            'da_check_out' => $value,
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        if ($checkoutSuKienId !== null) {
            $data['checkout_sukien_id'] = $checkoutSuKienId;
        }
        
        // Nếu người dùng check-out
        if ($value) {
            // Lưu thời gian check-out
            $data['checkout_time'] = Time::now()->toDateTimeString();
            
            // Tính thời gian tham dự nếu không được cung cấp
            if ($attendanceMinutes <= 0 && !empty($registration->checkin_time)) {
                $checkInTime = new Time($registration->checkin_time);
                $checkOutTime = Time::now();
                $diffMinutes = $checkOutTime->difference($checkInTime)->getMinutes();
                $attendanceMinutes = max(0, $diffMinutes);
            }
            
            // Cập nhật số phút tham dự
            if ($attendanceMinutes > 0) {
                $data['attendance_minutes'] = $attendanceMinutes;
                
                // Xác định trạng thái tham dự dựa vào thời gian và quy định của sự kiện
                // Có thể cấu hình ngưỡng phút tham dự tối thiểu cho mỗi sự kiện
                $suKienId = $registration->getSuKienId();
                $thresholdMinutes = $this->getAttendanceThreshold($suKienId);
                
                $data['attendance_status'] = ($attendanceMinutes >= $thresholdMinutes) ? 'full' : 'partial';
            }
        } else {
            // Nếu bỏ check-out, giữ nguyên attendance_status nếu đã là partial
            if ($registration->getAttendanceStatus() === 'full') {
                $data['attendance_status'] = 'partial';
            }
            
            // Xóa thời gian check-out
            $data['checkout_time'] = null;
        }
        
        $result = $this->update($id, $data);
        
        // Cập nhật thống kê sự kiện
        if ($result) {
            $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
            $suKienModel->updateEventStats($suKienId);
        }
        
        return $result;
    }
    
    /**
     * Lấy ngưỡng phút tham dự tối thiểu cho sự kiện
     * 
     * @param int $suKienId ID của sự kiện
     * @return int Số phút tối thiểu để tính là tham dự đầy đủ
     */
    protected function getAttendanceThreshold(int $suKienId): int
    {
        // Mặc định: 90 phút
        $defaultThreshold = 90;
        
        // TODO: Có thể lấy từ cấu hình sự kiện từ bảng su_kien
        try {
            $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
            $suKien = $suKienModel->find($suKienId);
            
            if ($suKien && !empty($suKien->thoi_luong_dk_full)) {
                return (int)$suKien->thoi_luong_dk_full;
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi lấy thời lượng tham dự tối thiểu: ' . $e->getMessage());
        }
        
        return $defaultThreshold;
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
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
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
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
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
        if (isset($params['su_kien_id']) && $params['su_kien_id'] !== '') {
            $criteria['su_kien_id'] = (int)$params['su_kien_id'];
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

        // Xử lý lọc theo thời gian
        if (!empty($params['start_date'])) {
            $criteria['start_date'] = $params['start_date'];
        }
        
        if (!empty($params['end_date'])) {
            $criteria['end_date'] = $params['end_date'];
        }
        
        return $criteria;
    }
    
    /**
     * Kiểm tra email đã tồn tại trong sự kiện chưa (loại trừ ID đang cập nhật)
     *
     * @param string $email Email cần kiểm tra
     * @param int $suKienId ID sự kiện
     * @param int|null $excludeId ID bản ghi cần loại trừ khỏi việc kiểm tra (khi cập nhật)
     * @return bool True nếu email là duy nhất, False nếu đã tồn tại
     */
    public function isUniqueEmail(string $email, int $suKienId, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('email', $email);
        $builder->where('su_kien_id', $suKienId);
        
        if ($excludeId !== null) {
            $builder->where("{$this->primaryKey} !=", $excludeId);
        }
        
        return $builder->countAllResults() === 0;
    }
    
    /**
     * Cập nhật trạng thái tham dự dựa trên thông tin check-in/check-out
     *
     * @param int $id ID của đăng ký
     * @return bool True nếu cập nhật thành công
     */
    public function updateAttendanceStatus(int $id): bool
    {
        // Lấy thông tin đăng ký hiện tại
        $registration = $this->find($id);
        if (!$registration) {
            return false;
        }
        
        $data = [
            'updated_at' => Time::now()->toDateTimeString()
        ];
        
        // Đã check-in và check-out
        if ($registration->isDaCheckIn() && $registration->isDaCheckOut()) {
            $attendanceMinutes = $registration->getAttendanceMinutes();
            if ($attendanceMinutes > 0) {
                $suKienId = $registration->getSuKienId();
                $thresholdMinutes = $this->getAttendanceThreshold($suKienId);
                
                $data['attendance_status'] = ($attendanceMinutes >= $thresholdMinutes) ? 'full' : 'partial';
            } else {
                $data['attendance_status'] = 'partial';
            }
        }
        // Chỉ check-in, chưa check-out
        else if ($registration->isDaCheckIn()) {
            $data['attendance_status'] = 'partial';
        }
        // Chưa check-in
        else {
            $data['attendance_status'] = 'not_attended';
        }
        
        return $this->update($id, $data);
    }
    
    /**
     * Lấy danh sách loại người dùng cho filter
     *
     * @return array
     */
    public function getLoaiNguoiDungOptions(): array
    {
        return [
            '' => 'Tất cả loại người dùng',
            'khach' => 'Khách mời',
            'sinh_vien' => 'Sinh viên', 
            'giang_vien' => 'Giảng viên'
        ];
    }
    
    /**
     * Xử lý upload ảnh khuôn mặt
     *
     * @param array $file File upload từ form
     * @return string|null Đường dẫn ảnh nếu upload thành công, null nếu thất bại
     */
    public function uploadFaceImage($file)
    {
        if (empty($file) || !isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return null;
        }

        // Thư mục lưu ảnh
        $uploadPath = WRITEPATH . 'uploads/faces';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Tạo tên file ngẫu nhiên
        $newName = uniqid('face_') . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        
        // Di chuyển file upload
        if (move_uploaded_file($file['tmp_name'], $uploadPath . '/' . $newName)) {
            return 'uploads/faces/' . $newName;
        }

        return null;
    }

    /**
     * Xóa ảnh khuôn mặt cũ
     *
     * @param string $path Đường dẫn ảnh cần xóa
     * @return bool
     */
    public function deleteFaceImage($path)
    {
        if (empty($path)) {
            return false;
        }

        $fullPath = WRITEPATH . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Override phương thức insert để xử lý upload ảnh và cập nhật thống kê
     */
    public function insert($data = null, bool $returnID = true)
    {
        // Xử lý upload ảnh nếu có
        if (isset($_FILES['face_image'])) {
            $imagePath = $this->uploadFaceImage($_FILES['face_image']);
            if ($imagePath) {
                $data['face_image_path'] = $imagePath;
            }
        }
        
        $result = parent::insert($data, $returnID);
        
        // Cập nhật thống kê sự kiện nếu có trường su_kien_id trong dữ liệu
        if ($result && isset($data['su_kien_id'])) {
            $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
            $suKienModel->updateEventStats($data['su_kien_id']);
        }
        
        return $result;
    }

    /**
     * Override phương thức update để xử lý upload ảnh và cập nhật thống kê
     */
    public function update($id = null, $data = null): bool
    {
        // Xử lý upload ảnh nếu có
        if (isset($_FILES['face_image']) && !empty($_FILES['face_image']['tmp_name'])) {
            // Lấy thông tin bản ghi cũ
            $oldData = $this->find($id);
            
            // Upload ảnh mới
            $imagePath = $this->uploadFaceImage($_FILES['face_image']);
            if ($imagePath) {
                // Xóa ảnh cũ nếu có
                if ($oldData && !empty($oldData->getFaceImagePath())) {
                    $this->deleteFaceImage($oldData->getFaceImagePath());
                }
                
                $data['face_image_path'] = $imagePath;
            }
        }
        
        $result = parent::update($id, $data);
        
        // Nếu cập nhật thành công và không được gọi từ phương thức khác đã cập nhật thống kê
        if ($result && $id !== null && !is_array($id)) {
            // Lấy đối tượng đăng ký sau khi cập nhật
            $registration = $this->find($id);
            if ($registration && !empty($registration->su_kien_id)) {
                // Kiểm tra xem đã có backtrace của updateEventStats chưa để tránh đệ quy vô hạn
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
                $skipUpdate = false;
                
                foreach ($backtrace as $trace) {
                    if (isset($trace['function']) && $trace['function'] === 'updateEventStats') {
                        $skipUpdate = true;
                        break;
                    }
                }
                
                if (!$skipUpdate) {
                    $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
                    $suKienModel->updateEventStats($registration->su_kien_id);
                }
            }
        }
        
        return $result;
    }

    /**
     * Lấy danh sách đăng ký sự kiện theo email người dùng
     *
     * @param string $email Email người dùng
     * @param array $options Các tùy chọn bổ sung (limit, offset, where, order, join_event_info)
     * @return array Danh sách đăng ký sự kiện
     */
    public function getRegistrationsByEmail(string $email, array $options = [])
    {
        $builder = $this->builder();
        
        // Thêm điều kiện email
        $builder->where('dangky_sukien.email', $email);
        
        // Thêm các điều kiện bổ sung nếu có
        if (isset($options['where']) && is_array($options['where'])) {
            foreach ($options['where'] as $field => $value) {
                $builder->where("dangky_sukien.{$field}", $value);
            }
        }
        
        // Join với bảng sự kiện nếu cần
        if (isset($options['join_event_info']) && $options['join_event_info'] === true) {
            $builder->select('dangky_sukien.*, su_kien.*')
                    ->join('su_kien', 'su_kien.su_kien_id = dangky_sukien.su_kien_id', 'left');
        }
        
        // Thêm sắp xếp
        if (isset($options['order']) && is_array($options['order'])) {
            foreach ($options['order'] as $field => $direction) {
                $builder->orderBy($field, $direction);
            }
        } else {
            $builder->orderBy('dangky_sukien.created_at', 'DESC');
        }
        
        // Thêm giới hạn và phân trang
        if (isset($options['limit']) && is_numeric($options['limit'])) {
            $offset = isset($options['offset']) && is_numeric($options['offset']) ? $options['offset'] : 0;
            $builder->limit($options['limit'], $offset);
        }
        
        return $builder->get()->getResult();
    }

    /**
     * Đếm số lượng đăng ký sự kiện theo email người dùng
     *
     * @param string $email Email người dùng
     * @param array $options Các tùy chọn bổ sung (where)
     * @return int Số lượng đăng ký sự kiện
     */
    public function countRegistrationsByEmail(string $email, array $options = [])
    {
        $builder = $this->builder();
        
        // Thêm điều kiện email
        $builder->where('dangky_sukien.email', $email);
        
        // Thêm các điều kiện bổ sung nếu có
        if (isset($options['where']) && is_array($options['where'])) {
            foreach ($options['where'] as $field => $value) {
                $builder->where("dangky_sukien.{$field}", $value);
            }
        }
        
        return $builder->countAllResults();
    }
    
    public function huyDangKySuKien($su_kien_id, $email)
    {
        $builder = $this->builder();
        $builder->where('su_kien_id', $su_kien_id);
        $builder->where('email', $email);
        return $builder->delete();
    }
} 