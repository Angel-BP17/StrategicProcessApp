<?php

namespace App\Models\Quality;

use App\Models\User; // Importamos el modelo User
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectiveAction extends Model
{
    use HasFactory;

    /**
     * Le decimos a Eloquent que no existe la columna 'created_at'.
     * La tabla 'corrective_actions' solo tiene 'created_at' y 'updated_at' (ambas).
     * PERO, revisando la DB, sí tiene created_at y updated_at. 
     * Así que NO necesitamos la línea 'const CREATED_AT = null;'.
     */

    protected $fillable = [
        'finding_id',
        'user_id', // El responsable de la acción
        'description',
        'status',
        'engagement_date', // Fecha de compromiso
        'due_date',        // Fecha límite
        'completion_date', // Fecha de completado
    ];

    // Relación: Una acción pertenece a un Hallazgo
    public function finding()
    {
        return $this->belongsTo(Finding::class);
    }

    // Relación: Una acción pertenece a un Usuario (responsable)
    public function responsible()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}