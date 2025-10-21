<?php

namespace App\Models\Documentation;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    public $timestamps = false;
    protected $fillable = ['document_id', 'version_number', 'file_name', 'storage_path', 'mime_type', 'file_size', 'uploaded_by_user_id', 'uploaded_at', 'checksum', 'notes', 'linked_type', 'linked_id', 'created_at'];
}
