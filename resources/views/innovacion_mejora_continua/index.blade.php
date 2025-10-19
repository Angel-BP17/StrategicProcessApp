@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Innovación y Mejora Continua</h1>
        <p class="text-gray-600">Sistema de gestión de iniciativas de innovación, implementación de nuevas metodologías y evaluación de mejoras aplicadas</p>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Iniciativas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Total Iniciativas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_initiatives'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Iniciativas Activas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Iniciativas Activas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['active_initiatives'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Iniciativas Aprobadas -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Aprobadas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['approved_initiatives'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Evaluaciones -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Total Evaluaciones</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_evaluations'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Nueva Iniciativa -->
        <a href="{{ route('innovacion-mejora-continua.initiatives.create') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">Nueva Iniciativa</h3>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <p class="text-blue-100">Registrar una nueva iniciativa de innovación o mejora continua</p>
        </a>

        <!-- Ver Todas las Iniciativas -->
        <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">Ver Iniciativas</h3>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-green-100">Listar y gestionar todas las iniciativas registradas</p>
        </a>

        <!-- Dashboard -->
        <a href="{{ route('innovacion-mejora-continua.dashboards.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">Dashboard</h3>
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-purple-100">Visualizar métricas y estadísticas del módulo</p>
        </a>
    </div>

    <!-- Distribución de Iniciativas por Estado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Distribución por Estado</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $statusConfig = [
                    'propuesta' => ['label' => 'Propuestas', 'color' => 'yellow', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    'evaluada' => ['label' => 'Evaluadas', 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    'aprobada' => ['label' => 'Aprobadas', 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'implementada' => ['label' => 'Implementadas', 'color' => 'purple', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
                    'cerrada' => ['label' => 'Cerradas', 'color' => 'gray', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z']
                ];
            @endphp

            @foreach($statusConfig as $status => $config)
                <div class="bg-{{ $config['color'] }}-50 rounded-lg p-4 text-center">
                    <div class="flex justify-center mb-2">
                        <svg class="w-8 h-8 text-{{ $config['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-{{ $config['color'] }}-600">
                        {{ $initiativesByStatus[$status] ?? 0 }}
                    </p>
                    <p class="text-sm text-{{ $config['color'] }}-800 font-medium">{{ $config['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Últimas Iniciativas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Iniciativas Recientes</h2>
                <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todas →
                </a>
            </div>
            
            @forelse($recentInitiatives as $initiative)
                <div class="border-b border-gray-200 last:border-b-0 py-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 hover:text-blue-600">
                                <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}">
                                    {{ Str::limit($initiative->title, 50) }}
                                </a>
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($initiative->summary, 80) }}</p>
                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $initiative->responsibleUser->name ?? 'Sin asignar' }}
                                </span>
                                <span>{{ $initiative->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                            @if($initiative->status === 'propuesta') bg-yellow-100 text-yellow-800
                            @elseif($initiative->status === 'evaluada') bg-blue-100 text-blue-800
                            @elseif($initiative->status === 'aprobada') bg-green-100 text-green-800
                            @elseif($initiative->status === 'implementada') bg-purple-100 text-purple-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($initiative->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <p>No hay iniciativas registradas</p>
                </div>
            @endforelse
        </div>

        <!-- Últimas Evaluaciones -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Evaluaciones Recientes</h2>
            
            @forelse($recentEvaluations as $evaluation)
                <div class="border-b border-gray-200 last:border-b-0 py-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">
                                <a href="{{ route('innovacion-mejora-continua.initiatives.show', $evaluation->initiative) }}" class="hover:text-blue-600">
                                    {{ Str::limit($evaluation->initiative->title, 50) }}
                                </a>
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($evaluation->summary, 80) }}</p>
                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $evaluation->evaluator->name }}
                                </span>
                                <span>{{ $evaluation->evaluation_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="ml-2 flex items-center">
                            <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-bold text-gray-800">{{ number_format($evaluation->score, 1) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <p>No hay evaluaciones registradas</p>
                </div>
            @endforelse

            @if($averageScore)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between bg-yellow-50 rounded-lg p-3">
                        <span class="text-sm font-medium text-gray-700">Puntuación Promedio</span>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-lg font-bold text-gray-800">{{ number_format($averageScore, 2) }}</span>
                            <span class="text-sm text-gray-600 ml-1">/ 10</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
