<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
		'loginUser'    		=> \App\Filters\LoginUserFilter::class,
		'guestUser' 		=> \App\Filters\GuestUserFilter::class,
		'manager' 		=> \App\Filters\ManagerFilter::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
			'csrf' => ['except' => []],
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don’t expect could bypass the filter.
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [
		'loginUser' => [
			'before' => [
				'users*',
				'roles*',
				'permissions*',
				'settings*',
				'nguoidung*',
				'login/logout'
			]
		],

		'guestUser' => [
			'before' => [
                'login*',
                'login/admin*'
            ],
            'except' => [
                '/'  // Không áp dụng filter cho API
            ]
		],

		'manager' => [
			'before' => [
				'users*',
				'roles*',
				'permissions*',
				'settings*',
				'admin*',
				'nguoidung*',
			]
		],
	];
}
