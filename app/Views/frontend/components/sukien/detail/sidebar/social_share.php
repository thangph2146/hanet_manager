<div class="card shadow-sm mt-4 animate__animated animate__fadeInRight" style="animation-delay: 0.7s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-share me-2"></i> Chia sẻ sự kiện</h4>
        </div>
        <div class="card-body">
            <div class="qr-code text-center mt-4">
                <p class="small mb-2"><i class="lni lni-qr-code me-1"></i> Quét mã QR để xem trên điện thoại</p>
                <div class="bg-white border rounded shadow-sm p-3 mx-auto mb-2">
                    <img class="img-fluid w-100" src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=<?= urlencode(current_url()) ?>" alt="QR Code">
                </div>
                <p class="small text-muted mb-0">
                    <i class="lni lni-link me-1"></i> <a href="<?= current_url() ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 100%;"><?= current_url() ?></a>
                </p>
                <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&margin=10&data=<?= urlencode(current_url()) ?>" download="qr-code-sukien-<?= urlencode($event['ten_su_kien']) ?>.png" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="lni lni-download me-1"></i> Tải mã QR
                </a>
            </div>
        </div>
    </div>