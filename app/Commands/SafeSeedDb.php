<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SafeSeedDb extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:safeseed';
    protected $description = 'Safely runs all seeders with foreign key checks disabled';
    protected $usage       = 'db:safeseed [Options]';
    protected $arguments   = [];
    protected $options     = [
        '--seeder'  => 'Specific seeder to run (default: DatabaseSeeder)',
        '--reset'   => 'Reset tables before seeding',
    ];

    public function run(array $params)
    {
        // Parse options
        $seeder = $params['seeder'] ?? CLI::getOption('seeder') ?? 'DatabaseSeeder';
        $reset = array_key_exists('reset', $params) || CLI::getOption('reset');
        
        // Get database connection
        $db = \Config\Database::connect();
        
        if ($reset) {
            // Confirm before resetting data
            $confirm = CLI::prompt('This will delete ALL existing data. Continue?', ['y', 'n']);
            if ($confirm !== 'y') {
                CLI::write('Operation cancelled.', 'red');
                return;
            }
            
            CLI::write('Temporarily disabling foreign key checks for reset...', 'yellow');
            $db->query('SET FOREIGN_KEY_CHECKS=0');
            
            // Get all tables
            $tables = $db->listTables();
            
            // Filter out CodeIgniter system tables
            $tables = array_filter($tables, function($table) {
                return !in_array($table, ['migrations', 'ci_sessions']);
            });
            
            // Truncate all tables
            foreach ($tables as $table) {
                try {
                    $db->table($table)->truncate();
                    CLI::write("Truncated table: $table", 'green');
                } catch (\Exception $e) {
                    CLI::write("Could not truncate $table: " . $e->getMessage(), 'red');
                    
                    // Try emptying instead
                    try {
                        $db->table($table)->emptyTable();
                        CLI::write("Emptied table: $table", 'yellow');
                    } catch (\Exception $e2) {
                        CLI::write("Failed to empty $table: " . $e2->getMessage(), 'red');
                    }
                }
            }
            
            // Re-enable foreign key checks
            $db->query('SET FOREIGN_KEY_CHECKS=1');
            CLI::write('Foreign key checks re-enabled', 'green');
        }

        // Run the seeder with foreign key checks disabled
        CLI::write('Temporarily disabling foreign key checks for seeding...', 'yellow');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        
        CLI::write("Running seeder: $seeder", 'green');
        $command = "php spark db:seed $seeder";
        passthru($command);
        
        // Re-enable foreign key checks
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        CLI::write('Foreign key checks re-enabled', 'green');
        
        CLI::write('Seeding operation completed.', 'green');
    }
}
