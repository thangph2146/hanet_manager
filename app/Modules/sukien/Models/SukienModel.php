<?php

namespace App\Modules\sukien\Models;

use App\Models\BaseModel;
use App\Modules\sukien\Entities\SuKien;
use App\Modules\sukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

class SuKienModel extends BaseModel
{
    protected $table = 'su_kien';
    protected $primaryKey = 'su_kien_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'ten_su_kien',
        'su_kien_poster',
        'mo_ta',
        'mo_ta_su_kien',
        'chi_tiet_su_kien',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'dia_diem',
        'dia_chi_cu_the',
        'toa_do_gps',
        'loai_su_kien_id',
        'nguoi_tao_id',
        'ma_qr_code',
        'status',
        'tong_dang_ky',
        'tong_check_in',
        'tong_check_out',
        'cho_phep_check_in',
        'cho_phep_check_out',
        'yeu_cau_face_id',
        'cho_phep_checkin_thu_cong',
        'tu_dong_xac_nhan_svgv',
        'yeu_cau_duyet_khach',
        'bat_dau_dang_ky',
        'ket_thuc_dang_ky',
        'han_huy_dang_ky',
        'gio_bat_dau',
        'gio_ket_thuc',
        'so_luong_tham_gia',
        'so_luong_dien_gia',
        'gioi_han_loai_nguoi_dung',
        'tu_khoa_su_kien',
        'hashtag',
        'slug',
        'so_luot_xem',
        'lich_trinh',
        'version',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = SuKien::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_su_kien',
        'mo_ta',
        'mo_ta_su_kien',
        'chi_tiet_su_kien',
        'dia_diem',
        'ma_qr_code',
        'tu_khoa_su_kien',
        'hashtag',
        'slug'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'loai_su_kien_id',
        'nguoi_tao_id',
        'status',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'gioi_han_loai_nguoi_dung'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Template pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi sự kiện
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
     * Lấy tất cả bản ghi sự kiện đã xóa
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
     * Đếm tổng số bản ghi sự kiện
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
     * Đếm tổng số bản ghi sự kiện đã xóa
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
     * Tìm kiếm sự kiện dựa vào các tiêu chí
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
        
