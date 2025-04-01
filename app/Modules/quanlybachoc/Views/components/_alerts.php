<?php
/**
 * Component hiển thị thông báo lỗi và thành công
 */
?>

<?php if (session()->has('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?> 