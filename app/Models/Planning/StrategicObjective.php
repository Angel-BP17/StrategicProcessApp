<?php

namespace App\Models\Planning;

use Illuminate\Database\Eloquent\Model;

class StrategicObjective extends Model
{
    protected $fillable = [
        'plan_id',
        'title',
        'description',
        'goal_value',
        'responsible_user_id',
        'weight',
    ];

    public function plan()
    {
        return $this->belongsTo(StrategicPlan::class, 'plan_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'reponsible_user_id');
    }
    public function kpis()
    {
        return $this->hasMany(Kpi::class, 'objetive_id');
    }
}
