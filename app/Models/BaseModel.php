<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $returnType = 'App\Entities\BaseEntity';
    protected $useTimestamps = true; // Bật timestamp
    protected $useSoftDeletes = true; // Bật soft delete
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $showExecutionTime = false; // Biến tùy chỉnh để hiển thị thời gian xử lý

    // Insert và trả về ID
    public function insertWithId($data, bool $returnId = true)
    {
        $startTime = microtime(true);
        $result = $this->insert($data, $returnId);
        $this->showTime($startTime);
        return $result;
    }

    // Insert không trả về ID
    public function insertWithoutId($data)
    {
        return $this->insertWithId($data, false);
    }

    // Cập nhật dữ liệu
    public function updateRecord($id, $data)
    {
        $startTime = microtime(true);
        $result = $this->update($id, $data);
        $this->showTime($startTime);
        return $result;
    }

    // Xóa cứng dữ liệu
    public function deleteRecord($id)
    {
        $startTime = microtime(true);
        $result = $this->delete($id, true);
        $this->showTime($startTime);
        return $result;
    }

    // Soft Delete
    public function softDeleteRecord($id)
    {
        $startTime = microtime(true);
        $result = $this->delete($id);
        $this->showTime($startTime);
        return $result;
    }

    // Lấy tất cả bản ghi
    public function getAll(bool $includeDeleted = false)
    {
        $startTime = microtime(true);
        $query = $includeDeleted ? $this->withDeleted() : $this;
        $result = $query->findAll();
        $this->showTime($startTime);
        return $result;
    }

    // Lấy bản ghi theo ID
    public function getById($id, bool $includeDeleted = false)
    {
        $startTime = microtime(true);
        $query = $includeDeleted ? $this->withDeleted() : $this;
        $result = $query->find($id);
        $this->showTime($startTime);
        return $result;
    }

    // Lấy bản ghi theo điều kiện
    public function getByCondition(array $conditions, bool $includeDeleted = false)
    {
        $startTime = microtime(true);
        $query = $includeDeleted ? $this->withDeleted() : $this;

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        $result = $query->findAll();
        $this->showTime($startTime);
        return $result;
    }

    // Lấy ID của bản ghi cuối cùng
    public function getLastInsertedId()
    {
        return $this->insertID();
    }

    // Chèn nhiều dòng dữ liệu
    public function bulkInsert(array $data)
    {
        return $this->insertBatch($data);
    }

    // Cập nhật nhiều dòng dữ liệu
    public function bulkUpdate(array $data, string $primaryKey = 'id')
    {
        return $this->updateBatch($data, $primaryKey);
    }

    // Hiển thị thời gian xử lý
    protected function showTime($startTime)
    {
        if ($this->showExecutionTime) {
            echo "Thời gian xử lý: " . (microtime(true) - $startTime) . " giây\n";
        }
    }
}
