@extends('layouts.app')
@section('content')
    <div class="container mx-auto p-4">
        @include('planning._nav')
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">Plan: Plan Estratégico 2025</h1>
            <a href="{{ route('planning.plans.edit', ['plan' => 1]) }}"
                class="px-3 py-2 bg-gray-800 text-white rounded">Editar</a>
        </div>

        <div class="mb-6">
            <p class="text-gray-600">2025-01-01 — 2025-12-31</p>
            <p class="mt-2">Descripción del plan…</p>
        </div>

        <h2 class="font-semibold mb-2">Objetivos estratégicos</h2>
        <a href="{{ route('planning.objectives.create', ['plan' => 1]) }}"
            class="inline-block mb-3 px-3 py-1 bg-blue-600 text-white rounded">Nuevo objetivo</a>

        <ul class="space-y-2">
            <li class="p-3 border rounded flex justify-between">
                <div>
                    <div class="font-semibold">Aumentar graduados del semestre</div>
                    <div class="text-sm text-gray-600">Meta: 200</div>
                </div>
                <a class="text-blue-600" href="{{ route('planning.objectives.show', ['plan' => 1, 'objective' => 1]) }}">Ver</a>
            </li>
        </ul>
    </div>
@endsection
