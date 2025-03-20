<?php
/**
 * Template inline cho form
 * Sử dụng layout inline của Bootstrap 5 cho form compact
 */
?>
<?= $header_scripts ?? '' ?>

<?= $form_open ?>

<div class="d-flex align-items-center flex-wrap gap-2">
    <?= $fields_html ?>

    <div class="ms-auto">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
    </div>
</div>

<?= $form_close ?> 