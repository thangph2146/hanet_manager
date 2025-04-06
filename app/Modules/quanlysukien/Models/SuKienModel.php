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
        'thoi_gian_checkin_bat_dau',
        'thoi_gian_checkin_ket_thuc',
        'don_vi_to_chuc',
        'don_vi_phoi_hop',
        'doi_tuong_tham_gia',
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
        'hashtag',
        'don_vi_to_chuc',
        'doi_tuong_tham_gia'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'ten_su_kien',
        'loai_su_kien_id',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'status',
        'hinh_thuc',
        'don_vi_to_chuc'
    ];
    
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
     * Áp dụng tiêu chí tìm kiếm lên builder
     * 
     * @param \CodeIgniter\Database\BaseBuilder $builder
     * @param array $criteria
     * @param bool $findDeleted Tìm các bản ghi đã bị xóa
     * @return \CodeIgniter\Database\BaseBuilder
     */
    protected function applySearchCriteria($builder, $criteria, $findDeleted = false)
    {
        // Lọc theo từ khóa
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            // Kiểm tra nếu keyword là số, thì có thể là đang tìm kiếm theo ID
            if (is_numeric($criteria['keyword'])) {
                $builder->groupStart()
                    ->like($this->table . '.ten_su_kien', $criteria['keyword'])
                    ->orLike($this->table . '.mo_ta', $criteria['keyword'])
                    ->orLike($this->table . '.noi_dung', $criteria['keyword'])
                    ->orLike($this->table . '.dia_diem', $criteria['keyword'])
                    ->orWhere($this->table . '.su_kien_id', (int)$criteria['keyword'])
                    ->groupEnd();
            } else {
                $builder->groupStart()
                    ->like($this->table . '.ten_su_kien', $criteria['keyword'])
                    ->orLike($this->table . '.mo_ta', $criteria['keyword'])
                    ->orLike($this->table . '.noi_dung', $criteria['keyword'])
                    ->orLike($this->table . '.dia_diem', $criteria['keyword'])
                    ->groupEnd();
            }
        }
        
        // Lọc theo ID
        if (isset($criteria['id']) && !empty($criteria['id'])) {
            $builder->where($this->table . '.su_kien_id', $criteria['id']);
        }
        
        // Lọc theo loại sự kiện
        if (isset($criteria['loai_su_kien_id']) && !empty($criteria['loai_su_kien_id'])) {
            $builder->where($this->table . '.loai_su_kien_id', $criteria['loai_su_kien_id']);
        }
        
        // Lọc theo người tạo
        if (isset($criteria['nguoi_tao_id']) && !empty($criteria['nguoi_tao_id'])) {
            $builder->where($this->table . '.nguoi_tao_id', $criteria['nguoi_tao_id']);
        }
        
        // Lọc theo trạng thái
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $builder->where($this->table . '.status', $criteria['status']);
        } else {
            $builder->where($this->table . '.status !=', 2); // Mặc định không lấy sự kiện đã xóa
        }
        
        // Loại trừ các ID được chỉ định
        if (isset($criteria['not_in_ids']) && is_array($criteria['not_in_ids']) && !empty($criteria['not_in_ids'])) {
            $builder->whereNotIn($this->table . '.su_kien_id', $criteria['not_in_ids']);
        }
        
        // Lọc sự kiện sắp diễn ra
        if (isset($criteria['upcoming']) && $criteria['upcoming']) {
            $currentDate = date('Y-m-d H:i:s');
            $builder->where($this->table . '.thoi_gian_bat_dau >=', $currentDate);
        }
        
        // Lọc sự kiện đã diễn ra
        if (isset($criteria['past']) && $criteria['past']) {
            $currentDate = date('Y-m-d H:i:s');
            $builder->where($this->table . '.thoi_gian_ket_thuc <', $currentDate);
        }
        
        // Lọc sự kiện đang diễn ra
        if (isset($criteria['ongoing']) && $criteria['ongoing']) {
            $currentDate = date('Y-m-d H:i:s');
            $builder->where($this->table . '.thoi_gian_bat_dau <=', $currentDate)
                   ->where($this->table . '.thoi_gian_ket_thuc >=', $currentDate);
        }
        
        // Lọc theo ngày tổ chức
        if (isset($criteria['start_date']) && !empty($criteria['start_date'])) {
            $startDate = date('Y-m-d H:i:s', strtotime($criteria['start_date']));
            $builder->where($this->table . '.thoi_gian_bat_dau >=', $startDate);
        }
        
        if (isset($criteria['end_date']) && !empty($criteria['end_date'])) {
            $endDate = date('Y-m-d H:i:s', strtotime($criteria['end_date']));
            $builder->where($this->table . '.thoi_gian_bat_dau <=', $endDate);
        }
        
        // Lọc sự kiện nổi bật
        if (isset($criteria['featured']) && $criteria['featured']) {
            $builder->where($this->table . '.featured', 1);
        }
        
        // Lọc theo đơn vị tổ chức
        if (isset($criteria['don_vi_to_chuc']) && !empty($criteria['don_vi_to_chuc'])) {
            $builder->where($this->table . '.don_vi_to_chuc', $criteria['don_vi_to_chuc']);
        }
        
        // Lọc theo thời gian check-in
        if (isset($criteria['thoi_gian_checkin_bat_dau']) && !empty($criteria['thoi_gian_checkin_bat_dau'])) {
            $startDate = date('Y-m-d H:i:s', strtotime($criteria['thoi_gian_checkin_bat_dau']));
            $builder->where($this->table . '.thoi_gian_checkin_bat_dau >=', $startDate);
        }
        
        if (isset($criteria['thoi_gian_checkin_ket_thuc']) && !empty($criteria['thoi_gian_checkin_ket_thuc'])) {
            $endDate = date('Y-m-d H:i:s', strtotime($criteria['thoi_gian_checkin_ket_thuc']));
            $builder->where($this->table . '.thoi_gian_checkin_ket_thuc <=', $endDate);
        }
        
        // Xác định lấy bản ghi đã xóa hay chưa xóa
        if ($findDeleted) {
            $builder->where($this->table . '.deleted_at IS NOT NULL');
        } else {
            $builder->where($this->table . '.deleted_at IS NULL');
        }
        
        return $builder;
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
            'Đơn vị tổ chức' => 'E',
            'Thời gian bắt đầu' => 'F',
            'Thời gian kết thúc' => 'G',
            'Thời gian check-in bắt đầu' => 'H',
            'Thời gian check-in kết thúc' => 'I',
            'Địa điểm' => 'J',
            'Hình thức' => 'K',
            'Số lượng tham gia' => 'L',
            'Tổng đăng ký' => 'M',
            'Tổng check-in' => 'N',
            'Trạng thái' => 'O',
            'Ngày tạo' => 'P',
            'Ngày cập nhật' => 'Q'
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'R';
        }

        return $headers;
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
            'so_luong_tham_gia' => $event->so_luong_tham_gia ?? 0,
            'don_vi_to_chuc' => $event->don_vi_to_chuc ?? '',
            'don_vi_phoi_hop' => $event->don_vi_phoi_hop ?? '',
            'doi_tuong_tham_gia' => $event->doi_tuong_tham_gia ?? '',
            'thoi_gian_checkin_bat_dau' => $event->thoi_gian_checkin_bat_dau ? date('Y-m-d H:i:s', strtotime($event->thoi_gian_checkin_bat_dau)) : null,
            'thoi_gian_checkin_ket_thuc' => $event->thoi_gian_checkin_ket_thuc ? date('Y-m-d H:i:s', strtotime($event->thoi_gian_checkin_ket_thuc)) : null
        ];
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
        
        // Cập nhật surroundCount cho đối tượng pager nếu đã được khởi tạo
        if ($this->pager !== null && method_exists($this->pager, 'setSurroundCount')) {
            $this->pager->setSurroundCount($surroundCount);
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
                $this->pager = new Pager($totalRows, $limit, $currentPage, $this->surroundCount ?? 2);
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
        $query = $this->builder -> where($this->table . '.deleted_at IS NULL') -> get();
        $result = $query->getResult($this->returnType);
        return $result;
    }

    /**
     * Lấy sự kiện theo slug
     */
    public function getEventBySlug($slug)
    {
        // Debug: Ghi log để kiểm tra slug được truyền vào
        log_message('debug', 'Tìm kiếm sự kiện với slug: ' . $slug);
        
        // Khởi tạo biến chứa lý do không tìm thấy sự kiện
        $errorReason = '';
        
        // Chuẩn hóa slug đầu vào - chuyển dấu cách thành dấu gạch ngang
        $normalizedSlug = $this->normalizeSlug($slug);
        $spaceNormalizedSlug = str_replace(' ', '-', $slug); // Chuyển dấu cách thành dấu gạch
        $dashNormalizedSlug = str_replace('-', ' ', $slug); // Chuyển dấu gạch thành dấu cách
        
        log_message('debug', 'Slug sau khi chuẩn hóa 1: ' . $normalizedSlug);
        log_message('debug', 'Slug sau khi chuẩn hóa 2: ' . $spaceNormalizedSlug);
        log_message('debug', 'Slug sau khi chuẩn hóa 3: ' . $dashNormalizedSlug);
        
        // Đầu tiên thử tìm chính xác
        $builder = $this->builder();
        $builder->select('su_kien.*, loai_su_kien.ten_loai_su_kien');
        $builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = su_kien.loai_su_kien_id', 'left');
        
        // Tìm theo nhiều cách khác nhau
        $builder->groupStart()
            ->where('su_kien.slug', $slug)  // Tìm chính xác slug gốc
            ->orWhere('su_kien.slug', $normalizedSlug)  // Tìm theo slug đã chuẩn hóa
            ->orWhere('su_kien.slug', $spaceNormalizedSlug) // Tìm theo slug chuyển dấu cách thành gạch ngang
            ->orWhere('su_kien.slug', $dashNormalizedSlug) // Tìm theo slug chuyển dấu gạch thành dấu cách
            ->orWhere('LOWER(su_kien.slug)', strtolower($slug))  // Tìm không phân biệt hoa thường
            ->orWhere('LOWER(su_kien.slug)', strtolower($normalizedSlug))  // Tìm không phân biệt hoa thường với slug chuẩn hóa
            ->orWhere('LOWER(su_kien.slug)', strtolower($spaceNormalizedSlug)) // Tìm không phân biệt hoa thường với slug chuyển dấu cách
            ->orWhere('LOWER(su_kien.slug)', strtolower($dashNormalizedSlug)) // Tìm không phân biệt hoa thường với slug chuyển dấu gạch
        ->groupEnd();
        
        // Không lọc theo status và deleted_at để debug
        // Sau đó kiểm tra kết quả xem sự kiện có tồn tại nhưng bị ẩn không
        $query = $builder->get();
        $event = $query->getRow();
        
        // Ghi log kết quả truy vấn
        log_message('debug', 'SQL Query: ' . $builder->getCompiledSelect(false));
        log_message('debug', 'Kết quả: ' . ($event ? 'Tìm thấy sự kiện (ID: ' . $event->su_kien_id . ', Status: ' . ($event->status ?? 'null') . ', Deleted: ' . ($event->deleted_at ? 'Yes' : 'No') . ')' : 'Không tìm thấy sự kiện'));
        
        if (!$event) {
            // Tìm kiếm bằng cách so sánh gần đúng
            $builder = $this->builder();
            $builder->select('su_kien.*, loai_su_kien.ten_loai_su_kien');
            $builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = su_kien.loai_su_kien_id', 'left');
            
            // Tìm kiếm slug gần giống
            $searchTerms = explode('-', $normalizedSlug);
            $searchTermsSpace = explode(' ', str_replace('-', ' ', $normalizedSlug));
            
            $builder->groupStart();
            
            // Tìm theo từng phần của slug có dấu gạch
            foreach ($searchTerms as $term) {
                if (strlen($term) > 2) { // Chỉ tìm kiếm với các từ có ít nhất 3 ký tự
                    $builder->orLike('su_kien.slug', $term);
                    $builder->orLike('su_kien.ten_su_kien', $term);
                }
            }
            
            // Tìm theo từng phần của slug có dấu cách
            foreach ($searchTermsSpace as $term) {
                if (strlen($term) > 2) { // Chỉ tìm kiếm với các từ có ít nhất 3 ký tự
                    $builder->orLike('su_kien.slug', $term);
                    $builder->orLike('su_kien.ten_su_kien', $term);
                }
            }
            
            // Thêm tìm kiếm theo tên sự kiện
            $searchText = str_replace('-', ' ', $normalizedSlug);
            $builder->orLike('su_kien.ten_su_kien', $searchText);
            
            $builder->groupEnd();
            $builder->where('su_kien.status', 1);
            $builder->where('su_kien.deleted_at IS NULL');
            $builder->limit(5);
            
            $query = $builder->get();
            $possibleEvents = $query->getResult();
            
            log_message('debug', 'Tìm kiếm gần đúng, SQL: ' . $builder->getCompiledSelect(false));
            log_message('debug', 'Kết quả tìm kiếm gần đúng: ' . count($possibleEvents) . ' sự kiện');
            
            if (!empty($possibleEvents)) {
                // Nếu tìm thấy các sự kiện gần giống, lấy sự kiện đầu tiên
                $event = $possibleEvents[0];
                log_message('debug', 'Chọn sự kiện gần giống nhất: ' . $event->ten_su_kien . ' (slug: ' . $event->slug . ')');
                
                // Lưu slug chính xác vào session để redirect
                session()->set('correct_event_slug', $event->slug);
                
                // Thêm danh sách sự kiện tương tự vào session
                $similarEvents = [];
                foreach ($possibleEvents as $pe) {
                    $similarEvents[] = [
                        'su_kien_id' => $pe->su_kien_id,
                        'ten_su_kien' => $pe->ten_su_kien,
                        'slug' => $pe->slug
                    ];
                }
                session()->set('similar_events_list', $similarEvents);
            } else {
                // Kiểm tra xem có bất kỳ sự kiện nào gần giống với slug này không
                $builder = $this->builder();
                $builder->select('su_kien.slug, su_kien.ten_su_kien, su_kien.su_kien_id');
                $builder->like('su_kien.slug', str_replace('-', '%', $normalizedSlug));
                $builder->orLike('su_kien.slug', str_replace(' ', '%', $slug));
                $builder->orLike('su_kien.ten_su_kien', str_replace('-', ' ', $slug));
                $builder->where('su_kien.status', 1);
                $builder->where('su_kien.deleted_at IS NULL');
                $builder->limit(5);
                $possibleEvents = $builder->get()->getResult();
                
                if (!empty($possibleEvents)) {
                    $slugs = [];
                    foreach ($possibleEvents as $pe) {
                        $slugs[] = $pe->slug;
                    }
                    log_message('debug', 'Không tìm thấy sự kiện với slug "' . $slug . '", nhưng có các slug tương tự: ' . implode(', ', $slugs));
                    $errorReason = 'not_found_similar_exists';
                    
                    // Lưu danh sách sự kiện tương tự để hiển thị
                    $similarEvents = [];
                    foreach ($possibleEvents as $pe) {
                        $similarEvents[] = [
                            'su_kien_id' => $pe->su_kien_id,
                            'ten_su_kien' => $pe->ten_su_kien,
                            'slug' => $pe->slug
                        ];
                    }
                    session()->set('similar_events_list', $similarEvents);
                } else {
                    $errorReason = 'not_found';
                }
                
                // Kiểm tra xem có phải slug đã bị xóa
                $builder = $this->builder();
                $builder->select('su_kien.*');
                $builder->where('su_kien.slug', $slug);
                $builder->orWhere('su_kien.slug', $normalizedSlug);
                $builder->orWhere('su_kien.slug', $spaceNormalizedSlug);
                $builder->orWhere('su_kien.slug', $dashNormalizedSlug);
                $builder->whereNotNull('su_kien.deleted_at');
                $deletedEvent = $builder->get()->getRow();
                
                if ($deletedEvent) {
                    $errorReason = 'deleted';
                }
                
                // Lưu lý do vào session
                session()->set('event_error_reason', $errorReason);
                return null;
            }
        }
        
        // Kiểm tra status và deleted_at sau khi đã tìm thấy
        if (isset($event->status) && $event->status != 1 || $event->deleted_at != null) {
            log_message('debug', 'Sự kiện tìm thấy nhưng không khả dụng: Status=' . ($event->status ?? 'null') . ', Deleted=' . ($event->deleted_at ? 'Yes' : 'No'));
            
            if ($event->deleted_at != null) {
                $errorReason = 'deleted';
            } else if (isset($event->status) && $event->status != 1) {
                $errorReason = 'inactive';
            }
            
            // Lưu lý do vào session
            session()->set('event_error_reason', $errorReason);
            return null;
        }
        
        // Nếu slug không chính xác nhưng tìm được sự kiện gần đúng, lưu slug chính xác để redirect
        if ($event->slug !== $slug) {
            session()->set('correct_event_slug', $event->slug);
        }
        
        // Chuyển đổi sang định dạng tương thích với view
        return [
            'su_kien_id' => $event->su_kien_id,
            'ten_su_kien' => $event->ten_su_kien,
            'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
            'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
            'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
            'dia_diem' => $event->dia_diem,
            'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
            'hinh_anh' => $event->su_kien_poster,
            'gio_bat_dau' => date('H:i', strtotime($event->thoi_gian_bat_dau)),
            'gio_ket_thuc' => date('H:i', strtotime($event->thoi_gian_ket_thuc)),
            'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc,
            'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc)),
            'loai_su_kien_id' => $event->loai_su_kien_id,
            'loai_su_kien' => $event->ten_loai_su_kien ?? '',
            'loai_su_kien_slug' => strtolower(str_replace(' ', '-', $event->ten_loai_su_kien ?? '')),
            'slug' => $event->slug,
            'so_luot_xem' => $event->so_luot_xem,
            'lich_trinh' => $event->lich_trinh,
            'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
            'link_online' => $event->link_online ?? '',
            'mat_khau_online' => $event->mat_khau_online ?? '',
            'so_luong_tham_gia' => $event->so_luong_tham_gia ?? 0,
            'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            'tong_check_in' => $event->tong_check_in ?? 0,
            'tong_check_out' => $event->tong_check_out ?? 0,
            'tu_khoa_su_kien' => $event->tu_khoa_su_kien ?? '',
            'hashtag' => $event->hashtag ?? '',
            'bat_dau_dang_ky' => $event->bat_dau_dang_ky ?? null,
            'ket_thuc_dang_ky' => $event->ket_thuc_dang_ky ?? null,
            'han_huy_dang_ky' => $event->han_huy_dang_ky ?? null,
        ];
    }
    
    /**
     * Tăng số lượt xem cho sự kiện
     *
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function updateViewCount($eventId)
    {
        // Kiểm tra sự kiện tồn tại
        $event = $this->find($eventId);
        if (!$event) {
            return false;
        }
        
        // Tăng số lượt xem lên 1
        $currentViews = (int)($event->so_luot_xem ?? 0);
        $data = [
            'so_luot_xem' => $currentViews + 1
        ];
        
        // Cập nhật và trả về kết quả
        return $this->update($eventId, $data);
    }

    /**
     * Cập nhật số lượng check-in cho sự kiện
     *
     * @param int $eventId ID của sự kiện
     * @param int $increment Số lượng tăng thêm
     * @return bool
     */
    public function updateCheckInCount(int $eventId, int $increment = 1): bool
    {
        $suKien = $this->find($eventId);
        if (!$suKien) {
            return false;
        }
        
        $currentCount = (int)$suKien->tong_check_in;
        $newCount = $currentCount + $increment;
        
        return $this->update($eventId, [
            'tong_check_in' => $newCount
        ]);
    }

    /**
     * Kiểm tra thời gian check-in hợp lệ
     *
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function isValidCheckinTime(int $eventId): bool
    {
        $suKien = $this->find($eventId);
        if (!$suKien) {
            return false;
        }
        
        $now = Time::now();
        
        // Nếu có thời gian check-in cụ thể
        if (!empty($suKien->thoi_gian_checkin_bat_dau) && !empty($suKien->thoi_gian_checkin_ket_thuc)) {
            $startTime = new Time($suKien->thoi_gian_checkin_bat_dau);
            $endTime = new Time($suKien->thoi_gian_checkin_ket_thuc);
            
            return $now >= $startTime && $now <= $endTime;
        }
        
        // Nếu không có thời gian check-in cụ thể, sử dụng thời gian sự kiện
        $startTime = new Time($suKien->thoi_gian_bat_dau);
        $endTime = new Time($suKien->thoi_gian_ket_thuc);
        
        return $now >= $startTime && $now <= $endTime;
    }

    /**
     * Lấy thông tin cấu hình hiển thị check-in cho sự kiện
     *
     * @param int $eventId ID của sự kiện
     * @return array
     */
    public function getCheckinDisplayConfig(int $eventId): array
    {
        $suKien = $this->find($eventId);
        if (!$suKien) {
            return [
                'text1' => 'Chào mừng đến với sự kiện',
                'text2' => 'Welcome to the event',
                'bgType' => '1'
            ];
        }
        
        // Ở đây bạn có thể mở rộng để lưu cấu hình hiển thị check-in trong CSDL
        return [
            'text1' => 'Chào mừng đến với sự kiện',
            'text2' => $suKien->ten_su_kien,
            'bgType' => '1',
            'place' => $suKien->dia_diem
        ];
    }

    /**
     * Lấy danh sách người đăng ký cho sự kiện
     *
     * @param int $eventId ID của sự kiện
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array Danh sách người đăng ký
     */
    public function getRegistrations($eventId, array $options = [])
    {
        $limit = $options['limit'] ?? 0;
        $offset = $options['offset'] ?? 0;
        $sort = $options['sort'] ?? 'created_at';
        $order = $options['order'] ?? 'DESC';
        
        $db = \Config\Database::connect();
        $builder = $db->table('dangky_sukien');
        
        $builder->select('dangky_sukien.*')
                ->where('dangky_sukien.su_kien_id', $eventId)
                ->where('dangky_sukien.deleted_at IS NULL');
        
        // Sắp xếp
        $builder->orderBy($sort, $order);
        
        // Phân trang nếu có yêu cầu
        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }
        
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * Lấy danh sách sự kiện liên quan (cùng loại)
     *
     * @param int $eventId ID của sự kiện hiện tại (sẽ loại trừ khỏi kết quả)
     * @param string $eventType Loại sự kiện
     * @param int $limit Số lượng sự kiện tối đa trả về
     * @return array Danh sách sự kiện liên quan
     */
    public function getRelatedEvents($eventId, $eventType, $limit = 3)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        // Lấy loại sự kiện ID dựa trên tên loại sự kiện
        $loaiSuKienId = null;
        if (is_numeric($eventType)) {
            $loaiSuKienId = $eventType;
        } else {
            $loaiSukienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
            $loaiSukien = $loaiSukienModel->where('ten_loai_su_kien', $eventType)->first();
            if ($loaiSukien) {
                $loaiSuKienId = $loaiSukien->loai_su_kien_id;
            }
        }
        
        // Lọc theo loại sự kiện
        if ($loaiSuKienId) {
            $builder->where($this->table . '.loai_su_kien_id', $loaiSuKienId);
        }
        
        // Loại trừ sự kiện hiện tại
        $builder->where($this->table . '.su_kien_id !=', $eventId);
        
        // Chỉ lấy sự kiện đang hoạt động và chưa bị xóa
        $builder->where($this->table . '.status', 1);
        $builder->where($this->table . '.deleted_at IS NULL');
        
        // Sắp xếp theo thời gian diễn ra (gần nhất trước)
        $builder->orderBy($this->table . '.thoi_gian_bat_dau', 'DESC');
        
        // Giới hạn số lượng kết quả
        $builder->limit($limit);
        
        $result = $builder->get()->getResult();
        
        // Chuyển đổi định dạng kết quả nếu cần
        $relatedEvents = [];
        foreach ($result as $event) {
            // Đảm bảo event là đối tượng
            if (is_object($event)) {
                // Chuyển đổi định dạng nếu cần
                $relatedEvents[] = $event;
            }
        }
        
        return $relatedEvents;
    }
    
    /**
     * Lấy lịch trình của sự kiện từ ID
     *
     * @param int $eventId ID của sự kiện
     * @return array Lịch trình của sự kiện
     */
    public function getEventSchedule($eventId)
    {
        $event = $this->find($eventId);
        
        if (!$event) {
            return [];
        }
        
        // Xử lý lịch trình
        $lichTrinh = [];
        if (!empty($event->lich_trinh)) {
            if (is_array($event->lich_trinh)) {
                $lichTrinh = $event->lich_trinh;
            } elseif (is_string($event->lich_trinh)) {
                $lichTrinh = json_decode($event->lich_trinh, true);
            }
        }
        
        // Nếu không có lịch trình cụ thể, tạo lịch trình mặc định từ thời gian bắt đầu và kết thúc
        if (empty($lichTrinh) && !empty($event->thoi_gian_bat_dau) && !empty($event->thoi_gian_ket_thuc)) {
            $lichTrinh = [
                [
                    'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc)),
                    'ngay' => date('d/m/Y', strtotime($event->thoi_gian_bat_dau)),
                    'noi_dung' => $event->ten_su_kien,
                    'dia_diem' => $event->dia_diem ?? ''
                ]
            ];
        }
        
        // Sắp xếp lịch trình theo thời gian nếu cần
        if (!empty($lichTrinh) && isset($lichTrinh[0]['ngay']) && isset($lichTrinh[0]['thoi_gian'])) {
            usort($lichTrinh, function($a, $b) {
                $dateA = \DateTime::createFromFormat('d/m/Y H:i', $a['ngay'] . ' ' . explode(' - ', $a['thoi_gian'])[0]);
                $dateB = \DateTime::createFromFormat('d/m/Y H:i', $b['ngay'] . ' ' . explode(' - ', $b['thoi_gian'])[0]);
                
                if ($dateA && $dateB) {
                    return $dateA <=> $dateB;
                }
                
                return 0;
            });
        }
        
        return $lichTrinh;
    }

    /**
     * Lấy tất cả sự kiện đang hoạt động
     *
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array Danh sách tất cả sự kiện
     */
    public function getAllEvents(array $options = [])
    {
        // Sử dụng lại phương thức getAll nhưng với các tùy chọn phù hợp
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        $sort = $options['sort'] ?? 'thoi_gian_bat_dau';
        $order = $options['order'] ?? 'DESC';
        
        return $this->getAll($limit, $offset, $sort, $order);
    }

    /**
     * Đếm tổng số kết quả tìm kiếm dựa trên tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @return int Tổng số kết quả
     */
    public function countSearchResults(array $criteria = [])
    {
        $builder = $this->builder();
        
        // Áp dụng các tiêu chí tìm kiếm
        $this->applySearchCriteria($builder, $criteria);
        
        // Đếm tổng số kết quả
        return $builder->countAllResults();
    }

    /**
     * Tìm kiếm sự kiện đã bị xóa dựa vào các tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        $limit = $options['limit'] ?? 10;
        $offset = $options['offset'] ?? 0;
        $sort = $options['sort'] ?? 'deleted_at';
        $order = $options['order'] ?? 'DESC';
        
        $this->builder = $this->builder();
        $this->builder->select($this->table . '.*');
        
        // Thêm join với bảng loại sự kiện nếu cần
        if (isset($options['join_loai_su_kien']) && $options['join_loai_su_kien']) {
            $this->builder->select('loai_su_kien.ten_loai_su_kien, loai_su_kien.ma_loai_su_kien');
            $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
        }
        
        // Thêm điều kiện tìm kiếm
        $this->applySearchCriteria($this->builder, $criteria, true);
        
        // Sắp xếp
        if (strpos($sort, '.') === false) {
            $sort = $this->table . '.' . $sort;
        }
        $this->builder->orderBy($sort, $order);
        
        // Thiết lập pager nếu có limit
        if ($limit > 0) {
            $totalRows = $this->countDeletedSearchResults($criteria);
            $currentPage = floor($offset / $limit) + 1;
            
            if ($this->pager === null) {
                $this->pager = new Pager($totalRows, $limit, $currentPage, $this->surroundCount ?? 2);
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
     * Đếm tổng số kết quả tìm kiếm đã bị xóa dựa trên tiêu chí
     *
     * @param array $criteria Các tiêu chí tìm kiếm
     * @return int Tổng số kết quả
     */
    public function countDeletedSearchResults(array $criteria = [])
    {
        $builder = $this->builder();
        
        // Áp dụng các tiêu chí tìm kiếm
        $this->applySearchCriteria($builder, $criteria, true);
        
        // Đếm tổng số kết quả
        return $builder->countAllResults();
    }

    /**
     * Chuẩn hóa slug
     *
     * @param string $slug Slug cần chuẩn hóa
     * @return string Slug đã chuẩn hóa
     */
    protected function normalizeSlug($slug)
    {
        // Chuyển dấu cách thành dấu gạch ngang (hoặc ngược lại nếu cần)
        $slug = str_replace(' ', '-', $slug);
        
        // Loại bỏ ký tự đặc biệt
        $slug = preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
        
        // Chuyển đổi sang chữ thường
        $slug = strtolower($slug);
        
        // Loại bỏ các dấu gạch ngang ở đầu và cuối
        $slug = trim($slug, '-');
        
        // Loại bỏ các dấu gạch ngang liên tiếp
        $slug = preg_replace('/-+/', '-', $slug);
        
        return $slug;
    }

    /**
     * Ghi đè phương thức update để loại bỏ các trường không tồn tại trong DB
     *
     * @param array|string $id
     * @param array|object $data
     * @return bool
     */
    public function update($id = null, $data = null): bool
    {
        // Chuyển đổi đối tượng thành mảng nếu cần
        if (is_object($data)) {
            $data = (array) $data;
        }

        // Danh sách các trường có trong bảng su_kien
        $validFields = [
            'su_kien_id', 'ten_su_kien', 'mo_ta', 'chi_tiet_su_kien', 'dia_diem', 
            'dia_chi_cu_the', 'su_kien_poster', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc',
            'gio_bat_dau', 'gio_ket_thuc', 'loai_su_kien_id', 'nguoi_tao_id', 'status',
            'featured', 'so_luot_xem', 'so_luong_tham_gia', 'tong_dang_ky', 
            'tong_check_in', 'tong_check_out', 'hinh_thuc', 'slug', 'lich_trinh',
            'tu_khoa_su_kien', 'hashtag', 'don_vi_to_chuc', 'don_vi_phoi_hop',
            'doi_tuong_tham_gia', 'created_at', 'updated_at', 'deleted_at',
            'bat_dau_dang_ky', 'ket_thuc_dang_ky', 'han_huy_dang_ky', 'link_online',
            'mat_khau_online', 'thoi_gian_checkin_bat_dau', 'thoi_gian_checkin_ket_thuc',
            'thoi_gian_bat_dau_dang_ky', 'thoi_gian_ket_thuc_dang_ky'
        ];

        // Loại bỏ các trường không tồn tại trong bảng
        $cleanData = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $validFields)) {
                $cleanData[$key] = $value;
            } else {
                // Log lại các trường bị loại bỏ để theo dõi
                log_message('notice', 'Trường không tồn tại trong bảng su_kien: ' . $key);
            }
        }

        // Gọi phương thức update của lớp cha với dữ liệu đã được làm sạch
        return parent::update($id, $cleanData);
    }
    public function getUpcomingEvents($limit = 5)
    {
        $builder = $this->builder();
        $builder->where('status', 1);
        $builder->where('deleted_at IS NULL');
        $builder->orderBy('thoi_gian_bat_dau', 'ASC');
        $builder->limit($limit);
        return $builder->get()->getResult();
    }

    /**
     * Lấy danh sách các loại sự kiện (categories)
     *
     * @return array
     */
    public function getCategories()
    {
        $loaiSuKienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
        return $loaiSuKienModel->getForDropdown(true);
    }

    /**
     * Lấy danh sách sự kiện với các tùy chọn lọc
     * 
     * @param array $options Các tùy chọn lọc sự kiện
     * @return array Mảng các đối tượng sự kiện
     */
    public function getEvents(array $options = [])
    {
        // Xây dựng truy vấn
        $builder = $this->builder();
        
        // Áp dụng điều kiện trạng thái nếu có
        if (isset($options['status'])) {
            if ($options['status'] === 'published') {
                $builder->where('status', 1);
            } elseif ($options['status'] === 'draft') {
                $builder->where('status', 0);
            }
        }
        
        // Áp dụng các điều kiện WHERE nếu có
        if (isset($options['where']) && is_array($options['where'])) {
            foreach ($options['where'] as $field => $value) {
                $builder->where($field, $value);
            }
        }
        
        // Áp dụng LIKE điều kiện nếu có
        if (isset($options['like']) && is_array($options['like'])) {
            foreach ($options['like'] as $field => $value) {
                $builder->like($field, $value);
            }
        }
        
        // Áp dụng sắp xếp nếu có
        if (isset($options['order']) && is_array($options['order'])) {
            foreach ($options['order'] as $field => $direction) {
                $builder->orderBy($field, $direction);
            }
        } else {
            // Mặc định sắp xếp theo thời gian tạo giảm dần
            $builder->orderBy('created_at', 'DESC');
        }
        
        // Áp dụng giới hạn và offset nếu có
        if (isset($options['limit'])) {
            $builder->limit($options['limit']);
        }
        
        // Áp dụng offset nếu có
        if (isset($options['offset'])) {
            $builder->offset($options['offset']);
        }
        
        // Thực hiện truy vấn
        $query = $builder->get();
        return $query->getResult();
    }
}   