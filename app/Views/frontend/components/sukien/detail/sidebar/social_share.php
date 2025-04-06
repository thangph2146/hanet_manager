<div class="card shadow-sm mt-4 animate__animated animate__fadeInRight" style="animation-delay: 0.7s;">
    <div class="card-header text-white py-3">
        <h4 class="card-title mb-0"><i class="lni lni-share me-2"></i> Chia sẻ sự kiện</h4>
    </div>
    <div class="card-body">
        <!-- Nút chia sẻ mạng xã hội -->
        <div class="social-buttons mb-4">
            <div class="d-flex justify-content-center gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-sm btn-facebook" title="Chia sẻ lên Facebook">
                    <i class="lni lni-facebook-original"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($event['ten_su_kien'] ?? 'Sự kiện') ?>" target="_blank" class="btn btn-sm btn-twitter" title="Chia sẻ lên Twitter">
                    <i class="lni lni-twitter-original"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(current_url()) ?>&title=<?= urlencode($event['ten_su_kien'] ?? 'Sự kiện') ?>" target="_blank" class="btn btn-sm btn-linkedin" title="Chia sẻ lên LinkedIn">
                    <i class="lni lni-linkedin-original"></i>
                </a>
                <a href="mailto:?subject=<?= urlencode($event['ten_su_kien'] ?? 'Sự kiện') ?>&body=<?= urlencode('Xem chi tiết sự kiện tại: ' . current_url()) ?>" target="_blank" class="btn btn-sm btn-email" title="Gửi qua Email">
                    <i class="lni lni-envelope"></i>
                </a>
            </div>
        </div>

        <!-- Mã QR -->
        <div class="qr-code text-center">
            <p class="small mb-2"><i class="lni lni-qr-code me-1"></i> Quét mã QR để xem trên điện thoại</p>
            <div class="bg-white border rounded shadow-sm p-3 mx-auto mb-2">
                <img class="img-fluid w-100" src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=<?= urlencode(current_url()) ?>" alt="QR Code">
            </div>
            <p class="small text-muted mb-0">
                <i class="lni lni-link me-1"></i> <a href="<?= current_url() ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 100%;"><?= current_url() ?></a>
            </p>
            <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&margin=10&data=<?= urlencode(current_url()) ?>" download="qr-code-sukien-<?= urlencode($event['ten_su_kien'] ?? 'event') ?>.png" class="btn btn-sm btn-outline-primary mt-2">
                <i class="lni lni-download me-1"></i> Tải mã QR
            </a>
        </div>
    </div>
</div>

<style>
.btn-facebook {
    background-color: #1877f2;
    color: #fff;
}
.btn-twitter {
    background-color: #1da1f2;
    color: #fff;
}
.btn-linkedin {
    background-color: #0a66c2;
    color: #fff;
}
.btn-email {
    background-color: #6c757d;
    color: #fff;
}
.btn-facebook:hover, .btn-twitter:hover, .btn-linkedin:hover, .btn-email:hover {
    opacity: 0.9;
    color: #fff;
}
</style>