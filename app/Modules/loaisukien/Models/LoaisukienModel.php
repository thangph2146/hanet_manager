<?php

namespace App\Modules\loaisukien\Models;

use App\Models\BaseModel;
use App\Modules\loaisukien\Entities\Loaisukien;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class LoaisukienModel extends BaseModel
{
    protected $table = 'loai_su_kien';
    protected $primaryKey = 'loai_su_kien_id';
    protected $useSoftDeletes = false;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ma_loai_su_kien',
        'ten_loai_su_kien',
        'status',
        'bin',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    
    protected $returnType = 'App\Modules\loaisukien\Entities\Loaisukien';
    
    // Các trường được tìm kiếm
    protected $searchableFields = [
        'ten_loai_su_kien' => ['weight' => 2],
        'ma_loai_su_kien' => ['weight' => 1]
    ];
    
    // Các trường được lọc
    protected $filterableFields = [
        'ten_loai_su_kien',
        'ma_loai_su_kien',
        'status',
        'bin'
    ];
    
    // Các trường sắp xếp
    protected $sortableFields = [
        'loai_su_kien_id',
        'ten_loai_su_kien',
        'ma_loai_su_kien',
        'status',
        'created_at',
        'updated_at'
    ];
    
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }
    
    protected function getBaseQuery()
    {
        return $this->db->table($this->table)
                      ->select("{$this->table}.*");
    }
    
    /**
     * Lấy tất cả các mục đã xóa (trong thùng rác)
     */
    public function getAllDeleted(bool $withRelations = false)
    {
        $query = $this->getBaseQuery()
                    ->where("{$this->table}.bin", 1);
        
        if ($this->useSoftDeletes) {
            $query->where("{$this->table}.{$this->deletedField} IS NULL");
        }
        
        $result = $query->get()->getResult($this->returnType);
        
        return $result;
    }
    
    /**
     * Kiểm tra tên loại sự kiện đã tồn tại hay chưa
     */
    public function isNameExists(string $tenLoaiSuKien, int $exceptId = null)
    {
        $query = $this->getBaseQuery()
                    ->where("{$this->table}.ten_loai_su_kien", $tenLoaiSuKien)
                    ->where("{$this->table}.bin", 0);
        
        if ($exceptId !== null) {
            $query->where("{$this->table}.{$this->primaryKey} !=", $exceptId);
        }
        
        $count = $query->countAllResults();
        
        return $count > 0;
    }
    
    /**
     * Kiểm tra mã loại sự kiện đã tồn tại hay chưa
     */
    public function isCodeExists(string $maLoaiSuKien, int $exceptId = null)
    {
        $query = $this->getBaseQuery()
                    ->where("{$this->table}.ma_loai_su_kien", $maLoaiSuKien)
                    ->where("{$this->table}.bin", 0);
        
        if ($exceptId !== null) {
            $query->where("{$this->table}.{$this->primaryKey} !=", $exceptId);
        }
        
        $count = $query->countAllResults();
        
        return $count > 0;
    }
    
    /**
     * Tìm kiếm theo tiêu chí
     */
    public function search(array $criteria = [], array $options = [])
    {
        // Thiết lập mặc định cho các tùy chọn
        $options = array_merge([
            'sort' => $this->primaryKey,
            'sort_direction' => 'asc',
            'paginate' => false,
            'page' => 1,
            'per_page' => 20
        ], $options);
        
        // Query cơ bản
        $query = $this->getBaseQuery();
        
        // Áp dụng các bộ lọc
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    $query->where("{$this->table}.{$field}", $value);
                }
            }
        }
        
        // Áp dụng tìm kiếm
        if (isset($criteria['search']) && $criteria['search'] !== '') {
            $searchQuery = $criteria['search'];
            $query->groupStart();
            
            foreach ($this->searchableFields as $field => $options) {
                $weight = $options['weight'] ?? 1;
                $query->orLike("{$this->table}.{$field}", $searchQuery);
            }
            
            $query->groupEnd();
        }
        
        // Sắp xếp
        if (in_array($options['sort'], $this->sortableFields)) {
            $query->orderBy("{$this->table}.{$options['sort']}", $options['sort_direction']);
        } else {
            $query->orderBy("{$this->table}.{$this->primaryKey}", 'asc');
        }
        
        // Phân trang nếu cần
        if ($options['paginate']) {
            $page = $options['page'] ?? 1;
            $perPage = $options['per_page'] ?? 20;
            
            return [
                'data' => $this->pager($query, $page, $perPage),
                'pager' => $this->pager
            ];
        }
        
        // Trả về kết quả không phân trang
        return [
            'data' => $query->get()->getResult($this->returnType),
            'pager' => null
        ];
    }
    
    /**
     * Chuyển mục vào thùng rác
     */
    public function moveToRecycleBin(int $id): bool
    {
        $data = ['bin' => 1];
        return $this->update($id, $data);
    }
    
    /**
     * Khôi phục mục từ thùng rác
     */
    public function restoreFromRecycleBin(int $id): bool
    {
        $data = ['bin' => 0];
        return $this->update($id, $data);
    }
    
    /**
     * Lấy tất cả các mục
     */
    public function getAll()
    {
        return $this->where('bin', 0)
                ->orderBy('updated_at', 'DESC')
                ->findAll();
    }
    
    /**
     * Lấy tất cả các mục đang hoạt động
     */
    public function getAllActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_loai_su_kien', 'ASC')
                    ->findAll();
    }
    
    /**
     * Lấy tất cả các mục trong thùng rác
     */
    public function getAllInRecycleBin()
    {
        return $this->where('bin', 1)
                ->orderBy('deleted_at', 'DESC')
                ->findAll();
    }
    
    /**
     * Hỗ trợ phân trang cho các truy vấn
     */
    protected function pager($query, $page = 1, $perPage = 20)
    {
        $total = $query->countAllResults(false);
        $this->pager = service('pager');
        $this->pager->makeLinks($page, $perPage, $total);
        
        return $query->paginate($perPage, 'default', $page);
    }
    
    /**
     * Đếm số lượng kết quả tìm kiếm
     */
    public function countSearchResults(array $criteria = [])
    {
        // Query cơ bản
        $query = $this->getBaseQuery();
        
        // Áp dụng các bộ lọc
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    $query->where("{$this->table}.{$field}", $value);
                }
            }
        }
        
        // Áp dụng tìm kiếm
        if (isset($criteria['search']) && $criteria['search'] !== '') {
            $searchQuery = $criteria['search'];
            $query->groupStart();
            
            foreach ($this->searchableFields as $field => $options) {
                $query->orLike("{$this->table}.{$field}", $searchQuery);
            }
            
            $query->groupEnd();
        }
        
        return $query->countAllResults();
    }
} 