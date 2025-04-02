<?php

namespace App\Modules\quanlyloaisukien\Models;

use App\Models\BaseModel;
use App\Modules\quanlyloaisukien\Entities\LoaiSuKien;
use App\Modules\quanlyloaisukien\Libraries\Pager;
use CodeIgniter\I18n\Time;

class LoaiSuKienModel extends BaseModel
{
    protected $table = 'loai_su_kien';
    protected $primaryKey = 'loai_su_kien_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    protected $surroundCount = 2;
    
    protected $allowedFields = [
        'ten_loai_su_kien',
        'ma_loai_su_kien',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = LoaiSuKien::class;
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_loai_su_kien',
        'ma_loai_su_kien',
        'status',
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'ten_loai_su_kien',
        'ma_loai_su_kien',
        'status',
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [
        'ten_loai_su_kien' => 'required|string|max_length[100]',
        'ma_loai_su_kien' => 'required|string|max_length[20]',
        'status' => 'required|integer|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'ten_loai_su_kien' => [
            'required' => 'Tên loại sự kiện là bắt buộc',
            'string' => 'Tên loại sự kiện phải là chuỗi',
            'max_length' => 'Tên loại sự kiện không được vượt quá {param} ký tự'
        ],
        'ma_loai_su_kien' => [
            'required' => 'Mã loại sự kiện là bắt buộc',
            'string' => 'Mã loại sự kiện phải là chuỗi',
            'max_length' => 'Mã loại sự kiện không được vượt quá {param} ký tự'
        ],
        'status' => [
            'required' => 'Trạng thái là bắt buộc',
            'integer' => 'Trạng thái phải là số nguyên',
            'in_list' => 'Trạng thái phải có giá trị hợp lệ'
        ]
    ];
    
    protected $skipValidation = false;
    
    // Loại sự kiện pager
    public $pager = null;
    
    /**
     * Cập nhật dữ liệu loại sự kiện
     *
     * @param int $id ID loại sự kiện
     * @param array $data Dữ liệu cần cập nhật
     * @return bool
     */
    public function updateData($id, $data)
    {
        $this->builder->where($this->primaryKey, $id);
        $this->builder->update($data);
        return $this->db->affectedRows() > 0;
    }
    
