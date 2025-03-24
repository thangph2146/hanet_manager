<?php

namespace App\Modules\diengia\Models;

use App\Modules\diengia\Entities\DienGia;
use \App\Models\BaseModel;

class DienGiaModel extends BaseModel
{
    protected $table = 'dien_gia';
    protected $primaryKey = 'dien_gia_id';
    protected $allowedFields = [
        'ten_dien_gia',
        'chuc_danh', // Chuỗi tự nhập
        'to_chuc',
        'gioi_thieu',
        'avatar',
        'thu_tu',
        'bin',
    ];
    protected $returnType = DienGia::class;

    protected $searchableFields = [
        'ten_dien_gia',
        'to_chuc',
        'gioi_thieu',
    ];
    protected $filterableFields = [
        'chuc_danh',
        'bin',
        'thu_tu',
    ];

    /**
     * Lấy danh sách diễn giả theo thứ tự
     *
     * @param int $limit Giới hạn số lượng
     * @return array Danh sách diễn giả
     */
    public function getOrderedDiengias($limit = 0)
    {
        $builder = $this->where('bin', 0);
        
        if ($limit > 0) {
            $builder->limit($limit);
        }
        
        return $builder->orderBy('thu_tu', 'ASC')
                      ->orderBy('ten_dien_gia', 'ASC')
                      ->findAll();
    }

    /**
     * Lấy diễn giả theo tên và tổ chức
     *
     * @param string $name Tên diễn giả
     * @param string $organization Tổ chức
     * @return array Danh sách diễn giả
     */
    public function findByNameAndOrganization($name, $organization = null)
    {
        $builder = $this->like('ten_dien_gia', $name)
                       ->where('bin', 0);
        
        if ($organization) {
            $builder->like('to_chuc', $organization);
        }
        
        return $builder->findAll();
    }

    /**
     * Kiểm tra xem tên diễn giả đã tồn tại hay chưa
     *
     * @param string $name Tên diễn giả
     * @param int|null $id ID diễn giả để loại trừ khi kiểm tra (dùng cho cập nhật)
     * @return bool True nếu tên đã tồn tại
     */
    public function isNameExists($name, $id = null)
    {
        $builder = $this->where('ten_dien_gia', $name)
                       ->where('bin', 0);
        
        if ($id) {
            $builder->where("{$this->primaryKey} !=", $id);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Chuyển diễn giả vào thùng rác
     *
     * @param int $id ID diễn giả
     * @return bool
     */
    public function moveToRecycleBin($id)
    {
        return $this->update($id, [
            'bin' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Khôi phục diễn giả từ thùng rác
     *
     * @param int $id ID diễn giả
     * @return bool
     */
    public function restoreFromRecycleBin($id)
    {
        return $this->update($id, [
            'bin' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Lấy diễn giả theo chức danh
     *
     * @param string $chucDanh Chức danh
     * @return array Danh sách diễn giả
     */
    public function findByChucDanh($chucDanh)
    {
        return $this->where('chuc_danh', $chucDanh)
                   ->where('bin', 0)
                   ->orderBy('thu_tu', 'ASC')
                   ->orderBy('ten_dien_gia', 'ASC')
                   ->findAll();
    }

    /**
     * Cập nhật thứ tự cho diễn giả
     *
     * @param int $id ID diễn giả
     * @param int $order Thứ tự mới
     * @return bool
     */
    public function updateOrder($id, $order)
    {
        return $this->update($id, [
            'thu_tu' => $order,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}