<?php

/**
 * Locale Helper
 * 
 * This helper provides a polyfill for the Locale class if it doesn't exist.
 * This can happen when the PHP intl extension is not properly configured.
 */

if (!class_exists('Locale')) {
    /**
     * Polyfill for the Locale class
     */
    class Locale {
        /**
         * Fallback setDefault method
         * 
         * @param string $locale The locale to set as default
         * @return bool Always returns true
         */
        public static function setDefault($locale) {
            // This is just a fallback that does nothing
            // but prevents the application from crashing
            log_message('warning', 'PHP intl extension is not properly configured. Using fallback Locale class.');
            return true;
        }
        
        /**
         * Fallback getDefault method
         * 
         * @return string Always returns 'en'
         */
        public static function getDefault() {
            return 'en';
        }
    }
} 