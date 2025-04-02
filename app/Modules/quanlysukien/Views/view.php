<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('table') ?>
<?= page_section_css('modal') ?>
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
		['url' => site_url($module_name . '/edit/' . $data->getId()), 'title' => 'Chỉnh sửa', 'icon' => 'bx bx-edit'],
		['url' => site_url($module_name), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>  

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card-footer bg-transparent d-flex justify-content-between py-3">
            <a href="<?= site_url($module_name) ?>" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
            </a>
            <div>
                <a href="<?= site_url($module_name . '/edit/' . $data->getId()) ?>" class="btn btn-primary me-2">
                    <i class="bx bx-edit me-1"></i> Chỉnh sửa
                </a>
                <button type="button" class="btn btn-danger btn-delete" 
                        data-id="<?= $data->getId() ?>" 
                        data-name="<?= esc($data->getTenSuKien()) ?>">
                    <i class="bx bx-trash me-1"></i> Xóa
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0"><?= esc($data->getTenSuKien()) ?></h5>
                <div>
                    <span class="badge bg-<?= $data->getStatus() ? 'success' : 'danger' ?>">
                        <?= $data->getStatus() ? 'Hoạt động' : 'Không hoạt động' ?>
                    </span>
                    <span class="badge bg-<?= $data->getHinhThuc() === 'offline' ? 'success' : ($data->getHinhThuc() === 'online' ? 'info' : 'warning') ?>">
                        <?= $data->hinh_thuc_text ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($data->getSuKienPoster())): ?>
                <div class="mb-4 text-center">
                    <?php 
                        $posters = $data->getSuKienPoster();
                        if (is_array($posters) && !empty($posters)):
                            $mainPoster = reset($posters);
                    ?>
                    <img src="<?= esc($mainPoster['url'] ?? '') ?>" alt="<?= esc($data->getTenSuKien()) ?>" class="img-fluid rounded" style="max-height: 300px;">
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between flex-wrap mb-3">
                    <div class="detail-group mb-3">
                        <h6 class="fw-bold"><i class="bx bx-calendar me-1"></i> Thời gian</h6>
                        <div class="ps-4">
                            <p class="mb-1">
                                <strong>Bắt đầu:</strong> <?= esc($data->thoi_gian_bat_dau_formatted) ?>
                            </p>
                            <p class="mb-1">
                                <strong>Kết thúc:</strong> <?= esc($data->thoi_gian_ket_thuc_formatted) ?>
                            </p>
                            <?php if ($data->getGioBatDau()): ?>
                            <p class="mb-1">
                                <strong>Giờ bắt đầu:</strong> <?= $data->getGioBatDau()->format('H:i') ?>
                            </p>
                            <?php endif; ?>
                            <?php if ($data->getGioKetThuc()): ?>
                            <p class="mb-1">
                                <strong>Giờ kết thúc:</strong> <?= $data->getGioKetThuc()->format('H:i') ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-group mb-3">
                        <h6 class="fw-bold"><i class="bx bx-map-pin me-1"></i> Địa điểm</h6>
                        <div class="ps-4">
                            <?php if (!empty($data->getDiaDiem())): ?>
                            <p class="mb-1"><?= esc($data->getDiaDiem()) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($data->getDiaChiCuThe())): ?>
                            <p class="mb-1 text-muted"><?= esc($data->getDiaChiCuThe()) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($data->getToaDoGPS())): ?>
                            <p class="mb-1">
                                <small class="text-muted">
                                    <i class="bx bx-map"></i> <?= esc($data->getToaDoGPS()) ?>
                                </small>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-group mb-3">
                        <h6 class="fw-bold"><i class="bx bx-user-check me-1"></i> Đăng ký tham gia</h6>
                        <div class="ps-4">
                            <?php if ($data->getBatDauDangKy()): ?>
                            <p class="mb-1">
                                <strong>Bắt đầu đăng ký:</strong> <?= $data->getBatDauDangKy()->format('d/m/Y H:i') ?>
                            </p>
                            <?php endif; ?>
                            <?php if ($data->getKetThucDangKy()): ?>
                            <p class="mb-1">
                                <strong>Kết thúc đăng ký:</strong> <?= $data->getKetThucDangKy()->format('d/m/Y H:i') ?>
                            </p>
                            <?php endif; ?>
                            <?php if ($data->getHanHuyDangKy()): ?>
                            <p class="mb-1">
                                <strong>Hạn hủy đăng ký:</strong> <?= $data->getHanHuyDangKy()->format('d/m/Y H:i') ?>
                            </p>
                            <?php endif; ?>
                            <?php if ($data->getSoLuongThamGia()): ?>
                            <p class="mb-1">
                                <strong>Số lượng:</strong> <?= esc($data->getSoLuongThamGia()) ?> người
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($data->getHinhThuc() === 'online' || $data->getHinhThuc() === 'hybrid'): ?>
                    <div class="detail-group mb-3">
                        <h6 class="fw-bold"><i class="bx bx-video me-1"></i> Thông tin trực tuyến</h6>
                        <div class="ps-4">
                            <?php if (!empty($data->getLinkOnline())): ?>
                            <p class="mb-1">
                                <strong>Link tham gia:</strong> 
                                <a href="<?= esc($data->getLinkOnline()) ?>" target="_blank"><?= esc($data->getLinkOnline()) ?></a>
                            </p>
                            <?php endif; ?>
                            <?php if (!empty($data->getMatKhauOnline())): ?>
                            <p class="mb-1">
                                <strong>Mật khẩu:</strong> <?= esc($data->getMatKhauOnline()) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($data->getMoTa())): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Mô tả</h6>
                    <div class="card-text p-3 bg-light rounded">
                        <?= $data->getMoTa() ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($data->getMoTaSuKien())): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Mô tả chi tiết</h6>
                    <div class="card-text p-3 bg-light rounded">
                        <?= $data->getMoTaSuKien() ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($data->getChiTietSuKien())): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Chi tiết sự kiện</h6>
                    <div class="card-text p-3 bg-light rounded">
                        <?= $data->getChiTietSuKien() ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($data->getLichTrinh())): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Lịch trình chi tiết</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Nội dung</th>
                                    <th>Người phụ trách</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data->getLichTrinh() as $lichTrinh): ?>
                                <tr>
                                    <td><?= esc($lichTrinh['thoi_gian'] ?? '') ?></td>
                                    <td><?= esc($lichTrinh['noi_dung'] ?? '') ?></td>
                                    <td><?= esc($lichTrinh['nguoi_phu_trach'] ?? '') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($data->getTuKhoaSuKien()) || !empty($data->getHashtag())): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Từ khóa & Hashtag</h6>
                    <div>
                        <?php if (!empty($data->getTuKhoaSuKien())): ?>
                            <div class="mb-2">
                                <strong>Từ khóa:</strong>
                                <?php
                                $keywords = explode(',', $data->getTuKhoaSuKien());
                                foreach ($keywords as $keyword): ?>
                                    <span class="badge bg-primary me-1"><?= trim(esc($keyword)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($data->getHashtag())): ?>
                            <div>
                                <strong>Hashtag:</strong>
                                <?php
                                $hashtags = explode(',', $data->getHashtag());
                                foreach ($hashtags as $hashtag): ?>
                                    <span class="badge bg-info me-1">#<?= trim(esc($hashtag)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($data->getMaQRCode())): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Mã QR Code</h6>
                    <div class="p-3 bg-light rounded text-center">
                        <img src="<?= site_url('generate-qrcode?data=' . urlencode($data->getMaQRCode())) ?>" alt="QR Code" class="img-fluid" style="max-width: 200px">
                        <p class="text-muted mt-2"><?= esc($data->getMaQRCode()) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer text-muted">
                <div class="row">
                    <div class="col-md-6">
                        <small>Ngày tạo: <?= esc($data->created_at_formatted) ?></small>
                    </div>
                    <div class="col-md-6 text-end">
                        <small>Cập nhật lần cuối: <?= esc($data->updated_at_formatted) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Thông tin bổ sung</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Loại sự kiện</h6>
                    <?php if (!empty($data->loaiSuKien)): ?>
                    <p class="mb-1"><?= esc($data->loaiSuKien->ten_loai_su_kien) ?></p>
                    <?php else: ?>
                    <p class="text-muted mb-1">Không xác định</p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Thống kê tham gia</h6>
                    <div class="d-flex justify-content-between">
                        <div class="text-center p-2">
                            <div class="fs-4 fw-bold"><?= esc($data->getTongDangKy()) ?></div>
                            <small class="text-muted">Đăng ký</small>
                        </div>
                        <div class="text-center p-2">
                            <div class="fs-4 fw-bold"><?= esc($data->getTongCheckIn()) ?></div>
                            <small class="text-muted">Check-in</small>
                        </div>
                        <div class="text-center p-2">
                            <div class="fs-4 fw-bold"><?= esc($data->getTongCheckOut()) ?></div>
                            <small class="text-muted">Check-out</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Cài đặt Check-in/out</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Cho phép check-in
                            <span class="badge bg-<?= $data->getChoPhepCheckIn() ? 'success' : 'danger' ?>">
                                <?= $data->getChoPhepCheckIn() ? 'Có' : 'Không' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Cho phép check-out
                            <span class="badge bg-<?= $data->getChoPhepCheckOut() ? 'success' : 'danger' ?>">
                                <?= $data->getChoPhepCheckOut() ? 'Có' : 'Không' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Yêu cầu nhận diện khuôn mặt
                            <span class="badge bg-<?= $data->getYeuCauFaceId() ? 'success' : 'danger' ?>">
                                <?= $data->getYeuCauFaceId() ? 'Có' : 'Không' ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Cho phép check-in thủ công
                            <span class="badge bg-<?= $data->getChoPhepCheckinThuCong() ? 'success' : 'danger' ?>">
                                <?= $data->getChoPhepCheckinThuCong() ? 'Có' : 'Không' ?>
                            </span>
                        </li>
                    </ul>
                </div>

                <?php if (!empty($data->getGioiHanLoaiNguoiDung())): ?>
                <div class="mb-3">
                    <h6 class="fw-bold">Giới hạn đối tượng tham gia</h6>
                    <p><?= esc($data->getGioiHanLoaiNguoiDung()) ?></p>
                </div>
                <?php endif; ?>

                <div class="mb-3">
                    <h6 class="fw-bold">Thông tin khác</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Số lượng diễn giả
                            <span class="badge bg-secondary"><?= esc($data->getSoLuongDienGia()) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Số lượt xem
                            <span class="badge bg-secondary"><?= esc($data->getSoLuotXem()) ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Phiên bản
                            <span class="badge bg-secondary"><?= esc($data->getVersion()) ?></span>
                        </li>
                        <?php if (!empty($data->getSlug())): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                Slug
                                <span class="badge bg-secondary"><?= esc($data->getSlug()) ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sự kiện <strong id="delete-item-name"></strong>?</p>
                <p class="text-danger mb-0"><i class="bx bx-info-circle"></i> Hành động này có thể được hoàn tác từ danh sách mục đã xóa.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="delete-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Xử lý xóa mục
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        $('#delete-item-name').text(name);
        $('#delete-form').attr('action', '<?= site_url($module_name . "/delete/") ?>' + id);
        
        $('#deleteConfirmModal').modal('show');
    });
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
