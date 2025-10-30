@extends('layouts.app')
@section('title', 'Innovación y Mejora Continua')
@section('content')
    <div class="container mx-auto px-4 py-8 text-slate-100">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-semibold text-white mb-2">Innovación y Mejora Continua</h1>
            <p class="text-sm text-slate-400">Sistema de gestión de iniciativas de innovación, implementación de nuevas
                metodologías y evaluación de mejoras aplicadas</p>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Iniciativas -->
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-medium mb-2 uppercase tracking-wider">Total Iniciativas</p>
                        <p class="text-3xl font-semibold text-white">{{ $stats['total_initiatives'] }}</p>
                    </div>
                    <div class="bg-sky-500/10 border border-sky-400/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Iniciativas Activas -->
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-medium mb-2 uppercase tracking-wider">Iniciativas Activas</p>
                        <p class="text-3xl font-semibold text-white">{{ $stats['active_initiatives'] }}</p>
                    </div>
                    <div class="bg-emerald-500/10 border border-emerald-400/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Iniciativas Aprobadas -->
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-medium mb-2 uppercase tracking-wider">Aprobadas</p>
                        <p class="text-3xl font-semibold text-white">{{ $stats['approved_initiatives'] }}</p>
                    </div>
                    <div class="bg-fuchsia-500/10 border border-fuchsia-400/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-fuchsia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Evaluaciones -->
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-xs font-medium mb-2 uppercase tracking-wider">Total Evaluaciones</p>
                        <p class="text-3xl font-semibold text-white">{{ $stats['total_evaluations'] }}</p>
                    </div>
                    <div class="bg-amber-500/10 border border-amber-400/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Nueva Iniciativa -->
            <a href="{{ route('innovacion-mejora-continua.initiatives.create') }}"
                class="bg-gradient-to-br from-sky-500 via-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-sky-500/40 p-6 text-white hover:shadow-2xl hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Nueva Iniciativa</h3>
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <p class="text-sky-100 text-sm">Registrar una nueva iniciativa de innovación o mejora continua</p>
            </a>

            <!-- Ver Todas las Iniciativas -->
            <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}"
                class="bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 rounded-2xl shadow-lg shadow-emerald-500/40 p-6 text-white hover:shadow-2xl hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Ver Iniciativas</h3>
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-emerald-100 text-sm">Listar y gestionar todas las iniciativas registradas</p>
            </a>

            <!-- Dashboard -->
            <a href="{{ route('innovacion-mejora-continua.dashboards.index') }}"
                class="bg-gradient-to-br from-fuchsia-500 via-purple-500 to-violet-500 rounded-2xl shadow-lg shadow-fuchsia-500/40 p-6 text-white hover:shadow-2xl hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold">Dashboard</h3>
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="text-fuchsia-100 text-sm">Visualizar métricas y estadísticas del módulo</p>
            </a>
        </div>

        <!-- Distribución de Iniciativas por Estado -->
        <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">Distribución por Estado</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $statusConfig = [
                        'propuesta' => [
                            'label' => 'Propuestas',
                            'wrapper' => 'bg-amber-500/10 border border-amber-400/30',
                            'icon' => 'text-amber-300',
                            'count' => 'text-amber-200',
                            'text' => 'text-amber-300',
                            'icon_path' =>
                                'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        ],
                        'evaluada' => [
                            'label' => 'Evaluadas',
                            'wrapper' => 'bg-sky-500/10 border border-sky-400/30',
                            'icon' => 'text-sky-300',
                            'count' => 'text-sky-200',
                            'text' => 'text-sky-300',
                            'icon_path' =>
                                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        ],
                        'aprobada' => [
                            'label' => 'Aprobadas',
                            'wrapper' => 'bg-emerald-500/10 border border-emerald-400/30',
                            'icon' => 'text-emerald-300',
                            'count' => 'text-emerald-200',
                            'text' => 'text-emerald-300',
                            'icon_path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                        'implementada' => [
                            'label' => 'Implementadas',
                            'wrapper' => 'bg-fuchsia-500/10 border border-fuchsia-400/30',
                            'icon' => 'text-fuchsia-300',
                            'count' => 'text-fuchsia-200',
                            'text' => 'text-fuchsia-300',
                            'icon_path' =>
                                'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                        ],
                        'cerrada' => [
                            'label' => 'Cerradas',
                            'wrapper' => 'bg-slate-500/20 border border-slate-400/30',
                            'icon' => 'text-slate-200',
                            'count' => 'text-slate-100',
                            'text' => 'text-slate-300',
                            'icon_path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                    ];
                @endphp

                @foreach ($statusConfig as $status => $config)
                    <div class="rounded-2xl p-4 text-center backdrop-blur {{ $config['wrapper'] }}">
                        <div class="flex justify-center mb-2">
                            <svg class="w-8 h-8 {{ $config['icon'] }}" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $config['icon_path'] }}" />
                            </svg>
                        </div>
                        <p class="text-2xl font-semibold {{ $config['count'] }}">
                            {{ $initiativesByStatus[$status] ?? 0 }}
                        </p>
                        <p class="text-sm font-medium {{ $config['text'] }}">{{ $config['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Últimas Iniciativas -->
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-white">Iniciativas Recientes</h2>
                    <a href="{{ route('innovacion-mejora-continua.initiatives.index') }}"
                        class="text-sky-300 hover:text-sky-200 text-sm font-medium">
                        Ver todas →
                    </a>
                </div>

                @forelse($recentInitiatives as $initiative)
                    <div class="border-b border-slate-800/70 last:border-b-0 py-3">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-white hover:text-sky-200 transition">
                                    <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}">
                                        {{ Str::limit($initiative->title, 50) }}
                                    </a>
                                </h4>
                                <p class="text-sm text-slate-400 mt-1">{{ Str::limit($initiative->summary, 80) }}</p>
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-slate-500">
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $initiative->responsibleUser->full_name ?? 'Sin asignar' }}
                                    </span>
                                    <span>{{ $initiative->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @php
                                $badgeClasses = match ($initiative->status) {
                                    'propuesta' => 'bg-amber-500/15 text-amber-300 border border-amber-400/30',
                                    'evaluada' => 'bg-sky-500/15 text-sky-300 border border-sky-400/30',
                                    'aprobada' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-400/30',
                                    'implementada' => 'bg-fuchsia-500/15 text-fuchsia-300 border border-fuchsia-400/30',
                                    default => 'bg-slate-500/20 text-slate-200 border border-slate-400/30',
                                };
                            @endphp
                            <span class="ml-2 px-3 py-1 text-xs font-semibold rounded-full {{ $badgeClasses }}">
                                {{ ucfirst($initiative->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <p>No hay iniciativas registradas</p>
                    </div>
                @endforelse
            </div>

            <!-- Últimas Evaluaciones -->
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/50 p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Evaluaciones Recientes</h2>

                @forelse($recentEvaluations as $evaluation)
                    <div class="border-b border-slate-800/70 last:border-b-0 py-3">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-white">
                                    <a href="{{ route('innovacion-mejora-continua.initiatives.show', $evaluation->initiative) }}"
                                        class="hover:text-sky-200 transition">
                                        {{ Str::limit($evaluation->initiative->title, 50) }}
                                    </a>
                                </h4>
                                <p class="text-sm text-slate-400 mt-1">{{ Str::limit($evaluation->summary, 80) }}</p>
                                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-slate-500">
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $evaluation->evaluator->name }}
                                    </span>
                                    <span>{{ $evaluation->evaluation_date->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="ml-2 flex items-center text-amber-300 gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span
                                    class="text-lg font-semibold text-white">{{ number_format($evaluation->score, 1) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <p>No hay evaluaciones registradas</p>
                    </div>
                @endforelse

                @if ($averageScore)
                    <div class="mt-4 pt-4 border-t border-slate-800/70">
                        <div
                            class="flex items-center justify-between bg-amber-500/10 border border-amber-400/30 rounded-xl p-3">
                            <span class="text-sm font-medium text-amber-200">Puntuación Promedio</span>
                            <div class="flex items-center text-white">
                                <svg class="w-5 h-5 text-amber-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-lg font-semibold">{{ number_format($averageScore, 2) }}</span>
                                <span class="text-sm text-slate-400 ml-1">/ 10</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
