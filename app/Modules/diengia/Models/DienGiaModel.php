<?php

namespace App\Modules\diengia\Models;

use App\Models\BaseModel;
use App\Modules\diengia\Entities\DienGia;
use App\Modules\diengia\Libraries\Pager;

class DienGiaModel extends BaseModel
{
    protected $table = 'dien_gia';
    protected $primaryKey = 'dien_gia_id';
    protected $useAutoIncrement = true;
    
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $allowedFields = [
        'ten_dien_gia',
        'chuc_danh',
        'to_chuc',
        'gioi_thieu',
        'avatar',
        'thu_tu',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $returnType = '\App\Modules\diengia\Entities\DienGia';
    
    // Trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_dien_gia',
        'chuc_danh',
        'to_chuc'
    ];
    
    // Trường có thể lọc
    protected $filterableFields = [
        'thu_tu',
        'status'
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    
    // Pager
    protected $Pager = null;
    
    /**
     * Lấy tất cả diễn giả
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAll($limit = 10, $offset = 0, $sort = 'thu_tu', $order = 'ASC')
    {
        // Reset query builder để đảm bảo không có điều kiện nào từ trước
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('deleted_at', null);
        
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        // Lấy tổng số bản ghi để cấu hình pagination
        $total = $this->countAll();
        
        // Tính toán trang hiện tại từ offset và limit
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        // Khởi tạo Pager nếu chưa có
        if ($this->Pager === null) {
            $this->Pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->Pager->setTotal($total)
                         ->setPerPage($limit)
                         ->setCurrentPage($currentPage);
        }
        
        // Lấy dữ liệu với phân trang
        $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Đảm bảo kết quả được trả về dù không có dữ liệu
        return $result ?: [];
    }
    
    /**
     * Đếm tổng số diễn giả không nằm trong thùng rác
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('deleted_at', null);
        
        // Áp dụng điều kiện bổ sung nếu có
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Lấy tất cả diễn giả trong thùng rác
     *
     * @param int $limit Số lượng bản ghi trên mỗi trang
     * @param int $offset Vị trí bắt đầu lấy dữ liệu
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp (ASC, DESC)
     * @return array
     */
    public function getAllInRecycleBin(int $limit = 10, int $offset = 0, string $sort = 'updated_at', string $order = 'DESC')
    {
        // Reset query builder để đảm bảo không có điều kiện nào từ trước
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        $this->builder->where('deleted_at IS NOT NULL', null, false);
        
        // Thiết lập sắp xếp
        if ($sort && $order) {
            $this->builder->orderBy($sort, $order);
        }
        
        // Lấy tổng số bản ghi để cấu hình pagination
        $total = $this->countAllInRecycleBin();
        
        // Tính toán trang hiện tại từ offset và limit
        $currentPage = $limit > 0 ? floor($offset / $limit) + 1 : 1;
        
        // Khởi tạo Pager nếu chưa có
        if ($this->Pager === null) {
            $this->Pager = new Pager($total, $limit, $currentPage);
        } else {
            $this->Pager->setTotal($total)
                         ->setPerPage($limit)
                         ->setCurrentPage($currentPage);
        }
        
        // Lấy dữ liệu với phân trang
        if ($limit > 0) {
            $result = $this->builder->limit($limit, $offset)->get()->getResult($this->returnType);
            return $result ?: [];
        }
        
        return $this->findAll();
    }
    
    /**
     * Đếm tổng số diễn giả trong thùng rác
     *
     * @param array $conditions Điều kiện bổ sung
     * @return int
     */
    public function countAllInRecycleBin($conditions = [])
    {
        $builder = $this->builder();
        $builder->where('deleted_at IS NOT NULL', null, false);
        
        // Áp dụng điều kiện bổ sung nếu có
        if (!empty($conditions)) {
            $builder->where($conditions);
        }
        
        return $builder->countAllResults();
    }
    
    /**
     * Tìm kiếm diễn giả
     *
     * @param array $criteria Tiêu chí tìm kiếm
     * @param array $options Tùy chọn tìm kiếm (limit, offset, sort, order)
     * @return array
     */
    public function search(array $criteria = [], array $options = [])
    {
        // Reset query builder để đảm bảo không có điều kiện nào từ trước
        $this->builder = $this->db->table($this->table);
        $this->builder->select('*');
        
        // Mặc định các tùy chọn
        $defaultOptions = [
            'limit' => 10,
            'offset' => 0,
            'sort' => 'thu_tu',
            'order' => 'ASC'
        ];
        
        // Merge options
        $options = array_merge($defaultOptions, $options);
        
        // Log đầy đủ tham số tìm kiếm và tùy chọn
        log_message('debug', 'Tham số tìm kiếm và tùy chọn đầy đủ:');
        log_message('debug', 'Tham số tìm kiếm: ' . json_encode($criteria));
        log_message('debug', 'Tùy chọn: ' . json_encode($options));
        
        // Xử lý từ khóa tìm kiếm
        if (isset($criteria['keyword']) && !empty($criteria['keyword'])) {
            $keyword = trim($criteria['keyword']);
            
            // Sử dụng LIKE chính xác
            $this->builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $this->builder->like($field, $keyword);
                } else {
                    $this->builder->orLike($field, $keyword);
                }
            }
            $this->builder->groupEnd();
            
            log_message('debug', 'Từ khóa tìm kiếm: ' . $keyword);
        }
        
