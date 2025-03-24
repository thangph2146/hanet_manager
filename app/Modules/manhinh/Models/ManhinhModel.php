<?php

namespace App\Modules\manhinh\Models;

use App\Models\BaseModel;
use App\Modules\manhinh\Entities\Manhinh;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class ManhinhModel extends BaseModel
{
    protected $table = 'man_hinh';
    protected $primaryKey = 'man_hinh_id';
    protected $useSoftDeletes = false;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ma_man_hinh',
        'ten_man_hinh',
        'camera_id',
        'temlate_id',
        'status',
        'bin',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    
    protected $returnType = 'App\Modules\manhinh\Entities\Manhinh';
    
    // Định nghĩa các mối quan hệ
    protected $relations = [
        'camera' => [
            'type' => 'n-1',
            'table' => 'camera',
            'foreignKey' => 'camera_id',
            'localKey' => 'camera_id',
            'foreignPrimaryKey' => 'camera_id',
            'entity' => null,
            'conditions' => [
                ['field' => 'camera.bin', 'value' => 0]
            ],
            'select' => ['camera_id', 'ten_camera', 'status'],
            'useSoftDeletes' => true
        ],
        'template' => [
            'type' => 'n-1',
            'table' => 'template',
            'foreignKey' => 'temlate_id',
            'localKey' => 'temlate_id',
            'foreignPrimaryKey' => 'template_id',
            'entity' => null,
            'conditions' => [
                ['field' => 'template.bin', 'value' => 0]
            ],
            'select' => ['template_id', 'ten_template', 'status'],
            'useSoftDeletes' => true
        ],
    ];
    
    // Các trường được tìm kiếm
    protected $searchableFields = [
        'ma_man_hinh',
        'ten_man_hinh'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'camera_id',
        'temlate_id',
        'status',
        'bin',
        'created_at'
    ];
    
    // Các trường cần kiểm tra tính duy nhất
    protected $uniqueFields = [
        'ten_man_hinh' => 'Tên màn hình',
    ];
    
    // Các trường loại bỏ khoảng trắng thừa trước khi lưu
    protected $beforeSpaceRemoval = [
        'ma_man_hinh',
        'ten_man_hinh'
    ];
    
    // Các quy tắc xác thực
    public $validationRules = [
        'ten_man_hinh' => 'required|min_length[3]|max_length[255]|is_unique[man_hinh.ten_man_hinh,man_hinh_id,{man_hinh_id}]',
        'ma_man_hinh' => 'permit_empty|max_length[20]',
        'camera_id' => 'permit_empty|integer',
        'temlate_id' => 'permit_empty|integer',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    public $validationMessages = [
        'ten_man_hinh' => [
            'required' => 'Tên màn hình là bắt buộc',
            'min_length' => 'Tên màn hình phải có ít nhất {param} ký tự',
            'max_length' => 'Tên màn hình không được vượt quá {param} ký tự',
            'is_unique' => 'Tên màn hình đã tồn tại, vui lòng chọn tên khác',
        ],
        'ma_man_hinh' => [
            'max_length' => 'Mã màn hình không được vượt quá {param} ký tự',
        ],
        'camera_id' => [
            'integer' => 'ID camera phải là số nguyên',
        ],
        'temlate_id' => [
            'integer' => 'ID template phải là số nguyên',
        ],
    ];
    
    public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }
    
    /**
     * Khởi tạo query cơ bản cho model
     * Tự động tải quan hệ khi được yêu cầu
     * 
     * @return BaseBuilder
     */
    protected function getBaseQuery()
    {
        return parent::getBaseQuery()->where('bin', 0);
    }
    
    /**
     * Lấy tất cả các bản ghi màn hình đã xóa
     *
     * @param bool $withRelations Có tải mối quan hệ không
     * @return array
     */
    public function getAllDeleted(bool $withRelations = false)
    {
        $query = $this->withDeleted()
                 ->where('deleted_at IS NOT NULL')
                 ->orderBy('deleted_at', 'DESC');
        
        if ($withRelations) {
            $query->withRelations(['camera', 'template']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Kiểm tra tên màn hình đã tồn tại chưa
     *
     * @param string $tenManHinh
     * @param int|null $exceptId ID màn hình cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isNameExists(string $tenManHinh, int $exceptId = null)
    {
        $builder = $this->where('ten_man_hinh', $tenManHinh);
        
        if ($exceptId !== null) {
            $builder->where('man_hinh_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Kiểm tra mã màn hình đã tồn tại chưa
     *
     * @param string $maManHinh
     * @param int|null $exceptId ID màn hình cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isCodeExists(string $maManHinh, int $exceptId = null)
    {
        $builder = $this->where('ma_man_hinh', $maManHinh);
        
        if ($exceptId !== null) {
            $builder->where('man_hinh_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Lấy danh sách màn hình theo ID camera
     *
     * @param int $cameraId
     * @param bool $withRelations Có tải mối quan hệ không
     * @return array
     */
    public function getByCameraId(int $cameraId, bool $withRelations = false)
    {
        $query = $this->where('camera_id', $cameraId)
                 ->where('status', 1)
                 ->where('bin', 0)
                 ->orderBy('ten_man_hinh', 'ASC');
        
        if ($withRelations) {
            $query->withRelations(['camera', 'template']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Lấy danh sách màn hình theo ID template
     *
     * @param int $templateId
     * @param bool $withRelations Có tải mối quan hệ không
     * @return array
     */
    public function getByTemplateId(int $templateId, bool $withRelations = false)
    {
        $query = $this->where('temlate_id', $templateId)
                 ->where('status', 1)
                 ->where('bin', 0)
                 ->orderBy('ten_man_hinh', 'ASC');
        
        if ($withRelations) {
            $query->withRelations(['camera', 'template']);
        }
        
        return $query->findAll();
    }
    
    /**
     * Lấy tất cả camera để hiển thị trong dropdown
     *
     * @return array
     */
    public function getAllCameras()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('camera');
        $builder->where('bin', 0);
        $builder->orderBy('ten_camera', 'ASC');
        
        return $builder->get()->getResult();
    }
    
    /**
     * Lấy tất cả template để hiển thị trong dropdown
     *
     * @return array
     */
    public function getAllTemplates()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('template');
        $builder->where('bin', 0);
        $builder->orderBy('ten_template', 'ASC');
        
        return $builder->get()->getResult();
    }
    
    /**
     * Tìm kiếm màn hình theo từ khóa và bộ lọc
     * Tận dụng phương thức search từ BaseModel
     * 
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn bổ sung
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        // Biến đổi criteria để phù hợp với BaseModel
        $searchCriteria = [];
        
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $searchCriteria['search'] = $criteria['keyword'];
        }
        
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            $searchCriteria['filters'] = $criteria['filters'];
        }
        
        // Thiết lập tùy chọn
        $searchOptions = [];
        
        if (isset($options['sort_field']) && isset($options['sort_direction'])) {
            $searchOptions['sort'] = $options['sort_field'];
            $searchOptions['sort_direction'] = $options['sort_direction'];
        }
        
        if (isset($options['limit']) && isset($options['offset'])) {
            $searchOptions['limit'] = $options['limit'];
            $searchOptions['page'] = floor($options['offset'] / $options['limit']) + 1;
        }
        
        // Tải quan hệ
        $this->withRelations(['camera', 'template']);
        
        // Chỉ hiển thị bản ghi không bị xóa mềm và không trong thùng rác
        $builder = $this->where('bin', 0);
        
        return parent::search($searchCriteria, $searchOptions);
    }
    
    /**
     * Tìm và trả về màn hình với tất cả các mối quan hệ
     *
     * @param mixed $id ID màn hình cần tìm
     * @param array $relations Mảng các mối quan hệ cần tải
     * @param bool $validate Kiểm tra dữ liệu trả về
     * @return object|null
     */
    public function findWithRelations($id, $relations = [], $validate = false)
    {
        // Nếu không cung cấp mối quan hệ, sử dụng mặc định
        if (empty($relations)) {
            $relations = ['camera', 'template'];
        }
        
        return parent::findWithRelations($id, $relations, $validate);
    }
    
    /**
     * Chuyển màn hình vào thùng rác
     *
     * @param int $id ID màn hình cần chuyển
     * @return bool
     */
    public function moveToRecycleBin(int $id): bool
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục màn hình từ thùng rác
     *
     * @param int $id ID màn hình cần khôi phục
     * @return bool
     */
    public function restoreFromRecycleBin(int $id): bool
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy tất cả màn hình kèm phân trang
     *
     * @return array
     */
    public function getAll()
    {
        return $this->where('bin', 0)
                  ->withRelations(['camera', 'template'])
                  ->orderBy('updated_at', 'DESC')
                  ->paginate(10);
    }
    
    /**
     * Lấy tất cả màn hình đang hoạt động
     *
     * @return array
     */
    public function getAllActive()
    {
        return $this->where('status', 1)
                  ->where('bin', 0)
                  ->withRelations(['camera', 'template'])
                  ->orderBy('ten_man_hinh', 'ASC')
                  ->paginate(10);
    }
    
    /**
     * Lấy tất cả màn hình đang trong thùng rác
     *
     * @return array
     */
    public function getAllInRecycleBin()
    {
        return $this->where('bin', 1)
                  ->withRelations(['camera', 'template'])
                  ->orderBy('updated_at', 'DESC')
                  ->paginate(10);
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
        
        // Thêm điều kiện tìm kiếm từ khóa
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = $criteria['keyword'];
            $builder->groupStart()
                    ->like('ten_man_hinh', $keyword)
                    ->orLike('ma_man_hinh', $keyword)
                    ->groupEnd();
        }
        
        // Thêm điều kiện lọc
        if (isset($criteria['filters']) && is_array($criteria['filters'])) {
            foreach ($criteria['filters'] as $field => $value) {
                if ($value !== '' && $value !== null) {
                    $builder->where($field, $value);
                }
            }
        }
        
        // Chỉ đếm bản ghi không trong thùng rác, trừ khi có yêu cầu ngược lại
        if (!isset($criteria['filters']['bin'])) {
            $builder->where('bin', 0);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Chuẩn bị quy tắc validation tùy theo ngữ cảnh
     *
     * @param string $context Insert hay update
     * @param int|null $id ID của bản ghi cần update (nếu context là update)
     */
    public function prepareValidationRules(string $context = 'insert', ?int $id = null): void
    {
        // Đối với update, cần kiểm tra tính duy nhất của tên loại trừ chính bản ghi đó
        if ($context === 'update' && $id !== null) {
            // Thiết lập quy tắc duy nhất cho ten_man_hinh khi update
            $this->validationRules['ten_man_hinh'] = 'required|min_length[3]|max_length[255]|is_unique[man_hinh.ten_man_hinh,man_hinh_id,'.$id.']';
        }
    }
    
    /**
     * Kiểm tra validation cho dữ liệu trước khi update
     *
     * @param int $id ID của bản ghi cần update
     * @param array $data Dữ liệu cần kiểm tra
     * @return bool|array True nếu hợp lệ, mảng lỗi nếu không hợp lệ
     */
    public function validateUpdate(int $id, array $data)
    {
        $this->prepareValidationRules('update', $id);
        return $this->validate($data);
    }
    
    /**
     * Override phương thức validate để phù hợp với phiên bản của lớp cha
     *
     * @param array $data Dữ liệu cần kiểm tra
     * @return bool
     */
    public function validate($data): bool
    {
        return parent::validate($data);
    }
} 