@extends('layouts.app')
@section('content')
    <div class="container mx-auto p-4">
        @include('planning._nav')
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">Objetivos del plan #{{ $plan ?? 'X' }}</h1>
            <a href="{{ route('planning.objectives.create', ['plan' => $plan ?? 1]) }}"
                class="px-3 py-2 bg-blue-600 text-white rounded">Nuevo objetivo</a>
        </div>

        {{-- listado --}}
        <ul class="space-y-2">
            <li class="p-3 border rounded flex justify-between">
                <div>
                    <div class="font-semibold">Aumentar graduados del semestre</div>
                    <div class="text-sm text-gray-600">Peso: 30%</div>
                </div>
                <a class="text-blue-600"
                    href="{{ route('planning.objectives.show', ['plan' => $plan ?? 1, 'objective' => 1]) }}">Ver</a>
            </li>
        </ul>
    </div>
@endsection
