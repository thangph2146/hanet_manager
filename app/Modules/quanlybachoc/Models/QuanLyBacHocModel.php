<?php

namespace App\Modules\quanlybachoc\Models;

use App\Models\BaseModel;
use App\Modules\quanlybachoc\Entities\QuanLyBacHoc;
use App\Modules\quanlybachoc\Libraries\Pager;
use CodeIgniter\I18n\Time;

class QuanLyBacHocModel extends BaseModel
{
    protected $table = 'bac_hoc';
    protected $primaryKey = 'bac_hoc_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'ten_bac_hoc',
        'ma_bac_hoc',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = QuanLyBacHoc::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_bac_hoc',
        'ma_bac_hoc'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'status'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [
        'ten_bac_hoc' => [
            'required' => 'Tên bậc học không được để trống',
            'max_length' => 'Tên bậc học không được vượt quá {param} ký tự',
            'min_length' => 'Tên bậc học phải có ít nhất {param} ký tự',
            'is_unique' => 'Tên bậc học này đã tồn tại, vui lòng chọn tên khác'
        ],
        'ma_bac_hoc' => [
            'required' => 'Mã bậc học không được để trống',
            'max_length' => 'Mã bậc học không được vượt quá {param} ký tự',
            'alpha_numeric_space' => 'Mã bậc học chỉ được chứa chữ cái, số và khoảng trắng',
            'is_unique' => 'Mã bậc học này đã tồn tại, vui lòng chọn mã khác'
        ],
        'status' => [
            'required' => 'Trạng thái không được để trống',
            'in_list' => 'Trạng thái không hợp lệ'
        ]
    ];
    protected $skipValidation = false;
    
    // Template pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi bậc học
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
     * Lấy tất cả bản ghi bậc học đã xóa
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
     * Đếm tổng số bản ghi bậc học
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
     * Đếm tổng số bản ghi bậc học đã xóa
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
     * Tìm kiếm bậc học dựa vào các tiêu chí
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
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị quy tắc validation dựa vào kịch bản (insert/update)
     *
     * @param string $scenario Kịch bản validation (insert/update)
     * @param mixed $data Dữ liệu cần validate hoặc ID của bản ghi cần cập nhật
     * @return void
     */
    public function prepareValidationRules($scenario = 'insert', $data = null)
    {
        // Khởi tạo ID là null
        $id = null;
        
        // Xác định ID tùy thuộc vào loại dữ liệu
        if (is_array($data) && isset($data[$this->primaryKey])) {
            $id = $data[$this->primaryKey];
        } elseif (is_numeric($data) || is_string($data)) {
            $id = $data;
        }
        
        // Thiết lập quy tắc validation cho các trường
        $this->validationRules = [
            'ten_bac_hoc' => [
                'rules' => 'required|min_length[2]|max_length[255]',
                'errors' => $this->validationMessages['ten_bac_hoc']
            ],
            'ma_bac_hoc' => [
                'rules' => 'required|max_length[50]|alpha_numeric_space',
                'errors' => $this->validationMessages['ma_bac_hoc']
            ],
            'status' => [
                'rules' => 'required|in_list[0,1]',
                'errors' => $this->validationMessages['status']
            ],
            'created_at' => [
                'rules' => 'permit_empty|valid_date[Y-m-d H:i:s]',
                'errors' => [
                    'valid_date' => 'Ngày tạo không đúng định dạng'
                ]
            ],
            'updated_at' => [
                'rules' => 'permit_empty|valid_date[Y-m-d H:i:s]',
                'errors' => [
                    'valid_date' => 'Ngày cập nhật không đúng định dạng'
                ]
            ],
            'deleted_at' => [
                'rules' => 'permit_empty|valid_date[Y-m-d H:i:s]',
                'errors' => [
                    'valid_date' => 'Ngày xóa không đúng định dạng'
                ]
            ]
        ];
        
        // Thêm quy tắc is_unique cho trường hợp thêm mới
        if ($scenario === 'insert') {
            // Quy tắc cho trường tên bậc học phải là duy nhất
            $this->validationRules['ten_bac_hoc']['rules'] .= '|is_unique[bac_hoc.ten_bac_hoc,deleted_at,NULL]';
            
            // Quy tắc cho trường mã bậc học phải là duy nhất
            $this->validationRules['ma_bac_hoc']['rules'] .= '|is_unique[bac_hoc.ma_bac_hoc,deleted_at,NULL]';
            
            // Không validate các trường thời gian khi thêm mới (sẽ tự động được thiết lập)
            unset($this->validationRules['created_at']);
            unset($this->validationRules['updated_at']);
            unset($this->validationRules['deleted_at']);
            
            // Không validate primary key khi thêm mới
            if (isset($this->validationRules[$this->primaryKey])) {
                unset($this->validationRules[$this->primaryKey]);
            }
        } 
        // Quy tắc cho trường hợp cập nhật
        elseif ($scenario === 'update' && $id) {
            // Quy tắc cho trường tên bậc học phải là duy nhất nhưng loại trừ bản ghi hiện tại
            $this->validationRules['ten_bac_hoc']['rules'] .= "|is_unique[bac_hoc.ten_bac_hoc,bac_hoc_id,$id,deleted_at,NULL]";
            
            // Quy tắc cho trường mã bậc học phải là duy nhất nhưng loại trừ bản ghi hiện tại
            $this->validationRules['ma_bac_hoc']['rules'] .= "|is_unique[bac_hoc.ma_bac_hoc,bac_hoc_id,$id,deleted_at,NULL]";
            
            // Không validate các trường thời gian khi cập nhật (updated_at sẽ tự động được thiết lập)
            unset($this->validationRules['created_at']);
            unset($this->validationRules['updated_at']);
            unset($this->validationRules['deleted_at']);
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
     * Kiểm tra xem tên bậc học đã tồn tại chưa
     *
     * @param string $tenBacHoc
     * @param int|null $excludeId ID của bản ghi cần loại trừ khi kiểm tra (cho update)
     * @return bool
     */
    public function isTenBacHocExists(string $tenBacHoc, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ten_bac_hoc', $tenBacHoc);
        $builder->where('deleted_at IS NULL');
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
} 