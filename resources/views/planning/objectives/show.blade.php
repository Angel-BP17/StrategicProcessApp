@extends('layouts.app')
@section('title', 'Objetivo')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">Objetivo: {{ $objective->title }}</h1>
                <p class="text-sm text-slate-400">Plan: {{ $objective->plan->title }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('planning.plans.show', ['plan' => $objective->plan_id]) }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">← Volver al plan</a>
                @can('objective.manage')
                    <a href="{{ route('planning.objectives.edit', ['plan' => $objective->plan_id, 'objective' => $objective->id]) }}"
                        class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-5 py-2 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">Editar objetivo</a>
                @endcan
            </div>
        </div>

        <div class="mb-10 rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
            <h2 class="text-lg font-semibold text-slate-100">Descripción</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-300">{{ $objective->description ?? 'Este objetivo aún no cuenta con una descripción detallada.' }}</p>
            <div class="mt-4 flex flex-wrap items-center gap-4 text-xs font-medium text-slate-400">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-emerald-200">
                    Meta: {{ $objective->goal_value ?? '—' }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-sky-400/30 bg-sky-500/10 px-3 py-1 text-sky-200">
                    Peso: {{ $objective->weight ?? '—' }}%
                </span>
            </div>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
            <h2 class="text-xl font-semibold text-slate-100">KPIs del objetivo</h2>
            @can('objective.manage')
                <a href="{{ route('planning.kpis.create', ['plan' => $objective->plan_id, 'objective' => $objective->id]) }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">Nuevo KPI</a>
            @endcan
        </div>

        @if ($objective->kpis->isEmpty())
            <div class="rounded-3xl border border-slate-800/60 bg-slate-950/60 p-10 text-center shadow-inner shadow-slate-950/40">
                <p class="text-sm text-slate-400">Aún no hay KPIs para este objetivo.</p>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2">
                @foreach ($objective->kpis as $kpi)
                    @php
                        $measures = $kpi->measurements->sortBy('measured_at');
                        $labels = $measures
                            ->pluck('measured_at')
                            ->map(fn($d) => \Illuminate\Support\Str::substr($d, 0, 10))
                            ->values()
                            ->all();
                        $values = $measures->pluck('value')->values()->all();
                    @endphp
                    <div class="rounded-3xl border border-slate-800/60 bg-slate-950/60 p-5 shadow-xl shadow-slate-950/40">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-4">
                            <div>
                                <div class="text-lg font-semibold text-slate-100">{{ $kpi->name }}</div>
                                <div class="text-xs text-slate-500">Meta: {{ $kpi->target_value ?? '—' }} {{ $kpi->unit }}</div>
                                <div class="text-xs text-slate-500">Frecuencia: {{ $kpi->frequency ?? '—' }}</div>
                            </div>
                            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                                <a class="rounded-full border border-sky-400/40 px-3 py-1 text-sky-200 hover:border-sky-300 hover:text-sky-100"
                                    href="{{ route('planning.kpis.show', ['plan' => $objective->plan_id, 'objective' => $objective->id, 'kpi' => $kpi->id]) }}">Ver</a>
                                @can('objective.manage')
                                    <a class="rounded-full border border-emerald-400/40 px-3 py-1 text-emerald-200 hover:border-emerald-300 hover:text-emerald-100"
                                        href="{{ route('planning.kpis.edit', ['plan' => $objective->plan_id, 'objective' => $objective->id, 'kpi' => $kpi->id]) }}">Editar</a>
                                    <form method="POST"
                                        action="{{ route('planning.kpis.destroy', ['plan' => $objective->plan_id, 'objective' => $objective->id, 'kpi' => $kpi->id]) }}"
                                        onsubmit="return confirm('¿Eliminar KPI?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-full border border-rose-400/40 px-3 py-1 text-rose-200 hover:border-rose-300 hover:text-rose-100">Eliminar</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                        <div class="rounded-2xl border border-slate-800/50 bg-slate-900/60 p-4">
                            <x-kpi-chart :kpi="$kpi" :labels="$labels" :values="$values" :compact="true" :height="160" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
