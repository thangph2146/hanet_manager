<?php
/**
 * Component hiển thị form lọc dữ liệu sự kiện đã xóa
 */

$perPageOptions = [10, 25, 50, 100];
$statusOptions = [
    '' => 'Tất cả trạng thái',
    '1' => 'Hoạt động',
    '0' => 'Vô hiệu'
];

$sortOptions = [
    'deleted_at' => 'Ngày xóa',
    'ten_su_kien' => 'Tên sự kiện',
    'thoi_gian_bat_dau' => 'Thời gian bắt đầu',
    'thoi_gian_ket_thuc' => 'Thời gian kết thúc',
    'dia_diem' => 'Địa điểm',
    'created_at' => 'Ngày tạo',
    'updated_at' => 'Ngày cập nhật'
];

$orderOptions = [
    'DESC' => 'Giảm dần',
    'ASC' => 'Tăng dần'
];

$hinhThucOptions = [
    '' => 'Tất cả hình thức',
    'offline' => 'Trực tiếp',
    'online' => 'Trực tuyến',
    'hybrid' => 'Kết hợp'
];

// Lấy danh sách loại sự kiện
$loaiSuKienModel = model('App\Modules\quanlyloaisukien\Models\LoaiSuKienModel');
$loaiSuKienList = $loaiSuKienModel->getForDropdown(true);
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name . '/listdeleted') ?>" method="get" class="row g-3">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" 
                       placeholder="Tìm kiếm theo tên, mô tả, địa điểm, ID..." 
                       name="keyword" value="<?= $keyword ?? '' ?>">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="loai_su_kien_id" onchange="this.form.submit()">
                <option value="">-- Loại sự kiện --</option>
                <?php foreach ($loaiSuKienList as $id => $name): ?>
                <option value="<?= $id ?>" <?= isset($loai_su_kien_id) && $loai_su_kien_id == $id ? 'selected' : '' ?>>
                    <?= esc($name) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="status" onchange="this.form.submit()">
                <?php foreach ($statusOptions as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= isset($status) && $status === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="sort" onchange="this.form.submit()">
                <?php foreach ($sortOptions as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= isset($sort) && $sort === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="order" onchange="this.form.submit()">
                <?php foreach ($orderOptions as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= isset($order) && $order === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="hinh_thuc" onchange="this.form.submit()">
                <?php foreach ($hinhThucOptions as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= isset($hinh_thuc) && $hinh_thuc === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="perPage" id="perPage" onchange="this.form.submit()">
                <?php foreach ($perPageOptions as $option) : ?>
                    <option value="<?= $option ?>" <?= (string)($perPage ?? 10) === (string)$option ? 'selected' : '' ?>>
                        <?= $option ?> mục
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="input-group">
                <input type="text" class="form-control datepicker" name="start_date" 
                       placeholder="Từ ngày" value="<?= $start_date ?? '' ?>">
                <span class="input-group-text">đến</span>
                <input type="text" class="form-control datepicker" name="end_date" 
                       placeholder="Đến ngày" value="<?= $end_date ?? '' ?>">
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <a href="<?= site_url($module_name . '/listdeleted')?>" class="btn btn-danger">Xóa lọc</a>
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý sự kiện thay đổi perPage
    document.getElementById('perPage').addEventListener('change', function(e) {
        e.preventDefault();
        let form = this.closest('form');
        // Reset về trang 1 khi thay đổi số lượng hiển thị
        let pageInput = document.createElement('input');
        pageInput.type = 'hidden';
        pageInput.name = 'page';
        pageInput.value = '1';
        form.appendChild(pageInput);
        form.submit();
    });
});
</script>

<?php if (!empty($keyword) || (isset($status) && $status !== '') || 
          (isset($loai_su_kien_id) && $loai_su_kien_id !== '') || 
          (isset($hinh_thuc) && $hinh_thuc !== '') ||
          (isset($start_date) && $start_date !== '') ||
          (isset($end_date) && $end_date !== '')): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm (đã xóa):</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (isset($loai_su_kien_id) && $loai_su_kien_id !== ''): ?>
                <span class="badge bg-secondary me-2">Loại sự kiện: <?= esc($loaiSuKienList[$loai_su_kien_id] ?? 'Không xác định') ?></span>
            <?php endif; ?>
            
            <?php if (isset($hinh_thuc) && $hinh_thuc !== ''): ?>
                <span class="badge bg-success me-2">Hình thức: <?= $hinhThucOptions[$hinh_thuc] ?></span>
            <?php endif; ?>
            
            <?php if (isset($status) && $status !== ''): ?>
                <span class="badge bg-warning text-dark me-2">Trạng thái: <?= $statusOptions[$status] ?></span>
            <?php endif; ?>
            
            <?php if (isset($start_date) && $start_date !== ''): ?>
                <span class="badge bg-dark me-2">Từ ngày: <?= date('d/m/Y H:i:s', strtotime($start_date)) ?></span>
            <?php endif; ?>
            
            <?php if (isset($end_date) && $end_date !== ''): ?>
                <span class="badge bg-dark me-2">Đến ngày: <?= date('d/m/Y H:i:s', strtotime($end_date)) ?></span>
            <?php endif; ?>
            
            <a href="<?= site_url($module_name . '/listdeleted') ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
        </div>
    </div>
<?php endif; ?>

<div class="row mt-2">
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <select class="form-control" name="hinh_thuc" id="hinh_thuc">
                <option value="">-- Hình thức sự kiện --</option>
                <option value="offline" <?= isset($hinh_thuc) && $hinh_thuc == 'offline' ? 'selected' : '' ?>>Offline</option>
                <option value="online" <?= isset($hinh_thuc) && $hinh_thuc == 'online' ? 'selected' : '' ?>>Online</option>
                <option value="hybrid" <?= isset($hinh_thuc) && $hinh_thuc == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <select class="form-control" name="cho_phep_check_in" id="cho_phep_check_in">
                <option value="">-- Trạng thái check-in --</option>
                <option value="1" <?= isset($cho_phep_check_in) && $cho_phep_check_in == '1' ? 'selected' : '' ?>>Cho phép check-in</option>
                <option value="0" <?= isset($cho_phep_check_in) && $cho_phep_check_in == '0' ? 'selected' : '' ?>>Không cho phép check-in</option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <input type="text" class="form-control" name="doi_tuong_tham_gia" id="doi_tuong_tham_gia" placeholder="Đối tượng tham gia" value="<?= $doi_tuong_tham_gia ?? '' ?>">
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <input type="text" class="form-control" name="don_vi_to_chuc" id="don_vi_to_chuc" placeholder="Đơn vị tổ chức" value="<?= $don_vi_to_chuc ?? '' ?>">
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <select class="form-control" name="sort" id="sort">
                <option value="deleted_at" <?= ($sort ?? 'deleted_at') == 'deleted_at' ? 'selected' : '' ?>>Sắp xếp theo ngày xóa</option>
                <option value="thoi_gian_bat_dau" <?= ($sort ?? '') == 'thoi_gian_bat_dau' ? 'selected' : '' ?>>Sắp xếp theo thời gian bắt đầu</option>
                <option value="thoi_gian_ket_thuc" <?= ($sort ?? '') == 'thoi_gian_ket_thuc' ? 'selected' : '' ?>>Sắp xếp theo thời gian kết thúc</option>
                <option value="ten_su_kien" <?= ($sort ?? '') == 'ten_su_kien' ? 'selected' : '' ?>>Sắp xếp theo tên sự kiện</option>
                <option value="tong_dang_ky" <?= ($sort ?? '') == 'tong_dang_ky' ? 'selected' : '' ?>>Sắp xếp theo số lượng đăng ký</option>
                <option value="tong_check_in" <?= ($sort ?? '') == 'tong_check_in' ? 'selected' : '' ?>>Sắp xếp theo số lượng check-in</option>
                <option value="so_luong_tham_gia" <?= ($sort ?? '') == 'so_luong_tham_gia' ? 'selected' : '' ?>>Sắp xếp theo số lượng tham gia</option>
            </select>
        </div>
    </div>
    
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <select class="form-control" name="order" id="order">
                <option value="DESC" <?= ($order ?? 'DESC') == 'DESC' ? 'selected' : '' ?>>Giảm dần</option>
                <option value="ASC" <?= ($order ?? '') == 'ASC' ? 'selected' : '' ?>>Tăng dần</option>
            </select>
        </div>
    </div>
    
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <input type="text" class="form-control datepicker" name="deleted_start" id="deleted_start" placeholder="Ngày xóa từ" value="<?= $deleted_start ?? '' ?>">
        </div>
    </div>
    
    <div class="col-md-3 mb-2">
        <div class="form-group mb-0">
            <input type="text" class="form-control datepicker" name="deleted_end" id="deleted_end" placeholder="Ngày xóa đến" value="<?= $deleted_end ?? '' ?>">
        </div>
    </div>
</div> 