<?php

namespace App\Modules\sukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuKienSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho sự kiện
        $data = [
            [
                'ten_su_kien' => 'Hội thảo Tài chính - Ngân hàng 2025',
                'su_kien_poster' => json_encode([
                    'url' => 'https://example.com/poster/ht-tc-ng-2025.jpg',
                    'width' => 800,
                    'height' => 1200
                ]),
                'mo_ta' => 'Hội thảo về xu hướng mới trong lĩnh vực tài chính - ngân hàng năm 2025',
                'mo_ta_su_kien' => 'Hội thảo giới thiệu các xu hướng mới và cơ hội nghề nghiệp trong lĩnh vực tài chính - ngân hàng',
                'chi_tiet_su_kien' => 'Hội thảo sẽ có sự tham gia của các chuyên gia hàng đầu trong lĩnh vực tài chính - ngân hàng, các nhà quản lý từ các ngân hàng thương mại và các tổ chức tài chính',
                'thoi_gian_bat_dau' => '2025-05-15 08:00:00',
                'thoi_gian_ket_thuc' => '2025-05-15 17:00:00',
                'dia_diem' => 'Hội trường A - Trường Đại học Ngân hàng TP.HCM',
                'dia_chi_cu_the' => '36 Tôn Thất Đạm, Quận 1, TP.HCM',
                'toa_do_gps' => '10.7769,106.7009',
                'loai_su_kien_id' => 1,
                'nguoi_tao_id' => 1,
                'ma_qr_code' => 'HTTCNG2025',
                'status' => 1,
                'tong_dang_ky' => 0,
                'tong_check_in' => 0,
                'tong_check_out' => 0,
                'cho_phep_check_in' => true,
                'cho_phep_check_out' => true,
                'yeu_cau_face_id' => false,
                'cho_phep_checkin_thu_cong' => true,
                'tu_dong_xac_nhan_svgv' => true,
                'yeu_cau_duyet_khach' => true,
                'bat_dau_dang_ky' => '2025-05-01 08:00:00',
                'ket_thuc_dang_ky' => '2025-05-14 17:00:00',
                'han_huy_dang_ky' => '2025-05-14 23:59:59',
                'gio_bat_dau' => '2025-05-15 08:00:00',
                'gio_ket_thuc' => '2025-05-15 17:00:00',
                'so_luong_tham_gia' => 200,
                'so_luong_dien_gia' => 5,
                'gioi_han_loai_nguoi_dung' => 'sinh_vien,giang_vien',
                'tu_khoa_su_kien' => 'tài chính, ngân hàng, fintech, blockchain',
                'hashtag' => '#HTTCNG2025 #Finance #Banking',
                'slug' => 'hoi-thao-tai-chinh-ngan-hang-2025',
                'so_luot_xem' => 0,
                'lich_trinh' => json_encode([
                    [
                        'thoi_gian' => '08:00 - 08:30',
                        'noi_dung' => 'Đón tiếp đại biểu'
                    ],
                    [
                        'thoi_gian' => '08:30 - 09:00',
                        'noi_dung' => 'Khai mạc hội thảo'
                    ],
                    [
                        'thoi_gian' => '09:00 - 10:30',
                        'noi_dung' => 'Báo cáo chuyên đề 1: Xu hướng Fintech và ứng dụng trong ngân hàng'
                    ],
                    [
                        'thoi_gian' => '10:30 - 12:00',
                        'noi_dung' => 'Báo cáo chuyên đề 2: Blockchain và ứng dụng trong thanh toán'
                    ],
                    [
                        'thoi_gian' => '12:00 - 13:30',
                        'noi_dung' => 'Nghỉ trưa'
                    ],
                    [
                        'thoi_gian' => '13:30 - 15:00',
                        'noi_dung' => 'Báo cáo chuyên đề 3: Quản trị rủi ro trong ngân hàng'
                    ],
                    [
                        'thoi_gian' => '15:00 - 16:30',
                        'noi_dung' => 'Thảo luận và chia sẻ kinh nghiệm'
                    ],
                    [
                        'thoi_gian' => '16:30 - 17:00',
                        'noi_dung' => 'Bế mạc hội thảo'
                    ]
                ]),
                'version' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_su_kien' => 'Workshop Kỹ năng Phỏng vấn Ngân hàng 2025',
                'su_kien_poster' => json_encode([
                    'url' => 'https://example.com/poster/ws-pv-ng-2025.jpg',
                    'width' => 800,
                    'height' => 1200
                ]),
                'mo_ta' => 'Workshop trang bị kỹ năng phỏng vấn cho sinh viên ngành ngân hàng',
                'mo_ta_su_kien' => 'Workshop giới thiệu các kỹ năng cần thiết để vượt qua phỏng vấn tại các ngân hàng',
                'chi_tiet_su_kien' => 'Workshop sẽ có sự tham gia của các chuyên gia nhân sự từ các ngân hàng thương mại, chia sẻ kinh nghiệm và hướng dẫn kỹ năng phỏng vấn',
                'thoi_gian_bat_dau' => '2025-06-20 09:00:00',
                'thoi_gian_ket_thuc' => '2025-06-20 16:00:00',
                'dia_diem' => 'Phòng Hội thảo B - Trường Đại học Ngân hàng TP.HCM',
                'dia_chi_cu_the' => '36 Tôn Thất Đạm, Quận 1, TP.HCM',
                'toa_do_gps' => '10.7769,106.7009',
                'loai_su_kien_id' => 2,
                'nguoi_tao_id' => 1,
                'ma_qr_code' => 'WSPVNG2025',
                'status' => 1,
                'tong_dang_ky' => 0,
                'tong_check_in' => 0,
                'tong_check_out' => 0,
                'cho_phep_check_in' => true,
                'cho_phep_check_out' => true,
                'yeu_cau_face_id' => false,
                'cho_phep_checkin_thu_cong' => true,
                'tu_dong_xac_nhan_svgv' => true,
                'yeu_cau_duyet_khach' => true,
                'bat_dau_dang_ky' => '2025-06-01 08:00:00',
                'ket_thuc_dang_ky' => '2025-06-19 17:00:00',
                'han_huy_dang_ky' => '2025-06-19 23:59:59',
                'gio_bat_dau' => '2025-06-20 09:00:00',
                'gio_ket_thuc' => '2025-06-20 16:00:00',
                'so_luong_tham_gia' => 100,
                'so_luong_dien_gia' => 3,
                'gioi_han_loai_nguoi_dung' => 'sinh_vien',
                'tu_khoa_su_kien' => 'phỏng vấn, kỹ năng mềm, ngân hàng',
                'hashtag' => '#WSPVNG2025 #InterviewSkills',
                'slug' => 'workshop-ky-nang-phong-van-ngan-hang-2025',
                'so_luot_xem' => 0,
                'lich_trinh' => json_encode([
                    [
                        'thoi_gian' => '09:00 - 09:30',
                        'noi_dung' => 'Giới thiệu workshop'
                    ],
                    [
                        'thoi_gian' => '09:30 - 11:00',
                        'noi_dung' => 'Chia sẻ kinh nghiệm phỏng vấn từ chuyên gia HR'
                    ],
                    [
                        'thoi_gian' => '11:00 - 12:00',
                        'noi_dung' => 'Kỹ năng trả lời phỏng vấn'
                    ],
                    [
                        'thoi_gian' => '12:00 - 13:30',
                        'noi_dung' => 'Nghỉ trưa'
                    ],
                    [
                        'thoi_gian' => '13:30 - 15:00',
                        'noi_dung' => 'Thực hành phỏng vấn mô phỏng'
                    ],
                    [
                        'thoi_gian' => '15:00 - 16:00',
                        'noi_dung' => 'Tổng kết và chia sẻ kinh nghiệm'
                    ]
                ]),
                'version' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng su_kien
        if (!empty($data)) {
            $this->db->table('su_kien')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi sự kiện.\n";
        }
        
        echo "Seeder SuKienSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho sự kiện.\n";
    }
} 