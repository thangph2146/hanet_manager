<!-- Modal Khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn khôi phục <?= $title ?> ID: "<span id="restore-item-id" class="fw-bold"></span>" - "<span id="restore-item-name" class="fw-bold"></span>" không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="restore-form" action="" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="return_url" value="<?= current_url() . '?' . $_SERVER['QUERY_STRING'] ?>">
                    <button type="submit" class="btn btn-success">Khôi phục</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xóa vĩnh viễn -->
<div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa vĩnh viễn <?= $title ?> ID: "<span id="delete-item-id" class="fw-bold"></span>" - "<span id="delete-item-name" class="fw-bold"></span>" không? Hành động này không thể hoàn tác!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="<?= site_url($module_name . '/permanentDelete/' ) ?>" method="post" class="d-inline force-delete-form">
                    <?= csrf_field() ?>
                    <input type="hidden" name="return_url" value="<?= current_url() . '?' . $_SERVER['QUERY_STRING'] ?>">
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal khôi phục nhiều mục -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-labelledby="restoreMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreMultipleModalLabel">Xác nhận khôi phục nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn khôi phục các <?= $title ?> đã chọn không?
                <div id="restore-multiple-count" class="text-primary mt-2"></div>
                <div id="restore-multiple-ids" class="text-muted small mt-1"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-success" id="confirm-restore-multiple">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xóa vĩnh viễn nhiều mục -->
<div class="modal fade" id="permanentDeleteMultipleModal" tabindex="-1" aria-labelledby="permanentDeleteMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permanentDeleteMultipleModalLabel">Xác nhận xóa vĩnh viễn nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle me-1"></i> Cảnh báo: Hành động này sẽ xóa vĩnh viễn các <?= $title ?> đã chọn và không thể khôi phục!
                </div>
                <div id="permanent-delete-multiple-count" class="text-danger mt-2"></div>
                <div id="permanent-delete-multiple-ids" class="text-muted small mt-1"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-permanent-delete-multiple">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>

<!-- Form ẩn cho xử lý khôi phục nhiều item -->
<form id="form-restore-multiple" action="<?= site_url($module_name . '/restoreMultiple') ?>" method="post" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="return_url" value="<?= current_url() . '?' . $_SERVER['QUERY_STRING'] ?>">
</form>

<!-- Form ẩn cho xử lý xóa vĩnh viễn nhiều item -->
<form id="form-permanent-delete-multiple" action="<?= site_url($module_name . '/permanentDeleteMultiple') ?>" method="post" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="return_url" value="<?= current_url() . '?' . $_SERVER['QUERY_STRING'] ?>">
</form> 