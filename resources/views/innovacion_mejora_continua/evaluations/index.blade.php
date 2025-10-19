@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-4">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li><a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-blue-600">Innovación y Mejora Continua</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="hover:text-blue-600">Iniciativas</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="hover:text-blue-600">{{ Str::limit($initiative->title, 30) }}</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800 font-medium">Evaluaciones</li>
        </ol>
    </nav>

    <!-- Encabezado -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Evaluaciones de Mejoras</h1>
            <p class="text-gray-600 mt-2">Evaluaciones registradas para: <strong>{{ $initiative->title }}</strong></p>
        </div>
        <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Evaluación
        </a>
    </div>

    <!-- Información de la Iniciativa -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">Estado de la Iniciativa</p>
                @php
                    $statusColors = [
                        'propuesta' => 'bg-yellow-100 text-yellow-800',
                        'evaluada' => 'bg-blue-100 text-blue-800',
                        'aprobada' => 'bg-green-100 text-green-800',
                        'implementada' => 'bg-purple-100 text-purple-800',
                        'cerrada' => 'bg-gray-100 text-gray-800'
                    ];
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$initiative->status] }}">
                    {{ ucfirst($initiative->status) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Responsable</p>
                <p class="font-medium text-gray-800">{{ $initiative->responsibleUser->name ?? 'Sin asignar' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total de Evaluaciones</p>
                <p class="text-2xl font-bold text-blue-600">{{ $evaluations->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Evaluaciones -->
    @if($evaluations->total() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Puntuación Promedio</p>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-gray-800">{{ number_format($evaluations->avg('score'), 2) }}</span>
                        <span class="text-lg text-gray-600 ml-1">/ 10</span>
                    </div>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Puntuación Máxima</p>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-gray-800">{{ number_format($evaluations->max('score'), 1) }}</span>
                        <span class="text-lg text-gray-600 ml-1">/ 10</span>
                    </div>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Puntuación Mínima</p>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-gray-800">{{ number_format($evaluations->min('score'), 1) }}</span>
                        <span class="text-lg text-gray-600 ml-1">/ 10</span>
                    </div>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Lista de Evaluaciones -->
    <div class="space-y-4">
        @forelse($evaluations as $evaluation)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            <!-- Puntuación -->
                            <div class="flex items-center bg-gradient-to-r from-yellow-50 to-orange-50 px-4 py-2 rounded-lg border border-yellow-200">
                                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-2xl font-bold text-gray-800">{{ number_format($evaluation->score, 1) }}</span>
                                <span class="text-sm text-gray-600 ml-1">/ 10</span>
                            </div>

                            <!-- Fecha -->
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">{{ $evaluation->evaluation_date->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <!-- Resumen -->
                        <p class="text-gray-700 mb-3 leading-relaxed">{{ Str::limit($evaluation->summary, 200) }}</p>

                        <!-- Evaluador -->
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Evaluado por: <strong class="text-gray-800">{{ $evaluation->evaluator->name }}</strong></span>
                            <span class="mx-2">•</span>
                            <span>{{ $evaluation->evaluation_date->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.show', [$initiative, $evaluation]) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
                            Ver
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay evaluaciones registradas</h3>
            <p class="text-gray-600 mb-6">Esta iniciativa aún no ha sido evaluada</p>
            <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}" 
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear Primera Evaluación
            </a>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($evaluations->hasPages())
    <div class="mt-6">
        {{ $evaluations->links() }}
    </div>
    @endif

    <!-- Botón para volver -->
    <div class="mt-6">
        <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a la Iniciativa
        </a>
    </div>
</div>
@endsection
