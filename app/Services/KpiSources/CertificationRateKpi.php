<?php
// app/Services/KpiSources/CertificationRateKpi.php
namespace App\Services\KpiSources;
use Illuminate\Support\Facades\DB;

class CertificationRateKpi
{
    // KPI: % con certificado entre egresados del periodo
    public static function rate(\DateTimeInterface $from, \DateTimeInterface $to): float
    {
        $grads = DB::table('graduates')
            ->whereBetween('graduation_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->where('state', 'graduated')
            ->pluck('user_id');

        if ($grads->isEmpty())
            return 0.0;

        $certs = DB::table('certificates')->whereIn('user_id', $grads)->count(); // :contentReference[oaicite:29]{index=29}
        return round(($certs / max(1, $grads->count())) * 100, 2);
    }
}
