<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class GcsStorageService
{
    /**
     * Upload a file to GCS and return metadata.
     *
     * @return array{
     *     public_id:string,
     *     object_path:string,
     *     url:?string,
     *     signed_url:?string,
     *     folder:?string,
     *     format:?string,
     *     bytes:int|null,
     *     mime_type:?string
     * }
     */
    public function upload(UploadedFile $file, string $folder = 'strategic-documents'): array
    {
        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Prefix is applied by the adapter; keep the stored path without prefix to avoid duplication
        $prefix = trim((string) config('filesystems.disks.gcs.path_prefix', ''), '/');
        $folder = trim($folder, '/');
        $objectPath = "{$folder}/{$filename}";

        $disk = Storage::disk('gcs');
        $bucket = config('filesystems.disks.gcs.bucket') ?? env('GOOGLE_CLOUD_STORAGE_BUCKET');
        $uniform = config('filesystems.disks.gcs.uniform_bucket_level_access', false);

        $sourcePath = $file->getRealPath() ?: $file->getPathname();
        if (!is_string($sourcePath) || $sourcePath === '' || is_dir($sourcePath)) {
            throw new RuntimeException('Ruta de archivo invalida para el upload');
        }

        $stream = @fopen($sourcePath, 'rb');
        if ($stream === false) {
            throw new RuntimeException('No se pudo abrir el archivo subido para lectura');
        }

        try {
            $uploaded = $disk->put(
                $objectPath,
                $stream,
                [
                    // In Uniform Bucket-Level Access, ACLs are not allowed; only metadata
                    'visibility' => $uniform ? null : 'private',
                    'metadata' => [
                        'contentType' => $file->getMimeType(),
                    ],
                ]
            );

            if ($uploaded === false) {
                Log::error('GCS put returned false', [
                    'bucket' => $bucket ?? 'unknown',
                    'object_path' => $objectPath,
                    'path_prefix' => $prefix,
                ]);
                throw new RuntimeException("No se pudo subir el archivo a GCS: put() returned false ({$objectPath})");
            }
        } catch (\Throwable $e) {
            Log::error('No se pudo subir el archivo a GCS', [
                'exception' => $e->getMessage(),
                'object_path' => $objectPath,
                'bucket' => $bucket ?? 'unknown',
                'path_prefix' => $prefix,
            ]);
            throw new RuntimeException('No se pudo subir el archivo a GCS: ' . $e->getMessage(), 0, $e);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $pathForUrl = $prefix ? "{$prefix}/{$objectPath}" : $objectPath;

        // Short URL without query params for DB storage
        $shortUrl = $bucket
            ? "https://storage.googleapis.com/{$bucket}/{$pathForUrl}"
            : null;

        // Signed URL for serving (temporary)
        $signedUrl = $this->temporaryUrl($objectPath, 60 * 24);

        return [
            'public_id' => $objectPath,
            'object_path' => $objectPath,
            'secure_url' => $shortUrl,
            'url' => $shortUrl,
            'signed_url' => $signedUrl,
            'folder' => dirname($objectPath),
            'format' => $file->getClientOriginalExtension(),
            'bytes' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Delete a file from GCS using the stored public_id.
     */
    public function delete(string $publicId): void
    {
        Storage::disk('gcs')->delete($publicId);
    }

    /**
     * Generate a temporary signed URL for a stored object.
     */
    public function temporaryUrl(string $publicId, int $minutes = 60, array $options = []): ?string
    {
        try {
            return Storage::disk('gcs')->temporaryUrl(
                $publicId,
                now()->addMinutes($minutes),
                $options
            );
        } catch (\Throwable $e) {
            Log::warning('No se pudo generar URL firmada', [
                'exception' => $e->getMessage(),
                'public_id' => $publicId,
            ]);
            return null;
        }
    }
}
