<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/thamgiasukien';
$module_name = isset($module_name) ? $module_name : 'thamgiasukien';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\thamgiasukien\Libraries\MasterScript($route_url, $module_name);
?>
<?= $masterScript->pageCss('view') ?>
<?= $masterScript->pageSectionCss('modal') ?>
<?= $this->endSection() ?>

<?= $this->section('title') ?>CHI TIẾT THAM GIA SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Chi tiết tham gia sự kiện',
	'dashboard_url' => site_url($route_url),
	'breadcrumbs' => [
		['title' => 'Quản lý tham gia sự kiện', 'url' => site_url($route_url)],
		['title' => 'Chi tiết', 'active' => true]
	],
	'actions' => [
		['url' => site_url($route_url . '/edit/' . $data->tham_gia_su_kien_id), 'title' => 'Chỉnh sửa', 'icon' => 'bx bx-edit'],
		['url' => site_url($route_url), 'title' => 'Quay lại', 'icon' => 'bx bx-left-arrow-alt']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chi tiết tham gia sự kiện ID: <?= esc($data->tham_gia_su_kien_id) ?></h5>
        <div class="d-flex gap-2">
            <a href="<?= site_url($route_url . '/edit/' . $data->tham_gia_su_kien_id) ?>" class="btn btn-sm btn-primary">
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
                        <td><?= esc($data->tham_gia_su_kien_id) ?></td>
                    </tr>
                    <tr>
                        <th>Người dùng</th>
                        <td>
                            <?php if (isset($data->nguoi_dung) && !empty($data->nguoi_dung)): ?>
                                <?= esc($data->nguoi_dung->ho_ten) ?>
                                <?php if (!empty($data->nguoi_dung->email)): ?>
                                    (<?= esc($data->nguoi_dung->email) ?>)
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">ID: <?= esc($data->nguoi_dung_id) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Sự kiện</th>
                        <td>
                            <?php if (isset($data->su_kien) && !empty($data->su_kien)): ?>
                                <?= esc($data->su_kien->ten_su_kien) ?>
                                <?php if (!empty($data->su_kien->mo_ta_su_kien)): ?>
                                    <br>
                                    <small class="text-muted"><?= esc($data->su_kien->mo_ta_su_kien) ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">ID: <?= esc($data->su_kien_id) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Thời gian điểm danh</th>
                        <td><?= !empty($data->thoi_gian_diem_danh) ? date('d/m/Y H:i:s', strtotime($data->thoi_gian_diem_danh)) : 'Chưa điểm danh' ?></td>
                    </tr>
                    <tr>
                        <th>Phương thức điểm danh</th>
                        <td>
                            <?php if ($data->phuong_thuc_diem_danh == 'qr_code'): ?>
                                <span class="badge bg-info">QR Code</span>
                            <?php elseif ($data->phuong_thuc_diem_danh == 'face_id'): ?>
                                <span class="badge bg-primary">Face ID</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Thủ công</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ghi chú</th>
                        <td><?= !empty($data->ghi_chu) ? nl2br(esc($data->ghi_chu)) : '<em>Không có ghi chú</em>' ?></td>
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
                        <th>Ngày tạo</th>
                        <td><?= !empty($data->created_at) ? date('d/m/Y H:i:s', strtotime($data->created_at)) : 'N/A' ?></td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td><?= !empty($data->updated_at) ? date('d/m/Y H:i:s', strtotime($data->updated_at)) : 'N/A' ?></td>
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
                Bạn có chắc chắn muốn xóa tham gia sự kiện ID: <strong><?= esc($data->tham_gia_su_kien_id) ?></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url($route_url . '/delete/' . $data->tham_gia_su_kien_id) ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= $masterScript->pageJs('view') ?>
<?= $this->endSection() ?>