    /**
     * Lấy tất cả bản ghi loại sự kiện
     *
     * @param int|array $limit Số lượng bản ghi trên mỗi trang hoặc mảng tùy chọn
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'ten_loai_su_kien', $order = 'DESC')
    {
        // Xử lý trường hợp tham số đầu vào là một mảng tùy chọn
        if (is_array($limit)) {
            $options = $limit;
            $sort = $options['sort'] ?? 'ten_loai_su_kien';
            $order = $options['order'] ?? 'DESC';
            $offset = $options['offset'] ?? 0;
            $limit = $options['limit'] ?? 10;
        }

        $this->builder = $this->db->table($this->table);
        $this->builder->select($this->table . '.*');
        
        // Chỉ lấy bản ghi chưa xóa và đang hoạt động
        $this->builder->where($this->table . '.deleted_at IS NULL');
        $this->builder->where($this->table . '.status', 1);
        
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
        
        // Nếu limit = 0, lấy tất cả dữ liệu không giới hạn (phục vụ xuất Excel/PDF)
        if ($limit > 0) {
            $this->builder->limit($limit, $offset);
        }
        
        $result = $this->builder->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số bản ghi loại sự kiện
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        
        // Mặc định chỉ đếm bản ghi chưa xóa và đang hoạt động
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->where($this->table . '.status', 1);
        
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm loại sự kiện dựa vào các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        $sort = $options['sort'] ?? $this->primaryKey;
        $order = $options['order'] ?? 'DESC';
        
        $this->builder = $this->builder();
        $this->builder->select($this->table . '.*');
        
        // Thêm điều kiện tìm kiếm
        $this->applySearchCriteria($this->builder, $criteria);
        
        // Sắp xếp
        if (strpos($sort, '.') === false) {
            $sort = $this->table . '.' . $sort;
        }
        $this->builder->orderBy($sort, $order);
        
        // Thiết lập pager nếu có limit
        if ($limit > 0) {
            $totalRows = $this->countSearchResults($criteria);
            $currentPage = floor($offset / $limit) + 1;
            
            if ($this->pager === null) {
                $this->pager = new Pager($totalRows, $limit, $currentPage);
                $this->pager->setSurroundCount($this->surroundCount ?? 2);
            } else {
                $this->pager->setTotal($totalRows)
                            ->setPerPage($limit)
                            ->setCurrentPage($currentPage)
                            ->setSurroundCount($this->surroundCount ?? 2);
            }
        }
        
        // Phân trang
        if ($limit > 0) {
            $this->builder->limit($limit, $offset);
        }
        
        // Thực hiện truy vấn
        $query = $this->builder->get();
        $result = $query->getResult($this->returnType);
        
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
        
        // Áp dụng các điều kiện tìm kiếm
        $this->applySearchCriteria($builder, $criteria);
        
        return $builder->countAllResults();
    }
    
    /**
     * Đếm tổng số kết quả tìm kiếm đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedSearchResults(array $criteria = [])
    {
        // Khởi tạo builder trực tiếp từ database
        $builder = $this->db->table($this->table);
        
        // ĐẢM BẢO chỉ đếm các bản ghi đã bị xóa - sửa lỗi whereNotNull
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Áp dụng các điều kiện tìm kiếm
        $criteria['ignoreDeletedCheck'] = true; // Đánh dấu để không kiểm tra deleted_at trong applySearchCriteria
        $this->applySearchCriteria($builder, $criteria);
        
        $count = $builder->countAllResults();
        
        return $count;
    }
    
    /**
     * Áp dụng các điều kiện tìm kiếm vào truy vấn
     *
     * @param object $builder Query builder
     * @param array $criteria Tiêu chí tìm kiếm
     * @return void
     */
    protected function applySearchCriteria(&$builder, array $criteria)
    {
        // Kiểm tra xem có bỏ qua kiểm tra deleted_at không
        $ignoreDeletedCheck = $criteria['ignoreDeletedCheck'] ?? false;
        unset($criteria['ignoreDeletedCheck']);

        // Nếu không bỏ qua kiểm tra deleted_at, thêm điều kiện lọc bản ghi chưa xóa
        if (!$ignoreDeletedCheck) {
            $builder->where($this->table . '.deleted_at IS NULL');
        }

        // Xử lý từng tiêu chí tìm kiếm
        foreach ($criteria as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // Xử lý tìm kiếm theo trạng thái đặc biệt
            if ($field === 'status' && $value !== '') {
                $builder->where($this->table . '.' . $field, $value);
                continue;
            }

            // Xử lý các trường còn lại với tìm kiếm tương đối
            if (in_array($field, $this->searchableFields) && !empty($value)) {
                $builder->like($this->table . '.' . $field, $value);
            }
        }
    }

    /**
     * Lấy danh sách loại sự kiện dưới dạng mảng key-value cho dropdown
     *
     * @param bool $active Chỉ lấy loại sự kiện đang hoạt động
     * @return array
     */
    public function getListForDropdown($active = true)
    {
        $builder = $this->builder();
        $builder->select('loai_su_kien_id, ten_loai_su_kien');
        $builder->where('deleted_at IS NULL');
        
        if ($active) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('ten_loai_su_kien', 'ASC');
        $result = $builder->get()->getResult();
        
        $dropdown = [];
        foreach ($result as $item) {
            $dropdown[$item->loai_su_kien_id] = $item->ten_loai_su_kien;
        }
        
        return $dropdown;
    }
    public function setSurroundCount($surroundCount)
    {
        $this->surroundCount = $surroundCount;
    }
    public function getPager()
    {
        return $this->pager;
    }
    public function searchDeleted($keyword)
    {
        $builder = $this->builder();
        $builder->select('*');
        $builder->where('deleted_at IS NOT NULL');

        // Ensure $keyword is a string and apply the LIKE condition
        if (is_string($keyword) && !empty($keyword)) {
            $builder->groupStart()
                    ->like('ten_loai_su_kien', $keyword)
                    ->orLike('ma_loai_su_kien', $keyword)
                    ->groupEnd();
        }

        $builder->orderBy('ten_loai_su_kien', 'ASC');
        $result = $builder->get()->getResult();
        return $result;
    }
    
