<?php

namespace App\Modules\nganh\Models;

use App\Models\BaseModel;
use App\Modules\nganh\Entities\Nganh;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class NganhModel extends BaseModel
{
    protected $table = 'nganh';
    protected $primaryKey = 'nganh_id';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ma_nganh',
        'ten_nganh',
        'phong_khoa_id',
        'status',
        'bin',
        'created_at',
        'updated_at'
    ];
    
    protected $returnType = Nganh::class;
    
    // Định nghĩa các mối quan hệ
    protected $relations = [
        'phong_khoa' => [
            'type' => 'n-1',
            'table' => 'phong_khoa',
            'foreignKey' => 'phong_khoa_id',
            'localKey' => 'phong_khoa_id',
            'entity' => 'App\Modules\phongkhoa\Entities\PhongKhoa',
            'conditions' => [
                ['field' => 'phong_khoa.bin', 'value' => 0]
            ],
            'select' => ['phong_khoa_id', 'ma_phong_khoa', 'ten_phong_khoa', 'status'],
            'useSoftDeletes' => true
        ],
    ];
    
    // Các trường được tìm kiếm
    protected $searchableFields = [
        'ma_nganh',
        'ten_nganh'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'phong_khoa_id',
        'status',
        'bin',
        'created_at'
    ];
    
    // Các trường cần kiểm tra tính duy nhất
    protected $uniqueFields = [
        'ma_nganh' => 'Mã ngành',
        'ten_nganh' => 'Tên ngành'
    ];
    
    // Các trường loại bỏ khoảng trắng thừa trước khi lưu
    protected $beforeSpaceRemoval = [
        'ma_nganh',
        'ten_nganh'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [
        'ten_nganh' => 'required|min_length[3]|max_length[200]',
        'ma_nganh' => 'required|max_length[20]',
        'phong_khoa_id' => 'permit_empty|integer',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_nganh' => [
            'required' => 'Tên ngành là bắt buộc',
            'min_length' => 'Tên ngành phải có ít nhất {param} ký tự',
            'max_length' => 'Tên ngành không được vượt quá {param} ký tự',
        ],
        'ma_nganh' => [
            'required' => 'Mã ngành là bắt buộc',
            'max_length' => 'Mã ngành không được vượt quá {param} ký tự',
        ],
        'phong_khoa_id' => [
            'integer' => 'ID phòng/khoa phải là số nguyên',
        ],
    ];
    
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }
    
    /**
     * Khởi tạo query cơ bản cho model
     * Tự động tải quan hệ phong_khoa khi được yêu cầu
     * 
     * @return BaseBuilder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()->where('bin', 0);
    }
    
    /**
     * Lấy tất cả các bản ghi ngành đã xóa
     *
     * @param bool $withRelations Có tải mối quan hệ không
     * @return array
     */
    public function getAllDeleted(bool $withRelations = false)
    {
        $query = $this->withDeleted()
                 ->where('deleted_at IS NOT NULL')
                 ->orderBy('deleted_at', 'DESC');
        
        if ($withRelations) {
            $query->withRelations(['phong_khoa']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Kiểm tra mã ngành đã tồn tại chưa
     *
     * @param string $code Mã ngành cần kiểm tra
     * @param int|null $excludeId ID ngành cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isCodeExists(string $code, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ma_nganh', $code);
        $builder->where('bin', 0);
        
        if ($excludeId !== null) {
            $builder->where('nganh_id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Kiểm tra tên ngành đã tồn tại chưa
     *
     * @param string $name Tên ngành cần kiểm tra
     * @param int|null $excludeId ID ngành cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isNameExists(string $name, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ten_nganh', $name);
        $builder->where('bin', 0);
        
        if ($excludeId !== null) {
            $builder->where('nganh_id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Lấy danh sách ngành theo ID phòng/khoa
     *
     * @param int $phongKhoaId
     * @param bool $withRelations Có tải mối quan hệ không
     * @return array
     */
    public function getByPhongKhoaId(int $phongKhoaId, bool $withRelations = false)
    {
        $query = $this->where('phong_khoa_id', $phongKhoaId)
                 ->where('status', 1)
                 ->where('bin', 0)
                 ->orderBy('ten_nganh', 'ASC');
        
        if ($withRelations) {
            $query->withRelations(['phong_khoa']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Lấy tất cả phòng khoa để hiển thị trong dropdown
     *
     * @return array
     */
    public function getAllPhongKhoa()
    {
        return $this->db->table('phong_khoa')
                ->select('phong_khoa_id, ma_phong_khoa, ten_phong_khoa')
                ->where('status', 1)
                ->where('bin', 0)
                ->where('deleted_at IS NULL')
                ->orderBy('ten_phong_khoa', 'ASC')
                ->get()
                ->getResult();
    }
    
    /**
     * Tìm kiếm ngành theo từ khóa và bộ lọc
     * Tận dụng phương thức search từ BaseModel
     * 
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn bổ sung
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        // Biến đổi criteria để phù hợp với BaseModel
        $searchCriteria = [];
        
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $searchCriteria['search'] = $criteria['keyword'];
        }
        
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            $searchCriteria['filters'] = $criteria['filters'];
        }
        
        // Thiết lập tùy chọn
        $searchOptions = [];
        
        if (isset($options['sort_field']) && isset($options['sort_direction'])) {
            $searchOptions['sort'] = $options['sort_field'];
            $searchOptions['sort_direction'] = $options['sort_direction'];
        }
        
        if (isset($options['limit']) && isset($options['offset'])) {
            $searchOptions['limit'] = $options['limit'];
            $searchOptions['page'] = floor($options['offset'] / $options['limit']) + 1;
        }
        
        // Tải quan hệ
        if (!isset($options['withRelations']) || $options['withRelations'] === true) {
            $this->withRelations(['phong_khoa']);
        }
        
        // Chỉ hiển thị bản ghi không bị xóa mềm và không trong thùng rác
        $builder = $this->where('bin', 0);
        
        return parent::search($searchCriteria, $searchOptions);
    }
    
    /**
     * Lấy ngành với quan hệ phòng khoa
     *
     * @param int $id ID ngành cần lấy
     * @return object|null
     */
    public function findWithPhongKhoa(int $id)
    {
        return $this->withRelations(['phong_khoa'])->findWithRelations($id);
    }
    
    /**
     * Chuyển ngành vào thùng rác
     *
     * @param int $id ID ngành cần chuyển vào thùng rác
     * @return bool
     */
    public function moveToRecycleBin(int $id): bool
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục ngành từ thùng rác
     *
     * @param int $id ID ngành cần khôi phục
     * @return bool
     */
    public function restoreFromRecycleBin(int $id): bool
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy tất cả ngành đang hoạt động (không ở thùng rác và có status = 1)
     *
     * @return array
     */
    public function getAllActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_nganh', 'ASC')
                    ->findAll();
    }
    
    /**
     * Lấy tất cả ngành có trong thùng rác
     *
     * @return array
     */
    public function getAllInRecycleBin()
    {
        return $this->where('bin', 1)
                    ->orderBy('updated_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Lấy ngành theo phòng khoa
     *
     * @param int $phongKhoaId ID phòng khoa cần lọc
     * @return array
     */
    public function getByPhongKhoa(int $phongKhoaId)
    {
        return $this->where('phong_khoa_id', $phongKhoaId)
                    ->where('bin', 0)
                    ->orderBy('ten_nganh', 'ASC')
                    ->findAll();
    }
    
    /**
     * Tìm kiếm ngành nâng cao
     *
     * @param array $criteria Tiêu chí tìm kiếm (search, filters)
     * @param array $options Tùy chọn (sort, sort_direction, page, limit)
     * @return array
     */
    public function searchNganh(array $criteria = [], array $options = [])
    {
        // Thiết lập sắp xếp mặc định nếu không được chỉ định
        if (empty($options['sort'])) {
            $options['sort'] = 'ten_nganh';
            $options['sort_direction'] = 'ASC';
        }
        
        // Tạo builder và thêm điều kiện không ở thùng rác
        $builder = $this->builder();
        $builder->where('bin', 0);
        
        // Xử lý tìm kiếm text
        if (!empty($criteria['search']) && !empty($this->searchableFields)) {
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $criteria['search']);
            }
            $builder->groupEnd();
        }
        
        // Xử lý bộ lọc
        if (!empty($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    if (is_array($value)) {
                        $builder->whereIn($field, $value);
                    } else {
                        $builder->where($field, $value);
                    }
                }
            }
        }
        
        // Xử lý sắp xếp
        if (!empty($options['sort'])) {
            $direction = $options['sort_direction'] ?? 'ASC';
            $builder->orderBy($options['sort'], $direction);
        }
        
        // Xử lý phân trang
        if (isset($options['page']) && isset($options['limit'])) {
            $offset = ($options['page'] - 1) * $options['limit'];
            $builder->limit($options['limit'], $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
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
        $builder->where('bin', 0);
        
        // Xử lý tìm kiếm text
        if (!empty($criteria['search']) && !empty($this->searchableFields)) {
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $criteria['search']);
            }
            $builder->groupEnd();
        }
        
        // Xử lý bộ lọc
        if (!empty($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    if (is_array($value)) {
                        $builder->whereIn($field, $value);
                    } else {
                        $builder->where($field, $value);
                    }
                }
            }
        }
        
        return $builder->countAllResults();
    }
} 