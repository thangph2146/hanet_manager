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
        'mo_ta_su_kien',
        'chi_tiet_su_kien',
        'dia_diem',
        'dia_chi_cu_the',
        'tu_khoa_su_kien',
        'hashtag'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'loai_su_kien_id',
        'status',
        'hinh_thuc'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Pager
    public $pager = null;
    
    /**
     * Lấy tất cả sự kiện
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'thoi_gian_bat_dau', $order = 'DESC')
    {
        $builder = $this->builder();
        
        // Chỉ lấy bản ghi chưa xóa
        $builder->where($this->table . '.deleted_at IS NULL');
        
        if ($sort && $order) {
            $builder->orderBy($this->table . '.' . $sort, $order);
        }
        
        $total = $this->countAllResults();
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
     * Đếm tổng số bản ghi
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
     * Lấy sự kiện theo ID
     *
     * @param int $id ID của sự kiện
     * @return SuKien|null
     */
    public function getSuKien(int $id)
    {
        return $this->find($id);
    }
    
    /**
     * Lấy sự kiện theo slug
     *
     * @param string $slug Slug của sự kiện
     * @return SuKien|null
     */
    public function getSuKienBySlug(string $slug)
    {
        return $this->where('slug', $slug)->first();
    }
    
    /**
     * Tìm kiếm sự kiện theo các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Các tùy chọn phân trang và sắp xếp
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
        
        // Xử lý lọc theo loại sự kiện
        if (isset($criteria['loai_su_kien_id'])) {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        // Lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo hình thức
        if (isset($criteria['hinh_thuc'])) {
            $builder->where($this->table . '.hinh_thuc', $criteria['hinh_thuc']);
        }
        
        // Lọc theo thời gian
        if (isset($criteria['thoi_gian_bat_dau_from'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau >=', $criteria['thoi_gian_bat_dau_from']);
        }
        
        if (isset($criteria['thoi_gian_bat_dau_to'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau <=', $criteria['thoi_gian_bat_dau_to']);
        }
        
        if (isset($criteria['thoi_gian_ket_thuc_from'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc >=', $criteria['thoi_gian_ket_thuc_from']);
        }
        
        if (isset($criteria['thoi_gian_ket_thuc_to'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc <=', $criteria['thoi_gian_ket_thuc_to']);
        }
        
        // Tìm kiếm theo từ khóa
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
        $sort = $options['sort'] ?? 'thoi_gian_bat_dau';
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
     * Đếm số kết quả tìm kiếm
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
        
        // Xử lý lọc theo loại sự kiện
        if (isset($criteria['loai_su_kien_id'])) {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        // Lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo hình thức
        if (isset($criteria['hinh_thuc'])) {
            $builder->where($this->table . '.hinh_thuc', $criteria['hinh_thuc']);
        }
        
        // Lọc theo thời gian
        if (isset($criteria['thoi_gian_bat_dau_from'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau >=', $criteria['thoi_gian_bat_dau_from']);
        }
        
        if (isset($criteria['thoi_gian_bat_dau_to'])) {
            $builder->where($this->table . '.thoi_gian_bat_dau <=', $criteria['thoi_gian_bat_dau_to']);
        }
        
        if (isset($criteria['thoi_gian_ket_thuc_from'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc >=', $criteria['thoi_gian_ket_thuc_from']);
        }
        
        if (isset($criteria['thoi_gian_ket_thuc_to'])) {
            $builder->where($this->table . '.thoi_gian_ket_thuc <=', $criteria['thoi_gian_ket_thuc_to']);
        }
        
        // Tìm kiếm theo từ khóa
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
     * Tăng số lượt xem cho sự kiện
     *
     * @param int $id ID của sự kiện
     * @return bool
     */
    public function increaseViewCount(int $id): bool
    {
        $data = $this->find($id);
        if ($data) {
            return $this->update($id, [
                'so_luot_xem' => $data->getSoLuotXem() + 1
            ]);
        }
        return false;
    }
    
    /**
     * Tăng tổng số đăng ký cho sự kiện
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng tăng
     * @return bool
     */
    public function increaseTongDangKy(int $id, int $count = 1): bool
    {
        $data = $this->find($id);
        if ($data) {
            return $this->update($id, [
                'tong_dang_ky' => $data->getTongDangKy() + $count
            ]);
        }
        return false;
    }
    
    /**
     * Tăng tổng số check-in cho sự kiện
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng tăng
     * @return bool
     */
    public function increaseTongCheckIn(int $id, int $count = 1): bool
    {
        $data = $this->find($id);
        if ($data) {
            return $this->update($id, [
                'tong_check_in' => $data->getTongCheckIn() + $count
            ]);
        }
        return false;
    }
    
    /**
     * Tăng tổng số check-out cho sự kiện
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng tăng
     * @return bool
     */
    public function increaseTongCheckOut(int $id, int $count = 1): bool
    {
        $data = $this->find($id);
        if ($data) {
            return $this->update($id, [
                'tong_check_out' => $data->getTongCheckOut() + $count
            ]);
        }
        return false;
    }
    
    /**
     * Tạo slug từ tên sự kiện
     *
     * @param string $tenSuKien Tên sự kiện
     * @param int|null $suKienId ID sự kiện (nếu đang cập nhật)
     * @return string
     */
    public function createSlug(string $tenSuKien, ?int $suKienId = null): string
    {
        // Tạo slug cơ bản từ tên sự kiện
        $slug = url_title($tenSuKien, '-', true);
        
        // Kiểm tra xem slug đã tồn tại chưa
        $builder = $this->builder();
        $builder->where('slug', $slug);
        
        // Nếu đang cập nhật, loại trừ bản ghi hiện tại
        if ($suKienId !== null) {
            $builder->where('su_kien_id !=', $suKienId);
        }
        
        // Nếu slug đã tồn tại, thêm số vào cuối
        if ($builder->countAllResults() > 0) {
            $i = 1;
            $originalSlug = $slug;
            
            while (true) {
                $newSlug = $originalSlug . '-' . $i;
                
                $builder = $this->builder();
                $builder->where('slug', $newSlug);
                
                if ($suKienId !== null) {
                    $builder->where('su_kien_id !=', $suKienId);
                }
                
                if ($builder->countAllResults() === 0) {
                    $slug = $newSlug;
                    break;
                }
                
                $i++;
            }
        }
        
        return $slug;
    }
    
    /**
     * Chuẩn bị quy tắc validation
     *
     * @param string $scenario Tình huống sử dụng (insert/update)
     * @param array $data Dữ liệu để xác thực
     * @return array Các quy tắc xác thực
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = null)
    {
        $rules = [
            'ten_su_kien' => [
                'label' => 'Tên sự kiện',
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Tên sự kiện là bắt buộc',
                    'min_length' => 'Tên sự kiện phải có ít nhất {param} ký tự',
                    'max_length' => 'Tên sự kiện không được vượt quá {param} ký tự'
                ]
            ],
            'mo_ta' => [
                'label' => 'Mô tả',
                'rules' => 'permit_empty|max_length[1000]',
                'errors' => [
                    'max_length' => 'Mô tả không được vượt quá {param} ký tự'
                ]
            ],
            'thoi_gian_bat_dau' => [
                'label' => 'Thời gian bắt đầu',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Thời gian bắt đầu là bắt buộc',
                    'valid_date' => 'Thời gian bắt đầu không hợp lệ'
                ]
            ],
            'thoi_gian_ket_thuc' => [
                'label' => 'Thời gian kết thúc',
                'rules' => 'required|valid_date|datetime_greater_than[thoi_gian_bat_dau]',
                'errors' => [
                    'required' => 'Thời gian kết thúc là bắt buộc',
                    'valid_date' => 'Thời gian kết thúc không hợp lệ',
                    'datetime_greater_than' => 'Thời gian kết thúc phải sau thời gian bắt đầu'
                ]
            ],
            'bat_dau_dang_ky' => [
                'label' => 'Thời gian bắt đầu đăng ký',
                'rules' => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Thời gian bắt đầu đăng ký không hợp lệ'
                ]
            ],
            'ket_thuc_dang_ky' => [
                'label' => 'Thời gian kết thúc đăng ký',
                'rules' => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Thời gian kết thúc đăng ký không hợp lệ'
                ]
            ],
            'hinh_thuc' => [
                'label' => 'Hình thức',
                'rules' => 'required|in_list[online,offline,hybrid]',
                'errors' => [
                    'required' => 'Hình thức là bắt buộc',
                    'in_list' => 'Hình thức phải là một trong các giá trị: online, offline, hybrid'
                ]
            ],
            'dia_diem' => [
                'label' => 'Địa điểm',
                'rules' => 'required_if[hinh_thuc,offline,hybrid]|max_length[255]',
                'errors' => [
                    'required_if' => 'Địa điểm là bắt buộc khi hình thức là offline hoặc hybrid',
                    'max_length' => 'Địa điểm không được vượt quá {param} ký tự'
                ]
            ],
            'link_online' => [
                'label' => 'Link trực tuyến',
                'rules' => 'required_if[hinh_thuc,online,hybrid]|max_length[255]',
                'errors' => [
                    'required_if' => 'Link trực tuyến là bắt buộc khi hình thức là online hoặc hybrid',
                    'max_length' => 'Link trực tuyến không được vượt quá {param} ký tự'
                ]
            ],
            'so_luong_tham_gia' => [
                'label' => 'Số lượng người tham gia',
                'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
                'errors' => [
                    'integer' => 'Số lượng người tham gia phải là số nguyên',
                    'greater_than_equal_to' => 'Số lượng người tham gia phải lớn hơn hoặc bằng {param}'
                ]
            ],
            'loai_su_kien_id' => [
                'label' => 'Loại sự kiện',
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Loại sự kiện không hợp lệ'
                ]
            ],
            'status' => [
                'label' => 'Trạng thái',
                'rules' => 'permit_empty|in_list[0,1]',
                'errors' => [
                    'in_list' => 'Trạng thái không hợp lệ'
                ]
            ]
        ];
        
        // Kiểm tra nếu cho phép đăng ký, thêm quy tắc kiểm tra thời gian đăng ký
        if (isset($data) && isset($data['cho_phep_dang_ky']) && $data['cho_phep_dang_ky'] == 1) {
            $rules['bat_dau_dang_ky']['rules'] = 'required|valid_date';
            $rules['bat_dau_dang_ky']['errors']['required'] = 'Thời gian bắt đầu đăng ký là bắt buộc khi cho phép đăng ký';
            
            $rules['ket_thuc_dang_ky']['rules'] = 'required|valid_date|datetime_greater_than[bat_dau_dang_ky]';
            $rules['ket_thuc_dang_ky']['errors']['required'] = 'Thời gian kết thúc đăng ký là bắt buộc khi cho phép đăng ký';
            $rules['ket_thuc_dang_ky']['errors']['datetime_greater_than'] = 'Thời gian kết thúc đăng ký phải sau thời gian bắt đầu đăng ký';
        }
        
        $this->validationRules = $rules;
        return $rules;
    }
    
    /**
     * Lấy các sự kiện sắp diễn ra
     *
     * @param int $limit Số lượng bản ghi
     * @return array
     */
    public function getUpcomingEvents(int $limit = 5): array
    {
        $now = date('Y-m-d H:i:s');
        
        return $this->where('thoi_gian_bat_dau >', $now)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->orderBy('thoi_gian_bat_dau', 'ASC')
                    ->limit($limit)
                    ->find();
    }
    
    /**
     * Lấy các sự kiện đang diễn ra
     *
     * @param int $limit Số lượng bản ghi
     * @return array
     */
    public function getOngoingEvents(int $limit = 5): array
    {
        $now = date('Y-m-d H:i:s');
        
        return $this->where('thoi_gian_bat_dau <=', $now)
                    ->where('thoi_gian_ket_thuc >=', $now)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->orderBy('thoi_gian_bat_dau', 'DESC')
                    ->limit($limit)
                    ->find();
    }
    
    /**
     * Lấy các sự kiện đã kết thúc
     *
     * @param int $limit Số lượng bản ghi
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @return array
     */
    public function getPastEvents(int $limit = 10, int $offset = 0): array
    {
        $now = date('Y-m-d H:i:s');
        
        return $this->where('thoi_gian_ket_thuc <', $now)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->orderBy('thoi_gian_ket_thuc', 'DESC')
                    ->limit($limit, $offset)
                    ->find();
    }
    
    /**
     * Lấy các sự kiện phổ biến
     *
     * @param int $limit Số lượng bản ghi
     * @return array
     */
    public function getPopularEvents(int $limit = 5): array
    {
        return $this->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->orderBy('so_luot_xem', 'DESC')
                    ->orderBy('tong_dang_ky', 'DESC')
                    ->limit($limit)
                    ->find();
    }
    
    /**
     * Lấy sự kiện theo loại
     *
     * @param int $loaiSuKienId ID loại sự kiện
     * @param int $limit Số lượng bản ghi
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @return array
     */
    public function getEventsByType(int $loaiSuKienId, int $limit = 10, int $offset = 0): array
    {
        return $this->where('loai_su_kien_id', $loaiSuKienId)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->orderBy('thoi_gian_bat_dau', 'DESC')
                    ->limit($limit, $offset)
                    ->find();
    }
    
    /**
     * Lấy sự kiện theo hình thức (online, offline, hybrid)
     *
     * @param string $hinhThuc Hình thức sự kiện
     * @param int $limit Số lượng bản ghi
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @return array
     */
    public function getEventsByFormat(string $hinhThuc, int $limit = 10, int $offset = 0): array
    {
        return $this->where('hinh_thuc', $hinhThuc)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->orderBy('thoi_gian_bat_dau', 'DESC')
                    ->limit($limit, $offset)
                    ->find();
    }
    
    /**
     * Đếm số sự kiện theo loại
     *
     * @param int $loaiSuKienId ID loại sự kiện
     * @return int
     */
    public function countEventsByType(int $loaiSuKienId): int
    {
        return $this->where('loai_su_kien_id', $loaiSuKienId)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->countAllResults();
    }
    
    /**
     * Đếm số sự kiện theo hình thức
     *
     * @param string $hinhThuc Hình thức sự kiện
     * @return int
     */
    public function countEventsByFormat(string $hinhThuc): int
    {
        return $this->where('hinh_thuc', $hinhThuc)
                    ->where('status', 1)
                    ->where('deleted_at IS NULL')
                    ->countAllResults();
    }
    
    /**
     * Đếm số sự kiện theo trạng thái thời gian
     *
     * @param string $timeStatus Trạng thái thời gian (upcoming, ongoing, past)
     * @return int
     */
    public function countEventsByTimeStatus(string $timeStatus): int
    {
        $now = date('Y-m-d H:i:s');
        $builder = $this->builder();
        
        $builder->where('status', 1)
                ->where('deleted_at IS NULL');
        
        if ($timeStatus === 'upcoming') {
            $builder->where('thoi_gian_bat_dau >', $now);
        } elseif ($timeStatus === 'ongoing') {
            $builder->where('thoi_gian_bat_dau <=', $now)
                    ->where('thoi_gian_ket_thuc >=', $now);
        } elseif ($timeStatus === 'past') {
            $builder->where('thoi_gian_ket_thuc <', $now);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm dữ liệu đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        $builder = $this->builder();
        
        // Lấy các bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo loại sự kiện
        if (isset($criteria['loai_su_kien_id'])) {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        // Lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo hình thức
        if (isset($criteria['hinh_thuc'])) {
            $builder->where($this->table . '.hinh_thuc', $criteria['hinh_thuc']);
        }
        
        // Tìm kiếm theo từ khóa
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
     * Đếm số kết quả tìm kiếm đã xóa
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @return int
     */
    public function countDeletedSearchResults(array $criteria = [])
    {
        $builder = $this->builder();
        
        // Lấy các bản ghi đã xóa
        $builder->where($this->table . '.deleted_at IS NOT NULL');
        
        // Xử lý lọc theo loại sự kiện
        if (isset($criteria['loai_su_kien_id'])) {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        // Lọc theo trạng thái
        if (isset($criteria['status'])) {
            $builder->where($this->table . '.status', $criteria['status']);
        }
        
        // Lọc theo hình thức
        if (isset($criteria['hinh_thuc'])) {
            $builder->where($this->table . '.hinh_thuc', $criteria['hinh_thuc']);
        }
        
        // Tìm kiếm theo từ khóa
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
     * Xóa nhiều bản ghi cùng lúc
     *
     * @param array $ids Danh sách ID cần xóa
     * @param array $currentParams Các tham số hiện tại
     * @return array
     */
    public function deleteMultiple(array $ids, array $currentParams = []): array
    {
        $result = [
            'success' => 0,
            'failed' => 0,
            'messages' => []
        ];
        
        if (empty($ids)) {
            $result['messages'][] = 'Không có ID nào được chọn để xóa';
            return $result;
        }
        
        foreach ($ids as $id) {
            try {
                if ($this->delete($id)) {
                    $result['success']++;
                } else {
                    $result['failed']++;
                    $result['messages'][] = "Không thể xóa bản ghi có ID: $id";
                }
            } catch (\Exception $e) {
                $result['failed']++;
                $result['messages'][] = "Lỗi khi xóa bản ghi có ID: $id - " . $e->getMessage();
            }
        }
        
        return $result;
    }
    
    /**
     * Khôi phục nhiều bản ghi cùng lúc
     *
     * @param array $ids Danh sách ID cần khôi phục
     * @param array $currentParams Các tham số hiện tại
     * @return array
     */
    public function restoreMultiple(array $ids, array $currentParams = []): array
    {
        $result = [
            'success' => 0,
            'failed' => 0,
            'messages' => []
        ];
        
        if (empty($ids)) {
            $result['messages'][] = 'Không có ID nào được chọn để khôi phục';
            return $result;
        }
        
        foreach ($ids as $id) {
            try {
                $data = $this->onlyDeleted()->find($id);
                
                if ($data) {
                    $this->update($id, ['deleted_at' => null]);
                    $result['success']++;
                } else {
                    $result['failed']++;
                    $result['messages'][] = "Không tìm thấy bản ghi đã xóa có ID: $id";
                }
            } catch (\Exception $e) {
                $result['failed']++;
                $result['messages'][] = "Lỗi khi khôi phục bản ghi có ID: $id - " . $e->getMessage();
            }
        }
        
        return $result;
    }
    
    /**
     * Định dạng thời gian
     *
     * @param string $dateTimeString Chuỗi thời gian đầu vào
     * @return string|null
     */
    public function formatDateTime($dateTimeString)
    {
        if (empty($dateTimeString)) {
            return null;
        }
        
        // Xử lý các định dạng thời gian phổ biến
        $formats = [
            'd/m/Y H:i:s',
            'd/m/Y H:i',
            'd/m/Y',
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'Y-m-d'
        ];
        
        $time = null;
        
        foreach ($formats as $format) {
            try {
                $time = Time::createFromFormat($format, $dateTimeString);
                if ($time !== false) {
                    break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // Nếu không nhận dạng được định dạng, thử phân tích trực tiếp
        if ($time === null || $time === false) {
            try {
                $time = new Time($dateTimeString);
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return $time->toDateTimeString();
    }
    
    /**
     * Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
     *
     * @param int $count Số liên kết trang hiển thị
     * @return $this
     */
    public function setSurroundCount(int $count)
    {
        $this->surroundCount = $count;
        return $this;
    }
    
    /**
     * Lấy pager
     *
     * @return Pager|null
     */
    public function getPager()
    {
        return $this->pager;
    }
} 