@extends('layouts.app')
@section('title', 'Nuevo plan')
@section('content')
    <div class="container mx-auto px-4">
        @include('planning._nav')

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Planificación institucional</p>
                <h1 class="text-3xl font-semibold text-slate-100">Crear nuevo plan</h1>
            </div>
            <a href="{{ route('planning.plans.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">
                ← Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-500/40 bg-rose-500/10 px-5 py-4 text-sm text-rose-200 shadow-lg shadow-rose-950/30">
                <p class="font-semibold">Por favor corrige los siguientes campos:</p>
                <ul class="mt-2 space-y-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('planning.plans.store') }}"
            class="rounded-3xl border border-slate-800/70 bg-slate-950/70 px-6 py-8 shadow-2xl shadow-slate-950/40 backdrop-blur">
            @csrf
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Título</label>
                    <input name="title" value="{{ old('title') }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Inicio</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-300">Fin</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40" />
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-slate-300">Descripción</label>
                    <textarea name="description" rows="4"
                        class="mt-2 w-full rounded-2xl border border-slate-800/60 bg-slate-900/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/40">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-fuchsia-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 transition hover:-translate-y-0.5">
                    Guardar plan
                </button>
            </div>
        </form>
    </div>
@endsection
