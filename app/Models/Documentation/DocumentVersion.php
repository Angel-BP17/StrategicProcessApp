<?php

namespace App\Models\Documentation;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    public $timestamps = false;
    protected $fillable = ['document_id', 'version_number', 'file_name', 'storage_path', 'mime_type', 'file_size', 'uploaded_by_user_id', 'uploaded_at', 'checksum', 'notes', 'linked_type', 'linked_id', 'created_at'];
     protected $casts = [
        'uploaded_at' => 'datetime',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }
}
