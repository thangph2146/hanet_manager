<?php
/**
 * 9/17/2022
 * AUTHOR:PDV-PC
 */
// Hàm cắt bỏ khoảng trắng dư thừa
if(!function_exists('remove_spaces'))
{
	function remove_spaces($str)
	{
		$str = str_replace('%', ' ', trim($str));
		return $str;
	}
}

if(!function_exists('sub_string'))
{
	function sub_string($sub_string, $str)
	{
		if (substr($str, 0, strlen($sub_string)) == $sub_string)
		{
			$str = substr($str, strlen($sub_string));
		}

		return strtolower($str);
	}
}

/**
 * Các hàm xử lý chuỗi bổ sung
 */
 
/**
 * Làm ngắn chuỗi với dấu "..."
 *
 * @param string $str Chuỗi cần rút gọn
 * @param int $n Độ dài tối đa
 * @param bool $strip_tags Loại bỏ các thẻ HTML hay không
 * @return string
 */
function shorten_string($str, $n = 155, $strip_tags = true) {
    if (empty($str)) return '';
    if ($strip_tags) {
        $str = strip_tags($str);
    }
    $str = trim($str);
    if (strlen($str) <= $n) return $str;
    $out = substr($str, 0, $n);
    $pos = strrpos($out, ' ');
    if ($pos > 0) {
        $out = substr($out, 0, $pos);
    }
    return $out . '...';
}

/**
 * Kiểm tra xem request hiện tại có phải là AJAX hay không
 *
 * @return bool
 */
function is_ajax() {
    $request = service('request');
    return $request->isAJAX() || 
           $request->hasHeader('X-Requested-With') && 
           strtolower($request->getHeaderLine('X-Requested-With')) == 'xmlhttprequest';
}
