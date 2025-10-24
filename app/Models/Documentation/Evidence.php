<?php

namespace App\Models\Documentation;

use App\Models\InnovacionMejoraContinua\Initiative;
use App\Models\Planning\KpiMeasurement;
use App\Models\Quality\Accreditation;
use App\Models\Quality\Audit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'initiative_id',
        'document_version_id',
        'kpi_measurement_id',
        'audit_id',
        'accreditation_id',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function documentVersion()
    {
        return $this->belongsTo(DocumentVersion::class);
    }

    public function initiative()
    {
        return $this->belongsTo(Initiative::class, 'initiative_id');
    }

    public function kpiMeasurement()
    {
        return $this->belongsTo(KpiMeasurement::class, 'kpi_measurement_id');
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class);
    }
}