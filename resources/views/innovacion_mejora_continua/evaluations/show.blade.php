@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-4">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li><a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-blue-600">Innovación y Mejora Continua</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="hover:text-blue-600">Iniciativas</a></li>
            <li><span class="mx-2">/</span></li>
            <li><a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="hover:text-blue-600">{{ Str::limit($initiative->title, 30) }}</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-800 font-medium">Evaluación</li>
        </ol>
    </nav>

    <!-- Mensajes -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información de la Iniciativa -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h2 class="text-sm font-semibold text-gray-500 uppercase mb-1">Iniciativa Evaluada</h2>
                        <h3 class="text-xl font-bold text-gray-800">
                            <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" class="hover:text-blue-600">
                                {{ $initiative->title }}
                            </a>
                        </h3>
                    </div>
                    @php
                        $statusColors = [
                            'propuesta' => 'bg-yellow-100 text-yellow-800',
                            'evaluada' => 'bg-blue-100 text-blue-800',
                            'aprobada' => 'bg-green-100 text-green-800',
                            'implementada' => 'bg-purple-100 text-purple-800',
                            'cerrada' => 'bg-gray-100 text-gray-800'
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$initiative->status] }}">
                        {{ ucfirst($initiative->status) }}
                    </span>
                </div>
            </div>

            <!-- Detalle de la Evaluación -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">Detalle de Evaluación</h1>
                        <p class="text-gray-600">Evaluación de mejoras aplicadas</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.edit', [$initiative, $evaluation]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                        <form action="{{ route('innovacion-mejora-continua.initiatives.evaluations.destroy', [$initiative, $evaluation]) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('¿Está seguro de eliminar esta evaluación? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Puntuación Destacada -->
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-6 mb-6 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-600 uppercase mb-1">Puntuación Obtenida</p>
                            <div class="flex items-baseline">
                                <span class="text-5xl font-bold text-gray-800">{{ number_format($evaluation->score, 1) }}</span>
                                <span class="text-2xl text-gray-600 ml-2">/ 10</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($evaluation->score >= $i * 2)
                                    <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @elseif($evaluation->score >= ($i * 2) - 1)
                                    <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <defs>
                                            <linearGradient id="half">
                                                <stop offset="50%" stop-color="currentColor"/>
                                                <stop offset="50%" stop-color="#D1D5DB"/>
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Barra de progreso -->
                    <div class="mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ ($evaluation->score / 10) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Resumen -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Resumen de la Evaluación</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $evaluation->summary }}</p>
                    </div>
                </div>

                <!-- Documento adjunto -->
                @if($evaluation->report_document_version_id)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-800">Documento de Reporte Adjunto</p>
                            <p class="text-xs text-blue-700">ID: {{ $evaluation->report_document_version_id }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Barra Lateral -->
        <div class="space-y-6">
            <!-- Información del Evaluador -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Información de la Evaluación</h3>
                
                <div class="space-y-4">
                    <!-- Evaluador -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Evaluador</p>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-800 font-medium">{{ $evaluation->evaluator->name }}</p>
                                <p class="text-xs text-gray-600">{{ $evaluation->evaluator->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha de Evaluación -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Fecha de Evaluación</p>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-gray-800 font-medium">{{ $evaluation->evaluation_date->format('d/m/Y') }}</p>
                                <p class="text-xs text-gray-600">{{ $evaluation->evaluation_date->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Creada -->
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Registro Creado</p>
                        <p class="text-sm text-gray-800">{{ $evaluation->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Acciones</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}" 
                       class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition duration-200">
                        Ver Iniciativa
                    </a>
                    <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}" 
                       class="block w-full text-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition duration-200">
                        Nueva Evaluación
                    </a>
                </div>
            </div>

            <!-- Contexto de la Iniciativa -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Contexto</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Responsable</p>
                        <p class="font-medium text-gray-800">{{ $initiative->responsibleUser->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Estado de Iniciativa</p>
                        <p class="font-medium text-gray-800">{{ ucfirst($initiative->status) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total de Evaluaciones</p>
                        <p class="font-medium text-gray-800">{{ $initiative->evaluations->count() }}</p>
                    </div>
                    @if($initiative->evaluations->count() > 1)
                    <div>
                        <p class="text-gray-600">Puntuación Promedio</p>
                        <p class="font-medium text-gray-800">{{ number_format($initiative->evaluations->avg('score'), 2) }} / 10</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
