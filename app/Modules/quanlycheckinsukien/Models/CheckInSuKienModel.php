<?php

namespace App\Modules\quanlycheckinsukien\Models;


use App\Models\BaseModel;
use App\Modules\quanlycheckinsukien\Entities\CheckInSuKien;
use App\Modules\quanlycheckinsukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

class CheckInSuKienModel extends BaseModel
{
    protected $module_name = 'quanlycheckinsukien';

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
        ]
    ];
    
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
        $builder->select("{$this->table}.*, sk.ten_su_kien");
        $builder->join('su_kien AS sk', "sk.su_kien_id = {$this->table}.su_kien_id", 'left');
        
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
        
         // Lọc theo thời gian check-out từ ngày
         if (!empty($criteria['start_date'])) {
            $tuNgay = $criteria['start_date'] . ' 00:00:00';
            $builder->where($this->table . '.thoi_gian_check_in >=', $tuNgay);
        }
        
        // Lọc theo thời gian check-out đến ngày
        if (!empty($criteria['end_date'])) {
            $denNgay = $criteria['end_date'] . ' 23:59:59';
            $builder->where($this->table . '.thoi_gian_check_in <=', $denNgay);
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
            $builder->orLike("sk.ten_su_kien", $keyword);
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
        $validSortFields = ['thoi_gian_check_in', 'created_at', 'updated_at', 'deleted_at', 'su_kien_id', 'email', 'ho_ten', 'status'];
        $sort = in_array($sort, $validSortFields) ? $sort : 'deleted_at';
        
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập pager nếu cần
        if ($limit > 0) {
            $totalRows = $this->countSearchResults($criteria);
            $pagerClass = "\App\Modules\\" . $this->module_name . '\Libraries\Pager';
            $this->pager = new $pagerClass(
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
            $builder->orLike("sk.ten_su_kien", $keyword);
            $builder->groupEnd();
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên kịch bản
     * 
     * @param string $scenario Kịch bản (insert hoặc update)
     * @param array $data Dữ liệu cần xác thực
     * @return array Rules xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        // Khởi tạo đối tượng thực thể và lấy quy tắc xác thực
        $entity = new \App\Modules\quanlycheckinsukien\Entities\CheckInSuKien();
        $rules = $entity->getValidationRules();
        $messages = $entity->getValidationMessages();
        
        // Các trường cần bỏ qua khi cập nhật
        $skipFieldsOnUpdate = [
            'created_at', 'updated_at', 'deleted_at'
        ];
        
        // Các trường cho phép null
        $nullableFields = [
            'dangky_sukien_id', 'face_match_score', 
            'ma_xac_nhan', 'ghi_chu',
            'face_image_path', 'location_data', 
            'device_info', 'thong_tin_bo_sung'
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
                    'label' => 'ID',
                    'errors' => [
                        'required' => '{field} là bắt buộc',
                        'integer' => '{field} phải là số nguyên',
                        'is_not_unique' => '{field} không tồn tại trong hệ thống'
                    ]
                ];
            }
            
            if (isset($data['su_kien_id'])) {
                // Kiểm tra sự kiện tồn tại và không bị xóa
                $updatedRules['su_kien_id'] = [
                    'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
                    'label' => 'Sự kiện',
                    'errors' => [
                        'required' => '{field} là bắt buộc',
                        'integer' => '{field} phải là số nguyên',
                        'is_not_unique' => '{field} không tồn tại trong hệ thống hoặc đã bị xóa'
                    ]
                ];
            }
            
            // Cập nhật lại rules
            $rules = $updatedRules;
        } else {
            // Đối với insert, đảm bảo sự kiện tồn tại và không bị xóa
            $rules['su_kien_id'] = [
                'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
                'label' => 'Sự kiện',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'integer' => '{field} phải là số nguyên',
                    'is_not_unique' => '{field} không tồn tại trong hệ thống hoặc đã bị xóa'
                ]
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
        
        // Quy tắc đặc biệt cho đường dẫn ảnh
        if (isset($rules['face_image_path'])) {
            if (is_array($rules['face_image_path']) && isset($rules['face_image_path']['rules'])) {
                $rules['face_image_path']['rules'] = 'permit_empty|string|max_length[255]';
            } else {
                $rules['face_image_path'] = 'permit_empty|string|max_length[255]';
            }
        }
        
        // Kiểm tra ID đăng ký sự kiện
        if (isset($rules['dangky_sukien_id'])) {
            if (is_array($rules['dangky_sukien_id']) && isset($rules['dangky_sukien_id']['rules'])) {
                $rules['dangky_sukien_id']['rules'] = 'permit_empty|integer|is_not_unique[dangky_sukien.dangky_sukien_id]';
                $rules['dangky_sukien_id']['errors']['is_not_unique'] = 'ID đăng ký sự kiện không tồn tại trong hệ thống hoặc đã bị xóa';
            } else {
                $rules['dangky_sukien_id'] = 'permit_empty|integer|is_not_unique[dangky_sukien.dangky_sukien_id]';
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
        
        return $rules;
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

    /**
     * Cập nhật trạng thái tham gia sự kiện
     *
     * @param int $id ID của bản ghi check-in
     * @param int $status Trạng thái muốn cập nhật (0: Vô hiệu, 1: Hoạt động, 2: Đang xử lý)
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
     * Thêm mới dữ liệu
     * 
     * @param array $data Dữ liệu cần thêm
     * @return int ID của bản ghi mới
     * @throws \RuntimeException Nếu có lỗi xảy ra trong quá trình thêm dữ liệu
     */
    public function insertData(array $data)
    {
        // Nếu chưa có mã xác nhận, tạo mã xác nhận mới
        if (empty($data['ma_xac_nhan'])) {
            $data['ma_xac_nhan'] = $this->generateConfirmationCode();
        }
        
        // Xác thực dữ liệu
        $this->prepareValidationRules('insert', $data);
        if (!$this->validate($data)) {
            $errors = $this->errors();
            log_message('error', 'Lỗi xác thực dữ liệu: ' . json_encode($errors));
            throw new \RuntimeException('Dữ liệu không hợp lệ: ' . implode(', ', $errors));
        }
        
        // Thêm dữ liệu
        try {
            $this->insert($data);
            $id = $this->getInsertID();
            log_message('info', 'Đã thêm mới bản ghi với ID: ' . $id);
            return $id;
        } catch (\Exception $e) {
            log_message('error', '[ERROR] Lỗi khi thêm dữ liệu: {exception}', ['exception' => $e]);
            throw new \RuntimeException('Có lỗi xảy ra khi thêm mới dữ liệu: ' . $e->getMessage());
        }
    }
    
    /**
     * Cập nhật dữ liệu
     * 
     * @param int $id ID của bản ghi cần cập nhật
     * @param array $data Dữ liệu cần cập nhật
     * @return bool Kết quả cập nhật
     * @throws \RuntimeException Nếu có lỗi xảy ra trong quá trình cập nhật dữ liệu
     */
    public function updateData(int $id, array $data)
    {
        // Kiểm tra xem bản ghi tồn tại không
        $existingRecord = $this->find($id);
        if (!$existingRecord) {
            log_message('error', 'Không tìm thấy bản ghi với ID: ' . $id);
            throw new \RuntimeException('Không tìm thấy bản ghi cần cập nhật');
        }
        
        // Đảm bảo ID được đưa vào dữ liệu để xác thực
        $data[$this->primaryKey] = $id;
        
        // Xác thực dữ liệu
        $this->prepareValidationRules('update', $data);
        if (!$this->validate($data)) {
            $errors = $this->errors();
            log_message('error', 'Lỗi xác thực dữ liệu: ' . json_encode($errors));
            throw new \RuntimeException('Dữ liệu không hợp lệ: ' . implode(', ', $errors));
        }
        
        // Cập nhật dữ liệu
        try {
            $result = $this->update($id, $data);
            log_message('info', 'Đã cập nhật bản ghi với ID: ' . $id);
            return $result;
        } catch (\Exception $e) {
            log_message('error', '[ERROR] Lỗi khi cập nhật dữ liệu: {exception}', ['exception' => $e]);
            throw new \RuntimeException('Có lỗi xảy ra khi cập nhật dữ liệu: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa vĩnh viễn một bản ghi khỏi cơ sở dữ liệu
     * 
     * @param int $id ID của bản ghi cần xóa vĩnh viễn
     * @return bool Kết quả xóa
     */
    public function permanentDelete(int $id): bool
    {
        try {
            // Kiểm tra bản ghi có tồn tại không (kể cả đã xóa mềm)
            $record = $this->withDeleted()->find($id);
            if (!$record) {
                log_message('error', 'permanentDelete: Không tìm thấy bản ghi với ID: ' . $id);
                return false;
            }
            
            // Sử dụng builder để xóa vĩnh viễn bản ghi
            $builder = $this->builder();
            $builder->where($this->primaryKey, $id);
            $result = $builder->delete();
            
            if ($result) {
                log_message('info', 'Đã xóa vĩnh viễn bản ghi với ID: ' . $id);
                return true;
            } else {
                log_message('error', 'Không thể xóa vĩnh viễn bản ghi với ID: ' . $id);
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi xóa vĩnh viễn bản ghi với ID: ' . $id . ', lỗi: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tìm dữ liệu đã xóa mềm dựa trên các tiêu chí tìm kiếm
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        $sort = $options['sort'] ?? 'deleted_at';
        $order = $options['order'] ?? 'DESC';
        
        // Ghi log thông tin về criteria để debug
        log_message('debug', '[searchDeleted] Criteria: ' . json_encode($criteria));
        log_message('debug', '[searchDeleted] Options: ' . json_encode($options));
        
        // Khởi tạo builder trực tiếp từ database
        $this->builder = $this->db->table($this->table);
        $this->builder->select($this->table . '.*, sk.ten_su_kien');
        $this->builder->join('su_kien AS sk', 'sk.su_kien_id = ' . $this->table . '.su_kien_id', 'left');
        
        // Chỉ lấy các bản ghi đã xóa mềm
        $this->builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $this->builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo loại check-in
        if (isset($criteria['checkin_type']) && $criteria['checkin_type'] !== '') {
            $this->builder->where($this->table . '.checkin_type', $criteria['checkin_type']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $this->builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý tìm kiếm theo khoảng thời gian xóa
        if (isset($criteria['start_date']) && isset($criteria['end_date'])) {
            $this->builder->where($this->table . '.deleted_at >=', $criteria['start_date']);
            $this->builder->where($this->table . '.deleted_at <=', $criteria['end_date']);
        } elseif (isset($criteria['start_date'])) {
            $this->builder->where($this->table . '.deleted_at >=', $criteria['start_date']);
        } elseif (isset($criteria['end_date'])) {
            $this->builder->where($this->table . '.deleted_at <=', $criteria['end_date']);
        }
        
        // Xử lý tìm kiếm theo từ khóa
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $this->builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $this->builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $this->builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            // Thêm tìm kiếm theo tên sự kiện
            $this->builder->orLike('sk.ten_su_kien', $keyword);
            $this->builder->groupEnd();
        }
        
        // Sắp xếp dữ liệu
        if (strpos($sort, '.') === false) {
            $sort = $this->table . '.' . $sort;
        }
        $this->builder->orderBy($sort, $order);
        
        // Thiết lập pager nếu có limit
        if ($limit > 0) {
            $totalRows = $this->countDeletedSearchResults($criteria);
            $currentPage = floor($offset / $limit) + 1;
            
            if ($this->pager === null) {
                $this->pager = new Pager($totalRows, $limit, $currentPage);
                $this->pager->setSurroundCount($this->surroundCount ?? 2);
            } else {
                $this->pager->setTotal($totalRows)
                            ->setPerPage($limit)
                            ->setCurrentPage($currentPage)
                            ->setSurroundCount($this->surroundCount ?? 2);
            }
        }
        
        // Phân trang
        if ($limit > 0) {
            $this->builder->limit($limit, $offset);
        }
        
        // Thực hiện truy vấn
        $query = $this->builder->get();
        
        // Ghi log câu SQL để debug
        log_message('debug', '[searchDeleted] SQL Query: ' . $this->db->getLastQuery());
        
        $result = $query->getResult($this->returnType);
        
        // Kiểm tra và ghi log kết quả
        log_message('debug', '[searchDeleted] Found ' . count($result) . ' deleted records');
        
        return $result;
    }
    
    /**
     * Đếm số bản ghi đã xóa mềm dựa trên các tiêu chí tìm kiếm
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedSearchResults(array $criteria = [])
    {
        // Khởi tạo builder trực tiếp từ database
        $builder = $this->db->table($this->table);
        
        // ĐẢM BẢO chỉ đếm các bản ghi đã bị xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Join với bảng sự kiện với alias rõ ràng để tránh trùng lặp
        $builder->select($this->table . '.checkin_sukien_id');
        $builder->join('su_kien AS sk', 'sk.su_kien_id = ' . $this->table . '.su_kien_id', 'left');
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && $criteria['su_kien_id'] !== '') {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo loại check-in
        if (isset($criteria['checkin_type']) && $criteria['checkin_type'] !== '') {
            $builder->where($this->table . '.checkin_type', $criteria['checkin_type']);
        }
        
        // Xử lý lọc theo hình thức tham gia
        if (isset($criteria['hinh_thuc_tham_gia']) && $criteria['hinh_thuc_tham_gia'] !== '') {
            $builder->where($this->table . '.hinh_thuc_tham_gia', $criteria['hinh_thuc_tham_gia']);
        }
        
        // Xử lý tìm kiếm theo khoảng thời gian xóa
        if (isset($criteria['start_date']) && isset($criteria['end_date'])) {
            $builder->where($this->table . '.deleted_at >=', $criteria['start_date']);
            $builder->where($this->table . '.deleted_at <=', $criteria['end_date']);
        } elseif (isset($criteria['start_date'])) {
            $builder->where($this->table . '.deleted_at >=', $criteria['start_date']);
        } elseif (isset($criteria['end_date'])) {
            $builder->where($this->table . '.deleted_at <=', $criteria['end_date']);
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
            // Thêm tìm kiếm theo tên sự kiện với alias đã được định nghĩa
            $builder->orLike('sk.ten_su_kien', $keyword);
            $builder->groupEnd();
        }
        
        $count = $builder->countAllResults();
        log_message('debug', '[countDeletedSearchResults] SQL Query: ' . $this->db->getLastQuery());
        log_message('debug', '[countDeletedSearchResults] Total count: ' . $count);
        
        return $count;
    }

    
    /**
     * Lấy toàn bộ dữ liệu đã xóa không phụ thuộc vào filter
     * 
     * @param array $options Tùy chọn tìm kiếm (sort, order, limit)
     * @return array Dữ liệu đã xóa
     */
    public function getAllDeleted($options = [])
    {
        // Thiết lập các tùy chọn tìm kiếm mặc định
        $sort = $options['sort'] ?? 'thoi_gian_check_in';
        $order = $options['order'] ?? 'DESC';
        $limit = $options['limit'] ?? 0;
        
        // Khởi tạo builder
        $builder = $this->db->table($this->table)
            ->select($this->table . ".*, su_kien.ten_su_kien")
            ->join('su_kien', "su_kien.su_kien_id = " . $this->table . ".su_kien_id", 'left');
        
        // Chỉ lấy các bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Sắp xếp kết quả
        $builder->orderBy($this->table . "." . $sort, $order);
        
        // Thêm giới hạn nếu cần
        if ($limit > 0) {
            $builder->limit($limit);
        }
        
        // Ghi log câu SQL để debug
        log_message('debug', '[getAllDeleted] SQL Query: ' . $this->db->getLastQuery());
        
        // Thực hiện truy vấn
        $query = $builder->get();
        $results = $query->getResult($this->returnType);
        
        // Kiểm tra kết quả
        log_message('debug', '[getAllDeleted] Found ' . count($results) . ' deleted records');
        
        // Tải các quan hệ
        $this->loadRelations($results);
        
        return $results;
    }

    /**
     * Định dạng datetime theo chuẩn d/m/Y H:i:s
     *
     * @param string|null $dateTimeString
     * @return string|null
     */
    public function formatDateTime($dateTimeString)
    {
        if (empty($dateTimeString)) {
            return null;
        }
        
        try {
            $dateTime = new Time($dateTimeString);
            return $dateTime->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            log_message('error', 'Lỗi định dạng ngày tháng: ' . $e->getMessage());
            return $dateTimeString;
        }
    }

    /**
     * Tải các mối quan hệ cho danh sách kết quả
     *
     * @param array $results Danh sách kết quả cần tải mối quan hệ
     * @return array Danh sách kết quả đã tải mối quan hệ
     */
    protected function loadRelations(array $results)
    {
        if (empty($results)) {
            return $results;
        }
        
        foreach ($results as $index => $item) {
            // Tải quan hệ với sự kiện
            if (isset($this->relations['sukien']) && !empty($item->su_kien_id)) {
                $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
                $suKien = $suKienModel->withDeleted()->find($item->su_kien_id);
                if ($suKien) {
                    $item->sukien = $suKien;
                }
            }
            
            // Tải quan hệ với đăng ký sự kiện
            if (isset($this->relations['dangkysukien']) && !empty($item->dangky_sukien_id)) {
                $dangKyModel = new \App\Modules\dangkysukien\Models\DangKySuKienModel();
                $dangKy = $dangKyModel->withDeleted()->find($item->dangky_sukien_id);
                if ($dangKy) {
                    $item->dangkysukien = $dangKy;
                }
            }
            
            $results[$index] = $item;
        }
        
        return $results;
    }
} 