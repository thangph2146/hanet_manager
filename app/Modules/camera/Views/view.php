<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/camera';
$route_url_php = $route_url;
include __DIR__ . '/master_scripts.php'; 
?>
<?= page_css('view', $route_url) ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT CAMERA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết camera',
    'dashboard_url' => site_url($route_url),
    'breadcrumbs' => [
        ['title' => 'Quản lý camera', 'url' => site_url($route_url)],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url($route_url), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-12 mb-3">
                <h4 class="card-title mb-0">Chi tiết camera</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> <?= esc($data->getId()) ?></p>
                <p><strong>Tên camera:</strong> <?= esc($data->getTenCamera()) ?></p>
                <p><strong>Mã camera:</strong> <?= esc($data->getMaCamera()) ?></p>
                <p><strong>IP camera:</strong> <?= esc($data->getIpCamera()) ?></p>
                <p><strong>Port:</strong> <?= esc($data->getPort()) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Trạng thái:</strong> <span class="badge <?= $data->isActive() ? 'bg-success' : 'bg-danger' ?>"><?= $data->getStatusLabel() ?></span></p>
                <p><strong>Ngày tạo:</strong> <?= $data->getCreatedAt() ? $data->getCreatedAt()->format('d/m/Y H:i:s') : 'N/A' ?></p>
                <p><strong>Cập nhật lần cuối:</strong> <?= $data->getUpdatedAt() ? $data->getUpdatedAt()->format('d/m/Y H:i:s') : 'N/A' ?></p>
            </div>
        </div>
        <hr>
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-end">
                <a href="<?= site_url($route_url . '/edit/' . $data->getId()) ?>" class="btn btn-primary me-2">
                    <i class="bx bx-edit"></i> Chỉnh sửa
                </a>
                <button type="button" class="btn btn-danger btn-delete" 
                        data-id="<?= $data->getId() ?>" 
                        data-name="ID: <?= esc($data->getId()) ?>"
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal">
                    <i class="bx bx-trash"></i> Xóa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa camera:</p>
                <p class="text-center fw-bold" id="delete-item-name"><?= esc($data->getTenCamera()) ?></p>
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-info-circle me-1"></i> Dữ liệu sẽ được chuyển vào thùng rác và có thể khôi phục.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open(site_url($route_url . '/delete/' . $data->getId()), ['id' => 'delete-form']) ?>
                <input type="hidden" name="return_url" value="<?= site_url($route_url) ?>">
                <button type="submit" class="btn btn-danger">Xóa</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    // Handle showing delete modal
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#delete-item-name').text(name);
        $('#delete-form').attr('action', '<?= site_url($route_url . "/delete/") ?>' + id);
        $('#deleteModal').modal('show');
    });
});
</script>
<?= $this->endSection() ?>
