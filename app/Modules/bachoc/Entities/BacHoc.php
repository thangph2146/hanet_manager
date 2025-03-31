<?php

namespace App\Modules\bachoc\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class BacHoc extends BaseEntity
{
    protected $tableName = 'bac_hoc';
    protected $primaryKey = 'bac_hoc_id'; // Mặc định Entity dùng `id`
    
    // Kế thừa $casts từ BaseEntity, chỉ định rõ các kiểu dữ liệu nếu cần
    protected $casts = [
        'bac_hoc_id' => 'int',
        'status' => 'int', 
    ];
    
    
    // Các quy tắc xác thực cụ thể cho BacHoc
    // BaseEntity có thể tự động tạo rules cơ bản, nhưng ta định nghĩa lại để rõ ràng và tùy chỉnh
    protected $validationRules = [
        'ten_bac_hoc' => [
            'rules' => 'required|max_length[100]|is_unique[bac_hoc.ten_bac_hoc,bac_hoc_id,{bac_hoc_id}]', // Placeholder {bac_hoc_id} sẽ được thay thế trong Model
            'label' => 'Tên bậc học'
        ],
        'ma_bac_hoc' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Mã bậc học'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]', // Sử dụng common rule 'status' từ BaseModel nếu có
            'label' => 'Trạng thái'
        ]
    ];
    
    // Kế thừa messages từ BaseEntity nếu có, hoặc định nghĩa lại
    protected $validationMessages = [
        'ten_bac_hoc' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'ma_bac_hoc' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của bậc học
     * Sử dụng getter mặc định của Entity hoặc định nghĩa lại nếu cần logic đặc biệt.
     *
     * @return int
     */
    public function getId(): int
    {
        // Entity tự động tạo getter cho các thuộc tính, ví dụ $this->bac_hoc_id
        // Nếu tên thuộc tính khác `id`, cần định nghĩa getter tường minh
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Kiểm tra trạng thái hoạt động
     * Giữ lại vì cung cấp logic rõ ràng hơn là chỉ trả về giá trị.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        // Ép kiểu bool rõ ràng
        return (bool)($this->attributes['status'] ?? false); 
    }
    
    
    /**
     * Kiểm tra xem bản ghi đã bị xóa chưa (soft delete)
     * Giữ lại vì logic kiểm tra deleted_at.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        // Kiểm tra xem deleted_at có giá trị khác null và không rỗng không
        return !empty($this->attributes['deleted_at']); 
    }
    
    /**
     * Lấy nhãn trạng thái hiển thị
     * Đây là logic hiển thị, có thể giữ lại trong Entity hoặc chuyển sang Helper/View.
     *
     * @return string HTML với badge status
     */
    public function getStatusLabel(): string
    {
        // Sử dụng toán tử tam ngôi cho gọn
        return $this->isActive() 
            ? '<span class="badge bg-success">Hoạt động</span>' 
            : '<span class="badge bg-danger">Không hoạt động</span>';
    }
    
    
    /**
     * Lấy ngày tạo đã định dạng (Ví dụ giữ lại)
     * 
     * @param string $format Định dạng mong muốn (mặc định 'Y-m-d H:i:s')
     * @return string
     */
    public function getCreatedAtFormatted(string $format = 'Y-m-d H:i:s'): string
    {
        // Sử dụng getter mặc định của Entity ($this->created_at)
        // BaseEntity đã cast thành đối tượng Time
        if (empty($this->created_at)) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        // Đảm bảo $this->created_at là đối tượng Time
        return ($this->created_at instanceof Time) ? $this->created_at->format($format) : '-';
    }
    
    /**
     * Lấy ngày cập nhật đã định dạng
     * 
     * @param string $format Định dạng mong muốn
     * @return string
     */
    public function getUpdatedAtFormatted(string $format = 'Y-m-d H:i:s'): string
    {
        if (empty($this->updated_at)) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        return ($this->updated_at instanceof Time) ? $this->updated_at->format($format) : '-';
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     * 
     * @param string $format Định dạng mong muốn
     * @return string
     */
    public function getDeletedAtFormatted(string $format = 'Y-m-d H:i:s'): string
    {
        if (empty($this->deleted_at)) {
            return '<span class="text-muted fst-italic">Chưa xóa</span>';
        }
        return ($this->deleted_at instanceof Time) ? $this->deleted_at->format($format) : '-';
    }
    
}
