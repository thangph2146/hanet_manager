<?php

namespace App\Modules\sukiendiengia\Models;

use App\Models\BaseModel;
use App\Modules\sukiendiengia\Entities\SuKienDienGia;
use App\Modules\sukiendiengia\Libraries\Pager;
use CodeIgniter\I18n\Time;

class SuKienDienGiaModel extends BaseModel
{
    protected $table = 'su_kien_dien_gia';
    protected $primaryKey = 'su_kien_dien_gia_id';
    protected $useAutoIncrement = false;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'su_kien_dien_gia_id',
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
        'dien_gia_id'
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
     * Lấy tất cả bản ghi liên kết sự kiện và diễn giả
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
        $builder->where('deleted_at IS NULL');
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy tất cả bản ghi liên kết đã xóa
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
     * Đếm tổng số bản ghi
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
     * Đếm tổng số bản ghi đã xóa
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
     * Tìm kiếm liên kết sự kiện và diễn giả dựa vào các tiêu chí
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
        
        // Lọc theo ID sự kiện
        if (!empty($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Lọc theo ID diễn giả
        if (!empty($criteria['dien_gia_id'])) {
            $builder->where($this->table . '.dien_gia_id', $criteria['dien_gia_id']);
        }
        
        // Lọc theo từ khóa
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            // Tìm kiếm theo ID
            $builder->groupStart();
            $builder->like($this->table . '.su_kien_dien_gia_id', $keyword);
            $builder->orLike($this->table . '.su_kien_id', $keyword);
            $builder->orLike($this->table . '.dien_gia_id', $keyword);
            $builder->groupEnd();
        }
        
        // Xác định trường sắp xếp và thứ tự sắp xếp
        $sort = $options['sort'] ?? 'thu_tu';
        $order = $options['order'] ?? 'ASC';
        
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
        
        // Lọc theo ID sự kiện
        if (!empty($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Lọc theo ID diễn giả
        if (!empty($criteria['dien_gia_id'])) {
            $builder->where($this->table . '.dien_gia_id', $criteria['dien_gia_id']);
        }
        
        // Lọc theo từ khóa
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            // Tìm kiếm theo ID
            $builder->groupStart();
            $builder->like($this->table . '.su_kien_dien_gia_id', $keyword);
            $builder->orLike($this->table . '.su_kien_id', $keyword);
            $builder->orLike($this->table . '.dien_gia_id', $keyword);
            $builder->groupEnd();
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
        $entity = new SuKienDienGia();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
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
     * Lấy danh sách diễn giả theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @return array
     */
    public function getDienGiaBySuKien(int $suKienId)
    {
        $builder = $this->builder();
        $builder->select($this->table . '.*, dg.ten_dien_gia, dg.chuc_danh, dg.to_chuc, dg.avatar');
        $builder->join('dien_gia dg', $this->table . '.dien_gia_id = dg.dien_gia_id', 'left');
        $builder->where($this->table . '.su_kien_id', $suKienId);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy($this->table . '.thu_tu', 'ASC');
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện theo diễn giả
     *
     * @param int $dienGiaId ID của diễn giả
     * @return array
     */
    public function getSuKienByDienGia(int $dienGiaId)
    {
        $builder = $this->builder();
        $builder->select($this->table . '.*, sk.ten_su_kien, sk.mo_ta, sk.thoi_gian_bat_dau, sk.thoi_gian_ket_thuc');
        $builder->join('su_kien sk', $this->table . '.su_kien_id = sk.su_kien_id', 'left');
        $builder->where($this->table . '.dien_gia_id', $dienGiaId);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy('sk.thoi_gian_bat_dau', 'DESC');
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Kiểm tra mối quan hệ giữa sự kiện và diễn giả đã tồn tại
     *
     * @param int $suKienId ID sự kiện
     * @param int $dienGiaId ID diễn giả
     * @return bool
     */
    public function isExistsRelation(int $suKienId, int $dienGiaId): bool
    {
        $builder = $this->builder();
        $builder->where('su_kien_id', $suKienId);
        $builder->where('dien_gia_id', $dienGiaId);
        $builder->where('deleted_at IS NULL');
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Xóa mềm tất cả diễn giả trong một sự kiện
     *
     * @param int $suKienId ID sự kiện
     * @return bool
     */
    public function deleteBySuKien(int $suKienId)
    {
        $this->where('su_kien_id', $suKienId);
        return $this->delete(null, true); // true để chỉ định xóa mềm
    }
    
    /**
     * Xóa mềm tất cả sự kiện của một diễn giả
     *
     * @param int $dienGiaId ID diễn giả
     * @return bool
     */
    public function deleteByDienGia(int $dienGiaId)
    {
        $this->where('dien_gia_id', $dienGiaId);
        return $this->delete(null, true); // true để chỉ định xóa mềm
    }
} 