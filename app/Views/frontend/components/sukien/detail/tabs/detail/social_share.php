<div class="social-share mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
        <h3>Chia sẻ sự kiện</h3>
        <div class="card">
            <div class="card-body">
                <p class="mb-3">Hãy chia sẻ sự kiện này đến bạn bè và đồng nghiệp của bạn:</p>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-facebook me-2 mb-2"><i class="lni lni-facebook-filled me-1"></i> Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($event['ten_su_kien']) ?>" target="_blank" class="btn btn-twitter me-2 mb-2"><i class="lni lni-twitter-filled me-1"></i> Twitter</a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(current_url()) ?>&title=<?= urlencode($event['ten_su_kien']) ?>" target="_blank" class="btn btn-linkedin me-2 mb-2"><i class="lni lni-linkedin-original me-1"></i> LinkedIn</a>
                    <a href="mailto:?subject=<?= urlencode($event['ten_su_kien']) ?>&body=<?= urlencode('Xem chi tiết sự kiện tại: ' . current_url()) ?>" class="btn btn-outline-primary mb-2"><i class="lni lni-envelope me-1"></i> Email</a>
                </div>
            </div>
        </div>
    </div>