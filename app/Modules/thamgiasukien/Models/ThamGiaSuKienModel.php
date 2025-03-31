<?php

namespace App\Modules\thamgiasukien\Models;

use App\Models\BaseModel;
use App\Modules\thamgiasukien\Entities\ThamGiaSuKien;
use App\Modules\thamgiasukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

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
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'nguoi_dung_id',
        'su_kien_id',
        'thoi_gian_diem_danh',
        'phuong_thuc_diem_danh',
        'ghi_chu',
        'status',
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
        'nguoi_dung_id',
        'su_kien_id',
        'phuong_thuc_diem_danh'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Template pager
    public $pager = null;
    
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
     * Lấy tất cả bản ghi tham gia sự kiện đã xóa
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
     * Đếm tổng số bản ghi tham gia sự kiện
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
     * Đếm tổng số bản ghi tham gia sự kiện đã xóa
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
        $builder->where('deleted_at IS NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm tham gia sự kiện dựa vào các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $builder = $this->builder();
        
        // Xử lý withDeleted nếu cần
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where($this->table . '.deleted_at IS NOT NULL');
        } else {
            // Mặc định chỉ lấy dữ liệu chưa xóa
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
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
        
        if (isset($criteria['status']) || array_key_exists('status', $criteria)) {
            $status = (int)$criteria['status'];
            $builder->where($this->table . '.status', $status);
        }
        
        if (isset($criteria['nguoi_dung_id'])) {
            $builder->where('nguoi_dung_id', $criteria['nguoi_dung_id']);
        }
        
        if (isset($criteria['su_kien_id'])) {
            $builder->where('su_kien_id', $criteria['su_kien_id']);
        }
        
        if (isset($criteria['phuong_thuc_diem_danh'])) {
            $builder->where('phuong_thuc_diem_danh', $criteria['phuong_thuc_diem_danh']);
        }
        
        // Xác định trường sắp xếp và thứ tự sắp xếp
        $sort = $options['sort'] ?? 'created_at';
        $order = $options['order'] ?? 'DESC';
        
        // Xử lý giới hạn và phân trang
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        // Thực hiện truy vấn với phân trang
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Sắp xếp kết quả
        $builder->orderBy($sort, $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập pager nếu cần
        if ($limit > 0) {
            $totalRows = $this->countSearchResults($criteria);
            $this->pager = new Pager(
                $totalRows,
                $limit,
                floor($offset / $limit) + 1
            );
            $this->pager->setSurroundCount($this->surroundCount ?? 2);
        }
        
        return $result;
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
        
        // Xử lý withDeleted nếu cần
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where($this->table . '.deleted_at IS NOT NULL');
        } else {
            // Mặc định chỉ lấy dữ liệu chưa xóa
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
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
        
        if (isset($criteria['status']) || array_key_exists('status', $criteria)) {
            $status = (int)$criteria['status'];
            $builder->where($this->table . '.status', $status);
        }
        
        if (isset($criteria['nguoi_dung_id'])) {
            $builder->where('nguoi_dung_id', $criteria['nguoi_dung_id']);
        }
        
        if (isset($criteria['su_kien_id'])) {
            $builder->where('su_kien_id', $criteria['su_kien_id']);
        }
        
        if (isset($criteria['phuong_thuc_diem_danh'])) {
            $builder->where('phuong_thuc_diem_danh', $criteria['phuong_thuc_diem_danh']);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên tình huống
     * 
     * @param string $scenario Tình huống xác thực ('insert' hoặc 'update')
     * @param array $data Dữ liệu cần xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $entity = new ThamGiaSuKien();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        if ($scenario === 'insert') {
            unset($this->validationRules['tham_gia_su_kien_id']);
        }
        
        if ($scenario === 'update' && isset($data['tham_gia_su_kien_id'])) {
            foreach ($this->validationRules as $field => &$rules) {
                // Kiểm tra nếu $rules là một mảng
                if (is_array($rules) && isset($rules['rules'])) {
                    // Kiểm tra nếu chuỗi quy tắc chứa is_unique
                    if (strpos($rules['rules'], 'is_unique') !== false) {
                        $rules['rules'] = str_replace('{tham_gia_su_kien_id}', $data['tham_gia_su_kien_id'], $rules['rules']);
                    }
                } 
                // Nếu $rules là một chuỗi
                else if (is_string($rules) && strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace('{tham_gia_su_kien_id}', $data['tham_gia_su_kien_id'], $rules);
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
     * Tìm bản ghi với các quan hệ
     *
     * @param int $id ID bản ghi cần tìm
     * @param array $relations Các quan hệ cần lấy theo
     * @param bool $validate Có kiểm tra dữ liệu trước khi trả về không
     * @return object|null Đối tượng tìm thấy hoặc null nếu không tìm thấy
     */
    public function findWithRelations($id, $relations = [], $validate = false)
    {
        // Trong trường hợp đơn giản, chúng ta chỉ gọi phương thức find
        // Nhưng trong thực tế, có thể cần xử lý thêm các quan hệ
        return $this->find($id);
    }
    
    /**
     * Tìm kiếm các bản ghi đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn tìm kiếm (limit, offset, sort, order)
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->withDeleted();
        
        // Đặt điều kiện để chỉ lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Sử dụng phương thức search hiện tại với tham số đã sửa đổi
        return $this->search($criteria, $options);
    }
    
    /**
     * Đếm số lượng bản ghi đã xóa theo tiêu chí tìm kiếm
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedResults(array $criteria = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->withDeleted();
        
        // Đặt điều kiện để chỉ đếm các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Sử dụng phương thức countSearchResults hiện tại với tham số đã sửa đổi
        return $this->countSearchResults($criteria);
    }
    
    /**
     * Xử lý dữ liệu trước khi lưu vào database
     *
     * @param array $data Dữ liệu cần xử lý
     * @return array Dữ liệu đã xử lý
     */
    protected function beforeInsert(array $data): array
    {
        $data = parent::beforeInsert($data);
        
        // Xử lý thời gian điểm danh
        if (isset($data['data']['thoi_gian_diem_danh'])) {
            $data['data']['thoi_gian_diem_danh'] = $this->formatDateTime($data['data']['thoi_gian_diem_danh']);
        }
        
        return $data;
    }
    
    /**
     * Xử lý dữ liệu trước khi cập nhật vào database
     *
     * @param array $data Dữ liệu cần xử lý
     * @return array Dữ liệu đã xử lý
     */
    protected function beforeUpdate(array $data): array
    {
        $data = parent::beforeUpdate($data);
        
        // Xử lý thời gian điểm danh
        if (isset($data['data']['thoi_gian_diem_danh'])) {
            $data['data']['thoi_gian_diem_danh'] = $this->formatDateTime($data['data']['thoi_gian_diem_danh']);
        }
        
        return $data;
    }
    
    /**
     * Kiểm tra xem người dùng đã tham gia sự kiện chưa
     *
     * @param int $nguoiDungId
     * @param int $suKienId
     * @return bool
     */
    public function isUserJoinedEvent(int $nguoiDungId, int $suKienId): bool
    {
        $builder = $this->builder();
        
        return $builder->where([
            'nguoi_dung_id' => $nguoiDungId,
            'su_kien_id' => $suKienId,
            'deleted_at IS NULL' => null
        ])->countAllResults() > 0;
    }
} 