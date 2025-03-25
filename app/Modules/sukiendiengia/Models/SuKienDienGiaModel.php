<?php

namespace App\Modules\sukiendiengia\Models;

use App\Models\BaseModel;
use App\Modules\sukiendiengia\Entities\SuKienDienGia;
use App\Modules\sukiendiengia\Libraries\Pager;
use CodeIgniter\I18n\Time;

class SuKienDienGiaModel extends BaseModel
{
    protected $table = 'su_kien_dien_gia';
    protected $primaryKey = ['su_kien_id', 'dien_gia_id'];
    protected $useAutoIncrement = false;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'su_kien_id',
        'dien_gia_id',
        'thu_tu',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = SuKienDienGia::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'su_kien_id',
        'dien_gia_id',
        'thu_tu'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'su_kien_id',
        'dien_gia_id'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Template pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi sự kiện diễn giả
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'thu_tu', $order = 'ASC')
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
     * Lấy tất cả bản ghi sự kiện diễn giả đã xóa
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
     * Đếm tổng số bản ghi sự kiện diễn giả
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
     * Đếm tổng số bản ghi sự kiện diễn giả đã xóa
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
     * Lấy tất cả bản ghi chưa lưu trữ
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllActive(int $limit = 10, int $offset = 0, string $sort = 'thu_tu', string $order = 'ASC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
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
        
        $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số bản ghi sự kiện diễn giả chưa lưu trữ
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllActive($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('deleted_at IS NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm bản ghi
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Các tùy chọn tìm kiếm
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Mặc định chỉ lấy bản ghi chưa xóa
        $this->builder->where('deleted_at IS NULL');
        
        // Xử lý tìm kiếm theo từ khóa
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $this->builder->groupStart();
            
            // Tìm kiếm trong các trường có thể tìm kiếm
            foreach ($this->searchableFields as $field) {
                $this->builder->orLike($field, $keyword);
            }
            $this->builder->groupEnd();
        }
        
        // Lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && !empty($criteria['su_kien_id'])) {
            $this->builder->where('su_kien_id', $criteria['su_kien_id']);
        }
        
        // Lọc theo diễn giả
        if (isset($criteria['dien_gia_id']) && !empty($criteria['dien_gia_id'])) {
            $this->builder->where('dien_gia_id', $criteria['dien_gia_id']);
        }
        
        // Tổng số bản ghi phù hợp
        $total = $this->countSearchResults($criteria);
        
        // Xử lý limit, offset
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        if ($limit > 0) {
            $this->builder->limit($limit, $offset);
        }
        
        // Xử lý sắp xếp
        $sort = $options['sort'] ?? 'thu_tu';
        $order = $options['order'] ?? 'ASC';
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        // Cấu hình phân trang
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        if ($this->pager === null) {
            $this->pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->pager->setTotal($total)
                        ->setPerPage($limit)
                        ->setCurrentPage($currentPage);
        }
        
        $result = $this->builder->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Đếm số lượng kết quả tìm kiếm
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @return int
     */
    public function countSearchResults(array $criteria = [])
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm bản ghi chưa xóa
        $builder->where('deleted_at IS NULL');
        
        // Xử lý tìm kiếm theo từ khóa
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $builder->groupStart();
            
            // Tìm kiếm trong các trường có thể tìm kiếm
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $keyword);
            }
            $builder->groupEnd();
        }
        
        // Lọc theo sự kiện
        if (isset($criteria['su_kien_id']) && !empty($criteria['su_kien_id'])) {
            $builder->where('su_kien_id', $criteria['su_kien_id']);
        }
        
        // Lọc theo diễn giả
        if (isset($criteria['dien_gia_id']) && !empty($criteria['dien_gia_id'])) {
            $builder->where('dien_gia_id', $criteria['dien_gia_id']);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị quy tắc xác thực
     *
     * @param string $scenario Kịch bản xác thực ('insert', 'update')
     * @param array $data Dữ liệu cần xác thực
     * @return array
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $rules = [
            'su_kien_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID sự kiện là bắt buộc',
                ]
            ],
            'dien_gia_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ID diễn giả là bắt buộc',
                ]
            ],
            'thu_tu' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Thứ tự phải là số nguyên',
                ]
            ]
        ];
        
        return $rules;
    }
    
    /**
     * Đặt số lượng liên kết hiển thị xung quanh trang hiện tại
     *
     * @param int $count
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
     * Tìm bản ghi và các quan hệ
     *
     * @param array|int $id
     * @param array $relations Các quan hệ cần lấy
     * @param bool $validate Có xác thực không tồn tại?
     * @return object|null
     */
    public function findWithRelations($id, $relations = [], $validate = false)
    {
        return $this->find($id, $validate);
    }
    
    /**
     * Tìm kiếm bản ghi đã xóa
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Các tùy chọn tìm kiếm
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('deleted_at IS NOT NULL');
        
        // Xử lý các tiêu chí tìm kiếm
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $this->builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $this->builder->orLike($field, $keyword);
            }
            $this->builder->groupEnd();
        }
        
        return $this->processSearchOptionsAndGetResults($options);
    }
    
    /**
     * Đếm số lượng kết quả xóa
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedResults(array $criteria = [])
    {
        $builder = $this->builder();
        $builder->where('deleted_at IS NOT NULL');
        
        // Xử lý các tiêu chí tìm kiếm
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $keyword);
            }
            $builder->groupEnd();
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Xử lý trước khi chèn
     *
     * @param array $data
     * @return array
     */
    protected function beforeInsert(array $data): array
    {
        $data = parent::beforeInsert($data);
        
        // Đảm bảo thu_tu có giá trị mặc định
        if (!isset($data['data']['thu_tu'])) {
            $data['data']['thu_tu'] = 0;
        }
        
        return $data;
    }
    
    /**
     * Xử lý trước khi cập nhật
     *
     * @param array $data
     * @return array
     */
    protected function beforeUpdate(array $data): array
    {
        $data = parent::beforeUpdate($data);
        
        // Đảm bảo thu_tu có giá trị hợp lệ nếu được cung cấp
        if (isset($data['data']['thu_tu']) && !is_numeric($data['data']['thu_tu'])) {
            $data['data']['thu_tu'] = 0;
        }
        
        return $data;
    }
    
    /**
     * Định dạng ngày giờ
     *
     * @param string|null $datetime
     * @return string|null
     */
    protected function formatDateTime(?string $datetime): ?string
    {
        if (empty($datetime)) {
            return null;
        }
        
        try {
            if ($datetime instanceof Time) {
                return $datetime->format('Y-m-d H:i:s');
            }
            
            $time = new Time($datetime);
            return $time->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            log_message('error', 'Error formatting datetime: ' . $e->getMessage());
            return $datetime;
        }
    }
    
    /**
     * Kiểm tra xem diễn giả có thuộc sự kiện không
     *
     * @param int $dienGiaId
     * @param int $suKienId
     * @return bool
     */
    public function isDienGiaInSuKien(int $dienGiaId, int $suKienId): bool
    {
        return $this->where([
            'dien_gia_id' => $dienGiaId,
            'su_kien_id' => $suKienId,
            'deleted_at' => null
        ])->first() !== null;
    }
    
    /**
     * Lấy tất cả diễn giả của một sự kiện
     *
     * @param int $suKienId
     * @return array
     */
    public function getDienGiaBySuKien(int $suKienId): array
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->where('su_kien_id', $suKienId);
        $this->builder->where('deleted_at IS NULL');
        $this->builder->orderBy('thu_tu', 'ASC');
        
        $result = $this->builder->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Lấy tất cả sự kiện của một diễn giả
     *
     * @param int $dienGiaId
     * @return array
     */
    public function getSuKienByDienGia(int $dienGiaId): array
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->where('dien_gia_id', $dienGiaId);
        $this->builder->where('deleted_at IS NULL');
        $this->builder->orderBy('thu_tu', 'ASC');
        
        $result = $this->builder->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Xử lý các tùy chọn tìm kiếm và trả về kết quả
     *
     * @param array $options
     * @return array
     */
    protected function processSearchOptionsAndGetResults(array $options = [])
    {
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        $sort = $options['sort'] ?? 'thu_tu';
        $order = $options['order'] ?? 'ASC';
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        if ($limit > 0) {
            $this->builder->limit($limit, $offset);
        }
        
        $result = $this->builder->get()->getResult($this->returnType);
        return $result ?: [];
    }
} 