        // Lọc theo loại sự kiện
        if (!empty($criteria['loai_su_kien_id'])) {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        // Lọc theo người tạo
        if (!empty($criteria['nguoi_tao_id'])) {
            $builder->where($this->table . '.nguoi_tao_id', $criteria['nguoi_tao_id']);
        }
        
        // Lọc theo thời gian bắt đầu từ
        if (!empty($criteria['thoi_gian_bat_dau_from'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau >=', $criteria['thoi_gian_bat_dau_from']);
        }
        
        // Lọc theo thời gian bắt đầu đến
        if (!empty($criteria['thoi_gian_bat_dau_to'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau <=', $criteria['thoi_gian_bat_dau_to']);
        }
        
        // Lọc theo thời gian kết thúc từ
        if (!empty($criteria['thoi_gian_ket_thuc_from'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc >=', $criteria['thoi_gian_ket_thuc_from']);
        }
        
        // Lọc theo thời gian kết thúc đến
        if (!empty($criteria['thoi_gian_ket_thuc_to'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc <=', $criteria['thoi_gian_ket_thuc_to']);
        }
        
        // Lọc theo loại người dùng
        if (!empty($criteria['gioi_han_loai_nguoi_dung'])) {
            $builder->like($this->table . '.gioi_han_loai_nguoi_dung', $criteria['gioi_han_loai_nguoi_dung']);
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
        
        // Lọc theo loại sự kiện
        if (!empty($criteria['loai_su_kien_id'])) {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        // Lọc theo người tạo
        if (!empty($criteria['nguoi_tao_id'])) {
            $builder->where($this->table . '.nguoi_tao_id', $criteria['nguoi_tao_id']);
        }
        
        // Lọc theo thời gian bắt đầu từ
        if (!empty($criteria['thoi_gian_bat_dau_from'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau >=', $criteria['thoi_gian_bat_dau_from']);
        }
        
        // Lọc theo thời gian bắt đầu đến
        if (!empty($criteria['thoi_gian_bat_dau_to'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau <=', $criteria['thoi_gian_bat_dau_to']);
        }
        
        // Lọc theo thời gian kết thúc từ
        if (!empty($criteria['thoi_gian_ket_thuc_from'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc >=', $criteria['thoi_gian_ket_thuc_from']);
        }
        
        // Lọc theo thời gian kết thúc đến
        if (!empty($criteria['thoi_gian_ket_thuc_to'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc <=', $criteria['thoi_gian_ket_thuc_to']);
        }
        
        // Lọc theo loại người dùng
        if (!empty($criteria['gioi_han_loai_nguoi_dung'])) {
            $builder->like($this->table . '.gioi_han_loai_nguoi_dung', $criteria['gioi_han_loai_nguoi_dung']);
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
        $entity = new SuKien();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        // Loại bỏ validation cho su_kien_id trong mọi trường hợp
        unset($this->validationRules['su_kien_id']);
        
        if ($scenario === 'update' && isset($data['su_kien_id'])) {
            foreach ($this->validationRules as $field => &$rules) {
                // Kiểm tra nếu $rules là một mảng
                if (is_array($rules) && isset($rules['rules'])) {
                    // Kiểm tra nếu chuỗi quy tắc chứa is_unique
                    if (strpos($rules['rules'], 'is_unique') !== false) {
                        $rules['rules'] = str_replace('{su_kien_id}', $data['su_kien_id'], $rules['rules']);
                    }
                } 
                // Nếu $rules là một chuỗi
                else if (is_string($rules) && strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace('{su_kien_id}', $data['su_kien_id'], $rules);
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
     * Kiểm tra xem slug đã tồn tại chưa
     *
     * @param string $slug
     * @param int|null $excludeId ID của bản ghi cần loại trừ khi kiểm tra (cho update)
     * @return bool
     */
    public function isSlugExists(string $slug, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('slug', $slug);
        $builder->where('deleted_at IS NULL');
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Lấy danh sách sự kiện theo loại sự kiện
     *
     * @param int $loaiSuKienId ID của loại sự kiện
     * @param bool $onlyActive Chỉ lấy các sự kiện đang hoạt động
     * @return array
     */
    public function getByLoaiSuKien(int $loaiSuKienId, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $builder->where('loai_su_kien_id', $loaiSuKienId);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('thoi_gian_bat_dau', 'DESC');
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện sắp diễn ra
     *
     * @param int $limit Số lượng bản ghi cần lấy
     * @param bool $onlyActive Chỉ lấy các sự kiện đang hoạt động
     * @return array
     */
    public function getUpcomingEvents(int $limit = 5, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $now = Time::now()->toDateTimeString();
        
        $builder->where('thoi_gian_bat_dau >', $now);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('thoi_gian_bat_dau', 'ASC');
        $builder->limit($limit);
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện đang diễn ra
     *
     * @param int $limit Số lượng bản ghi cần lấy
     * @param bool $onlyActive Chỉ lấy các sự kiện đang hoạt động
     * @return array
     */
    public function getOngoingEvents(int $limit = 5, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $now = Time::now()->toDateTimeString();
        
        $builder->where('thoi_gian_bat_dau <=', $now);
        $builder->where('thoi_gian_ket_thuc >=', $now);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('thoi_gian_bat_dau', 'ASC');
        $builder->limit($limit);
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện đã kết thúc
     *
     * @param int $limit Số lượng bản ghi cần lấy
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param bool $onlyActive Chỉ lấy các sự kiện đang hoạt động
     * @return array
     */
    public function getPastEvents(int $limit = 10, int $offset = 0, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $now = Time::now()->toDateTimeString();
        
        $builder->where('thoi_gian_ket_thuc <', $now);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('thoi_gian_ket_thuc', 'DESC');
        
        $total = $builder->countAllResults(false);
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        if ($this->pager === null) {
            $this->pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->pager->setTotal($total)
                        ->setPerPage($limit)
                        ->setCurrentPage($currentPage);
        }
        
        $builder->limit($limit, $offset);
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Tăng số lượt xem cho sự kiện
     *
     * @param int $id ID của sự kiện
     * @return bool
     */
    public function incrementViews(int $id): bool
    {
        $builder = $this->builder();
        $builder->set('so_luot_xem', 'so_luot_xem + 1', false);
        $builder->where($this->primaryKey, $id);
        
        return $builder->update();
    }
    
    /**
     * Cập nhật số lượng đăng ký
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng đăng ký cần cập nhật
     * @return bool
     */
    public function updateRegistrationCount(int $id, int $count): bool
    {
        $builder = $this->builder();
        $builder->set('tong_dang_ky', $count);
        $builder->where($this->primaryKey, $id);
        
        return $builder->update();
    }
    
    /**
     * Cập nhật số lượng check-in
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng check-in cần cập nhật
     * @return bool
     */
    public function updateCheckInCount(int $id, int $count): bool
    {
        $builder = $this->builder();
        $builder->set('tong_check_in', $count);
        $builder->where($this->primaryKey, $id);
        
        return $builder->update();
    }
    
    /**
     * Cập nhật số lượng check-out
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng check-out cần cập nhật
     * @return bool
     */
    public function updateCheckOutCount(int $id, int $count): bool
    {
        $builder = $this->builder();
        $builder->set('tong_check_out', $count);
        $builder->where($this->primaryKey, $id);
        
        return $builder->update();
    }
    
    /**
     * Tìm sự kiện theo slug
     *
     * @param string $slug Slug của sự kiện
     * @param bool $onlyActive Chỉ lấy sự kiện đang hoạt động
     * @return object|null
     */
    public function findBySlug(string $slug, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $builder->where('slug', $slug);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        return $builder->get()->getRow(0, $this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện theo người tạo
     *
     * @param int $nguoiTaoId ID của người tạo
     * @param int $limit Số lượng bản ghi cần lấy
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param bool $onlyActive Chỉ lấy các sự kiện đang hoạt động
     * @return array
     */
    public function getByNguoiTao(int $nguoiTaoId, int $limit = 10, int $offset = 0, bool $onlyActive = true)
    {
        $builder = $this->builder();
        $builder->where('nguoi_tao_id', $nguoiTaoId);
        $builder->where('deleted_at IS NULL');
        
        if ($onlyActive) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('created_at', 'DESC');
        
        $total = $builder->countAllResults(false);
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        if ($this->pager === null) {
            $this->pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->pager->setTotal($total)
                        ->setPerPage($limit)
                        ->setCurrentPage($currentPage);
        }
        
        $builder->limit($limit, $offset);
        
        return $builder->get()->getResult($this->returnType);
    }
} 