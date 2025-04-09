<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Commands extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Commands Namespaces
     * --------------------------------------------------------------------------
     *
     * This is an array of namespaces that holds all commands available to the CLI.
     *
     * @var string[]
     */
    public $namespaces = [
        'CodeIgniter\Commands',
        'App\Commands',
    ];

    /**
     * --------------------------------------------------------------------------
     * Commands
     * --------------------------------------------------------------------------
     *
     * This is an array of Command classnames for Commands.
     * They should be classes that extend \CodeIgniter\CLI\BaseCommand.
     *
     * @var string[]
     */
    public $commands = [
        'db:seed:module' => \App\Commands\RunSeeder::class,
    ];
} 