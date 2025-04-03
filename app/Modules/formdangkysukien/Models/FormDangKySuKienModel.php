<?php

namespace App\Modules\formdangkysukien\Models;

use App\Models\BaseModel;
use App\Modules\formdangkysukien\Entities\FormDangKySuKien;
use App\Modules\formdangkysukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

class FormDangKySuKienModel extends BaseModel
{
    protected $table = 'form_dangky_sukien';
    protected $primaryKey = 'form_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'ten_form',
        'mo_ta',
        'su_kien_id',
        'cau_truc_form',
        'hien_thi_cong_khai',
        'bat_buoc_dien',
        'so_lan_su_dung',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = FormDangKySuKien::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_form',
        'mo_ta'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'su_kien_id',
        'hien_thi_cong_khai',
        'bat_buoc_dien',
        'status'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // FormDangKySuKien pager
    public $pager = null;
    
    /**
     * Lấy tất cả bản ghi form đăng ký
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'created_at', $order = 'DESC')
    {
        $builder = $this->builder();
        
        // Chỉ lấy bản ghi chưa xóa
        $builder->where($this->table . '.deleted_at IS NULL');
        
        if ($sort && $order) {
            $builder->orderBy($this->table . '.' . $sort, $order);
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
        
        $result = $builder->limit($limit, $offset)->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số bản ghi form đăng ký
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
     * Tìm kiếm form đăng ký dựa vào các tiêu chí
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
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        // Xử lý lọc theo bắt buộc điền
        if (isset($criteria['bat_buoc_dien'])) {
            $builder->where($this->table . '.bat_buoc_dien', $criteria['bat_buoc_dien']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        // Xác định trường sắp xếp và thứ tự sắp xếp
        $sort = $options['sort'] ?? 'ten_form';
        $order = $options['order'] ?? 'ASC';
        
        // Xử lý giới hạn và phân trang
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        // Thực hiện truy vấn với phân trang
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Sắp xếp kết quả
        $builder->orderBy($this->table . '.' . $sort, $order);
        
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
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        // Xử lý lọc theo bắt buộc điền
        if (isset($criteria['bat_buoc_dien'])) {
            $builder->where($this->table . '.bat_buoc_dien', $criteria['bat_buoc_dien']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
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
        $entity = new FormDangKySuKien();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại trừ các trường timestamp và primary key khi thêm mới
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
     * Lấy danh sách form đăng ký theo sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @param array $options Các tùy chọn bổ sung
     * @return array
     */
    public function getFormBySuKien(int $suKienId, array $options = [])
    {
        $builder = $this->builder();
        $builder->where($this->table . '.su_kien_id', $suKienId);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Lọc theo trạng thái công khai
        if (isset($options['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $options['hien_thi_cong_khai']);
        }
        
        // Lọc theo trạng thái bắt buộc điền
        if (isset($options['bat_buoc_dien'])) {
            $builder->where($this->table . '.bat_buoc_dien', $options['bat_buoc_dien']);
        }
        
        // Lọc theo trạng thái hoạt động
        if (isset($options['status'])) {
            $builder->where($this->table . '.status', $options['status']);
        }
        
        // Sắp xếp
        $sort = $options['sort'] ?? 'ten_form';
        $order = $options['order'] ?? 'ASC';
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Giới hạn và phân trang
        if (isset($options['limit']) && $options['limit'] > 0) {
            $offset = $options['offset'] ?? 0;
            $builder->limit($options['limit'], $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Tăng số lần sử dụng form
     *
     * @param int $formId ID của form
     * @param int $increment Số lượng tăng
     * @return bool
     */
    public function tangSoLanSuDung(int $formId, int $increment = 1): bool
    {
        $form = $this->find($formId);
        
        if ($form) {
            $soLanSuDung = $form->getSoLanSuDung() + $increment;
            return $this->update($formId, [
                'so_lan_su_dung' => $soLanSuDung,
                'updated_at' => Time::now()->toDateTimeString()
            ]);
        }
        
        return false;
    }
    
    /**
     * Kiểm tra sự tồn tại của form trong sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @param string $tenForm Tên form cần kiểm tra
     * @param int|null $excludeId ID của bản ghi cần loại trừ khi kiểm tra (cho update)
     * @return bool
     */
    public function isFormExistsInEvent(int $suKienId, string $tenForm, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where('su_kien_id', $suKienId);
        $builder->where('ten_form', $tenForm);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Cập nhật trạng thái của form
     *
     * @param int $id ID của form
     * @param int $status Trạng thái mới (0: Không hoạt động, 1: Hoạt động)
     * @return bool
     */
    public function updateStatus(int $id, int $status): bool
    {
        if (!in_array($status, [0, 1])) {
            return false;
        }
        
        return $this->update($id, [
            'status' => $status,
            'updated_at' => Time::now()->toDateTimeString()
        ]);
    }
    
    /**
     * Tìm kiếm các bản ghi đã xóa
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        // Đảm bảo withDeleted được thiết lập để tìm kiếm cả các bản ghi đã xóa
        $this->withDeleted();
        
        $builder = $this->builder();
        
        // Chỉ lấy dữ liệu đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        // Xử lý lọc theo bắt buộc điền
        if (isset($criteria['bat_buoc_dien'])) {
            $builder->where($this->table . '.bat_buoc_dien', $criteria['bat_buoc_dien']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        // Xác định trường sắp xếp và thứ tự sắp xếp
        $sort = $options['sort'] ?? 'deleted_at';
        $order = $options['order'] ?? 'DESC';
        
        // Xử lý giới hạn và phân trang
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        
        // Thực hiện truy vấn với phân trang
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Sắp xếp kết quả
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập pager nếu cần
        if ($limit > 0) {
            $totalRows = $this->countDeletedSearchResults($criteria);
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
     * Đếm tổng số kết quả tìm kiếm đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedSearchResults(array $criteria = [])
    {
        // Đảm bảo withDeleted được thiết lập để tìm kiếm cả các bản ghi đã xóa
        $this->withDeleted();
        
        $builder = $this->builder();
        
        // Chỉ đếm bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo sự kiện
        if (isset($criteria['su_kien_id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['su_kien_id']);
        }
        
        // Xử lý lọc theo hiển thị công khai
        if (isset($criteria['hien_thi_cong_khai'])) {
            $builder->where($this->table . '.hien_thi_cong_khai', $criteria['hien_thi_cong_khai']);
        }
        
        // Xử lý lọc theo bắt buộc điền
        if (isset($criteria['bat_buoc_dien'])) {
            $builder->where($this->table . '.bat_buoc_dien', $criteria['bat_buoc_dien']);
        }
        
        // Xử lý lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        if (!empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            $builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $builder->like($this->table . '.' . $field, $keyword);
                } else {
                    $builder->orLike($this->table . '.' . $field, $keyword);
                }
            }
            $builder->groupEnd();
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Kiểm tra có form bắt buộc điền trong sự kiện không
     *
     * @param int $suKienId ID của sự kiện
     * @return bool
     */
    public function hasRequiredForm(int $suKienId): bool
    {
        return $this->where('su_kien_id', $suKienId)
                    ->where('bat_buoc_dien', 1)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->countAllResults() > 0;
    }
    
    /**
     * Lấy danh sách form bắt buộc điền trong sự kiện
     *
     * @param int $suKienId ID của sự kiện
     * @return array
     */
    public function getRequiredForms(int $suKienId): array
    {
        return $this->where('su_kien_id', $suKienId)
                    ->where('bat_buoc_dien', 1)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->orderBy('ten_form', 'ASC')
                    ->findAll();
    }
} 