        // Xác định xem đang lấy dữ liệu từ thùng rác hay không
        if (isset($criteria['bin']) && $criteria['bin'] == 1) {
            // Lấy từ thùng rác (đã xóa mềm)
            $this->builder->where('deleted_at IS NOT NULL', null, false);
        } else {
            // Lấy các bản ghi không ở trong thùng rác (chưa xóa mềm)
            $this->builder->where('deleted_at', null);
        }
        
        // Thiết lập sắp xếp
        if (!empty($options['sort']) && !empty($options['order'])) {
            $this->builder->orderBy($options['sort'], $options['order']);
        }
        
        // Clone builder để đếm tổng số bản ghi
        $builderForCount = clone $this->builder;
        $total = $builderForCount->countAllResults();
        log_message('debug', 'Tổng số bản ghi phù hợp (đếm trực tiếp): ' . $total);
        
        // Tính toán trang hiện tại từ offset và limit
        $currentPage = $options['limit'] > 0 ? floor($options['offset'] / $options['limit']) + 1 : 1;
        
        // Khởi tạo Pager nếu chưa có
        if ($this->Pager === null) {
            $this->Pager = new Pager($total, $options['limit'], $currentPage);
        } else {
            $this->Pager->setTotal($total)
                        ->setPerPage($options['limit'])
                        ->setCurrentPage($currentPage);
        }
        
        // Áp dụng giới hạn và offset
        if (!empty($options['limit'])) {
            $this->builder->limit($options['limit'], $options['offset']);
        }
        
        // Thực hiện truy vấn
        $results = $this->builder->get()->getResult($this->returnType);
        
