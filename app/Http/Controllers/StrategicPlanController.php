<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\StrategicPlan;

class StrategicPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['role:planner_admin']);
        /*
        $this->middleware('permission:strategic_plans.view')->only(['index', 'show']);
        $this->middleware('permission:strategic_plans.create')->only(['store']);
        $this->middleware('permission:strategic_plans.update')->only(['update']);
        $this->middleware('permission:strategic_plans.delete')->only(['destroy']);*/
    }

    public function index()
    {
        return response()->json(StrategicPlan::query()->withCount(['objectives', 'iniciatives'])->latest('id')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string', 'max:50'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $plan = StrategicPlan::create($data);
        /*
                $gc = new GoogleCalendarService();
                $event = $gc->createEventForPlan($plan);
                $gc->attachEventIds($plan, $event);*/

        return response()->json($plan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StrategicPlan $strategicPlan)
    {
        return response()->json(
            $strategicPlan->loadCount(['objectives', 'iniciatives'])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StrategicPlan $strategicPlan)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', 'string', 'max:50'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
        ]);

        $strategicPlan->update($data);
        /*
                $gc = new GoogleCalendarService();
                $event = $gc->updateEventForPlan($strategicPlan);

                */
        return response()->json($strategicPlan->fresh()->loadCount(['objectives', 'iniciatives']));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StrategicPlan $strategicPlan)
    {
        $gc = new GoogleCalendarService();
        $gc->deleteEventForPlan($strategicPlan);

        $strategicPlan->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}
