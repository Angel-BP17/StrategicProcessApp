<?php

namespace App\Models\Quality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionCriterion extends Model
{
    use HasFactory;

    protected $table = 'option_criteria';
    public $timestamps = false; // No tiene timestamps
    protected $primaryKey = 'id'; // Verifica si es 'id' o 'id_option_criteria'

    protected $fillable = [
        'id_evaluation_criteria',
        'option_text',
    ];

    /**
     * Relación: Una opción pertenece a un criterio de evaluación.
     */
    public function criterion()
    {
        return $this->belongsTo(EvaluationCriterion::class, 'id_evaluation_criteria');
    }
}