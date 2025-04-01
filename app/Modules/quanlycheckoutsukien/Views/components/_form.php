<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $checkout_sukien_id = $data->getId() ?? '';
    $su_kien_id = $data->getSuKienId() ?? '';
    $ho_ten = $data->getHoTen() ?? '';
    $email = $data->getEmail() ?? '';
    $thoi_gian_check_out = $data->getThoiGianCheckOutFormatted('Y-m-d\TH:i') ?? date('Y-m-d\TH:i');
    $checkout_type = $data->getCheckoutType() ?? 'manual';
    $hinh_thuc_tham_gia = $data->getHinhThucThamGia() ?? 'offline';
    $status = $data->getStatus() ?? 1;
    $face_verified = $data->isFaceVerified() ? 1 : 0;
    $ma_xac_nhan = $data->getMaXacNhan() ?? '';
    $ghi_chu = $data->getGhiChu() ?? '';
    $thong_tin_bo_sung = $data->getThongTinBoSungJson() ?? '';
    $danh_gia = $data->getDanhGia() ?? '';
    $noi_dung_danh_gia = $data->getNoiDungDanhGia() ?? '';
    $feedback = $data->getFeedback() ?? '';
} else {
    $checkout_sukien_id = '';
    $su_kien_id = '';
    $ho_ten = '';
    $email = '';
    $thoi_gian_check_out = date('Y-m-d\TH:i');
    $checkout_type = 'manual';
    $hinh_thuc_tham_gia = 'offline';
    $status = 1;
    $face_verified = 0;
    $ma_xac_nhan = '';
    $ghi_chu = '';
    $thong_tin_bo_sung = '';
    $danh_gia = '';
    $noi_dung_danh_gia = '';
    $feedback = '';
}
?>

<div class="col-md-6">
    <label for="su_kien_id" class="form-label">Sự kiện <span class="text-danger">*</span></label>
    <select class="form-select <?= isset($validation) && $validation->hasError('su_kien_id') ? 'is-invalid' : '' ?>" id="su_kien_id" name="su_kien_id" required>
        <option value="">Chọn sự kiện</option>
        <?php 
        // Lấy danh sách sự kiện từ controller
        $suKienModel = model('App\Modules\sukien\Models\SuKienModel');
        $suKienList = $suKienModel->findAll();
        foreach ($suKienList as $suKien): 
        ?>
            <option value="<?= $suKien->su_kien_id ?>" <?= $su_kien_id == $suKien->su_kien_id ? 'selected' : '' ?>><?= esc($suKien->ten_su_kien) ?></option>
        <?php endforeach; ?>
    </select>
    <?php if (isset($validation) && $validation->hasError('su_kien_id')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('su_kien_id') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="ho_ten" class="form-label">Họ tên <span class="text-danger">*</span></label>
    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ho_ten') ? 'is-invalid' : '' ?>" id="ho_ten" name="ho_ten" value="<?= $ho_ten ?>" required>
    <?php if (isset($validation) && $validation->hasError('ho_ten')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('ho_ten') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
    <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $email ?>" required>
    <?php if (isset($validation) && $validation->hasError('email')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('email') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="thoi_gian_check_out" class="form-label">Thời gian check-out <span class="text-danger">*</span></label>
    <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_check_out') ? 'is-invalid' : '' ?>" id="thoi_gian_check_out" name="thoi_gian_check_out" value="<?= $thoi_gian_check_out ?>" required>
    <?php if (isset($validation) && $validation->hasError('thoi_gian_check_out')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('thoi_gian_check_out') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="checkout_type" class="form-label">Loại check-out <span class="text-danger">*</span></label>
    <select class="form-select <?= isset($validation) && $validation->hasError('checkout_type') ? 'is-invalid' : '' ?>" id="checkout_type" name="checkout_type" required>
        <option value="manual" <?= $checkout_type == 'manual' ? 'selected' : '' ?>>Thủ công</option>
        <option value="face_id" <?= $checkout_type == 'face_id' ? 'selected' : '' ?>>Nhận diện khuôn mặt</option>
        <option value="qr_code" <?= $checkout_type == 'qr_code' ? 'selected' : '' ?>>Mã QR</option>
        <option value="auto" <?= $checkout_type == 'auto' ? 'selected' : '' ?>>Tự động</option>
        <option value="online" <?= $checkout_type == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
    </select>
    <?php if (isset($validation) && $validation->hasError('checkout_type')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('checkout_type') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="hinh_thuc_tham_gia" class="form-label">Hình thức tham gia <span class="text-danger">*</span></label>
    <select class="form-select <?= isset($validation) && $validation->hasError('hinh_thuc_tham_gia') ? 'is-invalid' : '' ?>" id="hinh_thuc_tham_gia" name="hinh_thuc_tham_gia" required>
        <option value="offline" <?= $hinh_thuc_tham_gia == 'offline' ? 'selected' : '' ?>>Trực tiếp</option>
        <option value="online" <?= $hinh_thuc_tham_gia == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
    </select>
    <?php if (isset($validation) && $validation->hasError('hinh_thuc_tham_gia')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('hinh_thuc_tham_gia') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="status" class="form-label">Trạng thái</label>
    <select class="form-select" id="status" name="status">
        <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
        <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Vô hiệu</option>
        <option value="2" <?= $status == 2 ? 'selected' : '' ?>>Đang xử lý</option>
    </select>
</div>

<div class="col-md-6">
    <label for="ma_xac_nhan" class="form-label">Mã xác nhận</label>
    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_xac_nhan') ? 'is-invalid' : '' ?>" id="ma_xac_nhan" name="ma_xac_nhan" value="<?= $ma_xac_nhan ?>">
    <?php if (isset($validation) && $validation->hasError('ma_xac_nhan')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('ma_xac_nhan') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="danh_gia" class="form-label">Đánh giá</label>
    <select class="form-select" id="danh_gia" name="danh_gia">
        <option value="">Chọn đánh giá</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>" <?= $danh_gia == $i ? 'selected' : '' ?>><?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?></option>
        <?php endfor; ?>
    </select>
</div>

<div class="col-md-12">
    <label for="noi_dung_danh_gia" class="form-label">Nội dung đánh giá</label>
    <textarea class="form-control" id="noi_dung_danh_gia" name="noi_dung_danh_gia" rows="3"><?= $noi_dung_danh_gia ?></textarea>
</div>

<div class="col-md-12">
    <label for="feedback" class="form-label">Phản hồi</label>
    <textarea class="form-control" id="feedback" name="feedback" rows="3"><?= $feedback ?></textarea>
</div>

<div class="col-md-12">
    <label for="ghi_chu" class="form-label">Ghi chú</label>
    <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="3"><?= $ghi_chu ?></textarea>
</div>

<div class="col-md-12 mt-3">
    <label for="thong_tin_bo_sung" class="form-label">Thông tin bổ sung (JSON)</label>
    <textarea class="form-control" id="thong_tin_bo_sung" name="thong_tin_bo_sung" rows="4"><?= $thong_tin_bo_sung ?></textarea>
    <small class="text-muted">Định dạng JSON, ví dụ: {"dien_thoai":"0123456789","dia_chi":"Hà Nội"}</small>
</div>

<div class="col-12 mt-3 d-flex justify-content-end gap-2">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="<?= site_url($module_name) ?>" class="btn btn-secondary">Quay lại</a>
</div> 