<?php

namespace App\Modules\diengia\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DienGiaSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng dien_gia
        $data = [
            [
                'ten_dien_gia' => 'GS.TS. Nguyễn Văn A',
                'chuc_danh' => 'Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Quốc gia Hà Nội',
                'gioi_thieu' => 'Chuyên gia hàng đầu về Công nghệ thông tin với hơn 20 năm kinh nghiệm giảng dạy và nghiên cứu. Đã tham gia nhiều dự án nghiên cứu cấp quốc gia và quốc tế.',
                'avatar' => 'nguyen-van-a.jpg',
                'email' => 'nguyenvana@vnu.edu.vn',
                'dien_thoai' => '0912345678',
                'website' => 'https://vnu.edu.vn/nguyenvana',
                'chuyen_mon' => 'Công nghệ thông tin, Trí tuệ nhân tạo, Học máy, Xử lý ngôn ngữ tự nhiên',
                'thanh_tuu' => "- Giải thưởng Khoa học Công nghệ Quốc gia 2020\n- Hơn 50 bài báo khoa học quốc tế\n- 5 bằng sáng chế\n- Chủ nhiệm 3 đề tài cấp Nhà nước\n- Giảng viên xuất sắc 5 năm liền",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/nguyenvana',
                    'linkedin' => 'https://linkedin.com/in/nguyenvana',
                    'google_scholar' => 'https://scholar.google.com/citations?user=nguyenvana',
                    'researchgate' => 'https://www.researchgate.net/profile/nguyenvana'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 8,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'PGS.TS. Trần Thị B',
                'chuc_danh' => 'Phó Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Bách khoa Hà Nội',
                'gioi_thieu' => 'Chuyên gia về Khoa học máy tính và Trí tuệ nhân tạo. Có nhiều kinh nghiệm trong lĩnh vực phát triển hệ thống thông minh và ứng dụng AI.',
                'avatar' => 'tran-thi-b.jpg',
                'email' => 'tranthib@hust.edu.vn',
                'dien_thoai' => '0912345679',
                'website' => 'https://hust.edu.vn/tranthib',
                'chuyen_mon' => 'Khoa học máy tính, Trí tuệ nhân tạo, Xử lý ngôn ngữ tự nhiên, Hệ thống thông minh',
                'thanh_tuu' => "- Giải thưởng Khoa học Công nghệ Quốc gia 2021\n- Hơn 30 bài báo khoa học quốc tế\n- 3 bằng sáng chế\n- Chủ nhiệm 2 đề tài cấp Bộ\n- Giảng viên xuất sắc 3 năm liền",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/tranthib',
                    'linkedin' => 'https://linkedin.com/in/tranthib',
                    'google_scholar' => 'https://scholar.google.com/citations?user=tranthib',
                    'researchgate' => 'https://www.researchgate.net/profile/tranthib'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 5,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'TS. Lê Văn C',
                'chuc_danh' => 'Tiến sĩ',
                'to_chuc' => 'Viện Công nghệ thông tin',
                'gioi_thieu' => 'Chuyên gia về Bảo mật thông tin và An ninh mạng. Có nhiều kinh nghiệm trong lĩnh vực bảo mật hệ thống và phát hiện xâm nhập.',
                'avatar' => 'le-van-c.jpg',
                'email' => 'levanc@ioit.ac.vn',
                'dien_thoai' => '0912345680',
                'website' => 'https://ioit.ac.vn/levanc',
                'chuyen_mon' => 'Bảo mật thông tin, An ninh mạng, Mật mã học, Phát hiện xâm nhập',
                'thanh_tuu' => "- Giải thưởng An toàn thông tin 2022\n- Hơn 20 bài báo khoa học quốc tế\n- 2 bằng sáng chế\n- Chủ nhiệm 1 đề tài cấp Nhà nước\n- Tham gia 5 dự án bảo mật cấp quốc gia",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/levanc',
                    'linkedin' => 'https://linkedin.com/in/levanc',
                    'google_scholar' => 'https://scholar.google.com/citations?user=levanc',
                    'researchgate' => 'https://www.researchgate.net/profile/levanc'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 6,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'ThS. Phạm Thị D',
                'chuc_danh' => 'Thạc sĩ',
                'to_chuc' => 'Đại học FPT',
                'gioi_thieu' => 'Chuyên gia về Phát triển phần mềm và Công nghệ Web. Có nhiều kinh nghiệm trong lĩnh vực phát triển ứng dụng web và mobile.',
                'avatar' => 'pham-thi-d.jpg',
                'email' => 'phamthid@fpt.edu.vn',
                'dien_thoai' => '0912345681',
                'website' => 'https://fpt.edu.vn/phamthid',
                'chuyen_mon' => 'Phát triển phần mềm, Công nghệ Web, UI/UX Design, Mobile Development',
                'thanh_tuu' => "- Giải thưởng Sản phẩm CNTT xuất sắc 2023\n- Hơn 10 dự án phần mềm thương mại\n- 1 bằng sáng chế\n- Chủ nhiệm 2 đề tài cấp trường\n- Giảng viên xuất sắc 2 năm liền",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/phamthid',
                    'linkedin' => 'https://linkedin.com/in/phamthid',
                    'github' => 'https://github.com/phamthid',
                    'stackoverflow' => 'https://stackoverflow.com/users/phamthid'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 3,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'GS.TS. Hoàng Văn E',
                'chuc_danh' => 'Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Công nghệ thông tin',
                'gioi_thieu' => 'Chuyên gia về Hệ thống thông tin và Quản trị dữ liệu. Có nhiều kinh nghiệm trong lĩnh vực xây dựng hệ thống thông tin quản lý và phân tích dữ liệu lớn.',
                'avatar' => 'hoang-van-e.jpg',
                'email' => 'hoangvane@uit.edu.vn',
                'dien_thoai' => '0912345682',
                'website' => 'https://uit.edu.vn/hoangvane',
                'chuyen_mon' => 'Hệ thống thông tin, Quản trị dữ liệu, Khoa học dữ liệu, Phân tích dữ liệu lớn',
                'thanh_tuu' => "- Giải thưởng Khoa học Công nghệ Quốc gia 2019\n- Hơn 40 bài báo khoa học quốc tế\n- 4 bằng sáng chế\n- Chủ nhiệm 2 đề tài cấp Nhà nước\n- Giảng viên xuất sắc 4 năm liền",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/hoangvane',
                    'linkedin' => 'https://linkedin.com/in/hoangvane',
                    'google_scholar' => 'https://scholar.google.com/citations?user=hoangvane',
                    'researchgate' => 'https://www.researchgate.net/profile/hoangvane'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 9,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'PGS.TS. Đỗ Thị F',
                'chuc_danh' => 'Phó Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Khoa học tự nhiên',
                'gioi_thieu' => 'Chuyên gia về Toán học và Lý thuyết tính toán. Có nhiều kinh nghiệm trong lĩnh vực nghiên cứu và giảng dạy Toán học ứng dụng.',
                'avatar' => 'do-thi-f.jpg',
                'email' => 'dothif@hus.edu.vn',
                'dien_thoai' => '0912345683',
                'website' => 'https://hus.edu.vn/dothif',
                'chuyen_mon' => 'Toán học, Lý thuyết tính toán, Toán học ứng dụng, Tối ưu hóa',
                'thanh_tuu' => "- Giải thưởng Khoa học Công nghệ Quốc gia 2022\n- Hơn 25 bài báo khoa học quốc tế\n- 2 bằng sáng chế\n- Chủ nhiệm 1 đề tài cấp Nhà nước\n- Giảng viên xuất sắc 3 năm liền",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/dothif',
                    'linkedin' => 'https://linkedin.com/in/dothif',
                    'google_scholar' => 'https://scholar.google.com/citations?user=dothif',
                    'researchgate' => 'https://www.researchgate.net/profile/dothif'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 4,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'TS. Vũ Văn G',
                'chuc_danh' => 'Tiến sĩ',
                'to_chuc' => 'Viện Nghiên cứu Công nghệ',
                'gioi_thieu' => 'Chuyên gia về Điện tử viễn thông và Mạng không dây. Có nhiều kinh nghiệm trong lĩnh vực nghiên cứu và phát triển công nghệ viễn thông.',
                'avatar' => 'vu-van-g.jpg',
                'email' => 'vuvang@irt.vn',
                'dien_thoai' => '0912345684',
                'website' => 'https://irt.vn/vuvang',
                'chuyen_mon' => 'Điện tử viễn thông, Mạng không dây, IoT, Công nghệ 5G',
                'thanh_tuu' => "- Giải thưởng Khoa học Công nghệ Quốc gia 2021\n- Hơn 15 bài báo khoa học quốc tế\n- 1 bằng sáng chế\n- Chủ nhiệm 1 đề tài cấp Bộ\n- Tham gia 3 dự án cấp quốc gia",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/vuvang',
                    'linkedin' => 'https://linkedin.com/in/vuvang',
                    'google_scholar' => 'https://scholar.google.com/citations?user=vuvang',
                    'researchgate' => 'https://www.researchgate.net/profile/vuvang'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 5,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'ThS. Bùi Thị H',
                'chuc_danh' => 'Thạc sĩ',
                'to_chuc' => 'Đại học Công nghiệp Hà Nội',
                'gioi_thieu' => 'Chuyên gia về Công nghệ phần mềm và Phát triển ứng dụng. Có nhiều kinh nghiệm trong lĩnh vực phát triển phần mềm và đào tạo lập trình viên.',
                'avatar' => 'bui-thi-h.jpg',
                'email' => 'buithih@haui.edu.vn',
                'dien_thoai' => '0912345685',
                'website' => 'https://haui.edu.vn/buithih',
                'chuyen_mon' => 'Công nghệ phần mềm, Phát triển ứng dụng, Lập trình hướng đối tượng, Kiểm thử phần mềm',
                'thanh_tuu' => "- Giải thưởng Sản phẩm CNTT xuất sắc 2022\n- Hơn 8 dự án phần mềm thương mại\n- Chủ nhiệm 1 đề tài cấp trường\n- Giảng viên xuất sắc 2 năm liền\n- Đào tạo hơn 500 lập trình viên",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/buithih',
                    'linkedin' => 'https://linkedin.com/in/buithih',
                    'github' => 'https://github.com/buithih',
                    'stackoverflow' => 'https://stackoverflow.com/users/buithih'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 2,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'GS.TS. Ngô Văn I',
                'chuc_danh' => 'Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Giao thông vận tải',
                'gioi_thieu' => 'Chuyên gia về Hệ thống nhúng và IoT. Có nhiều kinh nghiệm trong lĩnh vực nghiên cứu và phát triển hệ thống nhúng thông minh.',
                'avatar' => 'ngo-van-i.jpg',
                'email' => 'ngovani@utc.edu.vn',
                'dien_thoai' => '0912345686',
                'website' => 'https://utc.edu.vn/ngovani',
                'chuyen_mon' => 'Hệ thống nhúng, IoT, Vi xử lý, Điều khiển tự động',
                'thanh_tuu' => "- Giải thưởng Khoa học Công nghệ Quốc gia 2020\n- Hơn 35 bài báo khoa học quốc tế\n- 3 bằng sáng chế\n- Chủ nhiệm 2 đề tài cấp Nhà nước\n- Giảng viên xuất sắc 5 năm liền",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/ngovani',
                    'linkedin' => 'https://linkedin.com/in/ngovani',
                    'google_scholar' => 'https://scholar.google.com/citations?user=ngovani',
                    'researchgate' => 'https://www.researchgate.net/profile/ngovani'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 7,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'PGS.TS. Dương Thị K',
                'chuc_danh' => 'Phó Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Thương mại',
                'gioi_thieu' => 'Chuyên gia về Thương mại điện tử và Marketing số. Có nhiều kinh nghiệm trong lĩnh vực nghiên cứu và giảng dạy về thương mại điện tử.',
                'avatar' => 'duong-thi-k.jpg',
                'email' => 'duongthik@tmu.edu.vn',
                'dien_thoai' => '0912345687',
                'website' => 'https://tmu.edu.vn/duongthik',
                'chuyen_mon' => 'Thương mại điện tử, Marketing số, Quản trị thương mại điện tử, Phân tích dữ liệu kinh doanh',
                'thanh_tuu' => "- Giải thưởng Khoa học Công nghệ Quốc gia 2021\n- Hơn 20 bài báo khoa học quốc tế\n- 1 bằng sáng chế\n- Chủ nhiệm 1 đề tài cấp Bộ\n- Giảng viên xuất sắc 3 năm liền",
                'mang_xa_hoi' => json_encode([
                    'facebook' => 'https://facebook.com/duongthik',
                    'linkedin' => 'https://linkedin.com/in/duongthik',
                    'google_scholar' => 'https://scholar.google.com/citations?user=duongthik',
                    'researchgate' => 'https://www.researchgate.net/profile/duongthik'
                ]),
                'status' => 1,
                'so_su_kien_tham_gia' => 5,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng dien_gia
        if (!empty($data)) {
            $this->db->table('dien_gia')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi diễn giả.\n";
        }
        
        echo "Seeder DienGiaSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho diễn giả.\n";
    }
} 