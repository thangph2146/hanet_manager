<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('view', $module_name) ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết sự kiện',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý Sự kiện', 'url' => site_url($module_name)],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url($module_name), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chi tiết sự kiện ID: <?= esc($data->su_kien_id) ?></h5>
        <div class="d-flex gap-2">
            <a href="<?= site_url($module_name . '/edit/' . $data->su_kien_id) ?>" class="btn btn-sm btn-primary">
                <i class="bx bx-edit me-1"></i> Chỉnh sửa
            </a>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bx bx-trash me-1"></i> Xóa
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 200px;">ID</th>
                        <td><?= esc($data->su_kien_id) ?></td>
                    </tr>
                    <tr>
                        <th>Poster sự kiện</th>
                        <td>
                            <?php 
                            $poster = $data->getSuKienPoster();
                            if ($poster && isset($poster['url'])): 
                            ?>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= esc($poster['url']) ?>" 
                                         alt="Poster <?= esc($data->ten_su_kien) ?>" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px; height: auto;">
                                    <div class="small text-muted">
                                        <div>Kích thước: <?= esc($poster['width']) ?> x <?= esc($poster['height']) ?> px</div>
                                        <div>URL: <a href="<?= esc($poster['url']) ?>" target="_blank"><?= esc($poster['url']) ?></a></div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Không có poster</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tên sự kiện</th>
                        <td>
                            <?php if (!empty($data->ten_su_kien)): ?>
                                <?= esc($data->ten_su_kien) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mô tả</th>
                        <td>
                            <?php if (!empty($data->mo_ta)): ?>
                                <?= esc($data->mo_ta) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mô tả chi tiết</th>
                        <td>
                            <?php if (!empty($data->chi_tiet_su_kien)): ?>
                                <?= nl2br(esc($data->chi_tiet_su_kien)) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mã QR Code</th>
                        <td>
                            <?php if (!empty($data->ma_qr_code)): ?>
                                <?= esc($data->ma_qr_code) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Thời gian bắt đầu</th>
                        <td>
                            <?php if (!empty($data->thoi_gian_bat_dau)): ?>
                                <?= esc(date('d/m/Y H:i', strtotime($data->thoi_gian_bat_dau))) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Thời gian kết thúc</th>
                        <td>
                            <?php if (!empty($data->thoi_gian_ket_thuc)): ?>
                                <?= esc(date('d/m/Y H:i', strtotime($data->thoi_gian_ket_thuc))) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Địa điểm</th>
                        <td>
                            <?php if (!empty($data->dia_diem)): ?>
                                <?= esc($data->dia_diem) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Địa chỉ cụ thể</th>
                        <td>
                            <?php if (!empty($data->dia_chi_cu_the)): ?>
                                <?= esc($data->dia_chi_cu_the) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tọa độ GPS</th>
                        <td>
                            <?php if (!empty($data->toa_do_gps)): ?>
                                <?= esc($data->toa_do_gps) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Loại sự kiện</th>
                        <td>
                            <?php if (!empty($data->loai_su_kien)): ?>
                                <?= esc($data->loai_su_kien->ten_loai_su_kien) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <?php if ($data->status == 1): ?>
                                <span class="badge bg-success">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Không hoạt động</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Số lượng tham gia</th>
                        <td><?= esc($data->so_luong_tham_gia) ?></td>
                    </tr>
                    <tr>
                        <th>Số lượng diễn giả</th>
                        <td><?= esc($data->so_luong_dien_gia) ?></td>
                    </tr>
                    <tr>
                        <th>Thống kê đăng ký</th>
                        <td>
                            <div class="d-flex gap-3">
                                <div>
                                    <strong>Đăng ký:</strong> <?= esc($data->tong_dang_ky) ?>
                                </div>
                                <div>
                                    <strong>Check-in:</strong> <?= esc($data->tong_check_in) ?>
                                </div>
                                <div>
                                    <strong>Check-out:</strong> <?= esc($data->tong_check_out) ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Thời gian đăng ký</th>
                        <td>
                            <div class="mb-1">
                                <strong>Bắt đầu:</strong> 
                                <?php if (!empty($data->bat_dau_dang_ky)): ?>
                                    <?= esc(date('d/m/Y H:i', strtotime($data->bat_dau_dang_ky))) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Chưa thiết lập</span>
                                <?php endif; ?>
                            </div>
                            <div class="mb-1">
                                <strong>Kết thúc:</strong> 
                                <?php if (!empty($data->ket_thuc_dang_ky)): ?>
                                    <?= esc(date('d/m/Y H:i', strtotime($data->ket_thuc_dang_ky))) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Chưa thiết lập</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <strong>Hạn hủy:</strong> 
                                <?php if (!empty($data->han_huy_dang_ky)): ?>
                                    <?= esc(date('d/m/Y H:i', strtotime($data->han_huy_dang_ky))) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Chưa thiết lập</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Cấu hình tham gia</th>
                        <td>
                            <div class="mb-1">
                                <span class="badge bg-<?= $data->cho_phep_check_in ? 'success' : 'secondary' ?>">
                                    <?= $data->cho_phep_check_in ? 'Cho phép check-in' : 'Không cho phép check-in' ?>
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="badge bg-<?= $data->cho_phep_check_out ? 'success' : 'secondary' ?>">
                                    <?= $data->cho_phep_check_out ? 'Cho phép check-out' : 'Không cho phép check-out' ?>
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="badge bg-<?= $data->yeu_cau_face_id ? 'warning' : 'secondary' ?>">
                                    <?= $data->yeu_cau_face_id ? 'Yêu cầu Face ID' : 'Không yêu cầu Face ID' ?>
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="badge bg-<?= $data->cho_phep_checkin_thu_cong ? 'info' : 'secondary' ?>">
                                    <?= $data->cho_phep_checkin_thu_cong ? 'Cho phép check-in thủ công' : 'Không cho phép check-in thủ công' ?>
                                </span>
                            </div>
                            <div class="mb-1">
                                <span class="badge bg-<?= $data->tu_dong_xac_nhan_svgv ? 'primary' : 'secondary' ?>">
                                    <?= $data->tu_dong_xac_nhan_svgv ? 'Tự động xác nhận SV/GV' : 'Không tự động xác nhận SV/GV' ?>
                                </span>
                            </div>
                            <div>
                                <span class="badge bg-<?= $data->yeu_cau_duyet_khach ? 'danger' : 'secondary' ?>">
                                    <?= $data->yeu_cau_duyet_khach ? 'Yêu cầu duyệt khách' : 'Không yêu cầu duyệt khách' ?>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Giới hạn loại người dùng</th>
                        <td>
                            <?php if (!empty($data->gioi_han_loai_nguoi_dung)): ?>
                                <?php 
                                $loaiNguoiDung = explode(',', $data->gioi_han_loai_nguoi_dung);
                                foreach ($loaiNguoiDung as $loai): 
                                ?>
                                    <span class="badge bg-info me-1"><?= esc(trim($loai)) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Không giới hạn</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Từ khóa & Hashtag</th>
                        <td>
                            <div class="mb-2">
                                <strong>Từ khóa:</strong>
                                <?php if (!empty($data->tu_khoa_su_kien)): ?>
                                    <?php 
                                    $tuKhoa = explode(',', $data->tu_khoa_su_kien);
                                    foreach ($tuKhoa as $keyword): 
                                    ?>
                                        <span class="badge bg-secondary me-1"><?= esc(trim($keyword)) ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <strong>Hashtag:</strong>
                                <?php if (!empty($data->hashtag)): ?>
                                    <?php 
                                    $hashtags = explode(' ', $data->hashtag);
                                    foreach ($hashtags as $hashtag): 
                                        if (trim($hashtag) !== ''): 
                                    ?>
                                        <span class="badge bg-primary me-1"><?= esc(trim($hashtag)) ?></span>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>
                            <?php if (!empty($data->slug)): ?>
                                <?= esc($data->slug) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Số lượt xem</th>
                        <td><?= esc($data->so_luot_xem) ?></td>
                    </tr>
                    <?php if (!empty($data->lich_trinh)): ?>
                    <tr>
                        <th>Lịch trình</th>
                        <td>
                            <?php 
                            $lichTrinh = is_string($data->lich_trinh) 
                                ? json_decode($data->lich_trinh, true)
                                : $data->lich_trinh;
                            
                            if (!empty($lichTrinh) && is_array($lichTrinh)): 
                            ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Thời gian</th>
                                            <th>Nội dung</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lichTrinh as $item): ?>
                                        <tr>
                                            <td style="width: 150px;"><?= esc($item['thoi_gian'] ?? '') ?></td>
                                            <td><?= esc($item['noi_dung'] ?? '') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Lịch trình không hợp lệ</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Phiên bản</th>
                        <td><?= esc($data->version) ?></td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>
                            <?php if (!empty($data->created_at)): ?>
                                <?= esc(date('d/m/Y H:i', strtotime($data->created_at))) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa có</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td>
                            <?php if (!empty($data->updated_at)): ?>
                                <?= esc(date('d/m/Y H:i', strtotime($data->updated_at))) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa có</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày xóa</th>
                        <td>
                            <?php if (!empty($data->deleted_at)): ?>
                                <?= esc(date('d/m/Y H:i', strtotime($data->deleted_at))) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa xóa</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa sự kiện <strong><?= esc($data->ten_su_kien) ?></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url($module_name . '/delete/' . $data->su_kien_id) ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= page_js('view', $module_name) ?>
<?= $this->endSection() ?>
