<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $fillable = [
        'provider',
        'disk',
        'public_id',
        'resource_type',
        'format',
        'secure_url',
        'thumbnail_url',
        'folder',
        'original_name',
        'mime_type',
        'bytes',
        'width',
        'height',
        'meta',
        'uploaded_by',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
