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

    /**
     * Lấy giá trị của trường theo tên
     * 
     * @param string $field Tên trường
     * @param mixed $default Giá trị mặc định nếu không tìm thấy
     * @return mixed
     */
    public function getField(string $field, $default = null)
    {
        return $this->attributes[$field] ?? $default;
    }

    /**
     * Lấy giá trị của nhiều trường theo tên
     * 
     * @param array $fields Danh sách tên trường
     * @return array
     */
    public function getFields(array $fields): array
    {
        $result = [];
        foreach ($fields as $field) {
            $result[$field] = $this->getField($field);
        }
        return $result;
    }

    /**
     * Lấy giá trị của trường theo tên và chuyển đổi kiểu dữ liệu
     * 
     * @param string $field Tên trường
     * @param string $type Kiểu dữ liệu cần chuyển đổi
     * @param mixed $default Giá trị mặc định nếu không tìm thấy
     * @return mixed
     */
    public function getFieldAs(string $field, string $type, $default = null)
    {
        $value = $this->getField($field, $default);
        
        switch ($type) {
            case 'int':
            case 'integer':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'string':
                return (string)$value;
            case 'bool':
            case 'boolean':
                return (bool)$value;
            case 'array':
                return is_array($value) ? $value : [];
            case 'object':
                return is_object($value) ? $value : null;
            case 'json':
                return $this->castAsJson($value);
            case 'date':
                return $this->mutateDate($value);
            default:
                return $value;
        }
    }

    /**
     * Lấy giá trị của nhiều trường theo tên và chuyển đổi kiểu dữ liệu
     * 
     * @param array $fields Danh sách tên trường và kiểu dữ liệu
     * @return array
     */
    public function getFieldsAs(array $fields): array
    {
        $result = [];
        foreach ($fields as $field => $type) {
            $result[$field] = $this->getFieldAs($field, $type);
        }
        return $result;
    }

    /**
     * Lấy giá trị của trường theo tên và định dạng
     * 
     * @param string $field Tên trường
     * @param string $format Định dạng cần chuyển đổi
     * @param mixed $default Giá trị mặc định nếu không tìm thấy
     * @return mixed
     */
    public function getFieldFormatted(string $field, string $format, $default = null)
    {
        $value = $this->getField($field, $default);
        
        switch ($format) {
            case 'currency':
                return number_format($value, 0, ',', '.');
            case 'date':
                return date('d/m/Y', strtotime($value));
            case 'datetime':
                return date('d/m/Y H:i:s', strtotime($value));
            case 'time':
                return date('H:i:s', strtotime($value));
            case 'phone':
                return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $value);
            case 'email':
                return strtolower($value);
            case 'url':
                return strtolower($value);
            case 'slug':
                return url_title($value, '-', true);
            case 'title':
                return ucwords(strtolower($value));
            case 'upper':
                return strtoupper($value);
            case 'lower':
                return strtolower($value);
            default:
                return $value;
        }
    }

    /**
     * Lấy giá trị của nhiều trường theo tên và định dạng
     * 
     * @param array $fields Danh sách tên trường và định dạng
     * @return array
     */
    public function getFieldsFormatted(array $fields): array
    {
        $result = [];
        foreach ($fields as $field => $format) {
            $result[$field] = $this->getFieldFormatted($field, $format);
        }
        return $result;
    }

    /**
     * Lấy giá trị của trường theo tên và xử lý trước khi trả về
     * 
     * @param string $field Tên trường
     * @param callable $callback Hàm xử lý giá trị
     * @param mixed $default Giá trị mặc định nếu không tìm thấy
     * @return mixed
     */
    public function getFieldWithCallback(string $field, callable $callback, $default = null)
    {
        $value = $this->getField($field, $default);
        return $callback($value);
    }

    /**
     * Lấy giá trị của nhiều trường theo tên và xử lý trước khi trả về
     * 
     * @param array $fields Danh sách tên trường và hàm xử lý
     * @return array
     */
    public function getFieldsWithCallback(array $fields): array
    {
        $result = [];
        foreach ($fields as $field => $callback) {
            $result[$field] = $this->getFieldWithCallback($field, $callback);
        }
        return $result;
    }

    /**
     * Lấy giá trị của trường theo tên và xử lý trước khi trả về
     * 
     * @param string $field Tên trường
     * @param array $options Tùy chọn xử lý
     * @param mixed $default Giá trị mặc định nếu không tìm thấy
     * @return mixed
     */
    public function getFieldWithOptions(string $field, array $options = [], $default = null)
    {
        $value = $this->getField($field, $default);
        
        // Xử lý các tùy chọn
        if (!empty($options['type'])) {
            $value = $this->getFieldAs($field, $options['type'], $default);
        }
        
        if (!empty($options['format'])) {
            $value = $this->getFieldFormatted($field, $options['format']);
        }
        
        if (!empty($options['callback']) && is_callable($options['callback'])) {
            $value = $options['callback']($value);
        }
        
        return $value;
    }

    /**
     * Lấy giá trị của nhiều trường theo tên và xử lý trước khi trả về
     * 
     * @param array $fields Danh sách tên trường và tùy chọn xử lý
     * @return array
     */
    public function getFieldsWithOptions(array $fields): array
    {
        $result = [];
        foreach ($fields as $field => $options) {
            $result[$field] = $this->getFieldWithOptions($field, $options);
        }
        return $result;
    }

    /**
     * Lấy giá trị của trường theo tên và xử lý trước khi trả về
     * 
     * @param string $field Tên trường
     * @param array $options Tùy chọn xử lý
     * @param mixed $default Giá trị mặc định nếu không tìm thấy
     * @return mixed
     */
    public function getFieldWithValidation(string $field, array $options = [], $default = null)
    {
        $value = $this->getField($field, $default);
        
        // Xử lý các tùy chọn
        if (!empty($options['type'])) {
            $value = $this->getFieldAs($field, $options['type'], $default);
        }
        
        if (!empty($options['format'])) {
            $value = $this->getFieldFormatted($field, $options['format']);
        }
        
        if (!empty($options['callback']) && is_callable($options['callback'])) {
            $value = $options['callback']($value);
        }
        
        // Xử lý validation
        if (!empty($options['validation'])) {
            $validation = \Config\Services::validation();
            $validation->setRule($field, $options['validation']['label'] ?? $field, $options['validation']['rules']);
            
            if (!$validation->run([$field => $value])) {
                $value = $default;
            }
        }
        
        return $value;
    }

    /**
     * Lấy giá trị của nhiều trường theo tên và xử lý trước khi trả về
     * 
     * @param array $fields Danh sách tên trường và tùy chọn xử lý
     * @return array
     */
    public function getFieldsWithValidation(array $fields): array
    {
        $result = [];
        foreach ($fields as $field => $options) {
            $result[$field] = $this->getFieldWithValidation($field, $options);
        }
        return $result;
    }
}