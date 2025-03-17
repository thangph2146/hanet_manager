<aside id="sidebar">
    <div class="p-3">
        <!-- Brand Logo (for small screens) -->
        <div class="logo d-flex align-items-center d-lg-none mb-4">
            <h2 class="text-white fs-4 m-0">CMS Admin</h2>
        </div>
        
        <!-- User Info -->
        <div class="user-panel d-flex align-items-center mb-4">
            <div class="image">
                <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="User Image">
            </div>
            <div class="info">
                <div class="text-white">Admin</div>
                <small class="text-light">Quản trị viên</small>
            </div>
        </div>
        
        <!-- Search -->
        <div class="search-form mb-4">
            <form action="#" method="get">
                <div class="input-group">
                    <input class="form-control form-control-sm" type="search" placeholder="Tìm kiếm...">
                    <button class="btn btn-sm btn-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Sidebar Menu -->
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="<?= site_url('admin/dashboard') ?>" class="nav-link text-white">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-white" data-bs-toggle="collapse" href="#menuPosts" role="button">
                        <i class="fas fa-newspaper me-2"></i>
                        Bài viết
                        <i class="fas fa-angle-down ms-auto"></i>
                    </a>
                    <div class="collapse" id="menuPosts">
                        <ul class="nav flex-column ms-3 mt-2">
                            <li class="nav-item">
                                <a href="<?= site_url('admin/posts') ?>" class="nav-link text-white-50">
                                    <i class="fas fa-list me-2"></i>Danh sách
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('admin/posts/create') ?>" class="nav-link text-white-50">
                                    <i class="fas fa-plus me-2"></i>Thêm mới
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= site_url('admin/categories') ?>" class="nav-link text-white-50">
                                    <i class="fas fa-tags me-2"></i>Danh mục
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a href="<?= site_url('admin/media') ?>" class="nav-link text-white">
                        <i class="fas fa-photo-video me-2"></i>
                        Thư viện media
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= site_url('admin/users') ?>" class="nav-link text-white">
                        <i class="fas fa-users me-2"></i>
                        Người dùng
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= site_url('admin/settings') ?>" class="nav-link text-white">
                        <i class="fas fa-cog me-2"></i>
                        Cài đặt
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside> 