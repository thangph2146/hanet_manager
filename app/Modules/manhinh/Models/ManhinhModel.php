<?php

namespace App\Modules\manhinh\Models;

use App\Models\BaseModel;
use App\Modules\manhinh\Entities\ManHinh;
use App\Modules\manhinh\Libraries\ManHinhPager;

class ManhinhModel extends BaseModel
{
    protected $table = 'man_hinh';
    protected $primaryKey = 'man_hinh_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ten_man_hinh',
        'ma_man_hinh',
        'camera_id',
        'template_id',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = ManHinh::class;
    
    // Khai báo quan hệ với bảng khác
    protected $relationships = [
        'camera' => [
            'model' => 'App\Modules\camera\Models\CameraModel',
            'type' => 'belongsTo',
            'foreignKey' => 'camera_id'
        ],
        'template' => [
            'model' => 'App\Modules\template\Models\TemplateModel',
            'type' => 'belongsTo',
            'foreignKey' => 'template_id'
        ]
    ];
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_man_hinh',
        'ma_man_hinh'
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
    
    // Manhinh pager
    protected $manhinhPager = null;
    
    /**
     * Lấy tất cả màn hình
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'ten_man_hinh', $order = 'ASC')
    {
        // Reset query builder để đảm bảo không có điều kiện nào từ trước
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('deleted_at', null);
        $this->builder->where('bin', 0);
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        // Lấy tổng số bản ghi để cấu hình pagination
        $total = $this->countAll();
        
        // Tính toán trang hiện tại từ offset và limit
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        // Khởi tạo ManhinhPager nếu chưa có
        if ($this->manhinhPager === null) {
            $this->manhinhPager = new ManhinhPager($total, $limit, $currentPage);
        } else {
            $this->manhinhPager->setTotal($total)
                             ->setPerPage($limit)
                             ->setCurrentPage($currentPage);
        }
        
        // Lấy dữ liệu với phân trang
        $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Đảm bảo kết quả được trả về dù không có dữ liệu
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số màn hình không nằm trong thùng rác
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('bin', 0);
        
        // Áp dụng điều kiện bổ sung nếu có
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy màn hình theo ID với các quan hệ
     * 
     * @param int $id ID của màn hình
     * @return object|null
     */
    public function findWithRelations($id)
    {
        // Lấy thông tin màn hình
        $manhinh = $this->find($id);
        
        // Nếu không tìm thấy, trả về null
        if (!$manhinh) {
            return null;
        }
        
        // Load các quan hệ
        foreach ($this->relationships as $relName => $relData) {
            $relModel = model($relData['model']);
            
            if ($relData['type'] === 'belongsTo' && isset($manhinh->{$relData['foreignKey']})) {
                $relatedId = $manhinh->{$relData['foreignKey']};
                if ($relatedId) {
                    $manhinh->{$relName} = $relModel->find($relatedId);
                }
            } elseif ($relData['type'] === 'hasMany') {
                $manhinh->{$relName} = $relModel->where($relData['foreignKey'], $manhinh->{$this->primaryKey})
                                              ->where('bin', 0)
                                              ->findAll();
            }
        }
        
        return $manhinh;
    }
    
