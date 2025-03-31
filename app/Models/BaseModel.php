<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class BaseModel extends Model
{
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $relations = [];
    protected $relationsToLoad = [];
    protected $searchableFields = [];
    protected $filterableFields = [];
    protected $beforeSpaceRemoval = [];
    protected $concatFields = [];
    protected $allowedFields = [];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    protected $DBDebug = true;
    protected $DBGroup = 'default';
    protected $tempReturnType = null;
    protected $tempUseSoftDeletes = null;
    protected $tempWithDeleted = false;
    protected $tempOnlyDeleted = false;

    // Common validation rules that can be extended
    protected $commonValidationRules = [
        'status' => 'permit_empty|in_list[0,1]',
        'email' => 'permit_empty|valid_email',
        'phone' => 'permit_empty|numeric|min_length[10]|max_length[15]',
        'password' => 'required|min_length[6]',
        'confirm_password' => 'required|matches[password]',
        'image' => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
        'file' => 'permit_empty|uploaded[file]|max_size[file,2048]|mime_in[file,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document]'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->initializeModel();
    }

    protected function initializeModel()
    {
        if (!empty($this->allowedFields)) {
            foreach ($this->commonValidationRules as $field => $rules) {
                if (in_array($field, $this->allowedFields)) {
                    $this->validationRules[$field] = $rules;
                }
            }
        }
    }

    // Base query builder methods
    protected function getBaseQuery()
    {
        return $this->builder();
    }

    // Generic join method
    public function joinWith(array $joins = [], array $conditions = [], $select = '*')
    {
        $query = $this->select($select);

        foreach ($joins as $join) {
            $table = $join['table'];
            $condition = $join['condition'];
            $type = $join['type'] ?? 'inner';
            $query->join($table, $condition, $type);
        }

        if (!empty($conditions)) {
            foreach ($conditions as $condition) {
                $field = $condition['field'];
                $value = $condition['value'];
                $operator = $condition['operator'] ?? '=';
                
                switch ($operator) {
                    case 'in':
                        $query->whereIn($field, $value);
                        break;
                    case 'not in':
                        $query->whereNotIn($field, $value);
                        break;
                    case 'like':
                        $query->like($field, $value);
                        break;
                    default:
                        $query->where($field . ' ' . $operator, $value);
                }
            }
        }

        return $query;
    }

    // Enhanced relationship handling
    public function withRelations(array $relations = [])
    {
        $relationsToLoad = [];
        
        foreach ($relations as $key => $value) {
            if (is_int($key) && is_string($value)) {
                if (isset($this->relations[$value])) {
                    $relationsToLoad[$value] = $this->relations[$value];
                }
            } else {
                $relationsToLoad[$key] = $value;
            }
        }
        
        $this->relationsToLoad = $relationsToLoad;
        return $this;
    }

    /**
     * Tìm bản ghi với ID và các mối quan hệ
     *
     * @param int $id ID bản ghi cần tìm
     * @param bool|array $relations Mảng các mối quan hệ cần tải
     * @param bool $validate Có thực hiện validation không
     * @return object|null Đối tượng entity hoặc null
     */
    public function findWithRelations($id, $relations = [], $validate = false)
    {
        $data = $this->find($id);
        if (!$data) {
            return null;
        }
        
        if (property_exists($data, 'deleted_at') && ($data->deleted_at === '' || $data->deleted_at === '0000-00-00 00:00:00')) {
            $data->deleted_at = null;
        }
        
        $relationsToLoad = [];
        
        if ($relations === true) {
            $relationsToLoad = array_keys($this->relations);
        } 
        elseif (is_array($relations) && !empty($relations)) {
            $relationsToLoad = $relations;
        }
        else {
            $relationsToLoad = array_keys($this->relations);
        }
        
        foreach ($relationsToLoad as $relation) {
            if (is_string($relation) && isset($this->relations[$relation])) {
                $config = $this->relations[$relation];
                $data->$relation = $this->fetchRelation($relation, $config, $data);
            }
        }
        
        return $data;
    }

    protected function fetchRelation($relationName, $config, $data)
    {
        if (is_string($config)) {
            if (!isset($this->relations[$relationName])) {
                return null;    
            }
            $config = $this->relations[$relationName];
        }

        $type = $config['type'] ?? '1-1';
        $foreignTable = $config['table'];
        $foreignKey = $config['foreignKey'];
        $localKey = $config['localKey'] ?? $this->primaryKey;
        $entityClass = $config['entity'] ?? 'App\Entities\BaseEntity';
        
        if ($entityClass === 'App\Entities\BaseEntity') {
            $entityClass = null;
        }
        
        $conditions = $config['conditions'] ?? [];
        $select = $config['select'] ?? '*';
        $orderBy = $config['orderBy'] ?? null;
        $orderDir = $config['orderDir'] ?? 'ASC';

        $query = $this->db->table($foreignTable)->select($select);

        foreach ($conditions as $condition) {
            $field = $condition['field'];
            $value = $condition['value'];
            $operator = $condition['operator'] ?? '=';
            
            switch ($operator) {
                case 'in':
                    $query->whereIn($field, $value);
                    break;
                case 'not in':
                    $query->whereNotIn($field, $value);
                    break;
                case 'like':
                    $query->like($field, $value);
                    break;
                default:
                    $query->where($field . ' ' . $operator, $value);
            }
        }

        if (isset($config['useSoftDeletes']) && $config['useSoftDeletes']) {
            $query->where("{$foreignTable}.{$this->deletedField}", null);
        }

        if ($orderBy) {
            $query->orderBy($orderBy, $orderDir);
        }

        switch ($type) {
            case '1-1':
                $result = $query->where($foreignKey, $data->$localKey)
                               ->get()
                               ->getRow();
                if ($result) {
                    if ($entityClass && class_exists($entityClass)) {
                        try {
                            return new $entityClass((array) $result);
                        } catch (\Exception $e) {
                            return $result;
                        }
                    }
                    return $result;
                }
                return null;

            case '1-n':
                $results = $query->where($foreignKey, $data->$localKey)
                                ->get()
                                ->getResult();
                if ($entityClass && class_exists($entityClass)) {
                    try {
                        return array_map(function($row) use ($entityClass) {
                            return new $entityClass((array) $row);
                        }, $results);
                    } catch (\Exception $e) {
                        return $results;
                    }
                }
                return $results;

            case 'n-n':
                $pivotTable = $config['pivotTable'];
                $pivotLocalKey = $config['pivotLocalKey'];
                $pivotForeignKey = $config['pivotForeignKey'];

                $results = $query->join($pivotTable, "$pivotTable.$pivotForeignKey = $foreignTable.id")
                                ->where("$pivotTable.$pivotLocalKey", $data->$localKey)
                                ->get()
                                ->getResult();
                if ($entityClass && class_exists($entityClass)) {
                    try {
                        return array_map(function($row) use ($entityClass) {
                            return new $entityClass((array) $row);
                        }, $results);
                    } catch (\Exception $e) {
                        return $results;
                    }
                }
                return $results;

            case 'n-1':
                $foreignPrimaryKey = isset($config['foreignPrimaryKey']) ? $config['foreignPrimaryKey'] : 'id';
                $result = $query->where($foreignPrimaryKey, $data->$foreignKey)
                               ->get()
                               ->getRow();
                
                if ($result) {
                    if ($entityClass && class_exists($entityClass)) {
                        try {
                            return new $entityClass((array) $result);
                        } catch (\Exception $e) {
                            return $result;
                        }
                    }
                    return $result;
                }
                return null;

            default:
                return null;
        }
    }

    // Common CRUD operations with additional features
    public function createWithRelations(array $data, array $relations = [])
    {
        $this->db->transStart();
        
        $id = $this->insert($data);
        
        if ($id && !empty($relations)) {
            foreach ($relations as $relation => $relatedData) {
                $this->saveRelation($id, $relation, $relatedData);
            }
        }
        
        $this->db->transComplete();
        return $id;
    }

    
    public function updateWithRelations($id, array $data, array $relations = [])
    {
        $this->db->transStart();
        
        $updated = $this->update($id, $data);
        
        if ($updated && !empty($relations)) {
            foreach ($relations as $relation => $relatedData) {
                $this->saveRelation($id, $relation, $relatedData);
            }
        }
        
        $this->db->transComplete();
        return $updated;
    }

    protected function saveRelation($id, $relation, $relatedData)
    {
        if (!isset($this->relations[$relation])) {
            return false;
        }

        $config = $this->relations[$relation];
        $type = $config['type'] ?? '1-1';
        
        switch ($type) {
            case 'n-n':
                $pivotTable = $config['pivotTable'];
                $pivotLocalKey = $config['pivotLocalKey'];
                $pivotForeignKey = $config['pivotForeignKey'];
                
                $this->db->table($pivotTable)->where($pivotLocalKey, $id)->delete();
                
                foreach ($relatedData as $foreignId) {
                    $this->db->table($pivotTable)->insert([
                        $pivotLocalKey => $id,
                        $pivotForeignKey => $foreignId
                    ]);
                }
                break;
                
            case '1-n':
                $foreignTable = $config['table'];
                $foreignKey = $config['foreignKey'];
                
                $this->db->table($foreignTable)
                         ->where($foreignKey, $id)
                         ->update([$this->deletedField => date('Y-m-d H:i:s')]);
                
                foreach ($relatedData as $data) {
                    $data[$foreignKey] = $id;
                    $this->db->table($foreignTable)->insert($data);
                }
                break;
                
            case '1-1':
                $foreignTable = $config['table'];
                $foreignKey = $config['foreignKey'];
                
                $existing = $this->db->table($foreignTable)
                                   ->where($foreignKey, $id)
                                   ->get()
                                   ->getRow();
                
                if ($existing) {
                    $this->db->table($foreignTable)
                             ->where($foreignKey, $id)
                             ->update($relatedData);
                } else {
                    $relatedData[$foreignKey] = $id;
                    $this->db->table($foreignTable)->insert($relatedData);
                }
                break;
        }
        
        return true;
    }

    // Advanced search functionality
    public function search(array $criteria = [], array $options = [])
    {
        $builder = $this->builder();
        
        if (!empty($criteria['search']) && !empty($this->searchableFields)) {
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $criteria['search']);
            }
            $builder->groupEnd();
        }
        
        if (!empty($criteria['filters']) && !empty($this->filterableFields)) {
            foreach ($criteria['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    if (is_array($value)) {
                        $builder->whereIn($field, $value);
                    } else {
                        $builder->where($field, $value);
                    }
                }
            }
        }
        
        if (!empty($options['sort'])) {
            $direction = $options['sort_direction'] ?? 'ASC';
            $builder->orderBy($options['sort'], $direction);
        }
        
        if (isset($options['page']) && isset($options['limit'])) {
            $offset = ($options['page'] - 1) * $options['limit'];
            $builder->limit($options['limit'], $offset);
        }
        
        $results = $builder->get()->getResult($this->returnType);
        
        return $this->loadRelationsForResults($results);
    }
    
    /**
     * Tải các mối quan hệ cho kết quả trả về
     *
     * @param array $results Mảng kết quả cần tải quan hệ
     * @return array Mảng kết quả đã tải quan hệ
     */
    protected function loadRelationsForResults(array $results)
    {
        if (empty($results)) {
            return $results;
        }
        
        $relationsToLoad = !empty($this->relationsToLoad) ? $this->relationsToLoad : $this->relations;
        if (empty($relationsToLoad)) {
            return $results;
        }
        
        foreach ($results as $index => $item) {
            foreach ($relationsToLoad as $relation => $config) {
                $item->$relation = $this->fetchRelation($relation, $config, $item);
                $results[$index] = $item;
            }
        }
        
        return $results;
    }

    // Soft delete operations
    public function restore($id)
    {
        return $this->update($id, [
            $this->deletedField => null,
            $this->updatedField => date('Y-m-d H:i:s')
        ]);
    }

    public function getDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }

    // Space removal utility
    protected function removeSpaces(array $data)
    {
        if (!isset($data['data']) || empty($this->beforeSpaceRemoval)) {
            return $data;
        }

        foreach ($this->beforeSpaceRemoval as $field) {
            if (isset($data['data'][$field])) {
                $data['data'][$field] = trim(preg_replace('/\s+/', ' ', $data['data'][$field]));
            }
        }

        return $data;
    }

    // Utility methods
    public function exists($id)
    {
        return $this->find($id) !== null;
    }

    public function countAll($conditions = [])
    {
        $builder = $this->builder();
        foreach ($conditions as $field => $value) {
            $builder->where($field, $value);
        }
        return $builder->countAllResults();
    }

    public function findOneBy(array $conditions)
    {
        return $this->where($conditions)->first();
    }

    public function findBy(array $conditions, $limit = null, $offset = 0)
    {
        $query = $this->where($conditions);
        
        if ($limit !== null) {
            $query->limit($limit, $offset);
        }
        
        return $query->findAll();
    }

    // Helper method to build CONCAT fields
    protected function buildConcatFields(array $fields, string $separator = ' ')
    {
        return "CONCAT(" . implode(", '{$separator}', ", $fields) . ")";
    }

    /**
     * Override phương thức findAll để hỗ trợ tải các mối quan hệ
     *
     * @param int|null $limit Giới hạn kết quả
     * @param int $offset Vị trí bắt đầu
     * @return array Mảng kết quả với relations
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $results = parent::findAll($limit, $offset);
        return $this->loadRelationsForResults($results);
    }

    /**
     * Override phương thức paginate để hỗ trợ tải các mối quan hệ
     *
     * @param int|null $perPage Số bản ghi mỗi trang
     * @param string $group Nhóm phân trang
     * @param int|null $page Trang hiện tại
     * @param int $segment Phân đoạn
     * @return array Mảng kết quả phân trang với relations
     */
    public function paginate(?int $perPage = null, string $group = 'default', ?int $page = null, int $segment = 0)
    {
        $results = parent::paginate($perPage, $group, $page, $segment);
        return $this->loadRelationsForResults($results);
    }

    /**
     * Định dạng ngày giờ từ chuỗi đầu vào của form (Y-m-d\TH:i) sang định dạng datetime
     *
     * @param string $dateTimeString Chuỗi datetime từ form input
     * @return string|null Chuỗi datetime đã được định dạng hoặc null nếu là chuỗi rỗng
     */
    public function formatDateTime($dateTimeString)
    {
        if (empty($dateTimeString)) {
            return null;
        }
        
        try {
            $time = new \CodeIgniter\I18n\Time($dateTimeString);
            return $time->toDateTimeString();
        } catch (\Exception $e) {
            log_message('error', 'Lỗi định dạng thời gian: ' . $e->getMessage());
            return null;
        }
    }

    // Các phương thức mới cho xử lý dữ liệu
    public function getRelations(): array
    {
        return $this->relations;
    }

    public function getSearchableFields(): array
    {
        return $this->searchableFields;
    }

    public function getFilterableFields(): array
    {
        return $this->filterableFields;
    }

    public function getBeforeSpaceRemoval(): array
    {
        return $this->beforeSpaceRemoval;
    }

    public function getConcatFields(): array
    {
        return $this->concatFields;
    }

    public function getCommonValidationRules(): array
    {
        return $this->commonValidationRules;
    }

    public function setSearchableFields(array $fields): self
    {
        $this->searchableFields = $fields;
        return $this;
    }

    public function setFilterableFields(array $fields): self
    {
        $this->filterableFields = $fields;
        return $this;
    }

    public function setBeforeSpaceRemoval(array $fields): self
    {
        $this->beforeSpaceRemoval = $fields;
        return $this;
    }

    public function setConcatFields(array $fields): self
    {
        $this->concatFields = $fields;
        return $this;
    }

    /**
     * Lấy dữ liệu theo điều kiện với phân trang
     * 
     * @param array $conditions Điều kiện tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function getByConditions(array $conditions = [], array $options = [])
    {
        $builder = $this->builder();
        
        // Thêm điều kiện tìm kiếm
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $builder->whereIn($field, $value);
            } else {
                $builder->where($field, $value);
            }
        }
        
        // Thêm điều kiện soft delete
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField . ' IS NULL');
        }
        
        // Xử lý phân trang
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? $this->perPage;
        $offset = ($page - 1) * $limit;
        
        // Xử lý sắp xếp
        $sort = $options['sort'] ?? $this->defaultSort;
        if ($sort) {
            list($field, $direction) = explode(' ', $sort);
            $builder->orderBy($field, $direction);
        }
        
        // Thực hiện truy vấn
        $results = $builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Tính tổng số bản ghi
        $total = $builder->countAllResults(false);
        
        // Tạo pager và lưu trạng thái bằng service
        $pagerService = service('pager');
        // Lưu trữ trạng thái pager để controller và view có thể sử dụng
        $this->pager = $pagerService->store('default', $page, $limit, $total);
        
        return $results;
    }

    /**
     * Lấy dữ liệu theo ID với các mối quan hệ
     * 
     * @param int $id ID cần tìm
     * @param array $relations Các mối quan hệ cần lấy
     * @return object|null
     */
    public function getByIdWithRelations($id, array $relations = [])
    {
        $data = $this->find($id);
        if (!$data) {
            return null;
        }
        
        foreach ($relations as $relation) {
            if (isset($this->relations[$relation])) {
                $config = $this->relations[$relation];
                $data->$relation = $this->fetchRelation($relation, $config, $data);
            }
        }
        
        return $data;
    }

    /**
     * Lấy dữ liệu theo điều kiện với các mối quan hệ
     * 
     * @param array $conditions Điều kiện tìm kiếm
     * @param array $relations Các mối quan hệ cần lấy
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function getByConditionsWithRelations(array $conditions = [], array $relations = [], array $options = [])
    {
        $results = $this->getByConditions($conditions, $options);
        
        foreach ($results as $item) {
            foreach ($relations as $relation) {
                if (isset($this->relations[$relation])) {
                    $config = $this->relations[$relation];
                    $item->$relation = $this->fetchRelation($relation, $config, $item);
                }
            }
        }
        
        return $results;
    }

    /**
     * Lấy dữ liệu theo từ khóa tìm kiếm
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function searchByKeyword($keyword, array $options = [])
    {
        $builder = $this->builder();
        
        // Thêm điều kiện tìm kiếm theo từ khóa
        if (!empty($keyword) && !empty($this->searchableFields)) {
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $keyword);
            }
            $builder->groupEnd();
        }
        
        // Thêm điều kiện soft delete
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField . ' IS NULL');
        }
        
        // Xử lý phân trang
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? $this->perPage;
        $offset = ($page - 1) * $limit;
        
        // Xử lý sắp xếp
        $sort = $options['sort'] ?? $this->defaultSort;
        if ($sort) {
            list($field, $direction) = explode(' ', $sort);
            $builder->orderBy($field, $direction);
        }
        
        // Thực hiện truy vấn
        $results = $builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Tính tổng số bản ghi
        $total = $builder->countAllResults(false);
        
        // Tạo pager và lưu trạng thái bằng service
        $pagerService = service('pager');
        // Lưu trữ trạng thái pager để controller và view có thể sử dụng
        $this->pager = $pagerService->store('default', $page, $limit, $total);
        
        return $results;
    }

    /**
     * Lấy dữ liệu theo điều kiện lọc
     * 
     * @param array $filters Điều kiện lọc
     * @param array $options Tùy chọn phân trang và sắp xếp
     * @return array
     */
    public function getByFilters(array $filters = [], array $options = [])
    {
        $builder = $this->builder();
        
        // Thêm điều kiện lọc
        foreach ($filters as $field => $value) {
            if (in_array($field, $this->filterableFields)) {
                if (is_array($value)) {
                    $builder->whereIn($field, $value);
                } else {
                    $builder->where($field, $value);
                }
            }
        }
        
        // Thêm điều kiện soft delete
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField . ' IS NULL');
        }
        
        // Xử lý phân trang
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? $this->perPage;
        $offset = ($page - 1) * $limit;
        
        // Xử lý sắp xếp
        $sort = $options['sort'] ?? $this->defaultSort;
        if ($sort) {
            list($field, $direction) = explode(' ', $sort);
            $builder->orderBy($field, $direction);
        }
        
        // Thực hiện truy vấn
        $results = $builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Tính tổng số bản ghi
        $total = $builder->countAllResults(false);
        
        // Tạo pager và lưu trạng thái bằng service
        $pagerService = service('pager');
        // Lưu trữ trạng thái pager để controller và view có thể sử dụng
        $this->pager = $pagerService->store('default', $page, $limit, $total);
        
        return $results;
    }

    /**
     * Lấy dữ liệu theo điều kiện sắp xếp
     * 
     * @param array $sort Điều kiện sắp xếp
     * @param array $options Tùy chọn phân trang
     * @return array
     */
    public function getBySort(array $sort = [], array $options = [])
    {
        $builder = $this->builder();
        
        // Thêm điều kiện sắp xếp
        foreach ($sort as $field => $direction) {
            if (in_array($field, $this->sortableFields)) {
                $builder->orderBy($field, $direction);
            }
        }
        
        // Thêm điều kiện soft delete
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField . ' IS NULL');
        }
        
        // Xử lý phân trang
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? $this->perPage;
        $offset = ($page - 1) * $limit;
        
        // Thực hiện truy vấn
        $results = $builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Tính tổng số bản ghi
        $total = $builder->countAllResults(false);
        
        // Tạo pager và lưu trạng thái bằng service
        $pagerService = service('pager');
        // Lưu trữ trạng thái pager để controller và view có thể sử dụng
        $this->pager = $pagerService->store('default', $page, $limit, $total);
        
        return $results;
    }

    /**
     * Lấy dữ liệu theo điều kiện tổng hợp
     * 
     * @param array $params Tham số tìm kiếm, lọc, sắp xếp
     * @param array $options Tùy chọn phân trang
     * @return array
     */
    public function getByParams(array $params = [], array $options = [])
    {
        $builder = $this->builder();
        
        // Xử lý tìm kiếm theo từ khóa
        if (!empty($params['keyword']) && !empty($this->searchableFields)) {
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                $builder->orLike($field, $params['keyword']);
            }
            $builder->groupEnd();
        }
        
        // Xử lý điều kiện lọc
        if (!empty($params['filters'])) {
            foreach ($params['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields)) {
                    if (is_array($value)) {
                        $builder->whereIn($field, $value);
                    } else {
                        $builder->where($field, $value);
                    }
                }
            }
        }
        
        // Xử lý điều kiện sắp xếp
        if (!empty($params['sort'])) {
            // Tách trường và hướng sắp xếp từ chuỗi, ví dụ: "ten_bac_hoc DESC"
            $sortParts = explode(' ', $params['sort']);
            $sortField = $sortParts[0] ?? null;
            $sortOrder = strtoupper($sortParts[1] ?? 'ASC');
            
            // Kiểm tra xem trường sắp xếp có hợp lệ không (ví dụ: có trong $allowedFields hoặc $sortFields nếu bạn định nghĩa)
            // Tạm thời, chúng ta sẽ tin tưởng $params['sort'] đã được kiểm tra ở Controller
            if ($sortField) {
                // Kiểm tra nếu field có dạng table.column để tránh lỗi ambiguity khi join
                $fieldName = strpos($sortField, '.') !== false ? $sortField : $this->table . '.' . $sortField;
                 $builder->orderBy($fieldName, $sortOrder);
            }
        }
        
        // Thêm điều kiện soft delete
        if ($this->useSoftDeletes) {
            $builder->where($this->deletedField . ' IS NULL');
        }
        
        // Xử lý phân trang
        $page = $options['page'] ?? 1;
        $limit = $options['limit'] ?? $this->perPage;
        $offset = ($page - 1) * $limit;
        
        // Thực hiện truy vấn
        $results = $builder->limit($limit, $offset)->get()->getResult($this->returnType);
        
        // Tính tổng số bản ghi
        $total = $builder->countAllResults(false);
        
        // Tạo pager và lưu trạng thái bằng service
        $pagerService = service('pager');
        // Lưu trữ trạng thái pager để controller và view có thể sử dụng
        $this->pager = $pagerService->store('default', $page, $limit, $total);
        
        return $results;
    }

    /**
     * Lấy dữ liệu theo điều kiện tổng hợp với các mối quan hệ
     * 
     * @param array $params Tham số tìm kiếm, lọc, sắp xếp
     * @param array $relations Các mối quan hệ cần lấy
     * @param array $options Tùy chọn phân trang
     * @return array
     */
    public function getByParamsWithRelations(array $params = [], array $relations = [], array $options = [])
    {
        $results = $this->getByParams($params, $options);
        
        foreach ($results as $item) {
            foreach ($relations as $relation) {
                if (isset($this->relations[$relation])) {
                    $config = $this->relations[$relation];
                    $item->$relation = $this->fetchRelation($relation, $config, $item);
                }
            }
        }
        
        return $results;
    }

    /**
     * Lấy dữ liệu theo điều kiện tổng hợp không phân trang (cho export)
     * 
     * @param array $params Tham số tìm kiếm, lọc, sắp xếp
     * @return array
     */
    public function getAllByParams(array $params = [])
    {
        $builder = $this->builder();
        
        // Xử lý tìm kiếm theo từ khóa hoặc giá trị tìm kiếm chung
        $searchKeyword = $params['search'] ?? ($params['keyword'] ?? '');
        if (!empty($searchKeyword) && !empty($this->searchableFields)) {
            $builder->groupStart();
            foreach ($this->searchableFields as $field) {
                 // Kiểm tra nếu field có dạng table.column
                 $fieldName = strpos($field, '.') !== false ? $field : $this->table . '.' . $field;
                $builder->orLike($fieldName, $searchKeyword);
            }
            $builder->groupEnd();
        }
        
        // Xử lý điều kiện lọc
        if (!empty($params['filters'])) {
            foreach ($params['filters'] as $field => $value) {
                if (in_array($field, $this->filterableFields) && $value !== '') {
                    // Kiểm tra nếu field có dạng table.column
                    $fieldName = strpos($field, '.') !== false ? $field : $this->table . '.' . $field;
                    if (is_array($value)) {
                        $builder->whereIn($fieldName, $value);
                    } else {
                        $builder->where($fieldName, $value);
                    }
                }
            }
        }
        
        // Xử lý điều kiện sắp xếp
        $sort = $params['sort'] ?? $this->defaultSort;
        if (!empty($sort)) {
            // Tách trường và hướng sắp xếp
             $sortParts = explode(' ', $sort);
             $sortField = $sortParts[0];
             $sortOrder = strtoupper($sortParts[1] ?? 'ASC');
             // Kiểm tra nếu field có dạng table.column
             $fieldName = strpos($sortField, '.') !== false ? $sortField : $this->table . '.' . $sortField;
             $builder->orderBy($fieldName, $sortOrder);
        }
        
        // Điều kiện soft delete sẽ được xử lý bởi các phương thức như onlyDeleted() hoặc withDeleted()
        // Nếu không gọi các phương thức đó, mặc định sẽ chỉ lấy bản ghi không bị xóa (do $useSoftDeletes = true)

        // Thực hiện truy vấn để lấy tất cả kết quả
        $results = $builder->get()->getResult($this->returnType);
        
        // Load relations nếu được yêu cầu
        return $this->loadRelationsForResults($results);
    }

    /**
     * Cập nhật bản ghi với kiểm tra dữ liệu rỗng
     * 
     * Phương thức này mở rộng từ phương thức update() của CodeIgniter\Model 
     * để xử lý trường hợp khi dữ liệu cập nhật rỗng, thay vì báo lỗi sẽ trả về true.
     * 
     * @param int|array|string $id ID của bản ghi cần cập nhật
     * @param object|array $data Dữ liệu cần cập nhật
     * @return boolean Kết quả cập nhật
     */
    public function safeUpdate($id, $data = null)
    {   
        // Chuyển đổi dữ liệu sang dạng array
        if (is_object($data)) {
            // Nếu là entity, sử dụng toArray() hoặc cast về array
            if (method_exists($data, 'toArray')) {
                $dataArray = $data->toArray();
            } else {
                $dataArray = (array) $data;
            }
        } else {
            $dataArray = $data;
        }
        
        // Nếu không có dữ liệu hoặc dữ liệu rỗng, trả về true thay vì báo lỗi
        if (empty($dataArray)) {
            log_message('debug', 'safeUpdate(): Không có dữ liệu để cập nhật. Bỏ qua và trả về true.');
            return true;
        }
        
        // Lọc chỉ những trường có trong $allowedFields
        $filteredData = [];
        foreach ($dataArray as $key => $value) {
            if (in_array($key, $this->allowedFields)) {
                $filteredData[$key] = $value;
            }
        }
        
        // Nếu sau khi lọc, dữ liệu vẫn rỗng, trả về true
        if (empty($filteredData)) {
            log_message('debug', 'safeUpdate(): Sau khi lọc qua allowedFields, không có dữ liệu hợp lệ để cập nhật. Bỏ qua và trả về true.');
            return true;
        }
        
        // Sử dụng phương thức update gốc của Model
        return $this->update($id, $filteredData);
    }
    
    /**
     * Cập nhật bản ghi kể cả khi không có dữ liệu thay đổi
     * 
     * Phương thức này tạm thời đặt giá trị $allowEmptyInserts = true
     * để cho phép cập nhật ngay cả khi không có dữ liệu
     * 
     * @param int|array|string $id ID của bản ghi cần cập nhật
     * @param object|array $data Dữ liệu cần cập nhật
     * @return boolean Kết quả cập nhật
     */
    public function forceUpdate($id, $data = null)
    {
        // Lưu giá trị $allowEmptyInserts hiện tại
        $currentAllowEmptyInserts = $this->allowEmptyInserts;
        
        // Tạm thời cho phép cập nhật rỗng
        $this->allowEmptyInserts = true;
        
        try {
            // Thực hiện cập nhật
            $result = $this->update($id, $data);
        } finally {
            // Khôi phục giá trị $allowEmptyInserts
            $this->allowEmptyInserts = $currentAllowEmptyInserts;
        }
        
        return $result;
    }
}