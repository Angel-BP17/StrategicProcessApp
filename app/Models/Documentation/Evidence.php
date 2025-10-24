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

    protected $table = 'evidences';

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

    public function getTargetTypeLabelAttribute(): ?string
    {
        return match (true) {
            (bool) $this->initiative_id => 'Iniciativa',
            (bool) $this->audit_id => 'Auditoría',
            (bool) $this->accreditation_id => 'Acreditación',
            (bool) $this->kpi_measurement_id => 'Medición KPI',
            default => null,
        };
    }

    public function getTargetNameAttribute(): ?string
    {
        if ($this->initiative) {
            return $this->initiative->title;
        }

        if ($this->audit) {
            $range = collect([$this->audit->start_date, $this->audit->end_date])
                ->filter()
                ->map(function ($value) {
                    try {
                        return Carbon::parse($value)->format('d/m/Y');
                    } catch (\Throwable $th) {
                        return $value;
                    }
                })
                ->implode(' - ');

            return trim(($this->audit->area ?: 'Auditoría') . ($range ? " ({$range})" : ''));
        }

        if ($this->accreditation) {
            try {
                $date = $this->accreditation->accreditation_date
                    ? Carbon::parse($this->accreditation->accreditation_date)->format('d/m/Y')
                    : null;
            } catch (\Throwable $th) {
                $date = $this->accreditation->accreditation_date;
            }

            return trim(($this->accreditation->entity ?: 'Acreditación') . ($date ? " ({$date})" : ''));
        }

        if ($this->kpiMeasurement) {
            $kpiName = $this->kpiMeasurement->kpi->name ?? 'KPI';
            $measurementDate = $this->kpiMeasurement->measured_at;

            if ($measurementDate) {
                try {
                    $measurementDate = Carbon::parse($measurementDate)->format('d/m/Y');
                } catch (\Throwable $th) {
                    // mantener valor original
                }
            }

            return trim($kpiName . ($measurementDate ? " ({$measurementDate})" : ''));
        }

        return null;
    }
}