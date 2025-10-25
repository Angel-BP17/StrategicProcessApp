@extends('layouts.app')
@section('title', 'Planificación institucional')

@section('content')
    @php
        use Illuminate\Support\Carbon;
        use App\Models\Planning\StrategicPlan;
        use App\Models\Planning\StrategicObjective;
        use App\Models\Planning\Kpi;

        // Totales
        $totalPlans = StrategicPlan::count();
        $totalObjectives = StrategicObjective::count();
        $totalKpis = Kpi::count();

        // Recientes
        $recentPlans = StrategicPlan::latest()->take(5)->get();
        $recentObjectives = StrategicObjective::with('plan')->latest()->take(5)->get();

        // KPI por objetivo (para chart)
        $kpiCounts = Kpi::selectRaw('objective_id, COUNT(*) as total')
            ->whereIn('objective_id', $recentObjectives->pluck('id'))
            ->groupBy('objective_id')
            ->get()
            ->keyBy('objective_id');

        $chartObjLabels = $recentObjectives->map(fn($o) => mb_strimwidth($o->title ?? 'Obj ' . $o->id, 0, 28, '…'));
        $chartObjData = $recentObjectives->map(fn($o) => (int) ($kpiCounts[$o->id]->total ?? 0));

        // Planes por mes (6 meses)
        $from = Carbon::now()->startOfMonth()->subMonths(5);
        $plansByMonth = StrategicPlan::where('created_at', '>=', $from)
            ->selectRaw("to_char(created_at, 'YYYY-MM') as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        // Armar eje de 6 meses con ceros donde no hay datos
        $months = collect(range(0, 5))->map(
            fn($i) => Carbon::now()
                ->startOfMonth()
                ->subMonths(5 - $i)
                ->format('Y-m'),
        );
        $plansByMonthMap = $plansByMonth->keyBy('ym');
        $chartMonths = $months;
        $chartPlansData = $months->map(fn($m) => (int) ($plansByMonthMap[$m]->total ?? 0));

        $statusMap = [
            'draft' => 'draft',
            'borrador' => 'draft',
            'active' => 'active',
            'activo' => 'active',
            'activa' => 'active',
            'closed' => 'closed',
            'cerrado' => 'closed',
            'cerrada' => 'closed',
        ];

        //Chart de estados de planes

        $rawStatus = StrategicPlan::selectRaw('LOWER(status) as s, COUNT(*) as total')->groupBy('s')->get();

        $statusBuckets = ['draft' => 0, 'active' => 0, 'closed' => 0];

        foreach ($rawStatus as $row) {
            $canon = $statusMap[$row->s] ?? null;
            if ($canon) {
                $statusBuckets[$canon] += (int) $row->total;
            }
        }

        $chartStatusLabels = ['Draft', 'Active', 'Closed'];
        $chartStatusData = [$statusBuckets['draft'] ?? 0, $statusBuckets['active'] ?? 0, $statusBuckets['closed'] ?? 0];
    @endphp

    <div class="max-w-7xl mx-auto px-4 py-6 space-y-8">
        @include('planning._nav')

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Planificación institucional</p>
                <h1 class="text-2xl font-semibold text-slate-100">Planes de desarrollo</h1>
            </div>
            <a href="{{ route('planning.plans.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 text-white font-semibold shadow-lg shadow-sky-500/30 hover:shadow-indigo-500/40 transition-transform hover:-translate-y-0.5">
                <span class="hidden sm:inline">Nuevo plan</span>
                <span class="sm:hidden">Crear plan</span>
            </a>
        </div>

        {{-- Contadores --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-5 shadow-xl shadow-slate-900/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Planes</h3>
                    <svg class="w-5 h-5 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3M4 11h16M4 19h16M5 7h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-white">{{ $totalPlans }}</p>
            </div>

            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-5 shadow-xl shadow-slate-900/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">Objetivos</h3>
                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6l7 4v4l-7 4-7-4v-4l7-4z" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-white">{{ $totalObjectives }}</p>
            </div>

            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-5 shadow-xl shadow-slate-900/40">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs tracking-wide font-semibold text-slate-400 uppercase">KPIs</h3>
                    <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 3h2v14H3zm6 4h2v10H9zm6-2h2v12h-2z" />
                    </svg>
                </div>
                <p class="text-3xl font-semibold text-white">{{ $totalKpis }}</p>
            </div>
        </section>

        {{-- Listas + Charts --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Planes recientes --}}
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/40">
                <h2 class="text-lg font-semibold text-white mb-4">Planes recientes</h2>
                @if ($recentPlans->isEmpty())
                    <div class="text-center py-10 text-slate-400">No hay planes registrados aún</div>
                @else
                    <ul class="space-y-2">
                        @foreach ($recentPlans as $p)
                            <li>
                                <a href="{{ route('planning.plans.show', $p) }}"
                                    class="flex items-center justify-between gap-3 rounded-xl border border-slate-800/60 bg-slate-950/40 px-4 py-3 hover:border-sky-500/40 hover:shadow-sky-500/20 transition">
                                    <div class="min-w-0">
                                        <p class="text-slate-100 font-medium truncate">{{ $p->title }}</p>
                                        <p class="text-xs text-slate-400">
                                            {{ optional($p->start_date)->format('Y-m-d') }} —
                                            {{ optional($p->end_date)->format('Y-m-d') }}
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-400/30 text-[11px] font-semibold">
                                        <span class="size-2 rounded-full bg-emerald-400"></span>
                                        {{ $p->status ?? 'activo' }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Objetivos recientes + chart KPIs por objetivo --}}
            <div class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/40">
                <h2 class="text-lg font-semibold text-white mb-4">Objetivos recientes</h2>
                @if ($recentObjectives->isEmpty())
                    <div class="text-center py-10 text-slate-400">No hay objetivos registrados aún</div>
                @else
                    <ul class="space-y-2 mb-6">
                        @foreach ($recentObjectives as $o)
                            <li class="rounded-xl border border-slate-800/60 bg-slate-950/40 px-4 py-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-slate-100 font-medium truncate">{{ $o->title }}</p>
                                        <p class="text-xs text-slate-400">
                                            Plan: <span class="text-slate-300">{{ $o->plan->title ?? '—' }}</span>
                                        </p>
                                    </div>
                                    <span class="text-xs text-slate-400">
                                        KPIs: <span
                                            class="text-slate-200 font-semibold">{{ (int) ($kpiCounts[$o->id]->total ?? 0) }}</span>
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="h-64">
                        <canvas id="kpisByObjectiveChart"></canvas>
                    </div>
                @endif
            </div>
        </section>

        {{-- Chart Planes por mes 
        <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/40">
            <h2 class="text-lg font-semibold text-white mb-4">Planes creados por mes</h2>
            <div class="h-64">
                <canvas id="plansByMonthChart"></canvas>
            </div>
        </section> --}}

        <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-6 shadow-xl shadow-slate-900/40">
            <h2 class="text-lg font-semibold text-white mb-4">Planes por estado</h2>
            <div class="h-64">
                <canvas id="plansByStatusChart"></canvas>
            </div>
        </section>

        {{-- Listado principal (mantengo tu tabla) --}}
        <section
            class="overflow-hidden rounded-2xl border border-slate-800/80 bg-slate-950/60 shadow-xl shadow-slate-900/50">
            <table class="w-full text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-900/80 text-slate-300 uppercase tracking-wide text-[11px] sm:text-xs">
                        <th class="px-4 py-3 text-left font-semibold">Título</th>
                        <th class="px-4 py-3 text-left font-semibold">Periodo</th>
                        <th class="px-4 py-3 text-left font-semibold">Estado</th>
                        <th class="px-4 py-3 text-left font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @forelse ($plans as $plan)
                        <tr class="hover:bg-slate-900/60 transition">
                            <td class="px-4 py-4 text-slate-100">{{ $plan->title }}</td>
                            <td class="px-4 py-4 text-slate-300">{{ $plan->start_date?->format('Y-m-d') }} —
                                {{ $plan->end_date?->format('Y-m-d') }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-400/30 text-xs font-semibold">
                                    <span class="size-2 rounded-full bg-emerald-400"></span>
                                    {{ $plan->status ?? 'activo' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <a class="text-sky-300 hover:text-sky-200 font-medium"
                                    href="{{ route('planning.plans.show', $plan) }}">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">No hay planes registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

    {{-- Charts --}}
    @push('chartjs')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const gridColor = 'rgba(148, 163, 184, .15)'; // slate-400/15
            const tickColor = 'rgba(203, 213, 225, .8)'; // slate-300/80

            // KPIs por objetivo (barras)
            @if ($recentObjectives->isNotEmpty())
                (() => {
                    const el = document.getElementById('kpisByObjectiveChart');
                    if (!el) return;
                    new Chart(el, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($chartObjLabels->values()) !!},
                            datasets: [{
                                label: 'KPIs',
                                data: {!! json_encode($chartObjData->values()) !!},
                                backgroundColor: 'rgba(56, 189, 248, .7)', // sky-400/70
                                borderColor: 'rgba(56, 189, 248, 1)',
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

            // Planes por mes (línea)
            /*(() => {
                const el = document.getElementById('plansByMonthChart');
                if (!el) return;
                new Chart(el, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartMonths->values()) !!},
                        datasets: [{
                            label: 'Planes',
                            data: {!! json_encode($chartPlansData->values()) !!},
                            borderColor: 'rgba(16, 185, 129, 1)', // emerald-500
                            backgroundColor: 'rgba(16, 185, 129, .12)',
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
            })();*/

            // Planes por estado (barras)
            (() => {
                const el = document.getElementById('plansByStatusChart');
                if (!el) return;
                new Chart(el, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartStatusLabels) !!},
                        datasets: [{
                            label: 'Planes',
                            data: {!! json_encode($chartStatusData) !!},
                            backgroundColor: [
                                'rgba(250, 204, 21, .75)', // Draft (yellow-400)
                                'rgba(16, 185, 129, .75)', // Active (emerald-500)
                                'rgba(148, 163, 184, .75)' // Closed (slate-400)
                            ],
                            borderColor: [
                                'rgba(250, 204, 21, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(148, 163, 184, 1)'
                            ],
                            borderWidth: 1,
                            borderRadius: 6,
                            maxBarThickness: 48
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
        </script>
    @endpush
@endsection
