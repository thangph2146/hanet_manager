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

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
}
