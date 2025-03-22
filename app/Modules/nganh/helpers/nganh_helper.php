// Kiểm tra xem helper đã tồn tại chưa
if (!function_exists('nganh_section_js')) {
    /**
     * Tải Javascript của section cụ thể trong module nganh
     *
     * @param string $section Tên section (form, table, etc.)
     * @return string
     */
    function nganh_section_js($section)
    {
        return '<script src="' . base_url('js/modules/nganh/sections/' . $section . '.js') . '"></script>';
    }
}

if (!function_exists('nganh_section_css')) {
    /**
     * Tải CSS của section cụ thể trong module nganh
     *
     * @param string $section Tên section (form, table, etc.)
     * @return string
     */
    function nganh_section_css($section)
    {
        return '<link rel="stylesheet" href="' . base_url('css/modules/nganh/sections/' . $section . '.css') . '">';
    }
}

if (!function_exists('nganh_js')) {
    /**
     * Tải Javascript chung của module nganh
     *
     * @param string $name Tên file JavaScript (form, table, view, etc.)
     * @return string
     */
    function nganh_js($name)
    {
        return '<script src="' . base_url('js/modules/nganh/' . $name . '.js') . '"></script>';
    }
}

if (!function_exists('nganh_css')) {
    /**
     * Tải CSS chung của module nganh
     *
     * @param string $name Tên file CSS (form, table, view, etc.)
     * @return string
     */
    function nganh_css($name)
    {
        return '<link rel="stylesheet" href="' . base_url('css/modules/nganh/' . $name . '.css') . '">';
    }
} 