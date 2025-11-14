<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\StorageSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\MimeTypeDetection\ExtensionMimeTypeDetector;
use Aws\S3\S3Client;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure Flysystem to use extension-based MIME detection if finfo is not available
        // This prevents "Class finfo not found" errors on servers without fileinfo extension
        if (!class_exists('finfo') && !function_exists('finfo_open')) {
            // Configure local disk adapter
            Storage::extend('local', function ($app, $config) {
                $adapter = new LocalFilesystemAdapter(
                    $config['root'],
                    PortableVisibilityConverter::fromArray([
                        'file' => [
                            'public' => 0644,
                            'private' => 0600,
                        ],
                        'dir' => [
                            'public' => 0755,
                            'private' => 0700,
                        ],
                    ]),
                    \LOCK_EX,
                    LocalFilesystemAdapter::DISALLOW_LINKS,
                    new ExtensionMimeTypeDetector(), // Use extension-based instead of finfo
                    false,
                    false
                );

                return new \Illuminate\Filesystem\FilesystemAdapter(
                    new \League\Flysystem\Filesystem($adapter, $config),
                    $adapter,
                    $config
                );
            });

            // Configure S3 adapter to use extension-based MIME detection
            Storage::extend('s3', function ($app, $config) {
                $client = new S3Client([
                    'credentials' => [
                        'key' => $config['key'],
                        'secret' => $config['secret'],
                    ],
                    'region' => $config['region'],
                    'version' => 'latest',
                    'endpoint' => $config['endpoint'] ?? null,
                    'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
                ]);

                // Constructor signature:
                // __construct(
                //     S3ClientInterface $client,
                //     string $bucket,
                //     string $prefix = '',
                //     ?VisibilityConverter $visibility = null,
                //     ?MimeTypeDetector $mimeTypeDetector = null,
                //     ...
                // )
                $adapter = new AwsS3V3Adapter(
                    $client, // S3Client
                    $config['bucket'], // bucket
                    $config['prefix'] ?? '', // prefix
                    null, // visibility converter (uses default PortableVisibilityConverter)
                    new ExtensionMimeTypeDetector() // mimeTypeDetector - use extension-based instead of finfo
                );

                return new \Illuminate\Filesystem\FilesystemAdapter(
                    new \League\Flysystem\Filesystem($adapter, $config),
                    $adapter,
                    $config
                );
            });
        }

        if (Schema::hasTable('site_settings')) {
            view()->share('siteSettings', SiteSetting::getSettings());
        } else {
            view()->share('siteSettings', null);
        }

        if (Schema::hasTable('storage_settings')) {
            $storageSettings = StorageSetting::getSettings();
            $storageSettings->applyToConfig();
            view()->share('storageSettings', $storageSettings);
        } else {
            view()->share('storageSettings', null);
        }
    }
}
