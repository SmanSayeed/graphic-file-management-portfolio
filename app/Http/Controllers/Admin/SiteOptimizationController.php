<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SiteOptimizationController extends Controller
{
    /**
     * Create storage link
     */
    public function createStorageLink()
    {
        try {
            $storagePath = public_path('storage');
            
            // Remove existing link if it exists
            if (file_exists($storagePath)) {
                if (is_link($storagePath)) {
                    // Remove existing symbolic link
                    if (PHP_OS_FAMILY === 'Windows') {
                        // Windows: use rmdir for symlinks
                        rmdir($storagePath);
                    } else {
                        // Unix-like: use unlink
                        unlink($storagePath);
                    }
                } else {
                    // If it's a directory, we can't just delete it
                    return response()->json([
                        'success' => false,
                        'message' => 'Storage directory already exists and is not a symbolic link. Please remove it manually from: ' . $storagePath,
                    ], 400);
                }
            }

            // Create the storage link
            Artisan::call('storage:link');
            $output = trim(Artisan::output());

            return response()->json([
                'success' => true,
                'message' => 'Storage link created successfully.',
                'output' => $output ?: 'Storage link created.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create storage link: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear all caches and optimization files
     */
    public function clearOptimization()
    {
        try {
            $output = [];
            $hasErrors = false;
            
            // Clear individual caches first (more reliable)
            $commands = [
                'config:clear' => 'Clear configuration cache',
                'route:clear' => 'Clear route cache',
                'view:clear' => 'Clear view cache',
                'event:clear' => 'Clear event cache',
            ];

            foreach ($commands as $command => $description) {
                try {
                    Artisan::call($command);
                    $cmdOutput = trim(Artisan::output());
                    $output[] = [
                        'command' => $command,
                        'description' => $description,
                        'status' => 'success',
                        'output' => $cmdOutput ?: 'Command executed successfully.',
                    ];
                } catch (\Exception $e) {
                    $hasErrors = true;
                    $output[] = [
                        'command' => $command,
                        'description' => $description,
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ];
                }
            }

            // Try to clear application cache (may fail if cache table doesn't exist or DB is not connected)
            try {
                Artisan::call('cache:clear');
                $cmdOutput = trim(Artisan::output());
                $output[] = [
                    'command' => 'cache:clear',
                    'description' => 'Clear application cache',
                    'status' => 'success',
                    'output' => $cmdOutput ?: 'Command executed successfully.',
                ];
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                // Check for common database-related errors
                if (str_contains($errorMsg, "Table") && str_contains($errorMsg, "doesn't exist")) {
                    $output[] = [
                        'command' => 'cache:clear',
                        'description' => 'Clear application cache',
                        'status' => 'warning',
                        'message' => 'Cache table does not exist. Run: php artisan migrate',
                    ];
                } elseif (str_contains($errorMsg, "connection") || str_contains($errorMsg, "Connection") || str_contains($errorMsg, "refused")) {
                    $output[] = [
                        'command' => 'cache:clear',
                        'description' => 'Clear application cache',
                        'status' => 'warning',
                        'message' => 'Database connection failed. Cache uses database driver. Ensure MySQL is running.',
                    ];
                } else {
                    $hasErrors = true;
                    $output[] = [
                        'command' => 'cache:clear',
                        'description' => 'Clear application cache',
                        'status' => 'error',
                        'message' => $errorMsg,
                    ];
                }
            }

            // Try optimize:clear last (it may fail if cache:clear failed, but other clears should work)
            try {
                Artisan::call('optimize:clear');
                $cmdOutput = trim(Artisan::output());
                $output[] = [
                    'command' => 'optimize:clear',
                    'description' => 'Clear all optimization files',
                    'status' => 'success',
                    'output' => $cmdOutput ?: 'Command executed successfully.',
                ];
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                // Check if it's a database-related error
                if (str_contains($errorMsg, "connection") || str_contains($errorMsg, "Connection") || 
                    str_contains($errorMsg, "refused") || (str_contains($errorMsg, "Table") && str_contains($errorMsg, "doesn't exist"))) {
                    $output[] = [
                        'command' => 'optimize:clear',
                        'description' => 'Clear all optimization files',
                        'status' => 'warning',
                        'message' => 'Failed due to database issue. Other caches were cleared successfully.',
                    ];
                } else {
                    // Don't mark as error if other commands succeeded
                    $output[] = [
                        'command' => 'optimize:clear',
                        'description' => 'Clear all optimization files',
                        'status' => 'warning',
                        'message' => $errorMsg . ' (Some caches may have been cleared successfully)',
                    ];
                }
            }

            $successCount = count(array_filter($output, fn($item) => $item['status'] === 'success'));
            $message = $successCount > 0 
                ? "Cleared {$successCount} cache(s) successfully." 
                : 'Some commands failed. Check details below.';

            return response()->json([
                'success' => $successCount > 0,
                'message' => $message,
                'commands' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear optimization: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cache optimization (optimize the application)
     */
    public function cacheOptimization()
    {
        try {
            $output = [];
            $successCount = 0;
            
            // Run individual cache commands first
            // Note: 'optimize' command includes config:cache, route:cache, and view:cache
            // But we'll run them individually for better control and feedback
            $commands = [
                'config:cache' => 'Cache configuration files',
                'route:cache' => 'Cache routes',
                'view:cache' => 'Cache views',
                'event:cache' => 'Cache events',
            ];

            foreach ($commands as $command => $description) {
                try {
                    Artisan::call($command);
                    $cmdOutput = trim(Artisan::output());
                    $output[] = [
                        'command' => $command,
                        'description' => $description,
                        'status' => 'success',
                        'output' => $cmdOutput ?: 'Command executed successfully.',
                    ];
                    $successCount++;
                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                    // Check for database connection errors
                    if (str_contains($errorMsg, "connection") || str_contains($errorMsg, "Connection") || str_contains($errorMsg, "refused")) {
                        $output[] = [
                            'command' => $command,
                            'description' => $description,
                            'status' => 'warning',
                            'message' => 'Database connection failed. Ensure MySQL is running.',
                        ];
                    } else {
                        $output[] = [
                            'command' => $command,
                            'description' => $description,
                            'status' => 'error',
                            'message' => $errorMsg,
                        ];
                    }
                }
            }

            // Run optimize command as well (this may cache additional things)
            // Note: optimize may fail if individual cache commands failed, but that's okay
            try {
                Artisan::call('optimize');
                $cmdOutput = trim(Artisan::output());
                $output[] = [
                    'command' => 'optimize',
                    'description' => 'Optimize application',
                    'status' => 'success',
                    'output' => $cmdOutput ?: 'Command executed successfully.',
                ];
                $successCount++;
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                if (str_contains($errorMsg, "connection") || str_contains($errorMsg, "Connection") || str_contains($errorMsg, "refused")) {
                    $output[] = [
                        'command' => 'optimize',
                        'description' => 'Optimize application',
                        'status' => 'warning',
                        'message' => 'Failed due to database connection issue. Some caches may have been created successfully.',
                    ];
                } else {
                    $output[] = [
                        'command' => 'optimize',
                        'description' => 'Optimize application',
                        'status' => 'warning',
                        'message' => $errorMsg . ' (Some caches may have been created successfully)',
                    ];
                }
            }

            $message = $successCount > 0 
                ? "Optimized {$successCount} cache(s) successfully." 
                : 'Some commands failed. Check details below.';

            return response()->json([
                'success' => $successCount > 0,
                'message' => $message,
                'commands' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cache optimization: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Run database migrations
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            $output = trim(Artisan::output());

            return response()->json([
                'success' => true,
                'message' => 'Migrations completed successfully.',
                'output' => $output ?: 'All migrations have been run.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show optimization page
     */
    public function index()
    {
        // Check if storage link exists
        $storageLinkExists = file_exists(public_path('storage')) && is_link(public_path('storage'));
        
        // Check if storage directory exists (using direct filesystem check to avoid finfo dependency)
        $storagePath = storage_path('app/public');
        $storageExists = is_dir($storagePath) || file_exists($storagePath);

        // Check migration status
        try {
            Artisan::call('migrate:status');
            $migrationStatus = trim(Artisan::output());
            $hasPendingMigrations = str_contains($migrationStatus, 'Pending');
        } catch (\Exception $e) {
            $hasPendingMigrations = true;
            $migrationStatus = 'Unable to check migration status.';
        }

        return view('admin.optimization.index', [
            'storageLinkExists' => $storageLinkExists,
            'storageExists' => $storageExists,
            'hasPendingMigrations' => $hasPendingMigrations ?? true,
        ]);
    }
}

