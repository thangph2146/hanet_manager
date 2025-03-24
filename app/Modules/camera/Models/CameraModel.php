<?php

namespace App\Modules\camera\Models;

use App\Models\BaseModel;
use App\Modules\camera\Entities\Camera;
use App\Modules\camera\Libraries\CameraPager;

class CameraModel extends BaseModel
{
    protected $table = 'camera';
    protected $primaryKey = 'camera_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ten_camera',
        'ma_camera',
        'ip_camera',
        'port',
        'username',
        'password',
        'status',
        'bin'
    ];
    
    protected $returnType = Camera::class;
    
    // Khai báo quan hệ với bảng khác
    protected $relationships = [
        'manhinh' => [
            'model' => 'App\Modules\manhinh\Models\ManhinhModel',
            'type' => 'hasMany',
            'foreignKey' => 'camera_id'
        ]
    ];
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_camera',
        'ma_camera',
        'ip_camera'
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
    
    // Camera pager
    protected $cameraPager = null;
    
    /**
     * Lấy tất cả camera
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'ten_camera', $order = 'ASC')
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
        
        // Khởi tạo CameraPager nếu chưa có
        if ($this->cameraPager === null) {
            $this->cameraPager = new CameraPager($total, $limit, $currentPage);
        } else {
            $this->cameraPager->setTotal($total)
                             ->setPerPage($limit)
                             ->setCurrentPage($currentPage);
        }
        
        // Lấy dữ liệu với phân trang
        $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Đảm bảo kết quả được trả về dù không có dữ liệu
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số camera không nằm trong thùng rác
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
     * Lấy tất cả camera đang hoạt động
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllActive(int $limit = 10, int $offset = 0, string $sort = 'ten_camera', string $order = 'ASC')
    {
        // Reset query builder
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('deleted_at', null);
        $this->builder->where('bin', 0);
        $this->builder->where('status', 1);
        
        // Thiết lập sắp xếp
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        // Lấy tổng số bản ghi
        $total = $this->countAllActive();
        
        // Tính toán trang hiện tại từ offset và limit
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        // Khởi tạo CameraPager nếu chưa có
        if ($this->cameraPager === null) {
            $this->cameraPager = new CameraPager($total, $limit, $currentPage);
        } else {
            $this->cameraPager->setTotal($total)
                             ->setPerPage($limit)
                             ->setCurrentPage($currentPage);
        }
        
        // Nếu limit > 0 thì sử dụng phân trang
        if ($limit > 0) {
            $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
            return $result ?: [];
        }
        
        return $this->findAll();
    }
    
    /**
     * Đếm tổng số camera đang hoạt động
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
     * Lấy tất cả camera trong thùng rác
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
        
        // Khởi tạo CameraPager nếu chưa có
        if ($this->cameraPager === null) {
            $this->cameraPager = new CameraPager($total, $limit, $currentPage);
        } else {
            $this->cameraPager->setTotal($total)
                             ->setPerPage($limit)
                             ->setCurrentPage($currentPage);
        }
        
        // Lấy dữ liệu với phân trang
        if ($limit > 0) {
            $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
            return $result ?: [];
        }
        
        return $this->findAll();
    }
    
    /**
     * Đếm tổng số camera trong thùng rác
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
     * Lấy camera theo ID
     *
     * @param mixed $id
     * @param array|null $relations Quan hệ cần tải
     * @param bool $validate Xác thực dữ liệu hay không
     * @return Camera|null
     */
    public function findWithRelations($id, $relations = [], $validate = false)
    {
        if (empty($relations)) {
            $relations = ['manhinh'];
        }
        
        return parent::findWithRelations($id, $relations, $validate);
    }
    
    /**
     * Tìm kiếm camera
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn tìm kiếm (limit, offset, relations, sort, order)
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        // Reset query builder để đảm bảo không có điều kiện nào từ trước
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Mặc định các tùy chọn
        $defaultOptions = [
            'limit' => 10,
            'offset' => 0,
            'sort' => 'ten_camera',
            'order' => 'ASC'
        ];
        
        // Merge options
        $options = array_merge($defaultOptions, $options);
        
        // Xử lý các điều kiện tìm kiếm
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $this->builder->groupStart();
            $this->builder->like('ma_camera', $keyword);
            $this->builder->orLike('ten_camera', $keyword);
            $this->builder->orLike('ip_camera', $keyword);
            $this->builder->groupEnd();
        }
        
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $this->builder->where('status', $criteria['status']);
        }
        
        // Xác định xem đang lấy dữ liệu từ thùng rác hay không
        $bin = isset($criteria['bin']) ? $criteria['bin'] : 0;
        $this->builder->where('bin', $bin);
        
        // Thiết lập sắp xếp
        if (!empty($options['sort']) && !empty($options['order'])) {
            $this->builder->orderBy($options['sort'], $options['order']);
        }
        
        // Lấy tổng số bản ghi tìm kiếm để cấu hình pagination
        $total = $this->countSearchResults($criteria);
        
        // Tính toán trang hiện tại từ offset và limit
        $currentPage = $options['limit'] > 0 ? floor($options['offset'] / $options['limit']) + 1 : 1;
        
        // Khởi tạo CameraPager nếu chưa có
        if ($this->cameraPager === null) {
            $this->cameraPager = new CameraPager($total, $options['limit'], $currentPage);
        } else {
            $this->cameraPager->setTotal($total)
                             ->setPerPage($options['limit'])
                             ->setCurrentPage($currentPage);
        }
        
        // Phân trang kết quả
        if ($options['limit'] > 0) {
            $result = $this->builder->limit($options['limit'], $options['offset'])->get()->getResult($this->returnType);
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
        
        // Xử lý từ khóa tìm kiếm
        if (!empty($params['keyword'])) {
            $keyword = trim($params['keyword']);
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $keyword);
            }
            $builder->groupEnd();
        }
        
        // Lọc theo trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $builder->where('status', (int)$params['status']);
        }
        
        // Mặc định không đếm những bản ghi đã soft delete
        if ($this->useSoftDeletes) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }
        
        // Mặc định không đếm bản ghi trong thùng rác, trừ khi chỉ định rõ
        if (!isset($params['bin']) || $params['bin'] === '') {
            $builder->where('bin', 0);
        } else {
            $builder->where('bin', (int)$params['bin']);
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
        $entity = new Camera();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Điều chỉnh quy tắc dựa trên tình huống
        if ($scenario === 'update' && isset($data['camera_id'])) {
            // Khi cập nhật, cần loại trừ chính ID hiện tại khi kiểm tra tính duy nhất
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    // Thay thế placeholder {camera_id} bằng ID thực tế
                    $rules = str_replace('{camera_id}', $data['camera_id'], $rules);
                }
            }
        } elseif ($scenario === 'insert') {
            // Khi thêm mới, bỏ loại trừ ID vì không có ID nào cần loại trừ
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace(',camera_id,{camera_id}', '', $rules);
                }
            }
        }
    }
    
    /**
     * Chuyển một camera vào thùng rác
     *
     * @param int $id ID của camera cần chuyển vào thùng rác
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function moveToRecycleBin($id)
    {
        $camera = $this->find($id);
        
        if (!$camera) {
            return false;
        }
        
        // Cập nhật trạng thái bin thành 1 (đã trong thùng rác)
        $camera->bin = 1;
        
        // Lưu vào cơ sở dữ liệu
        return $this->save($camera);
    }
    
    /**
     * Khôi phục camera từ thùng rác
     *
     * @param int $id ID của camera cần khôi phục
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function restoreFromRecycleBin($id)
    {
        $camera = $this->find($id);
        
        if (!$camera) {
            return false;
        }
        
        // Cập nhật trạng thái bin thành 0 (không trong thùng rác)
        $camera->bin = 0;
        
        // Lưu vào cơ sở dữ liệu
        return $this->save($camera);
    }
    
    /**
     * Kiểm tra xem tên camera đã tồn tại chưa
     *
     * @param string $name Tên camera cần kiểm tra
     * @param int|null $exceptId ID camera để loại trừ khỏi việc kiểm tra (hữu ích khi cập nhật)
     * @return bool Trả về true nếu tên đã tồn tại, false nếu chưa
     */
    public function isNameExists(string $name, ?int $exceptId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ten_camera', $name);
        
        // Loại trừ camera có ID cụ thể (dùng khi cập nhật)
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        // Loại trừ các bản ghi đã bị xóa mềm
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField, null);
        }
        
        // Kiểm tra cả những camera không nằm trong thùng rác và trong thùng rác
        // Điều này đảm bảo tên camera là duy nhất trong toàn bộ hệ thống
        
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
        // Nếu đã có CameraPager thì cập nhật, nếu chưa thì chỉ lưu giá trị để dùng sau
        if ($this->cameraPager !== null) {
            $this->cameraPager->setSurroundCount($count);
        }
        
        return $this;
    }
    
    /**
     * Lấy đối tượng phân trang 
     * 
     * @return CameraPager|null
     */
    public function getPager()
    {
        return $this->cameraPager;
    }
} 