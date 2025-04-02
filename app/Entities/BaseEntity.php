<?php
namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Config\Database;
use CodeIgniter\I18n\Time;

abstract class BaseEntity extends Entity
{
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $tableName = '';
    protected $errors = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at']; // Hỗ trợ timestamps
    protected $casts = [];
    protected $datamap = [];
    protected $jsonFields = [];
    protected $hiddenFields = [];
    protected $fillable = [];
    protected $guarded = [];
    protected $original = [];
    protected $changed = [];
    protected $tempData = [];
    protected $tempDataRemoved = [];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $relations = [];
    protected $beforeSpaceRemoval = [];
    protected $concatFields = [];

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        if (!empty($this->tableName)) {
            $this->generateValidationRules();
        }
        
        // Initialize JSON fields
        foreach ($this->jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $this->attributes[$field] = json_decode($data[$field], true);
            }
        }

        // Lưu dữ liệu gốc
        $this->original = $this->attributes;
    }

    protected function generateValidationRules()
    {
        if (empty($this->tableName)) {
            return;
        }

        $db = Database::connect();
        $fields = $db->getFieldData($this->tableName);

        foreach ($fields as $field) {
            $rule = $this->mapFieldToRule($field);
            if ($rule) {
                $this->validationRules[$field->name] = $rule;
            }
        }
    }

    protected function mapFieldToRule($field)
    {
        $rules = [];

        if (!$field->nullable && $field->default === null && !in_array($field->name, ['created_at', 'updated_at', 'deleted_at'])) {
            $rules[] = 'required';
        }

        switch (strtolower($field->type)) {
            case 'varchar':
            case 'text':
                $rules[] = 'string';
                if ($field->max_length) {
                    $rules[] = "max_length[{$field->max_length}]";
                }
                break;
            case 'int':
            case 'integer':
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'bigint':
                $rules[] = 'integer';
                break;
            case 'float':
            case 'double':
            case 'decimal':
                $rules[] = 'numeric';
                break;
            case 'datetime':
            case 'timestamp':
                $rules[] = 'valid_date[Y-m-d H:i:s]';
                break;
            case 'date':
                $rules[] = 'valid_date[Y-m-d]';
                break;
            case 'time':
                $rules[] = 'valid_date[H:i:s]';
                break;
            case 'year':
                $rules[] = 'valid_date[Y]';
                break;
            case 'enum':
                if (!empty($field->values)) {
                    $rules[] = 'in_list[' . implode(',', $field->values) . ']';
                }
                break;
            case 'json':
                $rules[] = 'valid_json';
                break;
            case 'email':
                $rules[] = 'valid_email';
                break;
        }

        return implode('|', $rules);
    }

    public function validate(array $data = null): bool
    {
        $validation = \Config\Services::validation();
        $data = $data ?? $this->toArray();
        
        // Remove hidden fields from validation
        foreach ($this->hiddenFields as $field) {
            unset($data[$field]);
        }
        
        $validation->setRules($this->validationRules, $this->validationMessages);

        if (!$validation->run($data)) {
            $this->errors = $validation->getErrors();
            return false;
        }

        $this->errors = [];
        return true;
    }

    public function getErrors(): array
    {
        return $this->errors ?? [];
    }

    // JSON field handling
    protected function castAsJson($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    protected function castToJson($value)
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }
        return $value;
    }

    // Date handling
    protected function mutateDate($value)
    {
        if ($value instanceof Time) {
            return $value;
        }

        if (is_numeric($value)) {
            return Time::createFromTimestamp($value);
        }

        if (is_string($value)) {
            return new Time($value);
        }

        return $value;
    }

    // Array access methods
    public function toArray(bool $onlyChanged = false, bool $cast = true, bool $recursive = false): array
    {
        $data = parent::toArray($onlyChanged, $cast, $recursive);
        
        // Remove hidden fields
        foreach ($this->hiddenFields as $field) {
            unset($data[$field]);
        }
        
        // Handle JSON fields
        foreach ($this->jsonFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->castToJson($data[$field]);
            }
        }
        
        return $data;
    }

    // Utility methods
    public function hasAttribute(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    public function isEmpty(string $key): bool
    {
        return empty($this->attributes[$key]);
    }

    public function fill(?array $data = null)
    {
        if ($data === null) {
            return $this;
        }
        
        foreach ($data as $key => $value) {
            if ($this->hasAttribute($key)) {
                $this->attributes[$key] = $value;
            }
        }
        return $this;
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->toArray(), array_flip($keys));
    }

    public function except(array $keys): array
    {
        return array_diff_key($this->toArray(), array_flip($keys));
    }

    public function isDirty(string $key = null): bool
    {
        if ($key === null) {
            return !empty($this->changed);
        }
        return isset($this->changed[$key]);
    }

    public function getDirty(): array
    {
        return $this->changed;
    }

    public function getOriginal(string $key = null)
    {
        if ($key === null) {
            return $this->original;
        }
        return $this->original[$key] ?? null;
    }

    public function getChanges(): array
    {
        return array_intersect_key($this->attributes, $this->changed);
    }

    public function reset(): self
    {
        $this->attributes = $this->original;
        $this->changed = [];
        $this->tempData = [];
        $this->tempDataRemoved = [];
        return $this;
    }

    /**
     * Custom validation rule để kiểm tra ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu
     * 
     * @param string $value Giá trị của trường đang validation (ngày kết thúc)
     * @param string $startDateField Tên trường ngày bắt đầu
     * @param array $data Tất cả dữ liệu đang được validation
     * @param string $error Biến tham chiếu để trả về lỗi
     */
    public function validateDates(string $value, string $startDateField, array $data, &$error)
    {
        if (empty($value) || empty($data[$startDateField])) {
            return true;
        }
        
        $startDate = strtotime($data[$startDateField]);
        $endDate = strtotime($value);
        
        if ($startDate > $endDate) {
            $error = 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu';
            return false;
        }
        
        return true;
    }

    // Các phương thức mới cho xử lý dữ liệu
    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getFillable(): array
    {
        return $this->fillable;
    }

    public function getGuarded(): array
    {
        return $this->guarded;
    }

    public function getJsonFields(): array
    {
        return $this->jsonFields;
    }

    public function getHiddenFields(): array
    {
        return $this->hiddenFields;
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }

    public function isTimestampEnabled(): bool
    {
        return $this->useTimestamps;
    }

    public function isSoftDeleteEnabled(): bool
    {
        return $this->useSoftDeletes;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

    public function getBeforeSpaceRemoval(): array
    {
        return $this->beforeSpaceRemoval;
    }

    public function getConcatFields(): array
    {
        return $this->concatFields;
    }

    public function setTableName(string $name): self
    {
        $this->tableName = $name;
        return $this;
    }

    public function setFillable(array $fields): self
    {
        $this->fillable = $fields;
        return $this;
    }

    public function setGuarded(array $fields): self
    {
        $this->guarded = $fields;
        return $this;
    }

    public function setJsonFields(array $fields): self
    {
        $this->jsonFields = $fields;
        return $this;
    }

    public function setHiddenFields(array $fields): self
    {
        $this->hiddenFields = $fields;
        return $this;
    }

    public function setValidationRules(array $rules): self
    {
        $this->validationRules = $rules;
        return $this;
    }

    public function setValidationMessages(array $messages): self
    {
        $this->validationMessages = $messages;
        return $this;
    }

    public function setUseTimestamps(bool $enabled): self
    {
        $this->useTimestamps = $enabled;
        return $this;
    }

    public function setUseSoftDeletes(bool $enabled): self
    {
        $this->useSoftDeletes = $enabled;
        return $this;
    }

    public function setRelations(array $relations): self
    {
        $this->relations = $relations;
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
    public function getCreatedAtFormatted(): string
    {
        return $this->created_at === null ? '---' : $this->created_at->format('d/m/Y H:i:s');
    }
    public function getUpdatedAtFormatted(): string
    {
        return $this->updated_at === null ? '---' : $this->updated_at->format('d/m/Y H:i:s');
    }
    public function getDeletedAtFormatted(): string
    {   
        return $this->deleted_at === null ? '---' : $this->deleted_at->format('d/m/Y H:i:s');    
    }
    
    public function getDateTimeFormatted(): string
    {
        return $this->date_time === null ? '---' : $this->date_time->format('d/m/Y H:i:s');
    }
}