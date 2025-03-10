<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Config\Database;

abstract class BaseEntity extends Entity
{
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $tableName = '';

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->generateValidationRules();
    }

    protected function generateValidationRules()
    {
        if (empty($this->tableName)) {
            throw new \RuntimeException('Table name must be defined in child entity.');
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
        $rule = [];

        // Kiểm tra NOT NULL
        if (!$field->nullable) {
            $rule[] = 'required';
        }

        // Ánh xạ kiểu dữ liệu
        switch (strtolower($field->type)) {
            case 'varchar':
            case 'char':
            case 'text':
                $rule[] = 'string';
                if ($field->max_length) {
                    $rule[] = "max_length[{$field->max_length}]";
                }
                break;

            case 'int':
            case 'integer':
            case 'bigint':
                $rule[] = 'integer';
                if ($field->type === 'int') {
                    $rule[] = 'less_than_equal_to[2147483647]';
                }
                break;

            case 'tinyint':
                $rule[] = 'boolean';
                break;

            case 'float':
            case 'double':
            case 'decimal':
                $rule[] = 'numeric';
                break;

            case 'datetime':
            case 'timestamp':
                $rule[] = 'valid_date[Y-m-d H:i:s]';
                break;

            case 'date':
                $rule[] = 'valid_date[Y-m-d]';
                break;

            case 'time':
                $rule[] = 'valid_date[H:i:s]';
                break;

            case 'email':
                $rule[] = 'valid_email';
                break;

            case 'uuid':
                $rule[] = 'regex_match[/^[a-f0-9]{8}-[a-f0-9]{4}-[1-5][a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/i]';
                break;

            default:
                break;
        }

        // Nếu nullable và có default, thêm if_exist
        if ($field->nullable && $field->default !== null) {
            $rule[] = 'if_exist';
        }

        return implode('|', $rule);
    }

    public function validate(array $data = null): bool
    {
        $validation = \Config\Services::validation();
        $data = $data ?? $this->attributes;
        $validation->setRules($this->validationRules, $this->validationMessages);

        if (!$validation->run($data)) {
            $this->errors = $validation->getErrors();
            return false;
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors ?? [];
    }
}
