<?= $this->extend('admin/layout/layout') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
<?= $breadcrumb ?>
<?= $this->endSection() ?>

<?= $this->section('header') ?>
<h1>
    <?= $title ?>
    <small><?= $event->getTenSuKien() ?></small>
</h1>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-title">Danh sách người tham gia sự kiện</h3>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <a href="<?= site_url($module_name . '/detail/' . $event->getId()) ?>" class="btn btn-default mr-2">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="<?= site_url($module_name . '/exportParticipants/' . $event->getId()) ?>" class="btn btn-success">
                        <i class="fas fa-file-export"></i> Xuất Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter Form -->
        <form action="<?= site_url($module_name . '/participants/' . $event->getId()) ?>" method="get" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" value="<?= $params['search'] ?? '' ?>" class="form-control" placeholder="Tìm kiếm...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="filter" class="form-control">
                        <option value="all" <?= ($params['filter'] == 'all') ? 'selected' : '' ?>>Tất cả</option>
                        <option value="checked_in" <?= ($params['filter'] == 'checked_in') ? 'selected' : '' ?>>Đã check-in</option>
                        <option value="checked_out" <?= ($params['filter'] == 'checked_out') ? 'selected' : '' ?>>Đã check-out</option>
                        <option value="not_checked_in" <?= ($params['filter'] == 'not_checked_in') ? 'selected' : '' ?>>Chưa check-in</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="perPage" class="form-control">
                        <option value="15" <?= ($params['perPage'] == 15) ? 'selected' : '' ?>>15 mục</option>
                        <option value="30" <?= ($params['perPage'] == 30) ? 'selected' : '' ?>>30 mục</option>
                        <option value="50" <?= ($params['perPage'] == 50) ? 'selected' : '' ?>>50 mục</option>
                        <option value="100" <?= ($params['perPage'] == 100) ? 'selected' : '' ?>>100 mục</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Áp dụng</button>
                </div>
            </div>
        </form>

        <!-- Thống kê -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Tổng đăng ký</span>
                        <span class="info-box-number"><?= $event->getTongDangKy() ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Đã check-in</span>
                        <span class="info-box-number"><?= $event->getTongCheckIn() ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-sign-out-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Đã check-out</span>
                        <span class="info-box-number"><?= $event->getTongCheckOut() ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Chưa check-in</span>
                        <span class="info-box-number"><?= $event->getTongDangKy() - $event->getTongCheckIn() ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách người tham gia -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Thời gian đăng ký</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($participants)): ?>
                        <?php $i = ($params['page'] - 1) * $params['perPage']; ?>
                        <?php foreach ($participants as $item): ?>
                            <tr>
                                <td><?= ++$i ?></td>
                                <td><?= $item['ho_ten'] ?? 'N/A' ?></td>
                                <td><?= $item['email'] ?? 'N/A' ?></td>
                                <td><?= $item['so_dien_thoai'] ?? 'N/A' ?></td>
                                <td><?= date('d/m/Y H:i:s', strtotime($item['created_at'])) ?></td>
                                <td>
                                    <?php if (!empty($item['check_in_time'])): ?>
                                        <span class="badge badge-success">
                                            <?= date('d/m/Y H:i:s', strtotime($item['check_in_time'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Chưa check-in</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($item['thoi_gian_check_out'])): ?>
                                        <span class="badge badge-info">
                                            <?= date('d/m/Y H:i:s', strtotime($item['thoi_gian_check_out'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Chưa check-out</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= site_url('dangkysukien/detail/' . $item['dangky_sukien_id']) ?>" class="btn btn-sm btn-info" title="Chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (empty($item['check_in_time']) && $event->isAllowManualCheckin()): ?>
                                            <a href="<?= site_url('checkinsukien/manual/' . $item['dangky_sukien_id']) ?>" class="btn btn-sm btn-success" title="Check-in thủ công">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="mt-4">
            <?= $pager->links('default', 'bootstrap4') ?>
            <p class="text-muted">Hiển thị <?= count($participants) ?> / <?= $totalParticipants ?> bản ghi</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 