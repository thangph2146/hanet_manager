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

    // Common validation rules that can be extended
    protected $commonValidationRules = [
        'status' => 'permit_empty|in_list[0,1]',
        'email' => 'permit_empty|valid_email',
        'phone' => 'permit_empty|numeric|min_length[10]|max_length[15]',
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
        // Nếu $relations là mảng đơn giản gồm các chuỗi (tên quan hệ)
        // thì chuyển đổi thành mảng kết hợp
        $relationsToLoad = [];
        
        foreach ($relations as $key => $value) {
            // Nếu key là số nguyên và giá trị là chuỗi, đây là mảng đơn giản
            if (is_int($key) && is_string($value)) {
                if (isset($this->relations[$value])) {
                    $relationsToLoad[$value] = $this->relations[$value];
                }
            } else {
                // Nếu key là chuỗi, đây là mảng kết hợp đã có cấu hình
                $relationsToLoad[$key] = $value;
            }
        }
        
        // Lưu lại danh sách quan hệ cần tải mà không ghi đè toàn bộ $this->relations
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
        // Tìm bản ghi
        $data = $this->find($id);
        if (!$data) {
            return null;
        }
        
        // Xử lý deleted_at nếu là null hoặc chuỗi rỗng
        if (property_exists($data, 'deleted_at') && ($data->deleted_at === '' || $data->deleted_at === '0000-00-00 00:00:00')) {
            $data->deleted_at = null;
        }
        
        // Đã loại bỏ validation để tránh lỗi với trường deleted_at
        
        // Xác định quan hệ cần tải
        $relationsToLoad = [];
        
        // Nếu $relations là true, tải tất cả các mối quan hệ
        if ($relations === true) {
            $relationsToLoad = array_keys($this->relations);
        } 
        // Nếu $relations là mảng, tải các mối quan hệ được chỉ định
        elseif (is_array($relations) && !empty($relations)) {
            $relationsToLoad = $relations;
        }
        // Nếu không có quan hệ nào được chỉ định, sử dụng quan hệ hiện tại
        else {
            $relationsToLoad = array_keys($this->relations);
        }
        
        // Tải các mối quan hệ
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
        // Kiểm tra nếu $config là chuỗi thì lấy cấu hình từ $this->relations
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
        
        // Nếu entity class là BaseEntity (lớp trừu tượng), đặt thành null
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
                // Sử dụng khóa ngoại thay vì cố định 'id'
                $foreignPrimaryKey = isset($config['foreignPrimaryKey']) ? $config['foreignPrimaryKey'] : 'id';
                $result = $query->where($foreignPrimaryKey, $data->$foreignKey)
                               ->get()
                               ->getRow();
                
                // Kiểm tra entity class có được chỉ định hay không và có thể khởi tạo được không
                if ($result) {
                    if ($entityClass && class_exists($entityClass)) {
                        try {
                            return new $entityClass((array) $result);
                        } catch (\Exception $e) {
                            // Nếu có lỗi khi tạo đối tượng, trả về kết quả trực tiếp
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
        
        // Nếu có relationsToLoad, tải các quan hệ cho kết quả
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
        
        // Kiểm tra xem có mối quan hệ nào cần tải không
        $relationsToLoad = !empty($this->relationsToLoad) ? $this->relationsToLoad : $this->relations;
        if (empty($relationsToLoad)) {
            return $results;
        }
        
        // Tải quan hệ cho từng mục trong kết quả
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
    public function getActive()
    {
        return $this->where('status', 1)->where('bin', 0)->findAll();
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
        // Lấy kết quả gốc từ lớp cha
        $results = parent::findAll($limit, $offset);
        
        // Sử dụng phương thức chung để tải quan hệ
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
        // Lấy kết quả phân trang gốc từ lớp cha
        $results = parent::paginate($perPage, $group, $page, $segment);
        
        // Sử dụng phương thức chung để tải quan hệ
        return $this->loadRelationsForResults($results);
    }
}