<!-- 
Modal dùng chung với Bootstrap 5.2
Cách sử dụng:
1. Include component này trong view
2. Gọi modal bằng data-bs-toggle="modal" data-bs-target="#modal-id"
3. Có thể truyền các tham số sau dưới dạng mảng $modalParams:
   'id' - ID của modal (mặc định: 'modal')
   'title' - Tiêu đề modal (mặc định: '')
   'size' - Kích thước modal (sm, lg, xl - mặc định: '')
   'closeBtn' - Hiển thị nút đóng (true/false - mặc định: true)
   'saveBtn' - Hiển thị nút lưu (true/false - mặc định: true)
   'saveBtnText' - Text cho nút lưu (mặc định: 'Lưu')
   'saveBtnClass' - Class cho nút lưu (mặc định: 'btn-primary')
   'content' - Nội dung modal (mặc định: '')
   'footer' - Nội dung footer tùy chỉnh (mặc định: '')
   'container' - Hiển thị modal trong div container riêng (true/false - mặc định: false)
   'centered' - Canh giữa modal theo chiều dọc (true/false - mặc định: false)
   'scrollable' - Cho phép cuộn nội dung modal (true/false - mặc định: false)
   'fullscreen' - Hiển thị modal toàn màn hình (true/false hoặc 'sm-down'/'md-down'/'lg-down'/'xl-down'/'xxl-down' - mặc định: false)
   'backdrop' - Kiểu backdrop ('static' hoặc true/false - mặc định: true)
   'keyboard' - Cho phép đóng modal bằng phím Esc (true/false - mặc định: true)
-->

<?php
/**
 * Hàm hiển thị modal với các tham số
 * 
 * @param array $params Mảng chứa các tham số của modal
 * @return void
 */
function renderModal($params = []) {
    // Thiết lập giá trị mặc định cho các tham số
    $defaults = [
        'id' => 'modal',
        'title' => '',
        'size' => '',
        'closeBtn' => true,
        'saveBtn' => true,
        'saveBtnText' => 'Lưu',
        'saveBtnClass' => 'btn-primary',
        'content' => '',
        'footer' => '',
        'container' => false,
        'centered' => false,
        'scrollable' => false,
        'fullscreen' => false,
        'backdrop' => true,
        'keyboard' => true
    ];
    
    // Gộp tham số với giá trị mặc định
    $params = array_merge($defaults, $params);
    
    // Xử lý các class cho modal-dialog
    $dialogClasses = [];
    
    // Kích thước modal
    if (!empty($params['size'])) {
        $dialogClasses[] = "modal-{$params['size']}";
    }
    
    // Modal có căn giữa theo chiều dọc
    if ($params['centered']) {
        $dialogClasses[] = "modal-dialog-centered";
    }
    
    // Modal có thể cuộn
    if ($params['scrollable']) {
        $dialogClasses[] = "modal-dialog-scrollable";
    }
    
    // Modal toàn màn hình
    if ($params['fullscreen'] !== false) {
        if ($params['fullscreen'] === true) {
            $dialogClasses[] = "modal-fullscreen";
        } else {
            $dialogClasses[] = "modal-fullscreen-{$params['fullscreen']}";
        }
    }
    
    // Ghép các class lại với nhau
    $dialogClass = implode(' ', $dialogClasses);
    
    // ID duy nhất cho container
    $containerId = $params['container'] ? "{$params['id']}-container" : '';
    
    // Tạo thuộc tính dữ liệu cho backdrop và keyboard
    $backdropAttr = $params['backdrop'] === 'static' ? 'data-bs-backdrop="static"' : '';
    $keyboardAttr = $params['keyboard'] === false ? 'data-bs-keyboard="false"' : '';
?>
<!-- Modal -->
<div class="modal fade" id="<?= $params['id'] ?>" tabindex="-1" aria-labelledby="<?= $params['id'] ?>Label" aria-hidden="true" <?= $backdropAttr ?> <?= $keyboardAttr ?>>
  <div class="modal-dialog <?= $dialogClass ?>">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="<?= $params['id'] ?>Label"><?= $params['title'] ?></h5>
        <?php if ($params['closeBtn']): ?>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        <?php endif; ?>
      </div>
      <div class="modal-body">
        <?= $params['content'] ?>
      </div>
      <div class="modal-footer">
        <?php if ($params['footer']): ?>
          <?= $params['footer'] ?>
        <?php else: ?>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <?php if ($params['saveBtn']): ?>
            <button type="button" class="btn <?= $params['saveBtnClass'] ?>" id="<?= $params['id'] ?>-save"><?= $params['saveBtnText'] ?></button>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if ($params['container']): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tạo container ở cuối body nếu chưa tồn tại
    let container = document.getElementById('<?= $containerId ?>');
    if (!container) {
        container = document.createElement('div');
        container.id = '<?= $containerId ?>';
        container.className = 'modal-container';
        document.body.appendChild(container);
    }
    
    // Đảm bảo rằng modal sẽ được hiển thị trong container đã định
    var modalElement = document.getElementById('<?= $params['id'] ?>');
    var modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement, {
        backdrop: <?= is_bool($params['backdrop']) ? ($params['backdrop'] ? 'true' : 'false') : "'{$params['backdrop']}'" ?>,
        keyboard: <?= $params['keyboard'] ? 'true' : 'false' ?>,
        focus: true
    });
    
    // Đặt modal vào container khi DOM đã sẵn sàng
    if (modalElement && container) {
        // Di chuyển modal vào container
        container.appendChild(modalElement);
    }
    
    // Đảm bảo modal được hiển thị đúng khi mở
    var showTermsBtn = document.getElementById('showTermsBtn');
    if (showTermsBtn) {
        showTermsBtn.addEventListener('click', function() {
            modalInstance.show();
        });
    }
    
    // Xử lý khi modal đóng để đảm bảo xóa backdrop
    modalElement.addEventListener('hidden.bs.modal', function() {
        // Xóa tất cả các .modal-backdrop còn sót lại
        let backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(function(backdrop) {
            backdrop.remove();
        });
        
        // Xóa class modal-open trên body nếu không còn modal nào mở
        if (document.querySelectorAll('.modal.show').length === 0) {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    });
});
</script>
<?php else: ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalElement = document.getElementById('<?= $params['id'] ?>');
    
    // Xử lý khi modal đóng để đảm bảo xóa backdrop
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            // Xóa tất cả các .modal-backdrop còn sót lại
            let backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(function(backdrop) {
                backdrop.remove();
            });
            
            // Xóa class modal-open trên body nếu không còn modal nào mở
            if (document.querySelectorAll('.modal.show').length === 0) {
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        });
    }
});
</script>
<?php endif; ?>

<?php
}

// Kiểm tra nếu có biến $modalParams được truyền vào, thì hiển thị modal
if (isset($modalParams) && is_array($modalParams)) {
    renderModal($modalParams);
}
?>
