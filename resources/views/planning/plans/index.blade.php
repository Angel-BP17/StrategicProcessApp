@extends('layouts.app')
@section('title', 'Planificación institucional')
@section('content')
    <div class="container mx-auto p-4">
        @include('planning._nav')
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">Planes de desarrollo</h1>
            <a href="{{ route('planning.plans.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Nuevo plan</a>
        </div>

        {{-- Tabla simple (luego la rellenas con datos reales) --}}
        <table class="w-full text-sm border">
            <thead>
                <tr class="bg-gray-50">
                    <th class="p-2 border">Título</th>
                    <th class="p-2 border">Periodo</th>
                    <th class="p-2 border">Estado</th>
                    <th class="p-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plans as $plan)
                    <tr>
                        <td class="p-2 border">{{ $plan->title }}</td>
                        <td class="p-2 border">{{ $plan->start_date->format('Y-m-d') }} —
                            {{ $plan->end_date->format('Y-m-d') }}</td>
                        <td class="p-2 border"><span
                                class="px-2 py-1 rounded bg-green-100 text-green-700">{{ $plan->status }}</span></td>
                        <td class="p-2 border">
                            <a class="text-blue-600" href="{{ route('planning.plans.show', ['plan' => 1]) }}">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay planes registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
