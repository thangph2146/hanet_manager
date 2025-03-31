<?php

namespace App\Modules\bachoc\Models;

use App\Models\BaseModel;
use App\Modules\bachoc\Entities\BacHoc;
use CodeIgniter\I18n\Time;

class BacHocModel extends BaseModel
{
    protected $table = 'bac_hoc';
    protected $primaryKey = 'bac_hoc_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Số lượng liên kết trang hiển thị xung quanh trang hiện tại   
    // Loại bỏ surroundCount vì đã có trong BaseModel hoặc Controller xử lý pager
    // protected $surroundCount = 2; 
    
    protected $allowedFields = [
        'ten_bac_hoc',
        'ma_bac_hoc',
        'status',
        'created_at', // Giữ lại để BaseModel biết có timestamps
        'updated_at', // Giữ lại để BaseModel biết có timestamps
        'deleted_at'  // Giữ lại để BaseModel biết có soft deletes
    ];
    
    protected $returnType = BacHoc::class;
    
    // Trường có thể tìm kiếm (BaseModel sẽ sử dụng)
    protected $searchableFields = [
        'ten_bac_hoc',
        'ma_bac_hoc'
    ];
    
    // Trường có thể lọc (BaseModel sẽ sử dụng)
    protected $filterableFields = [
        'status'
    ];
    
    // Các quy tắc xác thực sẽ được lấy từ Entity
    protected $validationRules = []; 
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Lấy tất cả bản ghi đang hoạt động
     * Sử dụng phương thức getByConditions từ BaseModel
     * 
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllActive(int $limit = 10, int $offset = 0, string $sort = 'created_at', string $order = 'DESC')
    {
        // Tính toán page từ offset và limit nếu cần
        $page = $offset >= 0 && $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        // Gọi getByConditions của BaseModel
        return $this->getByConditions(
            ['status' => 1], 
            [
                'limit' => $limit,
                'page' => $page, // Sử dụng page thay vì offset
                'sort' => "{$sort} {$order}" // Kết hợp sort và order
            ]
        );
    }
    
    /**
     * Đếm tổng số bản ghi đang hoạt động
     * Sử dụng countAll từ BaseModel
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllActive($conditions = [])
    {
        $baseConditions = ['status' => 1];
        // Thêm điều kiện không bị xóa mềm
        $baseConditions[$this->deletedField . ' IS NULL'] = null; 
        $mergedConditions = array_merge($baseConditions, $conditions);
        // Gọi countAll của BaseModel
        return $this->countAll($mergedConditions); 
    }
    
    /**
     * Chuẩn bị các quy tắc xác thực dựa trên tình huống
     * Phương thức này vẫn cần thiết để tùy chỉnh validation rules từ Entity.
     * 
     * @param string $scenario Tình huống xác thực ('insert' hoặc 'update')
     * @param array $data Dữ liệu cần xác thực (chứa ID khi update)
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        // Lấy validation rules và messages từ Entity
        $entity = new $this->returnType(); 
        if (method_exists($entity, 'getValidationRules')) {
            $this->validationRules = $entity->getValidationRules();
        }
        if (method_exists($entity, 'getValidationMessages')) {
            $this->validationMessages = $entity->getValidationMessages();
        }
        
        // Loại bỏ các trường không cần thiết cho validation
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        unset($this->validationRules[$this->primaryKey]); // Loại bỏ primary key
        
        // Bỏ qua rule 'bin' nếu có trong Entity (thường không validate)
        if (isset($this->validationRules['bin'])) {
             unset($this->validationRules['bin']);
        }

        // Điều chỉnh quy tắc 'is_unique' cho trường hợp update
        if ($scenario === 'update' && isset($data[$this->primaryKey])) {
            $primaryKeyValue = $data[$this->primaryKey];
            foreach ($this->validationRules as $field => &$ruleSet) {
                // Xử lý cả trường hợp rules là string hoặc array
                $rulesString = is_array($ruleSet) ? ($ruleSet['rules'] ?? '') : $ruleSet;
                
                if (strpos($rulesString, 'is_unique') !== false) {
                    // Thay thế placeholder {id} hoặc tương tự bằng giá trị thực tế
                    $newRulesString = preg_replace('/\{.*\}/', $primaryKeyValue, $rulesString); 
                    
                    if (is_array($ruleSet)) {
                        $ruleSet['rules'] = $newRulesString;
                    } else {
                        $ruleSet = $newRulesString;
                    }
                }
            }
            // Giải phóng biến tham chiếu
            unset($ruleSet); 
        }
    }
    
    /**
     * Kiểm tra xem tên bậc học đã tồn tại chưa
     * Phương thức này đặc thù cho BacHocModel nên giữ lại.
     *
     * @param string $tenBacHoc
     * @param int|null $excludeId ID của bản ghi cần loại trừ khi kiểm tra (cho update)
     * @return bool
     */
    public function isTenBacHocExists(string $tenBacHoc, ?int $excludeId = null): bool
    {
        return $this->isDuplicate('ten_bac_hoc', $tenBacHoc, $excludeId);
    }

    /**
     * Hàm kiểm tra trùng lặp chung (có thể đưa vào BaseModel nếu dùng nhiều)
     *
     * @param string $field Tên trường cần kiểm tra
     * @param mixed $value Giá trị cần kiểm tra
     * @param int|null $excludeId ID của bản ghi cần loại trừ
     * @return bool
     */
    protected function isDuplicate(string $field, $value, ?int $excludeId = null): bool
    {
        $builder = $this->builder();
        $builder->where($field, $value);
        
        // Luôn kiểm tra trong các bản ghi chưa bị xóa mềm
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField . ' IS NULL');
        }
        
        if ($excludeId !== null) {
            $builder->where($this->primaryKey . ' !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

 
}
