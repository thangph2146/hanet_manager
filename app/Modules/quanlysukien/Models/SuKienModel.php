<?php

namespace App\Modules\quanlysukien\Models;

use App\Models\BaseModel;
use App\Modules\quanlysukien\Entities\SuKien;
use App\Modules\quanlysukien\Libraries\Pager;
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
        'ma_qr_code',
        'status',
        'tong_dang_ky',
        'tong_check_in',
        'tong_check_out',
        'cho_phep_check_in',
        'cho_phep_check_out',
        'yeu_cau_face_id',
        'cho_phep_checkin_thu_cong',
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
        'hinh_thuc',
        'link_online',
        'mat_khau_online',
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
        'dia_diem',
        'dia_chi_cu_the',
        'tu_khoa_su_kien',
        'hashtag'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'ten_su_kien',
        'loai_su_kien_id',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'status',
        'hinh_thuc'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [
        'ten_su_kien' => 'required|string|max_length[255]',
        'thoi_gian_bat_dau' => 'required',
        'thoi_gian_ket_thuc' => 'required',
        'loai_su_kien_id' => 'required|integer',
        'status' => 'required|integer|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'ten_su_kien' => [
            'required' => 'Tên sự kiện là bắt buộc',
            'string' => 'Tên sự kiện phải là chuỗi',
            'max_length' => 'Tên sự kiện không được vượt quá {param} ký tự'
        ],
        'thoi_gian_bat_dau' => [
            'required' => 'Thời gian bắt đầu là bắt buộc'
        ],
        'thoi_gian_ket_thuc' => [
            'required' => 'Thời gian kết thúc là bắt buộc'
        ],
        'loai_su_kien_id' => [
            'required' => 'Loại sự kiện là bắt buộc',
            'integer' => 'Loại sự kiện phải là số nguyên'
        ],
        'status' => [
            'required' => 'Trạng thái là bắt buộc',
            'integer' => 'Trạng thái phải là số nguyên',
            'in_list' => 'Trạng thái phải có giá trị hợp lệ'
        ]
    ];
    
    protected $skipValidation = false;
    
    // Sự kiện pager
    public $pager = null;
    
    /**
     * Cập nhật dữ liệu sự kiện
     *
     * @param int $id ID sự kiện
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
     * Lấy tất cả bản ghi sự kiện
     *
     * @param int|array $limit Số lượng bản ghi trên mỗi trang hoặc mảng tùy chọn
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'thoi_gian_bat_dau', $order = 'DESC')
    {
        // Xử lý trường hợp tham số đầu vào là một mảng tùy chọn
        if (is_array($limit)) {
            $options = $limit;
            $sort = $options['sort'] ?? 'thoi_gian_bat_dau';
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
     * Đếm tổng số bản ghi sự kiện
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
     * Tìm kiếm sự kiện dựa vào các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        $sort = $options['sort'] ?? 'thoi_gian_bat_dau';
        $order = $options['order'] ?? 'DESC';
        
        $this->builder = $this->builder();
        $this->builder->select($this->table . '.*');
        
        // Thêm join với bảng loại sự kiện nếu cần
        if (isset($options['join_loai_su_kien']) && $options['join_loai_su_kien']) {
            $this->builder->select('loai_su_kien.ten_loai_su_kien, loai_su_kien.ma_loai_su_kien');
            $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
        }
        
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
        $query = $this->builder->where($this->table . '.deleted_at IS NULL')->get();
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

            // Xử lý tìm kiếm theo loại sự kiện
            if ($field === 'loai_su_kien_id' && $value !== '') {
                $builder->where($this->table . '.' . $field, $value);
                continue;
            }

            // Xử lý tìm kiếm theo trạng thái
            if ($field === 'status' && $value !== '') {
                $builder->where($this->table . '.' . $field, $value);
                continue;
            }

            // Xử lý tìm kiếm theo hình thức
            if ($field === 'hinh_thuc' && $value !== '') {
                $builder->where($this->table . '.' . $field, $value);
                continue;
            }

            // Xử lý tìm kiếm theo thời gian
            if ($field === 'thoi_gian_bat_dau' && $value !== '') {
                $builder->where($this->table . '.' . $field . ' >=', $value);
                continue;
            }

            if ($field === 'thoi_gian_ket_thuc' && $value !== '') {
                $builder->where($this->table . '.' . $field . ' <=', $value);
                continue;
            }

            // Xử lý từ khóa tìm kiếm chung
            if ($field === 'keyword' && $value !== '') {
                $builder->groupStart();
                foreach ($this->searchableFields as $searchField) {
                    $builder->orLike($this->table . '.' . $searchField, $value);
                }
                $builder->groupEnd();
                continue;
            }

            // Xử lý các trường còn lại với tìm kiếm tương đối
            if (in_array($field, $this->searchableFields) && !empty($value)) {
                $builder->like($this->table . '.' . $field, $value);
            }
        }
    }

    /**
     * Lấy danh sách sự kiện dưới dạng mảng key-value cho dropdown
     *
     * @param bool $active Chỉ lấy sự kiện đang hoạt động
     * @return array
     */
    public function getListForDropdown($active = true)
    {
        $builder = $this->builder();
        $builder->select('su_kien_id, ten_su_kien');
        $builder->where('deleted_at IS NULL');
        
        if ($active) {
            $builder->where('status', 1);
        }
        
        $builder->orderBy('thoi_gian_bat_dau', 'DESC');
        $result = $builder->get()->getResult();
        
        $dropdown = [];
        foreach ($result as $item) {
            $dropdown[$item->su_kien_id] = $item->ten_su_kien;
        }
        
        return $dropdown;
    }

    /**
     * Thiết lập số lượng liên kết trang hiển thị xung quanh trang hiện tại
     *
     * @param int $surroundCount Số lượng liên kết
     * @return $this
     */
    public function setSurroundCount($surroundCount)
    {
        $this->surroundCount = $surroundCount;
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
     * Lấy tất cả bản ghi đã xóa
     *
     * @param array $options Mảng tùy chọn
     * @return array
     */
    public function getAllDeleted($options = [])
    {
        $sort = $options['sort'] ?? 'thoi_gian_bat_dau';
        $order = $options['order'] ?? 'DESC';
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
            'ten_su_kien' => 'required|string|max_length[255]',
            'thoi_gian_bat_dau' => 'required',
            'thoi_gian_ket_thuc' => 'required',
            'loai_su_kien_id' => 'required|integer',
            'status' => 'required|integer|in_list[0,1]'
        ];
        
        // Nếu slug được cung cấp, kiểm tra tính duy nhất
        if (isset($data['slug']) && !empty($data['slug'])) {
            if ($action === 'update' && $id) {
                $this->validationRules['slug'] = 'permit_empty|alpha_dash|max_length[255]|is_unique[su_kien.slug,su_kien_id,' . $id . ']';
            } else {
                $this->validationRules['slug'] = 'permit_empty|alpha_dash|max_length[255]|is_unique[su_kien.slug]';
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
            ['created_at', 'updated_at', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc']
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
        
        // Xử lý JSON nếu cần
        if (isset($data['su_kien_poster']) && is_array($data['su_kien_poster'])) {
            $data['su_kien_poster'] = json_encode($data['su_kien_poster']);
        }
        
        if (isset($data['lich_trinh']) && is_array($data['lich_trinh'])) {
            $data['lich_trinh'] = json_encode($data['lich_trinh']);
        }
        
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
    public function getAllPaginated($perPage = 10, $page = 1, $sort = 'thoi_gian_bat_dau', $order = 'DESC')
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
     * Tìm kiếm bản ghi đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn
     * @return array
     */
    public function searchDeleted($criteria = [], $options = [])
    {
        $builder = $this->builder();
        $builder->select('*');
        
        // Chỉ lấy bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Áp dụng điều kiện tìm kiếm
        if (!empty($criteria['keyword'])) {
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($this->table . '.' . $field, $criteria['keyword']);
            }
            $builder->groupEnd();
        }
        
        if (isset($criteria['loai_su_kien_id']) && $criteria['loai_su_kien_id'] !== '') {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        if (isset($criteria['hinh_thuc']) && $criteria['hinh_thuc'] !== '') {
            $builder->where($this->table . '.hinh_thuc', $criteria['hinh_thuc']);
        }
        
        // Sắp xếp mặc định theo ngày xóa giảm dần
        $sort = $options['sort'] ?? 'deleted_at';
        $order = $options['order'] ?? 'DESC';
        
        // Đảm bảo thêm tên bảng vào trường sắp xếp
        if (strpos($sort, '.') === false) {
            $sort = $this->table . '.' . $sort;
        }
        
        $builder->orderBy($sort, $order);
        
        // Phân trang
        if (!empty($options['limit'])) {
            $builder->limit($options['limit'], $options['offset'] ?? 0);
        }
        
        return $builder->get()->getResult($this->returnType);
    }

    /**
     * Tạo mã QR code ngẫu nhiên cho sự kiện
     *
     * @param int $length Độ dài mã QR code
     * @return string
     */
    public function generateQRCode(int $length = 10): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }

    /**
     * Cập nhật số lượt xem
     *
     * @param int $eventId ID của sự kiện
     * @return bool Kết quả cập nhật
     */
    public function updateViewCount($eventId)
    {
        try {
            $builder = $this->builder();
            $builder->set('so_luot_xem', 'so_luot_xem + 1', false);
            $builder->where('su_kien_id', $eventId);
            $builder->update();
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi cập nhật số lượt xem: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách sự kiện sắp diễn ra
     *
     * @param int $limit Giới hạn số lượng
     * @return array
     */
    public function getUpcomingEvents($limit = 5)
    {
        $now = Time::now()->toDateTimeString();
        
        return $this->where('thoi_gian_bat_dau >', $now)
                    ->where('status', 1)
                    ->orderBy('thoi_gian_bat_dau', 'ASC')
                    ->limit($limit)
                    ->find();
    }

    /**
     * Lấy danh sách sự kiện đang diễn ra
     *
     * @param int $limit Giới hạn số lượng
     * @return array
     */
    public function getOngoingEvents($limit = 5)
    {
        $now = Time::now()->toDateTimeString();
        
        return $this->where('thoi_gian_bat_dau <=', $now)
                    ->where('thoi_gian_ket_thuc >=', $now)
                    ->where('status', 1)
                    ->orderBy('thoi_gian_bat_dau', 'DESC')
                    ->limit($limit)
                    ->find();
    }

    /**
     * Lấy headers cho xuất Excel
     *
     * @param bool $includeDeleted Có bao gồm cột ngày xóa không
     * @return array
     */
    public function getExportHeaders($includeDeleted = false)
    {
        $headers = [
            'STT' => 'A',
            'ID' => 'B',
            'Tên sự kiện' => 'C',
            'Loại sự kiện' => 'D',
            'Thời gian bắt đầu' => 'E',
            'Thời gian kết thúc' => 'F',
            'Địa điểm' => 'G',
            'Hình thức' => 'H',
            'Số lượng tham gia' => 'I',
            'Tổng đăng ký' => 'J',
            'Tổng check-in' => 'K',
            'Trạng thái' => 'L',
            'Ngày tạo' => 'M',
            'Ngày cập nhật' => 'N'
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'O';
        }

        return $headers;
    }

    /**
     * Lấy sự kiện theo slug
     *
     * @param string $slug Slug của sự kiện
     * @return object|null Đối tượng sự kiện hoặc null nếu không tìm thấy
     */
    public function getEventBySlug($slug)
    {
        $builder = $this->builder();
        $builder->where('slug', $slug);
        $builder->where('deleted_at IS NULL');
        $builder->where('status', 1);
        
        $event = $builder->get()->getRow();
        
        return $event;
    }

    /**
     * Lấy tất cả sự kiện cho trang danh sách
     *
     * @return array Mảng chứa thông tin sự kiện
     */
    public function getAllEvents()
    {
        $options = [
            'limit' => 0, // Lấy tất cả
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'ASC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search([], $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
            ];
        }
        
        return $result;
    }
    
    /**
     * Tìm kiếm sự kiện bằng từ khóa
     *
     * @param string $keyword Từ khóa tìm kiếm
     * @return array Mảng chứa kết quả tìm kiếm
     */
    public function searchEvents($keyword)
    {
        $criteria = [
            'keyword' => $keyword,
            'status' => 1
        ];
        
        $options = [
            'limit' => 0,
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy đăng ký sự kiện
     *
     * @param int $eventId ID của sự kiện
     * @return array Danh sách đăng ký cho sự kiện
     */
    public function getRegistrations($eventId)
    {
        // Kết nối với model đăng ký sự kiện từ module quanlydangkysukien
        $dangKySukienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
        
        // Lấy danh sách đăng ký cho sự kiện
        $registrations = $dangKySukienModel->where('su_kien_id', $eventId)
                                         ->where('deleted_at IS NULL')
                                         ->findAll();
        
        // Chuẩn hóa kết quả để đảm bảo luôn trả về mảng với cấu trúc nhất quán
        $result = [];
        foreach ($registrations as $reg) {
            if (is_object($reg)) {
                $result[] = [
                    'dangky_sukien_id' => $reg->dangky_sukien_id ?? null,
                    'ho_ten' => $reg->ho_ten ?? '',
                    'email' => $reg->email ?? '',
                    'dien_thoai' => $reg->dien_thoai ?? '',
                    'ngay_dang_ky' => $reg->ngay_dang_ky ?? ($reg->created_at ?? ''),
                    'da_tham_gia' => $reg->da_check_in ?? 0,
                    'attendance_status' => $reg->attendance_status ?? '',
                    'ma_xac_nhan' => $reg->ma_xac_nhan ?? '',
                    'status' => $reg->status ?? 0,
                    'noi_dung_gop_y' => $reg->noi_dung_gop_y ?? '',
                    'nguon_gioi_thieu' => $reg->nguon_gioi_thieu ?? '',
                    'hinh_thuc_tham_gia' => $reg->hinh_thuc_tham_gia ?? '',
                    'ly_do_tham_du' => $reg->ly_do_tham_du ?? ''
                ];
            } else if (is_array($reg)) {
                // Đảm bảo tất cả các khóa cần thiết đều tồn tại
                $formattedReg = $reg;
                $keysToCheck = [
                    'dangky_sukien_id', 'ho_ten', 'email', 'dien_thoai', 'ngay_dang_ky',
                    'da_tham_gia', 'attendance_status', 'ma_xac_nhan', 'status',
                    'noi_dung_gop_y', 'nguon_gioi_thieu', 'hinh_thuc_tham_gia', 'ly_do_tham_du'
                ];
                
                foreach ($keysToCheck as $key) {
                    if (!isset($formattedReg[$key])) {
                        $formattedReg[$key] = '';
                    }
                }
                
                $result[] = $formattedReg;
            }
        }
        
        return $result;
    }
    
    /**
     * Lấy sự kiện theo loại
     *
     * @param string $category Tên hoặc slug của loại sự kiện
     * @return array Danh sách sự kiện thuộc loại
     */
    public function getEventsByCategory($category)
    {
        // Tìm ID loại sự kiện từ tên
        $loaiSukienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
        $loaiSukien = $loaiSukienModel->where('ten_loai_su_kien', $category)
                                     ->where('deleted_at IS NULL')
                                     ->first();
        
        $loaiSuKienId = $loaiSukien ? $loaiSukien->loai_su_kien_id : 0;
        
        $criteria = [
            'status' => 1,
            'loai_su_kien_id' => $loaiSuKienId
        ];
        
        $options = [
            'limit' => 0, // Lấy tất cả
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy các sự kiện nổi bật để hiển thị trên trang chủ
     *
     * @return array Mảng chứa thông tin sự kiện nổi bật
     */
    public function getFeaturedEvents()
    {
        // Sự kiện nổi bật có thể là sự kiện có thời gian gần nhất hoặc được đánh dấu đặc biệt
        $criteria = [
            'status' => 1
        ];
        
        $options = [
            'limit' => 3,
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'ASC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
            ];
        }
        
        return $result;
    }

    /**
     * Phương thức lấy tổng số sự kiện
     * 
     * @return int
     */
    public function getTotalEvents()
    {
        return $this->where('status', 1)->where('deleted_at IS NULL')->countAllResults();
    }

    /**
     * Phương thức lấy tổng số người tham gia
     * 
     * @return int
     */
    public function getTotalParticipants()
    {
        $builder = $this->builder();
        $builder->select('SUM(tong_dang_ky) as total');
        $builder->where('status', 1);
        $builder->where('deleted_at IS NULL');
        $result = $builder->get()->getRow();
        
        return (int)($result->total ?? 0);
    }

    /**
     * Phương thức lấy tổng số diễn giả
     * 
     * @return int
     */
    public function getTotalSpeakers()
    {
        $builder = $this->builder();
        $builder->select('SUM(so_luong_dien_gia) as total');
        $builder->where('status', 1);
        $builder->where('deleted_at IS NULL');
        $result = $builder->get()->getRow();
        
        return (int)($result->total ?? 0);
    }

    /**
     * Phương thức lấy danh sách diễn giả
     * 
     * @param int $limit Giới hạn số lượng
     * @return array
     */
    public function getSpeakers($limit = 4)
    {
        // Kết nối với model diễn giả từ module quanlydiengia
        try {
            $dienGiaModel = new \App\Modules\quanlydiengia\Models\DienGiaModel();
            
            // Lấy danh sách diễn giả hàng đầu
            $speakers = $dienGiaModel->where('deleted_at IS NULL')
                                    ->where('status', 1)
                                    ->orderBy('created_at', 'DESC')
                                    ->limit($limit)
                                    ->findAll();
            
            // Định dạng kết quả
            $result = [];
            foreach ($speakers as $speaker) {
                $result[] = [
                    'id' => $speaker->dien_gia_id,
                    'name' => $speaker->ho_ten,
                    'position' => $speaker->chuc_vu,
                    'organization' => $speaker->don_vi,
                    'image' => $speaker->avatar,
                    'bio' => $speaker->tieu_su
                ];
            }
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Không thể kết nối với module quanlydiengia: ' . $e->getMessage());
            
            // Trả về dữ liệu mẫu
            return [
                [
                    'id' => 1,
                    'name' => 'PGS.TS Nguyễn Đức Trung',
                    'position' => 'Hiệu Trưởng',
                    'organization' => 'Trường Đại học Ngân hàng TP.HCM',
                    'image' => 'assets/img/speakers/speaker-1.jpg',
                    'bio' => 'Chuyên gia trong lĩnh vực quản trị và tài chính ngân hàng.'
                ],
                [
                    'id' => 2,
                    'name' => 'TS. Lê Thị Vân',
                    'position' => 'Trưởng Khoa CNTT',
                    'organization' => 'Trường Đại học Ngân hàng TP.HCM',
                    'image' => 'assets/img/speakers/speaker-2.jpg',
                    'bio' => 'Chuyên gia về công nghệ thông tin và trí tuệ nhân tạo trong lĩnh vực tài chính.'
                ],
                [
                    'id' => 3,
                    'name' => 'TS. Trần Minh Tuấn',
                    'position' => 'Giám đốc',
                    'organization' => 'Viện Nghiên cứu Kinh tế',
                    'image' => 'assets/img/speakers/speaker-3.jpg',
                    'bio' => 'Nhà nghiên cứu kinh tế với nhiều công trình nghiên cứu xuất sắc.'
                ],
                [
                    'id' => 4,
                    'name' => 'ThS. Phạm Thanh Hà',
                    'position' => 'Chuyên gia Marketing',
                    'organization' => 'Công ty ABC',
                    'image' => 'assets/img/speakers/speaker-4.jpg',
                    'bio' => 'Chuyên gia marketing với hơn 10 năm kinh nghiệm trong ngành.'
                ]
            ];
        }
    }

    /**
     * Lấy các sự kiện liên quan (cùng loại)
     *
     * @param int $eventId ID của sự kiện hiện tại
     * @param string $category Loại sự kiện
     * @param int $limit Số lượng sự kiện liên quan
     * @return array Danh sách sự kiện liên quan
     */
    public function getRelatedEvents($eventId, $category, $limit = 3)
    {
        // Tìm ID loại sự kiện từ tên
        $loaiSukienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
        $loaiSukien = $loaiSukienModel->where('ten_loai_su_kien', $category)
                                     ->where('deleted_at IS NULL')
                                     ->first();
        
        $loaiSuKienId = $loaiSukien ? $loaiSukien->loai_su_kien_id : 0;
        
        $criteria = [
            'status' => 1,
            'loai_su_kien_id' => $loaiSuKienId
        ];
        
        $options = [
            'limit' => $limit + 1, // Lấy thêm 1 để loại trừ sự kiện hiện tại
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search($criteria, $options);
        
        // Loại bỏ sự kiện hiện tại và giới hạn kết quả
        $result = [];
        $count = 0;
        foreach ($events as $event) {
            if ($event->su_kien_id != $eventId && $count < $limit) {
                $result[] = $event;
                $count++;
            }
        }
        
        return $result;
    }

    /**
     * Lấy lịch trình của sự kiện
     *
     * @param int $eventId ID của sự kiện
     * @return array Lịch trình sự kiện
     */
    public function getEventSchedule($eventId)
    {
        $event = $this->find($eventId);
        
        if (!$event || empty($event->lich_trinh)) {
            return [];
        }
        
        // Kiểm tra xem $event->lich_trinh đã là mảng chưa
        if (is_array($event->lich_trinh)) {
            $schedule = $event->lich_trinh;
        }
        // Nếu là chuỗi thì giải mã JSON
        elseif (is_string($event->lich_trinh)) {
            $schedule = json_decode($event->lich_trinh, true);
            
            // Nếu không phải mảng hoặc rỗng, trả về mảng trống
            if (!is_array($schedule) || empty($schedule)) {
                return [];
            }
        }
        // Trường hợp khác, trả về mảng trống
        else {
            return [];
        }
        
        // Kiểm tra và chuẩn hóa dữ liệu cho mỗi mục lịch trình
        foreach ($schedule as $key => $item) {
            // Đảm bảo có trường thoi_gian, nếu không có thì tạo từ gio_bat_dau hoặc thoi_diem nếu có
            if (!isset($item['thoi_gian'])) {
                if (isset($item['gio_bat_dau'])) {
                    $schedule[$key]['thoi_gian'] = $item['gio_bat_dau'];
                } elseif (isset($item['thoi_diem'])) {
                    $schedule[$key]['thoi_gian'] = $item['thoi_diem'];
                } elseif (isset($item['time'])) {
                    $schedule[$key]['thoi_gian'] = $item['time'];
                } else {
                    $schedule[$key]['thoi_gian'] = '--:--';
                }
            }
            
            // Đảm bảo có trường tieu_de
            if (!isset($item['tieu_de'])) {
                if (isset($item['title'])) {
                    $schedule[$key]['tieu_de'] = $item['title'];
                } elseif (isset($item['ten'])) {
                    $schedule[$key]['tieu_de'] = $item['ten'];
                } else {
                    $schedule[$key]['tieu_de'] = 'Chưa có tiêu đề';
                }
            }
            
            // Đảm bảo có trường mo_ta
            if (!isset($item['mo_ta'])) {
                if (isset($item['description'])) {
                    $schedule[$key]['mo_ta'] = $item['description'];
                } elseif (isset($item['noi_dung'])) {
                    $schedule[$key]['mo_ta'] = $item['noi_dung'];
                } else {
                    $schedule[$key]['mo_ta'] = '';
                }
            }
            
            // Đảm bảo có trường dien_gia
            if (!isset($item['dien_gia'])) {
                if (isset($item['speaker'])) {
                    $schedule[$key]['dien_gia'] = $item['speaker'];
                } elseif (isset($item['nguoi_trinh_bay'])) {
                    $schedule[$key]['dien_gia'] = $item['nguoi_trinh_bay'];
                } else {
                    $schedule[$key]['dien_gia'] = '';
                }
            }
        }
        
        return $schedule;
    }

    /**
     * Lấy thông tin sự kiện theo ID
     *
     * @param int $eventId ID của sự kiện
     * @return array|null Thông tin sự kiện hoặc null nếu không tìm thấy
     */
    public function getEvent($eventId)
    {
        $event = $this->find($eventId);
        
        if (!$event) {
            return null;
        }
        
        // Lấy thông tin loại sự kiện
        $loaiSukienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
        $loaiSukien = $loaiSukienModel->find($event->loai_su_kien_id);
        
        // Xử lý lịch trình
        $lich_trinh = null;
        if (!empty($event->lich_trinh)) {
            if (is_array($event->lich_trinh)) {
                $lich_trinh = $event->lich_trinh;
            } elseif (is_string($event->lich_trinh)) {
                $lich_trinh = json_decode($event->lich_trinh, true);
            }
        }
        
        // Chuyển đổi sang định dạng mảng cho view
        return [
            'su_kien_id' => $event->su_kien_id,
            'ten_su_kien' => $event->ten_su_kien,
            'mo_ta_su_kien' => $event->mo_ta,
            'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
            'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
            'dia_diem' => $event->dia_diem,
            'hinh_anh' => $event->su_kien_poster,
            'gio_bat_dau' => $event->gio_bat_dau,
            'gio_ket_thuc' => $event->gio_ket_thuc,
            'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
            'loai_su_kien_id' => $event->loai_su_kien_id,
            'loai_su_kien' => $loaiSukien ? $loaiSukien->ten_loai_su_kien : '',
            'slug' => $event->slug,
            'so_luot_xem' => $event->so_luot_xem,
            'lich_trinh' => $lich_trinh,
            'so_luong_tham_gia' => $event->so_luong_tham_gia ?? 0
        ];
    }
} 