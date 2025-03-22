<?php

namespace App\Libraries;

/**
 * Lớp Alert - Quản lý thông báo trong ứng dụng
 * 
 * Cho phép thiết lập và hiển thị các thông báo dưới dạng các alert Bootstrap
 */
class Alert
{
    /**
     * Session key để lưu thông báo
     *
     * @var string
     */
    protected $sessionKey = 'alert_messages';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Đảm bảo session đã được khởi tạo
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }
    }
    
    /**
     * Thiết lập một thông báo
     *
     * @param string $type Loại thông báo (success, info, warning, danger)
     * @param string $message Nội dung thông báo
     * @param bool $flashData Có lưu thông báo vào flash data không
     * @return $this
     */
    public function set(string $type, string $message, bool $flashData = false)
    {
        $alert = [
            'type' => $type,
            'message' => $message
        ];
        
        if ($flashData) {
            // Lưu vào flash data để hiển thị sau khi redirect
            session()->setFlashdata($this->sessionKey, $alert);
        } else {
            // Lưu vào session để hiển thị ngay lập tức
            session()->set($this->sessionKey, $alert);
        }
        
        return $this;
    }
    
    /**
     * Thiết lập thông báo thành công
     *
     * @param string $message Nội dung thông báo
     * @param bool $flashData Có lưu thông báo vào flash data không
     * @return $this
     */
    public function success(string $message, bool $flashData = false)
    {
        return $this->set('success', $message, $flashData);
    }
    
    /**
     * Thiết lập thông báo thông tin
     *
     * @param string $message Nội dung thông báo
     * @param bool $flashData Có lưu thông báo vào flash data không
     * @return $this
     */
    public function info(string $message, bool $flashData = false)
    {
        return $this->set('info', $message, $flashData);
    }
    
    /**
     * Thiết lập thông báo cảnh báo
     *
     * @param string $message Nội dung thông báo
     * @param bool $flashData Có lưu thông báo vào flash data không
     * @return $this
     */
    public function warning(string $message, bool $flashData = false)
    {
        return $this->set('warning', $message, $flashData);
    }
    
    /**
     * Thiết lập thông báo lỗi
     *
     * @param string $message Nội dung thông báo
     * @param bool $flashData Có lưu thông báo vào flash data không
     * @return $this
     */
    public function danger(string $message, bool $flashData = false)
    {
        return $this->set('danger', $message, $flashData);
    }
    
    /**
     * Lấy thông báo từ session
     *
     * @return array|null
     */
    public function get()
    {
        // Ưu tiên lấy từ flash data
        $alert = session()->getFlashdata($this->sessionKey);
        
        if (!$alert) {
            // Nếu không có trong flash data, lấy từ session thường
            $alert = session()->get($this->sessionKey);
            
            // Và xóa nó khỏi session
            if ($alert) {
                session()->remove($this->sessionKey);
            }
        }
        
        return $alert;
    }
    
    /**
     * Kiểm tra xem có thông báo nào không
     *
     * @return bool
     */
    public function has()
    {
        return session()->has($this->sessionKey) || session()->has("_ci_flash_$this->sessionKey");
    }
    
    /**
     * Xóa tất cả thông báo
     *
     * @return $this
     */
    public function clear()
    {
        session()->remove($this->sessionKey);
        
        return $this;
    }
    
    /**
     * Hiển thị thông báo dưới dạng HTML
     *
     * @return string
     */
    public function display()
    {
        $alert = $this->get();
        
        if (!$alert) {
            return '';
        }
        
        $html = '<div class="alert alert-' . $alert['type'] . ' alert-dismissible fade show" role="alert">';
        $html .= $alert['message'];
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $html .= '</div>';
        
        return $html;
    }
} 