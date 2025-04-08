<?php
/**
 * Nguoi Dung dropdown component
 * Hiển thị dropdown menu cho người dùng đã đăng nhập
 */

/**
 * @param array $data [
 *   'avatar' => string (Optional) - Đường dẫn ảnh đại diện, mặc định là 'assets/images/avatars/default.jpg'
 *   'username' => string - Tên người dùng hiển thị
 *   'menu_groups' => array - Các nhóm menu trong dropdown [
 *     [
 *       'title' => string - Tiêu đề nhóm
 *       'actions' => array - Các hành động trong nhóm [
 *         [
 *           'url' => string - Đường dẫn
 *           'icon' => string - Tên icon (sử dụng FontAwesome, không cần 'fa-' prefix)
 *           'title' => string - Văn bản hiển thị
 *           'type' => string (Optional) - Loại hành động (ví dụ: 'danger' để hiển thị màu đỏ)
 *         ],
 *         ...
 *       ]
 *     ],
 *     ...
 *   ]
 * ]
 */


// Debug - Hiển thị cấu trúc dữ liệu menu_groups
// echo '<pre>'; print_r($menu_groups); echo '</pre>';
?>

<style>
/* CSS cho user dropdown */
.user-dropdown-container {
    position: relative;
    background-color: #fff;
    border-radius: 8px;
    padding: 0.25rem 0.5rem;
}

.user-dropdown {
    display: flex;
    align-items: center;
    text-decoration: none;
    padding: 0.25rem;
    transition: all 0.2s ease;
}

.user-avatar {
    position: relative;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #25D366;
    transition: all 0.2s ease;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #25D366;
    border: 1px solid #fff;
}

.user-name {
    font-weight: 500;
    font-size: 0.9rem;
    color: #333;
    white-space: nowrap;
}

/* Dropdown menu styling */
.user-menu {
    margin-top: 0.15rem !important;
    min-width: 200px;
    padding: 0.5rem 0.5rem;
    border: none;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    animation: menuFadeIn 0.2s ease;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

@keyframes menuFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-header {
    background-color: rgba(0, 0, 0, 0.02);
    padding: 1rem;
    border-radius: 10px 10px 0 0;
}

.dropdown-header .user-avatar {
    width: 45px;
    height: 45px;
    border-color: rgba(0, 0, 0, 0.1);
}

.dropdown-header-text {
    display: block;
    color: #6c757d;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 0.5rem 1rem 0.25rem;
    font-weight: 600;
}

.user-menu-section {
    margin-bottom: 0.25rem;
}

.dropdown-item {
    padding: 0.75rem 1rem;
    color: #333;
    font-size: 0.9rem;
    border-radius: 0;
    display: flex;
    align-items: center;
    transition: all 0.15s ease;
    border-radius: 8px;
}

.dropdown-item i {
    width: 20px;
    margin-right: 10px;
    font-size: 0.85rem;
    text-align: center;
    opacity: 0.8;
}

.dropdown-divider {
    margin: 0;
    opacity: 0.1;
}

.text-danger {
    color: #dc3545 !important;
}
</style>

<div class="dropdown user-dropdown-container">
    <a href="#" class="user-dropdown" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="user-avatar me-2">
            <?php 
            try {
                // Lấy đường dẫn avatar từ người dùng hiện tại
                $authnguoidung = service('authnguoidung');
                $nguoi_dung = $authnguoidung->getCurrentNguoiDung();
                $avatarUrl = $nguoi_dung->getAvatarUrl();
            } catch (Exception $e) {
                log_message('error', 'Lỗi lấy avatar: ' . $e->getMessage());
                $avatarUrl = base_url('assets/images/avatars/default.jpg');
            }
            ?>
            <img src="<?= $avatarUrl ?>" alt="Avatar" onerror="this.src='<?= base_url('assets/images/avatars/default.jpg') ?>'">
            <span class="user-status"></span>
        </div>
        <div class="user-name">
            <?php
            // Sử dụng service authnguoidung để lấy tên người dùng
            try {
                $authnguoidung = service('authnguoidung');
                if ($authnguoidung->isLoggedInStudent()) {
                    $nguoi_dung = $authnguoidung->getCurrentNguoiDung();
                    echo esc($nguoi_dung->getFullName());
                } else {
                    echo $username ?? 'Người dùng';
                }
            } catch (Exception $e) {
                log_message('error', 'Lỗi hiển thị tên người dùng: ' . $e->getMessage());
                echo $username ?? 'Người dùng';
            }
            ?>
        </div>
    </a>
    
    <div class="dropdown-menu user-menu" aria-labelledby="user-dropdown">
        <?php if (empty($menu_groups)): ?>
            <div class="p-3 text-center text-muted">
                <small>Không có menu</small>
            </div>
        <?php else: ?>
            <?php foreach($menu_groups as $group): ?>   
                <div class="user-menu-section">
                    <?php if (isset($group['title'])): ?>
                        <small class="dropdown-header-text"><?= $group['title'] ?></small>
                    <?php endif; ?>
                    <?php if (isset($group['actions']) && is_array($group['actions'])): ?>
                        <?php foreach($group['actions'] as $action): ?>
                            <a class="dropdown-item <?= isset($action['type']) && $action['type'] == 'danger' ? 'text-danger' : '' ?>" 
                               href="<?= base_url($action['url'] ?? '#') ?>">
                                <i class="fas fa-<?= $action['icon'] ?? 'circle' ?>"></i>
                                <span><?= $action['title'] ?? 'Menu Item' ?></span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
