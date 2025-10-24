<?php

namespace App\Models\InnovacionMejoraContinua;

use App\Models\Collaboration\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Initiative extends Model
{
    use SoftDeletes;

    protected $table = 'initiatives';

    protected $fillable = [
        'plan_id',
        'title',
        'summary',
        'responsible_team_id',
        'responsible_user_id',
        'status',
        'start_date',
        'end_date',
        'estimated_impact',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relación con evaluaciones
    public function evaluations()
    {
        return $this->hasMany(InitiativeEvaluation::class, 'initiative_id');
    }

    // Relación con el usuario responsable
    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    // Relación con el equipo responsable
    public function responsibleTeam()
    {
        return $this->belongsTo(Team::class, 'responsible_team_id');
    }

    // Scope para filtrar por estado
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope para iniciativas activas
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['propuesta', 'evaluada', 'aprobada', 'implementada']);
    }
}
