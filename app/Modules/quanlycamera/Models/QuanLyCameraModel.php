<?php

namespace App\Modules\quanlycamera\Models;

use App\Models\BaseModel;
use App\Modules\quanlycamera\Entities\QuanLyCamera;
use App\Modules\quanlycamera\Libraries\Pager;
use CodeIgniter\I18n\Time;

class QuanLyCameraModel extends BaseModel
{
    protected $table = 'camera';
    protected $primaryKey = 'camera_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'ten_camera',
        'ma_camera',
        'ip_camera',
        'port',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = QuanLyCamera::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_camera',
        'ma_camera',
        'ip_camera'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'status'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Template pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi camera
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
        $this->builder->where($this->table . '.deleted_at IS NULL');
        
        if ($sort && $order) {
            $this->builder->orderBy($this->table . '.' . $sort, $order);
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
     * Lấy tất cả bản ghi camera đã xóa
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
        $this->builder->where($this->table . '.deleted_at IS NOT NULL');
        
        if ($sort && $order) {
            $this->builder->orderBy($this->table . '.' . $sort, $order);
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
     * Đếm tổng số bản ghi camera
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
     * Đếm tổng số bản ghi camera đã xóa
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllDeleted($conditions = [])
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
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
        $this->builder->where($this->table . '.status', 1);
        $this->builder->where($this->table . '.deleted_at IS NULL');
        
        if ($sort && $order) {
            $this->builder->orderBy($this->table . '.' . $sort, $order);
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
     * Đếm tổng số bản ghi đang hoạt động
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllActive($conditions = [])
    {
        $builder = $this->builder();
        $builder->where($this->table . '.status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm bản ghi camera theo tiêu chí và tùy chọn
     * 
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Các tùy chọn (sắp xếp, giới hạn, phân trang, ...)
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        // Khởi tạo các giá trị mặc định
        $defaultOptions = [
            'sort' => 'created_at',
            'order' => 'DESC',
            'page' => 1,
            'perPage' => 10
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        // Tính offset dựa trên trang và perPage
        $offset = ($options['page'] - 1) * $options['perPage'];
        
        // Khởi tạo builder
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Thêm điều kiện tìm kiếm từ criteria
        if (!empty($criteria)) {
            foreach ($criteria as $field => $value) {
                if (is_array($value)) {
                    // Nếu là operator
                    if (isset($value['value'])) {
                        if ($value['operator'] === 'LIKE') {
                            $this->builder->like($this->table . '.' . $field, $value['value']);
                        } else if ($value['operator'] === 'OR_LIKE') {
                            $this->builder->orLike($this->table . '.' . $field, $value['value']);
                        } else {
                            $this->builder->where($this->table . '.' . $field . ' ' . $value['operator'], $value['value']);
                        }
                    } else {
                        // Nếu là mảng giá trị
                        $this->builder->whereIn($this->table . '.' . $field, $value);
                    }
                } else {
                    $this->builder->where($this->table . '.' . $field, $value);
                }
            }
        }
        
        // Thêm điều kiện chỉ lấy các bản ghi không bị xóa (mặc định nếu không có điều kiện ngược lại)
        if (!isset($criteria['deleted_at']) && !isset($criteria['show_deleted'])) {
            $this->builder->where($this->table . '.deleted_at IS NULL');
        }
        
        // Đếm tổng số kết quả
        $countBuilder = clone $this->builder;
        $total = $countBuilder->countAllResults();
        
        // Thêm sắp xếp
        if (!empty($options['sort']) && !empty($options['order'])) {
            $this->builder->orderBy($this->table . '.' . $options['sort'], $options['order']);
        }
        
        // Thiết lập phân trang
        $currentPage = $offset / $options['perPage'] + 1;
        
        if ($this->pager === null) {
            $this->pager = new Pager($total, $options['perPage'], $currentPage);
        } else {
            $this->pager->setTotal($total)
                      ->setPerPage($options['perPage'])
                      ->setCurrentPage($currentPage);
        }
        
        $this->builder->limit($options['perPage'], $offset);
        $result = $this->builder->get()->getResult($this->returnType);
        
        return $result ?: [];
    }
    
    /**
     * Đếm số bản ghi theo tiêu chí tìm kiếm
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @return int
     */
    public function countSearchResults(array $criteria = [])
    {
        $builder = $this->builder();
        
        if (!empty($criteria)) {
            foreach ($criteria as $field => $value) {
                if (is_array($value)) {
                    // Nếu là operator
                    if (isset($value['value'])) {
                        if ($value['operator'] === 'LIKE') {
                            $builder->like($this->table . '.' . $field, $value['value']);
                        } else if ($value['operator'] === 'OR_LIKE') {
                            $builder->orLike($this->table . '.' . $field, $value['value']);
                        } else {
                            $builder->where($this->table . '.' . $field . ' ' . $value['operator'], $value['value']);
                        }
                    } else {
                        // Nếu là mảng giá trị
                        $builder->whereIn($this->table . '.' . $field, $value);
                    }
                } else {
                    $builder->where($this->table . '.' . $field, $value);
                }
            }
        }
        
        // Thêm điều kiện chỉ lấy các bản ghi không bị xóa (mặc định nếu không có điều kiện ngược lại)
        if (!isset($criteria['deleted_at']) && !isset($criteria['show_deleted'])) {
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên kịch bản
     *
     * @param string $scenario Kịch bản ('insert' hoặc 'update')
     * @param array $data Dữ liệu cần xác thực
     * @return void
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        // Quy tắc chung cho cả thêm mới và cập nhật
        $this->validationRules = [
            'ten_camera' => 'required|max_length[255]',
            'ma_camera' => 'permit_empty|max_length[20]',
            'ip_camera' => 'permit_empty|max_length[100]|valid_ip',
            'port' => 'permit_empty|integer|greater_than[0]|less_than[65536]',
            'status' => 'required|in_list[0,1]'
        ];
        
        $this->validationMessages = [
            'ten_camera' => [
                'required' => 'Tên camera không được để trống',
                'max_length' => 'Tên camera không được vượt quá {param} ký tự',
                'is_unique' => 'Tên camera này đã tồn tại, vui lòng chọn tên khác'
            ],
            'ma_camera' => [
                'max_length' => 'Mã camera không được vượt quá {param} ký tự'
            ],
            'ip_camera' => [
                'max_length' => 'IP camera không được vượt quá {param} ký tự',
                'valid_ip' => 'IP camera không hợp lệ'
            ],
            'port' => [
                'integer' => 'Port phải là số nguyên',
                'greater_than' => 'Port phải lớn hơn {param}',
                'less_than' => 'Port phải nhỏ hơn {param}'
            ],
            'status' => [
                'required' => 'Trạng thái không được để trống',
                'in_list' => 'Trạng thái không hợp lệ'
            ]
        ];
        
        // Xử lý quy tắc is_unique cho kịch bản UPDATE
        if ($scenario === 'insert') {
            $this->validationRules['ten_camera'] .= '|is_unique[' . $this->table . '.ten_camera]';
        } else if ($scenario === 'update' && !empty($data[$this->primaryKey])) {
            $this->validationRules['ten_camera'] .= '|is_unique[' . $this->table . '.ten_camera,' . $this->primaryKey . ',' . $data[$this->primaryKey] . ']';
        }
    }
    
    /**
     * Thiết lập số lượng liên kết trang hiển thị xung quanh trang hiện tại
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
     * Lấy đối tượng pager
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
     * @param mixed $id ID của bản ghi
     * @param array $relations Các quan hệ cần load
     * @param bool $validate Xác thực dữ liệu trước khi trả về hay không
     * @return object|null
     */
    public function findWithRelations($id, $relations = [], $validate = false)
    {
        $data = $this->find($id);
        
        if (!$data) {
            return null;
        }
        
        // Xử lý các quan hệ nếu có
        
        return $data;
    }
    
    /**
     * Tìm kiếm các bản ghi đã xóa theo tiêu chí
     * 
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        // Đảm bảo sử dụng withDeleted để lấy cả bản ghi đã xóa
        $this->withDeleted();
        
        // Thêm tiêu chí deleted_at IS NOT NULL
        $criteria['deleted'] = true;
        
        // Khởi tạo các giá trị mặc định cho options
        $defaultOptions = [
            'sort' => 'deleted_at',
            'order' => 'DESC',
            'page' => 1,
            'perPage' => 10
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        // Tính offset dựa trên trang và perPage
        $offset = ($options['page'] - 1) * $options['perPage'];
        
        // Khởi tạo builder
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Thêm điều kiện để chỉ lấy bản ghi đã xóa
        $this->builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Thêm điều kiện tìm kiếm từ criteria
        if (!empty($criteria['keyword'])) {
            // Đảm bảo keyword là chuỗi
            $keyword = is_array($criteria['keyword']) ? json_encode($criteria['keyword']) : $criteria['keyword'];
            
            // Ghi log để debug
            log_message('debug', '[QuanLyCameraModel::searchDeleted] Keyword type: ' . gettype($keyword) . ', value: ' . $keyword);
            
            $this->builder->groupStart();
            
            foreach ($this->searchableFields as $field) {
                $this->builder->orLike($this->table . '.' . $field, $keyword);
            }
            
            $this->builder->groupEnd();
        }
        
        // Thêm điều kiện lọc
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    $this->builder->where($this->table . '.' . $field, $value);
                }
            }
        }
        
        // Đếm tổng số kết quả
        $total = $this->builder->countAllResults(false);
        
        // Sắp xếp
        if (!empty($options['sort']) && !empty($options['order'])) {
            $this->builder->orderBy($this->table . '.' . $options['sort'], $options['order']);
        }
        
        // Phân trang
        $this->builder->limit($options['perPage'], $offset);
        
        // Lấy dữ liệu
        $result = $this->builder->get()->getResult($this->returnType);
        
        // Tạo pager
        $currentPage = $options['page'];
        if ($this->pager === null) {
            $this->pager = new Pager($total, $options['perPage'], $currentPage);
        } else {
            $this->pager->setTotal($total)
                        ->setPerPage($options['perPage'])
                        ->setCurrentPage($currentPage);
        }
        
        return $result ?: [];
    }
    
    /**
     * Đếm số bản ghi đã xóa theo tiêu chí
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedResults(array $criteria = [])
    {
        // Đảm bảo sử dụng withDeleted để tính cả bản ghi đã xóa
        $this->withDeleted();
        
        // Khởi tạo builder
        $builder = $this->builder();
        
        // Thêm điều kiện deleted_at IS NOT NULL
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Thêm điều kiện tìm kiếm từ criteria
        if (!empty($criteria['keyword'])) {
            // Đảm bảo keyword là chuỗi
            $keyword = is_array($criteria['keyword']) ? json_encode($criteria['keyword']) : $criteria['keyword'];
            
            $builder->groupStart();
            
            foreach ($this->searchableFields as $field) {
                $builder->orLike($this->table . '.' . $field, $keyword);
            }
            
            $builder->groupEnd();
        }
        
        // Thêm điều kiện lọc
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    $builder->where($this->table . '.' . $field, $value);
                }
            }
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Kiểm tra xem tên camera đã tồn tại hay chưa
     * 
     * @param string $tenCamera Tên camera cần kiểm tra
     * @param int|null $excludeId ID để loại trừ khi kiểm tra (cho trường hợp cập nhật)
     * @return bool
     */
    public function isTenCameraExists(string $tenCamera, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ten_camera', $tenCamera);
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Lấy dữ liệu có phân trang
     */
    public function getPaginatedData($page = 1, $perPage = 10, $keyword = '', $status = '')
    {
        $builder = $this->builder();

        // Tìm kiếm theo từ khóa
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('ten_camera', $keyword)
                ->orLike('ma_camera', $keyword)
                ->groupEnd();
        }

        // Lọc theo trạng thái
        if ($status !== '') {
            $builder->where('status', $status);
        }

        // Tổng số bản ghi
        $total = $builder->countAllResults(false);

        // Lấy dữ liệu theo trang
        $start = ($page - 1) * $perPage;
        $data = $builder->get($perPage, $start)->getResult($this->returnType);

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    /**
     * Chỉ lấy các bản ghi đã xóa
     * 
     * @return $this
     */
    public function onlyDeleted()
    {
        // Ghi log để debug
        log_message('debug', '[QuanLyCameraModel::onlyDeleted] Getting only deleted records');
        
        $this->builder = $this->builder();
        $this->builder->where($this->table . '.deleted_at IS NOT NULL');
        
        return $this;
    }
    
    /**
     * Tìm bản ghi đã xóa theo ID
     * 
     * @param mixed $id ID của bản ghi cần tìm
     * @return object|null
     */
    public function findDeleted($id)
    {
        // Ghi log để debug
        log_message('debug', '[QuanLyCameraModel::findDeleted] Finding deleted record with ID: ' . $id);
        
        // Sử dụng withDeleted để tìm cả bản ghi đã xóa
        $this->withDeleted();
        
        // Tìm bản ghi theo ID và chỉ lấy những bản ghi đã xóa
        $this->builder = $this->builder();
        $this->builder->where($this->primaryKey, $id);
        $this->builder->where($this->table . '.deleted_at IS NOT NULL');
        
        $result = $this->builder->get()->getRow();
        
        if ($result) {
            // Chuyển đổi kết quả thành Entity nếu cần
            if ($this->returnType === $this->returnType) {
                $entityClass = $this->returnType;
                $entity = new $entityClass();
                $entity->fill((array)$result);
                return $entity;
            }
            
            return $result;
        }
        
        return null;
    }

    /**
     * Khôi phục bản ghi từ thùng rác (đặt deleted_at = null)
     * 
     * @param int $id ID của bản ghi cần khôi phục
     * @return bool Kết quả khôi phục
     */
    public function restoreFromTrash($id)
    {
        // Ghi log để debug
        log_message('debug', '[QuanLyCameraModel::restoreFromTrash] Restoring record with ID: ' . $id);
        
        // Sử dụng query builder trực tiếp để cập nhật deleted_at = null
        $builder = $this->db->table($this->table);
        $builder->set($this->deletedField, null);
        $builder->where($this->primaryKey, $id);
        
        $result = $builder->update();
        
        log_message('debug', '[QuanLyCameraModel::restoreFromTrash] Update result: ' . ($result ? 'success' : 'failed'));
        
        return $result;
    }

    /**
     * Khôi phục nhiều bản ghi từ thùng rác
     * 
     * @param array $ids Mảng ID các bản ghi cần khôi phục
     * @return int Số bản ghi đã khôi phục thành công
     */
    public function restoreMultipleFromTrash(array $ids)
    {
        if (empty($ids)) {
            return 0;
        }
        
        // Ghi log để debug
        log_message('debug', '[QuanLyCameraModel::restoreMultipleFromTrash] Restoring records with IDs: ' . json_encode($ids));
        
        // Sử dụng query builder trực tiếp để cập nhật deleted_at = null
        $builder = $this->db->table($this->table);
        $builder->set($this->deletedField, null);
        $builder->whereIn($this->primaryKey, $ids);
        
        $result = $builder->update();
        $affectedRows = $this->db->affectedRows();
        
        log_message('debug', '[QuanLyCameraModel::restoreMultipleFromTrash] Affected rows: ' . $affectedRows);
        
        return $affectedRows;
    }

} 