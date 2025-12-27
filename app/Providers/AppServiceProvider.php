<?php

namespace App\Providers;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\GoogleCloudStorage\GoogleCloudStorageAdapter;
use League\Flysystem\GoogleCloudStorage\UniformBucketLevelAccessVisibility;
use League\Flysystem\Config as FlysystemConfig;
use League\Flysystem\Visibility;

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
        // Register Google Cloud Storage driver for Flysystem
        Storage::extend('gcs', function ($app, $config) {
            $clientConfig = [
                'projectId' => $config['project_id'] ?? null,
            ];

            if (!empty($config['key_file'])) {
                // Validamos que sea un archivo, no un directorio, para evitar errores de file_get_contents
                if (is_dir($config['key_file'])) {
                    throw new \RuntimeException("GOOGLE_APPLICATION_CREDENTIALS/GOOGLE_CLOUD_KEY_FILE apunta a un directorio: {$config['key_file']}");
                }
                if (!is_file($config['key_file'])) {
                    throw new \RuntimeException("Archivo de credenciales GCP no encontrado: {$config['key_file']}");
                }
                $clientConfig['keyFilePath'] = $config['key_file'];
            }

            $storageClient = new StorageClient($clientConfig);
            $bucket = $storageClient->bucket($config['bucket']);

            $visibilityHandler = !empty($config['uniform_bucket_level_access'])
                ? new UniformBucketLevelAccessVisibility()
                : null;
            $defaultVisibility = ($config['visibility'] ?? 'private') === 'public'
                ? Visibility::PUBLIC
                : Visibility::PRIVATE;

            $adapter = new GoogleCloudStorageAdapter(
                $bucket,
                $config['path_prefix'] ?? '',
                $visibilityHandler,
                $defaultVisibility
            );

            $filesystem = new Filesystem($adapter, ['visibility' => $config['visibility'] ?? 'private']);

            $filesystemAdapter = new FilesystemAdapter($filesystem, $adapter, $config);

            // Enable temporaryUrl by delegating to adapter->temporaryUrl
            $filesystemAdapter->buildTemporaryUrlsUsing(function ($path, $expiration, array $options = []) use ($adapter) {
                return $adapter->temporaryUrl($path, $expiration, new FlysystemConfig($options));
            });

            return $filesystemAdapter;
        });
    }
}
