<?php

return [
    'storage_disk' => env('DOCUMENT_STORAGE_DISK', 'local'),
    'storage_directory' => env('DOCUMENT_STORAGE_DIRECTORY', 'documents'),
    'max_upload_size_kb' => env('DOCUMENT_MAX_UPLOAD_SIZE_KB', 25600), // 25 MB
    'allowed_extensions' => [
        'pdf',
        'fpd',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'csv',
        'txt',
        'zip',
        'rar',
        'jpg',
        'jpeg',
        'png',
    ],
];