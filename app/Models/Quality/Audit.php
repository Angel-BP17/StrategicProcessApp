<?php

namespace App\Models\Quality;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'area',
        'user_id', // El responsable
        'start_date',
        'end_date',
        'summary_results',
        'report_document_version_id',
        'type', // 'internal' o 'external'
        'state', // 'planned', 'in_progress', etc.
        'objective',
        'range', // 'range' es 'alcance' en español
    ];

    /**
     * Opcional, pero buena práctica:
     * Definir las relaciones con otras tablas.
     */
    
    // Relación: Una auditoría pertenece a un Usuario (responsable)
    public function responsible()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: Una auditoría tiene muchos Hallazgos
    public function findings()
    {
        return $this->hasMany(Finding::class);
    }

    // ... (luego agregaremos más relaciones) ...
}