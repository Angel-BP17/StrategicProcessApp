<?php

namespace App\Http\Controllers;

use App\Models\Planning\StrategicObjective;
use App\Models\Planning\StrategicPlan;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plan = StrategicPlan::latest('id')->with('dashboards')->first();

        $objectives = StrategicObjective::with(['kpis.measurements' => fn ($q) => $q->limit(1)])
            ->when($plan, fn ($q) => $q->where('plan_id', $plan->id))
            ->latest('id')
            ->take(5)
            ->get();

        return response()->json([
            'plan' => $plan,
            'objectives' => $objectives,
        ]);
    }
}
