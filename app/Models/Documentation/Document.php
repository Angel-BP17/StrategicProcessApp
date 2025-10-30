<?php

namespace App\Models\Documentation;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Schema;

class Document extends Model
{
    public const CATEGORIES = ['academic', 'administrative', 'legal'];
    public const STATUSES = ['draft', 'active', 'archived'];
    protected $table = 'documents';
    protected $fillable = ['title', 'category', 'entity_type', 'entity_id', 'version', 'status', 'file_path', 'created_by'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function latestVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'current_version_id');
    }

    public function histories()
    {
        return $this->hasMany(DocumentHistory::class);
    }

    public function evidences()
    {
        return $this->hasManyThrough(Evidence::class, DocumentVersion::class);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function syncCurrentVersion(DocumentVersion $version): void
    {
        if ($version->document_id !== $this->id) {
            return;
        }

        if (Schema::hasColumn($this->getTable(), 'current_version_id')) {
            $this->forceFill(['current_version_id' => $version->id])->save();
            $this->setRelation('latestVersion', $version);
        }
    }
}
