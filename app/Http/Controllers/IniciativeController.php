<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use IncadevUns\CoreDomain\Models\Iniciative;

class IniciativeController extends Controller
{
    private const STATUSES = [
        'propuesta',
        'en_revision',
        'aprobada',
        'rechazada',
        'en_ejecucion',
        'finalizada',
        'evaluada',
    ];

    private const TRANSITIONS = [
        'propuesta' => ['en_revision'],
        'en_revision' => ['aprobada', 'rechazada'],
        'aprobada' => ['en_ejecucion'],
        'en_ejecucion' => ['finalizada'],
        'finalizada' => ['evaluada'],
        'rechazada' => [],
        'evaluada' => [],
    ];

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);

        $q = Iniciative::query()
            ->with(['plan', 'user'])
            ->latest('id');

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        if ($request->filled('plan_id')) {
            $q->where('plan_id', $request->integer('plan_id'));
        }

        if ($request->filled('user_id')) {
            $q->where('user_id', $request->integer('user_id'));
        }

        return response()->json($q->paginate($perPage));
    }

    public function show(Iniciative $iniciative)
    {
        return response()->json($iniciative->load(['plan', 'user', 'evaluations']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'plan_id' => ['required', 'integer', 'exists:strategic_plans,id'],
            'summary' => ['required', 'string'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'string', Rule::in(self::STATUSES)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'estimated_impact' => ['required', 'string', 'max:50'],
        ]);

        $data['status'] = $data['status'] ?? 'propuesta';

        $ini = Iniciative::create($data);

        return response()->json($ini->load(['plan', 'user']), 201);
    }

    public function update(Request $request, Iniciative $iniciative)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'plan_id' => ['sometimes', 'integer', 'exists:strategic_plans,id'],
            'summary' => ['sometimes', 'string'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'string', Rule::in(self::STATUSES)],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'estimated_impact' => ['sometimes', 'string', 'max:50'],
        ]);

        if (array_key_exists('status', $data) && $data['status'] !== $iniciative->status) {
            $allowed = self::TRANSITIONS[$iniciative->status] ?? [];
            if (! in_array($data['status'], $allowed, true)) {
                return response()->json([
                    'message' => 'Transicion no permitida',
                    'from' => $iniciative->status,
                    'to' => $data['status'],
                ], 422);
            }
        }

        $iniciative->update($data);

        return response()->json($iniciative->load(['plan', 'user', 'evaluations']));
    }

    public function destroy(Iniciative $iniciative)
    {
        if ($iniciative->evaluations()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar una iniciativa con evaluaciones registradas',
            ], 422);
        }

        $iniciative->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }

    public function transition(Request $request, Iniciative $iniciative)
    {
        $data = $request->validate([
            'status' => ['required', 'string', Rule::in(self::STATUSES)],
        ]);

        $to = $data['status'];

        if ($to === $iniciative->status) {
            return response()->json($iniciative->load(['plan', 'user', 'evaluations']));
        }

        $allowed = self::TRANSITIONS[$iniciative->status] ?? [];
        if (! in_array($to, $allowed, true)) {
            return response()->json([
                'message' => 'Transicion no permitida',
                'from' => $iniciative->status,
                'to' => $to,
            ], 422);
        }

        $iniciative->update(['status' => $to]);

        return response()->json($iniciative->fresh()->load(['plan', 'user', 'evaluations']));
    }
}
