<?php
/**
 * Template horizontal cho form
 * Sử dụng layout Bootstrap 5 với label bên trái và trường nhập liệu bên phải
 */
?>
<?= $header_scripts ?? '' ?>

<?= $form_open ?>

<div class="row">
    <?= $fields_html ?>

    <div class="col-md-12 mt-4">
        <div class="offset-md-3 col-md-9">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="javascript:history.back()" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

<?= $form_close ?> 