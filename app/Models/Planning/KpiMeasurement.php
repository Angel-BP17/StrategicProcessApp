<?php

namespace App\Models\Planning;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class KpiMeasurement extends Model
{

    const UPDATED_AT = null;
    protected $fillable = [
        'kpi_id',
        'measured_at',
        'value',
        'source',
        'recorded_by_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
    public function kpi()
    {
        return $this->belongsTo(Kpi::class, 'kpi_id');
    }
}
