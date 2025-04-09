<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;

/**
 * Custom Time class to handle formatting without using IntlDateFormatter
 */
class CustomTime extends Time
{
    /**
     * Override toLocalizedString to use PHP's DateTime format instead of IntlDateFormatter
     */
    public function toLocalizedString(?string $format = null)
    {
        $format ??= $this->toStringFormat;

        // Simple mapping of common IntlDateFormatter patterns to PHP DateTime format
        $format = str_replace(
            ['yyyy', 'MM', 'dd', 'HH', 'mm', 'ss'],
            ['Y', 'm', 'd', 'H', 'i', 's'],
            $format
        );

        return $this->format($format);
    }
} 