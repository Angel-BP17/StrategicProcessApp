@extends('layouts.app')

@section('title', 'Innovación y Mejora Continua · Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-8">

        {{-- Breadcrumb --}}
        <nav class="text-xs sm:text-sm text-slate-400">
            <ol class="flex items-center gap-2">
                <li>
                    <a href="{{ route('innovacion-mejora-continua.index') }}" class="hover:text-sky-300 transition">Innovación
                        y Mejora Continua</a>
                </li>
                <li class="text-slate-600">/</li>
                <li class="text-slate-200 font-medium">Dashboard</li>
            </ol>
        </nav>

        {{-- Encabezado --}}
        <header>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white">Dashboard de Innovación y Mejora Continua</h1>
            <p class="text-slate-400 mt-1">Métricas, estadísticas y análisis del módulo</p>
        </header>

        {{-- Distribución por estado --}}
        <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/40 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Distribución de iniciativas por estado</h2>

            @if ($statusDistribution && $statusDistribution->count() > 0)
                @php
                    // Mapeo a clases estáticas (evita clases dinámicas bg-{{ }})
                    $palette = [
                        'propuesta' => [
                            'card' => 'bg-yellow-400/10',
                            'text' => 'text-yellow-300',
                            'border' => 'border-yellow-400/20',
                            'icon' => 'text-yellow-300',
                            'label' => 'Propuestas',
                            'path' =>
                                'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        ],
                        'evaluada' => [
                            'card' => 'bg-sky-400/10',
                            'text' => 'text-sky-300',
                            'border' => 'border-sky-400/20',
                            'icon' => 'text-sky-300',
                            'label' => 'Evaluadas',
                            'path' =>
                                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        ],
                        'aprobada' => [
                            'card' => 'bg-emerald-400/10',
                            'text' => 'text-emerald-300',
                            'border' => 'border-emerald-400/20',
                            'icon' => 'text-emerald-300',
                            'label' => 'Aprobadas',
                            'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                        'implementada' => [
                            'card' => 'bg-fuchsia-400/10',
                            'text' => 'text-fuchsia-300',
                            'border' => 'border-fuchsia-400/20',
                            'icon' => 'text-fuchsia-300',
                            'label' => 'Implementadas',
                            'path' =>
                                'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                        ],
                        'cerrada' => [
                            'card' => 'bg-slate-400/10',
                            'text' => 'text-slate-300',
                            'border' => 'border-slate-400/20',
                            'icon' => 'text-slate-300',
                            'label' => 'Cerradas',
                            'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach ($statusDistribution as $s)
                        @php
                            $cfg = $palette[$s->status] ?? [
                                'card' => 'bg-slate-400/10',
                                'text' => 'text-slate-300',
                                'border' => 'border-slate-400/20',
                                'icon' => 'text-slate-300',
                                'label' => ucfirst($s->status),
                                'path' => 'M12 4v16m8-8H4',
                            ];
                        @endphp
                        <div class="rounded-2xl border {{ $cfg['border'] }} {{ $cfg['card'] }} p-5 text-center">
                            <div class="flex justify-center mb-3">
                                <svg class="w-10 h-10 {{ $cfg['icon'] }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $cfg['path'] }}" />
                                </svg>
                            </div>
                            <p class="text-3xl font-semibold {{ $cfg['text'] }}">{{ $s->total }}</p>
                            <p class="text-xs mt-1 text-slate-400">{{ $cfg['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 text-slate-400">
                    <svg class="mx-auto h-12 w-12 text-slate-600 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p>No hay iniciativas registradas aún</p>
                </div>
            @endif
        </section>

        {{-- Gráficos --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/40 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Iniciativas creadas por mes</h2>
                <div class="h-64">
                    @if ($initiativesByMonth->count() > 0)
                        <canvas id="initiativesChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-slate-400">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-600 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p>No hay datos disponibles</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/40 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Evaluaciones realizadas por mes</h2>
                <div class="h-64">
                    @if ($evaluationsByMonth->count() > 0)
                        <canvas id="evaluationsChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-slate-400">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-600 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                </svg>
                                <p>No hay datos disponibles</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Top iniciativas --}}
        @if ($topInitiatives->count() > 0)
            <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/40 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Top 5 iniciativas mejor evaluadas</h2>
                <div class="space-y-3">
                    @foreach ($topInitiatives as $index => $initiative)
                        <a href="{{ route('innovacion-mejora-continua.initiatives.show', $initiative) }}"
                            class="flex items-center gap-4 p-4 rounded-xl border border-slate-800/60 bg-slate-950/40 hover:border-emerald-500/40 hover:shadow-emerald-500/20 transition">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white font-bold grid place-items-center">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-slate-100 font-medium">{{ $initiative->title }}</h3>
                                <p class="text-xs text-slate-400">Responsable:
                                    {{ $initiative->responsibleUser->name ?? 'Sin asignar' }}</p>
                            </div>
                            <div
                                class="flex items-center gap-2 bg-slate-900/60 px-3 py-1.5 rounded-lg border border-yellow-400/20">
                                <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span
                                    class="text-xl font-semibold text-slate-100">{{ number_format($initiative->avg_score, 2) }}</span>
                                <span class="text-xs text-slate-400">/ 10</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Métricas adicionales --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if ($avgEvaluationTime)
                <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/40 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Tiempo promedio de
                            evaluación</h3>
                        <svg class="w-6 h-6 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-3xl font-semibold text-white">{{ round($avgEvaluationTime) }}</p>
                    <p class="text-xs text-slate-400 mt-1">días desde la creación</p>
                </div>
            @endif

            @if ($evaluationsByMonth->count() > 0)
                <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/40 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Puntuación promedio general
                        </h3>
                        <svg class="w-6 h-6 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <p class="text-3xl font-semibold text-white">
                            {{ number_format($evaluationsByMonth->avg('avg_score'), 2) }}</p>
                        <p class="text-lg text-slate-400">/ 10</p>
                    </div>
                </div>
            @endif

            @if ($statusDistribution && $statusDistribution->sum('total') > 0)
                <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl shadow-xl shadow-slate-900/40 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Tasa de implementación</h3>
                        <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @php
                        $implemented = $statusDistribution->where('status', 'implementada')->first();
                        $implementedCount = $implemented?->total ?? 0;
                        $total = $statusDistribution->sum('total');
                        $rate = $total > 0 ? ($implementedCount / $total) * 100 : 0;
                    @endphp
                    <p class="text-3xl font-semibold text-white">{{ number_format($rate, 1) }}%</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $implementedCount }} de {{ $total }} iniciativas</p>
                </div>
            @endif
        </section>

    </div>

    {{-- Charts --}}
    @if ($initiativesByMonth->count() > 0 || $evaluationsByMonth->count() > 0)
        @push('chartjs')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // paleta consistente con el tema
                const gridColor = 'rgba(148, 163, 184, .15)'; // slate-400/15
                const tickColor = 'rgba(203, 213, 225, .8)'; // slate-300/80

                @if ($initiativesByMonth->count() > 0)
                    (() => {
                        const el = document.getElementById('initiativesChart');
                        if (!el) return;
                        new Chart(el, {
                            type: 'line',
                            data: {
                                labels: {!! json_encode($initiativesByMonth->pluck('month')) !!},
                                datasets: [{
                                    label: 'Iniciativas creadas',
                                    data: {!! json_encode($initiativesByMonth->pluck('total')) !!},
                                    borderColor: 'rgba(56, 189, 248, 1)', // sky-400
                                    backgroundColor: 'rgba(56, 189, 248, .12)',
                                    tension: .35,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: tickColor
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: tickColor,
                                            precision: 0
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    }
                                }
                            }
                        });
                    })();
                @endif

                @if ($evaluationsByMonth->count() > 0)
                    (() => {
                        const el = document.getElementById('evaluationsChart');
                        if (!el) return;
                        new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: {!! json_encode($evaluationsByMonth->pluck('month')) !!},
                                datasets: [{
                                    label: 'Evaluaciones realizadas',
                                    data: {!! json_encode($evaluationsByMonth->pluck('total')) !!},
                                    backgroundColor: 'rgba(16, 185, 129, .7)', // emerald-500/70
                                    borderColor: 'rgba(16, 185, 129, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: tickColor
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: tickColor,
                                            precision: 0
                                        },
                                        grid: {
                                            color: gridColor
                                        }
                                    }
                                }
                            }
                        });
                    })();
                @endif
            </script>
        @endpush
    @endif
@endsection
