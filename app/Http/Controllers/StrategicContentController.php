<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Incadev\Core\Models\StrategicContent;

class StrategicContentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['permission:strategic_contents.view'])->only(['index', 'show']);
        $this->middleware(['permission:strategic_contents.create'])->only(['store']);
        $this->middleware(['permission:strategic_contents.update'])->only(['update']);
        $this->middleware(['permission:strategic_contents.delete'])->only(['destroy']);
    }

    public function index()
    {
        return response()->json(StrategicContent::query()->latest('id')->paginate(20));
    }

    public function show(StrategicContent $strategicContent)
    {
        return response()->json($strategicContent);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', Rule::in(['mission', 'vision', 'objective', 'plan'])],
            'content' => ['required', 'string'],
        ]);
        $item = StrategicContent::create($data);
        return response()->json($item, 201);
    }

    public function update(Request $request, StrategicContent $strategicContent)
    {
        $data = $request->validate([
            'type' => ['sometimes', 'string', Rule::in(['mission', 'vision', 'objective', 'plan'])],
            'content' => ['sometimes', 'string'],
        ]);
        $strategicContent->update($data);
        return response()->json($strategicContent);
    }

    public function destroy(StrategicContent $strategicContent)
    {
        $strategicContent->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}
