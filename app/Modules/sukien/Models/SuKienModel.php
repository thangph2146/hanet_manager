<?php

namespace App\Modules\sukien\Models;

use App\Models\BaseModel;
use App\Modules\sukien\Entities\SuKien;
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
        
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập phân trang
        $pager = service('pager');
        $pager->setPath('sukien');
        $pager->makeLinks(ceil($offset / $limit) + 1, $limit, $total);
        
        return $result;
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
        
        // Mặc định chỉ lấy dữ liệu chưa xóa trừ khi có yêu cầu khác
        if (isset($criteria['deleted']) && $criteria['deleted'] === true) {
            $builder->where($this->table . '.deleted_at IS NOT NULL');
        } else {
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        // Lọc theo loại sự kiện
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
        
        // Đếm tổng số kết quả cho phân trang
        $total = $builder->countAllResults(false);
        
        // Thực hiện truy vấn với phân trang
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        // Sắp xếp kết quả
        $builder->orderBy($this->table . '.' . $sort, $order);
        
        // Thực hiện truy vấn
        $result = $builder->get()->getResult($this->returnType);
        
        // Thiết lập phân trang
        $pager = service('pager');
        $pager->setPath('sukien/search');
        $pager->makeLinks(ceil($offset / $limit) + 1, $limit, $total);
        
        return $result;
    }
    
    /**
     * Tăng số lượt xem cho sự kiện
     *
     * @param int $id ID của sự kiện
     * @return bool
     */
    public function increaseViewCount(int $id): bool
    {
        $builder = $this->builder();
        $builder->set('so_luot_xem', 'so_luot_xem + 1', false);
        $builder->where($this->primaryKey, $id);
        return $builder->update();
    }
    
    /**
     * Tăng số lượng đăng ký tham gia
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng tăng (mặc định là 1)
     * @return bool
     */
    public function increaseTongDangKy(int $id, int $count = 1): bool
    {
        $builder = $this->builder();
        $builder->set('tong_dang_ky', "tong_dang_ky + $count", false);
        $builder->where($this->primaryKey, $id);
        return $builder->update();
    }
    
    /**
     * Tăng số lượng check-in
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng tăng (mặc định là 1)
     * @return bool
     */
    public function increaseTongCheckIn(int $id, int $count = 1): bool
    {
        $builder = $this->builder();
        $builder->set('tong_check_in', "tong_check_in + $count", false);
        $builder->where($this->primaryKey, $id);
        return $builder->update();
    }
    
    /**
     * Tăng số lượng check-out
     *
     * @param int $id ID của sự kiện
     * @param int $count Số lượng tăng (mặc định là 1)
     * @return bool
     */
    public function increaseTongCheckOut(int $id, int $count = 1): bool
    {
        $builder = $this->builder();
        $builder->set('tong_check_out', "tong_check_out + $count", false);
        $builder->where($this->primaryKey, $id);
        return $builder->update();
    }
    
    /**
     * Tạo slug tự động nếu chưa có
     *
     * @param string $tenSuKien Tên sự kiện
     * @param int|null $suKienId ID sự kiện (nếu đang cập nhật)
     * @return string
     */
    public function createSlug(string $tenSuKien, ?int $suKienId = null): string
    {
        $slug = url_title(convert_accented_characters($tenSuKien), '-', true);
        
        // Kiểm tra xem slug đã tồn tại chưa
        $builder = $this->builder();
        $builder->select('slug');
        $builder->where('slug', $slug);
        
        if ($suKienId !== null) {
            $builder->where($this->primaryKey . ' !=', $suKienId);
        }
        
        $result = $builder->get()->getRow();
        
        // Nếu slug đã tồn tại, thêm số vào cuối
        if ($result) {
            $i = 1;
            $originalSlug = $slug;
            
            while (true) {
                $slug = $originalSlug . '-' . $i;
                
                $builder = $this->builder();
                $builder->select('slug');
                $builder->where('slug', $slug);
                
                if ($suKienId !== null) {
                    $builder->where($this->primaryKey . ' !=', $suKienId);
                }
                
                $result = $builder->get()->getRow();
                
                if (!$result) {
                    break;
                }
                
                $i++;
            }
        }
        
        return $slug;
    }
    
    /**
     * Lấy danh sách sự kiện sắp diễn ra
     *
     * @param int $limit Số lượng sự kiện cần lấy
     * @return array
     */
    public function getUpcomingEvents(int $limit = 5): array
    {
        $now = Time::now()->toDateTimeString();
        
        $builder = $this->builder();
        $builder->where('thoi_gian_bat_dau >', $now);
        $builder->where('status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy('thoi_gian_bat_dau', 'ASC');
        $builder->limit($limit);
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện đang diễn ra
     *
     * @param int $limit Số lượng sự kiện cần lấy
     * @return array
     */
    public function getOngoingEvents(int $limit = 5): array
    {
        $now = Time::now()->toDateTimeString();
        
        $builder = $this->builder();
        $builder->where('thoi_gian_bat_dau <=', $now);
        $builder->where('thoi_gian_ket_thuc >=', $now);
        $builder->where('status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy('thoi_gian_bat_dau', 'ASC');
        $builder->limit($limit);
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện đã kết thúc
     *
     * @param int $limit Số lượng sự kiện cần lấy
     * @param int $offset Vị trí bắt đầu
     * @return array
     */
    public function getPastEvents(int $limit = 10, int $offset = 0): array
    {
        $now = Time::now()->toDateTimeString();
        
        $builder = $this->builder();
        $builder->where('thoi_gian_ket_thuc <', $now);
        $builder->where('status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy('thoi_gian_ket_thuc', 'DESC');
        
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện phổ biến (theo số lượt xem)
     *
     * @param int $limit Số lượng sự kiện cần lấy
     * @return array
     */
    public function getPopularEvents(int $limit = 5): array
    {
        $builder = $this->builder();
        $builder->where('status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy('so_luot_xem', 'DESC');
        $builder->limit($limit);
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện theo loại
     *
     * @param int $loaiSuKienId ID của loại sự kiện
     * @param int $limit Số lượng sự kiện cần lấy
     * @param int $offset Vị trí bắt đầu
     * @return array
     */
    public function getEventsByType(int $loaiSuKienId, int $limit = 10, int $offset = 0): array
    {
        $builder = $this->builder();
        $builder->where('loai_su_kien_id', $loaiSuKienId);
        $builder->where('status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy('thoi_gian_bat_dau', 'DESC');
        
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
    }
    
    /**
     * Lấy danh sách sự kiện theo hình thức
     *
     * @param string $hinhThuc Hình thức sự kiện (offline, online, hybrid)
     * @param int $limit Số lượng sự kiện cần lấy
     * @param int $offset Vị trí bắt đầu
     * @return array
     */
    public function getEventsByFormat(string $hinhThuc, int $limit = 10, int $offset = 0): array
    {
        $builder = $this->builder();
        $builder->where('hinh_thuc', $hinhThuc);
        $builder->where('status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->orderBy('thoi_gian_bat_dau', 'DESC');
        
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResult($this->returnType);
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
        
        if ($scenario === 'update') {
            // Loại bỏ validation cho su_kien_id khi cập nhật
            unset($this->validationRules['su_kien_id']);
        }
    }
    
    /**
     * Đếm số sự kiện theo loại
     *
     * @param int $loaiSuKienId ID của loại sự kiện
     * @return int
     */
    public function countEventsByType(int $loaiSuKienId): int
    {
        return $this->where('loai_su_kien_id', $loaiSuKienId)
                    ->where('status', 1)
                    ->countAllResults();
    }
    
    /**
     * Đếm số sự kiện theo hình thức
     *
     * @param string $hinhThuc Hình thức sự kiện (offline, online, hybrid)
     * @return int
     */
    public function countEventsByFormat(string $hinhThuc): int
    {
        return $this->where('hinh_thuc', $hinhThuc)
                    ->where('status', 1)
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
        $now = Time::now()->toDateTimeString();
        $builder = $this->builder();
        
        switch ($timeStatus) {
            case 'upcoming':
                $builder->where('thoi_gian_bat_dau >', $now);
                break;
            case 'ongoing':
                $builder->where('thoi_gian_bat_dau <=', $now);
                $builder->where('thoi_gian_ket_thuc >=', $now);
                break;
            case 'past':
                $builder->where('thoi_gian_ket_thuc <', $now);
                break;
            default:
                return 0;
        }
        
        $builder->where('status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        return $builder->countAllResults();
    }
} 