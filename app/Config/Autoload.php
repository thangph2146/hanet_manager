<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 * the values in this file will overwrite the framework's values.
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     *```
     *   $psr4 = [
     *       'CodeIgniter' => SYSTEMPATH,
     *       'App'	       => APPPATH
     *   ];
     *```
     *
     * @var array<string, string>
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'App\Modules\nguoidung' => APPPATH . 'Modules/nguoidung',
        'App\Modules\sukien' => APPPATH . 'Modules/sukien',
        'App\Modules\login' => APPPATH . 'Modules/login',
        'App\Modules\students' => APPPATH . 'Modules/students',
        'App\Modules\loainguoidung' => APPPATH . 'Modules/loainguoidung',
        'App\Modules\phongkhoa' => APPPATH . 'Modules/phongkhoa',
        'App\Modules\khoahoc' => APPPATH . 'Modules/khoahoc',
        'App\Modules\bachoc' => APPPATH . 'Modules/bachoc',
        'App\Modules\hedaotao' => APPPATH . 'Modules/hedaotao',
        'App\Modules\nganh' => APPPATH . 'Modules/nganh',
        'App\Modules\loaisukien' => APPPATH . 'Modules/loaisukien',
        'App\Modules\facenguoidung' => APPPATH . 'Modules/facenguoidung',
        'App\Modules\manhinh' => APPPATH . 'Modules/manhinh',
        'App\Modules\camera' => APPPATH . 'Modules/camera',
        'App\Modules\template' => APPPATH . 'Modules/template',
        'App\Modules\diengia' => APPPATH . 'Modules/diengia',
        'App\Modules\thamgiasukien' => APPPATH . 'Modules/thamgiasukien',
        'App\Modules\sukien' => APPPATH . 'Modules/sukien',
        'App\Modules\sukiendiengia' => APPPATH . 'Modules/sukiendiengia',
        'App\Modules\dangkysukien' => APPPATH . 'Modules/dangkysukien',
        'App\Modules\quanlybachoc' => APPPATH . 'Modules/quanlybachoc',
        'App\Modules\quanlycamera' => APPPATH . 'Modules/quanlycamera',
        'App\Modules\quanlydangkysukien' => APPPATH . 'Modules/quanlydangkysukien',
        'App\Modules\quanlynguoidung' => APPPATH . 'Modules/quanlynguoidung',
        'App\Modules\quanlyloaisukien' => APPPATH . 'Modules/quanlyloaisukien',
        'App\Modules\quanlysukien' => APPPATH . 'Modules/quanlysukien',
        'App\Modules\quanlycheckinsukien' => APPPATH . 'Modules/quanlycheckinsukien',
        'App\Modules\quanlycheckoutsukien' => APPPATH . 'Modules/quanlycheckoutsukien',
        'App\Modules\namhoc' => APPPATH . 'Modules/namhoc',
    ];


    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     *```
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     *```
     *
     * @var array<string, string>
     */
    public $classmap = [];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     * ```
     *	  $files = [
     *	 	   '/path/to/my/file.php',
     *    ];
     * ```
     *
     * @var array<int, string>
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Auto-load Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *
     *     $helpers = ['url', 'file'];
     */
    public $helpers = [
        'url',
        'html',
        'form',
        'security',
        'array',
        'my_string',
        'locale',
        'time',
    ];
}
