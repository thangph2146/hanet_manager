<?= $this->extend('layouts/frontend') ?>

<?= $this->section('title') ?>Đặt lại mật khẩu<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php helper('App\Modules\nguoidung\Helpers\session'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Đặt lại mật khẩu</h4>
                </div>
                <div class="card-body">
                    <?php if (nguoidung_session_has('error')) : ?>
                        <div class="alert alert-danger">
                            <?= nguoidung_session_get('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (nguoidung_session_has('success')) : ?>
                        <div class="alert alert-success">
                            <?= nguoidung_session_get('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (nguoidung_session_has('info')) : ?>
                        <div class="alert alert-info">
                            <?= nguoidung_session_get('info') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (nguoidung_session_has('warning')) : ?>
                        <div class="alert alert-warning">
                            <?= nguoidung_session_get('warning') ?>
                        </div>
                    <?php endif; ?>

                    <p class="mb-4">Vui lòng nhập mật khẩu mới của bạn.</p>

                    <form action="<?= base_url('nguoidung/reset-password/' . $token) ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control <?= (isset(nguoidung_session_get('errors')['password'])) ? 'is-invalid' : '' ?>" 
                                id="password" name="password" required>
                            <?php if (isset(nguoidung_session_get('errors')['password'])) : ?>
                                <div class="invalid-feedback">
                                    <?= nguoidung_session_get('errors')['password'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control <?= (isset(nguoidung_session_get('errors')['password_confirm'])) ? 'is-invalid' : '' ?>" 
                                id="password_confirm" name="password_confirm" required>
                            <?php if (isset(nguoidung_session_get('errors')['password_confirm'])) : ?>
                                <div class="invalid-feedback">
                                    <?= nguoidung_session_get('errors')['password_confirm'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('nguoidung/login') ?>">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 