<?php
/**
 * @var string $search Current search term.
 * @var array $filters Current filter values.
 * @var array $searchFields Fields available for search.
 * @var array $filterFields Fields available for filtering (key => label or options).
 * @var string $moduleName Name of the module (e.g., 'bachoc').
 */
?>

<form action="<?= current_url() ?>" method="get" id="search-form" class="mb-3 border p-3 rounded bg-light">
    <div class="row g-2 align-items-end">
        <?php // --- Search Input --- ?>
        <div class="col-md-4">
            <label for="table-search" class="form-label">Tìm kiếm</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" class="form-control" id="table-search" name="search" value="<?= esc($search ?? '') ?>" placeholder="Nhập từ khóa...">
            </div>
            <?php if (!empty($searchFields)) : ?>
                <small class="form-text text-muted">
                    Tìm kiếm trong: <?= implode(', ', array_map('esc', $searchFields)) ?>
                </small>
            <?php endif; ?>
        </div>

        <?php // --- Filter Fields --- ?>
        <?php if (!empty($filterFields)) : ?>
            <?php foreach ($filterFields as $field => $options) : ?>
                <div class="col-md-3">
                    <label for="filter_<?= esc($field) ?>" class="form-label"><?= esc(is_array($options) ? ($options['label'] ?? ucfirst($field)) : ucfirst($field)) ?></label>
                    <?php if (is_array($options) && isset($options['options'])) : ?>
                        <select class="form-select form-select-sm" id="filter_<?= esc($field) ?>" name="filters[<?= esc($field) ?>]">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($options['options'] as $value => $label) : ?>
                                <option value="<?= esc($value) ?>" <?= (isset($filters[$field]) && (string)$filters[$field] === (string)$value) ? 'selected' : '' ?>><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else : ?>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bx bx-filter"></i></span>
                            <input type="text" class="form-control" id="filter_<?= esc($field) ?>" name="filters[<?= esc($field) ?>]" value="<?= esc($filters[$field] ?? '') ?>" placeholder="<?= esc(is_array($options) ? ($options['label'] ?? ucfirst($field)) : ucfirst($field)) ?>...">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php // --- Action Buttons --- ?>
        <div class="col-md-auto">
            <div class="d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bx bx-search"></i> Tìm kiếm
                </button>
                <a href="<?= current_url() ?>" class="btn btn-secondary btn-sm">
                    <i class="bx bx-reset"></i> Làm mới
                </a>
            </div>
        </div>
    </div>
</form>

<style>
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}
.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
.form-control:focus, .form-select:focus {
    border-color: #435ebe;
    box-shadow: 0 0 0 0.2rem rgba(67, 94, 190, 0.25);
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.btn i {
    margin-right: 0.25rem;
}
.form-text {
    font-size: 0.75rem;
    color: #6c757d;
}
</style>

<script>
$(document).ready(function() {
    // Xử lý nhấn Enter trong ô tìm kiếm
    $('#table-search').on('keyup', function(e) {
        if (e.key === 'Enter') {
            $('#search-form').submit();
        }
    });
});
</script>
