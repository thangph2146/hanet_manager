<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

/**
 * Helper để dễ dàng tham chiếu đến view trong module
 * 
 * @param string $view Đường dẫn tới view
 * @param string $module Tên module chứa view
 * @return string Đường dẫn đầy đủ đến view
 */
if (!function_exists('module_view')) {
    function module_view(string $view, string $module = ''): string
    {
        if (empty($module)) {
            return $view;
        }
        
        return 'App\Modules\\' . $module . '\Views\\' . $view;
    }
}

/**
 * Helper để dễ dàng tham chiếu đến layout chung
 * 
 * @param string $layout Tên file layout
 * @return string Đường dẫn đầy đủ đến layout
 */
if (!function_exists('common_layout')) {
    function common_layout(string $layout): string
    {
        return 'App\Modules\layouts\\' . $layout;
    }
}
