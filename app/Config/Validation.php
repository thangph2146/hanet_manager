<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        \Config\Validation::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    /**
     * Custom validation rule để kiểm tra ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu
     */
    public function validateDates(string $endDate, string $params, array $data, &$error = null): bool
    {
        if (empty($endDate) || empty($data[$params])) {
            return true;
        }
        
        $startDate = strtotime($data[$params]);
        $end = strtotime($endDate);
        
        if ($startDate > $end) {
            $error = 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu';
            return false;
        }
        
        return true;
    }

    /**
     * Custom validation rule để kiểm tra ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu
     * 
     * @param string $endDate Giá trị ngày kết thúc
     * @param string $params Tên trường chứa ngày bắt đầu
     * @param array $data Tất cả dữ liệu
     * @param string|null $error Thông báo lỗi
     * @return bool
     */
    public function datetime_greater_than(string $endDate, string $params, array $data, &$error = null): bool
    {
        if (empty($endDate) || empty($data[$params])) {
            return true;
        }
        
        $startDate = strtotime($data[$params]);
        $end = strtotime($endDate);
        
        if ($startDate >= $end) {
            $error = 'Thời gian kết thúc phải sau thời gian bắt đầu';
            return false;
        }
        
        return true;
    }

    /**
     * Custom validation rule - Bắt buộc nhập nếu một trường khác có một trong các giá trị
     *
     * @param string $value Giá trị cần kiểm tra
     * @param string $params Tên trường và các giá trị cần so sánh, cách nhau bởi dấu phẩy
     * @param array $data Tất cả dữ liệu
     * @param string|null $error Thông báo lỗi
     * @return bool
     */
    public function required_if(string $value, string $params, array $data, &$error = null): bool
    {
        // Tách chuỗi tham số thành mảng [field, value1, value2,...]
        $param_arr = explode(',', $params);
        
        if (count($param_arr) < 2) {
            return true; // Không đủ tham số, coi như hợp lệ
        }
        
        $field = array_shift($param_arr); // Lấy tên trường đầu tiên
        
        // Nếu trường không tồn tại trong dữ liệu, hoặc trường đang kiểm tra đã có giá trị
        if (!isset($data[$field]) || !empty($value)) {
            return true;
        }
        
        // Xử lý các giá trị so sánh để đảm bảo so sánh chính xác
        $fieldValue = (string)$data[$field]; // Chuyển đổi giá trị trường cần so sánh thành chuỗi
        $valueMatched = false;
        
        // So sánh với danh sách các giá trị cho phép
        foreach ($param_arr as $paramValue) {
            if ((string)$paramValue === $fieldValue) {
                $valueMatched = true;
                break;
            }
        }
        
        // Nếu giá trị của trường khớp với một trong các giá trị cần kiểm tra
        // và giá trị của trường đang validate trống
        if ($valueMatched && trim($value) === '') {
            $error = 'Trường này bắt buộc nhập.';
            return false;
        }
        
        return true;
    }

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
}
