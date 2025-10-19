@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Criterios de Evaluación Docente') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            {{-- Título y botón --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-medium text-white">
                    Listado de Criterios de Evaluación
                </h1>
                <a href="{{ route('quality.evaluation-criteria.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                    Crear Nuevo Criterio
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-smoky-black">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre del Criterio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo de Respuesta</th>
                            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Peso (%)</th> --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-dark-purple divide-y divide-gray-700">
                        @forelse ($criteria as $criterion)
                            <tr>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-200">{{ $criterion->criterion_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ ucfirst($criterion->response_type) }}</td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ $criterion->percentage_weight ?? 'N/A' }}%</td> --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $criterion->state == 'active' ? 'bg-green-800 text-green-100' : 'bg-gray-700 text-gray-200' }}">
                                        {{ ucfirst($criterion->state ?? 'Indefinido') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('quality.evaluation-criteria.edit', $criterion) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a> {{-- Pendiente --}}
                                        {{-- FORMULARIO ELIMINAR --}}
                                        <form action="{{ route('quality.evaluation-criteria.destroy', $criterion) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700"
                                                    onclick="return confirm('¿Estás seguro de eliminar este criterio? Si tiene opciones, también se borrarán.');">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    No hay criterios de evaluación definidos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection