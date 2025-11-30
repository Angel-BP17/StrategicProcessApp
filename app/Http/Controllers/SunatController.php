<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SunatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function consultarRuc($ruc)
    {
        // Token desde el .env
        $token = env('APIPERU_TOKEN');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'No existe el token APIPERU_TOKEN en el archivo .env'
            ], 500);
        }

        $url = "https://apiperu.dev/api/ruc/{$ruc}";

        // Enviar token correctamente en headers
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->get($url);

        // Error de conexión
        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo conectar con la API de SUNAT'
            ], 400);
        }

        $data = $response->json();

        // Validación si la API devuelve success = false
        if (!($data['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $data['message'] ?? 'RUC no válido o no encontrado'
            ], 404);
        }

        // Respuesta limpia
        return response()->json([
            'success' => true,
            'ruc' => $data['data']['ruc'],
            'nombre' => $data['data']['nombre_o_razon_social'],
            'direccion' => $data['data']['direccion_completa'] ?? null,
            'estado' => $data['data']['estado'] ?? null,
            'condicion' => $data['data']['condicion'] ?? null,
        ]);
    }
}
