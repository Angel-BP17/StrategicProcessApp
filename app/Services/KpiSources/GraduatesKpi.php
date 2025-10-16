<?php
// app/Services/KpiSources/GraduatesKpi.php
namespace App\Services\KpiSources;
use Illuminate\Support\Facades\DB;

class GraduatesKpi
{
    // KPI: Número de graduados en un rango de fechas
    public static function totalGraduates(\DateTimeInterface $from, \DateTimeInterface $to): int
    {
        return DB::table('graduates') // tabla común
            ->whereBetween('graduation_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->where('state', 'graduated')
            ->count();
    }
}
