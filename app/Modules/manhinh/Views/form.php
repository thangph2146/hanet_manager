<?php
/**
 * Form component for creating and updating màn hình
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var ManHinh $manhinh ManHinh entity data for editing (optional)
 */

// Set default values if editing
$ten_man_hinh = isset($manhinh) ? $manhinh->ten_man_hinh : '';
$ma_man_hinh = isset($manhinh) ? $manhinh->ma_man_hinh : '';
$camera_id = isset($manhinh) ? $manhinh->camera_id : '';
$template_id = isset($manhinh) ? $manhinh->template_id : '';
$status = isset($manhinh) ? (string)$manhinh->status : '1';
$bin = isset($manhinh) ? (string)$manhinh->bin : '0';
$id = isset($manhinh) ? $manhinh->man_hinh_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('manhinh/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới màn hình' : 'Cập nhật màn hình';
$isUpdate = isset($manhinh) && isset($manhinh->man_hinh_id);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="manhinhForm" class="needs-validation" novalidate>
    <?php if ($isUpdate): ?>
        <input type="hidden" name="man_hinh_id" value="<?= $id ?>">
    <?php endif; ?>
    
    <!-- Trường bin ẩn -->
    <input type="hidden" name="bin" value="<?= $bin ?>">

    <h4 class="mb-3"><?= $formTitle ?></h4>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class='bx bx-error-circle fs-3'></i>
                </div>
                <div>
                    <strong>Lỗi!</strong> <?= session('error') ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Hiển thị lỗi validation -->
    <?php if (isset($errors) && (is_array($errors) || is_object($errors)) && count($errors) > 0): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class='bx bx-error-circle fs-3'></i>
                </div>
                <div>
                    <strong>Lỗi nhập liệu:</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        <?php foreach ($errors as $field => $error): ?>
                            <li><?= is_array($error) ? implode(', ', $error) : $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class='bx bx-info-circle text-primary me-2'></i>
                Thông tin cơ bản
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ma_man_hinh -->
                <div class="col-md-6">
                    <label for="ma_man_hinh" class="form-label fw-semibold">
                        Mã màn hình <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_man_hinh') ? 'is-invalid' : '' ?>" 
                                id="ma_man_hinh" name="ma_man_hinh" 
                                value="<?= old('ma_man_hinh', $ma_man_hinh) ?>" 
                                placeholder="Nhập mã màn hình"
                                required maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_man_hinh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_man_hinh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã màn hình</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã màn hình phải là duy nhất trong hệ thống, tối đa 20 ký tự
                    </div>
                </div>

                <!-- ten_man_hinh -->
                <div class="col-md-6">
                    <label for="ten_man_hinh" class="form-label fw-semibold">
                        Tên màn hình <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-desktop'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_man_hinh') ? 'is-invalid' : '' ?>" 
                                id="ten_man_hinh" name="ten_man_hinh" 
                                value="<?= old('ten_man_hinh', $ten_man_hinh) ?>" 
                                placeholder="Nhập tên màn hình"
                                required minlength="3" maxlength="255" autocomplete="off"
                                oninput="this.value = this.value.trim()">
                        <?php if (isset($validation) && $validation->hasError('ten_man_hinh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_man_hinh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên màn hình (tối thiểu 3 ký tự)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên màn hình phải có ít nhất 3 ký tự và không trùng với bất kỳ màn hình nào trong hệ thống
                    </div>
                </div>

                <!-- camera_id -->
                <div class="col-md-6">
                    <label for="camera_id" class="form-label fw-semibold">
                        Camera <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-camera'></i></span>
                        <input type="text" class="form-control camera-search" id="camera_search" 
                               placeholder="Tìm kiếm camera..." autocomplete="off" list="camera_options">
                        <select class="form-select d-none" id="camera_id" name="camera_id">
                            <option value="">-- Chọn camera --</option>
                            <?php foreach ($cameras as $camera): ?>
                                <option value="<?= $camera->camera_id ?>" 
                                        data-name="<?= esc($camera->ten_camera) ?>"
                                        data-code="<?= esc($camera->ma_camera) ?>"
                                        <?= (old('camera_id', $manhinh->camera_id) == $camera->camera_id) ? 'selected' : '' ?>>
                                    <?= esc($camera->ten_camera) ?> (<?= esc($camera->ma_camera) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <datalist id="camera_options">
                            <?php foreach ($cameras as $camera): ?>
                                <option value="<?= esc($camera->ten_camera) ?> (<?= esc($camera->ma_camera) ?>)" 
                                        data-id="<?= $camera->camera_id ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>

                <!-- template_id -->
                <div class="col-md-6">
                    <label for="template_id" class="form-label fw-semibold">
                        Template <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-layout'></i></span>
                        <input type="text" class="form-control template-search" id="template_search" 
                               placeholder="Tìm kiếm template..." autocomplete="off" list="template_options">
                        <select class="form-select d-none" id="template_id" name="template_id">
                            <option value="">-- Chọn template --</option>
                            <?php foreach ($templates as $template): ?>
                                <option value="<?= $template->template_id ?>" 
                                        data-name="<?= esc($template->ten_template) ?>"
                                        data-code="<?= esc($template->ma_template) ?>"
                                        <?= (old('template_id', $manhinh->template_id) == $template->template_id) ? 'selected' : '' ?>>
                                    <?= esc($template->ten_template) ?> (<?= esc($template->ma_template) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <datalist id="template_options">
                            <?php foreach ($templates as $template): ?>
                                <option value="<?= esc($template->ten_template) ?> (<?= esc($template->ma_template) ?>)" 
                                        data-id="<?= $template->template_id ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Trạng thái</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-toggle-left'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                               id="status" name="status">
                            <option value="1" <?= old('status', $status) == '1' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Màn hình không hoạt động sẽ không hiển thị trong các danh sách chọn
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    <i class='bx bx-info-circle me-1'></i>
                    Các trường có dấu <span class="text-danger">*</span> là bắt buộc
                </span>
                
                <div class="d-flex gap-2">
                    <a href="<?= site_url('manhinh') ?>" class="btn btn-light">
                        <i class='bx bx-arrow-back me-1'></i> Quay lại
                    </a>
                    <button class="btn btn-primary px-4" type="submit">
                        <i class='bx bx-save me-1'></i>
                        <?= $isUpdate ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
        
        // Tự động focus vào trường đầu tiên
        document.getElementById('ten_man_hinh').focus();
    });
</script>

<?php $this->section('scripts') ?>
<!-- Không còn cần Select2 -->
<script>
    $(document).ready(function() {
        // Xử lý tìm kiếm camera
        $('#camera_search').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            var cameraFound = false;
            
            // Nếu tìm thấy giá trị chính xác từ datalist
            $('#camera_options option').each(function() {
                var optionValue = $(this).val().toLowerCase();
                var cameraId = $(this).data('id');
                
                if (optionValue === searchText.toLowerCase()) {
                    // Đặt giá trị cho select
                    $('#camera_id').val(cameraId);
                    cameraFound = true;
                    return false; // Dừng vòng lặp
                }
            });
            
            // Nếu không tìm thấy và có từ khóa tìm kiếm, gửi AJAX
            if (!cameraFound && searchText.length > 0) {
                $.ajax({
                    url: '<?= site_url('manhinh/search-cameras') ?>',
                    method: 'GET',
                    data: { keyword: searchText },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            // Cập nhật datalist
                            $('#camera_options').empty();
                            
                            // Cập nhật select
                            var selectedId = $('#camera_id').val();
                            var selectHtml = '<option value="">-- Chọn camera --</option>';
                            
                            $.each(response.data, function(index, camera) {
                                // Thêm vào datalist
                                var optionText = camera.ten_camera + ' (' + camera.ma_camera + ')';
                                $('#camera_options').append('<option value="' + optionText + '" data-id="' + camera.camera_id + '">');
                                
                                // Thêm vào select
                                var selected = (selectedId == camera.camera_id) ? 'selected' : '';
                                selectHtml += '<option value="' + camera.camera_id + '" ' + selected + '>' + optionText + '</option>';
                            });
                            
                            $('#camera_id').html(selectHtml);
                        }
                    }
                });
            }
        });
        
        // Xử lý tìm kiếm template
        $('#template_search').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            var templateFound = false;
            
            // Nếu tìm thấy giá trị chính xác từ datalist
            $('#template_options option').each(function() {
                var optionValue = $(this).val().toLowerCase();
                var templateId = $(this).data('id');
                
                if (optionValue === searchText.toLowerCase()) {
                    // Đặt giá trị cho select
                    $('#template_id').val(templateId);
                    templateFound = true;
                    return false; // Dừng vòng lặp
                }
            });
            
            // Nếu không tìm thấy và có từ khóa tìm kiếm, gửi AJAX
            if (!templateFound && searchText.length > 0) {
                $.ajax({
                    url: '<?= site_url('manhinh/search-templates') ?>',
                    method: 'GET',
                    data: { keyword: searchText },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            // Cập nhật datalist
                            $('#template_options').empty();
                            
                            // Cập nhật select
                            var selectedId = $('#template_id').val();
                            var selectHtml = '<option value="">-- Chọn template --</option>';
                            
                            $.each(response.data, function(index, template) {
                                // Thêm vào datalist
                                var optionText = template.ten_template + ' (' + template.ma_template + ')';
                                $('#template_options').append('<option value="' + optionText + '" data-id="' + template.template_id + '">');
                                
                                // Thêm vào select
                                var selected = (selectedId == template.template_id) ? 'selected' : '';
                                selectHtml += '<option value="' + template.template_id + '" ' + selected + '>' + optionText + '</option>';
                            });
                            
                            $('#template_id').html(selectHtml);
                        }
                    }
                });
            }
        });
        
        // Hiển thị giá trị đã chọn trong ô tìm kiếm khi tải trang
        function setSelectedValues() {
            // Xử lý camera
            var selectedCameraId = $('#camera_id').val();
            if (selectedCameraId) {
                var selectedCamera = $('#camera_id option[value="' + selectedCameraId + '"]');
                var cameraName = selectedCamera.data('name');
                var cameraCode = selectedCamera.data('code');
                if (cameraName && cameraCode) {
                    $('#camera_search').val(cameraName + ' (' + cameraCode + ')');
                }
            }
            
            // Xử lý template
            var selectedTemplateId = $('#template_id').val();
            if (selectedTemplateId) {
                var selectedTemplate = $('#template_id option[value="' + selectedTemplateId + '"]');
                var templateName = selectedTemplate.data('name');
                var templateCode = selectedTemplate.data('code');
                if (templateName && templateCode) {
                    $('#template_search').val(templateName + ' (' + templateCode + ')');
                }
            }
        }
        
        // Thiết lập giá trị mặc định khi trang được tải
        setSelectedValues();
        
        // Khi form được submit, đảm bảo select có giá trị
        $('#manhinhForm').on('submit', function() {
            if ($('#camera_id').val() == '' && $('#camera_search').val() != '') {
                // Tìm ID từ văn bản tìm kiếm
                var searchText = $('#camera_search').val();
                $('#camera_options option').each(function() {
                    if ($(this).val() == searchText) {
                        $('#camera_id').val($(this).data('id'));
                        return false;
                    }
                });
            }
            
            if ($('#template_id').val() == '' && $('#template_search').val() != '') {
                // Tìm ID từ văn bản tìm kiếm
                var searchText = $('#template_search').val();
                $('#template_options option').each(function() {
                    if ($(this).val() == searchText) {
                        $('#template_id').val($(this).data('id'));
                        return false;
                    }
                });
            }
        });
    });
</script>
<?php $this->endSection() ?>

<?php $this->section('styles') ?>
<style>
    /* Ẩn select thực sự */
    .d-none {
        display: none !important;
    }
    
    /* Tùy chỉnh ô tìm kiếm */
    .camera-search, .template-search {
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
    
    /* Style cho datalist và options */
    datalist {
        position: absolute;
        max-height: 20em;
        border: 0 none;
        overflow-x: hidden;
        overflow-y: auto;
    }
    
    datalist option {
        font-size: 0.8em;
        padding: 0.3em 1em;
        background-color: #fff;
        cursor: pointer;
    }
    
    datalist option:hover, datalist option:focus {
        color: #fff;
        background-color: #036;
        outline: 0 none;
    }
    
    input::-webkit-calendar-picker-indicator {
        display: none;
    }
</style>
<?php $this->endSection() ?> 