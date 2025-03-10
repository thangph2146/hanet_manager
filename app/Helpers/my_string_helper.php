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
