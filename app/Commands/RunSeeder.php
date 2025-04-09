<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Seeder;

class RunSeeder extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:seed:module';
    protected $description = 'Runs the specified module seeder to populate known data into the database.';
    protected $usage       = 'db:seed:module [moduleName] [seederName]';
    protected $arguments   = [
        'moduleName'  => 'The module name containing the seeder',
        'seederName'  => 'The seeder name to run (without "Seeder" suffix)',
    ];

    public function run(array $params)
    {
        $module = $params[0] ?? CLI::prompt('Module name');
        $seeder = $params[1] ?? CLI::prompt('Seeder name (without "Seeder" suffix)');
        
        // Format the seeder path for modules
        $seederClass = "App\\Modules\\{$module}\\Database\\Seeds\\{$seeder}Seeder";
        
        if (!class_exists($seederClass)) {
            CLI::error("Seeder {$seederClass} not found.");
            return;
        }
        
        $seeder = new Seeder(config('Database'));
        $seeder->call($seederClass);
        
        CLI::write("Seeder {$seederClass} executed successfully.", 'green');
    }
} 