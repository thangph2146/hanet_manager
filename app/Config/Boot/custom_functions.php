<?php

/**
 * Custom function overrides to avoid IntlDateFormatter dependency
 */

// Add this to a path that is always loaded early in the bootstrap process

// Only override if the IntlDateFormatter class doesn't exist
if (!class_exists('IntlDateFormatter')) {
    /**
     * Provide a simplified IntlDateFormatter::formatObject fallback
     *
     * @param mixed $datetime DateTime object or DateTimeInterface
     * @param string $format The format pattern
     * @param string $locale The locale code
     * @return string The formatted date
     */
    function formatDateTime($datetime, $format, $locale = null)
    {
        // Basic conversion of IntlDateFormatter patterns to PHP date format
        $replacements = [
            'yyyy' => 'Y',
            'MM' => 'm',
            'dd' => 'd',
            'HH' => 'H',
            'mm' => 'i',
            'ss' => 's'
        ];
        
        $format = str_replace(array_keys($replacements), array_values($replacements), $format);
        
        if (method_exists($datetime, 'format')) {
            return $datetime->format($format);
        }
        
        return date($format, strtotime($datetime));
    }
    
    // Create a simplified IntlDateFormatter class if it doesn't exist
    class IntlDateFormatter
    {
        public static function formatObject($datetime, $format, $locale = null)
        {
            return formatDateTime($datetime, $format, $locale);
        }
    }
} 