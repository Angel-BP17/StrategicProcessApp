<?php

namespace App\Models\Documentation;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = ['title', 'category', 'entity_type', 'entity_id', 'version', 'status', 'file_path', 'created_by'];
}
