@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Gestión de Encuestas') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            {{-- Título y botón --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-medium text-white">
                    Listado de Encuestas
                </h1>
                <a href="{{ route('quality.surveys.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-opacity-75">
                    Crear Nueva Encuesta
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-smoky-black">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dirigido a</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Fecha Creación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-dark-purple divide-y divide-gray-700">
                        {{-- Usamos la variable $surveys que viene del controlador --}}
                        @forelse ($surveys as $survey)
                            <tr>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-200">{{ $survey->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">{{ ucfirst($survey->target_type) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{-- Badge de estado --}}
                                    <span @class([
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        'bg-yellow-800 text-yellow-100' => $survey->status == 'draft',
                                        'bg-green-800 text-green-100' => $survey->status == 'active',
                                        'bg-gray-700 text-gray-200' => $survey->status == 'closed',
                                    ])>
                                        {{ ucfirst($survey->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    {{ $survey->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('quality.surveys.assign.show', $survey) }}" class="text-blue-500 hover:text-blue-700">Asignar</a>
                                        <a href="{{ route('quality.surveys.design', $survey) }}" class="text-primary hover:text-opacity-75">Diseñar</a>
                                        <a href="{{ route('quality.surveys.edit', $survey) }}" class="text-yellow-500 hover:text-yellow-700">Editar</a> 
                                        {{-- FORMULARIO ELIMINAR --}}
                                        <form action="{{ route('quality.surveys.destroy', $survey) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta encuesta? Se borrarán todas sus preguntas y respuestas.');">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    No hay encuestas creadas.
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