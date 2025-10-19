@extends('layouts.app')
@section('title', 'Plan')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">Plan: {{ $plan->title }}</h1>
                <p class="text-sm text-slate-400">{{ optional($plan->start_date)->format('Y-m-d') }} —
                    {{ optional($plan->end_date)->format('Y-m-d') }}</p>
            </div>
            <a href="{{ route('planning.plans.edit', $plan) }}"
                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">Editar plan</a>
        </div>

        <div class="mb-10 rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
            <h2 class="text-lg font-semibold text-slate-100">Descripción</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-300">{{ $plan->description ?? 'Este plan aún no tiene una descripción detallada.' }}</p>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
            <h2 class="text-xl font-semibold text-slate-100">Objetivos estratégicos</h2>
            @can('objective.manage')
                <a href="{{ route('planning.objectives.create', $plan) }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-5 py-2 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">Nuevo objetivo</a>
            @endcan
        </div>

        @if ($plan->objectives->isEmpty())
            <div class="rounded-3xl border border-slate-800/60 bg-slate-950/60 p-10 text-center shadow-inner shadow-slate-950/40">
                <p class="text-sm text-slate-400">No hay objetivos registrados para este plan.</p>
                @can('objective.manage')
                    <a href="{{ route('planning.objectives.create', $plan) }}"
                        class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">Crear objetivos</a>
                @endcan
            </div>
        @else
            <div class="space-y-5">
                @foreach ($plan->objectives as $objective)
                    <div class="rounded-3xl border border-slate-800/60 bg-slate-950/60 p-6 shadow-xl shadow-slate-950/40">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="text-lg font-semibold text-slate-100">{{ $objective->title }}</div>
                                <div class="text-sm text-slate-400">Meta: {{ $objective->goal_value }}</div>
                            </div>
                            <a class="inline-flex items-center gap-2 text-sm font-semibold text-sky-300 hover:text-sky-100"
                                href="{{ route('planning.objectives.show', [$plan, $objective]) }}">
                                Ver detalle →
                            </a>
                        </div>

                        @if ($objective->kpis->isNotEmpty())
                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                @foreach ($objective->kpis as $kpi)
                                    @php
                                        $measures = $kpi->measurements->sortBy('measured_at')->take(6);
                                        $labels = $measures
                                            ->pluck('measured_at')
                                            ->map(fn($d) => \Illuminate\Support\Str::substr($d, 0, 10));
                                        $values = $measures->pluck('value');
                                    @endphp
                                    <div class="rounded-2xl border border-slate-800/60 bg-slate-950/60 p-4">
                                        <x-kpi-chart :kpi="$kpi" :labels="$labels" :values="$values" :compact="true" :height="110" />
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4 text-sm text-slate-500">Este objetivo aún no tiene KPIs.</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
