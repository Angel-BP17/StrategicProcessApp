<?php

namespace App\Http\Controllers;

use App\Models\Planning\StrategicObjective;
use App\Models\Planning\StrategicPlan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plan = StrategicPlan::latest('id')->with('dashboards')->first();

        $objectives = StrategicObjective::with(['kpis.measurements' => fn($q) => $q->limit(1)])
            ->when($plan, fn($q) => $q->where('plan_id', $plan->id))
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact('plan', 'objectives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
