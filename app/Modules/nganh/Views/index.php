<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= nganh_css('table') ?>
<style>
.status-badge {
    min-width: 80px;
}
</style>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH NGÀNH<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách ngành',
	'dashboard_url' => site_url('nganh/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Ngành', 'url' => site_url('nganh')],
		['title' => 'Danh sách', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/nganh/new'), 'title' => 'Thêm mới', 'icon' => 'bx bx-plus-circle']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-12 col-sm-6">
                        <h5 class="card-title mb-0">Danh sách ngành</h5>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="table-search" placeholder="Tìm kiếm...">
                            <button class="btn btn-outline-primary" type="button" id="search-btn">
                                <i class="bx bx-search"></i>
                            </button>
                            <button class="btn btn-outline-secondary" type="button" id="refresh-table" data-bs-toggle="tooltip" title="Làm mới">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->has('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="40px">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                    </div>
                                </th>
                                <th width="80px">Mã</th>
                                <th>Tên ngành</th>
                                <th>Phòng/Khoa</th>
                                <th width="100px" class="text-center">Trạng thái</th>
                                <th width="100px" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($nganh)) : ?>
                                <?php foreach ($nganh as $item) : ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input checkbox-item" type="checkbox" value="<?= $item->nganh_id ?>">
                                            </div>
                                        </td>
                                        <td><?= esc($item->ma_nganh) ?></td>
                                        <td><?= esc($item->ten_nganh) ?></td>
                                        <td>
                                            <?= $item->getPhongKhoaInfo() ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $item->getStatusLabel() ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="<?= site_url("nganh/view/{$item->nganh_id}") ?>" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                    <i class="bx bx-info-circle"></i>
                                                </a>
                                                <a href="<?= site_url("nganh/edit/{$item->nganh_id}") ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Sửa">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                                        data-id="<?= $item->nganh_id ?>" 
                                                        data-name="<?= esc($item->ten_nganh) ?>"
                                                        data-bs-toggle="tooltip" title="Xóa">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Hiển thị <span id="total-records"><?= count($nganh ?? []) ?></span> bản ghi
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-danger" id="delete-selected" disabled>
                            <i class="bx bx-trash me-1"></i> Xóa đã chọn
                        </button>
                        <button type="button" class="btn btn-sm btn-success" id="status-selected" disabled>
                            <i class="bx bx-toggle-right me-1"></i> Đổi trạng thái
                        </button>
                        <a href="<?= site_url('nganh/listdeleted') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-trash-alt me-1"></i> Thùng rác
                        </a>
                    </div>
                </div>
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
                <p>Bạn có chắc chắn muốn xóa ngành "<span id="delete-item-name" class="fw-bold"></span>"?</p>
                <p class="text-danger mb-0"><small>* Dữ liệu sẽ được chuyển vào thùng rác và có thể khôi phục.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('', ['id' => 'delete-form']) ?>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa nhiều</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa <span id="selected-count" class="fw-bold"></span> ngành đã chọn?</p>
                <p class="text-danger mb-0"><small>* Dữ liệu sẽ được chuyển vào thùng rác và có thể khôi phục.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('nganh/deleteMultiple', ['id' => 'form-delete-multiple']) ?>
                    <button type="submit" class="btn btn-danger" id="confirm-delete-multiple">Xóa</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận đổi trạng thái nhiều -->
<div class="modal fade" id="statusMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn thay đổi trạng thái của <span id="status-count" class="fw-bold"></span> ngành đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('nganh/statusMultiple', ['id' => 'form-status-multiple']) ?>
                    <button type="submit" class="btn btn-success" id="confirm-status-multiple">Xác nhận</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = '<?= site_url() ?>';
</script>
<?= $this->endSection() ?> 