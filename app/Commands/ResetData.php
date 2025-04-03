<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ResetData extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:reset';
    protected $description = 'Safely resets database tables for re-seeding';
    protected $usage       = 'db:reset [Options]';
    protected $arguments   = [];
    protected $options     = [
        '--force'  => 'Force reset without confirmation',
        '--tables' => 'Comma-separated list of specific tables to reset (default: all)'
    ];

    public function run(array $params)
    {
        // Get options
        $force = array_key_exists('force', $params) || CLI::getOption('force');
        $tableList = $params['tables'] ?? CLI::getOption('tables');
        
        // Get database connection
        $db = \Config\Database::connect();
        
        // If specific tables are requested, process them
        if ($tableList) {
            $tables = explode(',', $tableList);
        } else {
            // Get all tables
            $tables = $db->listTables();
        }
        
        // Filter out CI tables and migrations
        $tables = array_filter($tables, function($table) {
            return !in_array($table, ['migrations']);
        });
        
        // Confirm reset
        if (!$force) {
            CLI::write('This will reset the following tables:', 'yellow');
            foreach ($tables as $table) {
                CLI::write(' - ' . $table, 'yellow');
            }
            
            $confirm = CLI::prompt('Are you sure you want to reset these tables?', ['y', 'n']);
            if ($confirm !== 'y') {
                CLI::write('Operation cancelled.', 'red');
                return;
            }
        }
        
        CLI::write('Starting database reset...', 'green');
        
        // Disable foreign key checks temporarily
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Reset each table
        foreach ($tables as $table) {
            try {
                $db->table($table)->truncate();
                CLI::write("Table '$table' reset successfully", 'green');
            } catch (\Exception $e) {
                CLI::write("Error resetting table '$table': " . $e->getMessage(), 'red');
                
                // Try to empty the table instead
                try {
                    $db->table($table)->emptyTable();
                    CLI::write("Table '$table' emptied successfully using alternate method", 'yellow');
                } catch (\Exception $e) {
                    CLI::write("Could not empty table '$table': " . $e->getMessage(), 'red');
                }
            }
        }
        
        // Re-enable foreign key checks
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        
        CLI::write('Database reset completed. You can now run seeders safely.', 'green');
        CLI::write('To run all seeders: php spark db:seed DatabaseSeeder', 'blue');
    }
}
