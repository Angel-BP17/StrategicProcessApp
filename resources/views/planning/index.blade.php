@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        @include('planning._nav')

        <h1 class="text-2xl font-bold mb-4">Planificación institucional</h1>
        <p class="text-gray-600 mb-6">
            Gestión de planes, objetivos, KPIs, seguimiento de metas e indicadores.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('planning.plans.index') }}" class="block p-4 border rounded hover:bg-gray-50">
                <h2 class="font-semibold">Planes de desarrollo</h2>
                <p class="text-sm text-gray-600">Crear y administrar planes.</p>
            </a>
            <a href="{{ route('planning.dashboards.index') }}" class="block p-4 border rounded hover:bg-gray-50">
                <h2 class="font-semibold">Dashboards</h2>
                <p class="text-sm text-gray-600">Visualiza KPIs y seguimiento.</p>
            </a>
            <a href="{{ route('planning.index') }}" class="block p-4 border rounded hover:bg-gray-50">
                <h2 class="font-semibold">Configuración</h2>
                <p class="text-sm text-gray-600">(Opcional) Parámetros del módulo.</p>
            </a>
        </div>
    </div>
@endsection
