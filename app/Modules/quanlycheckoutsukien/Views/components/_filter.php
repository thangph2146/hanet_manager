<?php
$perPageOptions = [10, 25, 50, 100];
$statusOptions = [
    '' => 'Tất cả trạng thái',
    '1' => 'Hoạt động',
    '0' => 'Vô hiệu',
    '2' => 'Đang xử lý'
];

$checkoutTypeOptions = [
    '' => 'Tất cả loại check-out',
    'manual' => 'Thủ công',
    'face_id' => 'Nhận diện khuôn mặt',
    'qr_code' => 'Mã QR',
    'auto' => 'Tự động',
    'online' => 'Trực tuyến'
];

$hinhThucThamGiaOptions = [
    '' => 'Tất cả hình thức',
    'offline' => 'Trực tiếp',
    'online' => 'Trực tuyến'
];

// Lấy danh sách sự kiện
$suKienModel = model('App\Modules\quanlysukien\Models\SuKienModel');
$suKienList = $suKienModel->findAll();
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name) ?>" method="get" class="row g-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm..." name="keyword" value="<?= $keyword ?>">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <select class="form-select" name="su_kien_id" onchange="this.form.submit()">
                <option value="">Tất cả sự kiện</option>
                <?php foreach ($suKienList as $suKien) : ?>
                    <option value="<?= $suKien->su_kien_id ?>" <?= isset($su_kien_id) && $su_kien_id == $suKien->su_kien_id ? 'selected' : '' ?>>
                        <?= esc($suKien->ten_su_kien) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="checkout_type" onchange="this.form.submit()">
                <?php foreach ($checkoutTypeOptions as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= isset($checkout_type) && $checkout_type === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="hinh_thuc_tham_gia" onchange="this.form.submit()">
                <?php foreach ($hinhThucThamGiaOptions as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= isset($hinh_thuc_tham_gia) && $hinh_thuc_tham_gia === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach ?>
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
            <select class="form-select" name="perPage" id="perPage" onchange="this.form.submit()">
                <?php foreach ($perPageOptions as $option) : ?>
                    <option value="<?= $option ?>" <?= (string)$perPage === (string)$option ? 'selected' : '' ?>>
                        <?= $option ?> mục
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <!-- Lọc theo thời gian check-out -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="input-group">
                <span class="input-group-text">Từ</span>
                <input type="datetime-local" class="form-control" name="start_date" value="<?= isset($start_date) ? (new DateTime($start_date))->format('Y-m-d\TH:i') : '' ?>">
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="input-group">
                <span class="input-group-text">Đến</span>
                <input type="datetime-local" class="form-control" name="end_date" value="<?= isset($end_date) ? (new DateTime($end_date))->format('Y-m-d\TH:i') : '' ?>">
                <button type="submit" class="btn btn-primary">Lọc</button>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <a href="<?= site_url($module_name)?>" class="btn btn-danger">Xóa lọc</a>
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
          (isset($su_kien_id) && $su_kien_id !== '') || 
          (isset($checkout_type) && $checkout_type !== '') || 
          (isset($hinh_thuc_tham_gia) && $hinh_thuc_tham_gia !== '') ||
          (isset($start_date) && $start_date !== '') ||
          (isset($end_date) && $end_date !== '')): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (isset($su_kien_id) && $su_kien_id !== ''): 
                $tenSuKien = '';
                foreach ($suKienList as $suKien) {
                    if ($suKien->su_kien_id == $su_kien_id) {
                        $tenSuKien = $suKien->ten_su_kien;
                        break;
                    }
                }
                ?>
                <span class="badge bg-secondary me-2">Sự kiện: <?= esc($tenSuKien) ?></span>
            <?php endif; ?>
            
            <?php if (isset($checkout_type) && $checkout_type !== ''): ?>
                <span class="badge bg-info me-2">Loại check-out: <?= $checkoutTypeOptions[$checkout_type] ?></span>
            <?php endif; ?>
            
            <?php if (isset($hinh_thuc_tham_gia) && $hinh_thuc_tham_gia !== ''): ?>
                <span class="badge bg-success me-2">Hình thức: <?= $hinhThucThamGiaOptions[$hinh_thuc_tham_gia] ?></span>
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
            
            <a href="<?= site_url($module_name) ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
        </div>
    </div>
<?php endif; ?> 