<?php

namespace App\Services\Documentation;

use App\Models\Documentation\Document;
use App\Models\Documentation\DocumentVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentVersionManager
{
    protected string $disk;
    protected string $directory;

    public function __construct()
    {
        $this->disk = config('documentation.storage_disk', config('filesystems.default', 'local'));
        $this->directory = trim(config('documentation.storage_directory', 'documents'), '/');
    }

    public function createVersion(Document $document, UploadedFile $file, int $userId, ?string $notes = null): DocumentVersion
    {
        $versionNumber = (int) $document->versions()->max('version_number') + 1;

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: '');
        $sanitizedTitle = Str::slug(Str::limit($document->title, 80, ''));
        $timestamp = now()->format('Ymd_His');
        $storedFileName = Arr::join(array_filter([
            $sanitizedTitle ?: 'documento',
            'v' . $versionNumber,
            $timestamp,
        ]), '_');

        if ($extension !== '') {
            $storedFileName .= '.' . $extension;
        }

        $path = $file->storeAs(
            $this->pathForDocument($document->id),
            $storedFileName,
            $this->disk
        );

        if ($path === false) {
            throw new \RuntimeException('No se pudo almacenar el archivo subido.');
        }

        $fullPath = Storage::disk($this->disk)->path($path);
        $checksum = is_file($fullPath) ? hash_file('sha256', $fullPath) : null;

        $timestampNow = now();

        $version = $document->versions()->create([
            'version_number' => $versionNumber,
            'file_name' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by_user_id' => $userId,
            'uploaded_at' => $timestampNow,
            'checksum' => $checksum,
            'notes' => $notes,
            'created_at' => $timestampNow,
        ]);

        $document->syncCurrentVersion($version);

        return $version;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    protected function pathForDocument(int $documentId): string
    {
        return $this->directory . '/' . $documentId;
    }
}