<?php
/**
 * Component chứa các modal dialog cho trang danh sách đã xóa
 */
?>

<!-- Modal Xác nhận khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn khôi phục bậc học: <span id="restore-item-name" class="fw-bold"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="restore-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success">Khôi phục</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận khôi phục nhiều -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-labelledby="restoreMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreMultipleModalLabel">Xác nhận khôi phục nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn khôi phục <span id="restore-count" class="fw-bold"></span> mục đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-restore-multiple" class="btn btn-success">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn bậc học: <span id="delete-item-name" class="fw-bold"></span>?</p>
                <p class="text-danger mb-0"><i class="bx bx-error-circle"></i> Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="delete-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-labelledBy="deleteMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMultipleModalLabel">Xác nhận xóa vĩnh viễn nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn <span id="selected-count" class="fw-bold"></span> mục đã chọn?</p>
                <p class="text-danger mb-0"><i class="bx bx-error-circle"></i> Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading indicator (hidden by default) -->
<div id="loading-indicator" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Đang tải...</span>
    </div>
</div> 