<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\IniciativeEvaluation;

class IniciativeEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['role:planner_admin']);
        /*
        $this->middleware('permission:iniciative_evaluations.view')->only(['index', 'show']);
        $this->middleware('permission:iniciative_evaluations.create')->only(['store']);
        $this->middleware('permission:iniciative_evaluations.update')->only(['update']);
        $this->middleware('permission:iniciative_evaluations.delete')->only(['destroy']);*/
    }

    public function index()
    {
        //
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
