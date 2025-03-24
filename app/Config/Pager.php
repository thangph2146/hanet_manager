<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Templates
     * --------------------------------------------------------------------------
     *
     * Pagination links are rendered out using views to configure their
     * appearance. This array contains aliases and the view names to
     * use when rendering the links.
     *
     * Within each view, the Pager object will be available as $pager,
     * and the desired group as $pagerGroup;
     *
     * @var array<string, string>
     */
    public $templates = [
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        'default_head'   => 'CodeIgniter\Pager\Views\default_head',
        'camera_full'    => 'App\Views\pagers\camera_full',
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     *
     * @var int
     */
    public $perPage = 10;
    
    /**
     * --------------------------------------------------------------------------
     * Surround Count
     * --------------------------------------------------------------------------
     *
     * The number of links displayed on each side of the current page.
     * By default it shows 2 links on each side.
     *
     * @var int
     */
    public $surroundCount = 2;
    
    /**
     * --------------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------------
     *
     * The default pagination group that should be used if none is specified.
     *
     * @var string
     */
    public $defaultGroup = 'default';
    
    /**
     * --------------------------------------------------------------------------
     * Use Query Strings
     * --------------------------------------------------------------------------
     *
     * Whether to use HTTP query strings to pass the parameters instead of URI segments.
     *
     * @var bool
     */
    public $useQueryString = true;
}
