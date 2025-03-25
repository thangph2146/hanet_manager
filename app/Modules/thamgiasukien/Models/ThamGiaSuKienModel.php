<?php

namespace App\Modules\thamgiasukien\Models;

use App\Models\BaseModel;
use App\Modules\thamgiasukien\Entities\ThamGiaSuKien;
use App\Modules\thamgiasukien\Libraries\Pager;

class ThamGiaSuKienModel extends BaseModel
{
    protected $table = 'tham_gia_su_kien';
    protected $primaryKey = 'tham_gia_su_kien_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $allowedFields = [
        'nguoi_dung_id',
        'su_kien_id',
        'thoi_gian_diem_danh',
        'phuong_thuc_diem_danh',
        'ghi_chu',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = ThamGiaSuKien::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'nguoi_dung_id',
        'su_kien_id',
        'thoi_gian_diem_danh',
        'phuong_thuc_diem_danh',
        'ghi_chu'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin',
        'nguoi_dung_id',
        'su_kien_id',
        'phuong_thuc_diem_danh'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Template pager
    protected $Pager = null;
    
    /**
     * Lấy tất cả bản ghi tham gia sự kiện
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
        $this->builder->where('bin', 0);
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        $total = $this->countAll(['bin' => 0]);
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        if ($this->Pager === null) {
            $this->Pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->Pager->setTotal($total)
                        ->setPerPage($limit)
                        ->setCurrentPage($currentPage);
        }
        
        $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số bản ghi tham gia sự kiện
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        
        if (!isset($conditions['bin'])) {
            $conditions['bin'] = 0;
        }
        
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
        $this->builder->where('bin', 0);
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        $total = $this->countAllActive();
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        if ($this->Pager === null) {
            $this->Pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->Pager->setTotal($total)
                        ->setPerPage($limit)
                        ->setCurrentPage($currentPage);
        }
        
        if ($limit > 0) {
            $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
            return $result ?: [];
        }
        
        return $this->findAll();
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
        $builder->where('bin', 0);
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm bản ghi tham gia sự kiện
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn tìm kiếm (limit, offset, relations, sort, order)
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Mặc định chỉ lấy dữ liệu không nằm trong thùng rác
        if (!isset($criteria['bin'])) {
            $this->builder->where('bin', 0);
        }
        
        $defaultOptions = [
            'limit' => 10,
            'offset' => 0,
            'sort' => 'created_at',
            'order' => 'DESC'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        // Xử lý tìm kiếm theo từ khóa
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $this->builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $this->builder->like($field, $keyword);
                } else {
                    $this->builder->orLike($field, $keyword);
                }
            }
            $this->builder->groupEnd();
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status']) || array_key_exists('status', $criteria)) {
            $status = (int)$criteria['status'];
            $this->builder->where($this->table . '.status', $status);
        }
        
        // Xử lý lọc theo bin (thùng rác)
        if (isset($criteria['bin']) || array_key_exists('bin', $criteria)) {
            $bin = (int)$criteria['bin'];
            $this->builder->where($this->table . '.bin', $bin);
        }
        
        // Xử lý lọc theo người dùng
        if (isset($criteria['nguoi_dung_id'])) {
            $this->builder->where('nguoi_dung_id', $criteria['nguoi_dung_id']);
        }
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $this->builder->where('su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo phương thức điểm danh
        if (isset($criteria['phuong_thuc_diem_danh'])) {
            $this->builder->where('phuong_thuc_diem_danh', $criteria['phuong_thuc_diem_danh']);
        }
        
        if (!empty($options['sort']) && !empty($options['order'])) {
            $this->builder->orderBy($options['sort'], $options['order']);
        }
        
        $builderForCount = clone $this->builder;
        $total = $builderForCount->countAllResults();
        
        $currentPage = $options['limit'] > 0 ? floor($options['offset'] / $options['limit']) + 1 : 1;
        
        if ($this->Pager === null) {
            $this->Pager = new Pager($total, $options['limit'], $currentPage);
        } else {
            $this->Pager->setTotal($total)
                        ->setPerPage($options['limit'])
                        ->setCurrentPage($currentPage);
        }
        
        if ($options['limit'] > 0) {
            if ($options['offset'] >= $total) {
                $options['offset'] = 0;
                $currentPage = 1;
                $this->Pager->setCurrentPage($currentPage);
            }
            
            $this->builder->limit($options['limit'], $options['offset']);
            $result = $this->builder->get()->getResult($this->returnType);
            
            return $result ?: [];
        }
        
        return $this->findAll();
    }
    
    /**
     * Đếm số lượng kết quả tìm kiếm
     *
     * @param array $params Tham số tìm kiếm
     * @return int
     */
    public function countSearchResults(array $params)
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm dữ liệu không nằm trong thùng rác
        if (!isset($params['bin'])) {
            $builder->where('bin', 0);
        } else {
            $builder->where('bin', (int)$params['bin']);
        }
        
