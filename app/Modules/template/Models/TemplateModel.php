<?php

namespace App\Modules\template\Models;

use App\Models\BaseModel;
use App\Modules\template\Entities\Template;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class TemplateModel extends BaseModel
{
    protected $table = 'template';
    protected $primaryKey = 'template_id';
    protected $useSoftDeletes = false;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ma_template',
        'ten_template',
        'status',
        'bin',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    
    protected $returnType = 'App\Modules\template\Entities\Template';
    
    // Các trường được tìm kiếm
    protected $searchableFields = [
        'ma_template',
        'ten_template'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin',
        'created_at'
    ];
    
    // Các trường cần kiểm tra tính duy nhất
    protected $uniqueFields = [
        'ma_template' => 'Mã template',
        'ten_template' => 'Tên template'
    ];
    
    // Các trường loại bỏ khoảng trắng thừa trước khi lưu
    protected $beforeSpaceRemoval = [
        'ma_template',
        'ten_template'
    ];
    
    // Các quy tắc xác thực
    public $validationRules = [
        'ten_template' => 'required|min_length[3]|max_length[200]',
        'ma_template' => 'required|max_length[20]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    public $validationMessages = [
        'ten_template' => [
            'required' => 'Tên template là bắt buộc',
            'min_length' => 'Tên template phải có ít nhất {param} ký tự',
            'max_length' => 'Tên template không được vượt quá {param} ký tự',
        ],
        'ma_template' => [
            'required' => 'Mã template là bắt buộc',
            'max_length' => 'Mã template không được vượt quá {param} ký tự',
        ]
    ];
    
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }
    
    /**
     * Khởi tạo query cơ bản cho model
     * 
     * @return BaseBuilder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()->where('bin', 0);
    }
    
    /**
     * Lấy tất cả các bản ghi template đã xóa
     *
     * @return array
     */
    public function getAllDeleted()
    {
        $query = $this->withDeleted()
                 ->where('deleted_at IS NOT NULL')
                 ->orderBy('deleted_at', 'DESC');
        
        return $query->findAll();
    }
    
    /**
     * Kiểm tra tên template đã tồn tại chưa
     *
     * @param string $tenTemplate
     * @param int|null $exceptId ID template cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isNameExists(string $tenTemplate, int $exceptId = null)
    {
        $builder = $this->where('ten_template', $tenTemplate);
        
        if ($exceptId !== null) {
            $builder->where('template_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Kiểm tra mã template đã tồn tại chưa
     *
     * @param string $maTemplate
     * @param int|null $exceptId ID template cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isCodeExists(string $maTemplate, int $exceptId = null)
    {
        $builder = $this->where('ma_template', $maTemplate);
        
        if ($exceptId !== null) {
            $builder->where('template_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Tìm kiếm template theo từ khóa và bộ lọc
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
        
        // Chỉ hiển thị bản ghi không bị xóa mềm và không trong thùng rác
        $builder = $this->where('bin', 0);
        
        return parent::search($searchCriteria, $searchOptions);
    }
    
    /**
     * Chuyển template vào thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function moveToRecycleBin(int $id): bool
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục template từ thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromRecycleBin(int $id): bool
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy tất cả template
     *
     * @return array
     */
    public function getAll()
    {
        return $this->where('bin', 0)
                    ->orderBy('ten_template', 'ASC')
                    ->findAll();
    }
    
    /**
     * Lấy tất cả template đang hoạt động
     *
     * @return array
     */
    public function getAllActive()
    {
        return $this->where('bin', 0)
                    ->where('status', 1)
                    ->orderBy('ten_template', 'ASC')
                    ->findAll();
    }
    
    /**
     * Lấy tất cả template trong thùng rác
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
     * Tìm và trả về một template với ID cho trước
     * 
     * @param int $id
     * @return object|null
     */
    public function findById(int $id)
    {
        return $this->find($id);
    }
    
    /**
     * Đếm số kết quả tìm kiếm
     *
     * @param array $criteria
     * @return int
     */
    public function countSearchResults(array $criteria = [])
    {
        $builder = $this->builder();
        
        // Thêm điều kiện tìm kiếm
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $builder->groupStart()
                    ->like('ten_template', $keyword)
                    ->orLike('ma_template', $keyword)
                    ->groupEnd();
        }
        
        // Thêm điều kiện lọc
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if ($value !== '' && $value !== null) {
                    $builder->where($field, $value);
                }
            }
        }
        
        return $builder->countAllResults();
    }
} 