    /**
     * Tìm kiếm màn hình với các tùy chọn
     * 
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Các tùy chọn bổ sung
     * @return array
     */
    public function search($criteria = [], $options = [])
    {
        // Mặc định sử dụng query builder của model
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Thiết lập tùy chọn mặc định
        $defaultOptions = [
            'limit' => 10,
            'offset' => 0,
            'sort' => 'ten_man_hinh',
            'order' => 'ASC'
        ];
        
        // Merge options
        $options = array_merge($defaultOptions, $options);
        
        // Log đầy đủ tham số tìm kiếm và tùy chọn
        log_message('debug', 'Tham số tìm kiếm và tùy chọn đầy đủ:');
        log_message('debug', 'Tham số tìm kiếm: ' . json_encode($criteria));
        log_message('debug', 'Tùy chọn: ' . json_encode($options));
        
        // Xử lý từ khóa tìm kiếm
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            // Sử dụng LIKE chính xác
            $this->builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $this->builder->like($field, $keyword);
                } else {
                    $this->builder->orLike($field, $keyword);
                }
            }
            $this->builder->groupEnd();
            
            log_message('debug', 'Từ khóa tìm kiếm: ' . $keyword);
        }
        
        // Xử lý trạng thái
        if (isset($criteria['status']) && $criteria['status'] !== '' && $criteria['status'] !== null) {
            $status = (int)$criteria['status'];
            $this->builder->where('status', $status);
            log_message('debug', 'Lọc theo trạng thái: ' . $status);
        }
        
        // Xử lý trạng thái bin (thùng rác)
        $bin = isset($criteria['bin']) ? (int)$criteria['bin'] : 0;
        $this->builder->where('bin', $bin);
        
        // Sắp xếp kết quả
        if (!empty($options['sort']) && !empty($options['order'])) {
            $this->builder->orderBy($options['sort'], $options['order']);
            log_message('debug', 'Sắp xếp: ' . $options['sort'] . ' ' . $options['order']);
        }
        
        // Thiết lập phân trang
        if ($options['limit'] > 0) {
            // Tổng số kết quả để cấu hình phân trang
            $countBuilder = clone $this->builder;
            $total = $countBuilder->countAllResults();
            log_message('debug', 'Tổng số kết quả tìm kiếm: ' . $total);
            
            // Tính toán trang hiện tại từ offset và limit
            $currentPage = $options['limit'] > 0 ? floor($options['offset'] / $options['limit']) + 1 : 1;
            
            // Tạo đối tượng phân trang
            if ($this->manhinhPager === null) {
                $this->manhinhPager = new ManhinhPager($total, $options['limit'], $currentPage);
            } else {
                $this->manhinhPager->setTotal($total)
                                ->setPerPage($options['limit'])
                                ->setCurrentPage($currentPage);
            }
            
            // Chỉ áp dụng limit nếu có yêu cầu
            $this->builder->limit($options['limit'], $options['offset']);
            log_message('debug', 'Giới hạn: ' . $options['limit'] . ', Vị trí bắt đầu: ' . $options['offset']);
            
            // Lấy dữ liệu
            $result = $this->builder->get()->getResult($this->returnType);
            return $result ?: [];
        }
        
        return $this->findAll();
    }
    
    /**
     * Đếm tổng số màn hình đang hoạt động
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllActive($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('bin', 0);
        $builder->where('status', 1);
        
        // Áp dụng điều kiện bổ sung nếu có
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy tất cả màn hình trong thùng rác
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllInRecycleBin(int $limit = 10, int $offset = 0, string $sort = 'updated_at', string $order = 'DESC')
    {
        // Reset query builder để đảm bảo không có điều kiện nào từ trước
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('bin', 1);
        
        // Thiết lập sắp xếp
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        // Lấy tổng số bản ghi để cấu hình pagination
        $total = $this->countAllInRecycleBin();
        
        // Tính toán trang hiện tại từ offset và limit
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        // Khởi tạo ManhinhPager nếu chưa có
        if ($this->manhinhPager === null) {
            $this->manhinhPager = new ManhinhPager($total, $limit, $currentPage);
        } else {
            $this->manhinhPager->setTotal($total)
                            ->setPerPage($limit)
                            ->setCurrentPage($currentPage);
        }
        
        // Lấy dữ liệu với phân trang
        $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Đảm bảo kết quả được trả về dù không có dữ liệu
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số màn hình trong thùng rác
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllInRecycleBin($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('bin', 1);
        
        // Áp dụng điều kiện bổ sung nếu có
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Đếm kết quả tìm kiếm
     * 
     * @param array $params Các tham số tìm kiếm
     * @return int
     */
    public function countSearchResults($params = [])
    {
        $builder = $this->builder();
        
        // Chỉ đếm nếu có từ khóa tìm kiếm
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $keyword = trim($params['keyword']);
            log_message('debug', 'Count: Từ khóa tìm kiếm: ' . $keyword);
            
            // Tìm kiếm với các trường có thể tìm kiếm
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
        
        // Lọc theo status nếu được chỉ định
        if (isset($params['status']) && ($params['status'] !== null && $params['status'] !== '')) {
            $status = (int)$params['status'];
            $builder->where($this->table . '.status', $status);
            log_message('debug', 'Count: Giá trị status sau khi ép kiểu: ' . $status);
        }
        
        // Mặc định không đếm bản ghi trong thùng rác, trừ khi chỉ định rõ
        $bin = isset($params['bin']) ? (int)$params['bin'] : 0;
        $builder->where($this->table . '.bin', $bin);
        
        // Log câu lệnh SQL để debug
        $sqlCount = $builder->getCompiledSelect(false);
        log_message('debug', 'Count: SQL Query để đếm: ' . $sqlCount);
        
        $count = $builder->countAllResults();
        log_message('debug', 'Count: Tổng số bản ghi tìm thấy: ' . $count);
        
        return $count;
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên ngữ cảnh
     *
     * @param string $scenario Tình huống xác thực ('insert' hoặc 'update')
     * @param array $data Dữ liệu cần xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $entity = new ManHinh();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại bỏ các quy tắc validate cho trường thời gian (vì chúng được tự động xử lý bởi model)
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        // Điều chỉnh quy tắc dựa trên tình huống
        if ($scenario === 'update' && isset($data['man_hinh_id'])) {
            // Khi cập nhật, cần loại trừ chính ID hiện tại khi kiểm tra tính duy nhất
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    // Thay thế placeholder {man_hinh_id} bằng ID thực tế
                    $rules = str_replace('{man_hinh_id}', $data['man_hinh_id'], $rules);
                }
            }
        } elseif ($scenario === 'insert') {
            // Khi thêm mới, bỏ loại trừ ID vì không có ID nào cần loại trừ
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace(',man_hinh_id,{man_hinh_id}', '', $rules);
                }
            }
        }
    }
    
    /**
     * Chuyển một màn hình vào thùng rác
     *
     * @param mixed $id ID của màn hình hoặc mảng các ID
     * @return bool
     */
    public function moveToRecycleBin($id)
    {
        if (is_array($id)) {
            $this->db->transStart();
            foreach ($id as $item) {
                $manhinh = $this->find($item);
                if ($manhinh) {
                    $manhinh->bin = 1;
                    $this->save($manhinh);
                }
            }
            $this->db->transComplete();
            return $this->db->transStatus();
        } else {
            $manhinh = $this->find($id);
            if ($manhinh) {
                $manhinh->bin = 1;
                return $this->save($manhinh);
            }
        }
        
        return false;
    }
    
    /**
     * Khôi phục màn hình từ thùng rác
     * 
     * @param mixed $id ID của màn hình hoặc mảng các ID
     * @return bool
     */
    public function restoreFromRecycleBin($id)
    {
        if (is_array($id)) {
            $this->db->transStart();
            foreach ($id as $item) {
                $manhinh = $this->find($item);
                if ($manhinh) {
                    $manhinh->bin = 0;
                    $this->save($manhinh);
                }
            }
            $this->db->transComplete();
            return $this->db->transStatus();
        } else {
            $manhinh = $this->find($id);
            if ($manhinh) {
                $manhinh->bin = 0;
                return $this->save($manhinh);
            }
        }
        
        return false;
    }
    
    /**
     * Kiểm tra xem tên màn hình đã tồn tại chưa
     * 
     * @param string $name Tên màn hình cần kiểm tra
     * @param int|null $excludeId ID cần loại trừ khi kiểm tra (cho trường hợp cập nhật)
     * @return bool
     */
    public function isNameExists(string $name, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ten_man_hinh', $name);
        $builder->where('bin', 0);
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        $count = $builder->countAllResults();
        return $count > 0;
    }
    
    /**
     * Lấy hoặc tạo Pager
     * 
     * @return \App\Modules\manhinh\Libraries\ManhinhPager|null
     */
    public function getPager()
    {
        return $this->manhinhPager;
    }
    
    /**
     * Thiết lập số lượng liên kết trang xung quanh trang hiện tại
     * 
     * @param int $count
     */
    public function setSurroundCount(int $count)
    {
        if ($this->manhinhPager) {
            $this->manhinhPager->setSurroundCount($count);
        }
    }
    
    /**
     * Lấy các quy tắc validation hiện tại
     * 
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }
} 