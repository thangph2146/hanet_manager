<div class="card-header bg-white">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><?= $title ?></h5>
        <div>
            <div>
                <form id="form-delete-multiple" action="<?= site_url($module_name . '/deleteMultiple') ?>" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="button" id="delete-selected-multiple" class="btn btn-danger" disabled>
                        <i class="bx bx-trash"></i> Xóa mục đã chọn
                    </button>
                </form>
                <a href="<?= site_url($module_name . '/exportExcel') ?>" class="btn btn-outline-secondary">
                    Xuất excel
                </a>
                <a href="<?= site_url($module_name . '/exportPdf') ?>" class="btn btn-outline-secondary">
                    Xuất pdf
                </a>
                <a href="<?= site_url($module_name . '/listdeleted') ?>" class="btn btn-outline-secondary">
                    <i class="bx bx-trash"></i> Thùng rác
                </a>
                <a href="<?= site_url($module_name . '/new') ?>" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Thêm mới
                </a>
            </div>
        </div>
    </div>
</div> 