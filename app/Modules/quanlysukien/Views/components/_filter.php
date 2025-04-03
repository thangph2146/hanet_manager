<?php
/**
 * Component hiển thị form lọc dữ liệu sự kiện
 */
$perPageOptions = [10, 25, 50, 100];
$statusOptions = [
    '' => 'Tất cả trạng thái',
    '1' => 'Hoạt động',
    '0' => 'Vô hiệu'
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

<div class="card-header p-0 border-0">
    <form action="<?= site_url($module_name) ?>" method="get" class="form-horizontal" id="filterForm">
        <div class="p-0">
            <div class="row mx-0 py-3">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Tìm kiếm theo tên, mô tả, ID..." value="<?= $keyword ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="loai_su_kien_id" id="loai_su_kien_id">
                                    <option value="">-- Loại sự kiện --</option>
                                    <?php foreach ($loaiSuKienList as $id => $name): ?>
                                    <option value="<?= $id ?>" <?= isset($loai_su_kien_id) && $loai_su_kien_id == $id ? 'selected' : '' ?>>
                                        <?= esc($name) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="status" id="status">
                                    <option value="">-- Trạng thái --</option>
                                    <option value="1" <?= isset($status) && $status == '1' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= isset($status) && $status == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="hinh_thuc" id="hinh_thuc">
                                    <option value="">-- Hình thức --</option>
                                    <option value="offline" <?= isset($hinh_thuc) && $hinh_thuc == 'offline' ? 'selected' : '' ?>>Offline</option>
                                    <option value="online" <?= isset($hinh_thuc) && $hinh_thuc == 'online' ? 'selected' : '' ?>>Online</option>
                                    <option value="hybrid" <?= isset($hinh_thuc) && $hinh_thuc == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="start_date" id="start_date" placeholder="Ngày bắt đầu" value="<?= $start_date ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="end_date" id="end_date" placeholder="Ngày kết thúc" value="<?= $end_date ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" name="don_vi_to_chuc" id="don_vi_to_chuc" placeholder="Đơn vị tổ chức" value="<?= $don_vi_to_chuc ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="perPage" id="perPage">
                                    <?php foreach ($perPageOptions as $option): ?>
                                    <option value="<?= $option ?>" <?= ($perPage ?? 10) == $option ? 'selected' : '' ?>><?= $option ?> bản ghi</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="upcoming" id="upcoming">
                                    <option value="">-- Thời gian diễn ra --</option>
                                    <option value="1" <?= isset($upcoming) && $upcoming == '1' ? 'selected' : '' ?>>Sắp diễn ra</option>
                                    <option value="0" <?= isset($upcoming) && $upcoming == '0' ? 'selected' : '' ?>>Đã diễn ra</option>
                                    <option value="ongoing" <?= isset($upcoming) && $upcoming == 'ongoing' ? 'selected' : '' ?>>Đang diễn ra</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="featured" id="featured">
                                    <option value="">-- Sự kiện nổi bật --</option>
                                    <option value="1" <?= isset($featured) && $featured == '1' ? 'selected' : '' ?>>Nổi bật</option>
                                    <option value="0" <?= isset($featured) && $featured == '0' ? 'selected' : '' ?>>Không nổi bật</option>
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
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <select class="form-control" name="sort" id="sort">
                                    <option value="thoi_gian_bat_dau" <?= ($sort ?? 'thoi_gian_bat_dau') == 'thoi_gian_bat_dau' ? 'selected' : '' ?>>Sắp xếp theo thời gian bắt đầu</option>
                                    <option value="thoi_gian_ket_thuc" <?= ($sort ?? '') == 'thoi_gian_ket_thuc' ? 'selected' : '' ?>>Sắp xếp theo thời gian kết thúc</option>
                                    <option value="ten_su_kien" <?= ($sort ?? '') == 'ten_su_kien' ? 'selected' : '' ?>>Sắp xếp theo tên sự kiện</option>
                                    <option value="created_at" <?= ($sort ?? '') == 'created_at' ? 'selected' : '' ?>>Sắp xếp theo ngày tạo</option>
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
                                <input type="text" class="form-control datepicker" name="bat_dau_dang_ky" id="bat_dau_dang_ky" placeholder="Bắt đầu đăng ký từ" value="<?= $bat_dau_dang_ky ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="ket_thuc_dang_ky" id="ket_thuc_dang_ky" placeholder="Kết thúc đăng ký đến" value="<?= $ket_thuc_dang_ky ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="<?= site_url($module_name) ?>" class="btn btn-secondary btn-block">
                        <i class="fas fa-sync"></i> Làm mới
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($keyword) || (isset($status) && $status !== '') || 
          (isset($loai_su_kien_id) && $loai_su_kien_id !== '') ||
          (isset($hinh_thuc) && $hinh_thuc !== '') ||
          (isset($start_date) && $start_date !== '') ||
          (isset($end_date) && $end_date !== '') ||
          (isset($don_vi_to_chuc) && $don_vi_to_chuc !== '') ||
          (isset($upcoming) && $upcoming !== '') ||
          (isset($featured) && $featured !== '')): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (isset($loai_su_kien_id) && $loai_su_kien_id !== ''): ?>
                <span class="badge bg-secondary me-2">Loại sự kiện: <?= esc($loaiSuKienList[$loai_su_kien_id] ?? '') ?></span>
            <?php endif; ?>
            
            <?php if (isset($hinh_thuc) && $hinh_thuc !== ''): ?>
                <span class="badge bg-success me-2">Hình thức: <?= $hinhThucOptions[$hinh_thuc] ?></span>
            <?php endif; ?>
            
            <?php if (isset($status) && $status !== ''): ?>
                <span class="badge bg-warning text-dark me-2">Trạng thái: <?= $statusOptions[$status] ?></span>
            <?php endif; ?>
            
            <?php if (isset($start_date) && $start_date !== ''): ?>
                <span class="badge bg-info me-2">Ngày bắt đầu: <?= esc($start_date) ?></span>
            <?php endif; ?>
            
            <?php if (isset($end_date) && $end_date !== ''): ?>
                <span class="badge bg-info me-2">Ngày kết thúc: <?= esc($end_date) ?></span>
            <?php endif; ?>
            
            <?php if (isset($don_vi_to_chuc) && $don_vi_to_chuc !== ''): ?>
                <span class="badge bg-dark me-2">Đơn vị tổ chức: <?= esc($don_vi_to_chuc) ?></span>
            <?php endif; ?>
            
            <?php if (isset($upcoming) && $upcoming !== ''): ?>
                <span class="badge bg-dark me-2">
                    <?= $upcoming == '1' ? 'Sắp diễn ra' : ($upcoming == '0' ? 'Đã diễn ra' : 'Đang diễn ra') ?>
                </span>
            <?php endif; ?>
            
            <?php if (isset($featured) && $featured !== ''): ?>
                <span class="badge bg-dark me-2">
                    <?= $featured == '1' ? 'Sự kiện nổi bật' : 'Không nổi bật' ?>
                </span>
            <?php endif; ?>
            
            <a href="<?= site_url($module_name) ?>" class="text-decoration-none">
                <i class="bx bx-x"></i> Xóa bộ lọc
            </a>
        </div>
    </div>
<?php endif; ?>