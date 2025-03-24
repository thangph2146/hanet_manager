<?php

namespace App\Modules\diengia\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DiengiaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ten_dien_gia' => 'Nguyễn Văn An',
                'chuc_danh' => 'Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Quốc gia Hà Nội',
                'gioi_thieu' => 'Chuyên gia hàng đầu về công nghệ thông tin với hơn 20 năm kinh nghiệm nghiên cứu và giảng dạy.',
                'avatar' => 'assets/images/diengia/nguyen-van-an.jpg',
                'thu_tu' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Trần Thị Bình',
                'chuc_danh' => 'Tiến sĩ',
                'to_chuc' => 'Viện Nghiên cứu Kinh tế',
                'gioi_thieu' => 'Nhà nghiên cứu kinh tế với nhiều công trình khoa học được công bố quốc tế.',
                'avatar' => 'assets/images/diengia/tran-thi-binh.jpg',
                'thu_tu' => 2,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Lê Minh Cường',
                'chuc_danh' => 'CEO',
                'to_chuc' => 'Tập đoàn FPT',
                'gioi_thieu' => 'Nhà lãnh đạo với tầm nhìn chiến lược, đã đưa công ty vượt qua nhiều thách thức để trở thành tập đoàn hàng đầu.',
                'avatar' => 'assets/images/diengia/le-minh-cuong.jpg',
                'thu_tu' => 3,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Phạm Quỳnh Dung',
                'chuc_danh' => 'Nhà văn, Nhà báo',
                'to_chuc' => 'Hội Nhà văn Việt Nam',
                'gioi_thieu' => 'Tác giả của nhiều tác phẩm văn học được đông đảo độc giả yêu thích.',
                'avatar' => 'assets/images/diengia/pham-quynh-dung.jpg',
                'thu_tu' => 4,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Hoàng Gia Huy',
                'chuc_danh' => 'Chuyên gia Marketing',
                'to_chuc' => 'Công ty Cổ phần Tiếp thị Số',
                'gioi_thieu' => 'Chuyên gia hàng đầu về tiếp thị số và xây dựng thương hiệu.',
                'avatar' => 'assets/images/diengia/hoang-gia-huy.jpg',
                'thu_tu' => 5,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Đỗ Thị Lan',
                'chuc_danh' => 'Phó Giáo sư, Tiến sĩ',
                'to_chuc' => 'Đại học Y Hà Nội',
                'gioi_thieu' => 'Chuyên gia y tế với nhiều năm nghiên cứu về các bệnh truyền nhiễm mới nổi.',
                'avatar' => 'assets/images/diengia/do-thi-lan.jpg',
                'thu_tu' => 6,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Vũ Thanh Minh',
                'chuc_danh' => 'Kiến trúc sư',
                'to_chuc' => 'Công ty Thiết kế VTM',
                'gioi_thieu' => 'Kiến trúc sư nổi tiếng với nhiều công trình hiện đại và bền vững.',
                'avatar' => 'assets/images/diengia/vu-thanh-minh.jpg',
                'thu_tu' => 7,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Nguyễn Hoài Nam',
                'chuc_danh' => 'Nhà đầu tư',
                'to_chuc' => 'Quỹ đầu tư Nam Ventures',
                'gioi_thieu' => 'Nhà đầu tư thành công với nhiều dự án khởi nghiệp công nghệ.',
                'avatar' => 'assets/images/diengia/nguyen-hoai-nam.jpg',
                'thu_tu' => 8,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Trần Anh Quân',
                'chuc_danh' => 'Giám đốc Kỹ thuật',
                'to_chuc' => 'Tập đoàn Viettel',
                'gioi_thieu' => 'Chuyên gia về hạ tầng mạng và an ninh mạng với nhiều kinh nghiệm triển khai dự án lớn.',
                'avatar' => 'assets/images/diengia/tran-anh-quan.jpg',
                'thu_tu' => 9,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Lê Thị Phương',
                'chuc_danh' => 'Nhà nghiên cứu',
                'to_chuc' => 'Viện Khoa học Xã hội Việt Nam',
                'gioi_thieu' => 'Chuyên gia nghiên cứu về các vấn đề xã hội và bình đẳng giới.',
                'avatar' => 'assets/images/diengia/le-thi-phuong.jpg',
                'thu_tu' => 10,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Đinh Văn Sơn',
                'chuc_danh' => 'Chuyên gia Tài chính',
                'to_chuc' => 'Ngân hàng BIDV',
                'gioi_thieu' => 'Chuyên gia tài chính ngân hàng với kinh nghiệm tư vấn cho nhiều doanh nghiệp lớn.',
                'avatar' => 'assets/images/diengia/dinh-van-son.jpg',
                'thu_tu' => 11,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Phạm Thị Thảo',
                'chuc_danh' => 'Tiến sĩ',
                'to_chuc' => 'Viện Nghiên cứu Môi trường',
                'gioi_thieu' => 'Nhà khoa học môi trường với nhiều đóng góp trong lĩnh vực phát triển bền vững.',
                'avatar' => 'assets/images/diengia/pham-thi-thao.jpg',
                'thu_tu' => 12,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Lý Văn Trường',
                'chuc_danh' => 'Luật sư',
                'to_chuc' => 'Công ty Luật LVT',
                'gioi_thieu' => 'Luật sư có uy tín trong lĩnh vực pháp lý doanh nghiệp và sở hữu trí tuệ.',
                'avatar' => 'assets/images/diengia/ly-van-truong.jpg',
                'thu_tu' => 13,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Ngô Thị Uyên',
                'chuc_danh' => 'Chuyên gia Nhân sự',
                'to_chuc' => 'Tập đoàn Vingroup',
                'gioi_thieu' => 'Chuyên gia nhân sự cấp cao với nhiều năm kinh nghiệm quản lý nhân tài.',
                'avatar' => 'assets/images/diengia/ngo-thi-uyen.jpg',
                'thu_tu' => 14,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Trần Văn Vũ',
                'chuc_danh' => 'Giám đốc Sáng tạo',
                'to_chuc' => 'Công ty Quảng cáo Sáng tạo Việt',
                'gioi_thieu' => 'Chuyên gia sáng tạo với nhiều chiến dịch quảng cáo thành công.',
                'avatar' => 'assets/images/diengia/tran-van-vu.jpg',
                'thu_tu' => 15,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Đặng Xuân Yến',
                'chuc_danh' => 'Nhà khoa học',
                'to_chuc' => 'Viện Công nghệ Sinh học',
                'gioi_thieu' => 'Chuyên gia công nghệ sinh học với nhiều nghiên cứu về ứng dụng công nghệ trong nông nghiệp.',
                'avatar' => 'assets/images/diengia/dang-xuan-yen.jpg',
                'thu_tu' => 16,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Bùi Quang Hải',
                'chuc_danh' => 'Giám đốc Điều hành',
                'to_chuc' => 'Công ty Thương mại Điện tử BQH',
                'gioi_thieu' => 'Doanh nhân thành công trong lĩnh vực thương mại điện tử.',
                'avatar' => 'assets/images/diengia/bui-quang-hai.jpg',
                'thu_tu' => 17,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Hoàng Thị Kim Chi',
                'chuc_danh' => 'Nhà báo',
                'to_chuc' => 'Báo Tuổi Trẻ',
                'gioi_thieu' => 'Nhà báo kỳ cựu với nhiều phóng sự điều tra có giá trị xã hội cao.',
                'avatar' => 'assets/images/diengia/hoang-thi-kim-chi.jpg',
                'thu_tu' => 18,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Trương Minh Đức',
                'chuc_danh' => 'Chuyên gia Công nghệ',
                'to_chuc' => 'Microsoft Việt Nam',
                'gioi_thieu' => 'Chuyên gia về trí tuệ nhân tạo và điện toán đám mây.',
                'avatar' => 'assets/images/diengia/truong-minh-duc.jpg',
                'thu_tu' => 19,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Lê Thanh Mai',
                'chuc_danh' => 'Giáo sư',
                'to_chuc' => 'Đại học Kinh tế TP.HCM',
                'gioi_thieu' => 'Giáo sư kinh tế với nhiều công trình nghiên cứu về phát triển kinh tế bền vững.',
                'avatar' => 'assets/images/diengia/le-thanh-mai.jpg',
                'thu_tu' => 20,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
        ];

        // Insert data into the dien_gia table
        $this->db->table('dien_gia')->insertBatch($data);
        
        echo "Seeder DiengiaSeeder đã chạy thành công! Đã tạo 20 bản ghi diễn giả mẫu.\n";
    }
} 