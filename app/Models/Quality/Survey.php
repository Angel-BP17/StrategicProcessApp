<?php

namespace App\Models\Quality;

use App\Models\User; // Importamos User
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla (Laravel buscaría 'surveys' por defecto, así que esto es correcto).
     */
    protected $table = 'surveys';

    /**
     * Atributos asignables masivamente.
     * Añadimos 'description' y 'status' que agregamos con el script.
     */
    protected $fillable = [
        'title',
        'description', // Nueva columna
        'target_type',
        'status',      // Nueva columna
        'created_by_user_id',
    ];

    /**
     * La tabla ya tiene 'created_at' y 'updated_at'.
     * Laravel los manejará automáticamente (timestamps = true por defecto).
     */

    // --- Relaciones ---

    /**
     * Una encuesta tiene muchas preguntas.
     */
    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class); // Apunta al nuevo modelo de pregunta
    }

    /**
     * Una encuesta fue creada por un usuario.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

     /**
     * Una encuesta tiene muchas asignaciones.
     */
    public function assignments()
    {
         return $this->hasMany(SurveyAssignment::class); // Apunta al nuevo modelo de asignación
    }
}