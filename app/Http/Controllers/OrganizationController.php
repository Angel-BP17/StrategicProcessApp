<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use IncadevUns\CoreDomain\Models\Organization;
use Illuminate\Support\Facades\Http;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['role:planner_admin']);
        /*
        $this->middleware(['permission:organizations.view'])->only(['index', 'show']);
        $this->middleware(['permission:organizations.create'])->only(['store']);
        $this->middleware(['permission:organizations.update'])->only(['update']);
        $this->middleware(['permission:organizations.delete'])->only(['destroy']);*/
    }

    public function index()
    {
        return response()->json(Organization::query()->latest('id')->paginate(20));
    }

    public function show(Organization $organization)
    {
        return response()->json($organization);
    }

    public function store(Request $request)
{
    // Validamos solo los campos que el usuario ingresa manualmente
    $data = $request->validate([
        'ruc' => ['required', 'string', 'max:20', 'unique:organizations,ruc'],
        'contact_phone' => ['nullable', 'string', 'max:50'],
        'contact_email' => ['nullable', 'email', 'max:255'],
    ]);

    try {
        // Consulta a ApiPeru.dev para obtener name y type
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('APIPERU_TOKEN')
        ])->get("https://api.apiperu.dev/api/ruc/{$data['ruc']}");

        if ($response->failed()) {
            return response()->json(['error' => 'RUC no encontrado en la API'], 404);
        }

        $apiData = $response->json();

        // Guardamos en la tabla los campos name y type obtenidos de la API
        $data['name'] = $apiData['nombre_o_razon_social'] ?? null;
        $data['type'] = $apiData['tipo_de_sociedad'] ?? 'Otro';

        $organization = Organization::create($data);

        return response()->json($organization, 201);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al consultar la API',
            'message' => $e->getMessage()
        ], 500);
    }
}


    public function update(Request $request, Organization $organization)
{
    // Validamos los datos que el usuario puede enviar
    $data = $request->validate([
        'ruc' => ['sometimes', 'string', 'max:20', 'unique:organizations,ruc,' . $organization->id],
        'contact_phone' => ['sometimes', 'nullable', 'string', 'max:50'],
        'contact_email' => ['sometimes', 'nullable', 'email', 'max:255'],
    ]);

    // Si el usuario envía un RUC nuevo, consultamos la API para obtener name y type
    if (isset($data['ruc'])) {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('APIPERU_TOKEN')
            ])->get("https://api.apiperu.dev/api/ruc/{$data['ruc']}");

            if ($response->successful()) {
                $apiData = $response->json();
                $data['name'] = $apiData['nombre_o_razon_social'] ?? $organization->name;
                $data['type'] = $apiData['tipo_de_sociedad'] ?? $organization->type;
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al consultar la API',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Actualizamos el registro con los datos validados y la info de la API
    $organization->update($data);

    return response()->json($organization);
}


    public function destroy(Organization $organization)
{
    // Cambiar el estado de los convenios relacionados
    $organization->agreements()->update(['status' => 'inactivo']);

    // Opcional: si quieres también marcar la organización como inactiva
    $organization->update(['status' => 'inactivo']); // Necesitarías agregar columna status en organizations

    return response()->json(['message' => 'Organization and related agreements marked as inactive'], 200);
}

    public function lookupRUC($ruc)
{
    // Validar que el RUC tenga 11 dígitos
    if (!preg_match('/^\d{11}$/', $ruc)) {
        return response()->json(['error' => 'RUC inválido'], 400);
    }

    try {
        // Llamada a ApiPeru.dev usando token del .env
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('APIPERU_TOKEN')
        ])->get("https://api.apiperu.dev/api/ruc/{$ruc}");

        if ($response->failed()) {
            return response()->json(['error' => 'RUC no encontrado'], 404);
        }

        $data = $response->json();

        return response()->json([
            'ruc' => $ruc,
            'name' => $data['nombre_o_razon_social'] ?? null,
            'type' => $data['tipo_de_sociedad'] ?? 'Otro',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Error al consultar la API',
            'message' => $e->getMessage()
        ], 500);
    }
}

}