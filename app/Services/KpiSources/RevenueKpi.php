<?php
namespace App\Services\KpiSources;
use Illuminate\Support\Facades\DB;

class RevenueKpi
{
    // KPI: Ingresos cobrados en el periodo
    public static function income(\DateTimeInterface $from, \DateTimeInterface $to): float
    {
        return (float) DB::table('payments')
            ->whereBetween('payment_date', [$from->format('Y-m-d'), $to->format('Y-m-d')])
            ->where('status', 'Completed')
            ->sum('amount'); // pagos contra facturas
    }
}
