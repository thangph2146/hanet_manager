<?php

namespace App\Modules\quanlynguoidung\Models;

use App\Models\BaseModel;
use App\Modules\quanlynguoidung\Entities\NguoiDung;
use App\Modules\quanlynguoidung\Libraries\Pager;
use CodeIgniter\I18n\Time;

class NguoiDungModel extends BaseModel
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'nguoi_dung_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'AccountId',
        'u_id',
        'FirstName',
        'MiddleName',
        'LastName',
        'AccountType',
        'FullName',
        'MobilePhone',
        'Email',
        'HomePhone1',
        'PW',
        'HomePhone',
        'avatar',
        'loai_nguoi_dung_id',
        'mat_khau_local',
        'nam_hoc_id',
        'bac_hoc_id',
        'he_dao_tao_id',
        'nganh_id',
        'phong_khoa_id',
        'status',
        'last_login',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = NguoiDung::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'AccountId',
        'FullName',
        'LastName',
        'MiddleName',
        'FirstName',
        'Email',
        'MobilePhone'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'loai_nguoi_dung_id',
        'nam_hoc_id',
        'bac_hoc_id',
        'he_dao_tao_id',
        'nganh_id',
        'phong_khoa_id',
        'status'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Template pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi người dùng
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'created_at', $order = 'DESC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Chỉ lấy bản ghi chưa xóa
        $this->builder->where('deleted_at IS NULL');
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
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
     * Lấy tất cả bản ghi người dùng đã xóa
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllDeleted($limit = 10, $offset = 0, $sort = 'deleted_at', $order = 'DESC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Chỉ lấy bản ghi đã xóa
        $this->builder->where('deleted_at IS NOT NULL');
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        $total = $this->countAllDeleted();
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
     * Đếm tổng số bản ghi người dùng
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm bản ghi chưa xóa
        $builder->where('deleted_at IS NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Đếm tổng số bản ghi người dùng đã xóa
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllDeleted($conditions = [])
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm bản ghi đã xóa
        $builder->where('deleted_at IS NOT NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy tất cả bản ghi đang hoạt động
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllActive(int $limit = 10, int $offset = 0, string $sort = 'created_at', string $order = 'DESC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('status', 1);
        $this->builder->where('deleted_at IS NULL');
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        $total = $this->countAllActive();
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        if ($this->pager === null) {
            $this->pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->pager->setTotal($total)
                        ->setPerPage($limit)
                        ->setCurrentPage($currentPage);
        }
        
        if ($limit > 0) {
            $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
            return $result ?: [];
        }
        
        // Thay vì sử dụng findAll(), sử dụng builder để đảm bảo điều kiện deleted_at IS NULL
        return $this->builder->get()->getResult($this->returnType);
    }
    
    /**
     * Đếm tổng số bản ghi đang hoạt động
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllActive($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('status', 1);
        $builder->where('deleted_at IS NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm người dùng dựa vào các tiêu chí
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
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($field, $keyword);
                } else {
                    $builder->orLike($field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        if (isset($criteria['status']) || array_key_exists('status', $criteria)) {
            $status = (int)$criteria['status'];
            $builder->where($this->table . '.status', $status);
        }
        
        // Lọc theo loại người dùng
        if (!empty($criteria['loai_nguoi_dung_id'])) {
            $builder->where($this->table . '.loai_nguoi_dung_id', $criteria['loai_nguoi_dung_id']);
        }
        
        // Lọc theo năm học
        if (!empty($criteria['nam_hoc_id'])) {
            $builder->where($this->table . '.nam_hoc_id', $criteria['nam_hoc_id']);
        }
        
        // Lọc theo bậc học
        if (!empty($criteria['bac_hoc_id'])) {
            $builder->where($this->table . '.bac_hoc_id', $criteria['bac_hoc_id']);
        }
        
        // Lọc theo hệ đào tạo
        if (!empty($criteria['he_dao_tao_id'])) {
            $builder->where($this->table . '.he_dao_tao_id', $criteria['he_dao_tao_id']);
        }
        
        // Lọc theo ngành
        if (!empty($criteria['nganh_id'])) {
            $builder->where($this->table . '.nganh_id', $criteria['nganh_id']);
        }
        
        // Lọc theo phòng khoa
        if (!empty($criteria['phong_khoa_id'])) {
            $builder->where($this->table . '.phong_khoa_id', $criteria['phong_khoa_id']);
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
        $builder->orderBy($sort, $order);
        
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
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($field, $keyword);
                } else {
                    $builder->orLike($field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        if (isset($criteria['status']) || array_key_exists('status', $criteria)) {
            $status = (int)$criteria['status'];
            $builder->where($this->table . '.status', $status);
        }
        
        // Lọc theo loại người dùng
        if (!empty($criteria['loai_nguoi_dung_id'])) {
            $builder->where($this->table . '.loai_nguoi_dung_id', $criteria['loai_nguoi_dung_id']);
        }
        
        // Lọc theo năm học
        if (!empty($criteria['nam_hoc_id'])) {
            $builder->where($this->table . '.nam_hoc_id', $criteria['nam_hoc_id']);
        }
        
        // Lọc theo bậc học
        if (!empty($criteria['bac_hoc_id'])) {
            $builder->where($this->table . '.bac_hoc_id', $criteria['bac_hoc_id']);
        }
        
        // Lọc theo hệ đào tạo
        if (!empty($criteria['he_dao_tao_id'])) {
            $builder->where($this->table . '.he_dao_tao_id', $criteria['he_dao_tao_id']);
        }
        
        // Lọc theo ngành
        if (!empty($criteria['nganh_id'])) {
            $builder->where($this->table . '.nganh_id', $criteria['nganh_id']);
        }
        
        // Lọc theo phòng khoa
        if (!empty($criteria['phong_khoa_id'])) {
            $builder->where($this->table . '.phong_khoa_id', $criteria['phong_khoa_id']);
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
        $entity = new NguoiDung();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        unset($this->validationRules['last_login']);
        // Loại bỏ validation cho nguoi_dung_id trong mọi trường hợp
        unset($this->validationRules['nguoi_dung_id']);
        unset($this->validationRules['u_id']);
        unset($this->validationRules['nam_hoc_id']);
        unset($this->validationRules['bac_hoc_id']);
        unset($this->validationRules['he_dao_tao_id']);
        unset($this->validationRules['nganh_id']);
        unset($this->validationRules['phong_khoa_id']);
        
        if ($scenario === 'update' && isset($data['nguoi_dung_id'])) {
            foreach ($this->validationRules as $field => &$rules) {
                // Kiểm tra nếu $rules là một mảng
                if (is_array($rules) && isset($rules['rules'])) {
                    // Kiểm tra nếu chuỗi quy tắc chứa is_unique
                    if (strpos($rules['rules'], 'is_unique') !== false) {
                        $rules['rules'] = str_replace('{nguoi_dung_id}', $data['nguoi_dung_id'], $rules['rules']);
                    }
                } 
                // Nếu $rules là một chuỗi
                else if (is_string($rules) && strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace('{nguoi_dung_id}', $data['nguoi_dung_id'], $rules);
                }
            }
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
     * Tìm bản ghi với các quan hệ
     *
     * @param int $id ID bản ghi cần tìm
     * @param array $relations Các quan hệ cần lấy theo
     * @param bool $validate Có kiểm tra dữ liệu trước khi trả về không
     * @return object|null Đối tượng tìm thấy hoặc null nếu không tìm thấy
     */
    public function findWithRelations($id, $relations = [], $validate = false)
    {
        // Trong trường hợp đơn giản, chúng ta chỉ gọi phương thức find
        // Nhưng trong thực tế, có thể cần xử lý thêm các quan hệ
        return $this->find($id);
    }
    
    /**
     * Tìm kiếm các bản ghi đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn tìm kiếm (limit, offset, sort, order)
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->withDeleted();
        
        // Đặt điều kiện để chỉ lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Sử dụng phương thức search hiện tại với tham số đã sửa đổi
        return $this->search($criteria, $options);
    }
    
    /**
     * Đếm số lượng bản ghi đã xóa theo tiêu chí tìm kiếm
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedResults(array $criteria = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->withDeleted();
        
        // Đặt điều kiện để chỉ đếm các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Sử dụng phương thức countSearchResults hiện tại với tham số đã sửa đổi
        return $this->countSearchResults($criteria);
    }
    
    /**
     * Kiểm tra xem tài khoản đã tồn tại chưa
     *
     * @param string $accountId
     * @param int|null $excludeId ID của bản ghi cần loại trừ khi kiểm tra (cho update)
     * @return bool
     */
    public function isAccountIdExists(string $accountId, ?int $excludeId = null): bool
    {
        if (empty($accountId)) {
            return false;
        }
        
        $builder = $this->builder();
        $builder->where('AccountId', $accountId);
        $builder->where('deleted_at IS NULL');
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Kiểm tra xem email đã tồn tại chưa
     *
     * @param string $email
     * @param int|null $excludeId ID của bản ghi cần loại trừ khi kiểm tra (cho update)
     * @return bool
     */
    public function isEmailExists(string $email, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('Email', $email);
        $builder->where('deleted_at IS NULL');
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Lấy danh sách người dùng theo loại người dùng
     *
     * @param int $loaiNguoiDungId ID của loại người dùng
     * @param bool $onlyActive Chỉ lấy các người dùng đang hoạt động
     * @return array
     */
    public function getByLoaiNguoiDung(int $loaiNguoiDungId, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $builder->where('loai_nguoi_dung_id', $loaiNguoiDungId);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('FullName', 'ASC');
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách người dùng theo phòng khoa
     *
     * @param int $phongKhoaId ID của phòng khoa
     * @param bool $onlyActive Chỉ lấy các người dùng đang hoạt động
     * @return array
     */
    public function getByPhongKhoa(int $phongKhoaId, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $builder->where('phong_khoa_id', $phongKhoaId);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('FullName', 'ASC');
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách người dùng theo ngành
     *
     * @param int $nganhId ID của ngành
     * @param bool $onlyActive Chỉ lấy các người dùng đang hoạt động
     * @return array
     */
    public function getByNganh(int $nganhId, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $builder->where('nganh_id', $nganhId);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('FullName', 'ASC');
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Cập nhật thời gian đăng nhập cuối cùng
     *
     * @param int $id ID của người dùng
     * @return bool
     */
    public function updateLastLogin(int $id): bool
    {
        return $this->update($id, [
            'last_login' => Time::now()->toDateTimeString()
        ]);
    }
    
    /**
     * Lọc dữ liệu trước khi chèn hoặc cập nhật
     * Xóa các trường trống không bắt buộc
     * 
     * @param array $data Dữ liệu cần lọc
     * @return array Dữ liệu đã lọc
     */
    public function filterData(array $data): array
    {
        // Các trường bắt buộc không được unset
        $requiredFields = ['Email', 'FullName', 'LastName', 'MiddleName', 'FirstName', 'MobilePhone'];
        
        // Các trường số nguyên cần được chuyển thành null khi rỗng
        $integerFields = ['u_id', 'loai_nguoi_dung_id', 'nam_hoc_id', 'bac_hoc_id', 'he_dao_tao_id', 'nganh_id', 'phong_khoa_id', 'status'];
        
        // Các trường datetime cần được xử lý đặc biệt
        $datetimeFields = ['last_login', 'created_at', 'updated_at', 'deleted_at'];
        
        $removedFields = [];
        
        // Lọc ra các trường trống không bắt buộc
        foreach ($data as $key => $value) {
            // Nếu là trường số nguyên và giá trị rỗng, đặt thành null
            if (in_array($key, $integerFields) && $value === '') {
                $data[$key] = null;
            }
            // Xử lý các trường datetime
            elseif (in_array($key, $datetimeFields) && empty($value)) {
                unset($data[$key]);
                $removedFields[] = $key;
            }
            // Nếu trường không phải là bắt buộc và giá trị rỗng, unset nó
            elseif (!in_array($key, $requiredFields) && (empty($value) && $value !== '0' && $value !== 0)) {
                unset($data[$key]);
                $removedFields[] = $key;
            }
        }
        
        // Đảm bảo các trường bắt buộc luôn tồn tại
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                log_message('error', 'Required field missing: ' . $field);
                // Không thêm trường trống vào data, chỉ log lỗi
            }
        }
        
        if (!empty($removedFields)) {
            log_message('debug', 'Removed empty fields from data: ' . implode(', ', $removedFields));
        }
        
        return $data;
    }
    
    /**
     * Ghi đè phương thức insert để lọc dữ liệu trước khi chèn
     * 
     * @param array $data Dữ liệu cần chèn
     * @param bool $returnID Có trả về ID của bản ghi mới hay không
     * @return int|string|bool ID của bản ghi mới hoặc kết quả thành công/thất bại
     */
    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data)) {
            $data = $this->filterData($data);
        }
        
        return parent::insert($data, $returnID);
    }
    
    /**
     * Ghi đè phương thức update để lọc dữ liệu trước khi cập nhật
     * 
     * @param int|array|string $id ID của bản ghi cần cập nhật hoặc mảng điều kiện
     * @param array $data Dữ liệu cập nhật
     * @return bool Kết quả thành công/thất bại
     */
    public function update($id = null, $data = null): bool
    {
        if (is_array($data)) {
            $data = $this->filterData($data);
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Lấy thông tin người dùng dựa vào địa chỉ email
     *
     * @param string|null $email Địa chỉ email cần tìm
     * @return object|null Thông tin người dùng hoặc null nếu không tìm thấy
     */
    public function getUserByEmail(?string $email)
    {
        if (empty($email)) {
            return null;
        }
        
        return $this->where('Email', $email)
                    ->where('deleted_at IS NULL')
                    ->first();
    }
} 