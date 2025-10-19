{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="max-w-7xl mx-auto text-slate-100 space-y-10">
        {{-- Si NO hay plan, mostrar CTA para crear --}}
        @if (!$plan)
            <div
                class="relative overflow-hidden rounded-3xl border border-slate-800/70 bg-slate-950/70 shadow-2xl shadow-slate-900/60 p-10 text-center">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-sky-500/15 via-violet-500/10 to-emerald-500/20 opacity-70">
                </div>
                <div class="relative">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-400 mb-3">Planificación Estratégica</p>
                    <h2 class="text-3xl font-semibold mb-4">Aún no existe un plan estratégico activo</h2>
                    <p class="text-base text-slate-300/90 mb-6 max-w-2xl mx-auto">Crea tu primer plan para comenzar a dar
                        seguimiento a objetivos, iniciativas y métricas clave con la nueva experiencia en modo oscuro.</p>
                    @can('plan.manage')
                        <a href="{{ route('planning.plans.create') }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-sky-500/90 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 transition hover:bg-sky-400">
                            Crear plan estratégico
                        </a>
                    @endcan
                </div>
            </div>
        @else
            {{-- Resumen del plan actual --}}
            <section
                class="rounded-3xl border border-slate-800/70 bg-gradient-to-br from-slate-950/80 via-slate-900/70 to-slate-950/80 shadow-2xl shadow-slate-950/50 p-8">
                <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                    <div class="space-y-3 max-w-3xl">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Plan estratégico activo</p>
                            <h2 class="text-3xl font-semibold text-white">{{ $plan->title }}</h2>
                        </div>
                        <p class="text-sm text-slate-300/90 leading-relaxed">{{ $plan->description }}</p>
                        <div class="flex flex-wrap gap-3 text-sm text-slate-300">
                            <span
                                class="inline-flex items-center gap-2 rounded-full border border-slate-700/70 bg-slate-900/70 px-4 py-1.5 text-xs uppercase tracking-widest text-slate-300/90">
                                Periodo {{ $plan->start_date }} → {{ $plan->end_date }}
                            </span>
                            @php
                                $statusStyles = [
                                    'Activo' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-400/40',
                                    'En progreso' => 'bg-amber-500/15 text-amber-300 border border-amber-400/40',
                                    'Completado' => 'bg-sky-500/15 text-sky-300 border border-sky-400/40',
                                ];
                                $statusClass =
                                    $statusStyles[$plan->status] ??
                                    'bg-slate-600/20 text-slate-200 border border-slate-500/40';
                            @endphp
                            <span
                                class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold {{ $statusClass }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $plan->status }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 md:flex-col md:items-end">
                        <a href="{{ route('planning.plans.show', $plan) }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-700/70 bg-slate-900/70 px-5 py-3 text-sm font-semibold text-slate-100 transition hover:border-sky-400/50 hover:text-sky-200">
                            Ver plan completo
                        </a>
                        @can('plan.manage')
                            <a href="{{ route('planning.plans.edit', $plan) }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-sky-500/90 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 transition hover:bg-sky-400">
                                Actualizar plan
                            </a>
                        @endcan
                    </div>
                </div>
            </section>

            {{-- Objetivos del plan --}}
            <section class="space-y-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Últimos objetivos</p>
                        <h3 class="text-2xl font-semibold text-white">Seguimiento de objetivos estratégicos</h3>
                    </div>
                    @can('objective.manage')
                        <a href="{{ route('planning.objectives.create', $plan) }}"
                            class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500/90 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:bg-emerald-400">
                            Crear nuevo objetivo
                        </a>
                    @endcan
                </div>

                @if ($objectives->isEmpty())
                    <div
                        class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-10 text-center shadow-xl shadow-slate-950/50">
                        <p class="text-slate-300/90 mb-4">Este plan aún no tiene objetivos registrados.</p>
                        @can('objective.manage')
                            <a href="{{ route('planning.objectives.create', $plan) }}"
                                class="inline-flex items-center gap-2 rounded-2xl bg-sky-500/90 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 transition hover:bg-sky-400">
                                Crear objetivos
                            </a>
                        @endcan
                    </div>
                @else
                    <div class="grid gap-5 md:grid-cols-2">
                        @foreach ($objectives as $o)
                            <article
                                class="group relative overflow-hidden rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/50 transition hover:border-sky-400/50">
                                <div
                                    class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-br from-sky-500/10 via-transparent to-emerald-500/10">
                                </div>
                                <div class="relative flex items-start justify-between gap-3">
                                    <a class="text-lg font-semibold text-white hover:text-sky-200 transition"
                                        href="{{ route('planning.objectives.show', [$o->plan_id, $o->id]) }}">{{ $o->title }}</a>
                                    <a class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 hover:text-sky-200"
                                        href="{{ route('planning.objectives.show', [$o->plan_id, $o->id]) }}">Detalles</a>
                                </div>
                                <p class="mt-3 text-sm text-slate-300/90 line-clamp-2">{{ $o->description }}</p>

                                {{-- Mini-dashboard de KPIs (última medición) --}}
                                <div class="mt-5 grid grid-cols-2 gap-4">
                                    @foreach ($o->kpis as $kpi)
                                        @php $last = $kpi->measurements->first(); @endphp
                                        <div
                                            class="rounded-2xl border border-slate-800/70 bg-slate-950/60 px-4 py-3 text-slate-200 shadow-inner shadow-slate-950/70">
                                            <div class="text-[11px] uppercase tracking-[0.2em] text-slate-400">
                                                {{ $kpi->name }}</div>
                                            <div class="mt-2 flex items-baseline gap-2 text-2xl font-semibold text-white">
                                                <span>{{ $last->value ?? '—' }}</span>
                                                <span class="text-xs font-medium text-slate-400">{{ $kpi->unit }}</span>
                                            </div>
                                            <div class="mt-2 text-[11px] text-slate-400">
                                                Meta: <span class="text-slate-200">{{ $kpi->target_value }}</span> •
                                                {{ $kpi->frequency }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif
    </div>
@endsection
