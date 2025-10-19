<?php

namespace App\Models\Quality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriterion extends Model
{
    use HasFactory;

    protected $table = 'evaluation_criteria';

    // Tu tabla no tiene timestamps 'created_at'/'updated_at'
    public $timestamps = false;

    // La PK parece ser 'id', pero confirma si es 'id_evaluation_criteria'
    protected $primaryKey = 'id';

    protected $fillable = [
        'criterion_name',
        'category', // Tipo TIMESTAMPTZ? Raro, verificar tipo de dato. Asumiendo Varchar por ahora.
        'response_type', // 'numeric', 'text', 'option'
        'percentage_weight',
        'state', // Asumiendo Varchar: 'active', 'inactive'?
    ];

    /**
     * Relación: Un criterio de tipo 'option' tiene muchas opciones.
     */
    public function options()
    {
        // Asegúrate que la FK en option_criteria sea 'id_evaluation_criteria'
        return $this->hasMany(OptionCriterion::class, 'id_evaluation_criteria');
    }
}