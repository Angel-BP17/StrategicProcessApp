@extends('layouts.app')
@section('title', 'KPI')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">{{ $kpi->name }}</h1>
                <p class="text-sm text-slate-400">Objetivo: {{ $objective->title }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100"
                    href="{{ route('planning.objectives.show', ['plan' => $objective->plan, 'objective' => $objective]) }}">← Volver al objetivo</a>
                @can('objective.manage')
                    <a class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-5 py-2 text-xs sm:text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5"
                        href="{{ route('planning.kpis.edit', [$plan->id, $objective->id, $kpi->id]) }}">Editar KPI</a>
                @endcan
            </div>
        </div>

        <div class="rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-2xl shadow-slate-950/40 backdrop-blur">
            <p class="text-sm leading-relaxed text-slate-300">{{ $kpi->description ?? 'Aún no se ha agregado una descripción para este indicador.' }}</p>
            <div class="mt-4 flex flex-wrap gap-3 text-xs font-semibold">
                <span class="inline-flex items-center gap-2 rounded-full border border-sky-400/40 bg-sky-500/10 px-3 py-1 text-sky-200">Unidad: {{ $kpi->unit ?? '—' }}</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/40 bg-emerald-500/10 px-3 py-1 text-emerald-200">Meta: {{ $kpi->target_value ?? '—' }}</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-indigo-400/40 bg-indigo-500/10 px-3 py-1 text-indigo-200">Frecuencia: {{ $kpi->frequency ?? '—' }}</span>
            </div>
        </div>

        @can('objective.manage')
            <div class="mt-10 rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/40 backdrop-blur">
                <h2 class="text-lg font-semibold text-slate-100 mb-4">Registrar medición</h2>
                <form class="grid gap-6 md:grid-cols-4" method="POST"
                    action="{{ route('planning.kpis.measurements.store', [$plan->id, $objective->id, $kpi->id]) }}">
                    @csrf
                    <input type="hidden" name="kpi_id" value="{{ $kpi->id }}">
                    <div>
                        <label class="text-sm font-semibold text-slate-300">Fecha de medición</label>
                        <input type="date" name="measured_at" value="{{ old('measured_at', now()->toDateString()) }}"
                            class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                        @error('measured_at')
                            <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-300">Valor</label>
                        <input type="number" step="any" name="value" value="{{ old('value') }}"
                            class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                        @error('value')
                            <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-300">Fuente</label>
                        <input name="source" value="{{ old('source') }}"
                            class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                    </div>
                    <div class="flex items-end">
                        <button class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">
                            Guardar medición
                        </button>
                    </div>
                </form>
            </div>
        @endcan

        <div class="mt-10 rounded-3xl border border-slate-800/70 bg-slate-950/70 p-6 shadow-xl shadow-slate-950/40 backdrop-blur">
            <h2 class="text-lg font-semibold text-slate-100 mb-4">Historial de mediciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-slate-800/60 text-left text-slate-400 uppercase tracking-wide text-[11px] sm:text-xs">
                            <th class="py-3 pr-4 font-semibold">Fecha</th>
                            <th class="py-3 pr-4 font-semibold">Valor</th>
                            <th class="py-3 pr-4 font-semibold">Fuente</th>
                            <th class="py-3 pr-4 font-semibold">Registrado por</th>
                            <th class="py-3 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($kpi->measurements as $m)
                            <tr class="hover:bg-slate-900/60">
                                <td class="py-3 pr-4 text-slate-200">{{ \Illuminate\Support\Carbon::parse($m->measured_at)->format('Y-m-d') }}</td>
                                <td class="py-3 pr-4 text-slate-300">{{ $m->value }}</td>
                                <td class="py-3 pr-4 text-slate-400">{{ $m->source }}</td>
                                <td class="py-3 pr-4 text-slate-400">#{{ $m->recorded_by_user_id }}</td>
                                <td class="py-3 text-right">
                                    @can('objective.manage')
                                        <form method="POST"
                                            action="{{ route('planning.kpis.measurements.destroy', [$plan->id, $objective->id, $kpi->id, $m->id]) }}"
                                            onsubmit="return confirm('¿Eliminar medición?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-xs font-semibold text-rose-300 hover:text-rose-200">Eliminar</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 text-center text-slate-500" colspan="5">Aún no hay mediciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
