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
            <li class="text-gray-800 font-medium">{{ Str::limit($initiative->title, 50) }}</li>
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

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Principal -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $initiative->title }}</h1>
                        <div class="flex items-center gap-2 mb-4">
                            @php
                                $statusColors = [
                                    'propuesta' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'evaluada' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'aprobada' => 'bg-green-100 text-green-800 border-green-300',
                                    'implementada' => 'bg-purple-100 text-purple-800 border-purple-300',
                                    'cerrada' => 'bg-gray-100 text-gray-800 border-gray-300'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $statusColors[$initiative->status] }}">
                                {{ ucfirst($initiative->status) }}
                            </span>
                            <span class="text-sm text-gray-500">
                                ID: <span class="font-mono font-semibold">{{ $initiative->plan_id }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('innovacion-mejora-continua.initiatives.edit', $initiative) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                        <form action="{{ route('innovacion-mejora-continua.initiatives.destroy', $initiative) }}" method="POST" class="inline"
                              onsubmit="return confirm('¿Está seguro de eliminar esta iniciativa? Esta acción no se puede deshacer.')">
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

                <div class="space-y-4">
                    <!-- Resumen -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Resumen</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $initiative->summary }}</p>
                    </div>

                    <!-- Descripción Completa -->
                    @if($initiative->description)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Descripción Completa</h3>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-4 rounded-lg">{{ $initiative->description }}</div>
                    </div>
                    @endif

                    <!-- Impacto Estimado -->
                    @if($initiative->estimated_impact)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <h3 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Impacto Estimado
                        </h3>
                        <p class="text-blue-800">{{ $initiative->estimated_impact }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Evaluaciones -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Evaluaciones de Mejoras Aplicadas
                    </h2>
                    <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva Evaluación
                    </a>
                </div>

                @forelse($initiative->evaluations as $evaluation)
                <div class="border-b border-gray-200 last:border-b-0 py-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-xl font-bold text-gray-800">{{ number_format($evaluation->score, 1) }}</span>
                                    <span class="text-sm text-gray-600">/10</span>
                                </div>
                                <span class="text-sm text-gray-500">|</span>
                                <span class="text-sm text-gray-600">
                                    {{ $evaluation->evaluation_date->format('d/m/Y') }}
                                </span>
                            </div>
                            <p class="text-gray-700 mb-2">{{ $evaluation->summary }}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Evaluado por: <span class="font-medium ml-1">{{ $evaluation->evaluator->name }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.show', [$initiative, $evaluation]) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver
                            </a>
                            <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.edit', [$initiative, $evaluation]) }}" 
                               class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay evaluaciones registradas</h3>
                    <p class="text-gray-600 mb-4">Esta iniciativa aún no ha sido evaluada</p>
                    <a href="{{ route('innovacion-mejora-continua.initiatives.evaluations.create', $initiative) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Primera Evaluación
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Barra Lateral -->
        <div class="space-y-6">
            <!-- Información Adicional -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Información</h3>
                
                <div class="space-y-4">
                    <!-- Responsable -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Usuario Responsable</p>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-gray-800 font-medium">
                                {{ $initiative->responsibleUser->name ?? 'Sin asignar' }}
                            </span>
                        </div>
                    </div>

                    <!-- Equipo -->
                    @if($initiative->responsibleTeam)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Equipo Responsable</p>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-gray-800 font-medium">{{ $initiative->responsibleTeam->name }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Fecha de Inicio -->
                    @if($initiative->start_date)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Fecha de Inicio</p>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-800 font-medium">{{ $initiative->start_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    @endif

                    <!-- Fecha de Fin -->
                    @if($initiative->end_date)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Fecha de Fin</p>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-800 font-medium">{{ $initiative->end_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Estadísticas</h3>
                
                <div class="space-y-4">
                    <!-- Total Evaluaciones -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Evaluaciones</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $initiative->evaluations->count() }}</span>
                    </div>

                    @if($initiative->evaluations->count() > 0)
                    <!-- Puntuación Promedio -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Puntuación Promedio</span>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-2xl font-bold text-gray-800">{{ number_format($initiative->evaluations->avg('score'), 1) }}</span>
                            <span class="text-sm text-gray-600 ml-1">/10</span>
                        </div>
                    </div>

                    <!-- Última Evaluación -->
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Última Evaluación</p>
                        <p class="text-sm text-gray-800">{{ $initiative->evaluations->first()->evaluation_date->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $initiative->evaluations->first()->evaluation_date->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Metadatos -->
            <div class="bg-gray-50 rounded-lg p-4 text-xs text-gray-600 space-y-1">
                <p><strong>Creada:</strong> {{ $initiative->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Actualizada:</strong> {{ $initiative->updated_at->format('d/m/Y H:i') }}</p>
                <p class="text-gray-500">{{ $initiative->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
