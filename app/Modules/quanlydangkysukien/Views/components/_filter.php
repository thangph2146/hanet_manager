<?php
$options = [
    'pagination' => [10, 25, 50, 100],
    'status' => [
        '' => 'Tất cả trạng thái',
        '0' => 'Chờ xác nhận',
        '1' => 'Đã xác nhận', 
        '-1' => 'Đã hủy'
    ],
    'loai_nguoi_dang_ky' => [
        '' => 'Tất cả loại người đăng ký',
        'khach' => 'Khách mời',
        'sinh_vien' => 'Sinh viên',
        'giang_vien' => 'Giảng viên'
    ],
    'hinh_thuc_tham_gia' => [
        '' => 'Tất cả hình thức tham gia',
        'offline' => 'Trực tiếp',
        'online' => 'Trực tuyến',
        'hybrid' => 'Kết hợp'
    ],
    'attendance_status' => [
        '' => 'Tất cả trạng thái điểm danh',
        'not_attended' => 'Chưa tham dự',
        'partial' => 'Tham dự một phần',
        'full' => 'Tham dự đầy đủ'
    ],
    'diem_danh_bang' => [
        '' => 'Tất cả phương thức điểm danh',
        'qr_code' => 'Mã QR',
        'face_id' => 'Nhận diện khuôn mặt',
        'manual' => 'Thủ công',
        'none' => 'Chưa điểm danh'
    ]
];

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$su_kien_id = isset($_GET['su_kien_id']) ? $_GET['su_kien_id'] : '';
$loai_nguoi_dang_ky = isset($_GET['loai_nguoi_dang_ky']) ? $_GET['loai_nguoi_dang_ky'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$hinh_thuc_tham_gia = isset($_GET['hinh_thuc_tham_gia']) ? $_GET['hinh_thuc_tham_gia'] : '';
$attendance_status = isset($_GET['attendance_status']) ? $_GET['attendance_status'] : '';
$diem_danh_bang = isset($_GET['diem_danh_bang']) ? $_GET['diem_danh_bang'] : '';
$face_verified = isset($_GET['face_verified']) ? $_GET['face_verified'] : '';
$da_check_in = isset($_GET['da_check_in']) ? $_GET['da_check_in'] : '';
$da_check_out = isset($_GET['da_check_out']) ? $_GET['da_check_out'] : '';
$perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10;
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name) ?>" method="GET" class="form-horizontal">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-search'></i></span>
                    <input type="text" class="form-control" name="keyword" value="<?= isset($keyword) ? $keyword : '' ?>" placeholder="Tìm kiếm...">
                </div>
            </div>

            <div class="col-12 col-md-3">
                <select name="su_kien_id" class="form-select">
                    <option value="">Tất cả sự kiện</option>
                    <?php if (!empty($suKiens)): ?>
                        <?php foreach ($suKiens as $suKien): ?>
                            <option value="<?= $suKien->getId() ?>" <?= (isset($su_kien_id) && $su_kien_id == $suKien->getId()) ? 'selected' : '' ?>>
                                <?= $suKien->getTenSuKien() ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <select name="loai_nguoi_dang_ky" class="form-select">
                    <?php foreach ($loaiNguoiDungOptions as $value => $label): ?>
                        <option value="<?= $value ?>" <?= (isset($loai_nguoi_dang_ky) && $loai_nguoi_dang_ky == $value) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <select name="status" class="form-select">
                    <?php foreach ($options['status'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= (isset($status) && $status === $value) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <select name="hinh_thuc_tham_gia" class="form-select">
                    <?php foreach ($options['hinh_thuc_tham_gia'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= (isset($hinh_thuc_tham_gia) && $hinh_thuc_tham_gia === $value) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <select name="attendance_status" class="form-select">
                    <?php foreach ($options['attendance_status'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= (isset($attendance_status) && $attendance_status === $value) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <select name="diem_danh_bang" class="form-select">
                    <?php foreach ($options['diem_danh_bang'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= (isset($diem_danh_bang) && $diem_danh_bang === $value) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Từ</span>
                    <input type="datetime-local" class="form-control" name="start_date" value="<?= isset($start_date) ? $start_date : '' ?>">
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Đến</span>
                    <input type="datetime-local" class="form-control" name="end_date" value="<?= isset($end_date) ? $end_date : '' ?>">
                </div>
            </div>

            <div class="col-12 col-md-3">
                <select name="perPage" class="form-select">
                    <?php foreach ($options['pagination'] as $value): ?>
                        <option value="<?= $value ?>" <?= (isset($perPage) && $perPage == $value) ? 'selected' : '' ?>>
                            <?= $value ?> bản ghi
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter-alt"></i> Lọc
                    </button>
                    <a href="<?= site_url($module_name) ?>" class="btn btn-danger">
                        <i class="bx bx-reset"></i> Đặt lại
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (isset($keyword) || isset($su_kien_id) || isset($loai_nguoi_dang_ky) || isset($status) || isset($hinh_thuc_tham_gia) || isset($attendance_status) || isset($diem_danh_bang) || isset($start_date) || isset($end_date)): ?>
    <div class="alert alert-info m-3">
        <h6 class="alert-heading fw-bold mb-1">Kết quả tìm kiếm:</h6>
        <div class="d-flex flex-wrap gap-2">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary">Từ khóa: <?= $keyword ?></span>
            <?php endif; ?>
            
            <?php if (!empty($su_kien_id)): ?>
                <?php foreach ($suKiens as $suKien): ?>
                    <?php if ($suKien->getId() == $su_kien_id): ?>
                        <span class="badge bg-primary">Sự kiện: <?= $suKien->getTenSuKien() ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($loai_nguoi_dang_ky)): ?>
                <span class="badge bg-primary">Loại người đăng ký: <?= $options['loai_nguoi_dang_ky'][$loai_nguoi_dang_ky] ?></span>
            <?php endif; ?>
            
            <?php if (!empty($status)): ?>
                <span class="badge bg-primary">Trạng thái: <?= $options['status'][$status] ?></span>
            <?php endif; ?>
            
            <?php if (!empty($hinh_thuc_tham_gia)): ?>
                <span class="badge bg-primary">Hình thức tham gia: <?= $options['hinh_thuc_tham_gia'][$hinh_thuc_tham_gia] ?></span>
            <?php endif; ?>
            
            <?php if (!empty($attendance_status)): ?>
                <span class="badge bg-primary">Trạng thái điểm danh: <?= $options['attendance_status'][$attendance_status] ?></span>
            <?php endif; ?>
            
            <?php if (!empty($diem_danh_bang)): ?>
                <span class="badge bg-primary">Phương thức điểm danh: <?= $options['diem_danh_bang'][$diem_danh_bang] ?></span>
            <?php endif; ?>
            
            <?php if (!empty($start_date)): ?>
                <span class="badge bg-primary">Từ ngày: <?= date('d/m/Y H:i', strtotime($start_date)) ?></span>
            <?php endif; ?>
            
            <?php if (!empty($end_date)): ?>
                <span class="badge bg-primary">Đến ngày: <?= date('d/m/Y H:i', strtotime($end_date)) ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?> 