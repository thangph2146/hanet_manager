<!doctype html>
<html lang="vi">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="<?= site_url('assets/images/favicon-32x32.png') ?>" type="image/png" />
    <!--plugins-->
    <link href="<?= site_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
    <link href="<?= site_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
    <link href="<?= site_url('assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />
    <!-- loader-->
    <link href="<?= site_url('assets/css/pace.min.css') ?>" rel="stylesheet" />
    <script src="<?= site_url('assets/js/pace.min.js') ?>"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= site_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/app.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/icons.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Login CSS -->
    <link href="<?= site_url('assets/modules/login/css/login.css') ?>" rel="stylesheet">
    <!-- Particles CSS -->
    <link href="<?= site_url('assets/modules/particles/css/particles.css') ?>" rel="stylesheet">
    <title>Đăng ký - Hệ thống đăng ký sự kiện ĐH Ngân hàng TP.HCM</title>
</head>

<body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <!-- warning -->
                <?= $this->include('components/_warning') ?>
                <!-- end warning -->
            </div>
        </div>
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="login-container">
                    <!-- Info Panel (Left Side) -->
                    <div class="info-panel">
                        <div class="text-center">
                            <a href="<?= site_url('') ?>">
                                <img src="<?= base_url('assets/modules/images/hub-logo.png') ?>" 
                                alt="Logo Đại học Ngân hàng TP.HCM" 
                                class="school-logo"
                                style="width: 300px">
                            </a>
                        </div>
                        
                        <h3 class="events-title">SỰ KIỆN NỔI BẬT</h3>
                        
                        <div class="events-list">
                            <div class="event-item">
                                <div class="event-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="event-text">
                                    <strong>Ngày hội việc làm <?= date('Y') ?></strong>
                                    Cơ hội kết nối với hơn <span class="highlight">50+ doanh nghiệp hàng đầu</span>
                                </div>
                            </div>
                            
                            <div class="event-item">
                                <div class="event-icon">
                                    <i class="fas fa-microphone"></i>
                                </div>
                                <div class="event-text">
                                    <strong>Hội thảo chuyên đề</strong>
                                    Gặp gỡ các chuyên gia trong ngành tài chính - ngân hàng
                                </div>
                            </div>
                            
                            <div class="event-item">
                                <div class="event-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="event-text">
                                    <strong>Cuộc thi tài năng sinh viên</strong>
                                    Cơ hội giành học bổng và những phần quà giá trị
                                </div>
                            </div>
                            
                            <div class="event-item">
                                <div class="event-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="event-text">
                                    <strong>Hoạt động câu lạc bộ</strong>
                                    Tham gia và phát triển kỹ năng mềm cùng các CLB sinh viên
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-footer text-center">
                            © <?= date('Y') ?> Trường Đại học Ngân hàng TP. Hồ Chí Minh<br>
                            <small>36 Tôn Thất Đạm, Phường Nguyễn Thái Bình, Quận 1, TP.HCM</small>
                        </div>
                    </div>
                    
                    <!-- Register Form Panel (Right Side) -->
                    <div class="login-form-panel">
                        <div class="floating-shape shape1"></div>
                        <div class="floating-shape shape2"></div>
                        
                        <div class="login-header">
                            <a href="<?= site_url('') ?>">
                                <img src="<?= base_url('assets/modules/images/hub-logo.png') ?>" alt="Logo Đại học Ngân hàng TP.HCM" class="mobile-logo">
                            </a>
                            <h3 class="login-title">ĐĂNG KÝ TÀI KHOẢN</h3>
                            <p class="login-subtitle">Tạo tài khoản để đăng ký và tham gia các sự kiện đặc biệt của trường</p>
                        </div>
                        
                        <!-- Form Body -->
                        <div class="form-body">
                            <?= form_open(base_url('login/nguoidung/create'), ['class' => 'row g-3']) ?>
                                <div class="row">
                                    <div class="col-xl-12 mx-auto">
                                        <?php if (session()->getFlashdata('error')) : ?>
                                            <?php if (is_array(session()->getFlashdata('error'))) : ?>
                                                <div class="alert alert-danger">
                                                    <ul class="mb-0">
                                                        <?php foreach (session()->getFlashdata('error') as $error) : ?>
                                                            <li><?= $error ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php else : ?>
                                                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <?php if (session()->getFlashdata('success')) : ?>
                                            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="LastName" class="form-label">Họ</label>
                                    <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Nhập họ và tên đệm" value="<?= old('LastName') ?>">
                                </div>
                                <div class="col-12">
                                    <label for="MiddleName" class="form-label">Tên đệm</label>
                                    <input type="text" class="form-control" id="MiddleName" name="MiddleName" placeholder="Nhập tên đệm (nếu có)" value="<?= old('MiddleName') ?>">
                                </div>
                                <div class="col-12">
                                    <label for="FirstName" class="form-label">Tên</label>
                                    <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="Nhập tên" value="<?= old('FirstName') ?>">
                                </div>
                                <div class="col-12">
                                    <label for="Email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="Email" name="Email" placeholder="Nhập email" value="<?= old('Email') ?>">
                                </div>
                                <div class="col-12">
                                    <input type="hidden" id="AccountId" name="AccountId" value="<?= old('AccountId') ?>">
                                    <label for="MobilePhone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="MobilePhone" name="MobilePhone" placeholder="Nhập số điện thoại" value="<?= old('MobilePhone') ?>">
                                </div>
                                <div class="col-12">
                                    <label for="PW" class="form-label">Mật khẩu</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control border-end-0" id="PW" name="PW" placeholder="Nhập mật khẩu">
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                    </div>
                                    <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                                </div>
                                <div class="col-12">
                                    <label for="PW_confirm" class="form-label">Xác nhận mật khẩu</label>
                                    <div class="input-group" id="show_hide_password_confirm">
                                        <input type="password" class="form-control border-end-0" id="PW_confirm" name="PW_confirm" placeholder="Nhập lại mật khẩu">
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <input type="hidden" id="FullName" name="FullName" value="<?= old('FullName') ?>">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="agree" name="agree" required>
                                        <label class="form-check-label" for="agree">Tôi đồng ý với <a href="#">Điều khoản sử dụng</a></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary"><i class="bx bx-user-plus"></i>Đăng Ký</button>
                                    </div>
                                </div>
                                <div class="col-12 text-center">
                                    <p>Đã có tài khoản? <a href="<?= base_url('login/nguoi-dung') ?>">Đăng nhập ngay</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="particles-js" class="particles-js"></div>
    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="<?= site_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!--plugins-->
    <script src="<?= site_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
    <!--app JS-->
    <script src="<?= site_url('assets/js/app.js') ?>"></script>
    <!-- Login JS -->
    <script src="<?= site_url('assets/modules/login/js/login.js') ?>"></script>
    <!-- Particles JS -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="<?= site_url('assets/modules/particles/js/particles.js') ?>"></script>

    <script>
        $(document).ready(function () {
            // Show/hide password functionality
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
            
            $("#show_hide_password_confirm a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password_confirm input').attr("type") == "text") {
                    $('#show_hide_password_confirm input').attr('type', 'password');
                    $('#show_hide_password_confirm i').addClass("bx-hide");
                    $('#show_hide_password_confirm i').removeClass("bx-show");
                } else if ($('#show_hide_password_confirm input').attr("type") == "password") {
                    $('#show_hide_password_confirm input').attr('type', 'text');
                    $('#show_hide_password_confirm i').removeClass("bx-hide");
                    $('#show_hide_password_confirm i').addClass("bx-show");
                }
            });

            // Auto-generate FullName from LastName, MiddleName, and FirstName
            function updateFullName() {
                var lastName = $('#LastName').val() || '';
                var middleName = $('#MiddleName').val() || '';
                var firstName = $('#FirstName').val() || '';
                var fullName = (lastName + ' ' + middleName + ' ' + firstName).trim();
                $('#FullName').val(fullName);
            }

            // Update FullName whenever name fields change
            $('#LastName, #MiddleName, #FirstName').on('change keyup', updateFullName);

            // Generate AccountId from Email
            function updateAccountId() {
                var email = $('#Email').val();
                if (email) {
                    var parts = email.split('@');
                    if (parts.length > 1) {
                        $('#AccountId').val(parts[0]);
                    }
                }
            }

            // Update AccountId when Email changes
            $('#Email').on('change keyup', updateAccountId);
        });
    </script>
</body>

</html> 