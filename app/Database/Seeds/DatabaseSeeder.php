<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "========== RUNNING MASTER DATABASE SEEDER ==========\n";
        
        // Define seeders with dependencies
        $seeders = [
            // Base data seeders (no dependencies)
            'LoaiNguoiDungSeeder' => [],
            'LoaiSuKienSeeder' => [],
            'DienGiaSeeder' => [],
            
            // Events data (depends on base data)
            'SuKienSeeder' => ['LoaiSuKienSeeder'],
            'SuKienDienGiaSeeder' => ['SuKienSeeder', 'DienGiaSeeder'],
            
            // Event participation (depends on events)
            'CheckInSuKienSeeder' => ['SuKienSeeder'],
            'CheckOutSuKienSeeder' => ['CheckInSuKienSeeder'],
            'DangKySuKienSeeder' => ['SuKienSeeder', 'CheckInSuKienSeeder', 'CheckOutSuKienSeeder'],
        ];
        
        // Track which seeders have been run
        $completed = [];
        $success = 0;
        $failed = 0;
        $skipped = 0;
        
        // Run seeders, respecting dependencies
        while (count($completed) < count($seeders)) {
            $ranThisRound = false;
            
            foreach ($seeders as $seeder => $dependencies) {
                // Skip if already completed
                if (in_array($seeder, $completed)) {
                    continue;
                }
                
                // Check if all dependencies are met
                $dependenciesMet = true;
                foreach ($dependencies as $dependency) {
                    if (!in_array($dependency, $completed)) {
                        $dependenciesMet = false;
                        break;
                    }
                }
                
                // If dependencies not met, skip for now
                if (!$dependenciesMet) {
                    continue;
                }
                
                // Run this seeder
                echo "\n----------- Running $seeder -----------\n";
                try {
                    $this->call($seeder);
                    $success++;
                    $completed[] = $seeder;
                    $ranThisRound = true;
                    echo "✓ $seeder completed successfully.\n";
                } catch (\Exception $e) {
                    $failed++;
                    echo "✗ Error in $seeder: " . $e->getMessage() . "\n";
                    
                    // Provide solution for common errors
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        echo "→ Solution: The seeder is trying to insert duplicate data. Try running with --reset option.\n";
                    }
                    
                    // Mark as completed to avoid dependency deadlocks
                    $completed[] = $seeder;
                }
            }
            
            // If nothing ran this round, we have a circular dependency or all seeders are complete
            if (!$ranThisRound) {
                $remaining = array_diff(array_keys($seeders), $completed);
                if (!empty($remaining)) {
                    echo "\n⚠️ Could not run some seeders due to unmet dependencies: " . implode(', ', $remaining) . "\n";
                    $skipped = count($remaining);
                }
                break;
            }
        }
        
        echo "\n========== DATABASE SEEDING SUMMARY ==========\n";
        echo "Total seeders: " . count($seeders) . "\n";
        echo "Successful: $success\n";
        echo "Failed: $failed\n";
        echo "Skipped: $skipped\n";
        
        if ($failed > 0 || $skipped > 0) {
            echo "\nSome seeders failed or were skipped. Try again with:\n";
            echo "php spark db:safeseed --reset\n";
        } else {
            echo "\nAll seeders completed successfully!\n";
        }
    }
}
