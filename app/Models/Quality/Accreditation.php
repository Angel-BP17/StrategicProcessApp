<?php

namespace App\Models\Quality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    
    protected $fillable = [
        'entity',
        'accreditation_date',
        'expiration_date',
        'result',
        'document_version_id',
    ];

    /**
     * La tabla no usa 'created_at' ni 'updated_at' por defecto,
     * pero el script SQL sí las incluye.
     * Así que dejamos que Laravel las maneje (timestamps = true).
     */

    // Aquí podríamos añadir la relación con 'document_versions'
    // public function document()
    // {
    //     return $this->belongsTo(DocumentVersion::class, 'document_version_id');
    // }
}