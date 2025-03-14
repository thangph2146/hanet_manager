<?php
/**
 * Component hiển thị tab form đăng ký tham gia sự kiện
 */
?>

<div class="tab-pane fade" id="event-registration" role="tabpanel" aria-labelledby="event-registration-tab">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Đăng ký tham gia</h3>
            
            <!-- Thông tin đăng ký -->
            <div class="registration-info mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <i class="lni lni-calendar me-2"></i>
                            <span>Ngày: <?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?></span>
                        </div>
                        <div class="info-item mb-3">
                            <i class="lni lni-map-marker me-2"></i>
                            <span>Địa điểm: <?= $event['dia_diem'] ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <i class="lni lni-clock me-2"></i>
                            <span>Thời gian: <?= $event['thoi_gian'] ?></span>
                        </div>
                        <div class="info-item mb-3">
                            <i class="lni lni-users me-2"></i>
                            <span>Số lượng: <?= $event['so_luong_tham_gia'] ?> người</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tiến trình đăng ký -->
            <div class="mb-4">
                <h5>Tình trạng đăng ký</h5>
                <div class="progress mb-2">
                    <?php 
                        $percent = isset($registration_count) && $event['so_luong_tham_gia'] > 0 
                            ? min(100, round(($registration_count / $event['so_luong_tham_gia']) * 100)) 
                            : 0;
                    ?>
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percent ?>%" 
                        aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                        <?= $percent ?>%
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Đã đăng ký: <?= isset($registration_count) ? $registration_count : 0 ?> người</span>
                    <span class="text-muted small">Còn trống: <?= max(0, $event['so_luong_tham_gia'] - (isset($registration_count) ? $registration_count : 0)) ?> chỗ</span>
                </div>
            </div>
            
            <!-- Form đăng ký -->
            <form action="<?= site_url('su-kien/register') ?>" method="post" id="registration-form">
                <input type="hidden" name="id_su_kien" value="<?= $event['id_su_kien'] ?>">
                <input type="hidden" name="nguoi_dung_id" value="<?= rand(1000, 9999) ?>"> <!-- Giả lập ID người dùng -->
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ma_sinh_vien" class="form-label">Mã sinh viên (nếu có)</label>
                            <input type="text" class="form-control" id="ma_sinh_vien" name="ma_sinh_vien">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="noi_dung_gop_y" class="form-label">Góp ý/Câu hỏi (nếu có)</label>
                    <textarea class="form-control" id="noi_dung_gop_y" name="noi_dung_gop_y" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="nguon_gioi_thieu" class="form-label">Bạn biết đến sự kiện này từ đâu?</label>
                    <select class="form-select" id="nguon_gioi_thieu" name="nguon_gioi_thieu">
                        <option value="Website trường">Website trường</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Email từ trường">Email từ trường</option>
                        <option value="Bạn bè">Bạn bè</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required>
                    <label class="form-check-label" for="agree_terms">
                        Tôi đồng ý với <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">điều khoản tham gia</a> của sự kiện
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Đăng ký tham gia</button>
            </form>
            
            <!-- Thông tin liên hệ -->
            <div class="contact-info mt-4">
                <h5>Thông tin liên hệ</h5>
                <p>Nếu bạn có thắc mắc về sự kiện, vui lòng liên hệ:</p>
                <ul class="list-unstyled">
                    <li><i class="lni lni-phone me-2"></i> Hotline: (028) 38 212 593</li>
                    <li><i class="lni lni-envelope me-2"></i> Email: sukien@hub.edu.vn</li>
                    <li><i class="lni lni-facebook-filled me-2"></i> Facebook: <a href="https://facebook.com/hubuniversity" target="_blank">facebook.com/hubuniversity</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Modal Điều khoản -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Điều khoản tham gia sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Quy định chung</h6>
                    <p>Người tham gia cần tuân thủ các quy định của Ban tổ chức và địa điểm tổ chức sự kiện.</p>
                    
                    <h6>2. Đăng ký và xác nhận</h6>
                    <p>Việc đăng ký tham gia sự kiện sẽ được xác nhận qua email. Người tham gia cần mang theo email xác nhận khi đến tham dự sự kiện.</p>
                    
                    <h6>3. Điểm danh và chứng nhận</h6>
                    <p>Người tham gia cần điểm danh khi đến tham dự sự kiện. Chứng nhận tham gia (nếu có) sẽ chỉ được cấp cho những người tham dự đầy đủ chương trình.</p>
                    
                    <h6>4. Quyền riêng tư và hình ảnh</h6>
                    <p>Ban tổ chức có quyền sử dụng hình ảnh, video được ghi lại trong sự kiện cho mục đích truyền thông và báo cáo.</p>
                    
                    <h6>5. Hủy đăng ký</h6>
                    <p>Nếu không thể tham gia, người đăng ký vui lòng thông báo cho Ban tổ chức ít nhất 24 giờ trước khi sự kiện diễn ra.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="document.getElementById('agree_terms').checked = true;">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div> 