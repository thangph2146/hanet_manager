<?php

/**
 * Custom helper functions for time manipulation
 */

if (!function_exists('format_datetime')) {
    /**
     * Format a datetime string without using IntlDateFormatter
     *
     * @param mixed $datetime The datetime to format
     * @param string $format The format string 
     * @return string Formatted datetime
     */
    function format_datetime($datetime = null, $format = 'Y-m-d H:i:s')
    {
        if ($datetime === null) {
            $datetime = new \DateTime();
        } elseif (is_string($datetime)) {
            $datetime = new \DateTime($datetime);
        }
        
        return $datetime->format($format);
    }
}

/**
 * Get current datetime string in database format
 * 
 * @return string Current datetime in Y-m-d H:i:s format
 */
function now_datetime()
{
    return date('Y-m-d H:i:s');
} 