        // Trả về kết quả tìm kiếm
        return $results ?: [];
    }
    
    /**
     * Đếm số lượng kết quả tìm kiếm
     * 
     * @param array $params Tham số tìm kiếm
     * @return int Số lượng kết quả tìm kiếm
     */
    public function countSearchResults(array $params)
    {
        // Reset query builder để đảm bảo không có điều kiện nào từ trước
        $this->builder = $this->db->table($this->table);
        
        // Xử lý từ khóa tìm kiếm
        if (isset($params['keyword']) && !empty($params['keyword'])) {
            $keyword = trim($params['keyword']);
            
            // Sử dụng LIKE trên tất cả các trường có thể tìm kiếm
            $this->builder->groupStart();
            foreach ($this->searchableFields as $index => $field) {
                if ($index === 0) {
                    $this->builder->like($field, $keyword);
                } else {
                    $this->builder->orLike($field, $keyword);
                }
            }
            $this->builder->groupEnd();
        }
        
        // Xác định xem đang đếm dữ liệu từ thùng rác hay không
        if (isset($params['bin']) && $params['bin'] == 1) {
            // Đếm từ thùng rác (đã xóa mềm)
            $this->builder->where('deleted_at IS NOT NULL', null, false);
        } else {
            // Đếm các bản ghi không ở trong thùng rác (chưa xóa mềm)
            $this->builder->where('deleted_at', null);
        }
        
        // Đếm số lượng kết quả
        $count = $this->builder->countAllResults();
        log_message('debug', 'Tổng số kết quả tìm kiếm: ' . $count);
        
        return $count;
    }
    
    /**
     * Chuẩn bị quy tắc validation dựa trên tình huống
     * 
     * @param string $scenario Tình huống (insert hoặc update)
     * @param array $data Dữ liệu cần validate
     */
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $entity = new DienGia();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại bỏ các quy tắc validate cho trường thời gian (vì chúng được tự động xử lý bởi model)
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        // Loại bỏ validation cho trường avatar khi đang xử lý file tải lên
        unset($this->validationRules['avatar']);
        
        // Điều chỉnh quy tắc dựa trên tình huống
        if ($scenario === 'update' && isset($data['dien_gia_id'])) {
            // Khi cập nhật, cần loại trừ chính ID hiện tại khi kiểm tra tính duy nhất
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    // Thay thế placeholder {dien_gia_id} bằng ID thực tế
                    $rules = str_replace('{dien_gia_id}', $data['dien_gia_id'], $rules);
                }
            }
        } elseif ($scenario === 'insert') {
            // Khi thêm mới, bỏ loại trừ ID vì không có ID nào cần loại trừ
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace(',dien_gia_id,{dien_gia_id}', '', $rules);
                }
            }
        }
    }
    
    /**
     * Chuyển một diễn giả vào thùng rác (sử dụng xóa mềm)
     *
     * @param int $id ID của diễn giả cần chuyển vào thùng rác
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function moveToRecycleBin($id)
    {
        // Sử dụng phương thức delete có sẵn của model với tham số purge=false để xóa mềm
        return $this->delete($id, false);
    }
    
    /**
     * Khôi phục diễn giả từ thùng rác
     *
     * @param int $id ID của diễn giả cần khôi phục
     * @return bool Trả về true nếu thành công, false nếu thất bại
     */
    public function restoreFromRecycleBin($id)
    {
        log_message('debug', "Bắt đầu khôi phục diễn giả ID: {$id}");
        
        try {
            // Sử dụng BaseModel::restore có sẵn để khôi phục bản ghi
            return $this->restore($id);
        } catch (\Exception $e) {
            log_message('error', "Ngoại lệ khi khôi phục diễn giả: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra xem tên diễn giả đã tồn tại chưa
     *
     * @param string $name Tên diễn giả cần kiểm tra
     * @param int|null $exceptId ID diễn giả để loại trừ khỏi việc kiểm tra (hữu ích khi cập nhật)
     * @return bool Trả về true nếu tên đã tồn tại, false nếu chưa
     */
    public function isNameExists(string $name, ?int $exceptId = null): bool
    {
        $builder = $this->builder();
        $builder->where('ten_dien_gia', $name);
        
        // Loại trừ diễn giả có ID cụ thể (dùng khi cập nhật)
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        // Loại trừ các bản ghi đã bị xóa mềm
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField, null);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Thiết lập số lượng liên kết trang hiển thị xung quanh trang hiện tại
     * 
     * @param int $count Số lượng liên kết trang hiển thị (mỗi bên)
     * @return $this
     */
    public function setSurroundCount(int $count)
    {
        // Nếu đã có Pager thì cập nhật, nếu chưa thì chỉ lưu giá trị để dùng sau
        if ($this->Pager !== null) {
            $this->Pager->setSurroundCount($count);
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
        return $this->Pager;
    }
} 