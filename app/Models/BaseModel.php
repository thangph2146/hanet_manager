<?php
namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class BaseModel extends Model
{
    protected $returnType = 'App\Entities\BaseEntity';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $relations = [];
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
        $this->relations = $relations;
        return $this;
    }

    public function findWithRelations($id, $validate = false)
    {
        $data = $this->find($id);
        if (!$data) {
            return null;
        }

        if ($validate && !$data->validate()) {
            throw new \RuntimeException('Entity validation failed: ' . json_encode($data->getErrors()));
        }

        foreach ($this->relations as $relation => $config) {
            $data->$relation = $this->fetchRelation($relation, $config, $data);
        }

        return $data;
    }

    protected function fetchRelation($relationName, $config, $data)
    {
        $type = $config['type'] ?? '1-1';
        $foreignTable = $config['table'];
        $foreignKey = $config['foreignKey'];
        $localKey = $config['localKey'] ?? $this->primaryKey;
        $entityClass = $config['entity'] ?? 'App\Entities\BaseEntity';
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
                $result = $query->where($foreignKey, $data->get($localKey))
                               ->get()
                               ->getRow();
                return $result ? new $entityClass((array) $result) : null;

            case '1-n':
                $results = $query->where($foreignKey, $data->get($localKey))
                                ->get()
                                ->getResult();
                return array_map(fn($row) => new $entityClass((array) $row), $results);

            case 'n-n':
                $pivotTable = $config['pivotTable'];
                $pivotLocalKey = $config['pivotLocalKey'];
                $pivotForeignKey = $config['pivotForeignKey'];

                $results = $query->join($pivotTable, "$pivotTable.$pivotForeignKey = $foreignTable.id")
                                ->where("$pivotTable.$pivotLocalKey", $data->get($localKey))
                                ->get()
                                ->getResult();
                return array_map(fn($row) => new $entityClass((array) $row), $results);

            case 'n-1':
                $result = $query->where('id', $data->get($foreignKey))
                               ->get()
                               ->getRow();
                return $result ? new $entityClass((array) $result) : null;

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
        
        return $builder->get()->getResult($this->returnType);
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
}