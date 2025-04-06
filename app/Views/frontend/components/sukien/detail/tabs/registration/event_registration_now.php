<?php if (service('authstudent')->isLoggedInStudent()): ?>
                <!-- Người dùng đã đăng nhập - Hiển thị nút đăng ký ngay -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-person-check-fill text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="mb-3">Bạn đã đăng nhập thành công</h4>
                        <p class="mb-4 text-muted">Bạn có thể đăng ký tham gia sự kiện ngay bây giờ mà không cần nhập thông tin</p>
                        <div class="d-grid gap-2 col-md-8 mx-auto">
                            <!-- Debug: ID sự kiện: <?= isset($event['su_kien_id']) ? $event['su_kien_id'] : 'Không có' ?> -->
                            <?php $userData = service('authstudent')->getUserData(); ?>
                            <form method="post" action="<?= base_url('/su-kien/register-now') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="su_kien_id" value="<?= isset($event['su_kien_id']) ? $event['su_kien_id'] : '' ?>">
                                <input type="hidden" name="ho_ten" value="<?= $userData ? ($userData->FullName ?? '') : '' ?>">
                                <input type="hidden" name="email" value="<?= $userData ? ($userData->Email ?? '') : '' ?>">
                                <input type="hidden" name="so_dien_thoai" value="<?= $userData ? ($userData->MobilePhone ?? '') : '' ?>">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-check-circle me-2"></i> Đăng ký ngay
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Người dùng chưa đăng nhập - Hiển thị nút đăng nhập -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-danger bg-gradient text-white py-3">
                        <h5 class="card-title mb-0 text-white"><i class="bi bi-person-plus-fill me-2"></i>Thông tin đăng ký</h5>
                    </div>
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <img src="<?= base_url('assets/images/login-illustration.svg') ?>" alt="Đăng nhập" class="img-fluid" style="max-height: 200px;">
                        </div>
                        <h4 class="mb-3">Đăng nhập để tiếp tục</h4>
                        <p class="mb-4 text-muted">Đăng nhập tài khoản để đăng ký tham gia sự kiện một cách nhanh chóng và thuận tiện hơn</p>
                        <div class="d-grid gap-2 col-md-8 mx-auto">
                            <a href="<?= base_url('login/nguoi-dung?redirect=' . current_url()) ?>" class="btn btn-danger btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập để tiếp tục
                            </a>
                            <a href="<?= base_url('dang-ky?redirect=' . current_url()) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-user-plus me-2"></i> Đăng ký tài khoản mới
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
<style>
/* Thêm màu đỏ đô tùy chỉnh */
:root {
    --bs-danger-rgb: 128, 0, 0;
}

.btn-danger {
    background-color: #800000;
    border-color: #800000;
}

.btn-danger:hover {
    background-color: #600000;
    border-color: #600000;
}

.text-danger {
    color: #800000 !important;
}
</style>