@extends('layouts.app')
@section('title', 'Planificación institucional')
@section('content')
    <div class="container mx-auto px-0 sm:px-4">
        @include('planning._nav')
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
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

        {{-- Tabla simple (luego la rellenas con datos reales) --}}
        <div class="overflow-hidden rounded-2xl border border-slate-800/80 bg-slate-950/60 shadow-xl shadow-slate-900/50">
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
                            <td class="px-4 py-4 text-slate-300">{{ $plan->start_date->format('Y-m-d') }} —
                                {{ $plan->end_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-400/30 text-xs font-semibold">
                                    <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    {{ $plan->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <a class="text-sky-300 hover:text-sky-200 font-medium" href="{{ route('planning.plans.show', ['plan' => 1]) }}">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-500">No hay planes registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
