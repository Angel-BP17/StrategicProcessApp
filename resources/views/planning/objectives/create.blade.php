@extends('layouts.app')
@section('title', 'Nuevo objetivo')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">Nuevo objetivo</h1>
                <p class="text-sm text-slate-400">Plan: {{ $plan->title }}</p>
            </div>
            <a href="{{ route('planning.plans.show', $plan) }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">←
                Volver al plan</a>
        </div>

        <form
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-8 shadow-2xl shadow-slate-950/40 backdrop-blur space-y-8"
            method="POST" action="{{ route('planning.objectives.store', $plan) }}">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Título</label>
                    <input name="title" value="{{ old('title') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                    @error('title')
                        <div class="mt-2 text-xs font-medium text-rose-300">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Meta (valor)</label>
                    <input type="number" step="any" name="goal_value" value="{{ old('goal_value') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Responsable</label>
                    <select name="responsible_user_id"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">
                        <option value="">— Seleccionar —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected(old('responsible_user_id') == $u->id)>{{ $u->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Peso (%)</label>
                    <input type="number" step="1" min="0" max="100" name="weight"
                        value="{{ old('weight') }}"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Descripción</label>
                    <textarea name="description" rows="3"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('planning.plans.show', $plan) }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/70 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:border-slate-500 hover:text-slate-100">Cancelar</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition hover:-translate-y-0.5">Guardar
                    objetivo</button>
            </div>
        </form>
    </div>
@endsection
