<?php

namespace App\Models\InnovacionMejoraContinua;

use Illuminate\Database\Eloquent\Model;

class InitiativeEvaluation extends Model
{
    protected $table = 'initiative_evaluations';

    protected $fillable = [
        'initiative_id',
        'evaluator_user_id',
        'evaluation_date',
        'summary',
        'score',
        'report_document_version_id'
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'score' => 'decimal:2',
        'created_at' => 'datetime'
    ];

    // Relación con la iniciativa
    public function initiative()
    {
        return $this->belongsTo(Initiative::class, 'initiative_id');
    }

    // Relación con el evaluador
    public function evaluator()
    {
        return $this->belongsTo(\App\Models\User::class, 'evaluator_user_id');
    }

    // Scope para ordenar por fecha más reciente
    public function scopeLatest($query)
    {
        return $query->orderBy('evaluation_date', 'desc');
    }
}
