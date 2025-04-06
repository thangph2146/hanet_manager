<div class="event-speakers mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
    <h3>Diễn giả</h3>
    <?php
    // Danh sách người phụ trách từ lịch trình
    $nguoi_phu_trach = [];
    
    // Lấy thông tin diễn giả từ biến event
    $speakers = isset($event['dien_gia']) ? $event['dien_gia'] : [];
    
    // Kiểm tra xem $speakers có phải là chuỗi JSON không
    if (is_string($speakers) && !empty($speakers)) {
        // Thử giải mã JSON
        $decoded = json_decode($speakers, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $speakers = $decoded;
        } else {
            // Nếu không phải JSON hợp lệ, gán mảng rỗng để tránh lỗi
            $speakers = [];
        }
    }
    
    // Nếu không có dữ liệu diễn giả và có lịch trình, lấy người phụ trách từ lịch trình
    if (empty($speakers) && isset($event['lich_trinh']) && !empty($event['lich_trinh'])) {
        $lich_trinh = $event['lich_trinh'];
        
        // Kiểm tra xem $lich_trinh có phải là chuỗi JSON không
        if (is_string($lich_trinh) && !empty($lich_trinh)) {
            // Thử giải mã JSON
            $decoded = json_decode($lich_trinh, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $lich_trinh = $decoded;
            }
        }
        
        // Nếu lịch trình là mảng, lấy danh sách người phụ trách
        if (is_array($lich_trinh)) {
            foreach ($lich_trinh as $item) {
                if (!empty($item['nguoi_phu_trach']) && !in_array($item['nguoi_phu_trach'], $nguoi_phu_trach)) {
                    // Loại trừ người phụ trách là "Ban tổ chức"
                    if (strtolower($item['nguoi_phu_trach']) != 'ban tổ chức' && 
                        strtolower($item['nguoi_phu_trach']) != 'ban to chuc') {
                        $nguoi_phu_trach[] = $item['nguoi_phu_trach'];
                    }
                }
            }
            
            // Tạo mảng diễn giả từ danh sách người phụ trách nếu danh sách diễn giả trống
            if (empty($speakers) && !empty($nguoi_phu_trach)) {
                foreach ($nguoi_phu_trach as $ten) {
                    $speakers[] = [
                        'ten' => $ten,
                        'chuc_vu' => isset($event['loai_su_kien']) ? 'Diễn giả ' . $event['loai_su_kien'] : 'Diễn giả',
                        'hinh_anh' => '', // Không có hình ảnh mặc định
                        'social_links' => [] // Không có liên kết mạng xã hội
                    ];
                }
            }
        }
    }
    
    // Đảm bảo $speakers luôn là mảng
    if (!is_array($speakers)) {
        $speakers = [];
    }
    
    if (!empty($speakers)): 
    ?>
    <div class="row">
        <?php foreach ($speakers as $speaker): ?>
        <div class="col-md-6 mb-4">
            <div class="speaker-card">
                <img src="<?= !empty($speaker['hinh_anh']) ? base_url($speaker['hinh_anh']) : base_url('assets/images/default-speaker.jpg') ?>" class="speaker-image" alt="<?= $speaker['ten'] ?? 'Diễn giả' ?>">
                <h5><?= $speaker['ten'] ?? '' ?></h5>
                <p class="text-muted"><?= $speaker['chuc_vu'] ?? '' ?></p>
                <?php if (!empty($speaker['social_links'])): ?>
                <div class="speaker-social mt-3">
                    <?php if (!empty($speaker['social_links']['facebook'])): ?>
                    <a href="<?= $speaker['social_links']['facebook'] ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1" target="_blank"><i class="lni lni-facebook-filled"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($speaker['social_links']['linkedin'])): ?>
                    <a href="<?= $speaker['social_links']['linkedin'] ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1" target="_blank"><i class="lni lni-linkedin-original"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($speaker['social_links']['email'])): ?>
                    <a href="mailto:<?= $speaker['social_links']['email'] ?>" class="btn btn-sm btn-outline-primary rounded-circle" target="_blank"><i class="lni lni-envelope"></i></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="lni lni-information me-2"></i> Chưa có thông tin diễn giả cho sự kiện này.
    </div>
    <?php endif; ?>
</div>