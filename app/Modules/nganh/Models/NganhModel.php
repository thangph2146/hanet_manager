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
    ];
    
    protected $returnType = Nganh::class;
    
    // Định nghĩa các mối quan hệ
    protected $relations = [
        'phong_khoa' => [
            'type' => 'n-1',
            'table' => 'phong_khoa',
            'foreignKey' => 'phong_khoa_id',
            'localKey' => 'phong_khoa_id',
            'entity' => 'App\Entities\BaseEntity', // Sử dụng BaseEntity nếu không có entity cụ thể
            'conditions' => [
                ['field' => 'phong_khoa.bin', 'value' => 0]
            ],
            'select' => ['phong_khoa_id', 'ma_phong_khoa', 'ten_phong_khoa', 'status'],
            'useSoftDeletes' => true
        ],
    ];
    
    // Các trường được tìm kiếm
    protected $searchableFields = [
        'ten_nganh',
        'ma_nganh'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'phong_khoa_id'
    ];
    
    // Các trường cần kiểm tra tính duy nhất
    protected $uniqueFields = [
        'ma_nganh' => 'Mã ngành',
        'ten_nganh' => 'Tên ngành'
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
     * Lấy tất cả các bản ghi ngành đang hoạt động
     *
     * @param bool $withPhongKhoa Có tải mối quan hệ phòng khoa không
     * @return array
     */
    public function getAllActive(bool $withPhongKhoa = false)
    {
        $query = $this->where('status', 1)
                 ->where('bin', 0)
                 ->orderBy('ten_nganh', 'ASC');
        
        if ($withPhongKhoa) {
            $query->withRelations(['phong_khoa']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Lấy tất cả các bản ghi ngành đã xóa
     *
     * @param bool $withPhongKhoa Có tải mối quan hệ phòng khoa không
     * @return array
     */
    public function getAllDeleted(bool $withPhongKhoa = false)
    {
        $query = $this->withDeleted()
                 ->where('deleted_at IS NOT NULL')
                 ->orderBy('deleted_at', 'DESC');
        
        if ($withPhongKhoa) {
            $query->withRelations(['phong_khoa']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Kiểm tra xem mã ngành đã tồn tại chưa
     *
     * @param string $code Mã ngành cần kiểm tra
     * @param int|null $excludeId ID ngành cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isCodeExists(string $code, ?int $excludeId = null): bool
    {
        $query = $this->where('ma_nganh', $code);
        
        if ($excludeId !== null) {
            $query->where("{$this->primaryKey} !=", $excludeId);
        }
        
        return $query->countAllResults() > 0;
    }
    
    /**
     * Kiểm tra xem tên ngành đã tồn tại chưa
     *
     * @param string $name Tên ngành cần kiểm tra
     * @param int|null $excludeId ID ngành cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isNameExists(string $name, ?int $excludeId = null): bool
    {
        $query = $this->where('ten_nganh', $name);
        
        if ($excludeId !== null) {
            $query->where("{$this->primaryKey} !=", $excludeId);
        }
        
        return $query->countAllResults() > 0;
    }
    
    /**
     * Lấy ngành với quan hệ phòng khoa
     *
     * @param int $id ID ngành cần lấy
     * @return object|null
     */
    public function findWithPhongKhoa(int $id)
    {
        return $this->findWithRelations($id, ['phong_khoa']);
    }
    
    /**
     * Lấy danh sách ngành theo ID phòng/khoa
     *
     * @param int $phongKhoaId
     * @param bool $withPhongKhoa Có tải mối quan hệ phòng khoa không
     * @return array
     */
    public function getByPhongKhoaId(int $phongKhoaId, bool $withPhongKhoa = false)
    {
        $query = $this->where('phong_khoa_id', $phongKhoaId)
                 ->where('status', 1)
                 ->where('bin', 0)
                 ->orderBy('ten_nganh', 'ASC');
        
        if ($withPhongKhoa) {
            $query->withRelations(['phong_khoa']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Tìm kiếm ngành theo từ khóa và bộ lọc
     * 
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn bổ sung
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        // Khởi tạo query builder
        $builder = $this->where('bin', 0);
        
        // Xử lý từ khóa tìm kiếm
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $builder->groupStart();
            
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $keyword);
            }
            
            $builder->groupEnd();
        }
        
        // Áp dụng các điều kiện lọc
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields) && !empty($value)) {
                    if (is_array($value)) {
                        $builder->whereIn($field, $value);
                    } else {
                        $builder->where($field, $value);
                    }
                }
            }
        }
        
        // Kết nối với bảng phòng/khoa
        if (!isset($options['withPhongKhoa']) || $options['withPhongKhoa'] === true) {
            $builder->withRelations(['phong_khoa']);
        }
        
        // Xử lý tùy chọn sắp xếp
        if (isset($options['sort_field']) && isset($options['sort_direction'])) {
            $builder->orderBy($options['sort_field'], $options['sort_direction']);
        } else {
            $builder->orderBy('updated_at', 'DESC');
        }
        
        // Xử lý phân trang nếu có
        if (isset($options['limit']) && isset($options['offset'])) {
            $builder->limit($options['limit'], $options['offset']);
        }
        
        // Trả về kết quả
        return $builder->findAll();
    }
    
    /**
     * Lấy danh sách phòng khoa từ quan hệ đã định nghĩa
     * Không cần model PhongKhoa, tận dụng BaseModel và relationship
     * 
     * @param array $select Các trường cần lấy
     * @return array
     */
    public function getAllPhongKhoa(array $select = ['phong_khoa_id', 'ten_phong_khoa', 'ma_phong_khoa'])
    {
        // Sử dụng relation đã định nghĩa thay vì truy vấn thủ công
        $relation = $this->relations['phong_khoa'];
        $table = $relation['table'];
        $conditions = $relation['conditions'] ?? [];
        
        $query = $this->db->table($table)->select($select);
        
        // Áp dụng các điều kiện từ relation
        foreach ($conditions as $condition) {
            $field = $condition['field'];
            $value = $condition['value'];
            $operator = $condition['operator'] ?? '=';
            
            $query->where($field, $value);
        }
        
        // Thêm các điều kiện mặc định
        $query->where('status', 1)
              ->where('bin', 0)
              ->orderBy('ten_phong_khoa', 'ASC');
        
        return $query->get()->getResult();
    }
    
    /**
     * Lấy một bản ghi ngành với thông tin phòng khoa đầy đủ
     *
     * @param int $id ID ngành cần lấy
     * @return object|null
     */
    public function getNganhWithPhongKhoa(int $id)
    {
        return $this->findWithRelations($id, ['phong_khoa']);
    }
} 