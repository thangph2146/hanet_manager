<?php
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
$loaiSuKienList = $loaiSuKienModel->findAll();
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name . '/listdeleted') ?>" method="get" class="row g-3">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" 
                       placeholder="Tìm kiếm theo tên, mô tả, địa điểm..." 
                       name="keyword" value="<?= $keyword ?>">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bx bx-search"></i>
                </button>
            </div>
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
            <select class="form-select" name="perPage" id="perPage" onchange="this.form.submit()">
                <?php foreach ($perPageOptions as $option) : ?>
                    <option value="<?= $option ?>" <?= (string)$perPage === (string)$option ? 'selected' : '' ?>>
                        <?= $option ?> mục
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <a href="<?= site_url($module_name . '/listdeleted')?>" class="btn btn-danger">Xóa lọc</a>
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
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (isset($loai_su_kien_id) && $loai_su_kien_id !== ''): 
                $tenLoaiSuKien = '';
                foreach ($loaiSuKienList as $loaiSuKien) {
                    if ($loaiSuKien->loai_su_kien_id == $loai_su_kien_id) {
                        $tenLoaiSuKien = $loaiSuKien->ten_loai_su_kien;
                        break;
                    }
                }
                ?>
                <span class="badge bg-secondary me-2">Loại sự kiện: <?= esc($tenLoaiSuKien) ?></span>
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