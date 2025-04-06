<?php
// Đặt thông tin đơn vị tổ chức
$banToChuc = [];
if (isset($event['ban_to_chuc']) && !empty($event['ban_to_chuc'])) {
    if (is_string($event['ban_to_chuc'])) {
        $banToChuc = json_decode($event['ban_to_chuc'], true);
    } else {
        $banToChuc = $event['ban_to_chuc'];
    }
}

// Lấy thông tin người phụ trách từ lịch trình (trong trường hợp không có ban_to_chuc)
if (empty($banToChuc) && isset($event['lich_trinh']) && !empty($event['lich_trinh'])) {
    $lichTrinh = is_string($event['lich_trinh']) ? json_decode($event['lich_trinh'], true) : $event['lich_trinh'];
    
    if (is_array($lichTrinh)) {
        foreach ($lichTrinh as $item) {
            if (isset($item['nguoi_phu_trach']) && !empty($item['nguoi_phu_trach'])) {
                if (strtolower($item['nguoi_phu_trach']) === 'ban tổ chức') {
                    continue;
                }
                $banToChuc[] = [
                    'ten' => $item['nguoi_phu_trach'],
                    'chuc_vu' => isset($item['don_vi']) ? $item['don_vi'] : 'Ban tổ chức'
                ];
            }
        }
    }
}
?>

<div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.5s;">
    <div class="card-header text-white py-3">
        <h4 class="card-title mb-0"><i class="lni lni-graduation me-2"></i> Ban tổ chức</h4>
    </div>
    <div class="card-body">
        <div class="organizer-info">
            <!-- Đơn vị tổ chức chính -->
            <div class="text-center mb-3">
                <img src="<?= base_url('assets/images/hub-logo.png') ?>" alt="HUB Logo" class="img-fluid mb-3" style="max-height: 80px;">
                <h5 class="mb-0">
                    <?= isset($event['don_vi_to_chuc']) ? $event['don_vi_to_chuc'] : 'Trường Đại học Ngân hàng TP.HCM' ?>
                </h5>
                <p class="text-muted small mt-1 mb-0">
                    <?= isset($event['loai_su_kien']) ? $event['loai_su_kien'] : (isset($event['ten_loai_su_kien']) ? $event['ten_loai_su_kien'] : '') ?>
                </p>
            </div>
            
            <hr class="my-3">
            
            <!-- Danh sách người phụ trách/ban tổ chức -->
            <?php if(!empty($banToChuc)): ?>
                <div class="contact-info mb-3">
                    <h6 class="mb-3"><i class="lni lni-user me-2 text-primary"></i> Người phụ trách</h6>
                    <?php foreach($banToChuc as $nguoi): ?>
                        <div class="mb-2">
                            <p class="mb-0 fw-bold"><?= esc($nguoi['ten'] ?? '') ?></p>
                            <p class="text-muted small mb-0"><?= esc($nguoi['chuc_vu'] ?? '') ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Thông tin liên hệ -->
            <div class="contact-info mt-3">
                <h6 class="mb-3"><i class="lni lni-phone me-2 text-primary"></i> Thông tin liên hệ</h6>
                <?php if(isset($event['dia_chi_cu_the']) && !empty($event['dia_chi_cu_the'])): ?>
                    <p class="mb-2"><i class="lni lni-map-marker me-2 text-secondary"></i> <?= esc($event['dia_chi_cu_the']) ?></p>
                <?php else: ?>
                    <p class="mb-2"><i class="lni lni-map-marker me-2 text-secondary"></i> 36 Tôn Thất Đạm, Quận 1, TP.HCM</p>
                <?php endif; ?>
                
                <?php if(isset($event['so_dien_thoai_lien_he']) && !empty($event['so_dien_thoai_lien_he'])): ?>
                    <p class="mb-2"><i class="lni lni-phone me-2 text-secondary"></i> <?= esc($event['so_dien_thoai_lien_he']) ?></p>
                <?php else: ?>
                    <p class="mb-2"><i class="lni lni-phone me-2 text-secondary"></i> (028) 38 212 593</p>
                <?php endif; ?>
                
                <?php if(isset($event['email_lien_he']) && !empty($event['email_lien_he'])): ?>
                    <p class="mb-0"><i class="lni lni-envelope me-2 text-secondary"></i> <?= esc($event['email_lien_he']) ?></p>
                <?php else: ?>
                    <p class="mb-0"><i class="lni lni-envelope me-2 text-secondary"></i> info@hub.edu.vn</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>