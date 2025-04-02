<?php
/**
 * Form xem chi tiết loại sự kiện
 * @var LoaiSuKien $entity Đối tượng loại sự kiện
 */
?>

<style>
.detail-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.detail-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.detail-card .card-header {
    border-bottom: 0;
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
    padding: 1rem 1.5rem;
}

.detail-card .card-body {
    padding: 1.5rem;
}

.detail-label {
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    padding: 0.75rem;
    background-color: #f8f9fa;
    border-radius: 6px;
    min-height: 2.8rem;
    display: flex;
    align-items: center;
}

.detail-table th {
    width: 30%;
    font-weight: 600;
    color: #6c757d;
}
</style>

<div class="row">
    <!-- Thông tin loại sự kiện -->
    <div class="col-12">
        <div class="card detail-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bx bx-info-circle me-2"></i> Thông tin loại sự kiện
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td><?= $entity->getId() ?></td>
                        </tr>
                        <tr>
                            <th>Tên loại sự kiện</th>
                            <td><?= esc($entity->getTenLoaiSuKien()) ?></td>
                        </tr>
                        <tr>
                            <th>Mã loại sự kiện</th>
                            <td><?= esc($entity->getMaLoaiSuKien()) ?></td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <?php if ($entity->getStatus() == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Không hoạt động</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Thông tin thời gian -->
    <div class="col-12">
        <div class="card detail-card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bx bx-time-five me-2"></i> Thông tin thời gian
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless detail-table">
                    <tbody>
                        <tr>
                            <th>Ngày tạo</th>
                            <td><?= $entity->getCreatedAt() ? $entity->getCreatedAt()->toDateTimeString() : '-' ?></td>
                        </tr>
                        <tr>
                            <th>Cập nhật lần cuối</th>
                            <td><?= $entity->getUpdatedAt() ? $entity->getUpdatedAt()->toDateTimeString() : '-' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 