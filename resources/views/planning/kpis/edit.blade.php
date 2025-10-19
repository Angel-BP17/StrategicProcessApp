@extends('layouts.app')
@section('title', 'Editar KPI')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">Editar KPI</h1>
                <p class="text-sm text-slate-400">Objetivo: {{ $objective->title }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100"
                    href="{{ route('planning.kpis.show', [$plan->id, $objective->id, $kpi->id]) }}">← Ver KPI</a>
                <a class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100"
                    href="{{ route('planning.objectives.show', [$plan->id, $objective->id]) }}">Volver al objetivo</a>
            </div>
        </div>

        <form class="rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-8 shadow-2xl shadow-slate-950/40 backdrop-blur space-y-8"
            method="POST" action="{{ route('planning.kpis.update', [$plan->id, $objective->id, $kpi->id]) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="objective_id" value="{{ $objective->id }}">

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Nombre</label>
                    <input name="name" value="{{ old('name', $kpi->name) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                    @error('name')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Unidad</label>
                    <input name="unit" value="{{ old('unit', $kpi->unit) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Meta (target)</label>
                    <input type="number" step="any" name="target_value" value="{{ old('target_value', $kpi->target_value) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Frecuencia</label>
                    <input name="frequency" value="{{ old('frequency', $kpi->frequency) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Descripción</label>
                    <textarea name="description" rows="3"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('description', $kpi->description) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('planning.kpis.show', [$plan->id, $objective->id, $kpi->id]) }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">Actualizar KPI</button>
            </div>
        </form>
    </div>
@endsection
