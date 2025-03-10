# Hướng dẫn tích hợp đăng nhập bằng Google

## Giới thiệu
Tài liệu này hướng dẫn cách tích hợp đăng nhập bằng Google vào ứng dụng CodeIgniter 4 của bạn.

## Các bước cài đặt

### 1. Tạo Google OAuth Client ID

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo một dự án mới hoặc chọn dự án hiện có
3. Đi đến "APIs & Services" > "Credentials"
4. Nhấp vào "Create Credentials" và chọn "OAuth client ID"
5. Chọn "Web application" làm loại ứng dụng
6. Đặt tên cho client ID
7. Thêm URI chuyển hướng: `http://your-domain.com/Login/googleCallback`
8. Nhấp vào "Create"
9. Lưu lại Client ID và Client Secret

### 2. Cấu hình trong ứng dụng

1. Mở file `.env` và thêm các thông tin sau:

```
# Google Authentication
GOOGLE_CLIENT_ID = 'your-google-client-id'
GOOGLE_CLIENT_SECRET = 'your-google-client-secret'
```

2. Thay thế `your-google-client-id` và `your-google-client-secret` bằng thông tin bạn đã lưu ở bước 1.

### 3. Chạy migration để thêm các trường mới vào bảng users

```bash
php spark migrate
```

## Cách hoạt động

1. Người dùng nhấp vào nút "Đăng nhập bằng Google" trên trang đăng nhập
2. Họ được chuyển hướng đến trang đăng nhập của Google
3. Sau khi xác thực thành công, Google sẽ chuyển hướng người dùng trở lại ứng dụng của bạn với một mã xác thực
4. Ứng dụng sẽ sử dụng mã này để lấy thông tin người dùng từ Google
5. Nếu email của người dùng đã tồn tại trong hệ thống, họ sẽ được đăng nhập
6. Nếu email chưa tồn tại, hệ thống sẽ tạo một tài khoản mới (tùy thuộc vào cấu hình)

## Tùy chỉnh

### Tự động tạo người dùng mới

Mặc định, hệ thống sẽ không tự động tạo người dùng mới nếu email chưa tồn tại trong cơ sở dữ liệu. Để bật tính năng này, mở file `app/Libraries/GoogleAuthentication.php` và bỏ comment dòng sau trong phương thức `loginWithGoogle()`:

```php
// return $this->createUserFromGoogle($googleUser);
```

### Thêm trường thông tin

Nếu bạn muốn lưu thêm thông tin từ Google, bạn có thể sửa đổi phương thức `getUserInfo()` trong `app/Libraries/GoogleAuthentication.php` và thêm các trường vào mảng trả về.

## Xử lý lỗi

Nếu bạn gặp lỗi trong quá trình đăng nhập bằng Google, hãy kiểm tra các log lỗi trong `writable/logs/`.

## Lưu ý bảo mật

- Không bao giờ chia sẻ Client ID và Client Secret của bạn
- Luôn xác thực email đã được xác minh từ Google trước khi đăng nhập người dùng
- Xem xét việc thêm các biện pháp bảo mật bổ sung như CSRF protection và rate limiting 