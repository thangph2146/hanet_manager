<?php

namespace App\Modules\formdangkysukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class FormDangKySuKienSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách sự kiện từ bảng su_kien (giả định có ít nhất 3 sự kiện)
        $suKienList = $this->db->table('su_kien')->limit(3)->get()->getResultArray();
        
        if (empty($suKienList)) {
            echo "Cần phải có dữ liệu trong bảng su_kien trước khi chạy seeder này.\n";
            return;
        }
        
        $data = [];
        $now = Time::now();
        
        // Tạo dữ liệu mẫu cho các form đăng ký sự kiện
        foreach ($suKienList as $suKienIndex => $suKien) {
            $suKienId = $suKien['su_kien_id'];
            
            // Tạo 2 form đăng ký cho mỗi sự kiện
            for ($i = 1; $i <= 2; $i++) {
                $data[] = [
                    'ten_form' => 'Form đăng ký ' . ($i == 1 ? 'thông tin cơ bản' : 'thông tin nâng cao') . ' - ' . $suKien['ten_su_kien'],
                    'mo_ta' => 'Đây là form ' . ($i == 1 ? 'thu thập thông tin cơ bản' : 'thu thập thông tin chi tiết') . ' của người tham gia sự kiện ' . $suKien['ten_su_kien'],
                    'su_kien_id' => $suKienId,
                    'cau_truc_form' => json_encode($this->generateFormStructure($i)),
                    'hien_thi_cong_khai' => true,
                    'bat_buoc_dien' => $i == 1 ? true : false, // Form cơ bản bắt buộc điền
                    'so_lan_su_dung' => rand(0, 100),
                    'status' => 1,
                    'created_at' => $now->toDateTimeString(),
                    'updated_at' => $now->toDateTimeString()
                ];
            }
        }
        
        // Thêm dữ liệu vào bảng form_dang_ky_su_kien
        if (!empty($data)) {
            // Xóa dữ liệu cũ (nếu có)
            $this->db->table('form_dang_ky_su_kien')->emptyTable();
            
            // Thêm dữ liệu mới
            $this->db->table('form_dang_ky_su_kien')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi form đăng ký sự kiện.\n";
        }
        
        echo "Seeder FormDangKySuKienSeeder đã được chạy thành công!\n";
    }
    
    /**
     * Tạo cấu trúc form đăng ký mẫu
     * 
     * @param int $formType Loại form (1: cơ bản, 2: nâng cao)
     * @return array
     */
    private function generateFormStructure($formType)
    {
        $basicFields = [
            [
                'id' => 'ho_ten',
                'label' => 'Họ và tên',
                'type' => 'text',
                'required' => true,
                'placeholder' => 'Nhập họ và tên đầy đủ',
                'validation' => [
                    'min_length' => 2,
                    'max_length' => 100
                ]
            ],
            [
                'id' => 'email',
                'label' => 'Địa chỉ Email',
                'type' => 'email',
                'required' => true,
                'placeholder' => 'Nhập địa chỉ email',
                'validation' => [
                    'email' => true
                ]
            ],
            [
                'id' => 'dien_thoai',
                'label' => 'Số điện thoại',
                'type' => 'tel',
                'required' => true,
                'placeholder' => 'Nhập số điện thoại',
                'validation' => [
                    'pattern' => '^[0-9]{10}$'
                ]
            ],
            [
                'id' => 'don_vi',
                'label' => 'Đơn vị/Tổ chức',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Nhập tên đơn vị hoặc tổ chức'
            ]
        ];
        
        // Nếu là form cơ bản, chỉ trả về các trường cơ bản
        if ($formType == 1) {
            return [
                'title' => 'Form đăng ký thông tin cơ bản',
                'fields' => $basicFields
            ];
        }
        
        // Nếu là form nâng cao, thêm các trường chi tiết
        $advancedFields = array_merge($basicFields, [
            [
                'id' => 'chuc_vu',
                'label' => 'Chức vụ',
                'type' => 'text',
                'required' => false,
                'placeholder' => 'Nhập chức vụ hiện tại'
            ],
            [
                'id' => 'gioi_tinh',
                'label' => 'Giới tính',
                'type' => 'select',
                'required' => true,
                'options' => [
                    ['value' => 'nam', 'label' => 'Nam'],
                    ['value' => 'nu', 'label' => 'Nữ'],
                    ['value' => 'khac', 'label' => 'Khác']
                ]
            ],
            [
                'id' => 'ngay_sinh',
                'label' => 'Ngày sinh',
                'type' => 'date',
                'required' => false,
                'placeholder' => 'Chọn ngày sinh'
            ],
            [
                'id' => 'dia_chi',
                'label' => 'Địa chỉ',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'Nhập địa chỉ đầy đủ'
            ],
            [
                'id' => 'ly_do_tham_gia',
                'label' => 'Lý do tham gia sự kiện',
                'type' => 'textarea',
                'required' => true,
                'placeholder' => 'Nhập lý do tham gia sự kiện',
                'validation' => [
                    'min_length' => 10,
                    'max_length' => 500
                ]
            ],
            [
                'id' => 'nguon_thong_tin',
                'label' => 'Bạn biết sự kiện từ đâu?',
                'type' => 'checkbox',
                'required' => false,
                'options' => [
                    ['value' => 'mxh', 'label' => 'Mạng xã hội'],
                    ['value' => 'ban_be', 'label' => 'Bạn bè/Đồng nghiệp'],
                    ['value' => 'bao_chi', 'label' => 'Báo chí/Truyền thông'],
                    ['value' => 'khac', 'label' => 'Nguồn khác']
                ]
            ],
            [
                'id' => 'nhu_cau_ho_tro',
                'label' => 'Bạn cần hỗ trợ gì đặc biệt không?',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => 'Ví dụ: Hỗ trợ đi lại, ăn uống, chỗ ở...'
            ]
        ]);
        
        return [
            'title' => 'Form đăng ký thông tin chi tiết',
            'fields' => $advancedFields
        ];
    }
} 