<?php

namespace App\Models\Quality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla.
     */
    protected $table = 'survey_questions';

    /**
     * Atributos asignables.
     * Añadimos 'order'. Renombramos 'question_type'.
     */
    protected $fillable = [
        'survey_id',
        'question_text',
        'question_type', // Asegúrate que la columna se llame así
        'order',         // Nueva columna
    ];

    /**
     * Habilitar timestamps si los añadiste con el script.
     */
    public $timestamps = true; // O 'false' si no añadiste created_at/updated_at

    // --- Relaciones ---

    /**
     * Una pregunta pertenece a una encuesta.
     */
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    /**
     * Una pregunta (de opción múltiple) tiene muchas opciones.
     */
    public function options()
    {
        return $this->hasMany(SurveyOption::class); // Apunta al nuevo modelo de opción
    }

    /**
     * Una pregunta tiene muchas respuestas.
     */
    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class); // Apunta al nuevo modelo de respuesta
    }
}