    /**
     * Lấy tất cả bản ghi đã xóa
     *
     * @param array $options Mảng tùy chọn
     * @return array
     */
    public function getAllDeleted($options = [])
    {
        $sort = $options['sort'] ?? 'ten_loai_su_kien';
        $order = $options['order'] ?? 'ASC';
        $offset = $options['offset'] ?? 0;
        $limit = $options['limit'] ?? 10;

        $builder = $this->db->table($this->table);
        $builder->select($this->table . '.*');
        
        // Chỉ lấy bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        if ($sort && $order) {
            $builder->orderBy($this->table . '.' . $sort, $order);
        }
        
        // Nếu limit = 0, lấy tất cả dữ liệu không giới hạn (phục vụ xuất Excel/PDF)
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        $result = $builder->get()->getResult($this->returnType);
        return $result ?: [];
    }
    
    /**
     * Chuẩn bị quy tắc xác thực dựa vào hành động
     *
     * @param string $action Hành động (insert, update)
     * @param array $data Dữ liệu đầu vào
     * @param int|null $id ID bản ghi (chỉ dùng cho update)
     * @return void
     */
    public function prepareValidationRules($action = 'insert', $data = [], $id = null)
    {
        $this->validationRules = [
            'ten_loai_su_kien' => 'required|string|max_length[100]',
            'ma_loai_su_kien' => 'required|string|max_length[20]',
            'status' => 'required|integer|in_list[0,1]'
        ];
        
        // Nếu đây là cập nhật, kiểm tra xem mã loại sự kiện đã thay đổi chưa
        if ($action === 'update' && $id) {
            $currentRecord = $this->find($id);
            
            // Nếu mã không thay đổi, không cần kiểm tra tính duy nhất
            if ($currentRecord && isset($data['ma_loai_su_kien']) && $currentRecord->getMaLoaiSuKien() === $data['ma_loai_su_kien']) {
                $this->validationRules['ma_loai_su_kien'] = 'required|string|max_length[20]';
            }
        }
    }
    
    /**
     * Lấy danh sách các trường có thể sắp xếp
     *
     * @return array
     */
    public function getValidSortFields()
    {
        return array_merge(
            [$this->primaryKey],
            $this->searchableFields,
            ['created_at', 'updated_at']
        );
    }
    
    /**
     * Chèn dữ liệu mới với kiểm tra xác thực
     *
     * @param array $data Dữ liệu cần chèn
     * @return int|bool ID bản ghi mới hoặc false nếu thất bại
     */
    public function insertData($data)
    {
        // Thiết lập các giá trị mặc định nếu cần
        $data['created_at'] = $data['created_at'] ?? Time::now()->toDateTimeString();
        
        // Thực hiện chèn dữ liệu
        $result = $this->insert($data);
        
        return $result ? $this->getInsertID() : false;
    }
        
    /**
     * Lấy tất cả bản ghi cho phân trang
     *
     * @param int $perPage Số lượng bản ghi trên mỗi trang
     * @param int $page Trang hiện tại
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllPaginated($perPage = 10, $page = 1, $sort = 'ten_loai_su_kien', $order = 'ASC')
    {
        $offset = ($page - 1) * $perPage;
        
        return $this->getAll($perPage, $offset, $sort, $order);
    }
    
    /**
     * Khôi phục một bản ghi đã xóa mềm
     *
     * @param int $id ID bản ghi cần khôi phục
     * @return bool
     */
    public function restore($id)
    {
        return $this->update($id, ['deleted_at' => null]);
    }
    
    /**
     * Tìm kiếm bản ghi đã xóa với phân trang
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */

    
    /**
     * Tạo mã xác nhận ngẫu nhiên
     *
     * @param int $length Độ dài mã xác nhận
     * @return string
     */
    public function generateConfirmationCode(int $length = 8): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
}