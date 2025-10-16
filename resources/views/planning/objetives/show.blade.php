@extends('layouts.app')
@section('content')
    <div class="container mx-auto p-4">
        @include('planning._nav')
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">Objetivo: Aumentar graduados del semestre</h1>
            <a href="{{ route('planning.objectives.edit', ['plan' => 1, 'objective' => 1]) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Editar</a>
        </div>

        <p class="mb-4 text-gray-700">Descripción del objetivo…</p>

        <h2 class="font-semibold mb-2">KPIs del objetivo</h2>
        <a href="{{ route('planning.kpis.create', ['objective' => 1]) }}"
            class="inline-block mb-3 px-3 py-1 bg-blue-600 text-white rounded">Nuevo KPI</a>

        <ul class="space-y-2">
            <li class="p-3 border rounded flex justify-between">
                <div>
                    <div class="font-semibold">Graduados del semestre (KPI-001)</div>
                    <div class="text-sm text-gray-600">Meta: 200 — Unidad: personas</div>
                </div>
                <a class="text-blue-600" href="{{ route('planning.kpis.show', ['objective' => 1, 'kpi' => 1]) }}">Ver</a>
            </li>
        </ul>
    </div>
@endsection
