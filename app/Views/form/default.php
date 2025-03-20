<?php
/**
 * Template mặc định cho form
 * Sử dụng layout cơ bản với Bootstrap 5
 */
?>
<?= $header_scripts ?? '' ?>

<?= $form_open ?>

<?= $fields_html ?>

<div class="form-group mt-4">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
</div>

<?= $form_close ?> 