        if (!empty($params['keyword'])) {
            $keyword = trim($params['keyword']);
            
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
        
        if (isset($params['status']) || array_key_exists('status', $params)) {
            $status = (int)$params['status'];
            $builder->where($this->table . '.status', $status);
        }
        
        if (isset($params['nguoi_dung_id'])) {
            $builder->where('nguoi_dung_id', $params['nguoi_dung_id']);
        }
        
        if (isset($params['su_kien_id'])) {
            $builder->where('su_kien_id', $params['su_kien_id']);
        }
        
        if (isset($params['phuong_thuc_diem_danh'])) {
            $builder->where('phuong_thuc_diem_danh', $params['phuong_thuc_diem_danh']);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên ngữ cảnh
     *
     * @param string $scenario Tình huống xác thực ('insert' hoặc 'update')
     * @param array $data Dữ liệu cần xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $entity = new ThamGiaSuKien();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        if ($scenario === 'update' && isset($data['tham_gia_su_kien_id'])) {
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace('{tham_gia_su_kien_id}', $data['tham_gia_su_kien_id'], $rules);
                }
            }
        } elseif ($scenario === 'insert') {
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace(',tham_gia_su_kien_id,{tham_gia_su_kien_id}', '', $rules);
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
        if ($this->Pager !== null) {
            $this->Pager->setSurroundCount($count);
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
        return $this->Pager;
    }
    
    /**
     * Chuyển bản ghi vào thùng rác
     *
     * @param int $id ID của bản ghi cần chuyển vào thùng rác
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function moveToRecycleBin($id)
    {
        $record = $this->find($id);
        
        if (!$record) {
            return false;
        }
        
        $record->bin = true;
        return $this->save($record);
    }
    
    /**
     * Khôi phục bản ghi từ thùng rác
     *
     * @param int $id ID của bản ghi cần khôi phục
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function restoreFromRecycleBin($id)
    {
        $record = $this->find($id);
        
        if (!$record) {
            return false;
        }
        
        $record->bin = false;
        return $this->save($record);
    }
    
    /**
     * Tìm kiếm các bản ghi đã xóa (trong thùng rác)
     *
     * @param array $params Tham số tìm kiếm
     * @param array $options Tùy chọn tìm kiếm (limit, offset, sort, order)
     * @return array
     */
    public function searchDeleted(array $params = [], array $options = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->withDeleted();
        
        // Đảm bảo chỉ lấy các bản ghi trong thùng rác
        $params['bin'] = 1;
        
        // Sử dụng phương thức search hiện tại với tham số đã sửa đổi
        return $this->search($params, $options);
    }
    
    /**
     * Đếm số lượng bản ghi đã xóa theo tiêu chí tìm kiếm
     *
     * @param array $params Tham số tìm kiếm
     * @return int
     */
    public function countDeletedResults(array $params = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->withDeleted();
        
        // Đảm bảo chỉ đếm các bản ghi trong thùng rác
        $params['bin'] = 1;
        
        // Sử dụng phương thức countSearchResults hiện tại với tham số đã sửa đổi
        return $this->countSearchResults($params);
    }
} 