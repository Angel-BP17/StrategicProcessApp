<?php

namespace App\Models\Planning;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $fillable = [
        'objective_id',
        'name',
        'description',
        'target_value',
        'unit',
        'frequency',
    ];

    public function objective()
    {
        return $this->belongsTo(StrategicObjective::class, 'objective_id');
    }
    public function measurements()
    {
        return $this->hasMany(KpiMeasurement::class);
    }
}
