<?php

namespace App\Models\Planning;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $fillable = [
        'objetive_id',
        'name',
        'description',
        'target_value',
        'unit',
        'frecuency',
    ];

    public function objective()
    {
        return $this->belongsTo(StrategicObjetive::class, 'objetive_id');
    }
    public function measurements()
    {
        return $this->hasMany(KpiMeasurement::class);
    }
}
