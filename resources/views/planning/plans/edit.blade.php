{{-- resources/views/planning/plans/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Editar plan')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">Editar plan</h1>
                <p class="text-sm text-slate-400">Actualiza la información clave del plan para mantenerlo vigente.</p>
            </div>
            <a href="{{ route('planning.plans.show', $plan) }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">
                ← Ver plan
            </a>
        </div>

        <form method="POST" action="{{ route('planning.plans.update', $plan) }}"
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-8 shadow-2xl shadow-slate-950/40 backdrop-blur space-y-8">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Título</label>
                    <input name="title" value="{{ old('title', $plan->title ?? '') }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                    @error('title')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Estado</label>
                    <select name="status"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">
                        @foreach (['Draft', 'Active', 'Closed'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $plan->status ?? 'Draft') == $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Inicio</label>
                    <input type="date" name="start_date"
                        value="{{ old('start_date', optional($plan->start_date)->format('Y-m-d')) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Fin</label>
                    <input type="date" name="end_date"
                        value="{{ old('end_date', optional($plan->end_date)->format('Y-m-d')) }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Descripción</label>
                    <textarea name="description" rows="4"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('description', $plan->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('planning.plans.index') }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                    Actualizar plan
                </button>
            </div>
        </form>
    </div>
@endsection
