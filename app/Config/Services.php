<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

	public static function auth($getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('auth');
		}

		return new \App\Libraries\Authentication;
	}

	public static function authnguoidung($getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('authnguoidung');
		}

		return new \App\Libraries\AuthenticationNguoiDung;
	}
	
	public static function googleAuth($getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('googleAuth');
		}

		return new \App\Libraries\GoogleAuthentication;
	}
	
	public static function locale($getShared = true)
	{
		if ($getShared)
		{
			return static::getSharedInstance('locale');
		}

		return new \App\Config\Locale();
	}

	/**
	 * Custom Time class to avoid IntlDateFormatter issues
	 */
	public static function time($time = null, $timezone = null, $locale = null)
	{
		return new \App\Libraries\CustomTime($time, $timezone, $locale);
	}
}
