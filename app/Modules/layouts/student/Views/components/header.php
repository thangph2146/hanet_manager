<!--start header -->
<header class="top-header">
    <nav class="navbar navbar-expand gap-3">
        <div class="mobile-menu-button">
            <i class="bi bi-list"></i>
        </div>
        <form class="searchbar">
            <div class="position-absolute top-50 translate-middle-y search-icon ms-3">
                <i class="bi bi-search"></i>
            </div>
            <input class="form-control" type="text" placeholder="Tìm kiếm sự kiện...">
            <div class="position-absolute top-50 translate-middle-y search-close-icon">
                <i class="bi bi-x-lg"></i>
            </div>
        </form>
        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown dropdown-large">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                        <div class="notifications">
                            <span class="notify-badge">8</span>
                            <i class="bi bi-bell-fill"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-0">
                        <div class="p-2 border-bottom m-2">
                            <h5 class="h5 mb-0">Thông báo</h5>
                        </div>
                        <div class="notification-list">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <a class="dropdown-item py-2" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="notification-icon bg-primary text-white">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <div class="notification-text ms-4">
                                        <p class="mb-0">Sự kiện mới đã được thêm vào</p>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime("-$i day")) ?></small>
                                    </div>
                                </div>
                            </a>
                            <?php endfor; ?>
                        </div>
                        <div class="p-2">
                            <div class="d-grid">
                                <a class="btn btn-sm btn-outline-primary" href="#">Xem tất cả thông báo</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <?php include('user_dropdown.php'); ?>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--end header --> 