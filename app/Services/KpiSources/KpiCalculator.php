<?php
// app/Services/KpiCalculator.php
namespace App\Services\KpiSources;
use App\Models\Planning\Kpi;
use App\Models\Planning\KpiMeasurement;

class KpiCalculator
{
    public static function computeAndStore(Kpi $kpi, \DateTimeInterface $from, \DateTimeInterface $to): KpiMeasurement
    {
        // Mapea por nombre a la clase de origen
        $map = [
            'Graduados del periodo' => [\App\Services\KpiSources\GraduatesKpi::class, 'totalGraduates'],
            'Tasa de certificación' => [\App\Services\KpiSources\CertificationRateKpi::class, 'rate'],
            'Ingresos cobrados' => [\App\Services\KpiSources\RevenueKpi::class, 'income'],
        ];
        [$cls, $fn] = $map[$kpi->name] ?? [null, null];
        abort_if(!$cls, 422, 'KPI no soportado');

        $value = $cls::$fn($from, $to);
        return KpiMeasurement::create([
            'kpi_id' => $kpi->id,
            'measured_at' => now()->toDateString(),
            'value' => $value,
            'source' => class_basename($cls),
            'recorded_by_user_id' => auth()->id(),
        ]);
    }
}
