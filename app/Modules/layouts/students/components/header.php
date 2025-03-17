<header id="header" class="d-flex align-items-center">
    <div class="container-fluid px-3">
        <div class="row w-100 align-items-center">
            <!-- Mobile Menu Toggle -->
            <div class="col-auto d-lg-none">
                <button class="btn mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <!-- Logo -->
            <div class="col">
                <div class="logo">
                    <h1 class="m-0 fs-4">
                        <a href="<?= site_url('admin/dashboard') ?>" class="text-decoration-none text-dark">
                            <i class="fas fa-tachometer-alt me-2"></i>CMS Admin
                        </a>
                    </h1>
                </div>
            </div>
            
            <!-- User Menu -->
            <div class="col-auto">
                <div class="dropdown">
                    <button class="btn dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="User">
                        <span class="d-none d-md-inline">Admin</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header> 