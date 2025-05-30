<?php

namespace App\Modules\quanlynguoidung\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class NguoiDung extends BaseEntity
{
    protected $tableName = 'nguoi_dung';
    protected $primaryKey = 'nguoi_dung_id';
    
    protected $dates = [
        'last_login',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'nguoi_dung_id' => 'int',
        'u_id' => 'int',
        'loai_nguoi_dung_id' => 'int',
        'nam_hoc_id' => 'int',
        'bac_hoc_id' => 'int',
        'he_dao_tao_id' => 'int',
        'nganh_id' => 'int',
        'phong_khoa_id' => 'int',
        'status' => 'int',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_AccountId' => ['AccountId'],
        'idx_FullName' => ['FullName'],
        'idx_Email' => ['Email'],
        'idx_phong_khoa_id' => ['phong_khoa_id'],
        'idx_nganh_id' => ['nganh_id']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_AccountId' => ['AccountId'],
        'uk_Email' => ['Email']
    ];
    
    // Các quy tắc xác thực cụ thể cho NguoiDung
    protected $validationRules = [
        'AccountId' => [
            'rules' => 'permit_empty|max_length[50]|is_unique[nguoi_dung.AccountId,nguoi_dung_id,{nguoi_dung_id}]',
            'label' => 'Tài khoản'
        ],
        'Email' => [
            'rules' => 'required|valid_email|max_length[100]|is_unique[nguoi_dung.Email,nguoi_dung_id,{nguoi_dung_id}]',
            'label' => 'Email'
        ],
        'FullName' => [
            'rules' => 'required|max_length[100]',
            'label' => 'Họ và tên đầy đủ'
        ],
        'LastName' => [
            'rules' => 'required|max_length[100]',
            'label' => 'Họ'
        ],
        'MiddleName' => [
            'rules' => 'required|max_length[100]',
            'label' => 'Tên đệm'
        ],
        'FirstName' => [
            'rules' => 'required|max_length[100]',
            'label' => 'Tên'
        ],
        'MobilePhone' => [
            'rules' => 'required|max_length[20]',
            'label' => 'Số điện thoại di động'
        ],
        'AccountType' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Loại tài khoản'
        ],
        'HomePhone1' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Số điện thoại nhà 1'
        ],
        'HomePhone' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Số điện thoại nhà'
        ],
        'avatar' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Ảnh đại diện'
        ],
        'avatar_file' => [
            'rules' => 'permit_empty|uploaded[avatar_file,0]|is_image[avatar_file]|max_size[avatar_file,2048]',
            'label' => 'File ảnh đại diện'
        ],
        'PW' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Mật khẩu'
        ],
        'mat_khau_local' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Mật khẩu local'
        ],
        'u_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'ID người dùng'
        ],
        'loai_nguoi_dung_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Loại người dùng'
        ],
        'nam_hoc_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Năm học'
        ],
        'bac_hoc_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Bậc học'
        ],
        'he_dao_tao_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Hệ đào tạo'
        ],
        'nganh_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Ngành'
        ],
        'phong_khoa_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Phòng/Khoa'
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'AccountId' => [
            'max_length' => '{field} không được vượt quá 50 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'Email' => [
            'required' => '{field} là bắt buộc',
            'valid_email' => '{field} không hợp lệ',
            'max_length' => '{field} không được vượt quá 100 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'FullName' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ],
        'LastName' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ],
        'MiddleName' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ],
        'FirstName' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ],
        'MobilePhone' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'AccountType' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'HomePhone1' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'HomePhone' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'avatar' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'avatar_file' => [
            'uploaded' => '{field} không upload được',
            'is_image' => '{field} phải là định dạng hình ảnh (JPG, PNG, GIF)',
            'max_size' => '{field} không được vượt quá 2MB'
        ],
        'PW' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'mat_khau_local' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'u_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'loai_nguoi_dung_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'nam_hoc_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'bac_hoc_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'he_dao_tao_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'nganh_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'phong_khoa_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'status' => [
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của người dùng
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên đăng nhập
     *
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->attributes['AccountId'] ?? '';
    }
    
    /**
     * Lấy họ tên đầy đủ của người dùng
     * Phương thức này tương thích với getFullName() trong AuthenticationNguoiDung
     *
     * @return string Họ tên đầy đủ
     */
    public function getFullName(): string
    {
        // Ưu tiên sử dụng các trường LastName, MiddleName và FirstName nếu có
        if (!empty($this->getLastName())) {
            $nameParts[] = $this->getLastName();
        }
        
        if (!empty($this->getMiddleName())) {
            $nameParts[] = $this->getMiddleName();
        }
        
        if (!empty($this->getFirstName())) {
            $nameParts[] = $this->getFirstName();
        }
        
        if (!empty($nameParts)) {
            return implode(' ', $nameParts);
        }
        
        // Nếu không có các trường riêng lẻ, sử dụng FullName
        return $this->attributes['FullName'] ?? '';
    }
    
    /**
     * Lấy email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->attributes['Email'] ?? '';
    }
    
    /**
     * Lấy số điện thoại di động
     *
     * @return string
     */
    public function getMobilePhone(): string
    {
        return $this->attributes['MobilePhone'] ?? '';
    }
    
    /**
     * Kiểm tra trạng thái hoạt động
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? true);
    }
    
    /**
     * Đặt trạng thái hoạt động
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status)
    {
        $this->attributes['status'] = (int)$status;
        return $this;
    }
    
    /**
     * Kiểm tra xem bản ghi đã bị xóa chưa
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return !empty($this->attributes['deleted_at']);
    }
    
    /**
     * Lấy nhãn trạng thái hiển thị
     *
     * @return string HTML với badge status
     */
    public function getStatusLabel(): string
    {
        if ($this->status == 1) {
            return '<span class="badge bg-success">Hoạt động</span>';
        } else {
            return '<span class="badge bg-danger">Không hoạt động</span>';
        }
    }
    
    /**
     * Lấy ngày đăng nhập cuối cùng đã định dạng
     * 
     * @return string
     */
    public function getLastLoginFormatted(): string
    {
        if (empty($this->attributes['last_login'])) {
            return '';
        }
        
        try {
            $time = $this->attributes['last_login'] instanceof Time 
                ? $this->attributes['last_login'] 
                : Time::parse($this->attributes['last_login']);
                
            return $time->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            log_message('error', 'Lỗi định dạng last_login: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Lấy ngày tạo đã định dạng
     * 
     * @return string
     */
    public function getCreatedAtFormatted(): string
    {
        if (empty($this->attributes['created_at'])) {
            return '';
        }
        
        try {
            $time = $this->attributes['created_at'] instanceof Time 
                ? $this->attributes['created_at'] 
                : Time::parse($this->attributes['created_at']);
                
            return $time->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            log_message('error', 'Lỗi định dạng created_at: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Lấy ngày cập nhật đã định dạng
     * 
     * @return string
     */
    public function getUpdatedAtFormatted(): string
    {
        if (empty($this->attributes['updated_at'])) {
            return '';
        }
        
        try {
            $time = $this->attributes['updated_at'] instanceof Time 
                ? $this->attributes['updated_at'] 
                : Time::parse($this->attributes['updated_at']);
                
            return $time->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            log_message('error', 'Lỗi định dạng updated_at: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     * 
     * @return string
     */
    public function getDeletedAtFormatted(): string
    {
        if (empty($this->attributes['deleted_at'])) {
            return '';
        }
        
        try {
            $time = $this->attributes['deleted_at'] instanceof Time 
                ? $this->attributes['deleted_at'] 
                : Time::parse($this->attributes['deleted_at']);
                
            return $time->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            log_message('error', 'Lỗi định dạng deleted_at: ' . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Lấy các quy tắc xác thực
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }
    
    /**
     * Lấy các thông báo xác thực
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }
    
    /**
     * Lấy ID loại người dùng
     *
     * @return int|null
     */
    public function getLoaiNguoiDungId(): ?int
    {
        return isset($this->attributes['loai_nguoi_dung_id']) ? (int)$this->attributes['loai_nguoi_dung_id'] : null;
    }
    
    /**
     * Lấy ID phòng khoa
     *
     * @return int|null
     */
    public function getPhongKhoaId(): ?int
    {
        return isset($this->attributes['phong_khoa_id']) ? (int)$this->attributes['phong_khoa_id'] : null;
    }
    
    /**
     * Lấy ID năm học
     *
     * @return int|null
     */
    public function getNamHocId(): ?int
    {
        return isset($this->attributes['nam_hoc_id']) ? (int)$this->attributes['nam_hoc_id'] : null;
    }
    
    /**
     * Lấy ID bậc học
     *
     * @return int|null
     */
    public function getBacHocId(): ?int
    {
        return isset($this->attributes['bac_hoc_id']) ? (int)$this->attributes['bac_hoc_id'] : null;
    }
    
    /**
     * Lấy ID hệ đào tạo
     *
     * @return int|null
     */
    public function getHeDaoTaoId(): ?int
    {
        return isset($this->attributes['he_dao_tao_id']) ? (int)$this->attributes['he_dao_tao_id'] : null;
    }
    
    /**
     * Lấy ID ngành
     *
     * @return int|null
     */
    public function getNganhId(): ?int
    {
        return isset($this->attributes['nganh_id']) ? (int)$this->attributes['nganh_id'] : null;
    }

    /**
     * Lấy thông tin loại người dùng
     *
     * @return \App\Modules\quanlyloainguoidung\Entities\LoaiNguoiDung|null
     */
    public function getLoaiNguoiDung(): ?\App\Modules\quanlyloainguoidung\Entities\LoaiNguoiDung
    {
        if (!isset($this->attributes['loai_nguoi_dung_id'])) {
            return null;
        }

        return $this->attributes['loai_nguoi_dung'] ?? null;
    }

    /**
     * Lấy thông tin phòng khoa
     *
     * @return \App\Modules\phongkhoa\Entities\PhongKhoa|null
     */
    public function getPhongKhoa(): ?\App\Modules\phongkhoa\Entities\PhongKhoa
    {
        if (!isset($this->attributes['phong_khoa_id'])) {
            return null;
        }

        return $this->attributes['phong_khoa'] ?? null;
    }

    /**
     * Lấy thông tin năm học
     *
     * @return \App\Modules\namhoc\Entities\NamHoc|null
     */
    public function getNamHoc(): ?\App\Modules\namhoc\Entities\NamHoc
    {
        if (!isset($this->attributes['nam_hoc_id'])) {
            return null;
        }

        return $this->attributes['nam_hoc'] ?? null;
    }

    /**
     * Lấy thông tin bậc học
     *
     * @return \App\Modules\bachoc\Entities\BacHoc|null
     */
    public function getBacHoc(): ?\App\Modules\bachoc\Entities\BacHoc
    {
        if (!isset($this->attributes['bac_hoc_id'])) {
            return null;
        }

        return $this->attributes['bac_hoc'] ?? null;
    }

    /**
     * Lấy thông tin hệ đào tạo
     *
     * @return \App\Modules\hedaotao\Entities\HeDaoTao|null
     */
    public function getHeDaoTao(): ?\App\Modules\hedaotao\Entities\HeDaoTao
    {
        if (!isset($this->attributes['he_dao_tao_id'])) {
            return null;
        }

        return $this->attributes['he_dao_tao'] ?? null;
    }

    /**
     * Lấy thông tin ngành
     *
     * @return \App\Modules\nganh\Entities\Nganh|null
     */
    public function getNganh(): ?\App\Modules\nganh\Entities\Nganh
    {
        if (!isset($this->attributes['nganh_id'])) {
            return null;
        }

        return $this->attributes['nganh'] ?? null;
    }

    /**
     * Lấy tên hiển thị của loại người dùng
     *
     * @return string
     */
    public function getLoaiNguoiDungDisplay(): string
    {
        $loaiNguoiDung = $this->getLoaiNguoiDung();
        if (!$loaiNguoiDung) {
            return '';
        }
        return $loaiNguoiDung->ten_loai;
    }

    /**
     * Lấy tên hiển thị của phòng khoa
     *
     * @return string
     */
    public function getPhongKhoaDisplay(): string
    {
        $phongKhoa = $this->getPhongKhoa();
        if (!$phongKhoa) {
            return '';
        }
        return $phongKhoa->ten_phong_khoa . 
               (!empty($phongKhoa->ma_phong_khoa) ? ' (' . $phongKhoa->ma_phong_khoa . ')' : '');
    }

    /**
     * Lấy tên hiển thị của năm học
     *
     * @return string
     */
    public function getNamHocDisplay(): string
    {
        $namHoc = $this->getNamHoc();
        if (!$namHoc) {
            return '';
        }
        return $namHoc->ten_nam_hoc;
    }

    /**
     * Lấy tên hiển thị của bậc học
     *
     * @return string
     */
    public function getBacHocDisplay(): string
    {
        $bacHoc = $this->getBacHoc();
        if (!$bacHoc) {
            return '';
        }
        return $bacHoc->ten_bac_hoc . 
               (!empty($bacHoc->ma_bac_hoc) ? ' (' . $bacHoc->ma_bac_hoc . ')' : '');
    }

    /**
     * Lấy tên hiển thị của hệ đào tạo
     *
     * @return string
     */
    public function getHeDaoTaoDisplay(): string
    {
        $heDaoTao = $this->getHeDaoTao();
        if (!$heDaoTao) {
            return '';
        }
        return $heDaoTao->ten_he_dao_tao . 
               (!empty($heDaoTao->ma_he_dao_tao) ? ' (' . $heDaoTao->ma_he_dao_tao . ')' : '');
    }

    /**
     * Lấy tên hiển thị của ngành
     *
     * @return string
     */
    public function getNganhDisplay(): string
    {
        $nganh = $this->getNganh();
        if (!$nganh) {
            return '';
        }
        return $nganh->ten_nganh . 
               (!empty($nganh->ma_nganh) ? ' (' . $nganh->ma_nganh . ')' : '');
    }

    /**
     * Lấy đường dẫn avatar
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->attributes['avatar'] ?? null;
    }

    /**
     * Lấy URL đầy đủ của avatar
     *
     * @return string
     */
    public function getAvatarUrl(): string
    {
        // If avatar is null or empty, return the default avatar path
        if (empty($this->attributes['avatar'])) {
            return base_url('assets/images/avatars/user.png');
        }

        // Kiểm tra xem avatar có phải là URL đầy đủ không
        if (strpos($this->attributes['avatar'], 'http://') === 0 || strpos($this->attributes['avatar'], 'https://') === 0) {
            return $this->attributes['avatar'];
        }

        // Nếu không, thêm base_url
        return base_url($this->attributes['avatar']);
    }

    /**
     * Lấy giá trị của một trường từ entity
     * 
     * @param string $field Tên trường cần lấy
     * @param mixed $default Giá trị mặc định nếu trường không tồn tại
     * @return mixed Giá trị của trường hoặc giá trị mặc định
     */
    public function getField(string $field, $default = null)
    {
        // Kiểm tra xem trường có tồn tại trong attributes không
        if (isset($this->attributes[$field]) && ($this->attributes[$field] !== '' || $this->attributes[$field] === 0 || $this->attributes[$field] === '0')) {
            return $this->attributes[$field];
        }
        
        // Nếu không tồn tại hoặc giá trị rỗng, trả về giá trị mặc định
        return $default;
    }

    /**
     * Lấy họ
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->attributes['LastName'] ?? '';
    }
    
    /**
     * Lấy tên đệm
     *
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->attributes['MiddleName'] ?? '';
    }
    
    /**
     * Lấy tên
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->attributes['FirstName'] ?? '';
    }
    
    /**
     * Lấy tên hiển thị từ các thành phần LastName, MiddleName và FirstName
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        $nameParts = [];
        
        if (!empty($this->getLastName())) {
            $nameParts[] = $this->getLastName();
        }
        
        if (!empty($this->getMiddleName())) {
            $nameParts[] = $this->getMiddleName();
        }
        
        if (!empty($this->getFirstName())) {
            $nameParts[] = $this->getFirstName();
        }
        
        if (empty($nameParts)) {
            return $this->attributes['FullName'] ?? '';
        }
        
        return implode(' ', $nameParts);
    }
    public function verifyPassword($password)
	{
        // Kiểm tra với PW (mật khẩu lưu từ Azure) trước
        if (!empty($this->PW) && password_verify($password, $this->PW)) {
            return true;
        }
        
        // Nếu không khớp hoặc không có PW, kiểm tra với mat_khau_local
        if (!empty($this->mat_khau_local) && password_verify($password, $this->mat_khau_local)) {
            return true;
        }
        
        return false;
	}
	
	/**
     * Mã hóa mật khẩu
     *
     * @param string $password Mật khẩu cần mã hóa
     * @return string Mật khẩu đã mã hóa
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
} 