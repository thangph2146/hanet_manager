<?php

namespace App\Modules\template\Models;

use App\Models\BaseModel;
use App\Modules\template\Entities\Template;
use App\Modules\template\Libraries\Pager;

class TemplateModel extends BaseModel
{
    protected $table = 'template';
    protected $primaryKey = 'template_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ten_template',
        'ma_template',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = Template::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_template',
        'ma_template'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Template pager
    protected $Pager = null;
    
    /**
     * Lấy tất cả template
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'ten_template', $order = 'ASC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('deleted_at', null);
        $this->builder->where('bin', 0);
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        $total = $this->countAll();
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
     * Đếm tổng số template không nằm trong thùng rác
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('bin', 0);
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy tất cả template đang hoạt động
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllActive(int $limit = 10, int $offset = 0, string $sort = 'ten_template', string $order = 'ASC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('deleted_at', null);
        $this->builder->where('bin', 0);
        $this->builder->where('status', 1);
        
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
     * Đếm tổng số template đang hoạt động
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllActive($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('bin', 0);
        $builder->where('status', 1);
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy tất cả template trong thùng rác
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllInRecycleBin(int $limit = 10, int $offset = 0, string $sort = 'updated_at', string $order = 'DESC')
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('bin', 1);
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        $total = $this->countAllInRecycleBin();
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
     * Đếm tổng số template trong thùng rác
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllInRecycleBin($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('bin', 1);
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm template
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn tìm kiếm (limit, offset, relations, sort, order)
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        if ($this->useSoftDeletes) {
            $this->builder->where($this->table . '.' . $this->deletedField, null);
        }
        
        $defaultOptions = [
            'limit' => 10,
            'offset' => 0,
            'sort' => 'ten_template',
            'order' => 'ASC'
        ];
        
        $options = array_merge($defaultOptions, $options);
        
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
        
        if (isset($criteria['status']) || array_key_exists('status', $criteria)) {
            $status = (int)$criteria['status'];
            $this->builder->where($this->table . '.status', $status);
        }
        
        $bin = isset($criteria['bin']) ? (int)$criteria['bin'] : 0;
        $this->builder->where($this->table . '.bin', $bin);
        
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
        
        if ($this->useSoftDeletes) {
            $builder->where($this->table . '.' . $this->deletedField, null);
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
        
        $bin = isset($params['bin']) ? (int)$params['bin'] : 0;
        $builder->where($this->table . '.bin', $bin);
        
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
        $entity = new Template();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        if ($scenario === 'update' && isset($data['template_id'])) {
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace('{template_id}', $data['template_id'], $rules);
                }
            }
        } elseif ($scenario === 'insert') {
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace(',template_id,{template_id}', '', $rules);
                }
            }
        }
    }
    
    /**
     * Chuyển một template vào thùng rác
     *
     * @param int $id ID của template cần chuyển vào thùng rác
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function moveToRecycleBin($id)
    {
        $template = $this->find($id);
        
        if (!$template) {
            return false;
        }
        
        $template->bin = 1;
        return $this->save($template);
    }
    
    /**
     * Khôi phục template từ thùng rác
     *
     * @param int $id ID của template cần khôi phục
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function restoreFromRecycleBin($id)
    {
        $template = $this->find($id);
        
        if (!$template) {
            log_message('error', "Không tìm thấy template với ID: {$id}");
            return false;
        }
        
        try {
            $template->bin = 0;
            $success = $this->save($template);
            
            if (!$success) {
                log_message('error', "Lỗi khi lưu template: " . print_r($this->errors(), true));
            }
            
            return $success;
        } catch (\Exception $e) {
            log_message('error', "Ngoại lệ khi khôi phục template: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra xem tên template đã tồn tại chưa
     *
     * @param string $name Tên template cần kiểm tra
     * @param int|null $exceptId ID template để loại trừ khỏi việc kiểm tra (hữu ích khi cập nhật)
     * @return bool Trả về true nếu tên đã tồn tại, false nếu chưa
     */
    public function isNameExists(string $name, ?int $exceptId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ten_template', $name);
        
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField, null);
        }
        
        return $builder->countAllResults() > 0;
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
} 